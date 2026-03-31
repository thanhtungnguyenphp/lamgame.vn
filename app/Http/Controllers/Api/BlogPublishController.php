<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Blog;
use Webbycrown\BlogBagisto\Models\Category;
use Webbycrown\BlogBagisto\Models\Tag;

class BlogPublishController extends Controller
{
    public function publish(Request $request)
    {
        $admin = $request->auth_admin;

        $request->validate([
            'title'       => 'required|string|max:500',
            'slug'        => 'required|string|max:500',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'category'    => 'required|string',
            'tags'        => 'nullable|array',
            'meta_title'  => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords'    => 'nullable|string',
            'published_at'     => 'nullable|date',
            'thumbnail'   => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'images'      => 'nullable|array',
            'images.*'    => 'file|mimes:jpg,jpeg,png,webp,gif,svg|max:10240',
        ]);

        // Check duplicate
        if (Blog::where('slug', $request->slug)->exists()) {
            return response()->json([
                'status'  => 'skipped',
                'message' => "Blog '{$request->slug}' already exists",
            ], 409);
        }

        $uploadedPaths = [];

        try {
            return DB::transaction(function () use ($request, $admin, &$uploadedPaths) {
                $categoryId = $this->resolveCategory($request->category);
                $tagIds = $this->resolveTags($request->tags ?? []);

                // Upload images first, rewrite URLs in description
                $description = $request->description;
                $uploadedImages = [];

                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $key => $file) {
                        $path = 'blogs/content/' . $request->slug . '/' . $file->getClientOriginalName();
                        Storage::disk('public')->put($path, file_get_contents($file));
                        $uploadedPaths[] = $path;
                        $publicUrl = '/storage/' . $path;
                        $uploadedImages[$file->getClientOriginalName()] = $publicUrl;
                    }

                    // Rewrite image references in HTML
                    foreach ($uploadedImages as $filename => $url) {
                        $escaped = preg_quote($filename, '/');
                        $description = preg_replace(
                            '/(?:\.{0,2}\/)*(?:[\w-]+\/)*images\/' . $escaped . '/',
                            $url,
                            $description
                        );
                    }
                }

                $publishAt = $request->published_at ? Carbon::parse($request->published_at) : now();

                $blog = Blog::create([
                    'name'              => $request->title,
                    'slug'              => $request->slug,
                    'short_description' => $request->short_description ?? $request->meta_description ?? '',
                    'description'       => $description,
                    'default_category'  => $categoryId,
                    'categorys'         => (string) $categoryId,
                    'tags'              => implode(',', $tagIds),
                    'author'            => $admin->name,
                    'author_id'         => $admin->id,
                    'locale'            => 'vi',
                    'channels'          => '1',
                    'status'            => $publishAt->isFuture() ? Blog::STATUS_SCHEDULED : Blog::STATUS_PUBLISHED,
                    'allow_comments'    => 1,
                    'meta_title'        => $request->meta_title ?? $request->title,
                    'meta_description'  => $request->meta_description ?? '',
                    'meta_keywords'     => $request->meta_keywords ?? '',
                    'published_at'      => $publishAt,
                ]);

                // Upload thumbnail
                if ($request->hasFile('thumbnail')) {
                    $ext = $request->file('thumbnail')->getClientOriginalExtension();
                    $storagePath = 'blogs/' . $blog->id . '/' . Str::random(40) . '.' . $ext;
                    Storage::disk('public')->put($storagePath, file_get_contents($request->file('thumbnail')));
                    $uploadedPaths[] = $storagePath;
                    $blog->update(['src' => $storagePath]);
                }

                $this->logAction($request, 'publish', $blog->slug, $blog->id);

                return response()->json([
                    'status'  => 'ok',
                    'message' => 'Blog published',
                    'data'    => [
                        'id'    => $blog->id,
                        'slug'  => $blog->slug,
                        'url'   => url('/blog/' . $blog->slug),
                        'images_uploaded' => count($uploadedImages),
                    ],
                ], 201);
            });
        } catch (\Throwable $e) {
            // Cleanup orphan files on failure
            foreach ($uploadedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $e;
        }
    }

    public function update(Request $request, string $slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        if (! $blog) {
            return response()->json(['status' => 'error', 'message' => 'Blog not found'], 404);
        }

        $request->validate([
            'title'             => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string',
            'category'          => 'nullable|string',
            'tags'              => 'nullable|array',
            'meta_title'        => 'nullable|string',
            'meta_description'  => 'nullable|string',
            'meta_keywords'     => 'nullable|string',
            'published_at'      => 'nullable|date',
            'status'            => 'nullable|string|in:draft,scheduled,published,archived',
            'thumbnail'         => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'images'            => 'nullable|array',
            'images.*'          => 'file|mimes:jpg,jpeg,png,webp,gif,svg|max:10240',
        ]);

        $uploadedPaths = [];

        try {
            return DB::transaction(function () use ($request, $blog, &$uploadedPaths) {
                $data = [];

                if ($request->filled('title'))             $data['name'] = $request->title;
                if ($request->filled('title'))             $data['meta_title'] = $request->meta_title ?? $request->title;
                if ($request->filled('short_description')) $data['short_description'] = $request->short_description;
                if ($request->filled('meta_description'))  $data['meta_description'] = $request->meta_description;
                if ($request->filled('meta_keywords'))     $data['meta_keywords'] = $request->meta_keywords;
                if ($request->filled('status'))                $data['status'] = $request->status;

                if ($request->filled('category')) {
                    $categoryId = $this->resolveCategory($request->category);
                    $data['default_category'] = $categoryId;
                    $data['categorys'] = (string) $categoryId;
                }

                if ($request->has('tags')) {
                    $data['tags'] = implode(',', $this->resolveTags($request->tags ?? []));
                }

                if ($request->filled('published_at')) {
                    $publishAt = Carbon::parse($request->published_at);
                    $data['published_at'] = $publishAt;
                    if (! $request->filled('status')) {
                        $data['status'] = $publishAt->isFuture() ? Blog::STATUS_SCHEDULED : Blog::STATUS_PUBLISHED;
                    }
                }

                // Handle description + content images
                if ($request->filled('description')) {
                    $description = $request->description;

                    if ($request->hasFile('images')) {
                        foreach ($request->file('images') as $file) {
                            $path = 'blogs/content/' . $blog->slug . '/' . $file->getClientOriginalName();
                            Storage::disk('public')->put($path, file_get_contents($file));
                            $uploadedPaths[] = $path;
                            $escaped = preg_quote($file->getClientOriginalName(), '/');
                            $description = preg_replace(
                                '/(?:\.{0,2}\/)*(?:[\w-]+\/)*images\/' . $escaped . '/',
                                '/storage/' . $path,
                                $description
                            );
                        }
                    }

                    $data['description'] = $description;
                }

                // Handle thumbnail
                if ($request->hasFile('thumbnail')) {
                    if ($blog->src) {
                        Storage::disk('public')->delete($blog->src);
                    }
                    $ext = $request->file('thumbnail')->getClientOriginalExtension();
                    $storagePath = 'blogs/' . $blog->id . '/' . Str::random(40) . '.' . $ext;
                    Storage::disk('public')->put($storagePath, file_get_contents($request->file('thumbnail')));
                    $uploadedPaths[] = $storagePath;
                    $data['src'] = $storagePath;
                }

                $blog->update($data);

                $this->logAction($request, 'update', $blog->slug, $blog->id, array_keys($data));

                return response()->json([
                    'status'  => 'ok',
                    'message' => 'Blog updated',
                    'data'    => [
                        'id'   => $blog->id,
                        'slug' => $blog->slug,
                        'url'  => url('/blog/' . $blog->slug),
                    ],
                ]);
            });
        } catch (\Throwable $e) {
            foreach ($uploadedPaths as $path) {
                Storage::disk('public')->delete($path);
            }
            throw $e;
        }
    }

    public function destroy(Request $request, string $slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        if (! $blog) {
            return response()->json(['status' => 'error', 'message' => 'Blog not found'], 404);
        }

        $blogId = $blog->id;

        // Cleanup files
        if ($blog->src) {
            Storage::disk('public')->delete($blog->src);
        }
        Storage::disk('public')->deleteDirectory('blogs/content/' . $slug);

        $blog->delete();

        $this->logAction($request, 'delete', $slug, $blogId);

        return response()->json(['status' => 'ok', 'message' => 'Blog deleted']);
    }

    public function status(Request $request)
    {
        $request->validate([
            'slugs'   => 'required|array|max:100',
            'slugs.*' => 'required|string|max:500',
        ]);

        $slugs = $request->input('slugs');
        $blogs = Blog::whereIn('slug', $slugs)->get(['slug', 'status']);

        $result = [
            'status'    => 'success',
            'published' => [],
            'pending'   => [],
            'draft'     => [],
            'scheduled' => [],
            'archived'  => [],
        ];

        $foundSlugs = [];
        foreach ($blogs as $blog) {
            $foundSlugs[] = $blog->slug;
            match ($blog->status) {
                Blog::STATUS_PUBLISHED => $result['published'][] = $blog->slug,
                Blog::STATUS_DRAFT     => $result['draft'][] = $blog->slug,
                Blog::STATUS_SCHEDULED => $result['scheduled'][] = $blog->slug,
                Blog::STATUS_ARCHIVED  => $result['archived'][] = $blog->slug,
                default                => $result['pending'][] = $blog->slug,
            };
        }

        // Slugs not found in DB go to pending
        $result['pending'] = array_merge(
            $result['pending'],
            array_values(array_diff($slugs, $foundSlugs))
        );

        return response()->json($result);
    }

    public function changeStatus(Request $request, string $slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        if (! $blog) {
            return response()->json(['status' => 'error', 'message' => 'Article not found'], 404);
        }

        $request->validate([
            'status' => 'required|string|in:draft,scheduled,published,archived',
        ], [
            'status.in' => 'Status must be one of: draft, scheduled, published, archived',
        ]);

        $newStatus = $request->status;

        // Business rules
        if ($newStatus === Blog::STATUS_SCHEDULED && (! $blog->published_at || ! $blog->published_at->isFuture())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Scheduled status requires published_at to be a future date',
            ], 422);
        }

        if ($newStatus === Blog::STATUS_PUBLISHED && ! $blog->published_at) {
            $blog->published_at = now();
        }

        if ($newStatus === Blog::STATUS_DRAFT) {
            $blog->published_at = null;
        }

        $blog->status = $newStatus;
        $blog->save();

        $this->logAction($request, 'change_status', $slug, $blog->id, ['status' => $newStatus]);

        return response()->json([
            'status' => 'success',
            'message' => "Article status updated to {$newStatus}",
        ]);
    }

    public function list(Request $request)
    {
        $request->validate([
            'page'     => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'status'   => 'nullable|string|in:draft,scheduled,published,archived',
            'category' => 'nullable|string',
            'search'   => 'nullable|string|max:200',
        ]);

        $query = Blog::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $cat = \Webbycrown\BlogBagisto\Models\Category::whereRaw('LOWER(name) = ?', [strtolower($request->category)])->first();
            if ($cat) {
                $query->where('default_category', $cat->id);
            } else {
                $query->whereRaw('0 = 1'); // no results
            }
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'LIKE', "%{$s}%")
                  ->orWhere('slug', 'LIKE', "%{$s}%");
            });
        }

        $perPage = $request->integer('per_page', 20);
        $paginated = $query->orderByDesc('updated_at')
            ->select(['slug', 'name', 'status', 'default_category', 'published_at', 'updated_at'])
            ->paginate($perPage);

        $data = $paginated->getCollection()->map(function ($blog) {
            $cat = $blog->default_category ? \Webbycrown\BlogBagisto\Models\Category::find($blog->default_category) : null;
            return [
                'slug'         => $blog->slug,
                'title'        => $blog->name,
                'status'       => $blog->status,
                'category'     => $cat?->name,
                'published_at' => $blog->published_at?->toDateString(),
                'updated_at'   => $blog->updated_at?->toIso8601String(),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => $data,
            'meta'   => [
                'current_page' => $paginated->currentPage(),
                'last_page'    => $paginated->lastPage(),
                'per_page'     => $paginated->perPage(),
                'total'        => $paginated->total(),
            ],
        ]);
    }

    public function detail(Request $request, string $slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        if (! $blog) {
            return response()->json(['status' => 'error', 'message' => 'Article not found'], 404);
        }

        $cat = $blog->default_category ? \Webbycrown\BlogBagisto\Models\Category::find($blog->default_category) : null;
        $tagIds = $blog->tags ? explode(',', $blog->tags) : [];
        $tagNames = $tagIds ? \Webbycrown\BlogBagisto\Models\Tag::whereIn('id', $tagIds)->pluck('name')->toArray() : [];

        return response()->json([
            'status' => 'success',
            'data'   => [
                'slug'              => $blog->slug,
                'title'             => $blog->name,
                'description'       => $blog->description,
                'short_description' => $blog->short_description,
                'category'          => $cat?->name,
                'tags'              => $tagNames,
                'meta_title'        => $blog->meta_title,
                'meta_description'  => $blog->meta_description,
                'meta_keywords'     => $blog->meta_keywords,
                'status'            => $blog->status,
                'published_at'      => $blog->published_at?->toDateString(),
                'thumbnail_url'     => $blog->src ? asset('storage/' . $blog->src) : null,
                'created_at'        => $blog->created_at?->toIso8601String(),
                'updated_at'        => $blog->updated_at?->toIso8601String(),
            ],
        ]);
    }

    private function logAction(Request $request, string $action, string $slug, ?int $blogId = null, ?array $changes = null): void
    {
        DB::table('blog_api_logs')->insert([
            'admin_id'   => $request->auth_admin->id,
            'action'     => $action,
            'slug'       => $slug,
            'blog_id'    => $blogId,
            'ip'         => $request->ip(),
            'changes'    => $changes ? json_encode($changes) : null,
            'created_at' => now(),
        ]);
    }

    private function resolveCategory(string $name): int
    {
        $cat = Category::whereRaw('LOWER(name) = ?', [Str::lower($name)])->first();
        if ($cat) return $cat->id;

        return Category::create([
            'name' => $name, 'slug' => Str::slug($name), 'status' => 1, 'locale' => 'vi',
        ])->id;
    }

    private function resolveTags(array $tags): array
    {
        return collect($tags)->map(function ($name) {
            $tag = Tag::whereRaw('LOWER(name) = ?', [Str::lower($name)])->first();
            if ($tag) return $tag->id;

            return Tag::create([
                'name' => $name, 'slug' => Str::slug($name), 'status' => 1, 'locale' => 'vi',
            ])->id;
        })->toArray();
    }
}
