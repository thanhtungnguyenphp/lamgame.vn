@extends('layouts.master')

@section('page_title', $page_title ?? __('lamgame.ideas.page_title'))

@section('page_description', $page_description ?? 'Nơi gamer chia sẻ ý tưởng game độc đáo và tìm kiếm đội ngũ phát triển. Biến ý tưởng thành hiện thực.')

@section('content')
    <!-- Hero Section -->
    <section class="idea-hero">
        <div class="container">
            <div class="hero-content">
                <h1>{{ __('lamgame.ideas.hero') }}</h1>
                <p class="hero-subtitle">
                    Nơi những ý tưởng game tuyệt vời được sinh ra và phát triển. 
                    Chia sẻ ý tưởng của bạn, tìm kiếm đồng đội và biến giấc mơ thành hiện thực.
                </p>
                <div class="hero-actions">
                    <button class="btn btn-primary btn-large" onclick="showIdeaForm()">
                        ✨ Đăng Ý Tưởng Mới
                    </button>
                    <button class="btn btn-outline btn-large" onclick="scrollToSection('#trending-ideas')">
                        🔥 Xem Ý Tưởng Hot
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="idea-stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">💡</div>
                    <div class="stat-number">{{ count($ideaPosts) }}+</div>
                    <div class="stat-label">{{ __('lamgame.ideas.stat_ideas') }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number">45+</div>
                    <div class="stat-label">{{ __('lamgame.ideas.stat_teams') }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🚀</div>
                    <div class="stat-number">12+</div>
                    <div class="stat-label">{{ __('lamgame.ideas.stat_games') }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-number">3+</div>
                    <div class="stat-label">{{ __('lamgame.ideas.stat_success') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works">
        <div class="container">
            <h2 class="section-title">{{ __('lamgame.ideas.how') }}</h2>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-icon">💡</div>
                    <h3>{{ __('lamgame.ideas.how1') }}</h3>
                    <p>{{ __('lamgame.ideas.how1_d') }}</p>
                </div>
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-icon">👀</div>
                    <h3>{{ __('lamgame.ideas.how2') }}</h3>
                    <p>{{ __('lamgame.ideas.how2_d') }}</p>
                </div>
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-icon">🤝</div>
                    <h3>{{ __('lamgame.ideas.how3') }}</h3>
                    <p>{{ __('lamgame.ideas.how3_d') }}</p>
                </div>
                <div class="step-card">
                    <div class="step-number">4</div>
                    <div class="step-icon">🎮</div>
                    <h3>{{ __('lamgame.ideas.how4') }}</h3>
                    <p>{{ __('lamgame.ideas.how4_d') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Trending Ideas -->
    <section id="trending-ideas" class="trending-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">{{ __('lamgame.ideas.trending') }}</h2>
                <div class="filter-tabs">
                    <button class="filter-tab active" onclick="filterIdeas('all')">{{ __('lamgame.ideas.all') }}</button>
                    <button class="filter-tab" onclick="filterIdeas('mobile')">Mobile</button>
                    <button class="filter-tab" onclick="filterIdeas('pc')">PC</button>
                    <button class="filter-tab" onclick="filterIdeas('vr')">VR</button>
                    <button class="filter-tab" onclick="filterIdeas('need-team')">{{ __('lamgame.ideas.need_team') }}</button>
                </div>
            </div>
            
            <div class="ideas-grid">
                @foreach($ideaPosts as $idea)
                <div class="idea-card" data-platform="{{ strtolower($idea['platform']) }}" data-genre="{{ strtolower($idea['genre']) }}">
                    <div class="idea-header">
                        <div class="idea-genre">{{ $idea['genre'] }}</div>
                        <div class="idea-platform">{{ $idea['platform'] }}</div>
                    </div>
                    
                    <h3 class="idea-title">{{ $idea['title'] }}</h3>
                    <p class="idea-description">{{ $idea['description'] }}</p>
                    
                    <div class="idea-author">
                        <span class="author-icon">👤</span>
                        <span>{{ $idea['author'] }}</span>
                        <span class="idea-date">{{ $idea['created_at'] }}</span>
                    </div>
                    
                    @if(!empty($idea['team_needed']))
                    <div class="team-needed">
                        <h4>🔍 Cần tìm:</h4>
                        <div class="needed-roles">
                            @foreach($idea['team_needed'] as $role)
                            <span class="role-tag">{{ $role }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <div class="idea-stats">
                        <div class="stat-item">
                            <span class="stat-icon">👍</span>
                            <span>{{ $idea['likes'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-icon">💬</span>
                            <span>{{ $idea['comments'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-icon">👥</span>
                            <span>Cần team</span>
                        </div>
                    </div>
                    
                    <div class="idea-actions">
                        <button class="btn btn-primary" onclick="likeIdea({{ $idea['id'] }})">
                            👍 Thích
                        </button>
                        <button class="btn btn-outline" onclick="joinTeam({{ $idea['id'] }})">
                            🤝 Tham gia team
                        </button>
                        <button class="btn btn-secondary" onclick="viewIdeaDetail({{ $idea['id'] }})">
                            📝 Chi tiết
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Success Stories -->
    <section class="success-stories">
        <div class="container">
            <h2 class="section-title">{{ __('lamgame.ideas.success') }}</h2>
            <div class="stories-grid">
                <div class="story-card">
                    <div class="story-image">
                        <img src="{{ asset('images/placeholder-game.svg') }}" alt="Game thành công" />
                    </div>
                    <div class="story-content">
                        <h3>{{ __('lamgame.ideas.story1_t') }}</h3>
                        <p>{{ __('lamgame.ideas.story1_d') }}</p>
                        <div class="story-author">
                            <span>Nguyễn Minh Tuấn - Game Designer</span>
                        </div>
                    </div>
                </div>
                
                <div class="story-card">
                    <div class="story-image">
                        <img src="{{ asset('images/placeholder-game.svg') }}" alt="Team success" />
                    </div>
                    <div class="story-content">
                        <h3>{{ __('lamgame.ideas.story2_t') }}</h3>
                        <p>{{ __('lamgame.ideas.story2_d') }}</p>
                        <div class="story-author">
                            <span>Trần Văn Hùng - Unity Developer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Submit Idea Modal Form -->
    <div id="ideaModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('lamgame.ideas.form_title') }}</h2>
                <button class="close-btn" onclick="closeIdeaModal()">&times;</button>
            </div>
            
            <form id="ideaForm" class="idea-form" onsubmit="submitIdea(event)">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="ideaTitle">{{ __('lamgame.ideas.f_name') }}</label>
                        <input type="text" id="ideaTitle" name="title" required placeholder="{{ __('lamgame.ideas.f_name_ph') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="ideaGenre">{{ __('lamgame.ideas.f_genre') }}</label>
                        <select id="ideaGenre" name="genre" required>
                            <option value="">{{ __('lamgame.ideas.choose_genre') }}</option>
                            <option value="Action">Action</option>
                            <option value="RPG">RPG</option>
                            <option value="Strategy">Strategy</option>
                            <option value="Simulation">Simulation</option>
                            <option value="Puzzle">Puzzle</option>
                            <option value="Racing">Racing</option>
                            <option value="Sports">Sports</option>
                            <option value="Adventure">Adventure</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="ideaPlatform">{{ __('lamgame.ideas.f_platform') }}</label>
                        <select id="ideaPlatform" name="platform" required>
                            <option value="">{{ __('lamgame.ideas.choose_plat') }}</option>
                            <option value="Mobile">Mobile (iOS/Android)</option>
                            <option value="PC">PC (Windows/Mac/Linux)</option>
                            <option value="Console">Console (PS/Xbox/Nintendo)</option>
                            <option value="VR">VR (Virtual Reality)</option>
                            <option value="Web">Web Browser</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="teamSize">{{ __('lamgame.ideas.f_team') }}</label>
                        <select id="teamSize" name="team_size">
                            <option value="">{{ __('lamgame.ideas.choose_size') }}</option>
                            <option value="Solo">{{ __('lamgame.ideas.size_solo') }}</option>
                            <option value="Small">{{ __('lamgame.ideas.size_small') }}</option>
                            <option value="Medium">{{ __('lamgame.ideas.size_medium') }}</option>
                            <option value="Large">{{ __('lamgame.ideas.size_large') }}</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="ideaDescription">{{ __('lamgame.ideas.f_desc') }}</label>
                    <textarea id="ideaDescription" name="description" required rows="6" 
                              placeholder="{{ __('lamgame.ideas.f_desc_ph') }}"></textarea>
                </div>
                
                <div class="form-group">
                    <label>{{ __('lamgame.ideas.f_roles') }}</label>
                    <div class="roles-grid">
                        <label class="role-checkbox">
                            <input type="checkbox" name="team_needed[]" value="Unity Developer">
                            <span>Unity Developer</span>
                        </label>
                        <label class="role-checkbox">
                            <input type="checkbox" name="team_needed[]" value="Unreal Developer">
                            <span>Unreal Developer</span>
                        </label>
                        <label class="role-checkbox">
                            <input type="checkbox" name="team_needed[]" value="2D Artist">
                            <span>2D Artist</span>
                        </label>
                        <label class="role-checkbox">
                            <input type="checkbox" name="team_needed[]" value="3D Artist">
                            <span>3D Artist</span>
                        </label>
                        <label class="role-checkbox">
                            <input type="checkbox" name="team_needed[]" value="Game Designer">
                            <span>Game Designer</span>
                        </label>
                        <label class="role-checkbox">
                            <input type="checkbox" name="team_needed[]" value="Sound Designer">
                            <span>Sound Designer</span>
                        </label>
                        <label class="role-checkbox">
                            <input type="checkbox" name="team_needed[]" value="UI/UX Designer">
                            <span>UI/UX Designer</span>
                        </label>
                        <label class="role-checkbox">
                            <input type="checkbox" name="team_needed[]" value="Marketing">
                            <span>Marketing</span>
                        </label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeIdeaModal()">{{ __('lamgame.ideas.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('lamgame.ideas.submit') }}</button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <style>
        .idea-hero {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        
        .idea-hero h1 {
            font-size: 3.5rem;
            margin-bottom: 25px;
            font-weight: 800;
        }
        
        .hero-subtitle {
            font-size: 1.4rem;
            max-width: 800px;
            margin: 0 auto 40px;
            opacity: 0.95;
            line-height: 1.7;
        }
        
        .hero-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 50px;
        }
        
        .idea-stats {
            padding: 60px 0;
            background: #f8f9fa;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #ff6b35;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: #666;
            font-weight: 500;
        }
        
        .how-it-works {
            padding: 80px 0;
        }
        
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
            margin-top: 60px;
        }
        
        .step-card {
            text-align: center;
            position: relative;
            padding: 40px 20px;
        }
        
        .step-number {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 40px;
            background: #ff6b35;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .step-icon {
            font-size: 4rem;
            margin: 20px 0;
        }
        
        .step-card h3 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .trending-section {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 50px;
        }
        
        .filter-tabs {
            display: flex;
            gap: 10px;
        }
        
        .filter-tab {
            padding: 10px 20px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .filter-tab.active, .filter-tab:hover {
            background: #ff6b35;
            color: white;
            border-color: #ff6b35;
        }
        
        .ideas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
        }
        
        .idea-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .idea-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .idea-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .idea-genre {
            background: #ff6b35;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .idea-platform {
            background: #667eea;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .idea-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
            line-height: 1.3;
        }
        
        .idea-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .idea-author {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            color: #666;
            font-size: 0.9rem;
        }
        
        .idea-date {
            margin-left: auto;
            font-size: 0.8rem;
            color: #999;
        }
        
        .team-needed {
            background: #fff9e6;
            border: 2px solid #ffd700;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .team-needed h4 {
            margin-bottom: 15px;
            color: #b8860b;
        }
        
        .needed-roles {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .role-tag {
            background: #ffd700;
            color: #b8860b;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .idea-stats {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .idea-actions {
            display: flex;
            gap: 10px;
        }
        
        .idea-actions .btn {
            flex: 1;
            padding: 12px;
            text-align: center;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .btn-primary {
            background: #ff6b35;
            color: white;
        }
        
        .btn-outline {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }
        
        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .success-stories {
            padding: 80px 0;
        }
        
        .stories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 40px;
            margin-top: 50px;
        }
        
        .story-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .story-card:hover {
            transform: translateY(-5px);
        }
        
        .story-image {
            height: 200px;
            overflow: hidden;
        }
        
        .story-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .story-content {
            padding: 30px;
        }
        
        .story-content h3 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .story-author {
            margin-top: 20px;
            color: #ff6b35;
            font-weight: 600;
        }
        
        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.8);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px;
            border-bottom: 1px solid #eee;
        }
        
        .modal-header h2 {
            margin: 0;
            color: #333;
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 2rem;
            cursor: pointer;
            color: #999;
        }
        
        .idea-form {
            padding: 30px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: #ff6b35;
        }
        
        .roles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .role-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 10px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .role-checkbox:hover, .role-checkbox:has(input:checked) {
            background: #fff9e6;
            border-color: #ffd700;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        @media (max-width: 768px) {
            .idea-hero h1 {
                font-size: 2.5rem;
            }
            
            .hero-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .section-header {
                flex-direction: column;
                gap: 20px;
            }
            
            .filter-tabs {
                flex-wrap: wrap;
            }
            
            .ideas-grid {
                grid-template-columns: 1fr;
            }
            
            .stories-grid {
                grid-template-columns: 1fr;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .idea-actions {
                flex-direction: column;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function showIdeaForm() {
            document.getElementById('ideaModal').style.display = 'flex';
        }
        
        function closeIdeaModal() {
            document.getElementById('ideaModal').style.display = 'none';
        }
        
        function submitIdea(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData.entries());
            
            // Handle team_needed array
            const teamNeeded = formData.getAll('team_needed[]');
            data.team_needed = teamNeeded;
            
            console.log('Submitting idea:', data);
            
            // Here you would normally send to server
            alert(LG_I18N.posted);
            closeIdeaModal();
            event.target.reset();
        }
        
        function filterIdeas(filter) {
            console.log('Filter ideas:', filter);
            
            const ideas = document.querySelectorAll('.idea-card');
            const tabs = document.querySelectorAll('.filter-tab');
            
            // Update active tab
            tabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');
            
            // Filter ideas
            ideas.forEach(idea => {
                let show = false;
                
                if (filter === 'all') {
                    show = true;
                } else if (filter === 'need-team') {
                    show = idea.querySelector('.team-needed') !== null;
                } else {
                    const platform = idea.dataset.platform.toLowerCase();
                    show = platform.includes(filter.toLowerCase());
                }
                
                idea.style.display = show ? 'block' : 'none';
            });
        }
        
        function likeIdea(id) {
            console.log('Like idea:', id);
            alert(LG_I18N.liked);
        }
        
        function joinTeam(id) {
            console.log('Join team for idea:', id);
            alert(LG_I18N.joinTeam);
        }
        
        function viewIdeaDetail(id) {
            console.log('View idea detail:', id);
            alert(LG_I18N.detail);
        }
        
        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('ideaModal');
            if (event.target === modal) {
                closeIdeaModal();
            }
        });
        
        // Scroll to section function
        function scrollToSection(selector) {
            document.querySelector(selector)?.scrollIntoView({
                behavior: 'smooth'
            });
        }
    </script>
    @endpush
@endsection
