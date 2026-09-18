@extends('layout.layout')
@section('title', ' - Login')
@section('content')

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
                    <div class="user-type-selector" id="usertypeselector">
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

                        <form id="loginForm" method="POST" action="{{ route('login.post') }}">

                            @csrf
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Oops!</strong> Please fix the errors below:
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif


                            <div class="form-section">
                                <div class="mb-3">
                                    <label for="loginEmail" class="form-label fw-semibold required-field">Email
                                        Address</label>
                                    <input type="email" class="form-control" id="loginEmail" name="email"
                                        placeholder="Enter your email address" required value="{{ old('email') }}">
                                    <div class="validation-message" id="loginEmailError">Please enter a valid email
                                        address</div>
                                    @error('email')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="loginPassword"
                                        class="form-label fw-semibold required-field">Password</label>
                                    <div class="password-input-group">
                                        <input type="password" class="form-control" id="loginPassword" name="password"
                                            placeholder="Enter your password" required>
                                        <button type="button" class="password-toggle" id="toggleLoginPassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div class="validation-message" id="loginPasswordError">Please enter your password
                                    </div>
                                    @error('password')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                                        <label class="form-check-label" for="rememberMe">
                                            Remember me
                                        </label>
                                    </div>

                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3" id="loginBtn">
                                Sign In
                            </button>

                            <div class="text-center mb-3 d-none">
                                <span class="text-muted">Or continue with</span>
                            </div>

                            <div class="row g-2 mb-4 d-none">
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

                        <form id="registerForm" method="POST" action="{{ route('register') }}">
                            @csrf
                            <!-- Hidden fields for form submission -->
                            <input type="hidden" name="user_type" id="userTypeInput" value="customer">
                            <input type="hidden" name="business_type" id="businessTypeInput" value="">
                            <input type="hidden" name="interests" id="interestsInput" value="">

                            <!-- Step 1: Basic Information -->
                            <div class="form-step active" id="registerStep1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstName" class="form-label fw-semibold required-field">First
                                                Name</label>
                                            <input type="text" class="form-control" id="firstName" name="first_name"
                                                placeholder="Enter your first name" required
                                                value="{{ old('first_name') }}">
                                            <div class="validation-message" id="firstNameError">Please enter your
                                                first name</div>
                                            @error('first_name')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="lastName" class="form-label fw-semibold required-field">Last
                                                Name</label>
                                            <input type="text" class="form-control" id="lastName" name="last_name"
                                                placeholder="Enter your last name" required
                                                value="{{ old('last_name') }}">
                                            <div class="validation-message" id="lastNameError">Please enter your last
                                                name</div>
                                            @error('last_name')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="registerEmail" class="form-label fw-semibold required-field">Email
                                        Address</label>
                                    <input type="email" class="form-control" id="registerEmail" name="email"
                                        placeholder="Enter your email address" required value="{{ old('email') }}">
                                    <div class="validation-message" id="registerEmailError">Please enter a valid email
                                        address</div>
                                    @error('email')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label fw-semibold required-field">Phone
                                        Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        placeholder="Enter your phone number" required value="{{ old('phone') }}">
                                    <div class="validation-message" id="phoneError">Please enter a valid phone number
                                    </div>
                                    @error('phone')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="registerPassword"
                                                class="form-label fw-semibold required-field">Password</label>
                                            <div class="password-input-group">
                                                <input type="password" class="form-control" id="registerPassword"
                                                    name="password" placeholder="Create a password" required>
                                                <button type="button" class="password-toggle"
                                                    id="toggleRegisterPassword">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            <div class="validation-message" id="registerPasswordError">Password must
                                                be at least 8 characters</div>
                                            @error('password')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="confirmPassword"
                                                class="form-label fw-semibold required-field">Confirm Password</label>
                                            <div class="password-input-group">
                                                <input type="password" class="form-control" id="confirmPassword"
                                                    name="password_confirmation" placeholder="Confirm your password"
                                                    required>
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

                                <div class="mb-3">
                                    <label for="referralCode" class="form-label fw-semibold">Referral Code
                                        (Optional)</label>
                                    <input type="text" class="form-control" id="referralCode" name="referral_code"
                                        placeholder="Enter referral code if you have one"
                                        value="{{ old('referral_code') }}">
                                    <div class="text-muted small">If someone referred you, enter their code here</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="state"
                                                class="form-label fw-semibold required-field">State</label>
                                            <select id="state" name="state" class="form-control" required
                                                onchange="populateLGAs()">
                                                <option value="">-- Select State --</option>
                                                @if (old('state'))
                                                    <option value="{{ old('state') }}" selected>{{ old('state') }}
                                                    </option>
                                                @endif
                                            </select>
                                            <div class="validation-message" id="stateError">Please select your state</div>
                                            @error('state')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="local_government"
                                                class="form-label fw-semibold required-field">Local Government</label>
                                            <select id="local_government" name="local_government" class="form-control"
                                                required>
                                                <option value="">-- Select Local Government --</option>
                                                @if (old('local_government'))
                                                    <option value="{{ old('local_government') }}" selected>
                                                        {{ old('local_government') }}</option>
                                                @endif
                                            </select>
                                            <div class="validation-message" id="lgaError">Please select your local
                                                government</div>
                                            @error('local_government')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label fw-semibold required-field">Shop
                                        Address</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        placeholder="Enter your address" required value="{{ old('address') }}">
                                    <div class="validation-message" id="addressError">Please enter your address</div>
                                    @error('address')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
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
                                            name="business_name" placeholder="Enter your business name (if any)"
                                            value="{{ old('business_name') }}">
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
                                        <select class="form-select" id="businessCategory" name="business_category">
                                            <option value="">Select your main category</option>
                                            <option value="electronics"
                                                {{ old('business_category') == 'electronics' ? 'selected' : '' }}>
                                                Electronics</option>
                                            <option value="fashion"
                                                {{ old('business_category') == 'fashion' ? 'selected' : '' }}>Fashion
                                            </option>
                                            <option value="home"
                                                {{ old('business_category') == 'home' ? 'selected' : '' }}>Home & Garden
                                            </option>
                                            <option value="vehicles"
                                                {{ old('business_category') == 'vehicles' ? 'selected' : '' }}>Vehicles
                                            </option>
                                            <option value="properties"
                                                {{ old('business_category') == 'properties' ? 'selected' : '' }}>Properties
                                            </option>
                                            <option value="services"
                                                {{ old('business_category') == 'services' ? 'selected' : '' }}>Services
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Social Media Section for Vendors -->
                                    <div class="social-media-section">
                                        <h6>Social Media Links (Optional)</h6>
                                        <p class="text-muted small mb-3">Add your social media profiles to help
                                            customers connect with you</p>
                                        {{--
                                        <div class="social-input-group">
                                            <div class="social-icon">
                                                <i class="bi bi-facebook"></i>
                                            </div>
                                            <input type="text" class="form-control social-input" id="facebookLink"
                                                name="facebook_link" placeholder="Facebook profile URL"
                                                value="{{ old('facebook_link') }}">
                                        </div>

                                        <div class="social-input-group">
                                            <div class="social-icon">
                                                <i class="bi bi-instagram"></i>
                                            </div>
                                            <input type="text" class="form-control social-input" id="instagramLink"
                                                name="instagram_link" placeholder="Instagram profile URL"
                                                value="{{ old('instagram_link') }}">
                                        </div>

                                        <div class="social-input-group">
                                            <div class="social-icon">
                                                <i class="bi bi-twitter"></i>
                                            </div>
                                            <input type="text" class="form-control social-input" id="twitterLink"
                                                name="twitter_link" placeholder="Twitter profile URL"
                                                value="{{ old('twitter_link') }}">
                                        </div> --}}

                                        <div class="social-input-group">
                                            <div class="social-icon">
                                                <i class="bi bi-whatsapp"></i>
                                            </div>
                                            <input type="text" class="form-control social-input" id="whatsappLink"
                                                name="whatsapp_link" placeholder="WhatsApp number"
                                                value="{{ old('whatsapp_link') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- <div id="customerDetails">
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
                                        <input type="text" class="form-control" id="location" name="location"
                                            placeholder="Enter your city or area" value="{{ old('location') }}">
                                        <div class="validation-message" id="locationError">Please enter your location
                                        </div>
                                        @error('location')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
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
                                </div> --}}

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="agreeTerms"
                                            name="agree_terms" required {{ old('agree_terms') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="agreeTerms">
                                            I agree to the <a href="#">Terms of Service</a> and <a
                                                href="#">Privacy Policy</a>
                                        </label>
                                        <div class="validation-message" id="termsError">You must agree to the terms
                                            and conditions</div>
                                        @error('agree_terms')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="newsletter"
                                            name="newsletter" {{ old('newsletter') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="newsletter">
                                            Send me updates about new features and promotions
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-primary"
                                        id="prevToStep1">Previous</button>
                                    <button type="submit" class="btn btn-primary" id="completeRegistration">Create
                                        Account</button>
                                </div>
                            </div>

                            <!-- Step 3: Completion (Only shown after successful submission) -->
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
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
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

        .text-danger {
            color: red;
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
@endpush


@push('scripts')
    @push('scripts')
        <script>
            let currentUserType = 'customer';
            let currentAuthStep = 'login';
            let currentRegisterStep = 1;
            let selectedBusinessType = '';
            let selectedInterests = [];
            const usertypeselector = document.getElementById('usertypeselector')

            // Initialize the page
            document.addEventListener('DOMContentLoaded', function() {
                initializeUserTypeSelection();
                initializeAuthSwitching();
                initializePasswordToggles();
                initializeFormValidation();
                initializeBusinessCategories();
                updateBenefitsDisplay();

                // Check for form errors and show appropriate step
                checkFormErrors();
            });


            function initializeUserTypeSelection() {
                const userTypeCards = document.querySelectorAll('.user-type-card');
                if (!userTypeCards.length) return;

                userTypeCards.forEach(card => {
                    card.addEventListener('click', function() {
                        document.querySelectorAll('.user-type-card').forEach(c => c.classList.remove(
                            'selected'));
                        this.classList.add('selected');

                        currentUserType = this.dataset.type;
                        const userTypeInput = document.getElementById('userTypeInput');
                        if (userTypeInput) {
                            userTypeInput.value = currentUserType;
                        }
                        updateBenefitsDisplay();
                        updateRegistrationForm();
                    });
                });

                // Default selection
                const defaultCard = document.querySelector('.user-type-card[data-type="customer"]');
                if (defaultCard) {
                    defaultCard.classList.add('selected');
                }
            }

            function initializeAuthSwitching() {
                const switchToRegister = document.getElementById('switchToRegister');
                const switchToLogin = document.getElementById('switchToLogin');

                if (switchToRegister) {
                    switchToRegister.addEventListener('click', function(e) {
                        e.preventDefault();
                        switchAuthMode('register');
                    });
                }

                if (switchToLogin) {
                    switchToLogin.addEventListener('click', function(e) {
                        e.preventDefault();
                        switchAuthMode('login');
                    });
                }
            }

            function initializePasswordToggles() {
                // Login password toggle
                const toggleLoginPassword = document.getElementById('toggleLoginPassword');
                if (toggleLoginPassword) {
                    toggleLoginPassword.addEventListener('click', function() {
                        const passwordInput = document.getElementById('loginPassword');
                        if (!passwordInput) return;

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

                // Register password toggles
                const toggleRegisterPassword = document.getElementById('toggleRegisterPassword');
                if (toggleRegisterPassword) {
                    toggleRegisterPassword.addEventListener('click', function() {
                        const passwordInput = document.getElementById('registerPassword');
                        if (!passwordInput) return;

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

                const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
                if (toggleConfirmPassword) {
                    toggleConfirmPassword.addEventListener('click', function() {
                        const passwordInput = document.getElementById('confirmPassword');
                        if (!passwordInput) return;

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
            }

            function initializeFormValidation() {
                // Login form submission
                const loginForm = document.getElementById('loginForm');
                if (loginForm) {
                    loginForm.addEventListener('submit', function(e) {
                        // Let Laravel handle the validation
                        // Just show loading state
                        const loginBtn = document.getElementById('loginBtn');
                        if (loginBtn) {
                            const originalText = loginBtn.innerHTML;
                            loginBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Signing In...';
                            loginBtn.disabled = true;

                            // Re-enable after 3 seconds in case of error
                            setTimeout(() => {
                                loginBtn.innerHTML = originalText;
                                loginBtn.disabled = false;
                            }, 3000);
                        }
                    });
                }

                // Register form navigation
                const nextToStep2 = document.getElementById('nextToStep2');
                if (nextToStep2) {
                    nextToStep2.addEventListener('click', function() {
                        if (validateRegisterStep1()) {
                            nextRegisterStep(2);
                        }
                    });
                }

                const prevToStep1 = document.getElementById('prevToStep1');
                if (prevToStep1) {
                    prevToStep1.addEventListener('click', function() {
                        prevRegisterStep(1);
                    });
                }

                // Register form submission - let Laravel handle it
                const registerForm = document.getElementById('registerForm');
                if (registerForm) {
                    registerForm.addEventListener('submit', function(e) {
                        // Update hidden fields before submission
                        const businessTypeInput = document.getElementById('businessTypeInput');
                        const interestsInput = document.getElementById('interestsInput');

                        if (businessTypeInput) businessTypeInput.value = selectedBusinessType;
                        if (interestsInput) interestsInput.value = JSON.stringify(selectedInterests);

                        // Show loading state
                        const registerBtn = document.getElementById('completeRegistration');
                        if (registerBtn) {
                            const originalText = registerBtn.innerHTML;
                            registerBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Creating Account...';
                            registerBtn.disabled = true;

                            // Re-enable after 3 seconds in case of error
                            setTimeout(() => {
                                registerBtn.innerHTML = originalText;
                                registerBtn.disabled = false;
                            }, 3000);
                        }

                        // Let the form submit normally - Laravel will handle validation
                    });
                }

                // Go to dashboard button
                const goToDashboard = document.getElementById('goToDashboard');
                if (goToDashboard) {
                    goToDashboard.addEventListener('click', function() {
                        window.location.href = currentUserType === 'vendor' ? '/vendor/dashboard' : '/dashboard';
                    });
                }

                // Add first listing button
                const addFirstListing = document.getElementById('addFirstListing');
                if (addFirstListing) {
                    addFirstListing.addEventListener('click', function() {
                        window.location.href = '/listings/create';
                    });
                }
            }

            function initializeBusinessCategories() {
                // Business type selection
                const businessCategories = document.querySelectorAll('.business-category[data-category]');
                if (businessCategories.length) {
                    businessCategories.forEach(category => {
                        category.addEventListener('click', function() {
                            document.querySelectorAll('.business-category[data-category]').forEach(c => c
                                .classList.remove('selected'));
                            this.classList.add('selected');
                            selectedBusinessType = this.dataset.category;
                        });
                    });
                }

                // Interest selection
                const interestCategories = document.querySelectorAll('.business-category[data-interest]');
                if (interestCategories.length) {
                    interestCategories.forEach(interest => {
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
            }

            function switchAuthMode(mode) {
                currentAuthStep = mode;

                const loginStep = document.getElementById('loginStep');
                const registerStep = document.getElementById('registerStep');


                if (mode === 'login') {
                    if (loginStep) loginStep.classList.add('active');
                    if (registerStep) registerStep.classList.remove('active');
                    usertypeselector?.classList.add('d-none');
                } else {
                    if (loginStep) loginStep.classList.remove('active');
                    if (registerStep) registerStep.classList.add('active');
                    usertypeselector?.classList.remove('d-none');
                    // Don't reset form if there are validation errors
                    if (!document.querySelector('.text-danger.small')) {
                        resetRegisterForm();
                    }
                }
            }


            if (window.location.href.includes("register")) {
                loginStep?.classList.remove('active', 'd-none');
                registerStep?.classList.add('active');
                usertypeselector?.classList.remove('d-none');
            } else {

                usertypeselector?.classList.add('d-none');
            }



            function nextRegisterStep(step) {
                const formSteps = document.querySelectorAll('#registerForm .form-step');
                if (!formSteps.length) return;

                formSteps.forEach(s => s.classList.remove('active'));
                const currentStep = document.getElementById(`registerStep${step}`);
                if (currentStep) {
                    currentStep.classList.add('active');
                }

                const steps = document.querySelectorAll('.step');
                steps.forEach(s => s.classList.remove('active', 'completed'));

                for (let i = 1; i <= step; i++) {
                    const stepElement = document.querySelector(`.step[data-step="${i}"]`);
                    if (stepElement) {
                        if (i === step) {
                            stepElement.classList.add('active');
                        } else {
                            stepElement.classList.add('completed');
                        }
                    }
                }

                currentRegisterStep = step;
            }

            function prevRegisterStep(step) {
                nextRegisterStep(step);
            }

            function updateBenefitsDisplay() {
                const benefitsContainer = document.getElementById('loginBenefits');
                if (!benefitsContainer) return;

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
                const vendorDetails = document.getElementById('vendorDetails');
                const customerDetails = document.getElementById('customerDetails');

                if (currentUserType === 'vendor') {
                    if (vendorDetails) vendorDetails.style.display = 'block';
                    if (customerDetails) customerDetails.style.display = 'none';
                } else {
                    if (vendorDetails) vendorDetails.style.display = 'none';
                    if (customerDetails) customerDetails.style.display = 'block';
                }
            }

            function validateRegisterStep1() {
                let isValid = true;
                const fields = ['firstName', 'lastName', 'registerEmail', 'phone', 'registerPassword', 'confirmPassword',
                    'state', 'local_government', 'city'
                ];

                fields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (!field) return;

                    const errorId = `${fieldId}Error`;
                    const errorElement = document.getElementById(errorId);

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
                if (email && email.value && !isValidEmail(email.value)) {
                    showError(email, 'registerEmailError', 'Please enter a valid email address');
                    isValid = false;
                }

                // Validate password match
                const password = document.getElementById('registerPassword');
                const confirmPassword = document.getElementById('confirmPassword');
                if (password && confirmPassword && password.value && confirmPassword.value && password.value !==
                    confirmPassword
                    .value) {
                    showError(confirmPassword, 'confirmPasswordError', 'Passwords do not match');
                    isValid = false;
                }

                // Validate password length
                if (password && password.value && password.value.length < 8) {
                    showError(password, 'registerPasswordError', 'Password must be at least 8 characters');
                    isValid = false;
                }

                return isValid;
            }

            function resetRegisterForm() {
                currentRegisterStep = 1;
                selectedBusinessType = '';
                selectedInterests = [];

                const steps = document.querySelectorAll('.step');
                steps.forEach(s => s.classList.remove('active', 'completed'));

                const firstStep = document.querySelector('.step[data-step="1"]');
                if (firstStep) {
                    firstStep.classList.add('active');
                }

                const formSteps = document.querySelectorAll('#registerForm .form-step');
                formSteps.forEach(s => s.classList.remove('active'));

                const registerStep1 = document.getElementById('registerStep1');
                if (registerStep1) {
                    registerStep1.classList.add('active');
                }

                const businessCategories = document.querySelectorAll('.business-category');
                businessCategories.forEach(c => c.classList.remove('selected'));

                // Reset hidden fields
                const userTypeInput = document.getElementById('userTypeInput');
                const businessTypeInput = document.getElementById('businessTypeInput');
                const interestsInput = document.getElementById('interestsInput');

                if (userTypeInput) userTypeInput.value = 'customer';
                if (businessTypeInput) businessTypeInput.value = '';
                if (interestsInput) interestsInput.value = '';
            }

            function showError(field, errorId, message) {
                if (!field) return;

                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
                const errorElement = document.getElementById(errorId);
                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.style.display = 'block';
                }
            }

            function hideError(field, errorId) {
                if (!field) return;

                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
                const errorElement = document.getElementById(errorId);
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            }

            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }
        </script>

        <script>
            // Nigerian States and LGAs data
            const lgas = {
                "Abia": ["Aba North", "Aba South", "Arochukwu", "Bende", "Ikwuano", "Isiala Ngwa North",
                    "Isiala Ngwa South", "Isuikwuato", "Obi Ngwa", "Ohafia", "Osisioma", "Ugwunagbo", "Ukwa East",
                    "Ukwa West", "Umuahia North", "Umuahia South", "Umu Nneochi"
                ],
                "Adamawa": ["Demsa", "Fufore", "Ganye", "Girei", "Gombi", "Guyuk", "Hong", "Jada", "Lamurde", "Madagali",
                    "Maiha", "Mayo-Belwa", "Michika", "Mubi North", "Mubi South", "Numan", "Shelleng", "Song", "Toungo",
                    "Yola North", "Yola South"
                ],
                "Akwa Ibom": ["Abak", "Eastern Obolo", "Eket", "Esit Eket", "Essien Udim", "Etim Ekpo", "Etinan", "Ibeno",
                    "Ibesikpo Asutan", "Ibiono Ibom", "Ika", "Ikono", "Ikot Abasi", "Ikot Ekpene", "Ini", "Itu", "Mbo",
                    "Mkpat Enin", "Nsit Atai", "Nsit Ibom", "Nsit Ubium", "Obot Akara", "Okobo", "Onna", "Oron",
                    "Oruk Anam", "Udung Uko", "Ukanafun", "Uruan", "Urue-Offong/Oruko", "Uyo"
                ],
                "Anambra": ["Aguata", "Anambra East", "Anambra West", "Anaocha", "Awka North", "Awka South", "Ayamelum",
                    "Dunukofia", "Ekwusigo", "Idemili North", "Idemili South", "Ihiala", "Njikoka", "Nnewi North",
                    "Nnewi South", "Ogbaru", "Onitsha North", "Onitsha South", "Orumba North", "Orumba South", "Oyi"
                ],
                "Bauchi": ["Alkaleri", "Bauchi", "Bogoro", "Damban", "Darazo", "Dass", "Gamawa", "Ganjuwa", "Giade",
                    "Itas/Gadau", "Jama'are", "Katagum", "Kirfi", "Misau", "Ningi", "Shira", "Tafawa Balewa", "Toro",
                    "Warji", "Zaki"
                ],
                "Bayelsa": ["Brass", "Ekeremor", "Kolokuma/Opokuma", "Nembe", "Ogbia", "Sagbama", "Southern Ijaw",
                    "Yenagoa"
                ],
                "Benue": ["Ado", "Agatu", "Apa", "Buruku", "Gboko", "Guma", "Gwer East", "Gwer West", "Katsina-Ala",
                    "Konshisha", "Kwande", "Logo", "Makurdi", "Obi", "Ogbadibo", "Ohimini", "Oju", "Okpokwu", "Otukpo",
                    "Tarka", "Ukum", "Ushongo", "Vandeikya"
                ],
                "Borno": ["Abadam", "Askira/Uba", "Bama", "Bayo", "Biu", "Chibok", "Damboa", "Dikwa", "Gubio", "Guzamala",
                    "Gwoza", "Hawul", "Jere", "Kaga", "Kala/Balge", "Konduga", "Kukawa", "Kwaya Kusar", "Mafa",
                    "Magumeri", "Maiduguri", "Marte", "Mobbar", "Monguno", "Ngala", "Nganzai", "Shani"
                ],
                "Cross River": ["Abi", "Akamkpa", "Akpabuyo", "Bakassi", "Bekwarra", "Biase", "Boki", "Calabar Municipal",
                    "Calabar South", "Etung", "Ikom", "Obanliku", "Obubra", "Obudu", "Odukpani", "Ogoja", "Yakurr",
                    "Yala"
                ],
                "Delta": ["Aniocha North", "Aniocha South", "Bomadi", "Burutu", "Ethiope East", "Ethiope West",
                    "Ika North East", "Ika South", "Isoko North", "Isoko South", "Ndokwa East", "Ndokwa West", "Okpe",
                    "Oshimili North", "Oshimili South", "Patani", "Sapele", "Udu", "Ughelli North", "Ughelli South",
                    "Ukwuani", "Uvwie", "Warri North", "Warri South", "Warri South West"
                ],
                "Ebonyi": ["Abakaliki", "Afikpo North", "Afikpo South", "Ebonyi", "Ezza North", "Ezza South", "Ikwo",
                    "Ishielu", "Ivo", "Izzi", "Ohaozara", "Ohaukwu", "Onicha"
                ],
                "Edo": ["Akoko-Edo", "Egor", "Esan Central", "Esan North-East", "Esan South-East", "Esan West",
                    "Etsako Central", "Etsako East", "Etsako West", "Igueben", "Ikpoba-Okha", "Orhionmwon", "Oredo",
                    "Ovia North-East", "Ovia South-West", "Owan East", "Owan West", "Uhunmwonde"
                ],
                "Ekiti": ["Ado Ekiti", "Efon", "Ekiti East", "Ekiti South-West", "Ekiti West", "Emure", "Gbonyin",
                    "Ido-Osi", "Ijero", "Ikere", "Ikole", "Ilejemeje", "Irepodun/Ifelodun", "Ise/Orun", "Moba", "Oye"
                ],
                "Enugu": ["Aninri", "Awgu", "Enugu East", "Enugu North", "Enugu South", "Ezeagu", "Igbo Etiti",
                    "Igbo Eze North", "Igbo Eze South", "Isi Uzo", "Nkanu East", "Nkanu West", "Nsukka", "Oji River",
                    "Udenu", "Udi", "Uzo-Uwani"
                ],
                "FCT": ["Abaji", "Bwari", "Gwagwalada", "Kuje", "Kwali", "Municipal Area Council"],
                "Gombe": ["Akko", "Balanga", "Billiri", "Dukku", "Funakaye", "Gombe", "Kaltungo", "Kwami", "Nafada",
                    "Shongom", "Yamaltu/Deba"
                ],
                "Imo": ["Aboh Mbaise", "Ahiazu Mbaise", "Ehime Mbano", "Ezinihitte", "Ideato North", "Ideato South",
                    "Ihitte/Uboma", "Ikeduru", "Isiala Mbano", "Isu", "Mbaitoli", "Ngor Okpala", "Njaba", "Nkwerre",
                    "Nwangele", "Obowo", "Oguta", "Ohaji/Egbema", "Okigwe", "Onuimo", "Orlu", "Orsu", "Oru East",
                    "Oru West", "Owerri Municipal", "Owerri North", "Owerri West"
                ],
                "Jigawa": ["Auyo", "Babura", "Biriniwa", "Birnin Kudu", "Buji", "Dutse", "Gagarawa", "Garki", "Gumel",
                    "Guri", "Gwaram", "Gwiwa", "Hadejia", "Jahun", "Kafin Hausa", "Kazaure", "Kiri Kasama", "Kiyawa",
                    "Kaugama", "Maigatari", "Malam Madori", "Miga", "Ringim", "Roni", "Sule Tankarkar", "Taura",
                    "Yankwashi"
                ],
                "Kaduna": ["Birnin Gwari", "Chikun", "Giwa", "Igabi", "Ikara", "Jaba", "Jema'a", "Kachia", "Kaduna North",
                    "Kaduna South", "Kagarko", "Kajuru", "Kaura", "Kauru", "Kubau", "Kudan", "Lere", "Makarfi",
                    "Sabon Gari", "Sanga", "Soba", "Zangon Kataf", "Zaria"
                ],
                "Kano": ["Ajingi", "Albasu", "Bagwai", "Bebeji", "Bichi", "Bunkure", "Dala", "Dambatta", "Dawakin Kudu",
                    "Dawakin Tofa", "Doguwa", "Fagge", "Gabasawa", "Garko", "Garum Mallam", "Gaya", "Gezawa", "Gwale",
                    "Gwarzo", "Kabo", "Kano Municipal", "Karaye", "Kibiya", "Kiru", "Kumbotso", "Kunchi", "Kura",
                    "Madobi", "Makoda", "Minjibir", "Nasarawa", "Rano", "Rimin Gado", "Rogo", "Shanono", "Sumaila",
                    "Takai", "Tarauni", "Tofa", "Tsanyawa", "Tudun Wada", "Ungogo", "Warawa", "Wudil"
                ],
                "Katsina": ["Bakori", "Batagarawa", "Batsari", "Baure", "Bindawa", "Charanchi", "Dandume", "Danja",
                    "Dan Musa", "Daura", "Dutsi", "Dutsin Ma", "Faskari", "Funtua", "Ingawa", "Jibia", "Kafur", "Kaita",
                    "Kankara", "Kankia", "Katsina", "Kurfi", "Kusada", "Mai'Adua", "Malumfashi", "Mani", "Mashi",
                    "Matazu", "Musawa", "Rimi", "Sabuwa", "Safana", "Sandamu", "Zango"
                ],
                "Kebbi": ["Aleiro", "Arewa Dandi", "Argungu", "Augie", "Bagudo", "Birnin Kebbi", "Bunza", "Dandi", "Fakai",
                    "Gwandu", "Jega", "Kalgo", "Koko/Besse", "Maiyama", "Ngaski", "Sakaba", "Shanga", "Suru",
                    "Wasagu/Danko", "Yauri", "Zuru"
                ],
                "Kogi": ["Adavi", "Ajaokuta", "Ankpa", "Bassa", "Dekina", "Ibaji", "Idah", "Igalamela-Odolu", "Ijumu",
                    "Kabba/Bunu", "Kogi", "Lokoja", "Mopa-Muro", "Ofu", "Ogori/Magongo", "Okehi", "Okene", "Olamaboro",
                    "Omala", "Yagba East", "Yagba West"
                ],
                "Kwara": ["Asa", "Baruten", "Edu", "Ekiti", "Ifelodun", "Ilorin East", "Ilorin South", "Ilorin West",
                    "Irepodun", "Isin", "Kaiama", "Moro", "Offa", "Oke Ero", "Oyun", "Pategi"
                ],
                "Lagos": ["Agege", "Ajeromi-Ifelodun", "Alimosho", "Amuwo-Odofin", "Apapa", "Badagry", "Epe", "Eti Osa",
                    "Ibeju-Lekki", "Ifako-Ijaiye", "Ikeja", "Ikorodu", "Kosofe", "Lagos Island", "Lagos Mainland",
                    "Mushin", "Ojo", "Oshodi-Isolo", "Shomolu", "Surulere"
                ],
                "Nasarawa": ["Akwanga", "Awe", "Doma", "Karu", "Keana", "Keffi", "Kokona", "Lafia", "Nasarawa",
                    "Nasarawa Egon", "Obi", "Toto", "Wamba"
                ],
                "Niger": ["Agaie", "Agwara", "Bida", "Borgu", "Bosso", "Chanchaga", "Edati", "Gbako", "Gurara", "Katcha",
                    "Kontagora", "Lapai", "Lavun", "Magama", "Mariga", "Mashegu", "Mokwa", "Moya", "Paikoro", "Rafi",
                    "Rijau", "Shiroro", "Suleja", "Tafa", "Wushishi"
                ],
                "Ogun": ["Abeokuta North", "Abeokuta South", "Ado-Odo/Ota", "Egbado North", "Egbado South", "Ewekoro",
                    "Ifo", "Ijebu East", "Ijebu North", "Ijebu North East", "Ijebu Ode", "Ikenne", "Imeko Afon",
                    "Ipokia", "Obafemi Owode", "Odeda", "Odogbolu", "Ogun Waterside", "Remo North", "Shagamu"
                ],
                "Ondo": ["Akoko North-East", "Akoko North-West", "Akoko South-West", "Akoko South-East", "Akure North",
                    "Akure South", "Ese Odo", "Idanre", "Ifedore", "Ilaje", "Ile Oluji/Okeigbo", "Irele", "Odigbo",
                    "Okitipupa", "Ondo East", "Ondo West", "Ose", "Owo"
                ],
                "Osun": ["Atakunmosa East", "Atakunmosa West", "Aiyedaade", "Aiyedire", "Boluwaduro", "Boripe", "Ede North",
                    "Ede South", "Egbedore", "Ejigbo", "Ife Central", "Ife East", "Ife North", "Ife South", "Ifedayo",
                    "Ifelodun", "Ila", "Ilesa East", "Ilesa West", "Irepodun", "Irewole", "Isokan", "Iwo", "Obokun",
                    "Odo Otin", "Ola Oluwa", "Olorunda", "Oriade", "Orolu", "Osogbo"
                ],
                "Oyo": ["Afijio", "Akinyele", "Atiba", "Atisbo", "Egbeda", "Ibadan North", "Ibadan North-East",
                    "Ibadan North-West", "Ibadan South-East", "Ibadan South-West", "Ibarapa Central", "Ibarapa East",
                    "Ibarapa North", "Ido", "Irepo", "Iseyin", "Itesiwaju", "Iwajowa", "Kajola", "Lagelu",
                    "Ogbomosho North", "Ogbomosho South", "Ogo Oluwa", "Olorunsogo", "Oluyole", "Ona Ara", "Orelope",
                    "Ori Ire", "Oyo East", "Oyo West", "Saki East", "Saki West", "Surulere"
                ],
                "Plateau": ["Barkin Ladi", "Bassa", "Bokkos", "Jos East", "Jos North", "Jos South", "Kanam", "Kanke",
                    "Langtang North", "Langtang South", "Mangu", "Mikang", "Pankshin", "Qua'an Pan", "Riyom", "Shendam",
                    "Wase"
                ],
                "Rivers": ["Abua/Odual", "Ahoada East", "Ahoada West", "Akuku-Toru", "Andoni", "Asari-Toru", "Bonny",
                    "Degema", "Eleme", "Emuoha", "Etche", "Gokana", "Ikwerre", "Khana", "Obio/Akpor",
                    "Ogba/Egbema/Ndoni", "Ogu/Bolo", "Okrika", "Omuma", "Opobo/Nkoro", "Oyigbo", "Port Harcourt", "Tai"
                ],
                "Sokoto": ["Binji", "Bodinga", "Dange Shuni", "Gada", "Goronyo", "Gudu", "Gwadabawa", "Illela", "Isa",
                    "Kebbe", "Kware", "Rabah", "Sabon Birni", "Shagari", "Silame", "Sokoto North", "Sokoto South",
                    "Tambuwal", "Tangaza", "Tureta", "Wamako", "Wurno", "Yabo"
                ],
                "Taraba": ["Ardo Kola", "Bali", "Donga", "Gashaka", "Gassol", "Ibi", "Jalingo", "Karim Lamido", "Kurmi",
                    "Lau", "Sardauna", "Takum", "Ussa", "Wukari", "Yorro", "Zing"
                ],
                "Yobe": ["Bade", "Bursari", "Damaturu", "Fika", "Fune", "Geidam", "Gujba", "Gulani", "Jakusko", "Karasuwa",
                    "Machina", "Nangere", "Nguru", "Potiskum", "Tarmuwa", "Yunusari", "Yusufari"
                ],
                "Zamfara": ["Anka", "Bakura", "Birnin Magaji/Kiyaw", "Bukkuyum", "Bungudu", "Gummi", "Gusau",
                    "Kaura Namoda", "Maradun", "Maru", "Shinkafi", "Talata Mafara", "Tsafe", "Zurmi"
                ]
            };

            // Populate states into the select dropdown
            const stateSelect = document.getElementById('state');
            if (stateSelect) {
                for (let state in lgas) {
                    let option = document.createElement('option');
                    option.value = state;
                    option.textContent = state;
                    stateSelect.appendChild(option);
                }

                // Set initial state value from old input
                const initialState = "{{ old('state') }}";
                if (initialState) {
                    stateSelect.value = initialState;
                    populateLGAs();
                }
            }

            function populateLGAs() {
                const lgaSelect = document.getElementById('local_government');
                const stateSelect = document.getElementById('state');

                if (!lgaSelect || !stateSelect) return;

                const selectedState = stateSelect.value;

                // Clear previous LGAs
                lgaSelect.innerHTML = '<option value="">-- Select Local Government --</option>';

                if (selectedState && lgas[selectedState]) {
                    lgas[selectedState].forEach(lga => {
                        const option = document.createElement('option');
                        option.value = lga;
                        option.textContent = lga;
                        lgaSelect.appendChild(option);
                    });
                }

                // Set initial LGA value from old input
                const initialLGA = "{{ old('local_government') }}";
                if (initialLGA) {
                    lgaSelect.value = initialLGA;
                }
            }
        </script>
    @endpush
