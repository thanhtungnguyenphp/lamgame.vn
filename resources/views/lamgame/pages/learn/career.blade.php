@extends('layouts.master')

@php $__loc = in_array(app()->getLocale(),['vi','en','de']) ? app()->getLocale() : 'vi'; @endphp

@section('page_title', __('lamgame.learn.career_title'))
@section('page_description', __('lamgame.learn.career_desc'))

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "Game Developer Career — Lộ trình & Việc làm Game Dev Việt Nam",
    "description": "Hướng dẫn toàn diện về career game developer tại Việt Nam. Roadmap, mức lương, kỹ năng và việc làm.",
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
@includeFirst(['lamgame.pages.learn.content.career-' . $__loc, 'lamgame.pages.learn.content.career-vi'])
@endsection

@push('styles')
<style>
.pillar-page{padding:40px 0 80px;background:#0a0a0f;color:#f5f7fa;min-height:100vh}
.pillar-page--career .pillar-hero{background:linear-gradient(135deg,rgba(34,197,94,.15),rgba(34,197,94,.05))}
.pillar-breadcrumb{font-size:.85rem;color:#7a8599;margin-bottom:16px}
.pillar-breadcrumb a{color:#7a8599;text-decoration:none}
.pillar-breadcrumb a:hover{color:#22c55e}
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
.pillar-toc a:hover{color:#22c55e}
.pillar-section{margin-bottom:48px;padding-bottom:32px;border-bottom:1px solid rgba(255,255,255,.06)}
.pillar-section h2{font-size:1.6rem;margin-bottom:20px;color:#22c55e}
.pillar-section h3{font-size:1.2rem;margin:24px 0 12px;color:#f5f7fa}
.pillar-section p,.pillar-section li{line-height:1.7;color:#b7c0d1}
.pillar-section ul,.pillar-section ol{padding-left:24px;margin:16px 0}
.pillar-section li{margin-bottom:8px}
.pillar-section a{color:#22c55e}
.pillar-highlight{background:rgba(34,197,94,.1);border-left:4px solid #22c55e;padding:20px;border-radius:0 8px 8px 0;margin:20px 0}
.pillar-highlight h4{margin-bottom:12px;color:#22c55e}
.pillar-highlight ul{margin:0;padding-left:20px}
.pillar-warning{background:rgba(255,170,0,.1);border-left:4px solid #ffaa00;padding:20px;border-radius:0 8px 8px 0;margin:20px 0}
.pillar-warning h4{margin-bottom:8px;color:#ffaa00}
.pillar-warning p{margin:0;color:#b7c0d1}
.pillar-table{width:100%;border-collapse:collapse;margin:20px 0}
.pillar-table th,.pillar-table td{padding:12px 16px;text-align:left;border-bottom:1px solid rgba(255,255,255,.1)}
.pillar-table th{background:rgba(34,197,94,.1);color:#22c55e}
.pillar-table tr:hover{background:rgba(255,255,255,.02)}
.pillar-note{font-size:.85rem;color:#7a8599;font-style:italic}
.career-roles{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin:24px 0}
.career-role{background:rgba(17,24,39,.6);border-radius:12px;padding:20px}
.career-role h4{font-size:1rem;margin-bottom:12px;color:#22c55e}
.career-role ul{list-style:none;padding:0;margin:0}
.career-role li{margin-bottom:6px;font-size:.9rem;color:#b7c0d1}
.career-roadmap{display:grid;gap:16px;margin:24px 0}
.roadmap-phase{background:rgba(17,24,39,.6);border:1px solid rgba(34,197,94,.1);border-radius:12px;padding:20px}
.roadmap-phase h4{color:#22c55e;margin-bottom:12px}
.roadmap-phase ul{margin:0;padding-left:20px}
.studio-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin:24px 0}
.studio-card{background:rgba(17,24,39,.6);border-radius:12px;padding:20px}
.studio-card h4{font-size:1rem;margin-bottom:8px;color:#f5f7fa}
.studio-card p{font-size:.85rem;color:#7a8599;margin:4px 0}
.pillar-jobs{display:grid;gap:16px;margin-bottom:20px}
.pillar-job-card{background:rgba(17,24,39,.6);border:1px solid rgba(255,255,255,.06);border-radius:12px;padding:20px}
.pillar-job-card h3{font-size:1.1rem;margin-bottom:8px}
.pillar-job-card h3 a{color:#f5f7fa;text-decoration:none}
.pillar-job-card h3 a:hover{color:#22c55e}
.job-meta{font-size:.85rem;color:#7a8599}
.job-meta span{margin-right:16px}
.pillar-empty{background:rgba(255,255,255,.04);padding:24px;border-radius:8px;text-align:center;color:#7a8599}
.pillar-sidebar__sticky{position:sticky;top:100px}
.pillar-cta-box{background:linear-gradient(135deg,rgba(34,197,94,.2),rgba(34,197,94,.1));border:1px solid rgba(34,197,94,.2);border-radius:12px;padding:24px;text-align:center;margin-bottom:24px}
.pillar-cta-box h3{margin-bottom:8px;font-size:1.1rem}
.pillar-cta-box p{font-size:.9rem;color:#7a8599;margin-bottom:16px}
.pillar-btn{display:inline-block;padding:12px 24px;border-radius:8px;text-decoration:none!important;font-weight:600;transition:all .3s}
.pillar-btn--primary{background:#22c55e;color:#fff!important}
.pillar-btn--primary:hover{background:#16a34a;transform:translateY(-2px)}
.pillar-btn--outline{border:1px solid #22c55e;color:#22c55e!important;background:transparent}
.pillar-btn--outline:hover{background:rgba(34,197,94,.1)}
.pillar-related{background:rgba(17,24,39,.6);border-radius:12px;padding:20px}
.pillar-related h4{font-size:1rem;margin-bottom:12px}
.pillar-related ul{list-style:none;padding:0;margin:0}
.pillar-related li{margin-bottom:10px}
.pillar-related a{color:#7a8599;text-decoration:none}
.pillar-related a:hover{color:#22c55e}
@media(max-width:1024px){.pillar-layout{grid-template-columns:1fr}.pillar-sidebar{display:none}.career-roles,.studio-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:640px){.pillar-hero{padding:24px}.pillar-hero h1{font-size:1.8rem}.pillar-hero__stats{flex-direction:column;gap:8px}.career-roles,.studio-grid{grid-template-columns:1fr}}
</style>
@endpush
