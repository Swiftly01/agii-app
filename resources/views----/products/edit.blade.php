@extends('layout.layout')
@section('title', 'Edit Product - ' . $product->title)
@section('content')

    <section class="py-5">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="text-center mb-5">
                        <h1 class="display-5 fw-bold mb-3">Edit Product</h1>
                        <p class="lead text-muted">Update your product information</p>
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
                        @method('PUT')

                        <!-- Step 1: Category Selection -->
                        <div class="form-step active" id="step1">
                            <h3 class="section-title">Product Category</h3>
                            <p class="text-muted mb-4">Current category: <strong>{{ $product->category->name }}</strong></p>

                            <div class="row g-4" id="categoryOptions">
                                @foreach ($categories as $category)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="category-card" data-category="{{ $category->id }}"
                                            {{ $product->category_id == $category->id ? 'data-selected="true"' : '' }}>
                                            <div class="category-icon">
                                                <i class="bi bi-{{ $category->icon ?? 'box' }}"></i>
                                            </div>
                                            <h5>{{ $category->name }}</h5>
                                            <p class="text-muted small">{{ $category->description }}</p>
                                            @if ($product->category_id == $category->id)
                                                <div class="selected-badge">Currently Selected</div>
                                            @endif
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
                                <button type="button" class="btn btn-primary" id="nextToStep2">Next: Details</button>
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
                                                value="{{ old('title', $product->title) }}"
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
                                                value="{{ old('price', $product->price) }}" placeholder="e.g., 450000"
                                                required min="0" step="0.01">
                                            <div class="validation-message" id="priceError">Please enter a valid price</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="oldPrice" class="form-label fw-semibold">Old Price (₦)</label>
                                            <input type="number" class="form-control" id="oldPrice" name="old_price"
                                                value="{{ old('old_price', $product->old_price) }}"
                                                placeholder="e.g., 500000" min="0" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label fw-semibold required-field">Quantity
                                                Available</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity"
                                                value="{{ old('quantity', $product->quantity) }}" required min="1">
                                            <div class="validation-message" id="quantityError">Please enter quantity</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="productDescription" class="form-label fw-semibold required-field">Product
                                        Description</label>
                                    <textarea class="form-control" id="productDescription" name="description" rows="5"
                                        placeholder="Describe your product in detail. Include condition, features, and any relevant information..."
                                        required>{{ old('description', $product->description) }}</textarea>
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
                                                <option value="new"
                                                    {{ $product->condition == 'new' ? 'selected' : '' }}>Brand New</option>
                                                <option value="used_like_new"
                                                    {{ $product->condition == 'used_like_new' ? 'selected' : '' }}>Used -
                                                    Like New</option>
                                                <option value="used_good"
                                                    {{ $product->condition == 'used_good' ? 'selected' : '' }}>Used - Good
                                                </option>
                                                <option value="used_fair"
                                                    {{ $product->condition == 'used_fair' ? 'selected' : '' }}>Used - Fair
                                                </option>
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
                                                name="location" value="{{ old('location', $product->location) }}"
                                                placeholder="e.g., Lekki Phase 1, Lagos" required>
                                            <div class="validation-message" id="locationError">Please enter a location
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="productStatus"
                                                class="form-label fw-semibold required-field">Status</label>
                                            <select class="form-select" id="productStatus" name="status" required>
                                                <option value="active"
                                                    {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive"
                                                    {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive
                                                </option>
                                                <option value="pending"
                                                    {{ $product->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox" id="negotiable"
                                                    name="negotiable" value="1"
                                                    {{ $product->negotiable ? 'checked' : '' }}>
                                                <label class="form-check-label" for="negotiable">
                                                    Price is negotiable
                                                </label>
                                            </div>
                                        </div>
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
                            <h3 class="section-title">Product Photos</h3>
                            <p class="text-muted mb-4">Current images (click × to remove, or upload new ones)</p>

                            <!-- Current Images -->
                            <div class="current-images mb-4">
                                <h5>Current Images:</h5>
                                <div class="image-preview" id="currentImagePreview">
                                    @foreach ($product->images as $image)
                                        <div class="preview-item">
                                            <img src="{{ asset($image) }}" alt="Current Image">
                                            <button type="button" class="remove-image"
                                                data-image="{{ $image }}">×</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

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
                                <!-- New image previews will be shown here -->
                            </div>

                            <div class="d-flex justify-content-between mt-5">
                                <button type="button" class="btn btn-outline-primary"
                                    onclick="prevStep(2)">Previous</button>
                                <button type="button" class="btn btn-primary" id="nextToStep4">Next: Review</button>
                            </div>
                        </div>

                        <!-- Step 4: Review & Submit -->
                        <div class="form-step" id="step4">
                            <h3 class="section-title">Review Changes</h3>

                            <div class="category-preview">
                                <h5 id="reviewCategory">Category: {{ $product->category->name }}</h5>
                                <h3 id="reviewTitle" class="mt-2">{{ $product->title }}</h3>
                                <h4 class="text-primary mt-2" id="reviewPrice">₦{{ number_format($product->price) }}</h4>
                                @if ($product->old_price)
                                    <div id="reviewOldPrice" class="text-muted">
                                        <del>₦{{ number_format($product->old_price) }}</del>
                                    </div>
                                @endif
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
                                <p id="reviewDescription" class="text-muted">{{ $product->description }}</p>
                            </div>

                            <div class="mt-4">
                                <h5>Location</h5>
                                <p id="reviewLocation" class="text-muted">{{ $product->location }}</p>
                            </div>

                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                <label class="form-check-label" for="agreeTerms">
                                    I confirm that these changes are accurate
                                </label>
                                <div class="validation-message" id="termsError">You must agree to continue</div>
                            </div>

                            <div class="success-message mt-4" id="successMessage" style="display: none;">
                                <h5>Product Updated Successfully!</h5>
                                <p>Your product has been updated successfully.</p>
                            </div>

                            <div class="d-flex justify-content-between mt-5">
                                <button type="button" class="btn btn-outline-primary"
                                    onclick="prevStep(3)">Previous</button>
                                <div>
                                    <button type="button" class="btn btn-danger me-2" id="deleteProduct">Delete
                                        Product</button>
                                    <button type="submit" class="btn btn-success" id="updateProduct">Update
                                        Product</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #90c74b;
            --accent-color: #90c74b;
            --light-primary-color: rgba(144, 199, 75, 0.1);
            --card-bg: #ffffff;
            --border-color: #e9ecef;
            --light-bg: #f8f9fa;
            --success-color: #28a745;
            --light-grey-color: #f8f9fa;
        }

        /* Progress Bar */
        .progress {
            height: 8px;
            border-radius: 10px;
            background-color: var(--light-bg);
        }

        .progress-bar {
            background-color: var(--primary-color);
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        /* Step Indicator */
        .step-indicator {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin: 0 20px;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: var(--border-color);
            z-index: 1;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--light-bg);
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }

        .step.active .step-number {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .step-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: #6c757d;
        }

        .step.active .step-label {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Form Steps */
        .form-step {
            display: none;
            animation: fadeIn 0.5s ease-in;
        }

        .form-step.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Section Titles */
        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 30px;
            font-weight: 700;
            color: #2c3e50;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        /* Category Cards */
        .category-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 25px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            height: 100%;
        }

        .category-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .category-card.selected {
            border-color: var(--primary-color);
            background: var(--light-primary-color);
        }

        .category-icon {
            width: 60px;
            height: 60px;
            background: var(--light-primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .category-card h5 {
            font-weight: 600;
            margin-bottom: 8px;
            color: #2c3e50;
        }

        .category-card .text-muted {
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .selected-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--success-color);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        /* Form Controls */
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .required-field::after {
            content: '*';
            color: #dc3545;
            margin-left: 4px;
        }

        .form-control,
        .form-select {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(144, 199, 75, 0.25);
        }

        .validation-message {
            display: none;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 5px;
        }

        /* Dropzone */
        .dropzone-container {
            margin-bottom: 20px;
        }

        .dropzone {
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--light-bg);
        }

        .dropzone:hover {
            border-color: var(--primary-color);
            background: var(--light-primary-color);
        }

        .dropzone.dragover {
            border-color: var(--primary-color);
            background: var(--light-primary-color);
        }

        .dz-message h5 {
            color: #2c3e50;
            margin-bottom: 8px;
        }

        /* Image Previews */
        .current-images {
            background: var(--light-bg);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .image-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .preview-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .preview-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            display: block;
        }

        .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 24px;
            height: 24px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .preview-item:hover .remove-image {
            opacity: 1;
        }

        /* Review Section */
        .category-preview {
            background: var(--light-bg);
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        #reviewTitle {
            color: #2c3e50;
            font-weight: 700;
        }

        #reviewPrice {
            font-weight: 700;
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #7ab436;
            border-color: #7ab436;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(144, 199, 75, 0.3);
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

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #218838;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #c82333;
            transform: translateY(-2px);
        }

        /* Success Message */
        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        /* Spinner */
        .spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Form Steps Navigation */
        .form-step-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .step-indicator {
                margin: 0 10px;
            }

            .step-number {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }

            .step-label {
                font-size: 0.75rem;
            }

            .category-card {
                padding: 20px 15px;
            }

            .category-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }

            .image-preview {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
                gap: 10px;
            }

            .preview-item img {
                height: 100px;
            }

            .btn {
                padding: 8px 20px;
                font-size: 0.9rem;
            }

            .form-step-navigation {
                flex-direction: column;
                gap: 15px;
            }

            .form-step-navigation .btn {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .step-indicator {
                flex-direction: column;
                gap: 20px;
                align-items: flex-start;
            }

            .step-indicator::before {
                display: none;
            }

            .step {
                flex-direction: row;
                gap: 15px;
                width: 100%;
            }

            .step-number {
                margin-bottom: 0;
            }

            .category-card {
                margin-bottom: 15px;
            }

            .dropzone {
                padding: 30px 15px;
            }

            .dz-message h5 {
                font-size: 1.1rem;
            }
        }

        /* Loading States */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        /* Utility Classes */
        .text-primary {
            color: var(--primary-color) !important;
        }

        .border-primary {
            border-color: var(--primary-color) !important;
        }

        .bg-light-primary {
            background-color: var(--light-primary-color) !important;
        }

        /* Subcategory Section */
        #subcategorySection {
            background: var(--light-bg);
            padding: 25px;
            border-radius: 12px;
            margin-top: 20px;
        }

        .subcategory-option {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .subcategory-option:hover {
            border-color: var(--primary-color);
        }

        .subcategory-option.selected {
            border-color: var(--primary-color);
            background: var(--light-primary-color);
        }

        /* Dynamic Fields */
        .specification-field {
            margin-bottom: 15px;
        }

        .specification-field label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        /* Checkbox Styles */
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(144, 199, 75, 0.25);
        }

        /* Alert Styles */
        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Initialize with product data
        let selectedCategory = {{ $product->category_id }};
        let selectedSubcategory = null;
        let uploadedImages = [];
        let specificationFields = {};
        let removedImages = [];

        // Load specification fields function
        function loadSpecificationFields(categoryId, subcategoryId = null) {
            const dynamicFields = document.getElementById('dynamicFields');

            // Show loading state
            dynamicFields.innerHTML =
                '<div class="text-center"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading specifications...</p></div>';

            let url = `/categories/${categoryId}/specifications`;
            if (subcategoryId) {
                url += `?subcategory_id=${subcategoryId}`;
            }

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    dynamicFields.innerHTML = '';
                    specificationFields = {};

                    // Check if we have specification fields in the response
                    const fields = data.fields || data.specification_fields || [];

                    if (fields && fields.length > 0) {
                        const fieldsContainer = document.createElement('div');
                        fieldsContainer.className = 'specification-fields';
                        fieldsContainer.innerHTML = '<h5 class="mb-3">Specifications</h5>';

                        // Get current product specifications
                        const currentSpecifications = {!! json_encode($product->specifications ?? []) !!};

                        fields.forEach(field => {
                            const fieldId = `spec_${field.name || field.key}`;
                            const fieldName = field.name || field.key;
                            const fieldLabel = field.label || field.name || field.key;

                            specificationFields[fieldName] = field;

                            const fieldElement = document.createElement('div');
                            fieldElement.className = 'mb-3 specification-field';

                            let inputField = '';
                            const currentValue = currentSpecifications[fieldName] || '';

                            // Handle different field types
                            if (field.type === 'select' && field.options) {
                                inputField = `
                                <select class="form-select" id="${fieldId}" name="specifications[${fieldName}]">
                                    <option value="">Select ${fieldLabel}</option>
                                    ${field.options.map(option =>
                                        `<option value="${option}" ${currentValue === option ? 'selected' : ''}>${option}</option>`
                                    ).join('')}
                                </select>
                            `;
                            } else if (field.type === 'textarea') {
                                inputField = `
                                <textarea class="form-control" id="${fieldId}" name="specifications[${fieldName}]"
                                          rows="3" placeholder="${field.placeholder || ''}">${currentValue}</textarea>
                            `;
                            } else {
                                // Default to text input
                                inputField = `
                                <input type="${field.type || 'text'}" class="form-control" id="${fieldId}"
                                       name="specifications[${fieldName}]" value="${currentValue}"
                                       placeholder="${field.placeholder || ''}">
                            `;
                            }

                            fieldElement.innerHTML = `
                            <label for="${fieldId}" class="form-label">${fieldLabel} ${field.required ? '<span class="text-danger">*</span>' : ''}</label>
                            ${inputField}
                            ${field.description ? `<div class="form-text">${field.description}</div>` : ''}
                        `;

                            fieldsContainer.appendChild(fieldElement);
                        });

                        dynamicFields.appendChild(fieldsContainer);
                    } else {
                        dynamicFields.innerHTML =
                            '<p class="text-muted">No additional specifications required for this category.</p>';
                    }

                    updateReviewSection();
                })
                .catch(error => {
                    console.error('Error loading specifications:', error);
                    dynamicFields.innerHTML =
                        '<p class="text-muted">No specifications available for this category.</p>';
                });
        }

        // Update the loadSubcategories function to use your API structure
        function loadSubcategories(categoryId) {
            const subcategorySection = document.getElementById('subcategorySection');
            const subcategoryOptions = document.getElementById('subcategoryOptions');

            if (!subcategorySection || !subcategoryOptions) return;

            // Show loading state
            subcategoryOptions.innerHTML =
                '<div class="col-12 text-center"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading subcategories...</p></div>';
            subcategorySection.style.display = 'block';

            // Fetch subcategories from server
            fetch(`/categories/${categoryId}/subcategories`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Use the correct response structure from your API
                    const subcategories = data.subcategories || [];

                    if (subcategories.length > 0) {
                        subcategoryOptions.innerHTML = '';
                        subcategories.forEach(subcategory => {
                            const isSelected = {{ $product->subcategory_id ?? 'null' }} == subcategory.id;
                            const subcategoryElement = document.createElement('div');
                            subcategoryElement.className = 'col-md-4 col-sm-6';
                            subcategoryElement.innerHTML = `
                            <div class="subcategory-option ${isSelected ? 'selected' : ''}"
                                 data-subcategory="${subcategory.id}">
                                <h6>${subcategory.name}</h6>
                                <p class="text-muted small mb-0">${subcategory.description || ''}</p>
                                ${isSelected ? '<div class="selected-badge">Currently Selected</div>' : ''}
                            </div>
                        `;
                            subcategoryOptions.appendChild(subcategoryElement);
                        });

                        // Add event listeners to subcategory options
                        document.querySelectorAll('.subcategory-option').forEach(option => {
                            option.addEventListener('click', function() {
                                document.querySelectorAll('.subcategory-option').forEach(opt => {
                                    opt.classList.remove('selected');
                                });
                                this.classList.add('selected');
                                selectedSubcategory = this.dataset.subcategory;
                                loadSpecificationFields(categoryId, selectedSubcategory);
                            });
                        });

                        // Auto-select if there's a current subcategory
                        const currentSubcategory = {{ $product->subcategory_id ?? 'null' }};
                        if (currentSubcategory) {
                            const currentOption = document.querySelector(
                                `.subcategory-option[data-subcategory="${currentSubcategory}"]`);
                            if (currentOption) {
                                currentOption.click();
                            }
                        }
                    } else {
                        subcategorySection.style.display = 'none';
                        // Also load specification fields from the subcategories response if available
                        if (data.specification_fields && data.specification_fields.length > 0) {
                            const dynamicFields = document.getElementById('dynamicFields');
                            dynamicFields.innerHTML = '';
                            specificationFields = {};

                            const fieldsContainer = document.createElement('div');
                            fieldsContainer.className = 'specification-fields';
                            fieldsContainer.innerHTML = '<h5 class="mb-3">Specifications</h5>';

                            // Process specification fields from subcategories response
                            const currentSpecifications = {!! json_encode($product->specifications ?? []) !!};

                            data.specification_fields.forEach(field => {
                                const fieldId = `spec_${field.name || field.key}`;
                                const fieldName = field.name || field.key;
                                const fieldLabel = field.label || field.name || field.key;

                                specificationFields[fieldName] = field;

                                const fieldElement = document.createElement('div');
                                fieldElement.className = 'mb-3 specification-field';

                                let inputField = '';
                                const currentValue = currentSpecifications[fieldName] || '';

                                if (field.type === 'select' && field.options) {
                                    inputField = `
                                    <select class="form-select" id="${fieldId}" name="specifications[${fieldName}]">
                                        <option value="">Select ${fieldLabel}</option>
                                        ${field.options.map(option =>
                                            `<option value="${option}" ${currentValue === option ? 'selected' : ''}>${option}</option>`
                                        ).join('')}
                                    </select>
                                `;
                                } else {
                                    inputField = `
                                    <input type="${field.type || 'text'}" class="form-control" id="${fieldId}"
                                           name="specifications[${fieldName}]" value="${currentValue}"
                                           placeholder="${field.placeholder || ''}">
                                `;
                                }

                                fieldElement.innerHTML = `
                                <label for="${fieldId}" class="form-label">${fieldLabel}</label>
                                ${inputField}
                            `;

                                fieldsContainer.appendChild(fieldElement);
                            });

                            dynamicFields.appendChild(fieldsContainer);
                        } else {
                            loadSpecificationFields(categoryId);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading subcategories:', error);
                    subcategorySection.style.display = 'none';
                    loadSpecificationFields(categoryId);
                });
        }
        // Update review section function
        function updateReviewSection() {
            // Update category
            const selectedCategoryCard = document.querySelector(`.category-card[data-category="${selectedCategory}"]`);
            if (selectedCategoryCard) {
                document.getElementById('reviewCategory').textContent = 'Category: ' + selectedCategoryCard.querySelector(
                    'h5').textContent;
            }

            // Update title and price
            document.getElementById('reviewTitle').textContent = document.getElementById('productTitle').value ||
                '{{ $product->title }}';
            const priceValue = document.getElementById('productPrice').value;
            document.getElementById('reviewPrice').textContent = '₦' + (priceValue ?
                Number(priceValue).toLocaleString() :
                '{{ number_format($product->price) }}');

            // Update old price
            const oldPrice = document.getElementById('oldPrice').value;
            const reviewOldPrice = document.getElementById('reviewOldPrice');
            if (oldPrice) {
                if (!reviewOldPrice) {
                    const priceElement = document.getElementById('reviewPrice');
                    const oldPriceElement = document.createElement('div');
                    oldPriceElement.id = 'reviewOldPrice';
                    oldPriceElement.className = 'text-muted';
                    oldPriceElement.innerHTML = `<del>₦${Number(oldPrice).toLocaleString()}</del>`;
                    priceElement.parentNode.insertBefore(oldPriceElement, priceElement.nextSibling);
                } else {
                    reviewOldPrice.innerHTML = `<del>₦${Number(oldPrice).toLocaleString()}</del>`;
                }
            } else if (reviewOldPrice) {
                reviewOldPrice.remove();
            }

            // Update description
            document.getElementById('reviewDescription').textContent = document.getElementById('productDescription')
                .value || '{{ $product->description }}';

            // Update location
            document.getElementById('reviewLocation').textContent = document.getElementById('productLocation').value ||
                '{{ $product->location }}';

            // Update specifications in review
            const reviewSpecifications = document.getElementById('reviewSpecifications');
            reviewSpecifications.innerHTML = '';

            Object.keys(specificationFields).forEach(key => {
                const fieldElement = document.getElementById(`spec_${key}`);
                if (fieldElement && fieldElement.value) {
                    const specItem = document.createElement('div');
                    specItem.className = 'mb-2';
                    specItem.innerHTML =
                        `<strong>${specificationFields[key].label}:</strong> ${fieldElement.value}`;
                    reviewSpecifications.appendChild(specItem);
                }
            });

            if (reviewSpecifications.children.length === 0) {
                reviewSpecifications.innerHTML = '<p class="text-muted">No specifications provided</p>';
            }

            // Update details
            const reviewDetails = document.getElementById('reviewDetails');
            const conditionSelect = document.getElementById('productCondition');
            const statusSelect = document.getElementById('productStatus');
            const negotiableCheckbox = document.getElementById('negotiable');

            reviewDetails.innerHTML = `
            <div class="mb-2"><strong>Condition:</strong> ${conditionSelect ? conditionSelect.options[conditionSelect.selectedIndex].text : '{{ $product->condition }}'}</div>
            <div class="mb-2"><strong>Quantity:</strong> ${document.getElementById('quantity').value || '{{ $product->quantity }}'}</div>
            <div class="mb-2"><strong>Status:</strong> ${statusSelect ? statusSelect.options[statusSelect.selectedIndex].text : '{{ $product->status }}'}</div>
            <div class="mb-2"><strong>Negotiable:</strong> ${negotiableCheckbox && negotiableCheckbox.checked ? 'Yes' : 'No'}</div>
        `;
        }

        // Step navigation functions
        function nextStep(currentStep) {
            // Validate current step before proceeding
            if (validateStep(currentStep)) {
                document.getElementById(`step${currentStep}`).classList.remove('active');
                document.getElementById(`step${currentStep + 1}`).classList.add('active');

                // Update progress bar
                const progress = ((currentStep) / 3) * 100;
                document.getElementById('formProgress').style.width = `${progress}%`;

                // Update step indicator
                document.querySelectorAll('.step').forEach(step => {
                    step.classList.remove('active');
                });
                document.querySelector(`.step[data-step="${currentStep + 1}"]`).classList.add('active');

                // Update review section when moving to step 4
                if (currentStep + 1 === 4) {
                    updateReviewSection();
                }
            }
        }

        function prevStep(currentStep) {
            document.getElementById(`step${currentStep}`).classList.remove('active');
            document.getElementById(`step${currentStep - 1}`).classList.add('active');

            // Update progress bar
            const progress = ((currentStep - 2) / 3) * 100;
            document.getElementById('formProgress').style.width = `${progress}%`;

            // Update step indicator
            document.querySelectorAll('.step').forEach(step => {
                step.classList.remove('active');
            });
            document.querySelector(`.step[data-step="${currentStep - 1}"]`).classList.add('active');
        }

        // Step validation function
        function validateStep(step) {
            let isValid = true;

            switch (step) {
                case 1:
                    if (!selectedCategory) {
                        alert('Please select a category for your product.');
                        isValid = false;
                    }
                    break;
                case 2:
                    const title = document.getElementById('productTitle');
                    const price = document.getElementById('productPrice');
                    const description = document.getElementById('productDescription');
                    const condition = document.getElementById('productCondition');
                    const location = document.getElementById('productLocation');
                    const quantity = document.getElementById('quantity');

                    // Reset all error messages
                    document.querySelectorAll('.validation-message').forEach(msg => {
                        msg.style.display = 'none';
                    });

                    if (!title.value.trim()) {
                        showValidationError('titleError', 'Please enter a product title');
                        isValid = false;
                    }
                    if (!price.value || price.value <= 0) {
                        showValidationError('priceError', 'Please enter a valid price');
                        isValid = false;
                    }
                    if (!description.value.trim()) {
                        showValidationError('descriptionError', 'Please enter a product description');
                        isValid = false;
                    }
                    if (!condition.value) {
                        showValidationError('conditionError', 'Please select a condition');
                        isValid = false;
                    }
                    if (!location.value.trim()) {
                        showValidationError('locationError', 'Please enter a location');
                        isValid = false;
                    }
                    if (!quantity.value || quantity.value < 1) {
                        showValidationError('quantityError', 'Please enter a valid quantity');
                        isValid = false;
                    }
                    break;
                case 3:
                    // No validation needed for media step
                    break;
            }

            return isValid;
        }

        function showValidationError(elementId, message) {
            const element = document.getElementById(elementId);
            if (element) {
                element.textContent = message;
                element.style.display = 'block';
            }
        }

        // Initialize event listeners
        function initializeEventListeners() {
            // Category selection
            document.querySelectorAll('.category-card').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.category-card').forEach(c => {
                        c.classList.remove('selected');
                    });
                    this.classList.add('selected');
                    selectedCategory = this.dataset.category;
                    loadSubcategories(selectedCategory);
                });
            });

            // Step navigation buttons
            document.getElementById('nextToStep2').addEventListener('click', () => nextStep(1));
            document.getElementById('nextToStep3').addEventListener('click', () => nextStep(2));
            document.getElementById('nextToStep4').addEventListener('click', () => nextStep(3));

            // Real-time validation for step 2 fields
            const step2Fields = ['productTitle', 'productPrice', 'productDescription', 'productCondition',
                'productLocation', 'quantity'
            ];
            step2Fields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', function() {
                        const errorElement = document.getElementById(fieldId + 'Error');
                        if (errorElement) {
                            errorElement.style.display = 'none';
                        }
                    });

                    // For select elements, use change event
                    if (field.tagName === 'SELECT') {
                        field.addEventListener('change', function() {
                            const errorElement = document.getElementById(fieldId + 'Error');
                            if (errorElement) {
                                errorElement.style.display = 'none';
                            }
                        });
                    }
                }
            });

            // Image upload handling
            const imageUpload = document.getElementById('imageUpload');
            const imagePreview = document.getElementById('imagePreview');

            if (imageUpload) {
                imageUpload.addEventListener('change', function(e) {
                    const files = e.target.files;
                    uploadedImages = [];

                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const previewItem = document.createElement('div');
                                previewItem.className = 'preview-item';
                                previewItem.innerHTML = `
                                <img src="${e.target.result}" alt="Preview">
                                <button type="button" class="remove-image" data-index="${uploadedImages.length}">×</button>
                            `;
                                if (imagePreview) {
                                    imagePreview.appendChild(previewItem);
                                }

                                uploadedImages.push({
                                    file: file,
                                    preview: e.target.result
                                });
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                });
            }

            // Remove uploaded image
            if (imagePreview) {
                imagePreview.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-image')) {
                        const index = parseInt(e.target.dataset.index);
                        uploadedImages.splice(index, 1);
                        e.target.closest('.preview-item').remove();

                        // Update indices for remaining items
                        document.querySelectorAll('.preview-item .remove-image').forEach((btn, i) => {
                            btn.dataset.index = i;
                        });
                    }
                });
            }

            // Drag and drop for images
            const dropzone = document.querySelector('.dropzone');
            if (dropzone) {
                dropzone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.classList.add('dragover');
                });

                dropzone.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    this.classList.remove('dragover');
                });

                dropzone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('dragover');
                    if (imageUpload) {
                        imageUpload.files = e.dataTransfer.files;
                        const event = new Event('change');
                        imageUpload.dispatchEvent(event);
                    }
                });
            }

            // Terms agreement validation
            const agreeTerms = document.getElementById('agreeTerms');
            if (agreeTerms) {
                agreeTerms.addEventListener('change', function() {
                    document.getElementById('termsError').style.display = 'none';
                });
            }
        }

        // Add function to handle removal of current images
        function handleRemoveCurrentImage(imagePath) {
            removedImages.push(imagePath);
            const previewItem = document.querySelector(`.preview-item img[src="${imagePath}"]`)?.closest('.preview-item');
            if (previewItem) {
                previewItem.remove();
            }
        }

        // Load category and specifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Pre-select the category
            const categoryCard = document.querySelector(`.category-card[data-category="${selectedCategory}"]`);
            if (categoryCard) {
                categoryCard.classList.add('selected');
            }

            // Load subcategories and specifications
            loadSubcategories(selectedCategory);

            // Initialize other event listeners
            initializeEventListeners();
        });

        // Update the form submission for edit
        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const agreeTerms = document.getElementById('agreeTerms');
            if (agreeTerms && !agreeTerms.checked) {
                document.getElementById('termsError').style.display = 'block';
                return;
            }

            const formData = new FormData();

            // Add all form fields
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('_method', 'PUT');
            formData.append('category_id', selectedCategory);
            if (selectedSubcategory) {
                formData.append('subcategory_id', selectedSubcategory);
            }
            formData.append('title', document.getElementById('productTitle').value);
            formData.append('price', document.getElementById('productPrice').value);
            formData.append('old_price', document.getElementById('oldPrice').value);
            formData.append('description', document.getElementById('productDescription').value);
            formData.append('condition', document.getElementById('productCondition').value);
            formData.append('location', document.getElementById('productLocation').value);
            formData.append('quantity', document.getElementById('quantity').value);
            formData.append('status', document.getElementById('productStatus').value);
            formData.append('negotiable', document.getElementById('negotiable').checked ? '1' : '0');

            // Add specifications
            Object.keys(specificationFields).forEach(key => {
                const fieldElement = document.getElementById(`spec_${key}`);
                if (fieldElement && fieldElement.value) {
                    formData.append(`specifications[${key}]`, fieldElement.value);
                }
            });

            // Add removed images
            removedImages.forEach(image => {
                formData.append('removed_images[]', image);
            });

            // Add new images
            uploadedImages.forEach((image, index) => {
                formData.append(`images[${index}]`, image.file);
            });

            // Submit form
            const submitBtn = document.getElementById('updateProduct');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Updating...';

            fetch('{{ route('products.update', $product->slug) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('successMessage').style.display = 'block';
                        setTimeout(() => {
                            window.location.href = data.redirect_url ||
                                '{{ route('products.index') }}';
                        }, 2000);
                    } else {
                        alert('Error: ' + (data.message || 'Something went wrong'));
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Update Product';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating your product.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Update Product';
                });
        });

        // Add delete functionality
        document.getElementById('deleteProduct').addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                fetch('{{ route('products.destroy', $product->slug) }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Product deleted successfully!');
                            window.location.href = data.redirect_url || '{{ route('products.index') }}';
                        } else {
                            alert('Error: ' + (data.message || 'Something went wrong'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the product.');
                    });
            }
        });

        // Add event listener for removing current images
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-image') && e.target.dataset.image) {
                handleRemoveCurrentImage(e.target.dataset.image);
            }
        });
    </script>
@endpush
