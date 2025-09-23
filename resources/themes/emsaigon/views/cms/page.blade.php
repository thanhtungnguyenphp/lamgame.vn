@extends('layouts.master')

@section('page_title', $page->meta_title ?: $page->page_title)

@section('page_description', $page->meta_description ?: '')

<!-- SEO Meta Content -->
@push('meta')
    <meta name="title" content="{{ $page->meta_title }}" />
    <meta name="description" content="{{ $page->meta_description }}" />
    <meta name="keywords" content="{{ $page->meta_keywords }}" />
@endPush

@section('content')
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1 class="page-title">{{ $page->page_title }}</h1>
                @if ($page->meta_description)
                    <p class="page-subtitle">{{ $page->meta_description }}</p>
                @endif
            </div>
        </div>
    </section>

    <!-- Page Content -->
    <section class="page-content">
        <div class="container">
            <div class="content-wrapper">
                {!! $page->html_content !!}
            </div>
        </div>
    </section>

    @pushOnce('styles')
        <style>
            /* Page Header Styling */
            .page-header {
                background: linear-gradient(135deg, #2c5f41 0%, #3d7c59 100%);
                color: white;
                padding: 3rem 0;
                text-align: center;
                position: relative;
                overflow: hidden;
            }
            
            .page-header::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
                opacity: 0.3;
            }
            
            .page-header-content {
                position: relative;
                z-index: 2;
                max-width: 800px;
                margin: 0 auto;
            }
            
            .page-title {
                font-size: 3rem;
                font-weight: 700;
                margin-bottom: 1rem;
                line-height: 1.2;
            }
            
            .page-subtitle {
                font-size: 1.2rem;
                opacity: 0.9;
                line-height: 1.5;
                margin: 0;
            }
            
            /* Page Content Styling */
            .page-content {
                padding: 4rem 0;
                background: white;
                min-height: 60vh;
            }
            
            .content-wrapper {
                max-width: 1000px;
                margin: 0 auto;
                padding: 2rem;
                background: white;
                border-radius: 15px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.08);
                line-height: 1.8;
            }
            
            /* Content Typography */
            .content-wrapper h1,
            .content-wrapper h2,
            .content-wrapper h3,
            .content-wrapper h4,
            .content-wrapper h5,
            .content-wrapper h6 {
                color: #2c5f41;
                margin-top: 2rem;
                margin-bottom: 1rem;
                font-weight: 600;
            }
            
            .content-wrapper h1 {
                font-size: 2.5rem;
                border-bottom: 3px solid #2c5f41;
                padding-bottom: 0.5rem;
            }
            
            .content-wrapper h2 {
                font-size: 2rem;
                border-left: 5px solid #2c5f41;
                padding-left: 1rem;
            }
            
            .content-wrapper h3 {
                font-size: 1.5rem;
                color: #ff6b35;
            }
            
            .content-wrapper p {
                color: #555;
                margin-bottom: 1.5rem;
                font-size: 1.1rem;
            }
            
            .content-wrapper ul,
            .content-wrapper ol {
                margin-bottom: 1.5rem;
                padding-left: 2rem;
            }
            
            .content-wrapper li {
                color: #555;
                margin-bottom: 0.5rem;
                line-height: 1.6;
            }
            
            .content-wrapper ul li::marker {
                color: #2c5f41;
            }
            
            .content-wrapper a {
                color: #2c5f41;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.3s;
            }
            
            .content-wrapper a:hover {
                color: #1e4530;
                text-decoration: underline;
            }
            
            .content-wrapper blockquote {
                background: #f8f9fa;
                border-left: 5px solid #2c5f41;
                padding: 1.5rem;
                margin: 2rem 0;
                font-style: italic;
                border-radius: 0 10px 10px 0;
            }
            
            .content-wrapper blockquote p {
                margin: 0;
                color: #2c5f41;
                font-size: 1.2rem;
            }
            
            .content-wrapper img {
                max-width: 100%;
                height: auto;
                border-radius: 10px;
                margin: 1.5rem 0;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
            
            .content-wrapper table {
                width: 100%;
                border-collapse: collapse;
                margin: 2rem 0;
                background: white;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
            
            .content-wrapper th {
                background: #2c5f41;
                color: white;
                padding: 1rem;
                text-align: left;
                font-weight: 600;
            }
            
            .content-wrapper td {
                padding: 1rem;
                border-bottom: 1px solid #eee;
                color: #555;
            }
            
            .content-wrapper tr:hover {
                background: #f8f9fa;
            }
            
            .content-wrapper .btn,
            .content-wrapper button,
            .content-wrapper input[type="submit"] {
                background: #2c5f41;
                color: white;
                padding: 1rem 2rem;
                border: none;
                border-radius: 8px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s;
                display: inline-block;
                text-decoration: none;
                margin: 0.5rem 0.5rem 0.5rem 0;
            }
            
            .content-wrapper .btn:hover,
            .content-wrapper button:hover,
            .content-wrapper input[type="submit"]:hover {
                background: #1e4530;
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(44, 95, 65, 0.3);
                color: white;
                text-decoration: none;
            }
            
            .content-wrapper .btn-outline {
                background: transparent;
                color: #2c5f41;
                border: 2px solid #2c5f41;
            }
            
            .content-wrapper .btn-outline:hover {
                background: #2c5f41;
                color: white;
            }
            
            .content-wrapper .highlight-box {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                padding: 2rem;
                border-radius: 15px;
                border-left: 5px solid #ff6b35;
                margin: 2rem 0;
                position: relative;
            }
            
            .content-wrapper .highlight-box::before {
                content: '💡';
                position: absolute;
                top: 1rem;
                right: 1rem;
                font-size: 1.5rem;
            }
            
            .content-wrapper .info-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 2rem;
                margin: 2rem 0;
            }
            
            .content-wrapper .info-card {
                background: #f8f9fa;
                padding: 1.5rem;
                border-radius: 10px;
                border-top: 4px solid #2c5f41;
                text-align: center;
                transition: transform 0.3s ease;
            }
            
            .content-wrapper .info-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            }
            
            .content-wrapper .info-card h4 {
                color: #2c5f41;
                margin-top: 0;
                margin-bottom: 1rem;
            }
            
            .content-wrapper .info-card p {
                margin: 0;
                font-size: 1rem;
            }
            
            /* Responsive Design */
            @media (max-width: 768px) {
                .page-title {
                    font-size: 2rem;
                }
                
                .page-subtitle {
                    font-size: 1rem;
                }
                
                .content-wrapper {
                    padding: 1rem;
                    margin: 0 1rem;
                }
                
                .content-wrapper h1 {
                    font-size: 1.8rem;
                }
                
                .content-wrapper h2 {
                    font-size: 1.5rem;
                }
                
                .content-wrapper p {
                    font-size: 1rem;
                }
                
                .info-grid {
                    grid-template-columns: 1fr;
                }
            }
            
            /* Print Styles */
            @media print {
                .page-header {
                    background: #2c5f41 !important;
                    color: white !important;
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                }
                
                .content-wrapper {
                    box-shadow: none;
                    padding: 0;
                }
                
                .btn,
                button {
                    display: none;
                }
            }
        </style>
    @endPushOnce
@endsection
