@extends('layouts.master')
@section('title', 'Bộ sưu tập của tôi')

@section('content')
<section style="padding: 3rem 0; background: #f8f9fa; min-height: 60vh;">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1 style="font-size: 1.8rem; font-weight: 700;">📚 Bộ sưu tập của tôi</h1>
            <form method="POST" action="{{ route('collections.store') }}" style="display: flex; gap: 0.5rem;">
                @csrf
                <input type="text" name="name" placeholder="Tên bộ sưu tập mới..." required
                       style="padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 8px;">
                <button type="submit" style="background: #2c5f41; color: white; padding: 0.5rem 1.5rem; border: none; border-radius: 8px; cursor: pointer;">Tạo mới</button>
            </form>
        </div>

        @if($collections->count())
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                @foreach($collections as $c)
                <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <a href="{{ route('collections.show', $c->slug) }}" style="font-size: 1.1rem; font-weight: 600; color: #1f2937; text-decoration: none;">{{ $c->name }}</a>
                        <form method="POST" action="{{ route('collections.destroy', $c->id) }}" onsubmit="return confirm('Xóa bộ sưu tập này?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #999; cursor: pointer;">🗑️</button>
                        </form>
                    </div>
                    <p style="color: #666; margin-top: 0.5rem;">{{ $c->items_count }} sản phẩm</p>
                    <p style="color: #999; font-size: 0.85rem;">Cập nhật {{ $c->updated_at->diffForHumans() }}</p>
                </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 3rem; color: #666;">
                <p style="font-size: 3rem;">📚</p>
                <p>Bạn chưa có bộ sưu tập nào. Tạo một bộ sưu tập để lưu source game yêu thích!</p>
            </div>
        @endif
    </div>
</section>
@endsection
