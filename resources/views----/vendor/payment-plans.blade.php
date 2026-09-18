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
            /* border-bottom: 1px solid var(--border-color); */
        }

        /* .plan-type-tab {
            padding: 10px 20px;
            cursor: pointer;
            font-weight: 600;
            border-bottom: 3px solid transparent;
            transition: var(--transition);
        }


        .plan-type-tab.active {
            color: #000;
            border-bottom: 3px solid var(--primary-color);
        } */

        .plan-type-tab {
            display: inline-block;
            padding: 10px 15px;
            /* border: 2px solid #8aad44; */
            border-radius: 6px;
            color: #8aad44;
            background-color: #fff;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-left: 4px;
        }

        .plan-type-tab:hover {
            background-color: #8aad44;
            color: #fff;
        }

        .plan-type-tab.active {
            background-color: #151e01;
            color: #fff;
            border-color: #8aad44;
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

        .background-vendor {
            background-image: linear-gradient(124deg, rgba(143, 199, 74, 0.8), rgba(122, 180, 54, 0.8)), url('/assets/images/checkout.jpg');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">Agii</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    Welcome, {{ Auth::user()->first_name }}!
                </span>
                <a class="btn btn-outline-primary" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Log Out
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section background-vendor">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center ">
                    <h1 class="display-4 fw-bold mb-4">Choose Your Vendor Plan</h1>
                    <p class="lead mb-5">Select the perfect plan to start selling on Agii. All plans include our core
                        marketplace features.</p>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

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
            <div class="plan-type-content active" id="regular-plans">
                <div class="row g-4 ">
                    @foreach ($regularPlans as $plan)
                        <div class="col-lg-4 col-md-6">
                            <div class="plan-card {{ $plan->is_popular ? 'popular' : '' }}">
                                @if ($plan->is_popular)
                                    <div class="popular-badge">MOST POPULAR</div>
                                @endif

                                <div class="plan-header">
                                    <div class="plan-name">{{ strtoupper($plan->tier) }}</div>

                                    @php
                                        $monthlyPricing = $plan->getMonthlyPriceWithVat();
                                        $yearlyPricing = $plan->getYearlyPriceWithVat();
                                    @endphp

                                    <div class="plan-price monthly-price">
                                        ₦{{ number_format($monthlyPricing['total'], 2) }}
                                    </div>
                                    <div class="plan-price yearly-price d-none">
                                        ₦{{ number_format($yearlyPricing['total'], 2) }}</div>

                                    <div class="plan-period monthly-price">per month</div>
                                    <div class="plan-period yearly-price d-none">per year</div>

                                    <small class="text-muted">
                                        (₦{{ number_format($monthlyPricing['subtotal'], 2) }} +
                                        ₦{{ number_format($monthlyPricing['vat_amount'], 2) }} VAT)
                                    </small>
                                </div>
                                <ul class="plan-features">
                                    <li><i class="bi bi-check-circle"></i>
                                        @if ($plan->product_limit)
                                            List up to {{ $plan->product_limit }} products
                                        @else
                                            Unlimited product listings
                                        @endif
                                    </li>
                                    <li><i class="bi bi-check-circle"></i>
                                        {{ ucfirst($plan->support_level) }} customer support
                                    </li>
                                    <li><i class="bi bi-check-circle"></i>
                                        {{ ucfirst($plan->visibility) }} listing visibility
                                    </li>
                                    <li><i class="bi bi-check-circle"></i> Access to marketplace</li>

                                    @if ($plan->analytics)
                                        <li><i class="bi bi-check-circle"></i>
                                            @if ($plan->tier === 'advance')
                                                Basic
                                            @else
                                                Advanced
                                            @endif analysis
                                        </li>
                                    @else
                                        <li class="disabled"><i class="bi bi-x-circle"></i> Basic analysis</li>
                                    @endif

                                    @if ($plan->featured_listings)
                                        <li><i class="bi bi-check-circle"></i>
                                            {{ $plan->featured_listings_count }} Featured listings per month
                                        </li>
                                    @else
                                        <li class="disabled"><i class="bi bi-x-circle"></i> Featured listings</li>
                                    @endif

                                    @if ($plan->custom_storefront)
                                        <li><i class="bi bi-check-circle"></i> Custom storefront</li>
                                    @else
                                        <li class="disabled"><i class="bi bi-x-circle"></i> Custom storefront</li>
                                    @endif

                                    @if ($plan->marketing_tools)
                                        <li><i class="bi bi-check-circle"></i> Advanced marketing tools</li>
                                    @else
                                        <li class="disabled"><i class="bi bi-x-circle"></i> Advanced marketing tools
                                        </li>
                                    @endif
                                </ul>
                                <a href="{{ route('vendor.plan.select', $plan) }}?billing_cycle=monthly"
                                    class="btn {{ $plan->is_popular ? 'btn-primary' : 'btn-outline-primary' }} select-plan"
                                    data-plan-id="{{ $plan->id }}" data-billing-cycle="monthly">
                                    Get Started
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>


            <!-- Plans Section - Stores -->
            <div class="plan-type-content" id="stores-plans">
                <div class="row g-4" id="stores-plans">
                    @foreach ($storesPlans as $plan)
                        <div class="col-lg-4 col-md-6">
                            <div class="plan-card {{ $plan->is_popular ? 'popular' : '' }}">
                                @if ($plan->is_popular)
                                    <div class="popular-badge">MOST POPULAR</div>
                                @endif
                                {{-- resources/views/vendor/payment-plans.blade.php --}}

                                <!-- In the plan cards section -->
                                <div class="plan-header">
                                    <div class="plan-name">{{ strtoupper($plan->tier) }}</div>

                                    @php
                                        $monthlyPricing = $plan->getMonthlyPriceWithVat();
                                        $yearlyPricing = $plan->getYearlyPriceWithVat();
                                    @endphp

                                    <div class="plan-price monthly-price">
                                        ₦{{ number_format($monthlyPricing['total'], 2) }}
                                    </div>
                                    <div class="plan-price yearly-price d-none">
                                        ₦{{ number_format($yearlyPricing['total'], 2) }}</div>

                                    <div class="plan-period monthly-price">per month</div>
                                    <div class="plan-period yearly-price d-none">per year</div>

                                    <small class="text-muted">
                                        (₦{{ number_format($monthlyPricing['subtotal'], 2) }} +
                                        ₦{{ number_format($monthlyPricing['vat_amount'], 2) }} VAT)
                                    </small>
                                </div>
                                <ul class="plan-features">
                                    <!-- Same feature list as above -->
                                    <li><i class="bi bi-check-circle"></i>
                                        @if ($plan->product_limit)
                                            List up to {{ $plan->product_limit }} products
                                        @else
                                            Unlimited product listings
                                        @endif
                                    </li>
                                    <li><i class="bi bi-check-circle"></i>
                                        {{ ucfirst($plan->support_level) }} customer support
                                    </li>
                                    <!-- ... rest of features -->
                                </ul>
                                <a href="{{ route('vendor.plan.select', $plan) }}?billing_cycle=monthly"
                                    class="btn {{ $plan->is_popular ? 'btn-primary' : 'btn-outline-primary' }} select-plan"
                                    data-plan-id="{{ $plan->id }}" data-billing-cycle="monthly">
                                    Get Started
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

            <!-- Rest of your existing HTML (comparison table, testimonials, FAQ, etc.) -->
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const billingToggle = document.getElementById('billingToggle');
            const monthlyPrices = document.querySelectorAll('.monthly-price');
            const yearlyPrices = document.querySelectorAll('.yearly-price');
            const planButtons = document.querySelectorAll('.select-plan');

            // Update prices when toggle changes
            billingToggle.addEventListener('change', function() {
                const isYearly = this.checked;

                monthlyPrices.forEach(price => price.classList.toggle('d-none', isYearly));
                yearlyPrices.forEach(price => price.classList.toggle('d-none', !isYearly));

                // Update plan button URLs
                planButtons.forEach(button => {
                    const currentUrl = new URL(button.href);
                    currentUrl.searchParams.set('billing_cycle', isYearly ? 'yearly' : 'monthly');
                    button.href = currentUrl.toString();
                });
            });

            // Plan type tabs functionality
            const planTypeTabs = document.querySelectorAll('.plan-type-tab');
            const planTypeContents = document.querySelectorAll('.plan-type-content');

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
        });
    </script>
</body>

</html>
