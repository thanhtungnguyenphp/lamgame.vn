<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webbycrown\BlogBagisto\Models\Category;
use Webkul\Core\Eloquent\Repository;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;

class BlogRepository extends \Webbycrown\BlogBagisto\Repositories\BlogRepository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webbycrown\BlogBagisto\Models\Blog';
    }

    /**
     * Save blog.
     *
     * @param  array  $data
     * @return bool|\Webbycrown\BlogBagisto\Contracts\Blog
     */
    public function save(array $data)
    {
        Event::dispatch('admin.blogs.create.before', $data);

        $create_data = $data;

        if ( array_key_exists('src', $create_data) ) {
            unset($create_data['src']);
        }

        $blog = $this->create($create_data);

        $this->uploadImages($data, $blog);

        Event::dispatch('admin.blogs.create.after', $blog);

        return true;
    }

    /**
     * Update item.
     *
     * @param  array  $data
     * @param  int  $id
     * @return bool
     */
    public function updateItem(array $data, $id)
    {
        Event::dispatch('admin.blogs.update.before', $id);

        $update_data = $data;

        if ( array_key_exists('src', $update_data) ) {
            unset($update_data['src']);
        }

        $blog = $this->update($update_data, $id);

        $this->uploadImages($data, $blog);

        Event::dispatch('admin.blogs.update.after', $blog);

        return true;
    }

    /**
     * Upload category's images.
     *
     * @param  array  $data
     * @param  \Webkul\Category\Contracts\Category  $category
     * @param  string  $type
     * @return void
     */
    public function uploadImages($data, $blog, $type = 'src')
    {
        if (isset($data[$type]) && is_array($data[$type])) {
            foreach ($data[$type] as $imageId => $file) {
                if ($file instanceof UploadedFile) {
                    if (Str::contains($file->getMimeType(), 'image')) {
                        $manager = new ImageManager;
                        $image = $manager->make($file)->encode('webp');
                    }

                    $blog->{$type} = 'blogs/' . $blog->id . '/' . Str::random(40) . '.webp';
                    Storage::disk('public')->put($blog->{$type}, $image);
                    $blog->save();
                }
            }
        }
    }
    public function destroy($id)
    {
        $blogItem = $this->find($id);

        $blogItemImage = $blogItem->src;

        Storage::delete($blogItemImage);

        return $this->model->destroy($id);
    }

    /**
     * Get only active blogs.
     *
     * @return array
     */
    public function getActiveBlogs()
    {
        $locale = config('app.locale');

        $blogs = DB::table('blogs')
            ->where('published_at', '<=', Carbon::now()->format('Y-m-d'))
            ->where('status', 1)
            ->where('locale', $locale)
            ->orderBy('id', 'DESC')
            ->paginate(12);

        return $blogs;
    }

    /**
     * Get only single blogs.
     *
     * @return array
     */
    public function getSingleBlogs($id)
    {
        $blog = DB::table('blogs')
            ->whereSlug($id)
            ->where('published_at', '<=', Carbon::now()->format('Y-m-d'))
            ->where('status', 1)
            ->first();

        return $blog;
    }

    /**
     * Get only single blogs.
     *
     * @return array
     */
    public function getBlogCategories($id)
    {
        $locale = config('app.locale');

        $categoryId = DB::table('blog_categories')
            ->where('slug', $id)->first();

        $blogs = DB::table('blogs')
            ->where('published_at', '<=', Carbon::now()->format('Y-m-d'))
            ->where('default_category', $categoryId['id'])
            ->where('status', 1)
            ->where('locale', $locale)
            ->orderBy('id', 'DESC')
            ->paginate(12);

        return $blogs;
    }
}
