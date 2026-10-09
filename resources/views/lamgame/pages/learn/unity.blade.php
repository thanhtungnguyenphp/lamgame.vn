@extends('layouts.master')

@php $__loc = in_array(app()->getLocale(),['vi','en','de']) ? app()->getLocale() : 'vi'; @endphp

@section('page_title', __('lamgame.learn.unity_title'))
@section('page_description', __('lamgame.learn.unity_desc'))

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "TechArticle",
    "headline": "Học Unity từ A-Z — Hướng dẫn toàn diện cho Game Developer",
    "description": "Hướng dẫn học Unity từ cơ bản đến nâng cao. Tutorial, best practices, source code và việc làm Unity Developer tại Việt Nam.",
    "author": {
        "@type": "Organization",
        "name": "LamGame.vn"
    },
    "publisher": {
        "@type": "Organization",
        "name": "LamGame.vn",
        "url": "{{ url('/') }}"
    },
    "datePublished": "2026-01-01",
    "dateModified": "{{ now()->toISOString() }}",
    "mainEntityOfPage": "{{ url()->current() }}"
}
</script>
@endpush

@section('content')
@includeFirst(['lamgame.pages.learn.content.unity-' . $__loc, 'lamgame.pages.learn.content.unity-vi'])

<style>
.pillar-page { padding: 2rem 0; }
.pillar-hero { text-align: center; margin-bottom: 3rem; padding-bottom: 2rem; border-bottom: 1px solid #eee; }
.pillar-hero h1 { font-size: 2.5rem; margin-bottom: 1rem; }
.pillar-hero__lead { font-size: 1.25rem; color: #666; max-width: 700px; margin: 0 auto 1.5rem; }
.pillar-hero__stats { display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap; }
.pillar-hero__stats span { background: #f3f4f6; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; }

.pillar-layout { display: grid; grid-template-columns: 1fr 280px; gap: 3rem; }
@media (max-width: 900px) { .pillar-layout { grid-template-columns: 1fr; } }

.pillar-toc { background: #f9fafb; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; }
.pillar-toc h2 { font-size: 1rem; margin-bottom: 1rem; }
.pillar-toc ol { padding-left: 1.25rem; margin: 0; }
.pillar-toc li { margin-bottom: 0.5rem; }
.pillar-toc a { color: #667eea; text-decoration: none; }
.pillar-toc a:hover { text-decoration: underline; }

.pillar-section { margin-bottom: 3rem; }
.pillar-section h2 { font-size: 1.5rem; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #667eea; }
.pillar-section h3 { font-size: 1.1rem; margin: 1.5rem 0 0.75rem; }
.pillar-section p { line-height: 1.7; margin-bottom: 1rem; }
.pillar-section ul, .pillar-section ol { padding-left: 1.5rem; margin-bottom: 1rem; }
.pillar-section li { margin-bottom: 0.5rem; line-height: 1.6; }
.pillar-section code { background: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-size: 0.9em; }
.pillar-section a { color: #667eea; }

.pillar-articles { display: grid; gap: 1rem; margin-top: 1rem; }
.pillar-article-card { display: block; padding: 1rem; border: 1px solid #eee; border-radius: 8px; text-decoration: none; color: inherit; transition: all 0.2s; }
.pillar-article-card:hover { border-color: #667eea; box-shadow: 0 2px 8px rgba(102,126,234,0.1); }
.pillar-article-card h4 { margin: 0 0 0.25rem; font-size: 1rem; }
.pillar-article-card span { font-size: 0.85rem; color: #888; }

.pillar-sources { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem; }
.pillar-source-card { display: flex; flex-direction: column; border: 1px solid #eee; border-radius: 8px; overflow: hidden; text-decoration: none; color: inherit; }
.pillar-source-card img { width: 100%; height: 120px; object-fit: cover; }
.pillar-source-card > div { padding: 0.75rem; }
.pillar-source-card h4 { margin: 0 0 0.25rem; font-size: 0.9rem; }
.pillar-source-card span { font-size: 0.8rem; color: #10b981; font-weight: 600; }

.pillar-btn { display: inline-block; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 8px; text-decoration: none; font-weight: 600; margin-top: 1rem; }
.pillar-btn:hover { opacity: 0.9; }

.pillar-sidebar__card { background: #f9fafb; padding: 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; }
.pillar-sidebar__card h3 { font-size: 1rem; margin-bottom: 1rem; }
.pillar-sidebar__card ul { list-style: none; padding: 0; margin: 0; }
.pillar-sidebar__card li { margin-bottom: 0.5rem; }
.pillar-sidebar__card a { color: #667eea; text-decoration: none; }
.pillar-sidebar__card a:hover { text-decoration: underline; }

.pillar-footer { margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid #eee; color: #666; font-size: 0.9rem; }
</style>
@endsection
