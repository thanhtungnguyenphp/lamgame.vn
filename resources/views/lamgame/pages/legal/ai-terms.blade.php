@extends('layouts.master')

@php $__loc = in_array(app()->getLocale(), ['vi','en','de']) ? app()->getLocale() : 'vi'; @endphp

@section('page_title', __('lamgame.legal.ai_title') . ' - LamGame.vn')
@section('page_description', __('lamgame.legal.ai_desc'))

@push('schema_markup')
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"WebPage","name":"{{ __('lamgame.legal.ai_title') }}","url":"{{ url('/dieu-khoan-ai') }}","inLanguage":"{{ $__loc }}","dateModified":"{{ date('Y-m-d') }}"}
</script>
@endpush

@section('content')
    @includeFirst(['lamgame.pages.legal.content.ai-terms-' . $__loc, 'lamgame.pages.legal.content.ai-terms-vi'])
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/legal.css') }}">
@endpush
