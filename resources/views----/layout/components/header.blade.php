<div class="header">
    <div class="header-left">
        <button class="mobile-toggle" id="mobileToggle">
            <i class="fas fa-bars"></i>
        </button>
        <h5 class="page-title">@yield('page-title', 'Dashboard')</h5>
    </div>

    <div class="header-right">
        <button class="header-icon d-none">
            <i class="fas fa-bell"></i>
            <span class="notification-badge">3</span>
        </button>

        <button class="header-icon d-none">
            <i class="fas fa-envelope"></i>
            <span class="notification-badge">5</span>
        </button>

        <div class="user-menu">
            <div class="user-avatar">
                {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
            </div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
                <div class="user-role">Marketer</div>
            </div>
        </div>
    </div>
</div>
