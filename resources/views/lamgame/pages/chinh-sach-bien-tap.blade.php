@extends('layouts.master')

@php $__loc = in_array(app()->getLocale(),['vi','en','de']) ? app()->getLocale() : 'vi'; @endphp

@section('page_title', __('lamgame.policy.editorial_title'))
@section('page_description', __('lamgame.policy.editorial_desc'))

@section('content')
@includeFirst(['lamgame.pages.policy.content.editorial-' . $__loc, 'lamgame.pages.policy.content.editorial-vi'])

<style>
.policy-page {
    padding: 2rem 0;
}

.policy-content {
    max-width: 800px;
    margin: 0 auto;
}

.policy-header {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #eee;
}

.policy-header h1 {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.policy-intro {
    font-size: 1.125rem;
    color: #666;
    line-height: 1.7;
}

.policy-section {
    margin-bottom: 2rem;
}

.policy-section h2 {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    color: #333;
}

.policy-section p {
    line-height: 1.7;
    margin-bottom: 1rem;
}

.policy-section ul,
.policy-section ol {
    padding-left: 1.5rem;
    margin-bottom: 1rem;
}

.policy-section li {
    margin-bottom: 0.75rem;
    line-height: 1.6;
}

.policy-section li p {
    margin: 0.25rem 0 0;
    color: #666;
    font-size: 0.95rem;
}

.policy-section a {
    color: #667eea;
}

.policy-section a:hover {
    text-decoration: underline;
}

.policy-footer {
    margin-top: 3rem;
    padding-top: 1.5rem;
    border-top: 1px solid #eee;
    color: #888;
    font-size: 0.9rem;
}
</style>
@endsection
