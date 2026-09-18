<!DOCTYPE html>
<html lang="en">

<head>
    <title>Agii - WhatsApp Notifications & Analytics</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            --whatsapp-green: #25D366;
            --whatsapp-light: #dcf8c6;
        }

        body {
            background: linear-gradient(135deg, #f5f7fb 0%, #e8f4ff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-color);
            min-height: 100vh;
        }

        .navbar {
            background: var(--card-bg);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .notification-container {
            padding: 40px 0;
        }

        .notification-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            margin-bottom: 30px;
            transition: var(--transition);
        }

        .notification-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .whatsapp-header {
            background: linear-gradient(135deg, var(--whatsapp-green) 0%, #128C7E 100%);
            color: white;
            padding: 40px 0;
            text-align: center;
            border-radius: 15px 15px 0 0;
            margin: -30px -30px 30px -30px;
        }

        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 25px;
            color: var(--dark-color);
            font-weight: 700;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .whatsapp-title::after {
            background: var(--whatsapp-green);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: var(--shadow);
            border-top: 4px solid var(--whatsapp-green);
            transition: var(--transition);
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--whatsapp-green);
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--light-dark-color);
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .trend-up {
            color: var(--success-color);
        }

        .trend-down {
            color: var(--danger-color);
        }

        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            height: 300px;
        }

        .analytics-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
        }

        .performance-metric {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .performance-metric:last-child {
            border-bottom: none;
        }

        .metric-info {
            flex: 1;
        }

        .metric-value {
            font-weight: 700;
            font-size: 1.2rem;
        }

        .metric-bar {
            height: 8px;
            background: var(--light-grey-color);
            border-radius: 4px;
            margin-top: 5px;
            overflow: hidden;
        }

        .metric-fill {
            height: 100%;
            background: var(--whatsapp-green);
            border-radius: 4px;
            transition: width 1s ease;
        }

        .notification-type {
            background: var(--light-grey-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid var(--whatsapp-green);
        }

        .form-check-input:checked {
            background-color: var(--whatsapp-green);
            border-color: var(--whatsapp-green);
        }

        .btn {
            border-radius: 8px;
            padding: 12px 20px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-whatsapp {
            background-color: var(--whatsapp-green);
            border-color: var(--whatsapp-green);
            color: white;
        }

        .btn-whatsapp:hover {
            background-color: #128C7E;
            border-color: #128C7E;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: white;
        }

        .time-filter {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .time-filter-btn {
            padding: 8px 15px;
            border: 1px solid var(--border-color);
            background: white;
            border-radius: 6px;
            cursor: pointer;
            transition: var(--transition);
        }

        .time-filter-btn.active {
            background: var(--whatsapp-green);
            color: white;
            border-color: var(--whatsapp-green);
        }

        .comparison-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
        }

        .comparison-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }

        .comparison-label {
            font-weight: 600;
        }

        .comparison-value {
            font-weight: 700;
        }

        .footer {
            background: var(--dark-color);
            color: white;
            padding: 40px 0;
            text-align: center;
            margin-top: 60px;
        }

        .notification-preview {
            background: var(--whatsapp-light);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            border-left: 4px solid var(--whatsapp-green);
            position: relative;
        }

        .notification-preview::before {
            content: '';
            position: absolute;
            top: 20px;
            left: -10px;
            width: 0;
            height: 0;
            border-top: 10px solid transparent;
            border-bottom: 10px solid transparent;
            border-right: 10px solid var(--whatsapp-green);
        }

        .preview-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .preview-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--whatsapp-green);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            color: white;
            font-weight: bold;
        }

        .preview-message {
            background: white;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 10px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            max-width: 80%;
        }

        .preview-time {
            font-size: 0.8rem;
            color: var(--light-dark-color);
            text-align: right;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .notification-container {
                padding: 20px 0;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">Agii</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Listings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Messages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Notifications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Analytics</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container notification-container">
        <div class="row">
            <div class="col-lg-8">
                <!-- WhatsApp Notification Card -->
                <div class="notification-card">
                    <div class="whatsapp-header">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-whatsapp me-3" style="font-size: 2rem;"></i>
                            <div>
                                <h2 class="mb-1">WhatsApp Notifications</h2>
                                <p class="mb-0">Stay updated with real-time alerts & track performance</p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Overview -->
                    <h4 class="section-title whatsapp-title">Performance Overview</h4>

                    <!-- Time Filter -->
                    <div class="time-filter">
                        <div class="time-filter-btn active" data-period="7d">7 Days</div>
                        <div class="time-filter-btn" data-period="30d">30 Days</div>
                        <div class="time-filter-btn" data-period="90d">90 Days</div>
                        <div class="time-filter-btn" data-period="1y">1 Year</div>
                    </div>

                    <!-- Stats Grid -->
                    <div class="stats-grid">
                        <div class="stat-card" id="deliveryRateCard">
                            <div class="stat-number">94.7%</div>
                            <div class="stat-label">Delivery Rate</div>
                            <div class="stat-trend trend-up">
                                <i class="bi bi-arrow-up-right me-1"></i> 2.3%
                            </div>
                        </div>
                        <div class="stat-card" id="notificationsSentCard">
                            <div class="stat-number">12,458</div>
                            <div class="stat-label">Notifications Sent</div>
                            <div class="stat-trend trend-up">
                                <i class="bi bi-arrow-up-right me-1"></i> 15.7%
                            </div>
                        </div>
                        <div class="stat-card" id="deliveryTimeCard">
                            <div class="stat-number">2.1s</div>
                            <div class="stat-label">Avg. Delivery Time</div>
                            <div class="stat-trend trend-down">
                                <i class="bi bi-arrow-down-right me-1"></i> 0.3s
                            </div>
                        </div>
                        <div class="stat-card" id="customerRepliesCard">
                            <div class="stat-number">642</div>
                            <div class="stat-label">Customer Replies</div>
                            <div class="stat-trend trend-up">
                                <i class="bi bi-arrow-up-right me-1"></i> 8.2%
                            </div>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="chart-container">
                                <h5 class="mb-3">Notifications by Type</h5>
                                <canvas id="typeChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="chart-container">
                                <h5 class="mb-3">Delivery Performance</h5>
                                <canvas id="deliveryChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Metrics -->
                    <div class="analytics-card">
                        <h5 class="mb-3">Notification Performance</h5>
                        <div class="performance-metric">
                            <div class="metric-info">
                                <div class="metric-value">Order Notifications</div>
                                <div class="metric-bar">
                                    <div class="metric-fill" style="width: 96%" data-value="96"></div>
                                </div>
                            </div>
                            <div class="metric-stat">96%</div>
                        </div>
                        <div class="performance-metric">
                            <div class="metric-info">
                                <div class="metric-value">Shipping Updates</div>
                                <div class="metric-bar">
                                    <div class="metric-fill" style="width: 92%" data-value="92"></div>
                                </div>
                            </div>
                            <div class="metric-stat">92%</div>
                        </div>
                        <div class="performance-metric">
                            <div class="metric-info">
                                <div class="metric-value">Promotional Messages</div>
                                <div class="metric-bar">
                                    <div class="metric-fill" style="width: 78%" data-value="78"></div>
                                </div>
                            </div>
                            <div class="metric-stat">78%</div>
                        </div>
                        <div class="performance-metric">
                            <div class="metric-info">
                                <div class="metric-value">Customer Service</div>
                                <div class="metric-bar">
                                    <div class="metric-fill" style="width: 89%" data-value="89"></div>
                                </div>
                            </div>
                            <div class="metric-stat">89%</div>
                        </div>
                    </div>

                    <!-- Notification Preview -->
                    <div class="notification-preview">
                        <div class="preview-header">
                            <div class="preview-avatar">AG</div>
                            <div>
                                <div class="fw-bold">Agii Notifications</div>
                                <div class="text-muted" style="font-size: 0.8rem;">WhatsApp Business</div>
                            </div>
                        </div>
                        <div class="preview-message">
                            <strong>New Order Received!</strong><br>
                            Order #AG-7892 for ₦45,000<br>
                            Customer: Chinedu Okoro<br>
                            <a href="#">View order details</a>
                        </div>
                        <div class="preview-time">2:45 PM</div>
                    </div>

                    <!-- Notification Settings -->
                    <h4 class="section-title whatsapp-title mt-5">Notification Settings</h4>

                    <div class="notification-type">
                        <h5><i class="bi bi-briefcase"></i> Vendor Notifications</h5>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="vendorNotifications" checked>
                            <label class="form-check-label fw-semibold" for="vendorNotifications">Enable Vendor
                                Notifications</label>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="newOrder" checked>
                                    <label class="form-check-label" for="newOrder">New orders</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="orderUpdates" checked>
                                    <label class="form-check-label" for="orderUpdates">Order status updates</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="customerMessages" checked>
                                    <label class="form-check-label" for="customerMessages">Customer messages</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="reviews" checked>
                                    <label class="form-check-label" for="reviews">New reviews</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button class="btn btn-outline-secondary" id="resetSettings">Reset to Default</button>
                        <button class="btn btn-whatsapp" id="savePreferences">
                            <i class="bi bi-whatsapp me-2"></i>Save Preferences
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Performance Summary -->
                <div class="notification-card">
                    <h4 class="section-title whatsapp-title">Performance Summary</h4>

                    <div class="comparison-card">
                        <h6>This Month vs Last Month</h6>
                        <div class="comparison-item">
                            <span class="comparison-label">Notifications Sent</span>
                            <span class="comparison-value text-success">+15.7%</span>
                        </div>
                        <div class="comparison-item">
                            <span class="comparison-label">Delivery Rate</span>
                            <span class="comparison-value text-success">+2.3%</span>
                        </div>
                        <div class="comparison-item">
                            <span class="comparison-label">Customer Replies</span>
                            <span class="comparison-value text-success">+8.2%</span>
                        </div>
                        <div class="comparison-item">
                            <span class="comparison-label">Failed Deliveries</span>
                            <span class="comparison-value text-danger">-12.4%</span>
                        </div>
                    </div>

                    <div class="analytics-card">
                        <h6>Best Performing Notifications</h6>
                        <div class="performance-metric">
                            <div class="metric-info">
                                <div class="metric-value">Order Confirmations</div>
                                <small class="text-muted">98% delivery rate</small>
                            </div>
                            <div class="metric-stat text-success">98%</div>
                        </div>
                        <div class="performance-metric">
                            <div class="metric-info">
                                <div class="metric-value">Shipping Updates</div>
                                <small class="text-muted">96% delivery rate</small>
                            </div>
                            <div class="metric-stat text-success">96%</div>
                        </div>
                        <div class="performance-metric">
                            <div class="metric-info">
                                <div class="metric-value">Price Alerts</div>
                                <small class="text-muted">45% reply rate</small>
                            </div>
                            <div class="metric-stat text-warning">45%</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="notification-card">
                    <h4 class="section-title whatsapp-title">Quick Stats</h4>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-number">98.2%</div>
                            <div class="stat-label">Read Rate</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">5.2%</div>
                            <div class="stat-label">Reply Rate</div>
                        </div>
                    </div>

                    <div class="analytics-card">
                        <h6>Peak Hours</h6>
                        <div class="performance-metric">
                            <div class="metric-info">
                                <div class="metric-value">10:00 AM - 12:00 PM</div>
                                <small class="text-muted">Highest engagement</small>
                            </div>
                            <div class="metric-stat">42%</div>
                        </div>
                        <div class="performance-metric">
                            <div class="metric-info">
                                <div class="metric-value">7:00 PM - 9:00 PM</div>
                                <small class="text-muted">Second highest</small>
                            </div>
                            <div class="metric-stat">38%</div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="notification-card">
                    <h4 class="section-title whatsapp-title">Quick Actions</h4>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" id="testNotification">
                            <i class="bi bi-send me-2"></i>Send Test Notification
                        </button>
                        <button class="btn btn-outline-warning" id="exportData">
                            <i class="bi bi-download me-2"></i>Export Analytics
                        </button>
                        <button class="btn btn-outline-info" id="optimizeSettings">
                            <i class="bi bi-gear me-2"></i>Optimize Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h4 class="mb-3">Agii</h4>
                    <p>Nigeria's fastest growing marketplace connecting buyers and sellers.</p>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-3">Company</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">About Us</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Careers</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-3">Resources</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">Help Center</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Blog</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Community</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5 class="mb-3">Legal</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">Privacy Policy</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Terms of Service</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; 2023 Agii. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Charts
            const typeCtx = document.getElementById('typeChart').getContext('2d');
            const deliveryCtx = document.getElementById('deliveryChart').getContext('2d');

            // Notifications by Type Chart
            const typeChart = new Chart(typeCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Orders', 'Shipping', 'Promotions', 'Customer Service', 'Reviews'],
                    datasets: [{
                        data: [35, 25, 20, 15, 5],
                        backgroundColor: [
                            '#25D366',
                            '#128C7E',
                            '#34B7F1',
                            '#FFC107',
                            '#FF5722'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Delivery Performance Chart
            const deliveryChart = new Chart(deliveryCtx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Delivery Rate (%)',
                        data: [92, 94, 96, 95, 97, 94, 96],
                        borderColor: '#25D366',
                        backgroundColor: 'rgba(37, 211, 102, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 90,
                            max: 100
                        }
                    }
                }
            });

            // Time Filter Functionality
            document.querySelectorAll('.time-filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.time-filter-btn').forEach(b => {
                        b.classList.remove('active');
                    });
                    this.classList.add('active');

                    // Update the charts and stats based on selected period
                    const period = this.getAttribute('data-period');
                    updateStatsForPeriod(period);
                });
            });

            function updateStatsForPeriod(period) {
                // Data for different time periods
                const stats = {
                    '7d': {
                        delivery: 94.7,
                        sent: 12458,
                        time: 2.1,
                        replies: 642,
                        typeData: [35, 25, 20, 15, 5],
                        deliveryData: [92, 94, 96, 95, 97, 94, 96]
                    },
                    '30d': {
                        delivery: 93.8,
                        sent: 45892,
                        time: 2.3,
                        replies: 2458,
                        typeData: [30, 28, 22, 12, 8],
                        deliveryData: [90, 92, 93, 94, 95, 93, 94]
                    },
                    '90d': {
                        delivery: 92.5,
                        sent: 128456,
                        time: 2.4,
                        replies: 6845,
                        typeData: [28, 26, 24, 14, 8],
                        deliveryData: [88, 90, 91, 92, 93, 91, 92]
                    },
                    '1y': {
                        delivery: 91.2,
                        sent: 485621,
                        time: 2.6,
                        replies: 25478,
                        typeData: [25, 24, 26, 16, 9],
                        deliveryData: [85, 87, 88, 89, 90, 88, 89]
                    }
                };

                const data = stats[period];

                // Update stat cards with animation
                animateValue('deliveryRateCard', data.delivery, '%');
                animateValue('notificationsSentCard', data.sent, '');
                animateValue('deliveryTimeCard', data.time, 's');
                animateValue('customerRepliesCard', data.replies, '');

                // Update charts
                typeChart.data.datasets[0].data = data.typeData;
                typeChart.update();

                deliveryChart.data.datasets[0].data = data.deliveryData;
                deliveryChart.update();
            }

            // Animate value changes
            function animateValue(cardId, targetValue, suffix) {
                const card = document.getElementById(cardId);
                const numberElement = card.querySelector('.stat-number');
                const currentValue = parseFloat(numberElement.textContent.replace(/[^0-9.]/g, ''));
                const duration = 1000;
                const startTime = performance.now();

                function updateValue(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);

                    // Easing function for smooth animation
                    const easeOutQuart = 1 - Math.pow(1 - progress, 4);

                    const current = currentValue + (targetValue - currentValue) * easeOutQuart;

                    if (suffix === '%') {
                        numberElement.textContent = current.toFixed(1) + suffix;
                    } else if (suffix === 's') {
                        numberElement.textContent = current.toFixed(1) + suffix;
                    } else {
                        numberElement.textContent = Math.round(current).toLocaleString();
                    }

                    if (progress < 1) {
                        requestAnimationFrame(updateValue);
                    }
                }

                requestAnimationFrame(updateValue);
            }

            // Save Preferences
            document.getElementById('savePreferences').addEventListener('click', function() {
                // Get all selected preferences
                const vendorNotifications = document.getElementById('vendorNotifications').checked;
                const newOrder = document.getElementById('newOrder').checked;
                const orderUpdates = document.getElementById('orderUpdates').checked;
                const customerMessages = document.getElementById('customerMessages').checked;
                const reviews = document.getElementById('reviews').checked;

                // In a real app, this would save to backend
                console.log('Saving preferences:', {
                    vendorNotifications,
                    newOrder,
                    orderUpdates,
                    customerMessages,
                    reviews
                });

                // Show success feedback
                this.innerHTML = '<i class="bi bi-check-circle me-2"></i>Saved!';
                this.classList.remove('btn-whatsapp');
                this.classList.add('btn-success');

                setTimeout(() => {
                    this.innerHTML = '<i class="bi bi-whatsapp me-2"></i>Save Preferences';
                    this.classList.remove('btn-success');
                    this.classList.add('btn-whatsapp');
                }, 2000);

                // Show confirmation message
                alert('Notification preferences saved successfully!');
            });

            // Reset Settings
            document.getElementById('resetSettings').addEventListener('click', function() {
                if (confirm('Are you sure you want to reset all settings to default?')) {
                    document.getElementById('vendorNotifications').checked = true;
                    document.getElementById('newOrder').checked = true;
                    document.getElementById('orderUpdates').checked = true;
                    document.getElementById('customerMessages').checked = true;
                    document.getElementById('reviews').checked = true;

                    alert('Settings have been reset to default values.');
                }
            });

            // Test Notification
            document.getElementById('testNotification').addEventListener('click', function() {
                // Show sending state
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Sending...';
                this.disabled = true;

                // Simulate API call
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                    alert('Test notification sent! Check your WhatsApp for the test message.');
                }, 1500);
            });

            // Export Data
            document.getElementById('exportData').addEventListener('click', function() {
                // Show exporting state
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Exporting...';
                this.disabled = true;

                // Simulate export process
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                    alert(
                        'Analytics data exported successfully! Your download will begin shortly.'
                        );

                    // In a real app, this would trigger a file download
                    // For demo purposes, we'll just show the alert
                }, 2000);
            });

            // Optimize Settings
            document.getElementById('optimizeSettings').addEventListener('click', function() {
                // Auto-optimize based on performance data
                document.getElementById('vendorNotifications').checked = true;
                document.getElementById('newOrder').checked = true;
                document.getElementById('orderUpdates').checked = true;
                document.getElementById('customerMessages').checked = true;
                document.getElementById('reviews').checked = true;

                // Show optimization message
                alert(
                    'Settings optimized based on your performance data! We\'ve enabled the most effective notification types.'
                    );
            });

            // Stat card click handlers for detailed views
            document.querySelectorAll('.stat-card').forEach(card => {
                card.addEventListener('click', function() {
                    const statType = this.querySelector('.stat-label').textContent;
                    alert(`Showing detailed analytics for: ${statType}`);
                    // In a real app, this would open a detailed modal or navigate to a detailed page
                });
            });

            // Animate progress bars on page load
            setTimeout(() => {
                document.querySelectorAll('.metric-fill').forEach(fill => {
                    const value = fill.getAttribute('data-value');
                    fill.style.width = '0%';
                    setTimeout(() => {
                        fill.style.width = value + '%';
                    }, 500);
                });
            }, 1000);
        });
    </script>
</body>

</html>
