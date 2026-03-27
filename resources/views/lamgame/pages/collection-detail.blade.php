@extends('layouts.master')
@section('title', $collection->name . ' - Bộ sưu tập')

@section('content')
<section style="padding: 3rem 0; background: #f8f9fa; min-height: 60vh;">
    <div class="container">
        <h1 style="font-size: 1.8rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $collection->name }}</h1>
        @if($collection->description)
            <p style="color: #666; margin-bottom: 2rem;">{{ $collection->description }}</p>
        @endif

        @if($products->count())
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                @foreach($products as $p)
                <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <a href="{{ route('lamgame.source-game.detail', $p->url_key ?? '') }}">
                        <img src="{{ $p->image ? asset('storage/' . $p->image) : asset('images/placeholder-game.svg') }}"
                             style="width: 100%; height: 180px; object-fit: cover;">
                    </a>
                    <div style="padding: 1rem;">
                        <a href="{{ route('lamgame.source-game.detail', $p->url_key ?? '') }}" style="font-weight: 600; color: #1f2937; text-decoration: none;">{{ $p->name }}</a>
                        <p style="color: #2c5f41; font-weight: 700; margin-top: 0.5rem;">{{ $p->price > 0 ? number_format($p->price) . 'đ' : 'Miễn phí' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div style="margin-top: 2rem;">{{ $products->links() }}</div>
        @else
            <div style="text-align: center; padding: 3rem; color: #666;">Bộ sưu tập trống.</div>
        @endif
    </div>
</section>
@endsection
