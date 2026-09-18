<!DOCTYPE html>
<html lang="en">

<head>
    <title>Agii - Choose Your Plan</title>
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

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .plans-container {
            padding: 80px 0;
        }

        .plan-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            transition: var(--transition);
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .plan-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .plan-card.popular {
            border: 2px solid var(--primary-color);
            transform: scale(1.05);
        }

        .plan-card.popular:hover {
            transform: scale(1.05) translateY(-10px);
        }

        .popular-badge {
            position: absolute;
            top: 20px;
            right: -30px;
            background: var(--primary-color);
            color: white;
            padding: 5px 40px;
            transform: rotate(45deg);
            font-size: 0.8rem;
            font-weight: 600;
        }

        .plan-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .plan-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .plan-price {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .plan-period {
            color: var(--light-dark-color);
            font-size: 1rem;
        }

        .plan-features {
            list-style: none;
            padding: 0;
            margin: 0 0 30px 0;
        }

        .plan-features li {
            padding: 10px 0;
            display: flex;
            align-items: center;
        }

        .plan-features li i {
            color: var(--success-color);
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .plan-features li.disabled {
            color: var(--light-dark-color);
        }

        .plan-features li.disabled i {
            color: var(--light-dark-color);
        }

        .btn {
            border-radius: 8px;
            padding: 12px 20px;
            font-weight: 600;
            transition: var(--transition);
            width: 100%;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 25px;
            color: var(--dark-color);
            font-weight: 700;
            text-align: center;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .faq-section {
            background: var(--light-grey-color);
            padding: 80px 0;
        }

        .faq-item {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
        }

        .faq-question {
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark-color);
        }

        .faq-answer {
            color: var(--light-dark-color);
        }

        .payment-methods {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .payment-method {
            background: white;
            border-radius: 8px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .comparison-table {
            background: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            margin-top: 40px;
        }

        .comparison-table th {
            background: var(--primary-light);
            font-weight: 600;
        }

        .comparison-table td,
        .comparison-table th {
            padding: 15px;
            text-align: center;
            border: 1px solid var(--border-color);
        }

        .comparison-table .feature-name {
            text-align: left;
            font-weight: 600;
        }

        .checkmark {
            color: var(--success-color);
            font-size: 1.2rem;
        }

        .crossmark {
            color: var(--danger-color);
            font-size: 1.2rem;
        }

        .testimonial-section {
            padding: 80px 0;
        }

        .testimonial-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 30px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            margin-bottom: 30px;
        }

        .testimonial-text {
            font-style: italic;
            margin-bottom: 20px;
            color: var(--light-dark-color);
        }

        .testimonial-author {
            display: flex;
            align-items: center;
        }

        .testimonial-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .billing-toggle {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 40px;
            gap: 15px;
        }

        .toggle-label {
            font-weight: 600;
            color: var(--dark-color);
        }

        .form-check-input:checked {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .discount-badge {
            background: var(--warning-color);
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: 10px;
        }

        .footer {
            background: var(--dark-color);
            color: white;
            padding: 40px 0;
            text-align: center;
        }

        .plan-type-tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
            border-bottom: 1px solid var(--border-color);
        }

        .plan-type-tab {
            padding: 10px 20px;
            cursor: pointer;
            font-weight: 600;
            border-bottom: 3px solid transparent;
            transition: var(--transition);
        }

        .plan-type-tab.active {
            color: var(--primary-color);
            border-bottom: 3px solid var(--primary-color);
        }

        .plan-type-content {
            display: none;
        }

        .plan-type-content.active {
            display: block;
        }

        @media (max-width: 768px) {
            .plan-card.popular {
                transform: scale(1);
            }

            .plan-card.popular:hover {
                transform: translateY(-10px);
            }

            .hero-section {
                padding: 60px 0;
            }

            .plans-container {
                padding: 60px 0;
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
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-outline-primary" href="#">Sign In</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="display-4 fw-bold mb-4">Choose the Perfect Plan for Your Business</h1>
                    <p class="lead mb-5">Scale your business with our flexible pricing plans. All plans include our core
                        features to help you succeed.</p>

                    <!-- Plan Type Tabs -->
                    <div class="plan-type-tabs">
                        <div class="plan-type-tab active" data-tab="regular">Regular</div>
                        <div class="plan-type-tab" data-tab="stores">Stores</div>
                    </div>

                    <!-- Billing Toggle -->
                    <div class="billing-toggle">
                        <span class="toggle-label">Monthly</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="billingToggle">
                            <label class="form-check-label" for="billingToggle"></label>
                        </div>
                        <span class="toggle-label">Yearly <span class="discount-badge">Save 15%</span></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Plans Section - Regular -->
    <section class="plans-container">
        <div class="container">
            <h2 class="section-title">Regular Plans</h2>
            <p class="text-center text-muted mb-5">Perfect for individual sellers and small businesses</p>

            <div class="row g-4 plan-type-content active" id="regular-plans">
                <!-- Basic Plan -->
                <div class="col-lg-4 col-md-6">
                    <div class="plan-card">
                        <div class="plan-header">
                            <div class="plan-name">BASIC</div>
                            <div class="plan-price monthly-price">₦1,075</div>
                            <div class="plan-price yearly-price d-none">₦10,965</div>
                            <div class="plan-period monthly-price">per month</div>
                            <div class="plan-period yearly-price d-none">per year</div>
                            <small class="text-muted">(7.5% VAT Inclusive)</small>
                        </div>
                        <ul class="plan-features">
                            <li><i class="bi bi-check-circle"></i> List up to 10 products</li>
                            <li><i class="bi bi-check-circle"></i> Basic customer support</li>
                            <li><i class="bi bi-check-circle"></i> Standard listing visibility</li>
                            <li><i class="bi bi-check-circle"></i> Access to marketplace</li>
                            <li class="disabled"><i class="bi bi-x-circle"></i> Basic analysis</li>
                            <li class="disabled"><i class="bi bi-x-circle"></i> Featured listings</li>
                            <li class="disabled"><i class="bi bi-x-circle"></i> Custom storefront</li>
                            <li class="disabled"><i class="bi bi-x-circle"></i> Advanced marketing tools</li>
                        </ul>
                        <button class="btn btn-outline-primary">Get Started</button>
                    </div>
                </div>

                <!-- Advance Plan (Popular) -->
                <div class="col-lg-4 col-md-6">
                    <div class="plan-card popular">
                        <div class="popular-badge">MOST POPULAR</div>
                        <div class="plan-header">
                            <div class="plan-name">ADVANCE</div>
                            <div class="plan-price monthly-price">₦2,150</div>
                            <div class="plan-price yearly-price d-none">₦21,930</div>
                            <div class="plan-period monthly-price">per month</div>
                            <div class="plan-period yearly-price d-none">per year</div>
                            <small class="text-muted">(7.5% VAT Inclusive)</small>
                        </div>
                        <ul class="plan-features">
                            <li><i class="bi bi-check-circle"></i> List up to 50 products</li>
                            <li><i class="bi bi-check-circle"></i> Priority customer support</li>
                            <li><i class="bi bi-check-circle"></i> Enhanced listing visibility</li>
                            <li><i class="bi bi-check-circle"></i> Access to marketplace</li>
                            <li><i class="bi bi-check-circle"></i> Basic analysis</li>
                            <li><i class="bi bi-check-circle"></i> 5 Featured listings per month</li>
                            <li class="disabled"><i class="bi bi-x-circle"></i> Custom storefront</li>
                            <li class="disabled"><i class="bi bi-x-circle"></i> Advanced marketing tools</li>
                        </ul>
                        <button class="btn btn-primary">Get Started</button>
                    </div>
                </div>

                <!-- Premium Plan -->
                <div class="col-lg-4 col-md-6">
                    <div class="plan-card">
                        <div class="plan-header">
                            <div class="plan-name">PREMIUM</div>
                            <div class="plan-price monthly-price">₦3,225</div>
                            <div class="plan-price yearly-price d-none">₦32,895</div>
                            <div class="plan-period monthly-price">per month</div>
                            <div class="plan-period yearly-price d-none">per year</div>
                            <small class="text-muted">(7.5% VAT Inclusive)</small>
                        </div>
                        <ul class="plan-features">
                            <li><i class="bi bi-check-circle"></i> Unlimited Product listings</li>
                            <li><i class="bi bi-check-circle"></i> 24/7 premium support</li>
                            <li><i class="bi bi-check-circle"></i> Maximum listing visibility</li>
                            <li><i class="bi bi-check-circle"></i> Access to marketplace</li>
                            <li><i class="bi bi-check-circle"></i> Advanced analysis dashboard</li>
                            <li><i class="bi bi-check-circle"></i> 5 Featured listings per month</li>
                            <li><i class="bi bi-check-circle"></i> Custom storefront</li>
                            <li><i class="bi bi-check-circle"></i> Advanced marketing tools</li>
                        </ul>
                        <button class="btn btn-outline-primary">Get Started</button>
                    </div>
                </div>
            </div>

            <!-- Plans Section - Stores -->
            <div class="row g-4 plan-type-content" id="stores-plans">
                <!-- Basic Plan -->
                <div class="col-lg-4 col-md-6">
                    <div class="plan-card">
                        <div class="plan-header">
                            <div class="plan-name">BASIC</div>
                            <div class="plan-price monthly-price">₦2,150</div>
                            <div class="plan-price yearly-price d-none">₦21,930</div>
                            <div class="plan-period monthly-price">per month</div>
                            <div class="plan-period yearly-price d-none">per year</div>
                            <small class="text-muted">(7.5% VAT Inclusive)</small>
                        </div>
                        <ul class="plan-features">
                            <li><i class="bi bi-check-circle"></i> List up to 10 products</li>
                            <li><i class="bi bi-check-circle"></i> Basic customer support</li>
                            <li><i class="bi bi-check-circle"></i> Standard listing visibility</li>
                            <li><i class="bi bi-check-circle"></i> Access to marketplace</li>
                            <li class="disabled"><i class="bi bi-x-circle"></i> Basic analysis</li>
                            <li class="disabled"><i class="bi bi-x-circle"></i> Featured listings</li>
                            <li class="disabled"><i class="bi bi-x-circle"></i> Custom storefront</li>
                            <li class="disabled"><i class="bi bi-x-circle"></i> Advanced marketing tools</li>
                        </ul>
                        <button class="btn btn-outline-primary">Get Started</button>
                    </div>
                </div>

                <!-- Advance Plan (Popular) -->
                <div class="col-lg-4 col-md-6">
                    <div class="plan-card popular">
                        <div class="popular-badge">MOST POPULAR</div>
                        <div class="plan-header">
                            <div class="plan-name">ADVANCE</div>
                            <div class="plan-price monthly-price">₦3,225</div>
                            <div class="plan-price yearly-price d-none">₦32,895</div>
                            <div class="plan-period monthly-price">per month</div>
                            <div class="plan-period yearly-price d-none">per year</div>
                            <small class="text-muted">(7.5% VAT Inclusive)</small>
                        </div>
                        <ul class="plan-features">
                            <li><i class="bi bi-check-circle"></i> List up to 50 products</li>
                            <li><i class="bi bi-check-circle"></i> Priority customer support</li>
                            <li><i class="bi bi-check-circle"></i> Enhanced listing visibility</li>
                            <li><i class="bi bi-check-circle"></i> Access to marketplace</li>
                            <li><i class="bi bi-check-circle"></i> Basic analysis</li>
                            <li><i class="bi bi-check-circle"></i> 5 Featured listings per month</li>
                            <li class="disabled"><i class="bi bi-x-circle"></i> Custom storefront</li>
                            <li class="disabled"><i class="bi bi-x-circle"></i> Advanced marketing tools</li>
                        </ul>
                        <button class="btn btn-primary">Get Started</button>
                    </div>
                </div>

                <!-- Premium Plan -->
                <div class="col-lg-4 col-md-6">
                    <div class="plan-card">
                        <div class="plan-header">
                            <div class="plan-name">PREMIUM</div>
                            <div class="plan-price monthly-price">₦5,375</div>
                            <div class="plan-price yearly-price d-none">₦54,835</div>
                            <div class="plan-period monthly-price">per month</div>
                            <div class="plan-period yearly-price d-none">per year</div>
                            <small class="text-muted">(7.5% VAT Inclusive)</small>
                        </div>
                        <ul class="plan-features">
                            <li><i class="bi bi-check-circle"></i> Unlimited Product listings</li>
                            <li><i class="bi bi-check-circle"></i> 24/7 premium support</li>
                            <li><i class="bi bi-check-circle"></i> Maximum listing visibility</li>
                            <li><i class="bi bi-check-circle"></i> Access to marketplace</li>
                            <li><i class="bi bi-check-circle"></i> Advanced analysis dashboard</li>
                            <li><i class="bi bi-check-circle"></i> 5 Featured listings per month</li>
                            <li><i class="bi bi-check-circle"></i> Custom storefront</li>
                            <li><i class="bi bi-check-circle"></i> Advanced marketing tools</li>
                        </ul>
                        <button class="btn btn-outline-primary">Get Started</button>
                    </div>
                </div>
            </div>

            <!-- Comparison Table -->
            <div class="comparison-table">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 30%;">Features</th>
                            <th scope="col" style="width: 23%;">BASIC</th>
                            <th scope="col" style="width: 23%;">ADVANCE</th>
                            <th scope="col" style="width: 23%;">PREMIUM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="feature-name">Product Listings</td>
                            <td>10</td>
                            <td>50</td>
                            <td>Unlimited</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Featured Listings</td>
                            <td><i class="bi bi-x crossmark"></i></td>
                            <td>5 per month</td>
                            <td>5 per month</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Customer Support</td>
                            <td>Basic</td>
                            <td>Priority</td>
                            <td>24/7 Premium</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Analytics</td>
                            <td><i class="bi bi-x crossmark"></i></td>
                            <td>Basic</td>
                            <td>Advanced Dashboard</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Custom Storefront</td>
                            <td><i class="bi bi-x crossmark"></i></td>
                            <td><i class="bi bi-x crossmark"></i></td>
                            <td><i class="bi bi-check checkmark"></i></td>
                        </tr>
                        <tr>
                            <td class="feature-name">Marketing Tools</td>
                            <td><i class="bi bi-x crossmark"></i></td>
                            <td><i class="bi bi-x crossmark"></i></td>
                            <td><i class="bi bi-check checkmark"></i></td>
                        </tr>
                        <tr>
                            <td class="feature-name">Listing Visibility</td>
                            <td>Standard</td>
                            <td>Enhanced</td>
                            <td>Maximum</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Payment Methods -->
            <div class="text-center mt-5">
                <p class="text-muted mb-3">We accept all major payment methods</p>
                <div class="payment-methods">
                    <div class="payment-method">
                        <i class="bi bi-credit-card"></i>
                        <span>Card</span>
                    </div>
                    <div class="payment-method">
                        <i class="bi bi-bank"></i>
                        <span>Bank Transfer</span>
                    </div>
                    <div class="payment-method">
                        <i class="bi bi-phone"></i>
                        <span>USSD</span>
                    </div>
                    <div class="payment-method">
                        <i class="bi bi-wallet2"></i>
                        <span>Digital Wallet</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonial-section bg-light">
        <div class="container">
            <h2 class="section-title">What Our Vendors Say</h2>
            <p class="text-center text-muted mb-5">Hear from successful vendors using Agii</p>

            <div class="row">
                <div class="col-lg-4">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            "Since upgrading to the Advance plan, my sales have increased by 150%. The featured
                            listings make all the difference!"
                        </div>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">AO</div>
                            <div>
                                <div class="fw-bold">Adeola Ogun</div>
                                <div class="text-muted">Fashion Store Owner</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            "The Premium plan has everything I need to scale. The analytics help me understand my
                            customers better and make data-driven decisions."
                        </div>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">CJ</div>
                            <div>
                                <div class="fw-bold">Chinedu James</div>
                                <div class="text-muted">Electronics Vendor</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            "Started with the Basic plan and grew into Advance. The platform scales perfectly
                            with my business. Highly recommended!"
                        </div>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">FA</div>
                            <div>
                                <div class="fw-bold">Fatima Ahmed</div>
                                <div class="text-muted">Home Goods Seller</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <h2 class="section-title">Frequently Asked Questions</h2>
            <p class="text-center text-muted mb-5">Find answers to common questions about our plans</p>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="faq-item">
                        <div class="faq-question">Can I change my plan later?</div>
                        <div class="faq-answer">Yes, you can upgrade or downgrade your plan at any time. Changes will
                            be prorated based on your billing cycle.</div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">Is there a contract or long-term commitment?</div>
                        <div class="faq-answer">No, all plans are month-to-month or year-to-year. You can cancel at any
                            time without penalty.</div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">What payment methods do you accept?</div>
                        <div class="faq-answer">We accept credit/debit cards, bank transfers, USSD, and popular digital
                            wallets in Nigeria.</div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">Do you offer discounts for yearly billing?</div>
                        <div class="faq-answer">Yes, you can save 15% by choosing yearly billing instead of monthly
                            payments.</div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">What happens if I exceed my product limit?</div>
                        <div class="faq-answer">You'll need to upgrade to a higher plan to list more products. We'll
                            notify you when you're approaching your limit.</div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">Is there a setup fee?</div>
                        <div class="faq-answer">No, there are no setup fees for any of our plans. You only pay the
                            monthly or yearly subscription fee.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
            const billingToggle = document.getElementById('billingToggle');
            const monthlyPrices = document.querySelectorAll('.monthly-price');
            const yearlyPrices = document.querySelectorAll('.yearly-price');
            const planTypeTabs = document.querySelectorAll('.plan-type-tab');
            const planTypeContents = document.querySelectorAll('.plan-type-content');

            // Initialize toggle state
            updatePrices();

            // Add event listener to toggle
            billingToggle.addEventListener('change', updatePrices);

            // Add event listeners to plan type tabs
            planTypeTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');

                    // Update active tab
                    planTypeTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // Show corresponding content
                    planTypeContents.forEach(content => {
                        content.classList.remove('active');
                        if (content.id === `${tabId}-plans`) {
                            content.classList.add('active');
                        }
                    });
                });
            });

            function updatePrices() {
                if (billingToggle.checked) {
                    // Show yearly prices
                    monthlyPrices.forEach(price => price.classList.add('d-none'));
                    yearlyPrices.forEach(price => price.classList.remove('d-none'));
                } else {
                    // Show monthly prices
                    monthlyPrices.forEach(price => price.classList.remove('d-none'));
                    yearlyPrices.forEach(price => price.classList.add('d-none'));
                }
            }

            // Add click handlers for plan buttons
            document.querySelectorAll('.plan-card .btn').forEach(button => {
                button.addEventListener('click', function() {
                    const planName = this.closest('.plan-card').querySelector('.plan-name')
                        .textContent;
                    const price = this.closest('.plan-card').querySelector(billingToggle.checked ?
                        '.yearly-price' : '.monthly-price').textContent;

                    // In a real application, this would redirect to a checkout page
                    alert(
                        `You've selected the ${planName} plan for ${price} per ${billingToggle.checked ? 'year' : 'month'}. You will be redirected to the checkout page.`
                    );

                    // Simulate redirect to checkout
                    // window.location.href = `/checkout?plan=${planName.toLowerCase()}&billing=${billingToggle.checked ? 'yearly' : 'monthly'}`;
                });
            });
        });
    </script>
</body>

</html>
