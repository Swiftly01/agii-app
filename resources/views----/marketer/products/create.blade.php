@extends('layout.layout')
@section('title', 'Sell on Agii - List Your Product')
@section('content')

    <section class="py-5">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="text-center mb-5">
                        <h1 class="display-5 fw-bold mb-3">Sell on Agii</h1>
                        <p class="lead text-muted">List your product and reach thousands of potential buyers</p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress mb-4">
                        <div class="progress-bar" id="formProgress" role="progressbar" style="width: 25%" aria-valuenow="25"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <!-- Step Indicator -->
                    <div class="step-indicator mb-5">
                        <div class="step active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Category</div>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">Details</div>
                        </div>
                        <div class="step" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Media</div>
                        </div>
                        <div class="step" data-step="4">
                            <div class="step-number">4</div>
                            <div class="step-label">Review</div>
                        </div>
                    </div>

                    <form id="productForm" class="sell-form-container" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                        <!-- Step 1: Category Selection -->
                        <div class="form-step active" id="step1">
                            <h3 class="section-title">Choose Product Category</h3>
                            <p class="text-muted mb-4">Select the category that best matches your product</p>

                            <div class="row g-4" id="categoryOptions">
                                @foreach ($categories as $category)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="category-card" data-category="{{ $category->id }}">
                                            <div class="category-icon">
                                                <i class="bi bi-{{ $category->icon ?? 'box' }}"></i>
                                            </div>
                                            <h5>{{ $category->name }}</h5>
                                            <p class="text-muted small">{{ $category->description }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Subcategory Selection -->
                            <div id="subcategorySection" class="mt-4" style="display: none;">
                                <h5 class="mb-3">Select specific type:</h5>
                                <div id="subcategoryOptions" class="row g-3">
                                    <!-- Subcategories will be loaded here dynamically -->
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-5">
                                <div></div>
                                <button type="button" class="btn btn-primary" id="nextToStep2" disabled>Next:
                                    Details</button>
                            </div>
                        </div>

                        <!-- Step 2: Product Details -->
                        <div class="form-step" id="step2">
                            <h3 class="section-title">Product Details</h3>

                            <div class="product-details">
                                <!-- Dynamic Specification Fields -->
                                <div id="dynamicFields">
                                    <!-- Specification fields will be loaded here based on category -->
                                </div>

                                <!-- Common Product Fields -->
                                <div class="row mt-4">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="productTitle" class="form-label fw-semibold required-field">Product
                                                Title</label>
                                            <input type="text" class="form-control" id="productTitle" name="title"
                                                placeholder="e.g., iPhone 13 Pro Max 256GB - Excellent Condition" required>
                                            <div class="validation-message" id="titleError">Please enter a product title
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="productPrice" class="form-label fw-semibold required-field">Price
                                                (₦)</label>
                                            <input type="number" class="form-control" id="productPrice" name="price"
                                                placeholder="e.g., 450000" required min="0" step="0.01">
                                            <div class="validation-message" id="priceError">Please enter a valid price</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="oldPrice" class="form-label fw-semibold">Old Price (₦)</label>
                                            <input type="number" class="form-control" id="oldPrice" name="old_price"
                                                placeholder="e.g., 500000" min="0" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label fw-semibold required-field">Quantity
                                                Available</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity"
                                                value="1" required min="1">
                                            <div class="validation-message" id="quantityError">Please enter quantity</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="productDescription" class="form-label fw-semibold required-field">Product
                                        Description</label>
                                    <textarea class="form-control" id="productDescription" name="description" rows="5"
                                        placeholder="Describe your product in detail. Include condition, features, and any relevant information..."
                                        required></textarea>
                                    <div class="validation-message" id="descriptionError">Please enter a product
                                        description</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="productCondition"
                                                class="form-label fw-semibold required-field">Condition</label>
                                            <select class="form-select" id="productCondition" name="condition" required>
                                                <option value="">Select condition</option>
                                                <option value="new">Brand New</option>
                                                <option value="used_like_new">Used - Like New</option>
                                                <option value="used_good">Used - Good</option>
                                                <option value="used_fair">Used - Fair</option>
                                            </select>
                                            <div class="validation-message" id="conditionError">Please select a condition
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="productLocation"
                                                class="form-label fw-semibold required-field">Location</label>
                                            <input type="text" class="form-control" id="productLocation"
                                                name="location" placeholder="e.g., Lekki Phase 1, Lagos" required>
                                            <div class="validation-message" id="locationError">Please enter a location
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="negotiable"
                                            name="negotiable" value="1">
                                        <label class="form-check-label" for="negotiable">
                                            Price is negotiable
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-5">
                                <button type="button" class="btn btn-outline-primary"
                                    onclick="prevStep(1)">Previous</button>
                                <button type="button" class="btn btn-primary" id="nextToStep3">Next: Media</button>
                            </div>
                        </div>

                        <!-- Step 3: Media Upload -->
                        <div class="form-step" id="step3">
                            <h3 class="section-title">Add Photos</h3>
                            <p class="text-muted mb-4">Upload clear photos of your product. First image will be the cover
                                photo.</p>

                            <div class="dropzone-container">
                                <input type="file" id="imageUpload" name="images[]" multiple accept="image/*"
                                    style="display: none;">
                                <div class="dropzone" onclick="document.getElementById('imageUpload').click()">
                                    <div class="dz-message">
                                        <i class="bi bi-cloud-upload" style="font-size: 3rem;"></i>
                                        <h5>Drop images here or click to upload</h5>
                                        <p class="text-muted">Upload up to 10 photos (max 5MB each)</p>
                                    </div>
                                </div>
                            </div>

                            <div class="image-preview mt-4" id="imagePreview">
                                <!-- Image previews will be shown here -->
                            </div>

                            <div class="d-flex justify-content-between mt-5">
                                <button type="button" class="btn btn-outline-primary"
                                    onclick="prevStep(2)">Previous</button>
                                <button type="button" class="btn btn-primary" id="nextToStep4">Next: Review</button>
                            </div>
                        </div>

                        <!-- Step 4: Review & Submit -->
                        <div class="form-step" id="step4">
                            <h3 class="section-title">Review Your Product</h3>

                            <div class="category-preview">
                                <h5 id="reviewCategory">Category: Electronics > Phones</h5>
                                <h3 id="reviewTitle" class="mt-2">iPhone 13 Pro Max 256GB</h3>
                                <h4 class="text-primary mt-2" id="reviewPrice">₦450,000</h4>
                                <div id="reviewOldPrice" class="text-muted"></div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h5>Specifications</h5>
                                    <div id="reviewSpecifications">
                                        <!-- Specifications will be shown here -->
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>Details</h5>
                                    <div id="reviewDetails">
                                        <!-- Details will be shown here -->
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <h5>Description</h5>
                                <p id="reviewDescription" class="text-muted"></p>
                            </div>

                            <div class="mt-4">
                                <h5>Location</h5>
                                <p id="reviewLocation" class="text-muted"></p>
                            </div>

                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                <label class="form-check-label" for="agreeTerms">
                                    I agree to the <a href="#">Terms of Service</a> and confirm that this product is
                                    legal to sell in Nigeria
                                </label>
                                <div class="validation-message" id="termsError">You must agree to the terms and conditions
                                </div>
                            </div>

                            <div class="success-message mt-4" id="successMessage" style="display: none;">
                                <h5>Product Published Successfully!</h5>
                                <p>Your product has been listed and is now visible to potential buyers.</p>
                            </div>

                            <div class="d-flex justify-content-between mt-5">
                                <button type="button" class="btn btn-outline-primary"
                                    onclick="prevStep(3)">Previous</button>
                                <button type="submit" class="btn btn-success" id="submitProduct">Publish
                                    Product</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('styles')
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

        .sell-form-container {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            margin: 20px 0;
            transition: var(--transition);
        }

        .sell-form-container:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .form-section {
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 1px solid var(--border-color);
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

        .dynamic-field {
            transition: var(--transition);
        }

        .category-preview {
            background: var(--primary-light);
            border-radius: 12px;
            padding: 20px;
            margin: 15px 0;
            border-left: 4px solid var(--accent-color);
        }

        .dropzone {
            border: 2px dashed var(--border-color) !important;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            background: var(--light-grey-color);
            transition: var(--transition);
            cursor: pointer;
        }

        .dropzone:hover {
            border-color: var(--accent-color) !important;
            background: var(--primary-light);
        }

        .dropzone.dz-drag-hover {
            border-color: var(--accent-color) !important;
            background: var(--primary-light);
            transform: scale(1.02);
        }

        .image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }

        .preview-item {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }

        .preview-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: bold;
            color: var(--danger-color);
            transition: var(--transition);
        }

        .remove-image:hover {
            background: var(--danger-color);
            color: white;
            transform: scale(1.1);
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
            margin-bottom: 40px;
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
            width: 35px;
            height: 35px;
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
        }

        .step.active .step-label {
            color: var(--accent-color);
        }

        .category-card {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            background: white;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .category-card:hover {
            border-color: var(--accent-color);
            transform: translateY(-5px);
            box-shadow: var(--shadow);
        }

        .category-card.selected {
            border-color: var(--accent-color);
            background: var(--primary-light);
            box-shadow: 0 5px 15px rgba(143, 199, 74, 0.15);
            transform: translateY(-3px);
        }

        .category-card.selected::after {
            content: '✓';
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--accent-color);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
        }

        .category-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--accent-color);
            transition: var(--transition);
        }

        .category-card.selected .category-icon {
            color: var(--primary-color);
            transform: scale(1.1);
        }

        .specification-group {
            background: var(--light-grey-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid var(--accent-color);
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 10px 15px;
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
            padding: 10px 20px;
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

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-success:hover {
            background-color: #7ab436;
            border-color: #7ab436;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .subcategory {
            padding: 15px;
            min-height: auto;
        }

        .subcategory h6 {
            margin: 0;
        }

        .progress {
            height: 8px;
            margin-bottom: 30px;
            border-radius: 10px;
        }

        .progress-bar {
            background-color: var(--accent-color);
            border-radius: 10px;
            transition: width 0.5s ease;
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

        .custom-category-input {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            background-color: var(--light-grey-color);
            border-left: 4px solid var(--accent-color);
        }

        .other-category-card {
            border: 2px dashed var(--border-color);
        }

        .other-category-card:hover,
        .other-category-card.selected {
            border-style: solid;
        }

        .service-details {
            background: var(--light-grey-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .product-details {
            background: var(--light-grey-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .listing-type-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--accent-color);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .listing-type-selector {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            justify-content: center;
        }

        .listing-type-card {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            background: white;
            flex: 1;
            max-width: 200px;
        }

        .listing-type-card:hover {
            border-color: var(--accent-color);
            transform: translateY(-3px);
        }

        .listing-type-card.selected {
            border-color: var(--accent-color);
            background: var(--primary-light);
            box-shadow: 0 5px 15px rgba(143, 199, 74, 0.15);
        }

        .listing-type-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: var(--accent-color);
        }

        .dz-preview {
            display: none !important;
        }

        @media (max-width: 768px) {
            .sell-form-container {
                padding: 20px;
            }

            .step-label {
                font-size: 0.8rem;
            }

            .category-card {
                padding: 15px;
            }

            .listing-type-selector {
                flex-direction: column;
                align-items: center;
            }

            .listing-type-card {
                max-width: 100%;
                width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // JavaScript functionality
        let selectedCategory = null;
        let selectedSubcategory = null;
        let uploadedImages = [];
        let specificationFields = {};

        // Step navigation
        function nextStep(step) {
            if (step > 1 && !validateStep(step - 1)) {
                return;
            }

            document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
            document.getElementById(`step${step}`).classList.add('active');

            document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
            document.querySelector(`.step[data-step="${step}"]`).classList.add('active');

            for (let i = 1; i < step; i++) {
                document.querySelector(`.step[data-step="${i}"]`).classList.add('completed');
            }

            document.getElementById('formProgress').style.width = `${(step / 4) * 100}%`;

            if (step === 2) {
                loadDynamicFields();
            } else if (step === 4) {
                updateReviewSection();
            }
        }

        function prevStep(step) {
            nextStep(step);
            document.getElementById('formProgress').style.width = `${(step / 4) * 100}%`;
        }

        // Form validation
        function validateStep(step) {
            let isValid = true;

            if (step === 1) {
                if (!selectedCategory) {
                    alert('Please select a category');
                    isValid = false;
                }
            } else if (step === 2) {
                // Validate required fields
                const requiredFields = [
                    'productTitle', 'productPrice', 'productDescription',
                    'productCondition', 'productLocation', 'quantity'
                ];

                requiredFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (!validateField(field)) {
                        isValid = false;
                    }
                });

                // Validate dynamic specification fields
                Object.entries(specificationFields).forEach(([key, field]) => {
                    if (field.required) {
                        const fieldElement = document.getElementById(`spec_${key}`);
                        if (!validateField(fieldElement)) {
                            isValid = false;
                        }
                    }
                });
            } else if (step === 3) {
                if (uploadedImages.length === 0) {
                    alert('Please upload at least one image');
                    isValid = false;
                }
            }

            return isValid;
        }

        function validateField(field) {
            if (!field) return true;

            const errorElement = document.getElementById(`${field.id}Error`);

            if (field.hasAttribute('required') && (!field.value || field.value.trim() === '')) {
                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
                if (errorElement) errorElement.style.display = 'block';
                return false;
            } else if (field.type === 'number' && field.hasAttribute('required') && (field.value <= 0 || field.value ===
                    '')) {
                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
                if (errorElement) errorElement.style.display = 'block';
                return false;
            } else {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
                if (errorElement) errorElement.style.display = 'none';
                return true;
            }
        }

        // Category selection
        document.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.category-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');

                selectedCategory = this.dataset.category;
                document.getElementById('nextToStep2').disabled = false;

                // Load subcategories
                loadSubcategories(selectedCategory);
            });
        });

        function loadSubcategories(categoryId) {
            fetch(`/categories/${categoryId}/subcategories`)
                .then(response => response.json())
                .then(data => {
                    const subcategorySection = document.getElementById('subcategorySection');
                    const subcategoryOptions = document.getElementById('subcategoryOptions');

                    if (data.success && data.subcategories.length > 0) {
                        subcategorySection.style.display = 'block';
                        subcategoryOptions.innerHTML = '';

                        data.subcategories.forEach(subcategory => {
                            const col = document.createElement('div');
                            col.className = 'col-md-4 col-sm-6';
                            col.innerHTML = `
                            <div class="category-card subcategory" data-subcategory="${subcategory.id}">
                                <h6>${subcategory.name}</h6>
                            </div>
                        `;
                            subcategoryOptions.appendChild(col);
                        });

                        // Add subcategory selection
                        document.querySelectorAll('.subcategory').forEach(card => {
                            card.addEventListener('click', function() {
                                document.querySelectorAll('.subcategory').forEach(c => c.classList
                                    .remove('selected'));
                                this.classList.add('selected');
                                selectedSubcategory = this.dataset.subcategory;
                            });
                        });
                    } else {
                        subcategorySection.style.display = 'none';
                    }

                    // Store specification fields
                    specificationFields = data.specification_fields || {};
                })
                .catch(error => {
                    console.error('Error loading subcategories:', error);
                });
        }

        function loadDynamicFields() {
            const dynamicFields = document.getElementById('dynamicFields');
            dynamicFields.innerHTML = '';

            if (Object.keys(specificationFields).length > 0) {
                let fieldsHTML = '<div class="specification-group"><h5>Specifications</h5><div class="row">';

                Object.entries(specificationFields).forEach(([key, field]) => {
                    const colClass = field.type === 'textarea' ? 'col-12' : 'col-md-6';
                    const requiredClass = field.required ? 'required-field' : '';

                    if (field.type === 'select') {
                        fieldsHTML += `
                        <div class="${colClass} mb-3">
                            <label for="spec_${key}" class="form-label ${requiredClass}">${field.label}</label>
                            <select class="form-select" id="spec_${key}" name="specifications[${key}]" ${field.required ? 'required' : ''}>
                                <option value="">Select ${field.label}</option>
                                ${field.options.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
                            </select>
                            <div class="validation-message" id="spec_${key}Error">Please select ${field.label.toLowerCase()}</div>
                        </div>
                    `;
                    } else if (field.type === 'textarea') {
                        fieldsHTML += `
                        <div class="${colClass} mb-3">
                            <label for="spec_${key}" class="form-label ${requiredClass}">${field.label}</label>
                            <textarea class="form-control" id="spec_${key}" name="specifications[${key}]" rows="3"
                                      placeholder="${field.placeholder || 'Enter ' + field.label.toLowerCase()}" ${field.required ? 'required' : ''}></textarea>
                            <div class="validation-message" id="spec_${key}Error">Please enter ${field.label.toLowerCase()}</div>
                        </div>
                    `;
                    } else {
                        fieldsHTML += `
                        <div class="${colClass} mb-3">
                            <label for="spec_${key}" class="form-label ${requiredClass}">${field.label}</label>
                            <input type="${field.type}" class="form-control" id="spec_${key}" name="specifications[${key}]"
                                   placeholder="${field.placeholder || 'Enter ' + field.label.toLowerCase()}" ${field.required ? 'required' : ''}>
                            <div class="validation-message" id="spec_${key}Error">Please enter ${field.label.toLowerCase()}</div>
                        </div>
                    `;
                    }
                });

                fieldsHTML += '</div></div>';
                dynamicFields.innerHTML = fieldsHTML;

                // Add validation for dynamic fields
                Object.keys(specificationFields).forEach(key => {
                    const field = document.getElementById(`spec_${key}`);
                    if (field) {
                        field.addEventListener('blur', function() {
                            validateField(this);
                        });
                    }
                });
            }
        }

        // Image handling
        document.getElementById('imageUpload').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);

            files.forEach(file => {
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size too large: ' + file.name);
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    uploadedImages.push({
                        id: Date.now() + Math.random(),
                        name: file.name,
                        dataUrl: e.target.result,
                        file: file
                    });
                    updateImagePreview();
                };
                reader.readAsDataURL(file);
            });

            // Reset input to allow uploading same files again
            this.value = '';
        });

        function updateImagePreview() {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            if (uploadedImages.length === 0) {
                preview.innerHTML = '<p class="text-muted">No images uploaded yet</p>';
                return;
            }

            uploadedImages.forEach((image, index) => {
                const previewItem = document.createElement('div');
                previewItem.className = 'preview-item';
                previewItem.innerHTML = `
                <img src="${image.dataUrl}" alt="Preview ${index + 1}">
                <button type="button" class="remove-image" data-index="${index}">×</button>
            `;
                preview.appendChild(previewItem);
            });

            document.querySelectorAll('.remove-image').forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    uploadedImages.splice(index, 1);
                    updateImagePreview();
                });
            });
        }

        function updateReviewSection() {
            // Update review section with form data
            const categoryElement = document.querySelector('.category-card.selected h5');
            const categoryName = categoryElement ? categoryElement.textContent : 'Selected Category';

            document.getElementById('reviewCategory').textContent = `Category: ${categoryName}`;
            document.getElementById('reviewTitle').textContent = document.getElementById('productTitle').value;

            const price = document.getElementById('productPrice').value;
            document.getElementById('reviewPrice').textContent = `₦${parseInt(price).toLocaleString()}`;

            const oldPrice = document.getElementById('oldPrice').value;
            if (oldPrice) {
                document.getElementById('reviewOldPrice').innerHTML = `<del>₦${parseInt(oldPrice).toLocaleString()}</del>`;
            }

            document.getElementById('reviewDescription').textContent = document.getElementById('productDescription').value;
            document.getElementById('reviewLocation').textContent = document.getElementById('productLocation').value;

            // Update specifications
            const specsContainer = document.getElementById('reviewSpecifications');
            specsContainer.innerHTML = '';

            Object.entries(specificationFields).forEach(([key, field]) => {
                const value = document.getElementById(`spec_${key}`)?.value;
                if (value) {
                    const specItem = document.createElement('div');
                    specItem.className = 'mb-2';
                    specItem.innerHTML = `<strong>${field.label}:</strong> ${value}`;
                    specsContainer.appendChild(specItem);
                }
            });

            // Update details
            const detailsContainer = document.getElementById('reviewDetails');
            detailsContainer.innerHTML = '';

            const condition = document.getElementById('productCondition');
            if (condition.value) {
                const conditionItem = document.createElement('div');
                conditionItem.className = 'mb-2';
                conditionItem.innerHTML = `<strong>Condition:</strong> ${condition.options[condition.selectedIndex].text}`;
                detailsContainer.appendChild(conditionItem);
            }

            const quantity = document.getElementById('quantity');
            if (quantity.value) {
                const quantityItem = document.createElement('div');
                quantityItem.className = 'mb-2';
                quantityItem.innerHTML = `<strong>Quantity:</strong> ${quantity.value}`;
                detailsContainer.appendChild(quantityItem);
            }

            const negotiable = document.getElementById('negotiable');
            if (negotiable.checked) {
                const negotiableItem = document.createElement('div');
                negotiableItem.className = 'mb-2';
                negotiableItem.innerHTML = `<strong>Price Negotiable:</strong> Yes`;
                detailsContainer.appendChild(negotiableItem);
            }

            // Add images count
            const imagesItem = document.createElement('div');
            imagesItem.className = 'mb-2';
            imagesItem.innerHTML = `<strong>Images:</strong> ${uploadedImages.length} uploaded`;
            detailsContainer.appendChild(imagesItem);
        }

        // Form submission
        // In your JavaScript, update the form submission part:
        // Form submission
        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate terms agreement
            const termsCheckbox = document.getElementById('agreeTerms');
            if (!termsCheckbox.checked) {
                document.getElementById('termsError').style.display = 'block';
                termsCheckbox.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return;
            } else {
                document.getElementById('termsError').style.display = 'none';
            }

            if (!selectedCategory) {
                alert('Please complete all required fields');
                return;
            }

            const formData = new FormData();

            // Add all form fields
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('category_id', selectedCategory);
            formData.append('title', document.getElementById('productTitle').value);
            formData.append('price', document.getElementById('productPrice').value);
            formData.append('old_price', document.getElementById('oldPrice').value);
            formData.append('description', document.getElementById('productDescription').value);
            formData.append('condition', document.getElementById('productCondition').value);
            formData.append('location', document.getElementById('productLocation').value);
            formData.append('quantity', document.getElementById('quantity').value);
            formData.append('negotiable', document.getElementById('negotiable').checked ? '1' : '0');

            // Add specifications
            Object.keys(specificationFields).forEach(key => {
                const fieldElement = document.getElementById(`spec_${key}`);
                if (fieldElement && fieldElement.value) {
                    formData.append(`specifications[${key}]`, fieldElement.value);
                }
            });

            // Add images
            uploadedImages.forEach((image, index) => {
                formData.append(`images[${index}]`, image.file);
            });

            // Submit form
            const submitBtn = document.getElementById('submitProduct');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Publishing...';

            fetch('{{ route('marketer.products.store', $vendor) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    // First, check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // If not JSON, get the text and try to parse it
                        return response.text().then(text => {
                            throw new Error('Server returned non-JSON response: ' + text.substring(0,
                                100));
                        });
                    }
                })
                .then(data => {
                    if (data.success) {
                        document.getElementById('successMessage').style.display = 'block';
                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 2000);
                    } else {
                        // Handle validation errors
                        let errorMessage = data.message || 'Unknown error occurred';

                        if (data.errors) {
                            // Format validation errors
                            const errorMessages = [];
                            for (const field in data.errors) {
                                errorMessages.push(data.errors[field].join(', '));
                            }
                            errorMessage = errorMessages.join('\n');
                        }

                        alert('Error: ' + errorMessage);
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Publish Product';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while publishing your product. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Publish Product';
                });
        });


        // Initialize event listeners
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('nextToStep2').addEventListener('click', () => nextStep(2));
            document.getElementById('nextToStep3').addEventListener('click', () => nextStep(3));
            document.getElementById('nextToStep4').addEventListener('click', () => nextStep(4));

            // Add validation for common fields
            const commonFields = [
                'productTitle', 'productPrice', 'productDescription',
                'productCondition', 'productLocation', 'quantity'
            ];

            commonFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('blur', function() {
                        validateField(this);
                    });
                }
            });

            updateImagePreview();
        });
    </script>
@endpush
