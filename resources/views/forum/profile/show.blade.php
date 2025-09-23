@extends('lamgame::layouts.app')

@section('title')
{{ $user->first_name }} {{ $user->last_name }} - Profile
@endsection

@section('css')
<style>
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .profile-header {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 24px;
    }
    
    .avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: bold;
        color: white;
        flex-shrink: 0;
    }
    
    .profile-info h1 {
        margin: 0 0 8px 0;
        font-size: 2rem;
        font-weight: 600;
        color: #1a1a1a;
    }
    
    .profile-meta {
        color: #666;
        margin-bottom: 16px;
    }
    
    .profile-meta span {
        margin-right: 16px;
    }
    
    .profile-bio {
        color: #444;
        line-height: 1.6;
        margin-bottom: 16px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }
    
    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #2563eb;
        margin-bottom: 4px;
    }
    
    .stat-label {
        color: #666;
        font-size: 0.875rem;
    }
    
    .tabs-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .tabs-nav {
        display: flex;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
    }
    
    .tab-button {
        flex: 1;
        padding: 16px;
        background: none;
        border: none;
        cursor: pointer;
        font-weight: 500;
        color: #6b7280;
        transition: all 0.2s;
    }
    
    .tab-button.active {
        color: #2563eb;
        background: white;
        border-bottom: 2px solid #2563eb;
    }
    
    .tab-content {
        padding: 24px;
        min-height: 400px;
    }
    
    .tab-pane {
        display: none;
    }
    
    .tab-pane.active {
        display: block;
    }
    
    .activity-item {
        padding: 16px;
        border-bottom: 1px solid #f3f4f6;
        border-left: 3px solid transparent;
        transition: all 0.2s;
    }
    
    .activity-item:hover {
        background: #f9fafb;
        border-left-color: #2563eb;
    }
    
    .activity-title {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 8px;
    }
    
    .activity-meta {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 8px;
    }
    
    .activity-excerpt {
        color: #4b5563;
        line-height: 1.5;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
    }
    
    .empty-icon {
        font-size: 3rem;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    
    /* Mobile-first responsive design */
    @media (max-width: 768px) {
        .profile-container {
            padding: 16px;
        }
        
        .profile-header {
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }
        
        .avatar {
            width: 100px;
            height: 100px;
            font-size: 40px;
        }
        
        .profile-info h1 {
            font-size: 1.5rem;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .tabs-nav {
            flex-direction: column;
        }
        
        .tab-content {
            padding: 16px;
        }
        
        .activity-item {
            padding: 12px;
        }
    }
</style>
@endsection

@section('content')
<div class="profile-container">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="avatar">
            @if($user->avatar_url)
                <img src="{{ Storage::url($user->avatar_url) }}" alt="{{ $user->first_name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
            @else
                {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
            @endif
        </div>
        <div class="profile-info">
            <h1>{{ $user->first_name }} {{ $user->last_name }}</h1>
            <div class="profile-meta">
                <span><i class="fa fa-calendar"></i> Joined {{ $stats['joined_date']->format('M Y') }}</span>
                @if($stats['last_activity'])
                    <span><i class="fa fa-clock"></i> Last active {{ $stats['last_activity']->diffForHumans() }}</span>
                @endif
            </div>
            @if($user->bio)
                <div class="profile-bio">{{ $user->bio }}</div>
            @endif
            
            @auth('customer')
                @if(Auth::guard('customer')->user()->id === $user->id)
                    <a href="{{ route('forum.profile.edit') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-edit"></i> Edit Profile
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_posts'] }}</div>
            <div class="stat-label">Posts</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_comments'] }}</div>
            <div class="stat-label">Comments</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['reputation'] }}</div>
            <div class="stat-label">Reputation</div>
        </div>
    </div>

    <!-- Activity Tabs -->
    <div class="tabs-container">
        <div class="tabs-nav">
            <button class="tab-button active" data-tab="posts">Posts</button>
            <button class="tab-button" data-tab="comments">Comments</button>
        </div>

        <!-- Posts Tab -->
        <div id="posts-tab" class="tab-content">
            <div class="tab-pane active">
                @if($posts->count() > 0)
                    @foreach($posts as $post)
                        <div class="activity-item">
                            <div class="activity-title">
                                <a href="{{ route('forum.posts.show', $post->slug) }}" style="color: inherit; text-decoration: none;">
                                    {{ $post->title }}
                                </a>
                            </div>
                            <div class="activity-meta">
                                <span>{{ $post->category->name }}</span> •
                                <span>{{ $post->created_at->diffForHumans() }}</span> •
                                <span>{{ $post->comments_count }} comments</span>
                            </div>
                            <div class="activity-excerpt">
                                {{ Str::limit(strip_tags($post->content), 150) }}
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $posts->appends(['posts_page'])->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">📝</div>
                        <h3>No posts yet</h3>
                        <p>This user hasn't posted anything yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Comments Tab -->
        <div id="comments-tab" class="tab-content" style="display: none;">
            <div class="tab-pane">
                @if($comments->count() > 0)
                    @foreach($comments as $comment)
                        <div class="activity-item">
                            <div class="activity-title">
                                <a href="{{ route('forum.posts.show', $comment->post->slug) }}#comment-{{ $comment->id }}" style="color: inherit; text-decoration: none;">
                                    Comment on "{{ $comment->post->title }}"
                                </a>
                            </div>
                            <div class="activity-meta">
                                <span>{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="activity-excerpt">
                                {{ Str::limit(strip_tags($comment->content), 150) }}
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $comments->appends(['comments_page'])->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">💬</div>
                        <h3>No comments yet</h3>
                        <p>This user hasn't commented on any posts yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('[id$="-tab"]');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.dataset.tab;
            
            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.style.display = 'none');
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Show target tab content
            document.getElementById(targetTab + '-tab').style.display = 'block';
        });
    });
});
</script>
@endsection
