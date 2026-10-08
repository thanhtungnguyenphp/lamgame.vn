@extends('layouts.master')

@php $__loc = in_array(app()->getLocale(), ['vi','en','de']) ? app()->getLocale() : 'vi'; @endphp

@section('page_title', __('lamgame.legal.privacy_title') . ' - LamGame.vn')
@section('page_description', __('lamgame.legal.privacy_desc'))

@push('schema_markup')
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"WebPage","name":"{{ __('lamgame.legal.privacy_title') }}","url":"{{ url('/chinh-sach-bao-mat') }}","inLanguage":"{{ $__loc }}","dateModified":"{{ date('Y-m-d') }}"}
</script>
@endpush

@section('content')
    @includeFirst(['lamgame.pages.legal.content.privacy-' . $__loc, 'lamgame.pages.legal.content.privacy-vi'])
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/legal.css') }}">
@endpush
