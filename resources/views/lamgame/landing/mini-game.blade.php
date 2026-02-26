@extends('layouts.master')

@section('page_title', $page_title)
@section('page_description', $page_description)
@section('og_type', 'website')
@section('og_image', $page->og_image_url)
@section('twitter_card', 'summary_large_image')

@section('content')
    {{-- Hero --}}
    <section class="mg-hero">
        <div class="mg-hero__bg"></div>
        <div class="container">
            <div class="mg-hero__inner">
                @if($page->hero_title)
                    <h1 class="mg-hero__title">{{ $page->hero_title }}</h1>
                @endif
                <div class="mg-hero__divider"></div>
                @if($page->hero_subtitle)
                    <p class="mg-hero__sub">{{ $page->hero_subtitle }}</p>
                @endif

                {{-- Prizes --}}
                @if($page->getSection('prizes.items'))
                <div class="mg-prizes">
                    @foreach($page->getSection('prizes.items') as $prize)
                    <div class="mg-prize">
                        <div class="mg-prize__rank">{{ $prize['rank'] ?? '' }}</div>
                        <div class="mg-prize__value">{{ $prize['value'] ?? '' }}</div>
                        <div class="mg-prize__desc">{{ $prize['desc'] ?? '' }}</div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Champion Prediction --}}
    <section class="mg-section" id="predict">
        <div class="container">
            <h2 class="mg-section__title">🏆 Dự đoán đội vô địch M7 2026</h2>
            <p class="mg-section__desc">Chọn đội bạn tin sẽ nâng cúp. Dự đoán đúng nhận <strong>100 điểm</strong>!</p>

            @if(session('success'))
                <div class="mg-alert mg-alert--ok">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mg-alert mg-alert--err">{{ session('error') }}</div>
            @endif

            @php
                $teams = $page->getSection('teams.items', []);
                $isLoggedIn = Auth::guard('customer')->check();
                $myChampion = $isLoggedIn
                    ? \App\Models\M7Prediction::forPage($page->id)->where('user_id', Auth::guard('customer')->id())->where('type', 'champion')->value('pick')
                    : null;
            @endphp

            @if(!$isLoggedIn)
            <div class="mg-login-gate">
                <p>🔒 Đăng nhập để tham gia dự đoán và tích điểm nhận quà!</p>
                <a href="{{ route('shop.customer.session.create', ['redirect' => url()->current()]) }}" class="mg-login-btn">Đăng nhập ngay</a>
            </div>
            @endif

            <div class="mg-teams">
                @foreach($teams as $team)
                <form method="POST" action="{{ route('m7.predict') }}" class="mg-team {{ $myChampion === $team['name'] ? 'mg-team--picked' : '' }}">
                    @csrf
                    <input type="hidden" name="landing_page_id" value="{{ $page->id }}">
                    <input type="hidden" name="type" value="champion">
                    <input type="hidden" name="pick" value="{{ $team['name'] }}">
                    <div class="mg-team__flag">{{ $team['flag'] ?? '🏳️' }}</div>
                    <div class="mg-team__name">{{ $team['name'] }}</div>
                    <div class="mg-team__region">{{ $team['region'] ?? '' }}</div>
                    <button type="submit" class="mg-team__btn" @if(!$isLoggedIn) disabled @endif>
                        {{ $myChampion === $team['name'] ? '✅ Đã chọn' : 'Chọn' }}
                    </button>
                </form>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section class="mg-section mg-section--alt">
        <div class="container">
            <h2 class="mg-section__title">📋 Cách chơi</h2>
            <div class="mg-steps">
                <div class="mg-step">
                    <div class="mg-step__num">1</div>
                    <h3>Chọn đội vô địch</h3>
                    <p>Pick đội bạn tin sẽ nâng cúp M7 2026</p>
                </div>
                <div class="mg-step">
                    <div class="mg-step__num">2</div>
                    <h3>Dự đoán từng trận</h3>
                    <p>Mỗi ngày thi đấu, chọn đội thắng từng trận</p>
                </div>
                <div class="mg-step">
                    <div class="mg-step__num">3</div>
                    <h3>Tích điểm & nhận quà</h3>
                    <p>Đúng = tích điểm. Top bảng xếp hạng nhận quà!</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Upcoming Matches --}}
    @if(isset($matches) && $matches->count())
    <section class="mg-section" id="matches">
        <div class="container">
            <h2 class="mg-section__title">⚔️ Lịch thi đấu & Dự đoán</h2>
            <div class="mg-matches">
                @foreach($matches as $match)
                @php
                    $myPick = $isLoggedIn
                        ? \App\Models\M7Prediction::forPage($page->id)->where('user_id', Auth::guard('customer')->id())->where('match_id', $match->id)->where('type', 'match')->value('pick')
                        : null;
                @endphp
                <div class="mg-match {{ $match->isFinished() ? 'mg-match--done' : '' }}">
                    <div class="mg-match__round">{{ $match->round }}</div>
                    <div class="mg-match__time">{{ $match->match_at->format('d/m H:i') }}</div>
                    <div class="mg-match__vs">
                        @if($match->isUpcoming())
                        <form method="POST" action="{{ route('m7.predict') }}" class="mg-match__pick">
                            @csrf
                            <input type="hidden" name="landing_page_id" value="{{ $page->id }}">
                            <input type="hidden" name="type" value="match">
                            <input type="hidden" name="match_id" value="{{ $match->id }}">
                            <input type="hidden" name="pick" value="{{ $match->team_a }}">
                            <button type="submit" class="mg-match__team {{ $myPick === $match->team_a ? 'mg-match__team--picked' : '' }}" @if(!$isLoggedIn) disabled @endif>{{ $match->team_a }}</button>
                        </form>
                        <span class="mg-match__x">VS</span>
                        <form method="POST" action="{{ route('m7.predict') }}" class="mg-match__pick">
                            @csrf
                            <input type="hidden" name="landing_page_id" value="{{ $page->id }}">
                            <input type="hidden" name="type" value="match">
                            <input type="hidden" name="match_id" value="{{ $match->id }}">
                            <input type="hidden" name="pick" value="{{ $match->team_b }}">
                            <button type="submit" class="mg-match__team {{ $myPick === $match->team_b ? 'mg-match__team--picked' : '' }}" @if(!$isLoggedIn) disabled @endif>{{ $match->team_b }}</button>
                        </form>
                        @else
                        <span class="mg-match__team {{ $match->winner === $match->team_a ? 'mg-match__team--won' : '' }}">{{ $match->team_a }}</span>
                        <span class="mg-match__x">VS</span>
                        <span class="mg-match__team {{ $match->winner === $match->team_b ? 'mg-match__team--won' : '' }}">{{ $match->team_b }}</span>
                        @endif
                    </div>
                    @if($match->isFinished() && $myPick)
                        <div class="mg-match__result {{ $myPick === $match->winner ? 'mg-match__result--ok' : 'mg-match__result--fail' }}">
                            {{ $myPick === $match->winner ? '✅ Đúng +10đ' : '❌ Sai' }}
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Leaderboard --}}
    <section class="mg-section mg-section--alt" id="leaderboard">
        <div class="container">
            <h2 class="mg-section__title">🏅 Bảng xếp hạng</h2>
            <p class="mg-section__desc">Giải đấu bắt đầu 03/03 — Bảng xếp hạng sẽ cập nhật sau mỗi trận đấu.</p>
            <div class="mg-lb" id="mg-leaderboard">
                <div class="mg-lb__empty">Chưa có dữ liệu. Hãy là người đầu tiên dự đoán! 🎯</div>
            </div>
        </div>
    </section>

    {{-- Dynamic Sections --}}
    @if($page->sections)
        @foreach($page->sections as $key => $section)
            @if(!in_array($key, ['prizes', 'teams']))
                @include('lamgame.landing.partials.section-block', ['section' => $section])
            @endif
        @endforeach
    @endif

    {{-- Body --}}
    @if($page->description)
    <section class="lp-content">
        <div class="container">
            <div class="lp-content__body post-body">{!! $page->description !!}</div>
        </div>
    </section>
    @endif

    @push('styles')
    <style>
        /* ===== HERO ===== */
        .mg-hero {
            position: relative; min-height: 55vh;
            display: flex; align-items: center; justify-content: center;
            text-align: center; color: #fff; overflow: hidden;
        }
        .mg-hero__bg {
            position: absolute; inset: 0; z-index: 1;
            background: #0f0c29;
            background-image:
                radial-gradient(ellipse 70% 50% at 50% 0%, rgba(168,85,247,0.3), transparent),
                radial-gradient(ellipse 50% 40% at 80% 100%, rgba(245,158,11,0.2), transparent);
        }
        .mg-hero__inner { position: relative; z-index: 2; padding: 3rem 1.5rem; max-width: 700px; margin: 0 auto; }
        .mg-hero__title {
            font-size: 2.8rem; font-weight: 900; line-height: 1.1; margin: 0 0 1rem;
            background: linear-gradient(135deg, #fbbf24, #f59e0b, #fff);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .mg-hero__divider { width: 60px; height: 3px; margin: 0 auto 1.5rem; background: linear-gradient(90deg, #f59e0b, #8b5cf6); border-radius: 2px; }
        .mg-hero__sub { font-size: 1.1rem; color: rgba(255,255,255,0.85); line-height: 1.7; margin: 0; }

        /* Prizes */
        .mg-prizes { display: flex; justify-content: center; gap: 1rem; margin-top: 2rem; flex-wrap: wrap; }
        .mg-prize {
            background: rgba(255,255,255,0.08); backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.15); border-radius: 16px;
            padding: 1.2rem 1.5rem; text-align: center; min-width: 130px;
        }
        .mg-prize__rank { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.6); margin-bottom: 0.4rem; }
        .mg-prize__value { font-size: 1.4rem; font-weight: 800; color: #fbbf24; }
        .mg-prize__desc { font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-top: 0.3rem; }

        /* ===== SECTIONS ===== */
        .mg-section { padding: 4rem 0; }
        .mg-section--alt { background: #f8f6fb; }
        .mg-section__title { text-align: center; font-size: 1.8rem; font-weight: 800; color: #1e1b4b; margin-bottom: 0.75rem; }
        .mg-section__desc { text-align: center; color: #6b7280; font-size: 1rem; margin-bottom: 2.5rem; max-width: 600px; margin-left: auto; margin-right: auto; }

        /* Alert */
        .mg-alert { text-align: center; padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 2rem; font-weight: 600; }
        .mg-alert--ok { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .mg-alert--err { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* Login gate */
        .mg-login-gate {
            text-align: center; padding: 1.5rem 2rem; margin-bottom: 2rem;
            background: linear-gradient(135deg, #fef3c7, #fde68a); border-radius: 16px;
            border: 1px solid #f59e0b;
        }
        .mg-login-gate p { margin: 0 0 1rem; font-weight: 600; color: #92400e; }
        .mg-login-btn {
            display: inline-block; padding: 0.7rem 2rem;
            background: linear-gradient(135deg, #8b5cf6, #6d28d9); color: #fff;
            border-radius: 50px; font-weight: 700; text-decoration: none;
            transition: all 0.2s;
        }
        .mg-login-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(139,92,246,0.4); }
        .mg-team__btn:disabled, button[disabled].mg-match__team { opacity: 0.5; cursor: not-allowed; }

        /* ===== TEAMS GRID ===== */
        .mg-teams {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1rem; max-width: 900px; margin: 0 auto;
        }
        .mg-team {
            background: #fff; border: 2px solid #e5e7eb; border-radius: 16px;
            padding: 1.5rem 1rem; text-align: center;
            transition: all 0.25s;
        }
        .mg-team:hover { border-color: #8b5cf6; box-shadow: 0 4px 20px rgba(139,92,246,0.12); }
        .mg-team--picked { border-color: #8b5cf6; background: #f5f3ff; box-shadow: 0 4px 20px rgba(139,92,246,0.15); }
        .mg-team__flag { font-size: 2.5rem; margin-bottom: 0.5rem; }
        .mg-team__name { font-size: 1rem; font-weight: 700; color: #1e1b4b; margin-bottom: 0.2rem; }
        .mg-team__region { font-size: 0.75rem; color: #9ca3af; margin-bottom: 0.75rem; }
        .mg-team__btn {
            display: inline-block; padding: 0.5rem 1.5rem;
            background: linear-gradient(135deg, #8b5cf6, #6d28d9); color: #fff;
            border: none; border-radius: 50px; font-weight: 700; font-size: 0.85rem;
            cursor: pointer; transition: all 0.2s;
        }
        .mg-team__btn:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(139,92,246,0.4); }
        .mg-team--picked .mg-team__btn { background: #22c55e; }

        /* ===== STEPS ===== */
        .mg-steps { display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap; max-width: 800px; margin: 0 auto; }
        .mg-step { flex: 1; min-width: 200px; text-align: center; }
        .mg-step__num {
            width: 48px; height: 48px; border-radius: 50%;
            background: linear-gradient(135deg, #8b5cf6, #6d28d9); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; font-weight: 800; margin: 0 auto 1rem;
        }
        .mg-step h3 { font-size: 1.05rem; font-weight: 700; color: #1e1b4b; margin-bottom: 0.4rem; }
        .mg-step p { font-size: 0.9rem; color: #6b7280; margin: 0; }

        /* ===== MATCHES ===== */
        .mg-matches { max-width: 700px; margin: 0 auto; display: flex; flex-direction: column; gap: 1rem; }
        .mg-match {
            background: #fff; border: 1px solid #e5e7eb; border-radius: 16px;
            padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
        }
        .mg-match--done { opacity: 0.7; }
        .mg-match__round { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: #8b5cf6; font-weight: 700; min-width: 100px; }
        .mg-match__time { font-size: 0.8rem; color: #9ca3af; min-width: 80px; }
        .mg-match__vs { display: flex; align-items: center; gap: 0.75rem; flex: 1; justify-content: center; }
        .mg-match__x { font-size: 0.8rem; font-weight: 700; color: #d1d5db; }
        .mg-match__team {
            padding: 0.5rem 1.2rem; border: 2px solid #e5e7eb; border-radius: 10px;
            font-weight: 700; font-size: 0.9rem; color: #1e1b4b;
            cursor: pointer; background: #fff; transition: all 0.2s;
        }
        .mg-match__team:hover { border-color: #8b5cf6; background: #f5f3ff; }
        .mg-match__team--picked { border-color: #8b5cf6; background: #f5f3ff; }
        .mg-match__team--won { border-color: #22c55e; background: #f0fdf4; color: #166534; }
        .mg-match__result { font-size: 0.8rem; font-weight: 700; }
        .mg-match__result--ok { color: #16a34a; }
        .mg-match__result--fail { color: #dc2626; }
        .mg-match__pick { display: inline; }

        /* ===== LEADERBOARD ===== */
        .mg-lb { max-width: 600px; margin: 0 auto; }
        .mg-lb__empty { text-align: center; padding: 3rem; color: #9ca3af; font-size: 1rem; background: #fff; border-radius: 16px; border: 1px solid #e5e7eb; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .mg-hero { min-height: 45vh; }
            .mg-hero__title { font-size: 1.8rem; }
            .mg-teams { grid-template-columns: repeat(2, 1fr); }
            .mg-steps { flex-direction: column; align-items: center; }
            .mg-match { flex-direction: column; text-align: center; }
            .mg-match__round, .mg-match__time { min-width: auto; }
            .mg-section { padding: 3rem 0; }
        }
        @media (max-width: 400px) {
            .mg-teams { grid-template-columns: 1fr; }
            .mg-hero__title { font-size: 1.5rem; }
        }
    </style>
    @endpush
@endsection
