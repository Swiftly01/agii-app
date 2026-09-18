@extends('layout.layout')
@section('title', ' - Register')
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

                    <!-- Success/Error Messages -->
                    <div class="alert alert-success alert-dismissible fade show" id="successAlert" style="display: none;">
                        <span id="successMessage"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                    <div class="alert alert-danger alert-dismissible fade show" id="errorAlert" style="display: none;">
                        <span id="errorMessage"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                    <!-- Registration Form -->
                    <div class="form-step active" id="registerStep">
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
                            <!-- Step 1: Basic Information -->
                            <div class="form-step active" id="registerStep1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="first_name" class="form-label fw-semibold required-field">First
                                                Name</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name"
                                                placeholder="Enter your first name" required
                                                value="{{ old('first_name') }}">
                                            <div class="validation-message" id="firstNameError">Please enter your first name
                                            </div>
                                            @error('first_name')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="last_name" class="form-label fw-semibold required-field">Last
                                                Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name"
                                                placeholder="Enter your last name" required value="{{ old('last_name') }}">
                                            <div class="validation-message" id="lastNameError">Please enter your last name
                                            </div>
                                            @error('last_name')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold required-field">Email
                                        Address</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter your email address" required value="{{ old('email') }}">
                                    <div class="validation-message" id="registerEmailError">Please enter a valid email
                                        address</div>
                                    @error('email')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label fw-semibold required-field">Phone Number</label>
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
                                            <label for="password"
                                                class="form-label fw-semibold required-field">Password</label>
                                            <div class="password-input-group">
                                                <input type="password" class="form-control" id="password"
                                                    name="password" placeholder="Create a password" required>
                                                <button type="button" class="password-toggle"
                                                    id="toggleRegisterPassword">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            <div class="validation-message" id="registerPasswordError">Password must be at
                                                least 8 characters</div>
                                            @error('password')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password_confirmation"
                                                class="form-label fw-semibold required-field">Confirm Password</label>
                                            <div class="password-input-group">
                                                <input type="password" class="form-control" id="password_confirmation"
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
                                    <label for="referral_code" class="form-label fw-semibold">Referral Code
                                        (Optional)</label>
                                    <input type="text" class="form-control" id="referral_code" name="referral_code"
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
                                    <label for="city" class="form-label fw-semibold required-field">City</label>
                                    <input type="text" class="form-control" id="city" name="city"
                                        placeholder="Enter your city" required value="{{ old('city') }}">
                                    <div class="validation-message" id="cityError">Please enter your city</div>
                                    @error('city')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Hidden user_type field -->
                                <input type="hidden" id="user_type" name="user_type"
                                    value="{{ old('user_type', 'customer') }}">

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
                                            <li><i class="bi bi-check-circle"></i> List unlimited products & services</li>
                                            <li><i class="bi bi-check-circle"></i> Reach thousands of customers</li>
                                            <li><i class="bi bi-check-circle"></i> Secure payment processing</li>
                                            <li><i class="bi bi-check-circle"></i> Business analytics dashboard</li>
                                        </ul>
                                    </div>

                                    <div class="mb-3">
                                        <label for="business_name" class="form-label fw-semibold">Business Name
                                            (Optional)</label>
                                        <input type="text" class="form-control" id="business_name"
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
                                        <input type="hidden" id="business_type" name="business_type"
                                            value="{{ old('business_type') }}">
                                        <div class="validation-message" id="businessTypeError">Please select your business
                                            type</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="business_category" class="form-label fw-semibold">Business
                                            Category</label>
                                        <select class="form-select" id="business_category" name="business_category">
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
                                        <p class="text-muted small mb-3">Add your social media profiles to help customers
                                            connect with you</p>

                                        <div class="social-input-group">
                                            <div class="social-icon">
                                                <i class="bi bi-facebook"></i>
                                            </div>
                                            <input type="text" class="form-control social-input" id="facebook_url"
                                                name="facebook_url" placeholder="Facebook profile URL"
                                                value="{{ old('facebook_url') }}">
                                        </div>

                                        <div class="social-input-group">
                                            <div class="social-icon">
                                                <i class="bi bi-instagram"></i>
                                            </div>
                                            <input type="text" class="form-control social-input" id="instagram_url"
                                                name="instagram_url" placeholder="Instagram profile URL"
                                                value="{{ old('instagram_url') }}">
                                        </div>

                                        <div class="social-input-group">
                                            <div class="social-icon">
                                                <i class="bi bi-twitter"></i>
                                            </div>
                                            <input type="text" class="form-control social-input" id="twitter_url"
                                                name="twitter_url" placeholder="Twitter profile URL"
                                                value="{{ old('twitter_url') }}">
                                        </div>

                                        <div class="social-input-group">
                                            <div class="social-icon">
                                                <i class="bi bi-whatsapp"></i>
                                            </div>
                                            <input type="text" class="form-control social-input" id="whatsapp_number"
                                                name="whatsapp_number" placeholder="WhatsApp number"
                                                value="{{ old('whatsapp_number') }}">
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
                                        <input type="hidden" id="interests" name="interests"
                                            value="{{ old('interests') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms_accepted"
                                            name="terms_accepted" required {{ old('terms_accepted') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="terms_accepted">
                                            I agree to the <a href="#">Terms of Service</a> and <a
                                                href="#">Privacy Policy</a>
                                        </label>
                                        <div class="validation-message" id="termsError">You must agree to the terms and
                                            conditions</div>
                                        @error('terms_accepted')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="newsletter_subscribed"
                                            name="newsletter_subscribed"
                                            {{ old('newsletter_subscribed') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="newsletter_subscribed">
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
                        </form>

                        <div class="auth-switch">
                            <p class="mb-0">Already have an account? <a href="{{ route('login') }}">Sign in here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <!-- Your existing CSS styles here -->
    <style>
        /* Your existing CSS styles */
    </style>
@endpush

@push('scripts')
    <script>
        let currentUserType = 'customer';
        let currentRegisterStep = 1;
        let selectedBusinessType = '';
        let selectedInterests = [];

        document.addEventListener('DOMContentLoaded', function() {
            initializeUserTypeSelection();
            initializePasswordToggles();
            initializeFormValidation();
            initializeBusinessCategories();
            updateRegistrationForm();

            // Set initial user type from old input or default
            const initialUserType = "{{ old('user_type', 'customer') }}";
            if (initialUserType) {
                currentUserType = initialUserType;
                document.querySelector(`.user-type-card[data-type="${initialUserType}"]`).classList.add('selected');
                document.getElementById('user_type').value = initialUserType;
                updateRegistrationForm();
            }
        });

        function initializeUserTypeSelection() {
            document.querySelectorAll('.user-type-card').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.user-type-card').forEach(c => c.classList.remove(
                        'selected'));
                    this.classList.add('selected');

                    currentUserType = this.dataset.type;
                    document.getElementById('user_type').value = currentUserType;
                    updateRegistrationForm();
                });
            });
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

        function initializePasswordToggles() {
            // Register password toggles
            document.getElementById('toggleRegisterPassword').addEventListener('click', function() {
                const passwordInput = document.getElementById('password');
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
                const passwordInput = document.getElementById('password_confirmation');
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
            // Register form navigation
            document.getElementById('nextToStep2').addEventListener('click', function() {
                if (validateRegisterStep1()) {
                    nextRegisterStep(2);
                }
            });

            // Form submission handled by Laravel
        }

        function initializeBusinessCategories() {
            // Business type selection
            document.querySelectorAll('.business-category[data-category]').forEach(category => {
                category.addEventListener('click', function() {
                    document.querySelectorAll('.business-category[data-category]').forEach(c => c.classList
                        .remove('selected'));
                    this.classList.add('selected');
                    selectedBusinessType = this.dataset.category;
                    document.getElementById('business_type').value = selectedBusinessType;
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

                    document.getElementById('interests').value = selectedInterests.join(',');
                });
            });

            // Set initial values from old input
            const initialBusinessType = "{{ old('business_type') }}";
            if (initialBusinessType) {
                selectedBusinessType = initialBusinessType;
                document.querySelector(`.business-category[data-category="${initialBusinessType}"]`).classList.add(
                    'selected');
                document.getElementById('business_type').value = initialBusinessType;
            }

            const initialInterests = "{{ old('interests') }}";
            if (initialInterests) {
                selectedInterests = initialInterests.split(',');
                selectedInterests.forEach(interest => {
                    const element = document.querySelector(`.business-category[data-interest="${interest}"]`);
                    if (element) {
                        element.classList.add('selected');
                    }
                });
                document.getElementById('interests').value = initialInterests;
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

        function validateRegisterStep1() {
            let isValid = true;
            const fields = ['first_name', 'last_name', 'email', 'phone', 'password', 'password_confirmation', 'state',
                'local_government', 'city'
            ];

            fields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                const errorId = `${fieldId}Error`;

                if (!field.value) {
                    showError(field, errorId, `Please enter your ${fieldId.replace(/_/g, ' ')}`);
                    isValid = false;
                } else {
                    hideError(field, errorId);
                }
            });

            // Validate email format
            const email = document.getElementById('email');
            if (email.value && !isValidEmail(email.value)) {
                showError(email, 'registerEmailError', 'Please enter a valid email address');
                isValid = false;
            }

            // Validate password match
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');
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

        // Nigerian States and LGAs data
        const lgas = {
            // Your existing LGAs data here
            "Abia": ["Aba North", "Aba South", "Arochukwu", "Bende", "Ikwuano", "Isiala Ngwa North",
                "Isiala Ngwa South", "Isuikwuato", "Obi Ngwa", "Ohafia", "Osisioma", "Ugwunagbo", "Ukwa East",
                "Ukwa West", "Umuahia North", "Umuahia South", "Umu Nneochi"
            ],
            // ... include all other states and LGAs
        };

        // Populate states into the select dropdown
        const stateSelect = document.getElementById('state');
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

        function populateLGAs() {
            const lgaSelect = document.getElementById('local_government');
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
