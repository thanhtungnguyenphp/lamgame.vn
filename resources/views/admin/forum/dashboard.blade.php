<x-admin::layouts>
    <x-slot:title>
        Forum Management Dashboard
    </x-slot>

    @push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 24px;
        text-align: center;
        border: 1px solid #e3e8ee;
    }
    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #2d3748;
        margin-bottom: 8px;
    }
    .stat-label {
        color: #718096;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .recent-section {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: 1px solid #e3e8ee;
    }
    .section-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e3e8ee;
        font-weight: 600;
        color: #2d3748;
    }
    .list-item {
        padding: 16px 24px;
        border-bottom: 1px solid #f7fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .list-item:last-child {
        border-bottom: none;
    }
    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .badge-pending {
        background: #fed7d7;
        color: #c53030;
    }
    .badge-published {
        background: #c6f6d5;
        color: #38a169;
    }
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-top: 24px;
    }
    .action-btn {
        background: #4299e1;
        color: white;
        padding: 12px 24px;
        border-radius: 6px;
        text-decoration: none;
        text-align: center;
        font-weight: 600;
        transition: background 0.2s;
    }
    .action-btn:hover {
        background: #3182ce;
        color: white;
        text-decoration: none;
    }
    .action-btn.danger {
        background: #e53e3e;
    }
    .action-btn.danger:hover {
        background: #c53030;
    }

    /* Mobile-first responsive design */
    @media (max-width: 768px) {
        .stat-card {
            padding: 16px;
        }
        .stat-number {
            font-size: 2rem;
        }
        .list-item {
            padding: 12px 16px;
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        .quick-actions {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h1>Forum Management Dashboard</h1>
            <p>Monitor and manage your community forum</p>
        </div>
    </div>

    <div class="page-content">
        <!-- Statistics Cards -->
        <div class="row" style="margin-bottom: 32px;">
            <div class="col-md-3 col-sm-6 col-12" style="margin-bottom: 16px;">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['total_posts'] }}</div>
                    <div class="stat-label">Total Posts</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12" style="margin-bottom: 16px;">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['total_comments'] }}</div>
                    <div class="stat-label">Total Comments</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12" style="margin-bottom: 16px;">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['total_users'] }}</div>
                    <div class="stat-label">Total Users</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12" style="margin-bottom: 16px;">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['pending_reports'] }}</div>
                    <div class="stat-label">Pending Reports</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="{{ route('admin.forum.posts') }}" class="action-btn">
                Manage Posts
            </a>
            <a href="{{ route('admin.forum.comments') }}" class="action-btn">
                Manage Comments
            </a>
            <a href="{{ route('admin.forum.reports') }}" class="action-btn danger">
                Review Reports ({{ $stats['pending_reports'] }})
            </a>
            <a href="{{ route('admin.forum.users') }}" class="action-btn">
                Manage Users
            </a>
        </div>

        <div class="row" style="margin-top: 32px;">
            <!-- Recent Posts -->
            <div class="col-md-6 col-12" style="margin-bottom: 24px;">
                <div class="recent-section">
                    <div class="section-header">Recent Posts</div>
                    @if($stats['recent_posts']->count() > 0)
                        @foreach($stats['recent_posts'] as $post)
                            <div class="list-item">
                                <div>
                                    <div style="font-weight: 600; margin-bottom: 4px;">
                                        {{ Str::limit($post->title, 50) }}
                                    </div>
                                    <div style="font-size: 0.875rem; color: #718096;">
                                        by {{ $post->author_name }}
                                        • {{ $post->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <span class="badge badge-{{ $post->status === 'published' ? 'published' : 'pending' }}">
                                    {{ $post->status }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="list-item">
                            <div style="color: #718096; font-style: italic;">No recent posts</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Reports -->
            <div class="col-md-6 col-12" style="margin-bottom: 24px;">
                <div class="recent-section">
                    <div class="section-header">Pending Reports</div>
                    @if($stats['recent_reports']->count() > 0)
                        @foreach($stats['recent_reports'] as $report)
                            <div class="list-item">
                                <div>
                                    <div style="font-weight: 600; margin-bottom: 4px;">
                                        {{ ucfirst($report->reportable_type) }} Report
                                    </div>
                                    <div style="font-size: 0.875rem; color: #718096;">
                                        Reason: {{ $report->reason }}
                                        • {{ $report->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <span class="badge badge-pending">
                                    {{ $report->status }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="list-item">
                            <div style="color: #718096; font-style: italic;">No pending reports</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

</x-admin::layouts>
