<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Marketer Dashboard - Agii')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        :root {
            --primary: #94c953;
            --primary-dark: #7db437;
            --primary-light: #e9f5d8;
            --secondary: #6c757d;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1f2937;
            --light: #f8fafc;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
            --header-height: 70px;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --gradient-primary: linear-gradient(135deg, #94c953 0%, #7db437 100%);
            --gradient-success: linear-gradient(135deg, #10b981 0%, #0da271 100%);
            --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            --gradient-info: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fb;
            color: #374151;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Layout */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            z-index: 1000;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--dark);
        }

        .logo-icon {
            color: var(--primary);
            font-size: 1.5rem;
        }

        .logo-text {
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .logo-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .toggle-sidebar {
            background: none;
            border: none;
            color: var(--secondary);
            font-size: 1.25rem;
            cursor: pointer;
            transition: color 0.2s;
        }

        .toggle-sidebar:hover {
            color: var(--primary);
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            color: var(--secondary);
            text-decoration: none;
            transition: all 0.2s;
            position: relative;
        }

        .menu-item:hover,
        .menu-item.active {
            color: var(--primary);
            background-color: var(--primary-light);
        }

        .menu-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--primary);
        }

        .menu-icon {
            font-size: 1.25rem;
            width: 24px;
            margin-right: 0.75rem;
            transition: margin 0.3s ease;
        }

        .sidebar.collapsed .menu-icon {
            margin-right: 0;
        }

        .menu-text {
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .menu-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .menu-badge {
            margin-left: auto;
            background: var(--primary);
            color: white;
            border-radius: 20px;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .sidebar.collapsed .menu-badge {
            display: none;
        }

        .menu-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 0.5rem 1.25rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Header */
        .header {
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--secondary);
            font-size: 1.25rem;
            cursor: pointer;
        }

        .page-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-icon {
            background: none;
            border: none;
            color: var(--secondary);
            font-size: 1.25rem;
            cursor: pointer;
            position: relative;
            transition: color 0.2s;
        }

        .header-icon:hover {
            color: var(--primary);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 500;
            color: var(--dark);
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.8rem;
            color: var(--secondary);
        }

        /* Content Area */
        .content-area {
            padding: 1.5rem;
        }

        /* Welcome Card */
        .welcome-card {
            background: var(--gradient-primary);
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .welcome-card .card-body {
            padding: 2rem;
        }

        .referral-badge {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
        }

        /* Stats Cards */
        .stats-card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            overflow: hidden;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--card-shadow-hover);
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
        }

        .stats-card-primary::before {
            background: var(--primary);
        }

        .stats-card-success::before {
            background: var(--success);
        }

        .stats-card-info::before {
            background: #0ea5e9;
        }

        .stats-card-warning::before {
            background: var(--warning);
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stats-icon-primary {
            background: rgba(148, 201, 83, 0.1);
            color: var(--primary);
        }

        .stats-icon-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .stats-icon-info {
            background: rgba(14, 165, 233, 0.1);
            color: #0ea5e9;
        }

        .stats-icon-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .stats-number {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stats-label {
            color: var(--secondary);
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Quick Actions */
        .quick-actions-card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .action-btn {
            border: none;
            border-radius: 12px;
            padding: 1.5rem 1rem;
            text-align: center;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
            display: block;
            height: 100%;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--card-shadow-hover);
            color: white;
        }

        .action-btn-primary {
            background: var(--gradient-primary);
        }

        .action-btn-success {
            background: var(--gradient-success);
        }

        .action-btn-info {
            background: var(--gradient-info);
        }

        .action-btn-warning {
            background: var(--gradient-warning);
        }

        .action-icon {
            font-size: 2rem;
            margin-bottom: 0.75rem;
            display: block;
        }

        /* Content Cards */
        .content-card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .content-card:hover {
            box-shadow: var(--card-shadow-hover);
        }

        .card-header-modern {
            background: white;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
            border-radius: 16px 16px 0 0 !important;
        }

        .card-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0;
            font-size: 1.1rem;
        }

        .card-body-modern {
            padding: 1.5rem;
        }

        /* Table Styles */
        .table-modern {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .table-modern thead th {
            background-color: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
            border: none;
            padding: 1rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-modern tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }

        .table-modern tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Badge Styles */
        .badge-modern {
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        .badge-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .badge-secondary {
            background-color: rgba(107, 114, 128, 0.1);
            color: var(--secondary);
        }

        /* Avatar */
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            border-radius: 10px;
            object-fit: cover;
        }

        /* Button Styles */
        .btn-modern {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
        }

        .btn-primary-modern {
            background-color: var(--primary);
            border: none;
        }

        .btn-primary-modern:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(148, 201, 83, 0.2);
        }

        .btn-outline-modern {
            background-color: transparent;
            border: 1px solid #e5e7eb;
            color: var(--secondary);
        }

        .btn-outline-modern:hover {
            background-color: #f8fafc;
            border-color: #d1d5db;
        }

        /* Referral Section */
        .referral-code-box {
            background: var(--primary-light);
            border: 2px dashed var(--primary);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .referral-code {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        /* Empty States */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--secondary);
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Modal Styles */
        .modal-modern .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow-hover);
        }

        .modal-modern .modal-header {
            border-bottom: 1px solid #f1f5f9;
            padding: 1.5rem;
        }

        .modal-modern .modal-body {
            padding: 1.5rem;
        }

        /* Chart Container */
        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            border-radius: 12px;
            box-shadow: var(--card-shadow-hover);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.expanded {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
            }

            .user-info {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .content-area {
                padding: 1rem;
            }

            .welcome-card .card-body {
                padding: 1.5rem;
            }

            .card-body-modern {
                padding: 1.25rem;
            }

            .action-btn {
                margin-bottom: 1rem;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        @include('layout.components.sidebar')

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Header -->
            @include('layout.components.header')

            <!-- Content Area -->
            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Modals -->
    @include('layout.components.modals')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleSidebar = document.getElementById('toggleSidebar');
        const mobileToggle = document.getElementById('mobileToggle');

        toggleSidebar.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');

            // Change icon based on state
            const icon = this.querySelector('i');
            if (sidebar.classList.contains('collapsed')) {
                icon.className = 'fas fa-chevron-right';
            } else {
                icon.className = 'fas fa-chevron-left';
            }
        });

        mobileToggle.addEventListener('click', function() {
            sidebar.classList.toggle('mobile-open');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 992) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnMobileToggle = mobileToggle.contains(event.target);

                if (!isClickInsideSidebar && !isClickOnMobileToggle && sidebar.classList.contains('mobile-open')) {
                    sidebar.classList.remove('mobile-open');
                }
            }
        });

        // Copy referral link
        function copyReferralLink() {
            const referralLink = document.getElementById('referralLink');
            referralLink.select();
            referralLink.setSelectionRange(0, 99999);
            document.execCommand('copy');

            showNotification('Referral link copied to clipboard!', 'success');
        }

        // Copy text
        function copyText(text) {
            navigator.clipboard.writeText(text).then(function() {
                showNotification('Copied to clipboard!', 'success');
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }

        // Show notification
        function showNotification(message, type = 'success') {
            // Remove existing notifications
            document.querySelectorAll('.notification').forEach(notification => {
                notification.remove();
            });

            // Create notification element
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} notification`;
            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle me-2"></i>
                    <span>${message}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            `;

            document.body.appendChild(notification);

            // Auto remove after 3 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 3000);
        }

        // Withdraw form submission
        document.getElementById('withdrawForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('{{ route('marketer.withdraw.request') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        $('#withdrawModal').modal('hide');
                        // Reload page to update earnings
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        showNotification(data.message, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred. Please try again.', 'danger');
                });
        });
    </script>

    @stack('scripts')
</body>

</html>
