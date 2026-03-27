<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webbycrown\BlogBagisto\Models\Blog;
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
                    'status'            => $publishAt->isFuture() ? 0 : 1,
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

    public function status(Request $request)
    {
        $request->validate([
            'slugs'   => 'required|array|max:100',
            'slugs.*' => 'required|string|max:500',
        ]);

        $slugs = $request->input('slugs');
        $existing = Blog::whereIn('slug', $slugs)->pluck('slug')->toArray();

        return response()->json([
            'status' => 'ok',
            'published' => $existing,
            'pending'   => array_values(array_diff($slugs, $existing)),
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
