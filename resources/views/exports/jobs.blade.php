<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs Export</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4472C4;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #4472C4;
            margin: 0;
            font-size: 24px;
        }

        .export-info {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
            font-size: 10px;
        }

        .job-item {
            margin-bottom: 25px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            page-break-inside: avoid;
        }

        .job-header {
            background: #f8f9fa;
            padding: 10px 15px;
            margin: -15px -15px 15px -15px;
            border-bottom: 1px solid #ddd;
        }

        .job-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0 0 5px 0;
        }

        .job-meta {
            color: #666;
            font-size: 11px;
        }

        .job-details {
            margin-top: 10px;
        }

        .detail-row {
            margin-bottom: 8px;
            display: flex;
        }

        .detail-label {
            font-weight: bold;
            color: #555;
            width: 120px;
            display: inline-block;
        }

        .detail-value {
            color: #333;
        }

        .job-description {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .section-title {
            font-weight: bold;
            color: #4472C4;
            margin-bottom: 5px;
            font-size: 13px;
        }

        .description-text {
            line-height: 1.4;
            text-align: justify;
        }

        .salary-range {
            color: #27ae60;
            font-weight: bold;
        }

        .status {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status.active {
            background: #d4edda;
            color: #155724;
        }

        .status.inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .page-break {
            page-break-before: always;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .statistics {
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 8px;
            text-align: center;
        }

        .stat-item {
            display: inline-block;
            margin: 0 15px;
            font-size: 11px;
        }

        .stat-number {
            font-weight: bold;
            color: #4472C4;
            font-size: 14px;
        }

        .no-jobs {
            text-align: center;
            color: #666;
            font-style: italic;
            margin: 40px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Jobs Export Report</h1>
        <div class="export-info">
            Generated on {{ date('F j, Y \a\t g:i A') }} | Total Jobs: {{ count($data) }}
        </div>
    </div>

    @if(count($data) > 0)
        @foreach($data as $index => $job)
            <div class="job-item">
                <div class="job-header">
                    <h2 class="job-title">{{ $job['name'] ?? 'Untitled Job' }}</h2>
                    <div class="job-meta">
                        Job ID: {{ $job['id'] ?? 'N/A' }} | 
                        Created: {{ isset($job['created_at']) ? \Carbon\Carbon::parse($job['created_at'])->format('M j, Y') : 'N/A' }} |
                        <span class="status {{ strtolower($job['status'] ?? 'inactive') }}">
                            {{ $job['status'] ?? 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="job-details">
                    @if(!empty($job['location']))
                    <div class="detail-row">
                        <span class="detail-label">Location:</span>
                        <span class="detail-value">{{ $job['location'] }}</span>
                    </div>
                    @endif

                    @if(!empty($job['salary_min']) || !empty($job['salary_max']))
                    <div class="detail-row">
                        <span class="detail-label">Salary Range:</span>
                        <span class="detail-value salary-range">
                            @if(!empty($job['salary_min']) && !empty($job['salary_max']))
                                ${{ number_format($job['salary_min']) }} - ${{ number_format($job['salary_max']) }}
                            @elseif(!empty($job['salary_min']))
                                From ${{ number_format($job['salary_min']) }}
                            @elseif(!empty($job['salary_max']))
                                Up to ${{ number_format($job['salary_max']) }}
                            @endif
                        </span>
                    </div>
                    @endif

                    @if(!empty($job['short_description']))
                    <div class="detail-row">
                        <span class="detail-label">Summary:</span>
                        <span class="detail-value">{{ $job['short_description'] }}</span>
                    </div>
                    @endif

                    @if(isset($job['views']) || isset($job['applications']) || isset($job['conversion_rate']))
                    <div class="statistics">
                        @if(isset($job['views']))
                        <div class="stat-item">
                            <div class="stat-number">{{ $job['views'] }}</div>
                            <div>Views</div>
                        </div>
                        @endif
                        
                        @if(isset($job['applications']))
                        <div class="stat-item">
                            <div class="stat-number">{{ $job['applications'] }}</div>
                            <div>Applications</div>
                        </div>
                        @endif

                        @if(isset($job['conversion_rate']))
                        <div class="stat-item">
                            <div class="stat-number">{{ number_format($job['conversion_rate'], 1) }}%</div>
                            <div>Conversion Rate</div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                @if(!empty($job['description']))
                <div class="job-description">
                    <div class="section-title">Job Description:</div>
                    <div class="description-text">
                        {!! nl2br(e(strip_tags($job['description']))) !!}
                    </div>
                </div>
                @endif
            </div>

            {{-- Add page break every 3 jobs to avoid overcrowding --}}
            @if(($index + 1) % 3 == 0 && $index + 1 < count($data))
                <div class="page-break"></div>
            @endif
        @endforeach
    @else
        <div class="no-jobs">
            <h3>No jobs found matching the export criteria</h3>
            <p>Please check your filters and try again.</p>
        </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the Job Management System</p>
        <p>Page {PAGENO} of {TOTALPAGES}</p>
    </div>
</body>
</html>