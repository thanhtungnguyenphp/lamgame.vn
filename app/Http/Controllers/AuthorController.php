<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display author profile page
     */
    public function show(string $slug)
    {
        $author = Author::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get author's published articles
        $articles = $author->blogs()
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('lamgame.authors.show', [
            'author' => $author,
            'articles' => $articles,
            'page_title' => $author->name . ' — Tác giả tại LamGame.vn',
            'page_description' => $author->bio ? \Str::limit(strip_tags($author->bio), 160) : 'Xem các bài viết của ' . $author->name . ' trên LamGame.vn',
        ]);
    }

    /**
     * List all authors
     */
    public function index()
    {
        $authors = Author::where('is_active', true)
            ->withCount(['blogs' => function ($query) {
                $query->where('status', 'published');
            }])
            ->orderBy('is_staff', 'desc')
            ->orderBy('blogs_count', 'desc')
            ->paginate(20);

        return view('lamgame.authors.index', [
            'authors' => $authors,
            'page_title' => 'Đội ngũ tác giả — LamGame.vn',
            'page_description' => 'Gặp gỡ đội ngũ tác giả và chuyên gia game development tại LamGame.vn',
        ]);
    }
}
