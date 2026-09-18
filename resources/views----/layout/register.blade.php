<!DOCTYPE html>
<html lang="en">

<head>
    <title>Agii - Login & Register</title>
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
            display: flex;
            align-items: center;
        }

        .auth-container {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            margin: 20px 0;
            transition: var(--transition);
        }

        .auth-container:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .auth-logo {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .auth-subtitle {
            color: var(--light-dark-color);
            font-size: 1.1rem;
        }

        .user-type-selector {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .user-type-card {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            background: white;
            flex: 1;
        }

        .user-type-card:hover {
            border-color: var(--accent-color);
            transform: translateY(-3px);
        }

        .user-type-card.selected {
            border-color: var(--accent-color);
            background: var(--primary-light);
            box-shadow: 0 5px 15px rgba(143, 199, 74, 0.15);
        }

        .user-type-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: var(--accent-color);
        }

        .form-section {
            margin-bottom: 25px;
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

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(143, 199, 74, 0.25);
        }

        .btn {
            border-radius: 8px;
            padding: 12px 20px;
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

        .form-check-input:checked {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .validation-message {
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 5px;
            display: none;
        }

        .is-invalid {
            border-color: var(--danger-color);
        }

        .is-valid {
            border-color: #28a745;
        }

        .required-field::after {
            content: " *";
            color: var(--danger-color);
        }

        .success-message {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            display: none;
        }

        .auth-switch {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }

        .auth-switch a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .auth-switch a:hover {
            text-decoration: underline;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--light-dark-color);
            cursor: pointer;
        }

        .password-input-group {
            position: relative;
        }

        .provider-features {
            background: var(--primary-light);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .feature-list li {
            padding: 8px 0;
            display: flex;
            align-items: center;
        }

        .feature-list li i {
            color: var(--success-color);
            margin-right: 10px;
        }

        .customer-benefits {
            background: var(--light-grey-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .form-step {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-step.active {
            display: block;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--border-color);
            z-index: 1;
        }

        .step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }

        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--border-color);
            color: var(--light-dark-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: 600;
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .step.active .step-number {
            background: var(--accent-color);
            color: white;
            transform: scale(1.1);
            box-shadow: 0 0 0 5px rgba(143, 199, 74, 0.2);
        }

        .step.completed .step-number {
            background: var(--success-color);
            color: white;
        }

        .step-label {
            font-weight: 600;
            color: var(--light-dark-color);
            transition: var(--transition);
            font-size: 0.8rem;
        }

        .step.active .step-label {
            color: var(--accent-color);
        }

        .business-categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .business-category {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            background: white;
        }

        .business-category:hover {
            border-color: var(--accent-color);
        }

        .business-category.selected {
            border-color: var(--accent-color);
            background: var(--primary-light);
        }

        .social-media-section {
            background: var(--light-grey-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .social-input-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-light);
            border-radius: 8px 0 0 8px;
            border: 1px solid var(--border-color);
            border-right: none;
            color: var(--dark-color);
        }

        .social-input {
            border-radius: 0 8px 8px 0;
            flex: 1;
        }

        @media (max-width: 768px) {
            .auth-container {
                padding: 20px;
            }

            .user-type-selector {
                flex-direction: column;
            }

            .step-label {
                font-size: 0.7rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="auth-container">
                    <!-- Auth Header -->
                    <div class="auth-header">
                        <div class="auth-logo">Agii</div>
                        <p class="auth-subtitle">Join thousands of buyers and sellers in Nigeria's fastest growing
                            marketplace</p>
                    </div>

                    <!-- User Type Selection -->
                    <div class="user-type-selector">
                        <div class="user-type-card" data-type="customer">
                            <div class="user-type-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            <h5>Customer</h5>
                            <p class="text-muted small">I want to buy products & services</p>
                        </div>
                        <div class="user-type-card" data-type="vendor">
                            <div class="user-type-icon">
                                <i class="bi bi-briefcase"></i>
                            </div>
                            <h5>Vendor</h5>
                            <p class="text-muted small">I want to sell products & services</p>
                        </div>
                    </div>

                    <!-- Login Form -->
                    <div class="form-step active" id="loginStep">
                        <h3 class="section-title">Welcome Back</h3>

                        <div id="loginBenefits">
                            <!-- Benefits will be shown based on user type -->
                        </div>

                        <form id="loginForm">
                            <div class="form-section">
                                <div class="mb-3">
                                    <label for="loginEmail" class="form-label fw-semibold required-field">Email
                                        Address</label>
                                    <input type="email" class="form-control" id="loginEmail"
                                        placeholder="Enter your email address" required>
                                    <div class="validation-message" id="loginEmailError">Please enter a valid email
                                        address</div>
                                </div>

                                <div class="mb-3">
                                    <label for="loginPassword"
                                        class="form-label fw-semibold required-field">Password</label>
                                    <div class="password-input-group">
                                        <input type="password" class="form-control" id="loginPassword"
                                            placeholder="Enter your password" required>
                                        <button type="button" class="password-toggle" id="toggleLoginPassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div class="validation-message" id="loginPasswordError">Please enter your password
                                    </div>
                                </div>

                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rememberMe">
                                        <label class="form-check-label" for="rememberMe">
                                            Remember me
                                        </label>
                                    </div>
                                    <a href="#" class="text-decoration-none">Forgot Password?</a>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3" id="loginBtn">
                                Sign In
                            </button>

                            <div class="text-center mb-3">
                                <span class="text-muted">Or continue with</span>
                            </div>

                            <div class="row g-2 mb-4">
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-google"></i> Google
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-facebook"></i> Facebook
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="auth-switch">
                            <p class="mb-0">Don't have an account? <a href="#" id="switchToRegister">Sign up
                                    here</a></p>
                        </div>
                    </div>

                    <!-- Registration Form -->
                    <div class="form-step" id="registerStep">
                        <h3 class="section-title">Create Your Account</h3>

                        <!-- Step Indicator -->
                        <div class="step-indicator">
                            <div class="step active" data-step="1">
                                <div class="step-number">1</div>
                                <div class="step-label">Basic Info</div>
                            </div>
                            <div class="step" data-step="2">
                                <div class="step-number">2</div>
                                <div class="step-label">Details</div>
                            </div>
                            <div class="step" data-step="3">
                                <div class="step-number">3</div>
                                <div class="step-label">Complete</div>
                            </div>
                        </div>

                        <form id="registerForm">
                            <!-- Step 1: Basic Information -->
                            <div class="form-step active" id="registerStep1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstName" class="form-label fw-semibold required-field">First
                                                Name</label>
                                            <input type="text" class="form-control" id="firstName"
                                                placeholder="Enter your first name" required>
                                            <div class="validation-message" id="firstNameError">Please enter your
                                                first name</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="lastName" class="form-label fw-semibold required-field">Last
                                                Name</label>
                                            <input type="text" class="form-control" id="lastName"
                                                placeholder="Enter your last name" required>
                                            <div class="validation-message" id="lastNameError">Please enter your last
                                                name</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="registerEmail" class="form-label fw-semibold required-field">Email
                                        Address</label>
                                    <input type="email" class="form-control" id="registerEmail"
                                        placeholder="Enter your email address" required>
                                    <div class="validation-message" id="registerEmailError">Please enter a valid email
                                        address</div>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label fw-semibold required-field">Phone
                                        Number</label>
                                    <input type="tel" class="form-control" id="phone"
                                        placeholder="Enter your phone number" required>
                                    <div class="validation-message" id="phoneError">Please enter a valid phone number
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="registerPassword"
                                                class="form-label fw-semibold required-field">Password</label>
                                            <div class="password-input-group">
                                                <input type="password" class="form-control" id="registerPassword"
                                                    placeholder="Create a password" required>
                                                <button type="button" class="password-toggle"
                                                    id="toggleRegisterPassword">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            <div class="validation-message" id="registerPasswordError">Password must
                                                be at least 8 characters</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="confirmPassword"
                                                class="form-label fw-semibold required-field">Confirm Password</label>
                                            <div class="password-input-group">
                                                <input type="password" class="form-control" id="confirmPassword"
                                                    placeholder="Confirm your password" required>
                                                <button type="button" class="password-toggle"
                                                    id="toggleConfirmPassword">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            <div class="validation-message" id="confirmPasswordError">Passwords do not
                                                match</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <div></div>
                                    <button type="button" class="btn btn-primary" id="nextToStep2">Next</button>
                                </div>
                            </div>

                            <!-- Step 2: Additional Details -->
                            <div class="form-step" id="registerStep2">
                                <div id="vendorDetails" style="display: none;">
                                    <div class="provider-features">
                                        <h5>Vendor Benefits</h5>
                                        <ul class="feature-list">
                                            <li><i class="bi bi-check-circle"></i> List unlimited products & services
                                            </li>
                                            <li><i class="bi bi-check-circle"></i> Reach thousands of customers</li>
                                            <li><i class="bi bi-check-circle"></i> Secure payment processing</li>
                                            <li><i class="bi bi-check-circle"></i> Business analytics dashboard</li>
                                        </ul>
                                    </div>

                                    <div class="mb-3">
                                        <label for="businessName" class="form-label fw-semibold">Business Name
                                            (Optional)</label>
                                        <input type="text" class="form-control" id="businessName"
                                            placeholder="Enter your business name (if any)">
                                        <div class="text-muted small">If you don't have a business name, we'll use your
                                            personal name</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold required-field">Business Type</label>
                                        <div class="business-categories">
                                            <div class="business-category" data-category="individual">
                                                <i class="bi bi-person-badge fs-4"></i>
                                                <div>Individual</div>
                                            </div>
                                            <div class="business-category" data-category="company">
                                                <i class="bi bi-building fs-4"></i>
                                                <div>Company</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="businessCategory" class="form-label fw-semibold">Business
                                            Category</label>
                                        <select class="form-select" id="businessCategory">
                                            <option value="">Select your main category</option>
                                            <option value="electronics">Electronics</option>
                                            <option value="fashion">Fashion</option>
                                            <option value="home">Home & Garden</option>
                                            <option value="vehicles">Vehicles</option>
                                            <option value="properties">Properties</option>
                                            <option value="services">Services</option>
                                        </select>
                                    </div>

                                    <!-- Social Media Section for Vendors -->
                                    <div class="social-media-section">
                                        <h6>Social Media Links (Optional)</h6>
                                        <p class="text-muted small mb-3">Add your social media profiles to help
                                            customers connect with you</p>

                                        <div class="social-input-group">
                                            <div class="social-icon">
                                                <i class="bi bi-facebook"></i>
                                            </div>
                                            <input type="text" class="form-control social-input" id="facebookLink"
                                                placeholder="Facebook profile URL">
                                        </div>

                                        <div class="social-input-group">
                                            <div class="social-icon">
                                                <i class="bi bi-instagram"></i>
                                            </div>
                                            <input type="text" class="form-control social-input"
                                                id="instagramLink" placeholder="Instagram profile URL">
                                        </div>

                                        <div class="social-input-group">
                                            <div class="social-icon">
                                                <i class="bi bi-twitter"></i>
                                            </div>
                                            <input type="text" class="form-control social-input" id="twitterLink"
                                                placeholder="Twitter profile URL">
                                        </div>

                                        <div class="social-input-group">
                                            <div class="social-icon">
                                                <i class="bi bi-whatsapp"></i>
                                            </div>
                                            <input type="text" class="form-control social-input" id="whatsappLink"
                                                placeholder="WhatsApp number">
                                        </div>
                                    </div>
                                </div>

                                <div id="customerDetails">
                                    <div class="customer-benefits">
                                        <h5>Customer Benefits</h5>
                                        <ul class="feature-list">
                                            <li><i class="bi bi-check-circle"></i> Access to thousands of products</li>
                                            <li><i class="bi bi-check-circle"></i> Find local service providers</li>
                                            <li><i class="bi bi-check-circle"></i> Secure transactions</li>
                                            <li><i class="bi bi-check-circle"></i> Customer reviews & ratings</li>
                                        </ul>
                                    </div>

                                    <div class="mb-3">
                                        <label for="location" class="form-label fw-semibold required-field">Your
                                            Location</label>
                                        <input type="text" class="form-control" id="location"
                                            placeholder="Enter your city or area">
                                        <div class="validation-message" id="locationError">Please enter your location
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Interests (Optional)</label>
                                        <div class="business-categories">
                                            <div class="business-category" data-interest="electronics">
                                                <i class="bi bi-phone fs-4"></i>
                                                <div>Electronics</div>
                                            </div>
                                            <div class="business-category" data-interest="fashion">
                                                <i class="bi bi-bag fs-4"></i>
                                                <div>Fashion</div>
                                            </div>
                                            <div class="business-category" data-interest="home">
                                                <i class="bi bi-house fs-4"></i>
                                                <div>Home</div>
                                            </div>
                                            <div class="business-category" data-interest="services">
                                                <i class="bi bi-tools fs-4"></i>
                                                <div>Services</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                        <label class="form-check-label" for="agreeTerms">
                                            I agree to the <a href="#">Terms of Service</a> and <a
                                                href="#">Privacy Policy</a>
                                        </label>
                                        <div class="validation-message" id="termsError">You must agree to the terms
                                            and conditions</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="newsletter">
                                        <label class="form-check-label" for="newsletter">
                                            Send me updates about new features and promotions
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-primary"
                                        onclick="prevRegisterStep(1)">Previous</button>
                                    <button type="submit" class="btn btn-primary" id="completeRegistration">Create
                                        Account</button>
                                </div>
                            </div>

                            <!-- Step 3: Completion -->
                            <div class="form-step" id="registerStep3">
                                <div class="text-center py-4">
                                    <div class="mb-4">
                                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                                    </div>
                                    <h3 class="text-success">Account Created Successfully!</h3>
                                    <p class="text-muted mb-4" id="welcomeMessage">Welcome to Agii! Your account has
                                        been created successfully.</p>

                                    <div id="vendorNextSteps" style="display: none;">
                                        <div class="alert alert-info">
                                            <h6>Next Steps for Vendors:</h6>
                                            <ul class="text-start">
                                                <li>Complete your business profile</li>
                                                <li>Add your first product or service</li>
                                                <li>Set up your payment method</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-primary" id="goToDashboard">
                                            Go to Dashboard
                                        </button>
                                        <button type="button" class="btn btn-outline-primary" id="addFirstListing">
                                            Add Your First Listing
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="auth-switch">
                            <p class="mb-0">Already have an account? <a href="#" id="switchToLogin">Sign in
                                    here</a></p>
                        </div>
                    </div>

                    <!-- Success Message -->
                    <div class="success-message" id="successMessage">
                        <h5>Registration Successful!</h5>
                        <p>Your account has been created successfully. Redirecting to dashboard...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let currentUserType = 'customer';
        let currentAuthStep = 'login';
        let currentRegisterStep = 1;
        let selectedBusinessType = '';
        let selectedInterests = [];

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            initializeUserTypeSelection();
            initializeAuthSwitching();
            initializePasswordToggles();
            initializeFormValidation();
            initializeBusinessCategories();
            updateBenefitsDisplay();
        });

        function initializeUserTypeSelection() {
            document.querySelectorAll('.user-type-card').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.user-type-card').forEach(c => c.classList.remove(
                        'selected'));
                    this.classList.add('selected');

                    currentUserType = this.dataset.type;
                    updateBenefitsDisplay();
                    updateRegistrationForm();
                });
            });

            // Default selection
            document.querySelector('.user-type-card[data-type="customer"]').classList.add('selected');
        }

        function initializeAuthSwitching() {
            document.getElementById('switchToRegister').addEventListener('click', function(e) {
                e.preventDefault();
                switchAuthMode('register');
            });

            document.getElementById('switchToLogin').addEventListener('click', function(e) {
                e.preventDefault();
                switchAuthMode('login');
            });
        }

        function initializePasswordToggles() {
            // Login password toggle
            document.getElementById('toggleLoginPassword').addEventListener('click', function() {
                const passwordInput = document.getElementById('loginPassword');
                const icon = this.querySelector('i');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });

            // Register password toggles
            document.getElementById('toggleRegisterPassword').addEventListener('click', function() {
                const passwordInput = document.getElementById('registerPassword');
                const icon = this.querySelector('i');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });

            document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
                const passwordInput = document.getElementById('confirmPassword');
                const icon = this.querySelector('i');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        }

        function initializeFormValidation() {
            // Login form submission
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                e.preventDefault();
                if (validateLoginForm()) {
                    performLogin();
                }
            });

            // Register form navigation
            document.getElementById('nextToStep2').addEventListener('click', function() {
                if (validateRegisterStep1()) {
                    nextRegisterStep(2);
                }
            });

            // Register form submission
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                e.preventDefault();
                if (validateRegisterStep2()) {
                    completeRegistration();
                }
            });

            // Go to dashboard button
            document.getElementById('goToDashboard').addEventListener('click', function() {
                window.location.href = currentUserType === 'vendor' ? '/vendor-dashboard' :
                    '/customer-dashboard';
            });

            // Add first listing button
            document.getElementById('addFirstListing').addEventListener('click', function() {
                window.location.href = '/sell';
            });
        }

        function initializeBusinessCategories() {
            // Business type selection
            document.querySelectorAll('.business-category[data-category]').forEach(category => {
                category.addEventListener('click', function() {
                    document.querySelectorAll('.business-category[data-category]').forEach(c => c.classList
                        .remove('selected'));
                    this.classList.add('selected');
                    selectedBusinessType = this.dataset.category;
                });
            });

            // Interest selection
            document.querySelectorAll('.business-category[data-interest]').forEach(interest => {
                interest.addEventListener('click', function() {
                    this.classList.toggle('selected');
                    const interestValue = this.dataset.interest;

                    if (this.classList.contains('selected')) {
                        if (!selectedInterests.includes(interestValue)) {
                            selectedInterests.push(interestValue);
                        }
                    } else {
                        selectedInterests = selectedInterests.filter(item => item !== interestValue);
                    }
                });
            });
        }

        function switchAuthMode(mode) {
            currentAuthStep = mode;

            if (mode === 'login') {
                document.getElementById('loginStep').classList.add('active');
                document.getElementById('registerStep').classList.remove('active');
            } else {
                document.getElementById('loginStep').classList.remove('active');
                document.getElementById('registerStep').classList.add('active');
                resetRegisterForm();
            }
        }

        function nextRegisterStep(step) {
            document.querySelectorAll('#registerForm .form-step').forEach(s => s.classList.remove('active'));
            document.getElementById(`registerStep${step}`).classList.add('active');

            document.querySelectorAll('.step').forEach(s => s.classList.remove('active', 'completed'));
            for (let i = 1; i <= step; i++) {
                const stepElement = document.querySelector(`.step[data-step="${i}"]`);
                if (i === step) {
                    stepElement.classList.add('active');
                } else {
                    stepElement.classList.add('completed');
                }
            }

            currentRegisterStep = step;
        }

        function prevRegisterStep(step) {
            nextRegisterStep(step);
        }

        function updateBenefitsDisplay() {
            const benefitsContainer = document.getElementById('loginBenefits');

            if (currentUserType === 'vendor') {
                benefitsContainer.innerHTML = `
                    <div class="provider-features">
                        <h6>Welcome back, Vendor!</h6>
                        <p class="mb-0">Manage your listings, track orders, and grow your business.</p>
                    </div>
                `;
            } else {
                benefitsContainer.innerHTML = `
                    <div class="customer-benefits">
                        <h6>Welcome back, Customer!</h6>
                        <p class="mb-0">Continue shopping, track your orders, and discover new services.</p>
                    </div>
                `;
            }
        }

        function updateRegistrationForm() {
            if (currentUserType === 'vendor') {
                document.getElementById('vendorDetails').style.display = 'block';
                document.getElementById('customerDetails').style.display = 'none';
            } else {
                document.getElementById('vendorDetails').style.display = 'none';
                document.getElementById('customerDetails').style.display = 'block';
            }
        }

        function validateLoginForm() {
            let isValid = true;
            const email = document.getElementById('loginEmail');
            const password = document.getElementById('loginPassword');

            // Validate email
            if (!email.value || !isValidEmail(email.value)) {
                showError(email, 'loginEmailError', 'Please enter a valid email address');
                isValid = false;
            } else {
                hideError(email, 'loginEmailError');
            }

            // Validate password
            if (!password.value) {
                showError(password, 'loginPasswordError', 'Please enter your password');
                isValid = false;
            } else {
                hideError(password, 'loginPasswordError');
            }

            return isValid;
        }

        function validateRegisterStep1() {
            let isValid = true;
            const fields = ['firstName', 'lastName', 'registerEmail', 'phone', 'registerPassword', 'confirmPassword'];

            fields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                const errorId = `${fieldId}Error`;

                if (!field.value) {
                    showError(field, errorId,
                        `Please enter your ${fieldId.replace(/([A-Z])/g, ' $1').toLowerCase()}`);
                    isValid = false;
                } else {
                    hideError(field, errorId);
                }
            });

            // Validate email format
            const email = document.getElementById('registerEmail');
            if (email.value && !isValidEmail(email.value)) {
                showError(email, 'registerEmailError', 'Please enter a valid email address');
                isValid = false;
            }

            // Validate password match
            const password = document.getElementById('registerPassword');
            const confirmPassword = document.getElementById('confirmPassword');
            if (password.value && confirmPassword.value && password.value !== confirmPassword.value) {
                showError(confirmPassword, 'confirmPasswordError', 'Passwords do not match');
                isValid = false;
            }

            // Validate password length
            if (password.value && password.value.length < 8) {
                showError(password, 'registerPasswordError', 'Password must be at least 8 characters');
                isValid = false;
            }

            return isValid;
        }

        function validateRegisterStep2() {
            let isValid = true;

            // Validate terms agreement
            const termsCheckbox = document.getElementById('agreeTerms');
            if (!termsCheckbox.checked) {
                showError(termsCheckbox, 'termsError', 'You must agree to the terms and conditions');
                isValid = false;
            } else {
                hideError(termsCheckbox, 'termsError');
            }

            // Validate vendor-specific fields
            if (currentUserType === 'vendor') {
                if (!selectedBusinessType) {
                    alert('Please select your business type');
                    isValid = false;
                }
            } else {
                const location = document.getElementById('location');
                if (!location.value) {
                    showError(location, 'locationError', 'Please enter your location');
                    isValid = false;
                } else {
                    hideError(location, 'locationError');
                }
            }

            return isValid;
        }

        function performLogin() {
            const loginBtn = document.getElementById('loginBtn');
            const originalText = loginBtn.innerHTML;

            loginBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Signing In...';
            loginBtn.disabled = true;

            // Simulate API call
            setTimeout(() => {
                alert(`Successfully signed in as ${currentUserType}!`);
                loginBtn.innerHTML = originalText;
                loginBtn.disabled = false;

                // Redirect based on user type
                window.location.href = currentUserType === 'vendor' ? '/vendor-dashboard' :
                    '/customer-dashboard';
            }, 2000);
        }

        function completeRegistration() {
            const registerBtn = document.getElementById('completeRegistration');
            const originalText = registerBtn.innerHTML;

            registerBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Creating Account...';
            registerBtn.disabled = true;

            // Prepare registration data
            const formData = {
                userType: currentUserType,
                firstName: document.getElementById('firstName').value,
                lastName: document.getElementById('lastName').value,
                email: document.getElementById('registerEmail').value,
                phone: document.getElementById('phone').value,
                ...(currentUserType === 'vendor' ? {
                    businessName: document.getElementById('businessName').value,
                    businessType: selectedBusinessType,
                    businessCategory: document.getElementById('businessCategory').value,
                    socialMedia: {
                        facebook: document.getElementById('facebookLink').value,
                        instagram: document.getElementById('instagramLink').value,
                        twitter: document.getElementById('twitterLink').value,
                        whatsapp: document.getElementById('whatsappLink').value
                    }
                } : {
                    location: document.getElementById('location').value,
                    interests: selectedInterests
                })
            };

            console.log('Registration data:', formData);

            // Simulate API call
            setTimeout(() => {
                // Show completion step
                nextRegisterStep(3);

                // Update welcome message
                const welcomeMessage = document.getElementById('welcomeMessage');
                if (currentUserType === 'vendor') {
                    welcomeMessage.textContent =
                        `Welcome to Agii, ${formData.firstName}! Your vendor account has been created successfully.`;
                    document.getElementById('vendorNextSteps').style.display = 'block';
                } else {
                    welcomeMessage.textContent =
                        `Welcome to Agii, ${formData.firstName}! Your customer account has been created successfully.`;
                }

                registerBtn.innerHTML = originalText;
                registerBtn.disabled = false;
            }, 2000);
        }

        function resetRegisterForm() {
            currentRegisterStep = 1;
            selectedBusinessType = '';
            selectedInterests = [];

            document.querySelectorAll('.step').forEach(s => s.classList.remove('active', 'completed'));
            document.querySelector('.step[data-step="1"]').classList.add('active');

            document.querySelectorAll('#registerForm .form-step').forEach(s => s.classList.remove('active'));
            document.getElementById('registerStep1').classList.add('active');

            document.querySelectorAll('.business-category').forEach(c => c.classList.remove('selected'));
            document.getElementById('agreeTerms').checked = false;

            // Reset social media fields
            document.getElementById('facebookLink').value = '';
            document.getElementById('instagramLink').value = '';
            document.getElementById('twitterLink').value = '';
            document.getElementById('whatsappLink').value = '';
        }

        function showError(field, errorId, message) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            const errorElement = document.getElementById(errorId);
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }

        function hideError(field, errorId) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            document.getElementById(errorId).style.display = 'none';
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    </script>
</body>

</html>
