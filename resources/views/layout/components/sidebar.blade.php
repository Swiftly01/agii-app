<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <!--<i class="fas fa-leaf logo-icon"></i>-->
            <!--<span class="logo-text">Agii</span>-->
            <img src='https://agii.ng/images/Agiilogo2.png' class='logo-text ' width='100' />
        </div>
        <button class="toggle-sidebar" id="toggleSidebar">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>

    <div class="sidebar-menu">

        @if (Auth::user()->user_type == 'marketer')
            <a href="{{ route('marketer.dashboard') }}" class="menu-item">
                <i class="fas fa-home menu-icon"></i>
                <span class="menu-text">Dashboard</span>
            </a>

            <a href="{{ route('marketer.vendors.index') }}" class="menu-item ">
                <i class="fas fa-users menu-icon"></i>
                <span class="menu-text">All Vendors</span>
                <span class="menu-badge">{{ $stats['total_vendors'] ?? session('total_vendors') }}</span>
            </a>
            
            <a href="{{ url('chat') }}" class="menu-item">
                <i class="fas fa-box menu-icon"></i>
                <span class="menu-text">Chat</span>
            </a>
            
            
            
            <a href="{{ route('marketer.tasks.index') }}" class="menu-item ">
                <i class="fas fa-users menu-icon"></i>
                <span class="menu-text">Task</span>
                <span class="menu-badge">{{ $stats['total_vendors'] ?? session('total_vendors') }}</span>
            </a>
           
        @else
            <a href="{{ route('admin.dashboard') }}" class="menu-item">
                <i class="fas fa-home menu-icon"></i>
                <span class="menu-text">Dashboard</span>
            </a>

            <a href="{{ route('admin.vendors.index') }}" class="menu-item">
                <i class="fas fa-home menu-icon"></i>
                <span class="menu-text">Vendor</span>
            </a>
            
            <a href="{{ url('https://agii.ng/admin/employment/applications') }}" class="menu-item">
                <i class="fas fa-user menu-icon"></i>
                <span class="menu-text">HR</span>
            </a>
            {{--
            <a href="{{ url('https://agii.ng/public/admin/offer-letters') }}" class="menu-item">
                <i class="fas fa-user menu-icon"></i>
                <span class="menu-text">Offer Letter</span>
            </a>
            
             <a href="{{ url('https://agii.ng/public/admin/documents/pending') }}" class="menu-item">
                <i class="fas fa-user menu-icon"></i>
                <span class="menu-text">Staff Document</span>
            </a>
            --}}
            
            
            
            
            <a href="{{ route('admin.customers.index') }}" class="menu-item">
                <i class="fas fa-box menu-icon"></i>
                <span class="menu-text">Customer</span>
            </a>

            <a href="{{ route('admin.users.create') }}" class="menu-item">
                <i class="fas fa-box menu-icon"></i>
                <span class="menu-text">Add User</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="menu-item">
                <i class="fas fa-box menu-icon"></i>
                <span class="menu-text">Assign Role </span>
            </a>

            <a href="{{ route('admin.products.pendinglist') }}" class="menu-item">
                <i class="fas fa-box menu-icon"></i>
                <span class="menu-text">Pending Advert</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="menu-item">
                <i class="fas fa-box menu-icon"></i>
                <span class="menu-text">All Advert</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="menu-item">
                <i class="fas fa-sitemap menu-icon"></i>
                <span class="menu-text">Categories</span>
            </a>
            
            
            <a href="{{ route('admin.tasks.index') }}" class="menu-item">
                <i class="fas fa-box menu-icon"></i>
                <span class="menu-text">Tasks</span>
            </a>
            
            <a href="{{ url('chat') }}" class="menu-item">
                <i class="fas fa-box menu-icon"></i>
                <span class="menu-text">Chat</span>
            </a>

           
        @endif
        
        
         <a href="{{ url('https://agii.ng:2096/cpsess5708345621/3rdparty/roundcube/?_task=mail&_mbox=INBOX') }}" class="menu-item">
                <i class="fas fa-lock menu-icon"></i>
                <span class="menu-text">Email</span>
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
