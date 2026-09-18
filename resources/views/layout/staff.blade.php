{{-- resources/views/layouts/staff.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', config('app.name', 'Staff Portal'))</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="{{ asset('css/staff.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="bg-light">
    <!-- Wrapper -->
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    <div class="logo">
                        @if(file_exists(public_path('/images/Agiilogo2.png')))
                            <img src="{{ asset('https://agii.ng/images/Agiilogo2.png') }}" alt="Company Logo" class="img-fluid" width='300'>
                        @else
                            <i class="fas fa-building"></i>
                        @endif
                    </div>
                    <small class="text-white">Staff Dashboard</small>
                </div>
                <button type="button" id="sidebarCollapse" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <!-- Staff Profile Card -->
            <div class="staff-profile-card">
                <div class="text-center mb-3">
                    @if(Auth::user()->staffProfile && Auth::user()->staffProfile->passport_photo)
                        <img src="{{ Storage::url(Auth::user()->staffProfile->passport_photo) }}" 
                             alt="Profile" class="rounded-circle profile-img">
                    @else
                        <div class="rounded-circle profile-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>
                <div class="text-center">
                    <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                    <p class="text-muted mb-1">
                        {{ Auth::user()->staffProfile->designation ?? 'Staff' }}
                    </p>
                    <span class="badge bg-success">
                        {{ Auth::user()->staffProfile->staff_id ?? 'STAFF' }}
                    </span>
                </div>
            </div>
            
            <!-- Sidebar Menu -->
            <ul class="list-unstyled components">
                <li class="{{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('staff.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <!-- Profile Section -->
                <li class="{{ request()->routeIs('staff.profile.*') ? 'active' : '' }}">
                    <a href="#profileSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-user"></i>
                        <span>My Profile</span>
                    </a>
                    <ul class="collapse list-unstyled" id="profileSubmenu">
                       {{-- <li>
                            <a href="{{ route('profile.edit') }}">
                                <i class="fas fa-eye"></i> View Profile
                            </a>
                        </li>--}}
                        <li>
                            <a href="{{ route('profile.edit') }}">
                                <i class="fas fa-edit"></i> Edit Profile
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/my/documents') }}">
                                <i class="fas fa-file-alt"></i> My Documents
                            </a>
                        </li>
                    </ul>
                </li>
                  <!-- Documents Section -->
                <li class="{{ request()->routeIs('staff.documents.*') ? 'active' : '' }}">
                    <a href="#documentsSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-file-alt"></i>
                        <span>Documents</span>
                    </a>
                    <ul class="collapse list-unstyled" id="documentsSubmenu">
                        <li>
                            <a href="{{ route('staff.documents.upload') }}">
                                <i class="fas fa-upload"></i> Upload Documents
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.documents.my-documents') }}">
                                <i class="fas fa-folder"></i> My Documents
                            </a>
                        </li>
                        <li class='d-none'>
                            <a href="{{ route('staff.documents.certificates') }}">
                                <i class="fas fa-certificate"></i> Certificates
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.offer-letters.index') }}">
                                <i class="fas fa-file-contract"></i> Offer Letter
                            </a>
                        </li>
                        <li>
                                <a class="nav-link" href="{{ route('staff.hr-queries.index') }}">
                                    <i class="bi bi-inbox"></i> My Queries
                                </a>
                        </li>
                        <!--<li>-->
                        <!--    <a href="{{ route('staff.documents.contracts') }}">-->
                        <!--        <i class="fas fa-file-contract"></i> Contracts-->
                        <!--    </a>-->
                        <!--</li>-->
                        
                        
                    </ul>
                </li>
                
                
                <li class="{{ request()->routeIs('staff.payroll.*') ? 'active' : '' }}">
                    <a href="#documentsSubmenuing" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-store"></i>
                        <span>Marketing</span>
                    </a>
                    <ul class="collapse list-unstyled" id="documentsSubmenuing">
                        <li>
                            <a href="{{ url('https://agii.ng/marketer/dashboard') }}">
                                <i class="fas fa-upload"></i> Marketing Dashboard 
                            </a>
                        </li>
                     
                        
                        
                    </ul>
                </li>
                
                
                
                
                <!-- Attendance & Leave Section -->
                <li class="{{ request()->routeIs('staff.attendance.*') || request()->routeIs('staff.leave.*') ? 'active' : '' }}">
                    <a href="#attendanceSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Attendance</span>
                    </a>
                    <ul class="collapse list-unstyled" id="attendanceSubmenu">
                        <li>
                            <a href="{{ route('staff.attendance.dashboard') }}">
                                <i class="fas fa-clock"></i> Attendance
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.attendance.history') }}">
                                <i class="fas fa-history"></i> Attendance History
                            </a>
                        </li>
                        
                        
                    </li>
                      {{--  <li>
                            <a href="{{ route('staff.leave.dashboard') }}">
                                <i class="fas fa-umbrella-beach"></i> Leave Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.leave.apply') }}">
                                <i class="fas fa-file-medical"></i> Apply for Leave
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.leave.history') }}">
                                <i class="fas fa-list-alt"></i> Leave History
                            </a>
                        </li> 
                    </ul>
                </li>
                
                <!-- Payroll & Benefits Section -->
                <li class="{{ request()->routeIs('staff.payroll.*') ? 'active' : '' }}">
                    <a href="#payrollSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Payroll & Benefits</span>
                    </a>
                    <ul class="collapse list-unstyled" id="payrollSubmenu">
                        <li>
                            <a href="{{ route('staff.payroll.dashboard') }}">
                                <i class="fas fa-chart-line"></i> Payroll Overview
                            </a>
                        </li>
                        
                        <li>
                            <a href="{{ route('staff.payroll.payslips') }}">
                                <i class="fas fa-file-invoice-dollar"></i> Payslips
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.payroll.history') }}">
                                <i class="fas fa-history"></i> Payment History
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.payroll.benefits') }}">
                                <i class="fas fa-shield-alt"></i> Benefits
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.payroll.tax') }}">
                                <i class="fas fa-percentage"></i> Tax Information
                            </a>
                        </li>
                        
                    
                    </ul>
                </li>
                
                --}}
                
              
                {{--
                <!-- Tasks & Performance -->
                <li class="{{ request()->routeIs('staff.tasks.*') ? 'active' : '' }}">
                    <a href="#tasksSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-tasks"></i>
                        <span>Tasks & Performance</span>
                    </a>
                    <ul class="collapse list-unstyled" id="tasksSubmenu">
                        <li>
                            <a href="{{ route('staff.tasks.dashboard') }}">
                                <i class="fas fa-clipboard-list"></i> My Tasks
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.tasks.assignments') }}">
                                <i class="fas fa-briefcase"></i> Assignments
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.tasks.performance') }}">
                                <i class="fas fa-chart-bar"></i> Performance
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.tasks.reviews') }}">
                                <i class="fas fa-star"></i> Reviews
                            </a>
                        </li>
                    </ul>
                </li> 
                
                <!-- Company Information -->
                <li class="{{ request()->routeIs('staff.company.*') ? 'active' : '' }}">
                    <a href="#companySubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-building"></i>
                        <span>Company Info</span>
                    </a>
                    <ul class="collapse list-unstyled" id="companySubmenu">
                        <li>
                            <a href="{{ route('staff.company.announcements') }}">
                                <i class="fas fa-bullhorn"></i> Announcements
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.company.policies') }}">
                                <i class="fas fa-book"></i> Policies
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.company.directory') }}">
                                <i class="fas fa-address-book"></i> Staff Directory
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.company.holidays') }}">
                                <i class="fas fa-calendar-day"></i> Holidays
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.company.events') }}">
                                <i class="fas fa-calendar-check"></i> Events
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Support -->
                <li class="{{ request()->routeIs('staff.support.*') ? 'active' : '' }}">
                    <a href="#supportSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-life-ring"></i>
                        <span>Support</span>
                    </a>
                    <ul class="collapse list-unstyled" id="supportSubmenu">
                        <li>
                            <a href="{{ route('staff.support.tickets') }}">
                                <i class="fas fa-ticket-alt"></i> Support Tickets
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.support.faq') }}">
                                <i class="fas fa-question-circle"></i> FAQ
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.support.help') }}">
                                <i class="fas fa-info-circle"></i> Help Center
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.support.contact') }}">
                                <i class="fas fa-headset"></i> Contact HR
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Settings -->
                <li class="{{ request()->routeIs('staff.settings.*') ? 'active' : '' }}">
                    <a href="#settingsSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <ul class="collapse list-unstyled" id="settingsSubmenu">
                        <li>
                            <a href="{{ route('staff.settings.account') }}">
                                <i class="fas fa-user-cog"></i> Account Settings
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.settings.password') }}">
                                <i class="fas fa-key"></i> Change Password
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.settings.notifications') }}">
                                <i class="fas fa-bell"></i> Notifications
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('staff.settings.privacy') }}">
                                <i class="fas fa-shield-alt"></i> Privacy
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            
            --}}
            
            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="system-status">
                        <i class="fas fa-circle text-success"></i>
                        <small>System Online</small>
                    </div>
                    <form action="{{ route('logout') }}" method="GET" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </nav>
        
        <!-- Main Content -->
        <div id="content" class="content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                <div class="container-fluid">
                    <!-- Top Navbar Left -->
                    <div class="d-flex align-items-center">
                        <button type="button" id="sidebarToggle" class="btn btn-light me-3">
                            <i class="fas fa-bars"></i>
                        </button>
                        
                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Home</a></li>
                                @yield('breadcrumbs')
                            </ol>
                        </nav>
                    </div>
                    {{--
                    <!-- Top Navbar Right -->
                    <div class="d-flex align-items-center">
                        <!-- Notifications -->
                        <div class="dropdown me-3">
                            <button class="btn btn-light position-relative" type="button" id="notificationsDropdown" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                @php
                                    $notificationCount = Auth::user()->unreadNotifications()->count();
                                @endphp
                                @if($notificationCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $notificationCount }}
                                    </span>
                                @endif
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                                <li><h6 class="dropdown-header">Notifications</h6></li>
                                @if($notificationCount > 0)
                                    @foreach(Auth::user()->unreadNotifications()->take(5)->get() as $notification)
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-{{ $notification->data['icon'] ?? 'info-circle' }} text-{{ $notification->data['color'] ?? 'primary' }}"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <p class="mb-0">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-center" href="{{ route('staff.notifications') }}">View All</a></li>
                                    <li><a class="dropdown-item text-center" href="{{ route('staff.notifications.mark-all-read') }}">Mark All as Read</a></li>
                                @else
                                    <li><a class="dropdown-item text-center text-muted" href="#">No new notifications</a></li>
                                @endif
                            </ul>
                        </div> 
                        
                        <!-- Messages -->
                        <div class="dropdown me-3">
                            <button class="btn btn-light position-relative" type="button" id="messagesDropdown" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-envelope"></i>
                                @php
                                    $messageCount = Auth::user()->unreadMessages()->count();
                                @endphp
                                @if($messageCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $messageCount }}
                                    </span>
                                @endif
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="messagesDropdown">
                                <li><h6 class="dropdown-header">Messages</h6></li>
                                @if($messageCount > 0)
                                    @foreach(Auth::user()->unreadMessages()->take(3)->get() as $message)
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                                             style="width: 30px; height: 30px;">
                                                            {{ substr($message->sender->name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <p class="mb-0">{{ $message->sender->name }}</p>
                                                        <small class="text-muted">{{ Str::limit($message->message, 30) }}</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-center" href="{{ route('staff.messages') }}">View All</a></li>
                                @else
                                    <li><a class="dropdown-item text-center text-muted" href="#">No new messages</a></li>
                                @endif
                            </ul>
                        </div>
                        
                        --}}
                        
                        <!-- Quick Actions -->
                        <div class="dropdown me-3">
                            <button class="btn btn-light" type="button" id="quickActionsDropdown" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bolt"></i> Quick Actions
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="quickActionsDropdown">
                                <li><a class="dropdown-item" href="{{ route('staff.attendance.dashboard') }}"><i class="fas fa-clock"></i> Clock In/Out</a></li>
                                <li><a class="dropdown-item" href="{{ route('staff.leave.apply') }}"><i class="fas fa-file-medical"></i> Apply for Leave</a></li>
                                <li><a class="dropdown-item" href="{{ route('staff.documents.upload') }}"><i class="fas fa-upload"></i> Upload Document</a></li>
                             {{--   <li><a class="dropdown-item" href="{{ route('staff.tasks.dashboard') }}"><i class="fas fa-tasks"></i> View Tasks</a></li> 
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('staff.support.tickets') }}"><i class="fas fa-plus"></i> New Support Ticket</a></li> --}}
                            </ul>
                        </div>
                        
                        <!-- User Profile -->
                        <div class="dropdown">
                            <button class="btn btn-light d-flex align-items-center" type="button" id="userProfileDropdown" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                @if(Auth::user()->staffProfile && Auth::user()->staffProfile->passport_photo)
                                    <img src="{{ Storage::url(Auth::user()->staffProfile->passport_photo) }}" 
                                         alt="Profile" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                         style="width: 32px; height: 32px;">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down ms-2"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userProfileDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('staff.profile.view') }}">
                                        <i class="fas fa-user"></i> My Profile
                                    </a>
                                </li>
                               {{-- <li>
                                    <a class="dropdown-item" href="{{ route('staff.settings.account') }}">
                                        <i class="fas fa-cog"></i> Account Settings
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('staff.support.help') }}">
                                        <i class="fas fa-question-circle"></i> Help & Support
                                    </a>
                                </li> --}}
                                <li>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            
            <!-- Main Content Area -->
            <main class="main-content">
                <div class="container-fluid py-4">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle"></i> {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <!-- Page Content -->
                    @yield('content')
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="footer bg-white border-top">
                <div class="container-fluid py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <small class="text-muted">
                                © {{ date('Y') }} {{ config('app.name', 'Staff Portal') }}. All rights reserved.
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <i class="fas fa-user-clock"></i> Last login: {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : 'First time' }}
                            </small>
                            <span class="mx-2">•</span>
                            <small class="text-muted">
                                <i class="fas fa-shield-alt"></i> Secure Connection
                            </small>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <!-- Hidden Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="GET" style="display: none;">
        @csrf
    </form>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/staff.js') }}"></script>
    
    @stack('scripts')
    
    <script>
        // Sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarCollapse = document.getElementById('sidebarCollapse');
            
            // Toggle sidebar on button click
            function toggleSidebar() {
                sidebar.classList.toggle('active');
                content.classList.toggle('active');
            }
            
            sidebarToggle.addEventListener('click', toggleSidebar);
            sidebarCollapse.addEventListener('click', toggleSidebar);
            
            // Close sidebar on mobile when clicking outside
            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggle = sidebarToggle.contains(event.target);
                
                if (window.innerWidth <= 768 && !isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('active')) {
                    toggleSidebar();
                }
            });
            
            // Auto-collapse submenus on mobile
            function handleResize() {
                if (window.innerWidth <= 768) {
                    // Collapse all submenus on mobile
                    const submenus = document.querySelectorAll('.components .collapse');
                    submenus.forEach(submenu => {
                        if (submenu.classList.contains('show')) {
                            submenu.classList.remove('show');
                        }
                    });
                }
            }
            
            // Initial check
            handleResize();
            
            // Check on resize
            window.addEventListener('resize', handleResize);
            
            // Mark notification as read when clicked
            document.querySelectorAll('.dropdown-item[href^="#"]').forEach(item => {
                item.addEventListener('click', function(e) {
                    if (this.closest('#notificationsDropdown')) {
                        // In a real app, you would send an AJAX request to mark as read
                        console.log('Mark notification as read');
                    }
                });
            });
            
            // Set active state for current page
            const currentPath = window.location.pathname;
            document.querySelectorAll('.components a').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.parentElement.classList.add('active');
                    // Expand parent dropdown if exists
                    const parentDropdown = link.closest('.collapse');
                    if (parentDropdown) {
                        parentDropdown.classList.add('show');
                    }
                }
            });
        });
    </script>
</body>
</html>