<!DOCTYPE html>
<html lang="en">

<head>
    <title>Vendor Dashboard - Agii</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #8fc74a;
            --primary-light: #f0f7e6;
            --secondary-color: #7ab436;
            --accent-color: #8fc74a;
            --success-color: #8fc74a;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --dark-color: #2c3e50;
            --light-dark-color: #7f8c8d;
            --light-grey-color: #f8f9fa;
            --border-color: #dee2e6;
            --card-bg: #ffffff;
            --shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-color);
        }

        /* Sidebar */
        .sidebar {
            background: var(--card-bg);
            min-height: 100vh;
            box-shadow: var(--shadow);
            position: fixed;
            width: 280px;
            transition: var(--transition);
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            background: var(--primary-color);
            color: white;
        }

        .sidebar-logo {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link {
            color: var(--dark-color);
            padding: 12px 20px;
            border-radius: 0;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--primary-light);
            color: var(--primary-color);
            border-right: 3px solid var(--primary-color);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 20px;
            transition: var(--transition);
        }

        /* Top Navigation */
        .top-nav {
            background: var(--card-bg);
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 400px;
        }

        .search-box input {
            border-radius: 20px;
            padding-left: 40px;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--light-dark-color);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            margin: 0;
        }

        .user-role {
            color: var(--light-dark-color);
            font-size: 0.875rem;
            margin: 0;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 25px;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary-color);
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: var(--primary-light);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }

        .stat-label {
            color: var(--light-dark-color);
            margin: 0;
        }

        .stat-change {
            font-size: 0.875rem;
            font-weight: 600;
        }

        .change-positive {
            color: var(--success-color);
        }

        .change-negative {
            color: var(--danger-color);
        }

        /* Dashboard Cards */
        .dashboard-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 25px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
            transition: var(--transition);
        }

        .dashboard-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            color: var(--dark-color);
        }

        .card-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        /* Table Styles */
        .table {
            margin: 0;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--dark-color);
            background: var(--light-grey-color);
        }

        .table td {
            vertical-align: middle;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-active {
            background: var(--primary-light);
            color: var(--success-color);
        }

        .status-pending {
            background: #fff3cd;
            color: var(--warning-color);
        }

        .status-sold {
            background: #d1ecf1;
            color: #17a2b8;
        }

        .status-inactive {
            background: #f8d7da;
            color: var(--danger-color);
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .action-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
            cursor: pointer;
            border: 2px solid transparent;
        }

        .action-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-3px);
        }

        .action-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: var(--primary-light);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 15px;
        }

        .action-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .action-desc {
            color: var(--light-dark-color);
            font-size: 0.875rem;
        }

        /* Chart Container */
        .chart-container {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 25px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
        }

        /* Recent Activity */
        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            margin: 0 0 5px 0;
        }

        .activity-desc {
            color: var(--light-dark-color);
            margin: 0;
            font-size: 0.875rem;
        }

        .activity-time {
            color: var(--light-dark-color);
            font-size: 0.75rem;
        }

        /* Mobile Toggle */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--dark-color);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 250px;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .top-nav {
                flex-direction: column;
                gap: 15px;
            }

            .search-box {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h1 class="sidebar-logo">Agii Vendor</h1>
        </div>

        <div class="sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-box"></i>
                        Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-tools"></i>
                        Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-cart"></i>
                        Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-people"></i>
                        Customers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-graph-up"></i>
                        Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-wallet2"></i>
                        Earnings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-chat-left"></i>
                        Messages
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-star"></i>
                        Reviews
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-gear"></i>
                        Settings
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>

            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control" placeholder="Search orders, products, customers...">
            </div>

            <div class="user-menu">
                <div class="user-info">
                    <p class="user-name">John Doe</p>
                    <p class="user-role">Electronics Vendor</p>
                </div>
                <div class="user-avatar">
                    JD
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <h3 class="stat-value">₦245,800</h3>
                <p class="stat-label">Total Earnings</p>
                <span class="stat-change change-positive">
                    <i class="bi bi-arrow-up"></i> 12.5% from last month
                </span>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-cart"></i>
                </div>
                <h3 class="stat-value">48</h3>
                <p class="stat-label">Total Orders</p>
                <span class="stat-change change-positive">
                    <i class="bi bi-arrow-up"></i> 8.2% from last month
                </span>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-box"></i>
                </div>
                <h3 class="stat-value">23</h3>
                <p class="stat-label">Active Products</p>
                <span class="stat-change change-positive">
                    <i class="bi bi-arrow-up"></i> 3 new this week
                </span>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-star"></i>
                </div>
                <h3 class="stat-value">4.8</h3>
                <p class="stat-label">Average Rating</p>
                <span class="stat-change change-positive">
                    <i class="bi bi-arrow-up"></i> 0.2 this month
                </span>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="quick-actions">
                <div class="action-card" onclick="location.href='/sell'">
                    <div class="action-icon">
                        <i class="bi bi-plus-circle"></i>
                    </div>
                    <h5 class="action-title">Add New Product</h5>
                    <p class="action-desc">List a new product for sale</p>
                </div>

                <div class="action-card" onclick="location.href='/services/new'">
                    <div class="action-icon">
                        <i class="bi bi-tools"></i>
                    </div>
                    <h5 class="action-title">Add Service</h5>
                    <p class="action-desc">Offer a new service</p>
                </div>

                <div class="action-card" onclick="location.href='/orders'">
                    <div class="action-icon">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <h5 class="action-title">Manage Orders</h5>
                    <p class="action-desc">Process pending orders</p>
                </div>

                <div class="action-card" onclick="location.href='/analytics'">
                    <div class="action-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h5 class="action-title">View Analytics</h5>
                    <p class="action-desc">Check business performance</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Recent Orders -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Orders</h3>
                        <div class="card-actions">
                            <button class="btn btn-outline-primary btn-sm">View All</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#ORD-7842</td>
                                    <td>Sarah Johnson</td>
                                    <td>iPhone 13 Pro</td>
                                    <td>₦450,000</td>
                                    <td><span class="status-badge status-active">Completed</span></td>
                                    <td>2 hours ago</td>
                                </tr>
                                <tr>
                                    <td>#ORD-7841</td>
                                    <td>Mike Chen</td>
                                    <td>MacBook Air M2</td>
                                    <td>₦680,000</td>
                                    <td><span class="status-badge status-pending">Processing</span></td>
                                    <td>5 hours ago</td>
                                </tr>
                                <tr>
                                    <td>#ORD-7840</td>
                                    <td>Emily Davis</td>
                                    <td>AirPods Pro</td>
                                    <td>₦120,000</td>
                                    <td><span class="status-badge status-sold">Shipped</span></td>
                                    <td>1 day ago</td>
                                </tr>
                                <tr>
                                    <td>#ORD-7839</td>
                                    <td>Robert Brown</td>
                                    <td>iPad Air</td>
                                    <td>₦320,000</td>
                                    <td><span class="status-badge status-active">Delivered</span></td>
                                    <td>2 days ago</td>
                                </tr>
                                <tr>
                                    <td>#ORD-7838</td>
                                    <td>Lisa Wang</td>
                                    <td>Smart Watch</td>
                                    <td>₦85,000</td>
                                    <td><span class="status-badge status-inactive">Cancelled</span></td>
                                    <td>3 days ago</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Sales Chart -->
                <div class="chart-container">
                    <div class="card-header">
                        <h3 class="card-title">Sales Overview</h3>
                        <div class="card-actions">
                            <select class="form-select form-select-sm" style="width: auto;">
                                <option>Last 7 days</option>
                                <option>Last 30 days</option>
                                <option>Last 3 months</option>
                            </select>
                        </div>
                    </div>
                    <div
                        style="height: 300px; background: var(--light-grey-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--light-dark-color);">
                        <div class="text-center">
                            <i class="bi bi-bar-chart" style="font-size: 3rem; margin-bottom: 10px;"></i>
                            <p>Sales chart will be displayed here</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Recent Activity -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Activity</h3>
                    </div>
                    <ul class="activity-list">
                        <li class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-cart-check"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="activity-title">New Order Received</h6>
                                <p class="activity-desc">Order #ORD-7842 for iPhone 13 Pro</p>
                            </div>
                            <div class="activity-time">2 min ago</div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-star"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="activity-title">New Review</h6>
                                <p class="activity-desc">Sarah Johnson rated 5 stars</p>
                            </div>
                            <div class="activity-time">1 hour ago</div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-box"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="activity-title">Product Added</h6>
                                <p class="activity-desc">New product "Wireless Earbuds" listed</p>
                            </div>
                            <div class="activity-time">3 hours ago</div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-chat-left"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="activity-title">New Message</h6>
                                <p class="activity-desc">From Mike Chen about MacBook</p>
                            </div>
                            <div class="activity-time">5 hours ago</div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="activity-title">Payment Received</h6>
                                <p class="activity-desc">₦450,000 for Order #ORD-7841</p>
                            </div>
                            <div class="activity-time">1 day ago</div>
                        </li>
                    </ul>
                </div>

                <!-- Top Products -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Top Products</h3>
                    </div>
                    <ul class="activity-list">
                        <li class="activity-item">
                            <div class="activity-icon" style="background: #e3f2fd; color: #1976d2;">
                                <i class="bi bi-phone"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="activity-title">iPhone 13 Pro</h6>
                                <p class="activity-desc">12 sales • ₦5.4M revenue</p>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon" style="background: #f3e5f5; color: #7b1fa2;">
                                <i class="bi bi-laptop"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="activity-title">MacBook Air M2</h6>
                                <p class="activity-desc">8 sales • ₦5.44M revenue</p>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon" style="background: #e8f5e8; color: #388e3c;">
                                <i class="bi bi-headphones"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="activity-title">AirPods Pro</h6>
                                <p class="activity-desc">15 sales • ₦1.8M revenue</p>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon" style="background: #fff3e0; color: #f57c00;">
                                <i class="bi bi-tablet"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="activity-title">iPad Air</h6>
                                <p class="activity-desc">6 sales • ₦1.92M revenue</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobile sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Simulate some dynamic data updates
        function updateStats() {
            // This would typically come from an API
            const stats = [{
                    element: '.stat-card:nth-child(1) .stat-value',
                    value: '₦' + (245800 + Math.floor(Math.random() * 5000)).toLocaleString()
                },
                {
                    element: '.stat-card:nth-child(2) .stat-value',
                    value: (48 + Math.floor(Math.random() * 3)).toString()
                },
                {
                    element: '.stat-card:nth-child(3) .stat-value',
                    value: (23 + Math.floor(Math.random() * 2)).toString()
                }
            ];

            stats.forEach(stat => {
                const element = document.querySelector(stat.element);
                if (element) {
                    element.style.transform = 'scale(1.1)';
                    setTimeout(() => {
                        element.textContent = stat.value;
                        element.style.transform = 'scale(1)';
                    }, 300);
                }
            });
        }

        // Update stats every 30 seconds (simulated)
        setInterval(updateStats, 30000);

        // Add loading animation to action cards
        document.querySelectorAll('.action-card').forEach(card => {
            card.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });

        // Simulate real-time notifications
        function simulateNewOrder() {
            const activities = document.querySelector('.activity-list');
            const newActivity = document.createElement('li');
            newActivity.className = 'activity-item';
            newActivity.innerHTML = `
                <div class="activity-icon">
                    <i class="bi bi-cart-plus"></i>
                </div>
                <div class="activity-content">
                    <h6 class="activity-title">New Order Placed</h6>
                    <p class="activity-desc">Order #ORD-${7843 + Math.floor(Math.random() * 10)} for Wireless Earbuds</p>
                </div>
                <div class="activity-time">just now</div>
            `;

            activities.insertBefore(newActivity, activities.firstChild);

            // Remove oldest activity if more than 5
            if (activities.children.length > 5) {
                activities.removeChild(activities.lastChild);
            }

            // Update orders count
            const ordersElement = document.querySelector('.stat-card:nth-child(2) .stat-value');
            const currentOrders = parseInt(ordersElement.textContent);
            ordersElement.textContent = (currentOrders + 1).toString();
        }

        // Simulate new orders randomly (for demo purposes)
        setInterval(simulateNewOrder, 60000);
    </script>
</body>

</html>
