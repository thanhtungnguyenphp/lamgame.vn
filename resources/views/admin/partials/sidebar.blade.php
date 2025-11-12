<nav class="sidebar-nav" aria-label="Admin navigation">
    <div class="sidebar-header">
        <h2 class="sidebar-title">Admin Panel</h2>
    </div>

    <ul class="sidebar-menu">
        <li class="sidebar-menu__item">
            <a href="{{ route('admin.dashboard.index') }}" class="sidebar-menu__link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                <i class="icon-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="sidebar-menu__item">
            <a href="{{ route('admin.jobs.index') }}" class="sidebar-menu__link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}">
                <i class="icon-briefcase"></i>
                <span>Quản Lý Jobs</span>
            </a>
        </li>

        <li class="sidebar-menu__item">
            <a href="{{ route('admin.applications.index') }}" class="sidebar-menu__link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                <i class="icon-users"></i>
                <span>Ứng Viên</span>
            </a>
        </li>

        <li class="sidebar-menu__item">
            <a href="{{ route('admin.companies.index') }}" class="sidebar-menu__link {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}">
                <i class="icon-building"></i>
                <span>Công Ty</span>
            </a>
        </li>

        <li class="sidebar-menu__item">
            <a href="{{ route('admin.settings') }}" class="sidebar-menu__link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class="icon-settings"></i>
                <span>Cài Đặt</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <a href="{{ route('home') }}" class="sidebar-menu__link">
            <i class="icon-arrow-left"></i>
            <span>Về trang chủ</span>
        </a>
    </div>
</nav>
