<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #8fc74a;
            --primary-light: #f0f7e6;
            --secondary-color: #7ab436;
            --accent-color: #8fc74a;
            --success-color: #8fc74a;
            --danger-color: #e74c3c;
            --dark-color: #2c3e50;
            --light-dark-color: #7f8c8d;
            --light-grey-color: #f8f9fa;
            --border-color: #dee2e6;
            --card-bg: #ffffff;
            --shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        body {
            background: linear-gradient(135deg, var(--primary-light) 0%, #f8f9fa 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .verification-card {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            overflow: hidden;
            max-width: 480px;
            width: 100%;
            transition: var(--transition);
        }

        .verification-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .verification-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .verification-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
        }

        .verification-body {
            padding: 30px;
        }

        .user-info-card {
            background: var(--light-grey-color);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            border-left: 4px solid var(--primary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 12px 20px;
            font-weight: 600;
            border-radius: 8px;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-outline-secondary {
            padding: 12px 20px;
            font-weight: 600;
            border-radius: 8px;
            transition: var(--transition);
        }

        .btn-outline-secondary:hover {
            transform: translateY(-2px);
        }

        .alert-success {
            background-color: rgba(143, 199, 74, 0.1);
            border-color: var(--success-color);
            color: #2d5016;
            border-radius: 8px;
        }

        .user-badge {
            display: inline-block;
            background: var(--primary-light);
            color: var(--dark-color);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: 8px;
        }
    </style>
</head>

<body>
    <div class="verification-card">
        <!-- Header -->
        <div class="verification-header">
            <div class="verification-icon">
                <i class="bi bi-envelope-check"></i>
            </div>
            <h1 class="h3 mb-0">Verify Your Email</h1>
        </div>

        <!-- Body -->
        <div class="verification-body">
            <!-- Success Message -->
            @if (session('success'))
                <div class="alert alert-success mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    A new verification link has been sent to your email address.
                </div>
            @endif

            <p class="text-muted mb-4">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the
                link we just emailed to <strong>{{ Auth::user()->email }}</strong>? If you didn't receive the email, we
                will gladly send you another.
            </p>

            <div class="d-grid gap-3">
                <!-- Resend Verification Email Form -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-send me-2"></i>Resend Verification Email
                    </button>
                </form>

                <!-- Logout Button -->
                <a class="btn btn-outline-secondary w-100" href="/logout">
                    <i class="bi bi-box-arrow-right me-2"></i>Log Out
                </a>
            </div>

            <!-- User Info -->
            <div class="user-info-card mt-4">
                <div class="d-flex align-items-center mb-2">
                    <h6 class="mb-0">Account Information</h6>
                    <span class="user-badge text-capitalize">{{ Auth::user()->user_type }}</span>
                </div>
                <p class="mb-1 small text-muted">
                    <i class="bi bi-person me-2"></i>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                </p>
                <p class="mb-0 small text-muted">
                    <i class="bi bi-envelope me-2"></i>{{ Auth::user()->email }}
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
