@extends('layouts.master')

@php $__loc = in_array(app()->getLocale(), ['vi','en','de']) ? app()->getLocale() : 'vi'; @endphp

@section('page_title', __('lamgame.legal.refund_title') . ' - LamGame.vn')
@section('page_description', __('lamgame.legal.refund_desc'))

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "{{ __('lamgame.legal.refund_title') }}",
    "description": "{{ __('lamgame.legal.refund_desc') }}",
    "url": "{{ url('/chinh-sach-hoan-tien') }}",
    "inLanguage": "{{ $__loc }}",
    "datePublished": "2026-01-01",
    "dateModified": "{{ date('Y-m-d') }}"
}
</script>
@endpush

@section('content')
    @includeFirst(
        ['lamgame.pages.legal.content.refund-' . $__loc, 'lamgame.pages.legal.content.refund-vi']
    )
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/legal.css') }}">
@endpush
