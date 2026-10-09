@extends('layouts.master')

@php $__loc = in_array(app()->getLocale(),['vi','en','de']) ? app()->getLocale() : 'vi'; @endphp

@section('page_title', __('lamgame.learn.godot_title'))
@section('page_description', __('lamgame.learn.godot_desc'))

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "TechArticle",
    "headline": "Học Godot từ A-Z — Hướng dẫn toàn diện cho Game Developer",
    "description": "Hướng dẫn học Godot Engine từ cơ bản đến nâng cao. GDScript tutorial, best practices và source code.",
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
@includeFirst(['lamgame.pages.learn.content.godot-' . $__loc, 'lamgame.pages.learn.content.godot-vi'])
@endsection

@push('styles')
<style>
.pillar-page{padding:40px 0 80px;background:#0a0a0f;color:#f5f7fa;min-height:100vh}
.pillar-page--godot .pillar-hero{background:linear-gradient(135deg,rgba(72,133,237,.15),rgba(72,133,237,.05))}
.pillar-breadcrumb{font-size:.85rem;color:#7a8599;margin-bottom:16px}
.pillar-breadcrumb a{color:#7a8599;text-decoration:none}
.pillar-breadcrumb a:hover{color:#4885ed}
.pillar-hero{padding:48px;border-radius:16px;margin-bottom:40px;background:linear-gradient(135deg,rgba(124,92,255,.15),rgba(124,92,255,.05));border:1px solid rgba(124,92,255,.1)}
.pillar-hero h1{font-size:2.4rem;margin-bottom:16px;font-weight:800}
.pillar-hero__lead{font-size:1.15rem;color:#b7c0d1;max-width:600px;line-height:1.6}
.pillar-hero__stats{display:flex;gap:24px;margin-top:24px;flex-wrap:wrap}
.pillar-hero__stats span{background:rgba(255,255,255,.08);padding:8px 16px;border-radius:8px;font-size:.9rem}
.pillar-layout{display:grid;grid-template-columns:1fr 300px;gap:48px}
.pillar-toc{background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);border-radius:12px;padding:24px;margin-bottom:32px}
.pillar-toc h2{font-size:1.1rem;margin-bottom:16px}
.pillar-toc ol{padding-left:20px}
.pillar-toc li{margin-bottom:8px}
.pillar-toc a{color:#7a8599;text-decoration:none}
.pillar-toc a:hover{color:#4885ed}
.pillar-section{margin-bottom:48px;padding-bottom:32px;border-bottom:1px solid rgba(255,255,255,.06)}
.pillar-section h2{font-size:1.6rem;margin-bottom:20px;color:#4885ed}
.pillar-section h3{font-size:1.2rem;margin:24px 0 12px;color:#f5f7fa}
.pillar-section p,.pillar-section li{line-height:1.7;color:#b7c0d1}
.pillar-section ul,.pillar-section ol{padding-left:24px;margin:16px 0}
.pillar-section li{margin-bottom:8px}
.pillar-section a{color:#4885ed}
.pillar-highlight{background:rgba(72,133,237,.1);border-left:4px solid #4885ed;padding:20px;border-radius:0 8px 8px 0;margin:20px 0}
.pillar-highlight h4{margin-bottom:12px;color:#4885ed}
.pillar-highlight ul{margin:0;padding-left:20px}
.pillar-table{width:100%;border-collapse:collapse;margin:20px 0}
.pillar-table th,.pillar-table td{padding:12px 16px;text-align:left;border-bottom:1px solid rgba(255,255,255,.1)}
.pillar-table th{background:rgba(72,133,237,.1);color:#4885ed}
.pillar-table tr:hover{background:rgba(255,255,255,.02)}
pre{background:#111827;border-radius:8px;padding:16px;overflow-x:auto;margin:16px 0}
code{font-family:'Fira Code',monospace;font-size:.9rem;color:#f5f7fa}
.pillar-articles{display:grid;gap:16px}
.pillar-article-card{background:rgba(17,24,39,.6);border:1px solid rgba(255,255,255,.06);border-radius:12px;padding:20px}
.pillar-article-card h3{font-size:1.1rem;margin-bottom:8px}
.pillar-article-card h3 a{color:#f5f7fa;text-decoration:none}
.pillar-article-card h3 a:hover{color:#4885ed}
.pillar-article-card p{font-size:.9rem;color:#7a8599;margin:0}
.pillar-empty{background:rgba(255,255,255,.04);padding:24px;border-radius:8px;text-align:center;color:#7a8599}
.pillar-resources{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.pillar-resource{background:rgba(17,24,39,.6);border-radius:12px;padding:20px}
.pillar-resource h4{font-size:1rem;margin-bottom:12px;color:#f5f7fa}
.pillar-resource ul{list-style:none;padding:0;margin:0}
.pillar-resource li{margin-bottom:8px}
.pillar-resource a{color:#7a8599;text-decoration:none;font-size:.9rem}
.pillar-resource a:hover{color:#4885ed}
.pillar-sidebar__sticky{position:sticky;top:100px}
.pillar-cta-box{background:linear-gradient(135deg,rgba(72,133,237,.2),rgba(72,133,237,.1));border:1px solid rgba(72,133,237,.2);border-radius:12px;padding:24px;text-align:center;margin-bottom:24px}
.pillar-cta-box h3{margin-bottom:8px;font-size:1.1rem}
.pillar-cta-box p{font-size:.9rem;color:#7a8599;margin-bottom:16px}
.pillar-btn{display:inline-block;padding:12px 24px;border-radius:8px;text-decoration:none!important;font-weight:600;transition:all .3s}
.pillar-btn--primary{background:#4885ed;color:#fff!important}
.pillar-btn--primary:hover{background:#3b78e0;transform:translateY(-2px)}
.pillar-related{background:rgba(17,24,39,.6);border-radius:12px;padding:20px}
.pillar-related h4{font-size:1rem;margin-bottom:12px}
.pillar-related ul{list-style:none;padding:0;margin:0}
.pillar-related li{margin-bottom:10px}
.pillar-related a{color:#7a8599;text-decoration:none}
.pillar-related a:hover{color:#4885ed}
@media(max-width:1024px){.pillar-layout{grid-template-columns:1fr}.pillar-sidebar{display:none}.pillar-resources{grid-template-columns:1fr}}
@media(max-width:640px){.pillar-hero{padding:24px}.pillar-hero h1{font-size:1.8rem}.pillar-hero__stats{flex-direction:column;gap:8px}}
</style>
@endpush
