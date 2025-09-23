<!-- Mobile Menu -->
<div class="mobile-menu" id="mobile-menu" role="navigation">
    <div class="mobile-menu-header">
        <div class="mobile-menu-brand">
            <img src="{{ asset('logo/lamgame-horizontal.svg') }}" alt="LAMGAME Logo" class="mobile-logo">
            <span>LAMGAME</span>
        </div>
        <button class="mobile-menu-close" onclick="closeMobileMenu()" aria-label="Close menu">
            &times;
        </button>
    </div>
    
    <nav class="mobile-nav">
        @php
            $menuItems = DB::table('menu_items')
                ->where('menu_id', 1)
                ->orderBy('sort_order')
                ->get();
        @endphp
        
        @foreach($menuItems as $item)
        <div class="mobile-nav-item">
            <a href="{{ $item->url }}" 
               target="{{ $item->target ?? '_self' }}"
               class="mobile-nav-link"
               onclick="closeMobileMenu()"
               >
               {{ $item->title }}
            </a>
        </div>
        @endforeach
    </nav>
</div>

<style>
.mobile-menu {
    position: fixed;
    top: 0;
    right: -300px;
    width: 300px;
    height: 100vh;
    background: white;
    box-shadow: -2px 0 10px rgba(0,0,0,0.1);
    transition: right 0.3s ease;
    z-index: 1000;
    overflow-y: auto;
}

.mobile-menu.open {
    right: 0;
}

.mobile-menu-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #eee;
}

.mobile-menu-brand {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: bold;
    color: #6a4c93;
}

.mobile-logo {
    width: 30px;
    height: 30px;
    border-radius: 50%;
}

.mobile-menu-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #666;
}

.mobile-nav-item {
    border-bottom: 1px solid #f0f0f0;
}

.mobile-nav-link {
    display: block;
    padding: 1rem;
    color: #333;
    text-decoration: none;
    transition: background 0.3s;
}

.mobile-nav-link:hover {
    background: #f8f9fa;
    color: #6a4c93;
}

.mobile-menu-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0,0,0,0.5);
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.mobile-menu-overlay.open {
    opacity: 1;
    visibility: visible;
}
</style>

<script>
function openMobileMenu() {
    document.getElementById('mobile-menu').classList.add('open');
    
    // Create overlay if it doesn't exist
    if (!document.getElementById('mobile-menu-overlay')) {
        const overlay = document.createElement('div');
        overlay.id = 'mobile-menu-overlay';
        overlay.className = 'mobile-menu-overlay';
        overlay.onclick = closeMobileMenu;
        document.body.appendChild(overlay);
    }
    document.getElementById('mobile-menu-overlay').classList.add('open');
}

function closeMobileMenu() {
    document.getElementById('mobile-menu').classList.remove('open');
    const overlay = document.getElementById('mobile-menu-overlay');
    if (overlay) {
        overlay.classList.remove('open');
    }
}
</script>
