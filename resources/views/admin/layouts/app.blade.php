@extends('layouts.master')

@section('page_title', $title ?? 'Admin Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/tokens.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endpush

@section('content')
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        @include('admin.partials.sidebar')
    </aside>
    
    <main class="admin-main">
        @if(session('success'))
            <div class="alert alert--success">
                <i class="icon-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert--error">
                <i class="icon-alert-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert--error">
                <i class="icon-alert-circle"></i>
                <ul class="alert__list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('admin-content')
    </main>
</div>

<!-- Screen reader announcements -->
<div id="sr-announcements" role="status" aria-live="polite" aria-atomic="true" class="sr-only"></div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/admin.js') }}"></script>
@endpush
