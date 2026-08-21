@extends('layouts.master')

@section('page_title', 'Learn Game Development — Hướng dẫn Unity, Godot, AI cho Developer')
@section('page_description', 'Trung tâm học game development. Hướng dẫn Unity, Godot, AI tools và career roadmap cho game developer Việt Nam.')

@section('content')
<div class="learn-hub">
    <div class="container">
        {{-- Hero --}}
        <header class="learn-hero">
            <h1>🎓 Learn Game Development</h1>
            <p>Hướng dẫn toàn diện cho game developer. Từ beginner đến professional.</p>
        </header>

        {{-- Pillar Grid --}}
        <section class="learn-pillars">
            <a href="{{ route('learn.unity') }}" class="learn-pillar learn-pillar--unity">
                <div class="learn-pillar__icon">🎮</div>
                <h2>Unity Development</h2>
                <p>Game engine phổ biến nhất. Từ cơ bản đến publish game.</p>
                <ul>
                    <li>C# Programming</li>
                    <li>2D & 3D Games</li>
                    <li>Mobile Development</li>
                    <li>Performance Tips</li>
                </ul>
                <span class="learn-pillar__cta">Học Unity →</span>
            </a>

            <a href="{{ route('learn.godot') }}" class="learn-pillar learn-pillar--godot">
                <div class="learn-pillar__icon">🤖</div>
                <h2>Godot Engine</h2>
                <p>Open-source, miễn phí 100%. Lựa chọn cho indie developer.</p>
                <ul>
                    <li>GDScript Basics</li>
                    <li>2D Game Excellence</li>
                    <li>Godot 4 Features</li>
                    <li>Export & Publish</li>
                </ul>
                <span class="learn-pillar__cta">Học Godot →</span>
            </a>

            <a href="{{ route('learn.ai-game-dev') }}" class="learn-pillar learn-pillar--ai">
                <div class="learn-pillar__icon">✨</div>
                <h2>AI cho Game Dev</h2>
                <p>Tận dụng AI để tăng tốc workflow phát triển game.</p>
                <ul>
                    <li>AI Coding Assistants</li>
                    <li>AI Art Generation</li>
                    <li>NPC AI & Behavior</li>
                    <li>Workflow 2026</li>
                </ul>
                <span class="learn-pillar__cta">Khám phá AI →</span>
            </a>

            <a href="{{ route('learn.career') }}" class="learn-pillar learn-pillar--career">
                <div class="learn-pillar__icon">💼</div>
                <h2>Game Dev Career</h2>
                <p>Lộ trình, kỹ năng và việc làm game developer tại Việt Nam.</p>
                <ul>
                    <li>Career Roadmap</li>
                    <li>Salary Guide</li>
                    <li>Interview Prep</li>
                    <li>Job Listings</li>
                </ul>
                <span class="learn-pillar__cta">Xem Career →</span>
            </a>
        </section>

        {{-- Quick Links --}}
        <section class="learn-quick">
            <h2>🔗 Truy cập nhanh</h2>
            <div class="learn-quick__grid">
                <a href="{{ route('lamgame.blog') }}" class="learn-quick__link">
                    <span>📚</span> Blog & Tutorials
                </a>
                <a href="{{ route('lamgame.source-game') }}" class="learn-quick__link">
                    <span>🎮</span> Source Code
                </a>
                <a href="{{ route('lamgame.viec-lam-game') }}" class="learn-quick__link">
                    <span>💼</span> Việc làm
                </a>
                <a href="{{ route('forum.index') }}" class="learn-quick__link">
                    <span>💬</span> Forum
                </a>
                <a href="{{ route('lamgame.ai-tools') }}" class="learn-quick__link">
                    <span>🤖</span> AI Tools
                </a>
                <a href="{{ route('lamgame.cong-dong') }}" class="learn-quick__link">
                    <span>👥</span> Cộng đồng
                </a>
            </div>
        </section>
    </div>
</div>
@endsection

@push('styles')
<style>
.learn-hub{padding:60px 0 100px;background:#0a0a0f;color:#f5f7fa;min-height:100vh}
.learn-hero{text-align:center;margin-bottom:60px}
.learn-hero h1{font-size:2.8rem;margin-bottom:16px;font-weight:800}
.learn-hero p{font-size:1.2rem;color:#7a8599}
.learn-pillars{display:grid;grid-template-columns:repeat(2,1fr);gap:24px;margin-bottom:60px}
.learn-pillar{display:block;padding:32px;border-radius:16px;text-decoration:none!important;transition:all .3s;border:1px solid transparent}
.learn-pillar--unity{background:linear-gradient(135deg,rgba(124,92,255,.15),rgba(124,92,255,.05));border-color:rgba(124,92,255,.2)}
.learn-pillar--unity:hover{border-color:rgba(124,92,255,.5);transform:translateY(-4px)}
.learn-pillar--godot{background:linear-gradient(135deg,rgba(72,133,237,.15),rgba(72,133,237,.05));border-color:rgba(72,133,237,.2)}
.learn-pillar--godot:hover{border-color:rgba(72,133,237,.5);transform:translateY(-4px)}
.learn-pillar--ai{background:linear-gradient(135deg,rgba(0,209,255,.15),rgba(124,92,255,.05));border-color:rgba(0,209,255,.2)}
.learn-pillar--ai:hover{border-color:rgba(0,209,255,.5);transform:translateY(-4px)}
.learn-pillar--career{background:linear-gradient(135deg,rgba(34,197,94,.15),rgba(34,197,94,.05));border-color:rgba(34,197,94,.2)}
.learn-pillar--career:hover{border-color:rgba(34,197,94,.5);transform:translateY(-4px)}
.learn-pillar__icon{font-size:3rem;margin-bottom:16px}
.learn-pillar h2{font-size:1.5rem;margin-bottom:8px;color:#f5f7fa}
.learn-pillar p{color:#7a8599;margin-bottom:16px;font-size:.95rem}
.learn-pillar ul{list-style:none;padding:0;margin:0 0 20px}
.learn-pillar li{color:#b7c0d1;font-size:.9rem;margin-bottom:6px;padding-left:16px;position:relative}
.learn-pillar li::before{content:'→';position:absolute;left:0;color:#7a8599}
.learn-pillar__cta{display:inline-block;padding:8px 16px;background:rgba(255,255,255,.1);border-radius:6px;font-size:.9rem;font-weight:600}
.learn-pillar--unity .learn-pillar__cta{color:#7c5cff}
.learn-pillar--godot .learn-pillar__cta{color:#4885ed}
.learn-pillar--ai .learn-pillar__cta{color:#00d1ff}
.learn-pillar--career .learn-pillar__cta{color:#22c55e}
.learn-quick{text-align:center}
.learn-quick h2{font-size:1.4rem;margin-bottom:24px}
.learn-quick__grid{display:grid;grid-template-columns:repeat(6,1fr);gap:16px}
.learn-quick__link{display:flex;flex-direction:column;align-items:center;gap:8px;padding:20px;background:rgba(17,24,39,.6);border:1px solid rgba(255,255,255,.06);border-radius:12px;text-decoration:none!important;color:#b7c0d1;font-size:.9rem;transition:all .3s}
.learn-quick__link:hover{border-color:rgba(124,92,255,.3);color:#f5f7fa;transform:translateY(-2px)}
.learn-quick__link span{font-size:1.5rem}
@media(max-width:1024px){.learn-pillars{grid-template-columns:1fr}.learn-quick__grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:640px){.learn-hero h1{font-size:2rem}.learn-quick__grid{grid-template-columns:repeat(2,1fr)}}
</style>
@endpush
