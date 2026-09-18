<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sell on Agii - List Your Product/Service</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/dropzone.min.css">

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
</head>

<body>
    <!-- Header Section -->
    <header>
        <!-- Your header code here -->
    </header>

    <!-- Sell Form Section -->
    <section class="py-5">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="text-center mb-5">
                        <h1 class="display-5 fw-bold mb-3">Sell on Agii</h1>
                        <p class="lead text-muted">List your product or service and reach thousands of potential buyers
                        </p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress">
                        <div class="progress-bar" id="formProgress" role="progressbar" style="width: 25%"
                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <!-- Step Indicator -->
                    <div class="step-indicator">
                        <div class="step active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Type & Category</div>
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

                    <form id="sellForm" class="sell-form-container">
                        <!-- Step 1: Listing Type & Category Selection -->
                        <div class="form-step active" id="step1">
                            <h3 class="section-title">What are you selling?</h3>
                            <p class="text-muted mb-4">First, choose what type of listing you want to create</p>

                            <!-- Listing Type Selection -->
                            <div class="listing-type-selector">
                                <div class="listing-type-card" data-type="product">
                                    <div class="listing-type-icon">
                                        <iconify-icon icon="mdi:package-variant"></iconify-icon>
                                    </div>
                                    <h5>Product</h5>
                                    <p class="text-muted small">Physical items you want to sell</p>
                                </div>
                                <div class="listing-type-card" data-type="service">
                                    <div class="listing-type-icon">
                                        <iconify-icon icon="mdi:tools"></iconify-icon>
                                    </div>
                                    <h5>Service</h5>
                                    <p class="text-muted small">Skills or services you offer</p>
                                </div>
                            </div>

                            <div id="categorySection" style="display: none;">
                                <h4 class="section-title mt-5">Choose a Category</h4>
                                <p class="text-muted mb-4">Select the category that best matches your item</p>

                                <div class="row g-4" id="categoryOptions">
                                    <!-- Categories will be loaded dynamically based on type -->
                                </div>

                                <!-- Custom Category Input -->
                                <div id="customCategorySection" class="custom-category-input" style="display: none;">
                                    <h5 class="mb-3">Specify Your Category</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="customCategory"
                                                    class="form-label fw-semibold required-field">Category Name</label>
                                                <input type="text" class="form-control" id="customCategory"
                                                    placeholder="e.g., Art Supplies, Musical Instruments, etc.">
                                                <div class="validation-message" id="customCategoryError">Please enter a
                                                    category name</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="customSubcategory"
                                                    class="form-label fw-semibold">Subcategory (Optional)</label>
                                                <input type="text" class="form-control" id="customSubcategory"
                                                    placeholder="e.g., Paint Brushes, Guitars, etc.">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Subcategory Selection (Dynamic) -->
                                <div id="subcategorySection" class="mt-4" style="display: none;">
                                    <h5 class="mb-3">Select specific type:</h5>
                                    <div id="subcategoryOptions" class="row g-3">
                                        <!-- Subcategories will be loaded here dynamically -->
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-5">
                                <div></div> <!-- Empty div for spacing -->
                                <button type="button" class="btn btn-primary" id="nextToStep2" disabled>Next:
                                    Details</button>
                            </div>
                        </div>

                        <!-- Step 2: Product/Service Details -->
                        <div class="form-step" id="step2">
                            <h3 class="section-title" id="detailsTitle">Product Details</h3>

                            <!-- Product Details Section -->
                            <div id="productSection">
                                <div class="product-details">
                                    <h5>Product Information</h5>

                                    <!-- Dynamic Fields Container -->
                                    <div id="dynamicFields">
                                        <!-- Fields will be loaded here based on category -->
                                    </div>

                                    <!-- Common Product Fields -->
                                    <div class="row mt-4">
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label for="productTitle"
                                                    class="form-label fw-semibold required-field">Product Title</label>
                                                <input type="text" class="form-control" id="productTitle"
                                                    placeholder="e.g., iPhone 13 Pro Max 256GB - Excellent Condition"
                                                    required>
                                                <div class="validation-message" id="titleError">Please enter a product
                                                    title</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="productPrice"
                                                    class="form-label fw-semibold required-field">Price (₦)</label>
                                                <input type="number" class="form-control" id="productPrice"
                                                    placeholder="e.g., 450000" required min="0">
                                                <div class="validation-message" id="priceError">Please enter a valid
                                                    price</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="productDescription"
                                            class="form-label fw-semibold required-field">Product Description</label>
                                        <textarea class="form-control" id="productDescription" rows="5"
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
                                                <select class="form-select" id="productCondition" required>
                                                    <option value="">Select condition</option>
                                                    <option value="new">Brand New</option>
                                                    <option value="used_like_new">Used - Like New</option>
                                                    <option value="used_good">Used - Good</option>
                                                    <option value="used_fair">Used - Fair</option>
                                                </select>
                                                <div class="validation-message" id="conditionError">Please select a
                                                    condition</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="productLocation"
                                                    class="form-label fw-semibold required-field">Location</label>
                                                <input type="text" class="form-control" id="productLocation"
                                                    placeholder="e.g., Lekki Phase 1, Lagos" required>
                                                <div class="validation-message" id="locationError">Please enter a
                                                    location</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Service Details Section -->
                            <div id="serviceSection" style="display: none;">
                                <div class="service-details">
                                    <h5>Service Information</h5>

                                    <!-- Service Specific Fields -->
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label for="serviceTitle"
                                                    class="form-label fw-semibold required-field">Service Title</label>
                                                <input type="text" class="form-control" id="serviceTitle"
                                                    placeholder="e.g., Professional Home Cleaning Service" required>
                                                <div class="validation-message" id="serviceTitleError">Please enter a
                                                    service title</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="servicePrice"
                                                    class="form-label fw-semibold required-field">Price (₦)</label>
                                                <input type="number" class="form-control" id="servicePrice"
                                                    placeholder="e.g., 15000" required min="0">
                                                <div class="validation-message" id="servicePriceError">Please enter a
                                                    valid price</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="serviceDescription"
                                            class="form-label fw-semibold required-field">Service Description</label>
                                        <textarea class="form-control" id="serviceDescription" rows="5"
                                            placeholder="Describe your service in detail. Include what you offer, your expertise, and any relevant information..."
                                            required></textarea>
                                        <div class="validation-message" id="serviceDescriptionError">Please enter a
                                            service description</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="serviceType"
                                                    class="form-label fw-semibold required-field">Service Type</label>
                                                <select class="form-select" id="serviceType" required>
                                                    <option value="">Select service type</option>
                                                    <option value="one_time">One-time Service</option>
                                                    <option value="recurring">Recurring Service</option>
                                                    <option value="contract">Contract-based</option>
                                                    <option value="consultation">Consultation</option>
                                                </select>
                                                <div class="validation-message" id="serviceTypeError">Please select a
                                                    service type</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="serviceDuration"
                                                    class="form-label fw-semibold">Duration</label>
                                                <input type="text" class="form-control" id="serviceDuration"
                                                    placeholder="e.g., 2 hours, 1 day, etc.">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="serviceLocation"
                                                    class="form-label fw-semibold required-field">Service
                                                    Location</label>
                                                <input type="text" class="form-control" id="serviceLocation"
                                                    placeholder="e.g., Lekki Phase 1, Lagos or Remote" required>
                                                <div class="validation-message" id="serviceLocationError">Please enter
                                                    a service location</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="serviceAvailability"
                                                    class="form-label fw-semibold">Availability</label>
                                                <select class="form-select" id="serviceAvailability">
                                                    <option value="">Select availability</option>
                                                    <option value="weekdays">Weekdays Only</option>
                                                    <option value="weekends">Weekends Only</option>
                                                    <option value="flexible">Flexible</option>
                                                    <option value="24_7">24/7 Available</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Service-specific dynamic fields -->
                                    <div id="serviceDynamicFields">
                                        <!-- Service-specific fields will be loaded here -->
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
                            <p class="text-muted mb-4">Upload clear photos of your item. First image will be the cover
                                photo.</p>

                            <div class="dropzone" id="imageUpload">
                                <div class="dz-message">
                                    <iconify-icon icon="mdi:cloud-upload" width="48"
                                        class="text-muted mb-3"></iconify-icon>
                                    <h5>Drop images here or click to upload</h5>
                                    <p class="text-muted">Upload up to 10 photos (max 5MB each)</p>
                                </div>
                            </div>

                            <div class="image-preview" id="imagePreview">
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
                            <h3 class="section-title">Review Your Listing</h3>

                            <div class="category-preview">
                                <h5 id="reviewCategory">Category: Electronics > Phones</h5>
                                <h3 id="reviewTitle" class="mt-2">iPhone 13 Pro Max 256GB</h3>
                                <h4 class="text-primary mt-2" id="reviewPrice">₦450,000</h4>
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
                                    I agree to the <a href="#">Terms of Service</a> and confirm that this item is
                                    legal to sell in Nigeria
                                </label>
                                <div class="validation-message" id="termsError">You must agree to the terms and
                                    conditions</div>
                            </div>

                            <div class="success-message" id="successMessage">
                                <h5>Listing Published Successfully!</h5>
                                <p>Your item has been listed and is now visible to potential buyers.</p>
                            </div>

                            <div class="d-flex justify-content-between mt-5">
                                <button type="button" class="btn btn-outline-primary"
                                    onclick="prevStep(3)">Previous</button>
                                <button type="submit" class="btn btn-success" id="submitListing">Publish
                                    Listing</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <!-- Your footer code here -->
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/dropzone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.7/dist/iconify-icon.min.js"></script>

    <script>
        // Category configurations
        const categories = {
            product: {
                electronics: {
                    name: "Electronics",
                    subcategories: {
                        phones: "Mobile Phones",
                        laptops: "Laptops & Computers",
                        tablets: "Tablets",
                        cameras: "Cameras",
                        audio: "Audio Equipment",
                        gaming: "Gaming Consoles",
                        accessories: "Accessories",
                        other: "Other Electronics"
                    },
                    fields: {
                        brand: {
                            type: "text",
                            label: "Brand",
                            required: true
                        },
                        model: {
                            type: "text",
                            label: "Model",
                            required: true
                        },
                        storage: {
                            type: "select",
                            label: "Storage",
                            options: ["16GB", "32GB", "64GB", "128GB", "256GB", "512GB", "1TB"]
                        },
                        ram: {
                            type: "select",
                            label: "RAM",
                            options: ["2GB", "4GB", "6GB", "8GB", "12GB", "16GB", "32GB"]
                        },
                        color: {
                            type: "text",
                            label: "Color"
                        },
                        warranty: {
                            type: "select",
                            label: "Warranty",
                            options: ["No Warranty", "1 Month", "3 Months", "6 Months", "1 Year", "2 Years"]
                        }
                    }
                },
                gadgets: {
                    name: "Gadgets",
                    subcategories: {
                        smart_watches: "Smart Watches",
                        fitness_trackers: "Fitness Trackers",
                        smart_home: "Smart Home Devices",
                        drones: "Drones",
                        vr_ar: "VR & AR Headsets",
                        wearables: "Wearable Tech",
                        other: "Other Gadgets"
                    },
                    fields: {
                        brand: {
                            type: "text",
                            label: "Brand",
                            required: true
                        },
                        model: {
                            type: "text",
                            label: "Model",
                            required: true
                        },
                        connectivity: {
                            type: "select",
                            label: "Connectivity",
                            options: ["Bluetooth", "Wi-Fi", "Cellular", "Wired", "Multiple"]
                        },
                        battery_life: {
                            type: "text",
                            label: "Battery Life",
                            placeholder: "e.g., 24 hours, 7 days, etc."
                        },
                        color: {
                            type: "text",
                            label: "Color"
                        },
                        compatibility: {
                            type: "text",
                            label: "Compatibility",
                            placeholder: "e.g., iOS, Android, Windows"
                        },
                        warranty: {
                            type: "select",
                            label: "Warranty",
                            options: ["No Warranty", "1 Month", "3 Months", "6 Months", "1 Year", "2 Years"]
                        }
                    }
                },
                vehicles: {
                    name: "Vehicles",
                    subcategories: {
                        cars: "Cars",
                        motorcycles: "Motorcycles",
                        trucks: "Trucks & Vans",
                        buses: "Buses",
                        parts: "Parts & Accessories",
                        other: "Other Vehicles"
                    },
                    fields: {
                        make: {
                            type: "text",
                            label: "Make",
                            required: true
                        },
                        model: {
                            type: "text",
                            label: "Model",
                            required: true
                        },
                        year: {
                            type: "number",
                            label: "Year",
                            required: true
                        },
                        mileage: {
                            type: "number",
                            label: "Mileage (km)"
                        },
                        fuel_type: {
                            type: "select",
                            label: "Fuel Type",
                            options: ["Petrol", "Diesel", "Electric", "Hybrid"]
                        },
                        transmission: {
                            type: "select",
                            label: "Transmission",
                            options: ["Manual", "Automatic"]
                        },
                        color: {
                            type: "text",
                            label: "Color"
                        }
                    }
                },
                properties: {
                    name: "Properties",
                    subcategories: {
                        apartments: "Apartments",
                        houses: "Houses",
                        lands: "Lands",
                        commercial: "Commercial Properties",
                        short_rent: "Short Term Rentals",
                        other: "Other Properties"
                    },
                    fields: {
                        property_type: {
                            type: "select",
                            label: "Property Type",
                            options: ["Residential", "Commercial", "Industrial", "Land"]
                        },
                        bedrooms: {
                            type: "number",
                            label: "Bedrooms"
                        },
                        bathrooms: {
                            type: "number",
                            label: "Bathrooms"
                        },
                        area: {
                            type: "number",
                            label: "Area (sqm)"
                        },
                        furnished: {
                            type: "select",
                            label: "Furnished",
                            options: ["Fully Furnished", "Semi-Furnished", "Unfurnished"]
                        },
                        parking: {
                            type: "select",
                            label: "Parking",
                            options: ["Available", "Not Available"]
                        }
                    }
                },
                fashion: {
                    name: "Fashion",
                    subcategories: {
                        clothing: "Clothing",
                        shoes: "Shoes",
                        bags: "Bags",
                        accessories: "Accessories",
                        jewelry: "Jewelry",
                        other: "Other Fashion"
                    },
                    fields: {
                        brand: {
                            type: "text",
                            label: "Brand"
                        },
                        size: {
                            type: "text",
                            label: "Size"
                        },
                        color: {
                            type: "text",
                            label: "Color"
                        },
                        material: {
                            type: "text",
                            label: "Material"
                        },
                        gender: {
                            type: "select",
                            label: "Gender",
                            options: ["Men", "Women", "Unisex", "Children"]
                        }
                    }
                },
                home_garden: {
                    name: "Home & Garden",
                    subcategories: {
                        furniture: "Furniture",
                        appliances: "Appliances",
                        garden: "Garden Tools",
                        decor: "Home Decor",
                        kitchen: "Kitchenware",
                        other: "Other Home & Garden"
                    },
                    fields: {
                        brand: {
                            type: "text",
                            label: "Brand"
                        },
                        material: {
                            type: "text",
                            label: "Material"
                        },
                        dimensions: {
                            type: "text",
                            label: "Dimensions"
                        },
                        power_source: {
                            type: "select",
                            label: "Power Source",
                            options: ["Electric", "Gas", "Manual", "Battery"]
                        }
                    }
                },
                other: {
                    name: "Other",
                    subcategories: {},
                    fields: {
                        item_type: {
                            type: "text",
                            label: "Item Type",
                            required: true
                        },
                        material: {
                            type: "text",
                            label: "Material"
                        },
                        dimensions: {
                            type: "text",
                            label: "Dimensions"
                        },
                        condition_details: {
                            type: "textarea",
                            label: "Condition Details"
                        }
                    }
                }
            },
            service: {
                repairs: {
                    name: "Repairs & Maintenance",
                    subcategories: {
                        electronics: "Electronics Repair",
                        home: "Home Repairs",
                        vehicle: "Vehicle Repair",
                        appliance: "Appliance Repair",
                        computer: "Computer Repair",
                        other: "Other Repairs"
                    },
                    fields: {
                        service_type: {
                            type: "text",
                            label: "Service Type",
                            required: true
                        },
                        experience: {
                            type: "select",
                            label: "Experience",
                            options: ["Beginner", "1-2 Years", "3-5 Years", "5+ Years"]
                        },
                        tools_available: {
                            type: "text",
                            label: "Tools Available"
                        },
                        warranty_offered: {
                            type: "select",
                            label: "Warranty Offered",
                            options: ["No Warranty", "30 Days", "90 Days", "1 Year"]
                        }
                    }
                },
                tutoring: {
                    name: "Tutoring & Education",
                    subcategories: {
                        academic: "Academic Subjects",
                        music: "Music Lessons",
                        language: "Language Tutoring",
                        test_prep: "Test Preparation",
                        computer: "Computer Skills",
                        other: "Other Tutoring"
                    },
                    fields: {
                        subjects: {
                            type: "text",
                            label: "Subjects",
                            required: true
                        },
                        qualification: {
                            type: "text",
                            label: "Qualification",
                            required: true
                        },
                        experience: {
                            type: "select",
                            label: "Experience",
                            options: ["Beginner", "1-2 Years", "3-5 Years", "5+ Years"]
                        },
                        teaching_method: {
                            type: "select",
                            label: "Teaching Method",
                            options: ["Online", "In-person", "Both"]
                        }
                    }
                },
                cleaning: {
                    name: "Cleaning Services",
                    subcategories: {
                        home: "Home Cleaning",
                        office: "Office Cleaning",
                        deep: "Deep Cleaning",
                        move: "Move-in/Move-out Cleaning",
                        other: "Other Cleaning"
                    },
                    fields: {
                        service_type: {
                            type: "text",
                            label: "Service Type",
                            required: true
                        },
                        equipment: {
                            type: "text",
                            label: "Equipment Used"
                        },
                        team_size: {
                            type: "select",
                            label: "Team Size",
                            options: ["Individual", "2-3 People", "4+ People"]
                        },
                        eco_friendly: {
                            type: "select",
                            label: "Eco-friendly",
                            options: ["Yes", "No"]
                        }
                    }
                },
                beauty: {
                    name: "Beauty & Personal Care",
                    subcategories: {
                        hair: "Hair Styling",
                        makeup: "Makeup Artistry",
                        nails: "Nail Services",
                        skincare: "Skincare",
                        barber: "Barber Services",
                        other: "Other Beauty"
                    },
                    fields: {
                        specialization: {
                            type: "text",
                            label: "Specialization",
                            required: true
                        },
                        certification: {
                            type: "text",
                            label: "Certification"
                        },
                        experience: {
                            type: "select",
                            label: "Experience",
                            options: ["Beginner", "1-2 Years", "3-5 Years", "5+ Years"]
                        },
                        products_used: {
                            type: "text",
                            label: "Products Used"
                        }
                    }
                },
                delivery: {
                    name: "Delivery Services",
                    subcategories: {
                        food: "Food Delivery",
                        package: "Package Delivery",
                        grocery: "Grocery Delivery",
                        document: "Document Delivery",
                        other: "Other Delivery"
                    },
                    fields: {
                        service_area: {
                            type: "text",
                            label: "Service Area",
                            required: true
                        },
                        vehicle_type: {
                            type: "select",
                            label: "Vehicle Type",
                            options: ["Motorcycle", "Car", "Truck", "Bicycle"]
                        },
                        delivery_time: {
                            type: "text",
                            label: "Delivery Time"
                        },
                        max_weight: {
                            type: "text",
                            label: "Max Weight Capacity"
                        }
                    }
                },
                // NEW CATEGORY: Hotel & Service Apartments
                accommodation: {
                    name: "Hotel & Service Apartments",
                    subcategories: {
                        hotel: "Hotel Rooms",
                        service_apartment: "Service Apartments",
                        other: "Other Accommodation"
                    },
                    fields: {
                        property_type: {
                            type: "select",
                            label: "Property Type",
                            required: true,
                            options: ["Hotel", "Service Apartment", "Guest House", "Vacation Rental",
                                "Short Stay Apartment"
                            ]
                        },
                        room_type: {
                            type: "select",
                            label: "Room Type",
                            required: true,
                            options: ["Single", "Double", "Twin", "Suite", "Studio", "1-Bedroom", "2-Bedroom",
                                "3-Bedroom"
                            ]
                        },
                        amenities: {
                            type: "textarea",
                            label: "Amenities",
                            placeholder: "List amenities like WiFi, AC, Pool, Gym, etc."
                        },
                        check_in_time: {
                            type: "text",
                            label: "Check-in Time",
                            placeholder: "e.g., 2:00 PM"
                        },
                        check_out_time: {
                            type: "text",
                            label: "Check-out Time",
                            placeholder: "e.g., 11:00 AM"
                        },
                        max_guests: {
                            type: "number",
                            label: "Maximum Guests",
                            required: true
                        },
                        breakfast_included: {
                            type: "select",
                            label: "Breakfast Included",
                            options: ["Yes", "No", "Optional"]
                        },
                        parking_available: {
                            type: "select",
                            label: "Parking Available",
                            options: ["Yes", "No", "Paid Parking"]
                        }
                    }
                },
                // NEW CATEGORY: Recruitment & Jobs
                recruitment: {
                    name: "Recruitment & Jobs",
                    subcategories: {
                        full_time: "Full-time Jobs",
                        part_time: "Part-time Jobs",
                        contract: "Contract Jobs",
                        freelance: "Freelance Work",
                        internship: "Internships",
                        remote: "Remote Jobs",
                        other: "Other Employment"
                    },
                    fields: {
                        job_title: {
                            type: "text",
                            label: "Job Title",
                            required: true,
                            placeholder: "e.g., Software Developer, Marketing Manager"
                        },
                        company_name: {
                            type: "text",
                            label: "Company Name",
                            required: true
                        },
                        employment_type: {
                            type: "select",
                            label: "Employment Type",
                            required: true,
                            options: ["Full-time", "Part-time", "Contract", "Freelance", "Internship", "Temporary"]
                        },
                        experience_level: {
                            type: "select",
                            label: "Experience Level",
                            options: ["Entry Level", "Mid Level", "Senior Level", "Executive"]
                        },
                        education_requirement: {
                            type: "select",
                            label: "Education Requirement",
                            options: ["High School", "Diploma", "Bachelor's Degree", "Master's Degree", "PhD",
                                "No Formal Education Required"
                            ]
                        },
                        salary_range: {
                            type: "text",
                            label: "Salary Range",
                            placeholder: "e.g., ₦100,000 - ₦200,000 monthly"
                        },
                        application_deadline: {
                            type: "date",
                            label: "Application Deadline"
                        },
                        work_location: {
                            type: "select",
                            label: "Work Location",
                            options: ["On-site", "Remote", "Hybrid"]
                        },
                        skills_required: {
                            type: "textarea",
                            label: "Skills Required",
                            placeholder: "List required skills and qualifications"
                        }
                    }
                },
                other: {
                    name: "Other Services",
                    subcategories: {},
                    fields: {
                        service_type: {
                            type: "text",
                            label: "Service Type",
                            required: true
                        },
                        specialization: {
                            type: "text",
                            label: "Specialization"
                        },
                        experience: {
                            type: "select",
                            label: "Experience",
                            options: ["Beginner", "1-2 Years", "3-5 Years", "5+ Years"]
                        },
                        service_area: {
                            type: "text",
                            label: "Service Area"
                        }
                    }
                }
            }
        };

        let selectedListingType = null;
        let selectedCategory = null;
        let selectedSubcategory = null;
        let uploadedImages = [];
        let isCustomCategory = false;

        // Initialize Dropzone
        Dropzone.autoDiscover = false;

        // Initialize Dropzone with proper configuration
        const initDropzone = () => {
            const myDropzone = new Dropzone("#imageUpload", {
                url: "/fake-url", // This is just a placeholder
                maxFiles: 10,
                maxFilesize: 5,
                acceptedFiles: "image/*",
                addRemoveLinks: false, // We'll handle removal ourselves
                autoProcessQueue: false, // Don't auto-upload
                dictDefaultMessage: "",
                previewsContainer: false, // Don't use default previews
                init: function() {
                    this.on("addedfile", function(file) {
                        // Create a preview for the uploaded file
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

                        // Remove the file from dropzone's internal list since we're handling it
                        this.removeFile(file);
                    });

                    this.on("error", function(file, message) {
                        console.error("Upload error:", message);
                        alert("Error uploading file: " + message);
                    });
                }
            });
        };

        // Update image preview
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

            // Add event listeners to remove buttons
            document.querySelectorAll('.remove-image').forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    removeImage(index);
                });
            });
        }

        function removeImage(index) {
            if (index >= 0 && index < uploadedImages.length) {
                uploadedImages.splice(index, 1);
                updateImagePreview();
            }
        }

        // Listing type selection
        document.querySelectorAll('.listing-type-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.listing-type-card').forEach(c => c.classList.remove(
                    'selected'));
                this.classList.add('selected');

                selectedListingType = this.dataset.type;

                // Show category section and load appropriate categories
                document.getElementById('categorySection').style.display = 'block';
                loadCategories(selectedListingType);

                // Reset category selection
                selectedCategory = null;
                selectedSubcategory = null;
                document.getElementById('nextToStep2').disabled = true;
                document.getElementById('subcategorySection').style.display = 'none';
                document.getElementById('customCategorySection').style.display = 'none';
            });
        });

        function loadCategories(type) {
            const categoryOptions = document.getElementById('categoryOptions');
            categoryOptions.innerHTML = '';

            if (categories[type]) {
                Object.entries(categories[type]).forEach(([key, category]) => {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 col-sm-6';
                    col.innerHTML = `
                        <div class="category-card" data-category="${key}">
                            <div class="category-icon">
                                <iconify-icon icon="${getCategoryIcon(type, key)}"></iconify-icon>
                            </div>
                            <h5>${category.name}</h5>
                            <p class="text-muted small">${getCategoryDescription(type, key)}</p>
                        </div>
                    `;
                    categoryOptions.appendChild(col);
                });

                // Add category selection event
                document.querySelectorAll('.category-card').forEach(card => {
                    card.addEventListener('click', function() {
                        document.querySelectorAll('.category-card').forEach(c => c.classList.remove(
                            'selected'));
                        this.classList.add('selected');

                        selectedCategory = this.dataset.category;
                        isCustomCategory = (selectedCategory === 'other');

                        // Show/hide appropriate sections
                        if (isCustomCategory) {
                            document.getElementById('customCategorySection').style.display = 'block';
                            document.getElementById('subcategorySection').style.display = 'none';
                        } else {
                            document.getElementById('customCategorySection').style.display = 'none';
                            loadSubcategories(selectedListingType, selectedCategory);
                        }

                        // Enable next button
                        document.getElementById('nextToStep2').disabled = false;
                    });
                });
            }
        }

        function getCategoryIcon(type, category) {
            const icons = {
                product: {
                    electronics: "mdi:cellphone",
                    gadgets: "mdi:devices",
                    vehicles: "mdi:car",
                    properties: "mdi:home",
                    fashion: "mdi:tshirt-crew",
                    home_garden: "mdi:sofa",
                    other: "mdi:package-variant"
                },
                service: {
                    repairs: "mdi:tools",
                    tutoring: "mdi:school",
                    cleaning: "mdi:broom",
                    beauty: "mdi:lipstick",
                    delivery: "mdi:truck-delivery",
                    accommodation: "mdi:bed",
                    recruitment: "mdi:briefcase",
                    other: "mdi:account-wrench"
                }
            };
            return icons[type]?.[category] || "mdi:help-circle";
        }

        function getCategoryDescription(type, category) {
            const descriptions = {
                product: {
                    electronics: "Phones, laptops, computers, TVs",
                    gadgets: "Smart watches, drones, VR headsets",
                    vehicles: "Cars, motorcycles, trucks",
                    properties: "Houses, lands, apartments",
                    fashion: "Clothes, shoes, accessories",
                    home_garden: "Furniture, appliances, tools",
                    other: "Other physical items"
                },
                service: {
                    repairs: "Fix and maintenance services",
                    tutoring: "Teaching and educational services",
                    cleaning: "Home and office cleaning",
                    beauty: "Hair, makeup, personal care",
                    delivery: "Package and food delivery",
                    accommodation: "Hotels, apartments, rentals",
                    recruitment: "Jobs, employment, hiring",
                    other: "Other services you offer"
                }
            };
            return descriptions[type]?.[category] || "Various items and services";
        }

        function loadSubcategories(type, category) {
            const subcategorySection = document.getElementById('subcategorySection');
            const subcategoryOptions = document.getElementById('subcategoryOptions');

            if (categories[type] && categories[type][category] &&
                Object.keys(categories[type][category].subcategories).length > 0) {
                subcategorySection.style.display = 'block';
                subcategoryOptions.innerHTML = '';

                Object.entries(categories[type][category].subcategories).forEach(([key, value]) => {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 col-sm-6';
                    col.innerHTML = `
                        <div class="category-card subcategory" data-subcategory="${key}">
                            <h6>${value}</h6>
                        </div>
                    `;
                    subcategoryOptions.appendChild(col);
                });

                // Add subcategory selection event
                document.querySelectorAll('.subcategory').forEach(card => {
                    card.addEventListener('click', function() {
                        document.querySelectorAll('.subcategory').forEach(c => c.classList.remove(
                            'selected'));
                        this.classList.add('selected');
                        selectedSubcategory = this.dataset.subcategory;
                    });
                });
            } else {
                subcategorySection.style.display = 'none';
            }
        }

        function loadDynamicFields() {
            const dynamicFields = document.getElementById('dynamicFields');
            const serviceDynamicFields = document.getElementById('serviceDynamicFields');

            // Clear existing fields
            dynamicFields.innerHTML = '';
            serviceDynamicFields.innerHTML = '';

            if (selectedListingType && selectedCategory) {
                const categoryConfig = categories[selectedListingType][selectedCategory];

                if (categoryConfig && categoryConfig.fields) {
                    let fieldsHTML = '<div class="specification-group"><h5>Specifications</h5><div class="row">';

                    Object.entries(categoryConfig.fields).forEach(([key, field]) => {
                        const colClass = field.type === 'textarea' ? 'col-12' : 'col-md-6';
                        const requiredClass = field.required ? 'required-field' : '';
                        const fieldId = selectedListingType === 'product' ? key : `service_${key}`;

                        if (field.type === 'select') {
                            fieldsHTML += `
                                <div class="${colClass} mb-3">
                                    <label for="${fieldId}" class="form-label ${requiredClass}">${field.label}</label>
                                    <select class="form-select" id="${fieldId}" name="${fieldId}" ${field.required ? 'required' : ''}>
                                        <option value="">Select ${field.label}</option>
                                        ${field.options.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
                                    </select>
                                    <div class="validation-message" id="${fieldId}Error">Please select ${field.label.toLowerCase()}</div>
                                </div>
                            `;
                        } else if (field.type === 'textarea') {
                            fieldsHTML += `
                                <div class="${colClass} mb-3">
                                    <label for="${fieldId}" class="form-label ${requiredClass}">${field.label}</label>
                                    <textarea class="form-control" id="${fieldId}" name="${fieldId}" rows="3"
                                              placeholder="${field.placeholder || 'Enter ' + field.label.toLowerCase()}" ${field.required ? 'required' : ''}></textarea>
                                    <div class="validation-message" id="${fieldId}Error">Please enter ${field.label.toLowerCase()}</div>
                                </div>
                            `;
                        } else if (field.type === 'date') {
                            fieldsHTML += `
                                <div class="${colClass} mb-3">
                                    <label for="${fieldId}" class="form-label ${requiredClass}">${field.label}</label>
                                    <input type="date" class="form-control" id="${fieldId}" name="${fieldId}"
                                           ${field.required ? 'required' : ''}>
                                    <div class="validation-message" id="${fieldId}Error">Please enter ${field.label.toLowerCase()}</div>
                                </div>
                            `;
                        } else {
                            fieldsHTML += `
                                <div class="${colClass} mb-3">
                                    <label for="${fieldId}" class="form-label ${requiredClass}">${field.label}</label>
                                    <input type="${field.type}" class="form-control" id="${fieldId}" name="${fieldId}"
                                           placeholder="${field.placeholder || 'Enter ' + field.label.toLowerCase()}" ${field.required ? 'required' : ''}>
                                    <div class="validation-message" id="${fieldId}Error">Please enter ${field.label.toLowerCase()}</div>
                                </div>
                            `;
                        }
                    });

                    fieldsHTML += '</div></div>';

                    if (selectedListingType === 'product') {
                        dynamicFields.innerHTML = fieldsHTML;
                    } else {
                        serviceDynamicFields.innerHTML = fieldsHTML;
                    }

                    // Add validation for dynamic fields
                    Object.keys(categoryConfig.fields).forEach(key => {
                        const fieldId = selectedListingType === 'product' ? key : `service_${key}`;
                        const field = document.getElementById(fieldId);
                        if (field) {
                            field.addEventListener('blur', function() {
                                validateField(this);
                            });
                        }
                    });
                }
            }
        }

        // Step navigation
        function nextStep(step) {
            // Validate current step before proceeding
            if (step > 1 && !validateStep(step - 1)) {
                return;
            }

            document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
            document.getElementById(`step${step}`).classList.add('active');

            document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
            document.querySelector(`.step[data-step="${step}"]`).classList.add('active');

            // Update previous steps to completed
            for (let i = 1; i < step; i++) {
                document.querySelector(`.step[data-step="${i}"]`).classList.add('completed');
            }

            // Update progress bar
            document.getElementById('formProgress').style.width = `${(step / 4) * 100}%`;

            if (step === 2) {
                // Update form display and load dynamic fields
                updateFormDisplay();
                loadDynamicFields();
            } else if (step === 4) {
                updateReviewSection();
            }
        }

        function prevStep(step) {
            nextStep(step);

            // Update progress bar
            document.getElementById('formProgress').style.width = `${(step / 4) * 100}%`;
        }

        function updateFormDisplay() {
            // Update the form title and show/hide appropriate sections
            if (selectedListingType === 'product') {
                document.getElementById('detailsTitle').textContent = 'Product Details';
                document.getElementById('productSection').style.display = 'block';
                document.getElementById('serviceSection').style.display = 'none';
            } else {
                document.getElementById('detailsTitle').textContent = 'Service Details';
                document.getElementById('productSection').style.display = 'none';
                document.getElementById('serviceSection').style.display = 'block';
            }
        }

        function updateReviewSection() {
            // Update review section with form data
            if (isCustomCategory) {
                const customCategory = document.getElementById('customCategory').value;
                const customSubcategory = document.getElementById('customSubcategory').value;
                let categoryText = `Category: ${customCategory}`;
                if (customSubcategory) {
                    categoryText += ` > ${customSubcategory}`;
                }
                document.getElementById('reviewCategory').textContent = categoryText;
            } else {
                const categoryName = categories[selectedListingType][selectedCategory].name;
                const subcategoryName = selectedSubcategory ?
                    categories[selectedListingType][selectedCategory].subcategories[selectedSubcategory] : '';

                let categoryText = `Category: ${categoryName}`;
                if (subcategoryName) {
                    categoryText += ` > ${subcategoryName}`;
                }
                document.getElementById('reviewCategory').textContent = categoryText;
            }

            // Update title and price based on type
            if (selectedListingType === 'product') {
                document.getElementById('reviewTitle').textContent = document.getElementById('productTitle').value;
                const price = document.getElementById('productPrice').value;
                document.getElementById('reviewPrice').textContent = `₦${parseInt(price).toLocaleString()}`;
                document.getElementById('reviewDescription').textContent = document.getElementById('productDescription')
                    .value;
                document.getElementById('reviewLocation').textContent = document.getElementById('productLocation').value;
            } else {
                document.getElementById('reviewTitle').textContent = document.getElementById('serviceTitle').value;
                const price = document.getElementById('servicePrice').value;
                document.getElementById('reviewPrice').textContent = `₦${parseInt(price).toLocaleString()}`;
                document.getElementById('reviewDescription').textContent = document.getElementById('serviceDescription')
                    .value;
                document.getElementById('reviewLocation').textContent = document.getElementById('serviceLocation').value;
            }

            // Update specifications
            const specsContainer = document.getElementById('reviewSpecifications');
            specsContainer.innerHTML = '';

            if (selectedCategory && categories[selectedListingType][selectedCategory].fields) {
                Object.entries(categories[selectedListingType][selectedCategory].fields).forEach(([key, field]) => {
                    const fieldId = selectedListingType === 'product' ? key : `service_${key}`;
                    const value = document.getElementById(fieldId)?.value;
                    if (value) {
                        const specItem = document.createElement('div');
                        specItem.className = 'mb-2';
                        specItem.innerHTML = `<strong>${field.label}:</strong> ${value}`;
                        specsContainer.appendChild(specItem);
                    }
                });
            }

            // Update details
            const detailsContainer = document.getElementById('reviewDetails');
            detailsContainer.innerHTML = '';

            if (selectedListingType === 'product') {
                const condition = document.getElementById('productCondition');
                if (condition.value) {
                    const conditionItem = document.createElement('div');
                    conditionItem.className = 'mb-2';
                    conditionItem.innerHTML =
                        `<strong>Condition:</strong> ${condition.options[condition.selectedIndex].text}`;
                    detailsContainer.appendChild(conditionItem);
                }
            } else {
                const serviceType = document.getElementById('serviceType');
                if (serviceType.value) {
                    const typeItem = document.createElement('div');
                    typeItem.className = 'mb-2';
                    typeItem.innerHTML =
                        `<strong>Service Type:</strong> ${serviceType.options[serviceType.selectedIndex].text}`;
                    detailsContainer.appendChild(typeItem);
                }

                const duration = document.getElementById('serviceDuration');
                if (duration.value) {
                    const durationItem = document.createElement('div');
                    durationItem.className = 'mb-2';
                    durationItem.innerHTML = `<strong>Duration:</strong> ${duration.value}`;
                    detailsContainer.appendChild(durationItem);
                }

                const availability = document.getElementById('serviceAvailability');
                if (availability.value) {
                    const availabilityItem = document.createElement('div');
                    availabilityItem.className = 'mb-2';
                    availabilityItem.innerHTML =
                        `<strong>Availability:</strong> ${availability.options[availability.selectedIndex].text}`;
                    detailsContainer.appendChild(availabilityItem);
                }
            }

            // Add images count
            const imagesItem = document.createElement('div');
            imagesItem.className = 'mb-2';
            imagesItem.innerHTML = `<strong>Images:</strong> ${uploadedImages.length} uploaded`;
            detailsContainer.appendChild(imagesItem);
        }

        // Form validation
        function validateStep(step) {
            let isValid = true;

            if (step === 1) {
                if (!selectedListingType) {
                    alert('Please select whether you are selling a product or service');
                    isValid = false;
                } else if (!selectedCategory) {
                    alert('Please select a category');
                    isValid = false;
                } else if (isCustomCategory) {
                    // Validate custom category fields
                    const customCategory = document.getElementById('customCategory');
                    if (!validateField(customCategory)) {
                        isValid = false;
                    }
                }
            } else if (step === 2) {
                // Validate fields based on listing type
                if (selectedListingType === 'product') {
                    const requiredFields = [
                        'productTitle', 'productPrice', 'productDescription',
                        'productCondition', 'productLocation'
                    ];

                    requiredFields.forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        if (!validateField(field)) {
                            isValid = false;
                        }
                    });
                } else {
                    const requiredFields = [
                        'serviceTitle', 'servicePrice', 'serviceDescription',
                        'serviceType', 'serviceLocation'
                    ];

                    requiredFields.forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        if (!validateField(field)) {
                            isValid = false;
                        }
                    });
                }

                // Validate dynamic fields if any
                if (selectedCategory && categories[selectedListingType][selectedCategory].fields) {
                    Object.entries(categories[selectedListingType][selectedCategory].fields).forEach(([key, field]) => {
                        if (field.required) {
                            const fieldId = selectedListingType === 'product' ? key : `service_${key}`;
                            const fieldElement = document.getElementById(fieldId);
                            if (!validateField(fieldElement)) {
                                isValid = false;
                            }
                        }
                    });
                }
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

            if (field.hasAttribute('required') && (!field.value || (field.type === 'number' && field.value <= 0))) {
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

        // Form submission
        document.getElementById('sellForm').addEventListener('submit', function(e) {
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

            if (!selectedListingType || !selectedCategory) {
                alert('Please complete all required fields');
                return;
            }

            // Collect form data
            const formData = {
                type: selectedListingType,
                category: isCustomCategory ? 'custom' : selectedCategory,
                subcategory: isCustomCategory ? null : selectedSubcategory,
                customCategory: isCustomCategory ? document.getElementById('customCategory').value : null,
                customSubcategory: isCustomCategory ? document.getElementById('customSubcategory').value : null,
                title: selectedListingType === 'product' ? document.getElementById('productTitle').value :
                    document.getElementById('serviceTitle').value,
                price: selectedListingType === 'product' ? document.getElementById('productPrice').value :
                    document.getElementById('servicePrice').value,
                description: selectedListingType === 'product' ? document.getElementById('productDescription')
                    .value : document.getElementById('serviceDescription').value,
                location: selectedListingType === 'product' ? document.getElementById('productLocation').value :
                    document.getElementById('serviceLocation').value,
                images: uploadedImages,
                specifications: {}
            };

            // Add type-specific data
            if (selectedListingType === 'product') {
                formData.condition = document.getElementById('productCondition').value;
            } else {
                formData.serviceType = document.getElementById('serviceType').value;
                formData.duration = document.getElementById('serviceDuration').value;
                formData.availability = document.getElementById('serviceAvailability').value;
            }

            // Collect dynamic fields
            if (selectedCategory && categories[selectedListingType][selectedCategory].fields) {
                Object.keys(categories[selectedListingType][selectedCategory].fields).forEach(key => {
                    const fieldId = selectedListingType === 'product' ? key : `service_${key}`;
                    formData.specifications[key] = document.getElementById(fieldId)?.value;
                });
            }

            console.log('Form submitted:', formData);

            // Show success message
            document.getElementById('successMessage').style.display = 'block';
            document.getElementById('submitListing').disabled = true;

            // Simulate API call
            setTimeout(() => {
                // Here you would typically send the data to your backend
                alert('Your listing has been published successfully!');
                // Reset form or redirect
            }, 1500);
        });

        // Initialize form
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Dropzone
            initDropzone();

            // Disable next button until category is selected
            document.getElementById('nextToStep2').disabled = true;

            // Add event listeners for step navigation buttons
            document.getElementById('nextToStep2').addEventListener('click', function() {
                nextStep(2);
            });

            document.getElementById('nextToStep3').addEventListener('click', function() {
                nextStep(3);
            });

            document.getElementById('nextToStep4').addEventListener('click', function() {
                nextStep(4);
            });

            // Add validation for common fields
            const commonFields = [
                'productTitle', 'productPrice', 'productDescription',
                'productCondition', 'productLocation', 'customCategory',
                'serviceTitle', 'servicePrice', 'serviceDescription',
                'serviceType', 'serviceLocation'
            ];

            commonFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('blur', function() {
                        validateField(this);
                    });
                }
            });

            // Initialize image preview
            updateImagePreview();
        });
    </script>
</body>

</html>
