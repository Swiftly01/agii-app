<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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

        .auth-card {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            overflow: hidden;
            max-width: 480px;
            width: 100%;
            transition: var(--transition);
        }

        .auth-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .auth-card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .auth-card-icon {
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

        .auth-card-body {
            padding: 30px;
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

        .alert-success {
            background-color: rgba(143, 199, 74, 0.1);
            border-color: var(--success-color);
            color: #2d5016;
            border-radius: 8px;
        }

        .alert-danger {
            border-radius: 8px;
        }

        .form-control {
            padding: 12px;
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(143, 199, 74, 0.25);
        }
    </style>
</head>

<body>
    <div class="auth-card">
        <div class="auth-card-header">
            <div class="auth-card-icon">
                <i class="bi bi-key"></i>
            </div>
            <h1 class="h3 mb-0">Forgot Password?</h1>
        </div>

        <div class="auth-card-body">
            @if (session('success'))
                <div class="alert alert-success mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <p class="text-muted mb-4">
                No worries — enter the email address on your account and we'll send you a link to reset your password.
            </p>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email"
                        placeholder="Enter your email address" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="d-grid gap-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-send me-2"></i>Send Reset Link
                    </button>

                    <a class="btn btn-outline-secondary w-100" href="{{ route('login') }}">
                        <i class="bi bi-arrow-left me-2"></i>Back to Sign In
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
