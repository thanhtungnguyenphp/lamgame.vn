<x-admin::layouts>
    <x-slot:title>
        Forum Posts Management
    </x-slot>

    @push('styles')
<style>
    .filter-bar {
        background: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .search-input {
        flex: 1;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        margin-right: 12px;
    }
    
    .filter-select {
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        margin-right: 12px;
    }
    
    .posts-table {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .table {
        margin: 0;
    }
    
    .table th {
        background: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        padding: 16px;
    }
    
    .table td {
        padding: 16px;
        vertical-align: middle;
    }
    
    .post-title {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 4px;
    }
    
    .post-meta {
        font-size: 0.875rem;
        color: #6b7280;
    }
    
    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-published {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-hidden {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .status-locked {
        background: #fef3c7;
        color: #92400e;
    }
    
    .actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 0.875rem;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-info {
        background: #3b82f6;
        color: white;
    }
    
    .btn-warning {
        background: #f59e0b;
        color: white;
    }
    
    .btn-danger {
        background: #ef4444;
        color: white;
    }
    
    .btn-success {
        background: #10b981;
        color: white;
    }
    
    .pagination {
        margin-top: 24px;
    }
    
    /* Mobile responsive */
    @media (max-width: 768px) {
        .filter-bar {
            padding: 16px;
        }
        
        .filter-bar form {
            flex-direction: column;
            gap: 12px;
        }
        
        .search-input, .filter-select {
            width: 100%;
            margin-right: 0;
        }
        
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .actions {
            flex-direction: column;
            gap: 4px;
        }
        
        .btn-sm {
            font-size: 0.75rem;
            padding: 4px 8px;
        }
    }
</style>
@endpush
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h1>Forum Posts Management</h1>
            <p>Review and moderate forum posts</p>
        </div>
    </div>

    <div class="page-content">
        <!-- Filter Bar -->
        <div class="filter-bar">
            <form method="GET" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..." class="search-input">
                
                <select name="status" class="filter-select">
                    <option value="">All Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="hidden" {{ request('status') === 'hidden' ? 'selected' : '' }}>Hidden</option>
                    <option value="locked" {{ request('status') === 'locked' ? 'selected' : '' }}>Locked</option>
                </select>
                
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.forum.posts') }}" class="btn btn-secondary">Clear</a>
            </form>
        </div>

        <!-- Posts Table -->
        <div class="posts-table">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Post</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Comments</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td>
                                    <div class="post-title">{{ Str::limit($post->title, 50) }}</div>
                                    <div class="post-meta">{{ Str::limit(strip_tags($post->content), 100) }}</div>
                                </td>
                                <td>
                                    <div>{{ $post->author_name }}</div>
                                    <small class="text-muted">{{ $post->author_email }}</small>
                                </td>
                                <td>{{ $post->category->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="status-badge status-{{ $post->status }}">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                </td>
                                <td>{{ $post->comments_count }}</td>
                                <td>{{ $post->created_at->format('M j, Y') }}</td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('forum.posts.show', $post->slug) }}" target="_blank" class="btn-sm btn-info">View</a>
                                        
                                        @if($post->status === 'published')
                                            <button onclick="updateStatus({{ $post->id }}, 'hidden')" class="btn-sm btn-warning">Hide</button>
                                            <button onclick="updateStatus({{ $post->id }}, 'locked')" class="btn-sm btn-danger">Lock</button>
                                        @elseif($post->status === 'hidden')
                                            <button onclick="updateStatus({{ $post->id }}, 'published')" class="btn-sm btn-success">Show</button>
                                        @else
                                            <button onclick="updateStatus({{ $post->id }}, 'published')" class="btn-sm btn-success">Unlock</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 60px; color: #6b7280;">
                                    <div style="font-size: 3rem; margin-bottom: 16px; opacity: 0.5;">📝</div>
                                    <h3>No posts found</h3>
                                    <p>No forum posts match your current filters.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $posts->appends(request()->query())->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateStatus(postId, status) {
    if (!confirm(`Are you sure you want to ${status} this post?`)) {
        return;
    }
    
    fetch(`/admin/forum/posts/${postId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success !== false) {
            location.reload();
        } else {
            alert('Error updating post status: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating post status');
    });
}
</script>
@endpush

</x-admin::layouts>
