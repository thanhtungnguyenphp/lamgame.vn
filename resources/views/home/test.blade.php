<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEST - LamGame Dynamic Homepage</title>
</head>
<body>
    <h1>🎯 TEST: HomeController Working!</h1>
    
    <h2>💼 Jobs from Database:</h2>
    @if(isset($jobs['featured']) && count($jobs['featured']) > 0)
        <ul>
        @foreach($jobs['featured'] as $job)
            <li>
                <strong>{{ $job['title'] }}</strong> at {{ $job['company'] }} - 
                <span style="color: green;">{{ $job['salary'] }}</span>
                ({{ $job['location'] }}, {{ $job['posted_ago'] }})
            </li>
        @endforeach
        </ul>
        <p>📊 Total jobs: {{ $jobs['total_count'] }}, This week: {{ $jobs['weekly_new'] }}</p>
    @else
        <p>❌ No jobs found from database</p>
    @endif

    <h2>📈 Statistics:</h2>
    @if(isset($stats))
        <ul>
            <li>Total Jobs: {{ $stats['total_jobs'] ?? 'N/A' }}</li>
            <li>Forum Posts: {{ $stats['forum_posts'] ?? 'N/A' }}</li>
            <li>Blog Posts: {{ $stats['blog_posts'] ?? 'N/A' }}</li>
            <li>Community Members: {{ $stats['community_members'] ?? 'N/A' }}</li>
        </ul>
    @else
        <p>❌ No statistics available</p>
    @endif

    <h2>🔥 Hot Forum Topics:</h2>
    @if(isset($hotForumTopics['featured']) && count($hotForumTopics['featured']) > 0)
        <ul>
        @foreach($hotForumTopics['featured'] as $topic)
            <li><strong>{{ $topic['title'] }}</strong> - {{ $topic['replies'] }} replies, {{ $topic['views'] }} views</li>
        @endforeach
        </ul>
    @else
        <p>❌ No forum topics found</p>
    @endif

    <p><em>✅ If you see this page, HomeController is working properly!</em></p>
</body>
</html>