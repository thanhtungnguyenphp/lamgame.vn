@extends('layouts.master')

@php $__loc = in_array(app()->getLocale(),['vi','en','de']) ? app()->getLocale() : 'vi'; @endphp

@section('page_title', __('lamgame.policy.revision_title'))
@section('page_description', __('lamgame.policy.revision_desc'))

@section('content')
@includeFirst(['lamgame.pages.policy.content.revision-' . $__loc, 'lamgame.pages.policy.content.revision-vi'])

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

.policy-section h3 {
    font-size: 1rem;
    margin-bottom: 0.5rem;
    color: #444;
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

.correction-type {
    background: #f9fafb;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.correction-type h3 {
    margin-top: 0;
}

.correction-type p {
    margin-bottom: 0;
}

.cta-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 8px;
    text-align: center;
    margin: 1.5rem 0;
}

.cta-box p {
    margin-bottom: 1rem;
}

.cta-box .btn {
    background: white;
    color: #667eea;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    display: inline-block;
}

.cta-box .btn:hover {
    background: #f3f4f6;
}

.timeline-table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
}

.timeline-table th,
.timeline-table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.timeline-table th {
    background: #f9fafb;
    font-weight: 600;
}

.example-box {
    background: #f9fafb;
    padding: 1rem;
    border-left: 3px solid #667eea;
    margin: 1rem 0;
}

.example-box p {
    margin: 0.5rem 0;
    font-size: 0.9rem;
    color: #666;
}

.policy-footer {
    margin-top: 3rem;
    padding-top: 1.5rem;
    border-top: 1px solid #eee;
    color: #888;
    font-size: 0.9rem;
}

.policy-footer a {
    color: #667eea;
}
</style>
@endsection
