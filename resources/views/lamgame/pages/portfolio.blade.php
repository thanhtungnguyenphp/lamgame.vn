@extends('layouts.master')

@section('page_title', 'Capability Demos & Source Projects | LamGame Studio')
@section('page_description', 'Explore verifiable LamGame game-development demos and source projects. Each item links to its current product details and available live demo.')
@section('canonical_url', url('/portfolio'))

@push('meta')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "LamGame Capability Demos",
    "description": "Verifiable game development demos and source projects published by LamGame.",
    "url": "{{ url('/portfolio') }}"
}
</script>
@endpush

@section('content')
<div class="proof-portfolio">
    <section class="proof-hero">
        <div class="proof-container">
            <span class="proof-eyebrow">VERIFIABLE WORK</span>
            <h1>Capability Demos & Source Projects</h1>
            <p>These entries come directly from our current source-game catalog. We do not publish client names, commercial results, or testimonials without permission and supporting evidence.</p>
            <div class="proof-actions">
                <a href="{{ route('lamgame.hire') }}#contact" class="proof-btn proof-btn--primary">Discuss Your Project</a>
                <a href="{{ route('lamgame.source-game') }}" class="proof-btn">Browse Full Catalog</a>
            </div>
        </div>
    </section>

    <section class="proof-section">
        <div class="proof-container">
            <div class="proof-heading">
                <h2>Published Projects</h2>
                <p>Open a project to review its screenshots, technical details, update date, downloadable package, and demo when available.</p>
            </div>

            @if($portfolioProjects->isNotEmpty())
            <div class="proof-grid">
                @foreach($portfolioProjects as $project)
                <article class="proof-card" data-proof-project data-project-id="{{ $project['id'] }}">
                    <a href="{{ $project['url'] }}" class="proof-card__image">
                        <img src="{{ $project['image'] }}" alt="Screenshot {{ $project['name'] }}" loading="lazy">
                    </a>
                    <div class="proof-card__body">
                        <div class="proof-card__meta">
                            <span>{{ $project['engine'] }}</span>
                            @if($project['demo_url'])<span class="proof-demo">Demo available</span>@endif
                        </div>
                        <h3>{{ $project['name'] }}</h3>
                        <p>{{ Str::limit($project['description'], 130) ?: 'Open the project page for current technical details and package contents.' }}</p>
                        <div class="proof-card__actions">
                            <a href="{{ $project['url'] }}" data-proof-action="details">View Details</a>
                            @if($project['demo_url'])
                            <a href="{{ $project['demo_url'] }}" target="_blank" rel="noopener" data-proof-action="demo">Open Demo</a>
                            @endif
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
            @else
            <div class="proof-empty">No verified project entries are available yet.</div>
            @endif
        </div>
    </section>

    <section class="proof-process">
        <div class="proof-container">
            <h2>What We Share During Scoping</h2>
            <div class="proof-checks">
                <div><strong>1. Relevant evidence</strong><span>Demos, screenshots, or code samples we are allowed to disclose.</span></div>
                <div><strong>2. Delivery plan</strong><span>Scope, milestones, assumptions, timeline, and acceptance criteria.</span></div>
                <div><strong>3. Commercial terms</strong><span>Payment schedule, IP ownership, support scope, and change control.</span></div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
.proof-portfolio{background:#070b14;color:#f5f7fa;min-height:100vh}.proof-container{max-width:1180px;margin:auto;padding:0 24px}.proof-hero{padding:100px 0 76px;text-align:center;background:radial-gradient(circle at 50% 0,rgba(124,92,255,.18),transparent 55%)}.proof-eyebrow{font-size:.76rem;letter-spacing:.16em;color:#00d1ff}.proof-hero h1{font-size:clamp(2.2rem,5vw,4rem);margin:14px 0 18px}.proof-hero p{max-width:760px;margin:0 auto;color:#aab3c2;line-height:1.7}.proof-actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-top:30px}.proof-btn{padding:12px 22px;border:1px solid rgba(255,255,255,.2);border-radius:10px;color:#fff;text-decoration:none}.proof-btn--primary{background:#7c5cff;border-color:#7c5cff}.proof-section{padding:72px 0}.proof-heading{text-align:center;max-width:760px;margin:0 auto 36px}.proof-heading h2,.proof-process h2{font-size:2rem;margin-bottom:10px}.proof-heading p{color:#8e9aae}.proof-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}.proof-card{background:#111827;border:1px solid rgba(255,255,255,.08);border-radius:16px;overflow:hidden}.proof-card__image{display:block;aspect-ratio:16/9;background:#0b1020}.proof-card__image img{width:100%;height:100%;object-fit:cover}.proof-card__body{padding:20px}.proof-card__meta{display:flex;justify-content:space-between;gap:8px;color:#8e9aae;font-size:.78rem}.proof-demo{color:#42d392}.proof-card h3{font-size:1.15rem;margin:10px 0}.proof-card p{color:#9ca7b8;font-size:.9rem;line-height:1.55;min-height:68px}.proof-card__actions{display:flex;gap:14px;margin-top:16px}.proof-card__actions a{color:#9f8cff;font-weight:600;text-decoration:none}.proof-process{padding:70px 0;background:#0b1020;text-align:center}.proof-checks{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:30px;text-align:left}.proof-checks div{padding:22px;background:#111827;border-radius:14px}.proof-checks strong,.proof-checks span{display:block}.proof-checks span{color:#9ca7b8;margin-top:8px;line-height:1.55}.proof-empty{text-align:center;color:#9ca7b8;padding:50px;border:1px dashed rgba(255,255,255,.16);border-radius:14px}@media(max-width:900px){.proof-grid,.proof-checks{grid-template-columns:1fr 1fr}}@media(max-width:620px){.proof-grid,.proof-checks{grid-template-columns:1fr}.proof-hero{padding-top:70px}}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.trackRevenueEvent?.('view_portfolio', {verified_projects: {{ $portfolioProjects->count() }}}, 'portfolio-view');
    document.querySelectorAll('[data-proof-action]').forEach(function (link) {
        link.addEventListener('click', function () {
            const card = link.closest('[data-proof-project]');
            window.trackRevenueEvent?.('portfolio_project_click', {
                project_id: card?.dataset.projectId || '',
                action: link.dataset.proofAction
            });
        });
    });
});
</script>
@endpush
