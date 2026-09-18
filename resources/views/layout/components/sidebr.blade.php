<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fas fa-leaf logo-icon"></i>
            <span class="logo-text">Agii Marketer</span>
        </div>
        <button class="toggle-sidebar" id="toggleSidebar">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>

    <div class="sidebar-menu">
        <a href="{{ route('marketer.dashboard') }}" class="menu-item">
            <i class="fas fa-home menu-icon"></i>
            <span class="menu-text">Dashboard</span>
        </a>

        <a href="{{ route('marketer.vendors.index') }}" class="menu-item ">
            <i class="fas fa-users menu-icon"></i>
            <span class="menu-text">All Vendors</span>
            <span class="menu-badge">{{ $stats['total_vendors'] ?? session('total_vendors') }}</span>
        </a>
        <a href="{{ url('/logout') }}" class="menu-item">
            <i class="fas fa-lock menu-icon"></i>
            <span class="menu-text">Log Out</span>
        </a>
        {{--
        <a href="#" class="menu-item">
            <i class="fas fa-money-bill-wave menu-icon"></i>
            <span class="menu-text">Commissions</span>
        </a> --}}
        {{--
        <a href="#" class="menu-item">
            <i class="fas fa-chart-line menu-icon"></i>
            <span class="menu-text">Analytics</span>
        </a>

        <div class="menu-divider"></div>

        <a href="#" class="menu-item">
            <i class="fas fa-user-cog menu-icon"></i>
            <span class="menu-text">Profile</span>
        </a> --}}
        {{--
        <a href="#" class="menu-item">
            <i class="fas fa-cog menu-icon"></i>
            <span class="menu-text">Settings</span>
        </a>

        <a href="#" class="menu-item">
            <i class="fas fa-question-circle menu-icon"></i>
            <span class="menu-text">Help & Support</span>
        </a> --}}
    </div>
</div>
