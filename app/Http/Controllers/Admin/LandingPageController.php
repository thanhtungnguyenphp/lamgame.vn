<?php

namespace App\Http\Controllers\Admin;

use App\Models\LandingPage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class LandingPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $pages = LandingPage::orderBy('updated_at', 'desc')->get();
        return view('admin.landing-pages.index', compact('pages'));
    }

    public function create()
    {
        $templates = LandingPage::TEMPLATES;
        return view('admin.landing-pages.create', compact('templates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:landing_pages,slug',
            'template'         => 'required|in:' . implode(',', array_keys(LandingPage::TEMPLATES)),
            'hero_title'       => 'nullable|string|max:255',
            'hero_subtitle'    => 'nullable|string|max:500',
            'hero_cta_text'    => 'nullable|string|max:100',
            'hero_cta_url'     => 'nullable|string|max:500',
            'hero_bg_color'    => 'nullable|string|max:50',
            'description'      => 'nullable|string',
            'sections'         => 'nullable|json',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
            'status'           => 'boolean',
            'start_at'         => 'nullable|date',
            'end_at'           => 'nullable|date|after_or_equal:start_at',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['sections'] = !empty($data['sections']) ? json_decode($data['sections'], true) : null;
        $data['start_at'] = !empty($data['start_at']) ? $data['start_at'] : null;
        $data['end_at'] = !empty($data['end_at']) ? $data['end_at'] : null;
        $data['author'] = auth()->guard('admin')->user()->name ?? '';
        $data['author_id'] = auth()->guard('admin')->id();

        // Handle hero background image upload
        if ($request->hasFile('hero_bg_image')) {
            $data['hero_bg_image'] = $request->file('hero_bg_image')->store('landing-pages', 'public');
        }

        if ($request->hasFile('og_image')) {
            $data['og_image'] = $request->file('og_image')->store('landing-pages', 'public');
        }

        LandingPage::create($data);

        session()->flash('success', 'Landing page đã được tạo thành công.');
        return redirect()->route('admin.landing-pages.index');
    }

    public function edit($id)
    {
        $page = LandingPage::findOrFail($id);
        $templates = LandingPage::TEMPLATES;
        return view('admin.landing-pages.edit', compact('page', 'templates'));
    }

    public function update(Request $request, $id)
    {
        $page = LandingPage::findOrFail($id);

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:landing_pages,slug,' . $id,
            'template'         => 'required|in:' . implode(',', array_keys(LandingPage::TEMPLATES)),
            'hero_title'       => 'nullable|string|max:255',
            'hero_subtitle'    => 'nullable|string|max:500',
            'hero_cta_text'    => 'nullable|string|max:100',
            'hero_cta_url'     => 'nullable|string|max:500',
            'hero_bg_color'    => 'nullable|string|max:50',
            'description'      => 'nullable|string',
            'sections'         => 'nullable|json',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
            'status'           => 'boolean',
            'start_at'         => 'nullable|date',
            'end_at'           => 'nullable|date|after_or_equal:start_at',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['sections'] = !empty($data['sections']) ? json_decode($data['sections'], true) : null;
        $data['start_at'] = !empty($data['start_at']) ? $data['start_at'] : null;
        $data['end_at'] = !empty($data['end_at']) ? $data['end_at'] : null;

        if ($request->hasFile('hero_bg_image')) {
            $data['hero_bg_image'] = $request->file('hero_bg_image')->store('landing-pages', 'public');
        }

        if ($request->hasFile('og_image')) {
            $data['og_image'] = $request->file('og_image')->store('landing-pages', 'public');
        }

        $page->update($data);

        session()->flash('success', 'Landing page đã được cập nhật.');
        return redirect()->route('admin.landing-pages.index');
    }

    public function destroy($id)
    {
        LandingPage::findOrFail($id)->delete();

        session()->flash('success', 'Landing page đã được xóa.');
        return redirect()->route('admin.landing-pages.index');
    }
}
