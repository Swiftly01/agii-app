@extends('layout.layout')
@section('title', 'Product Details - ' . $product->title)
@section('content')

    <section class="py-5">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="display-5 fw-bold">Product Details</h1>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary" id="toggleEditBtn">
                                <i class="bi bi-pencil"></i> Edit Mode
                            </button>
                            <a href="{{ route('marketer.vendors.show', $vendor->id) }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left"></i> Back to Vendor
                            </a>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    <div id="messageAlert" style="display: none;" class="alert alert-dismissible fade show">
                        <span id="messageText"></span>
                        <button type="button" class="btn-close" onclick="hideMessage()"></button>
                    </div>

                    <form id="productForm" action="{{ route('marketer.products.update', $product->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">

                        <div class="row">
                            <!-- Left Column - Product Images -->
                            <div class="col-md-5">
                                <div class="card shadow-sm mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">Product Images</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Current Images -->
                                        <div class="current-images mb-4">
                                            <h6>Current Images</h6>
                                            <div class="row g-2" id="currentImages">
                                                @foreach ($product->images as $index => $image)
                                                    <div class="col-4">
                                                        <div class="image-thumbnail position-relative">
                                                            <img src="{{ asset($image) }}"
                                                                alt="Product Image {{ $index + 1 }}"
                                                                class="img-fluid rounded"
                                                                style="height: 100px; object-fit: cover; width: 100%;">
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-existing-image"
                                                                data-image-index="{{ $index }}"
                                                                style="display: none;">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- New Image Upload (Edit Mode Only) -->
                                        <div class="new-images-upload" style="display: none;">
                                            <h6>Add New Images</h6>
                                            <div class="dropzone-container mb-3">
                                                <input type="file" id="imageUpload" name="new_images[]" multiple
                                                    accept="image/*" style="display: none;">
                                                <div class="dropzone"
                                                    onclick="document.getElementById('imageUpload').click()">
                                                    <div class="dz-message text-center py-4">
                                                        <i class="bi bi-cloud-upload display-4 text-muted"></i>
                                                        <h5>Click to upload new images</h5>
                                                        <p class="text-muted">Upload additional photos (max 5MB each)</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="image-preview row g-2" id="newImagePreview">
                                                <!-- New image previews will be shown here -->
                                            </div>
                                        </div>

                                        <!-- Images to Remove (Edit Mode Only) -->
                                        <div id="imagesToRemove" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column - Product Details -->
                            <div class="col-md-7">
                                <!-- Basic Information Card -->
                                <div class="card shadow-sm mb-4">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">Basic Information</h5>
                                        <span
                                            class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Product Title</label>
                                                    <input type="text" class="form-control view-mode"
                                                        value="{{ $product->title }}" readonly>
                                                    <input type="text" class="form-control edit-mode" name="title"
                                                        value="{{ $product->title }}" style="display: none;">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Category</label>
                                                    <input type="text" class="form-control view-mode"
                                                        value="{{ $product->category->name }}" readonly>
                                                    <select class="form-select edit-mode" name="category_id"
                                                        style="display: none;">
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}"
                                                                {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Price (₦)</label>
                                                    <input type="text" class="form-control view-mode"
                                                        value="{{ number_format($product->price, 2) }}" readonly>
                                                    <input type="number" class="form-control edit-mode" name="price"
                                                        value="{{ $product->price }}" step="0.01"
                                                        style="display: none;">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Old Price (₦)</label>
                                                    <input type="text" class="form-control view-mode"
                                                        value="{{ $product->old_price ? number_format($product->old_price, 2) : 'N/A' }}"
                                                        readonly>
                                                    <input type="number" class="form-control edit-mode" name="old_price"
                                                        value="{{ $product->old_price }}" step="0.01"
                                                        style="display: none;">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Description</label>
                                            <textarea class="form-control view-mode" rows="4" readonly>{{ $product->description }}</textarea>
                                            <textarea class="form-control edit-mode" name="description" rows="4" style="display: none;">{{ $product->description }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Details Card -->
                                <div class="card shadow-sm mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">Additional Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Condition</label>
                                                    <input type="text" class="form-control view-mode"
                                                        value="{{ ucfirst(str_replace('_', ' ', $product->condition)) }}"
                                                        readonly>
                                                    <select class="form-select edit-mode" name="condition"
                                                        style="display: none;">
                                                        <option value="new"
                                                            {{ $product->condition == 'new' ? 'selected' : '' }}>Brand New
                                                        </option>
                                                        <option value="used_like_new"
                                                            {{ $product->condition == 'used_like_new' ? 'selected' : '' }}>
                                                            Used - Like New</option>
                                                        <option value="used_good"
                                                            {{ $product->condition == 'used_good' ? 'selected' : '' }}>Used
                                                            - Good</option>
                                                        <option value="used_fair"
                                                            {{ $product->condition == 'used_fair' ? 'selected' : '' }}>Used
                                                            - Fair</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Quantity</label>
                                                    <input type="text" class="form-control view-mode"
                                                        value="{{ $product->quantity }}" readonly>
                                                    <input type="number" class="form-control edit-mode" name="quantity"
                                                        value="{{ $product->quantity }}" style="display: none;">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Location</label>
                                                    <input type="text" class="form-control view-mode"
                                                        value="{{ $product->location }}" readonly>
                                                    <input type="text" class="form-control edit-mode" name="location"
                                                        value="{{ $product->location }}" style="display: none;">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input view-mode" type="checkbox"
                                                    {{ $product->negotiable ? 'checked' : '' }} disabled>
                                                <input class="form-check-input edit-mode" type="checkbox"
                                                    name="negotiable" value="1"
                                                    {{ $product->negotiable ? 'checked' : '' }} style="display: none;">
                                                <label class="form-check-label">Price is negotiable</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Specifications Card -->
                                @if ($product->specifications && count($product->specifications) > 0)
                                    <div class="card shadow-sm mb-4">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0">Specifications</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="specificationsContainer">
                                                @foreach ($product->specifications as $key => $value)
                                                    @if (!empty($value))
                                                        <div class="row mb-2 specification-item">
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control view-mode"
                                                                    value="{{ ucfirst(str_replace('_', ' ', $key)) }}"
                                                                    readonly>
                                                                <input type="text" class="form-control edit-mode"
                                                                    name="specifications_keys[]"
                                                                    value="{{ $key }}" style="display: none;"
                                                                    placeholder="Specification key">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control view-mode"
                                                                    value="{{ $value }}" readonly>
                                                                <input type="text" class="form-control edit-mode"
                                                                    name="specifications_values[]"
                                                                    value="{{ $value }}" style="display: none;"
                                                                    placeholder="Specification value">
                                                            </div>
                                                            <div class="col-md-2 edit-mode" style="display: none;">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-danger remove-specification">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="edit-mode" style="display: none;">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    id="addSpecification">
                                                    <i class="bi bi-plus"></i> Add Specification
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Action Buttons (Edit Mode Only) -->
                                <div class="card edit-mode" style="display: none;">
                                    <div class="card-body">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button type="button" class="btn btn-outline-secondary" id="cancelEdit">
                                                Cancel
                                            </button>
                                            <button type="submit" class="btn btn-primary" id="updateProduct">
                                                <i class="bi bi-check-lg"></i> Update Product
                                            </button>
                                        </div>
                                    </div>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .card {
            border: none;
            border-radius: 12px;
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
            border-bottom: 1px solid #dee2e6;
        }

        .dropzone {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .dropzone:hover {
            border-color: #8fc74a;
            background: #f0f7e6;
        }

        .image-thumbnail {
            position: relative;
            transition: all 0.3s ease;
        }

        .image-thumbnail:hover .remove-existing-image {
            display: block !important;
        }

        .view-mode,
        .edit-mode {
            transition: all 0.3s ease;
        }

        .form-control:read-only {
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }

        .badge {
            font-size: 0.75em;
        }

        .specification-item {
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let isEditMode = false;
        let removedImageIndexes = [];
        let newImages = [];

        // Toggle Edit Mode
        document.getElementById('toggleEditBtn').addEventListener('click', function() {
            isEditMode = !isEditMode;
            toggleEditMode();
        });

        function toggleEditMode() {
            const viewElements = document.querySelectorAll('.view-mode');
            const editElements = document.querySelectorAll('.edit-mode');
            const toggleBtn = document.getElementById('toggleEditBtn');

            if (isEditMode) {
                // Switch to Edit Mode
                viewElements.forEach(el => el.style.display = 'none');
                editElements.forEach(el => el.style.display = 'block');
                toggleBtn.innerHTML = '<i class="bi bi-eye"></i> View Mode';
                toggleBtn.classList.remove('btn-outline-secondary');
                toggleBtn.classList.add('btn-warning');

                // Show image remove buttons and new image upload
                document.querySelectorAll('.remove-existing-image').forEach(btn => btn.style.display = 'block');
                document.querySelector('.new-images-upload').style.display = 'block';
            } else {
                // Switch to View Mode
                viewElements.forEach(el => el.style.display = 'block');
                editElements.forEach(el => el.style.display = 'none');
                toggleBtn.innerHTML = '<i class="bi bi-pencil"></i> Edit Mode';
                toggleBtn.classList.remove('btn-warning');
                toggleBtn.classList.add('btn-outline-secondary');

                // Hide image remove buttons and new image upload
                document.querySelectorAll('.remove-existing-image').forEach(btn => btn.style.display = 'none');
                document.querySelector('.new-images-upload').style.display = 'none';
            }
        }

        // Cancel Edit
        document.getElementById('cancelEdit').addEventListener('click', function() {
            isEditMode = false;
            toggleEditMode();
            resetForm();
        });

        function resetForm() {
            removedImageIndexes = [];
            newImages = [];
            document.getElementById('newImagePreview').innerHTML = '';
            document.getElementById('imagesToRemove').innerHTML = '';
            showMessage('Changes discarded', 'warning');
        }

        // Image Handling
        document.getElementById('imageUpload').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);

            files.forEach(file => {
                if (file.size > 5 * 1024 * 1024) {
                    showMessage('File size too large: ' + file.name, 'danger');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    newImages.push({
                        id: Date.now() + Math.random(),
                        name: file.name,
                        dataUrl: e.target.result,
                        file: file
                    });
                    updateNewImagePreview();
                };
                reader.readAsDataURL(file);
            });

            this.value = '';
        });

        function updateNewImagePreview() {
            const preview = document.getElementById('newImagePreview');
            preview.innerHTML = '';

            newImages.forEach((image, index) => {
                const col = document.createElement('div');
                col.className = 'col-4';
                col.innerHTML = `
                <div class="image-thumbnail position-relative">
                    <img src="${image.dataUrl}" alt="New Image ${index + 1}"
                         class="img-fluid rounded" style="height: 100px; object-fit: cover; width: 100%;">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-new-image"
                            data-image-index="${index}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
                preview.appendChild(col);
            });

            document.querySelectorAll('.remove-new-image').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-image-index'));
                    newImages.splice(index, 1);
                    updateNewImagePreview();
                });
            });
        }

        // Remove existing image
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-existing-image')) {
                const btn = e.target.closest('.remove-existing-image');
                const index = parseInt(btn.getAttribute('data-image-index'));

                removedImageIndexes.push(index);
                btn.closest('.col-4').style.opacity = '0.3';
                btn.disabled = true;

                // Add hidden input for removed images
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'removed_images[]';
                hiddenInput.value = index;
                document.getElementById('imagesToRemove').appendChild(hiddenInput);
            }
        });

        // Specifications Management
        document.getElementById('addSpecification').addEventListener('click', function() {
            const container = document.getElementById('specificationsContainer');
            const newItem = document.createElement('div');
            newItem.className = 'row mb-2 specification-item';
            newItem.innerHTML = `
            <div class="col-md-4">
                <input type="text" class="form-control edit-mode" name="specifications_keys[]" placeholder="Specification key">
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control edit-mode" name="specifications_values[]" placeholder="Specification value">
            </div>
            <div class="col-md-2 edit-mode">
                <button type="button" class="btn btn-sm btn-outline-danger remove-specification">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
            container.appendChild(newItem);
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-specification')) {
                e.target.closest('.specification-item').remove();
            }
        });

        // Form Submission
        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const productId = {{ $product->id }};

            // Add new images to form data
            newImages.forEach((image, index) => {
                formData.append(`new_images[${index}]`, image.file);
            });

            const submitBtn = document.getElementById('updateProduct');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Updating...';

            fetch('{{ route('marketer.products.update', $product->id) }}', {
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
                        showMessage(data.message, 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        let errorMessage = data.message || 'Unknown error occurred';
                        if (data.errors) {
                            const errorMessages = [];
                            for (const field in data.errors) {
                                errorMessages.push(data.errors[field].join(', '));
                            }
                            errorMessage = errorMessages.join('\n');
                        }
                        showMessage(errorMessage, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('An error occurred while updating the product. Please try again.', 'danger');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Update Product';
                });
        });

        function showMessage(message, type) {
            const alert = document.getElementById('messageAlert');
            const messageText = document.getElementById('messageText');

            alert.className = `alert alert-${type} alert-dismissible fade show`;
            messageText.textContent = message;
            alert.style.display = 'block';

            // Auto hide after 5 seconds
            setTimeout(hideMessage, 5000);
        }

        function hideMessage() {
            document.getElementById('messageAlert').style.display = 'none';
        }
    </script>
@endpush
