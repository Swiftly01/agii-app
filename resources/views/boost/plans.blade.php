<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boost Your Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #8fc74a;
            --primary-light: #f0f7e6;
            --secondary-color: #7ab436;
        }

        body {
            background: linear-gradient(135deg, var(--primary-light) 0%, #f8f9fa 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }

        .plan-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            max-width: 480px;
            width: 100%;
            overflow: hidden;
        }

        .plan-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: #fff;
            padding: 30px;
            text-align: center;
        }

        .plan-body {
            padding: 30px;
        }

        .price {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 12px 20px;
            font-weight: 600;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .feature-list li {
            padding: 8px 0;
        }

        .feature-list i {
            color: var(--primary-color);
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="plan-card">
        <div class="plan-header">
            <i class="bi bi-rocket-takeoff-fill" style="font-size: 2.5rem;"></i>
            <h1 class="h3 mt-3 mb-0">Boost Your Products</h1>
            <p class="mb-0 opacity-75">Get seen first. Sell faster.</p>
        </div>

        <div class="plan-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($subscription)
                <div class="alert alert-success d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                    <div>
                        Your boost subscription is active until
                        <strong>{{ $subscription->expires_at->format('M j, Y') }}</strong>.
                    </div>
                </div>
                <a href="{{ route('vendor.showadvert') }}" class="btn btn-primary w-100">
                    Go Boost a Product <i class="bi bi-arrow-right ms-1"></i>
                </a>
            @else
                <div class="text-center mb-4">
                    <span class="price">₦{{ number_format($monthlyPrice) }}</span>
                    <span class="text-muted">/ month</span>
                </div>

                <ul class="list-unstyled feature-list mb-4">
                    <li><i class="bi bi-check-circle-fill"></i>Boost up to {{ config('boost.max_boosted_products') }} products at once</li>
                    <li><i class="bi bi-check-circle-fill"></i>Boosted products show first in listings</li>
                    <li><i class="bi bi-check-circle-fill"></i>Feature one product in the home page carousel</li>
                    <li><i class="bi bi-check-circle-fill"></i>Cancel anytime — boosts end automatically when your subscription does</li>
                </ul>

                <form method="POST" action="{{ route('boost.checkout') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-credit-card me-2"></i>Subscribe & Pay
                    </button>
                </form>
            @endif

            <div class="text-center mt-3">
                <a href="{{ route('vendor.showadvert') }}" class="small text-muted">Back to My Adverts</a>
            </div>
        </div>
    </div>
</body>

</html>
