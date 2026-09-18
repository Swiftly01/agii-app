@extends('layout.marketer')

@section('title', 'Edit User - ' . $user->first_name . ' - Agii')
@section('page-title', 'Edit User: ' . $user->first_name . ' ' . $user->last_name)

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Edit User Information</h6>
                        <div class="d-flex align-items-center">
                            <span
                                class="badge bg-{{ $user->user_type === 'admin' ? 'danger' : ($user->user_type === 'vendor' ? 'success' : ($user->user_type === 'marketer' ? 'purple' : 'warning')) }} me-3">
                                {{ ucfirst($user->user_type) }}
                            </span>
                            <span class="badge bg-{{ $user->email_verified_at ? 'success' : 'warning' }}">
                                {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Personal Information -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-user me-2"></i>Personal Information</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                    id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                                    required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                    id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                    required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Account Information -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-key me-2"></i>Account Information</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="user_type" class="form-label">User Role *</label>
                                <select class="form-select @error('user_type') is-invalid @enderror" id="user_type"
                                    name="user_type" required onchange="toggleBusinessFields()">
                                    <option value="admin"
                                        {{ old('user_type', $user->user_type) == 'admin' ? 'selected' : '' }}>Administrator
                                    </option>
                                    <option value="vendor"
                                        {{ old('user_type', $user->user_type) == 'vendor' ? 'selected' : '' }}>Vendor
                                    </option>
                                    <option value="customer"
                                        {{ old('user_type', $user->user_type) == 'customer' ? 'selected' : '' }}>Customer
                                    </option>
                                    <option value="marketer"
                                        {{ old('user_type', $user->user_type) == 'marketer' ? 'selected' : '' }}>Marketer
                                    </option>
                                </select>
                                @error('user_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Business Information (for vendors) -->
                        <div id="businessFields"
                            style="display: {{ old('user_type', $user->user_type) == 'vendor' ? 'block' : 'none' }};">
                            <h6 class="mb-3 text-primary"><i class="fas fa-store me-2"></i>Business Information</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="business_name" class="form-label">Business Name</label>
                                    <input type="text" class="form-control @error('business_name') is-invalid @enderror"
                                        id="business_name" name="business_name"
                                        value="{{ old('business_name', $user->business_name) }}">
                                    @error('business_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="business_category" class="form-label">Business Category</label>
                                    <select class="form-select @error('business_category') is-invalid @enderror"
                                        id="business_category" name="business_category">
                                        <option value="">Select Category</option>
                                        <option value="fashion"
                                            {{ old('business_category', $user->business_category) == 'fashion' ? 'selected' : '' }}>
                                            Fashion</option>
                                        <option value="electronics"
                                            {{ old('business_category', $user->business_category) == 'electronics' ? 'selected' : '' }}>
                                            Electronics</option>
                                        <option value="home"
                                            {{ old('business_category', $user->business_category) == 'home' ? 'selected' : '' }}>
                                            Home & Living</option>
                                        <option value="beauty"
                                            {{ old('business_category', $user->business_category) == 'beauty' ? 'selected' : '' }}>
                                            Beauty & Health</option>
                                        <option value="food"
                                            {{ old('business_category', $user->business_category) == 'food' ? 'selected' : '' }}>
                                            Food & Drinks</option>
                                        <option value="services"
                                            {{ old('business_category', $user->business_category) == 'services' ? 'selected' : '' }}>
                                            Services</option>
                                    </select>
                                    @error('business_category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="business_type" class="form-label">Business Type</label>
                                    <select class="form-select @error('business_type') is-invalid @enderror"
                                        id="business_type" name="business_type">
                                        <option value="">Select Type</option>
                                        <option value="individual"
                                            {{ old('business_type', $user->business_type) == 'individual' ? 'selected' : '' }}>
                                            Individual</option>
                                        <option value="company"
                                            {{ old('business_type', $user->business_type) == 'company' ? 'selected' : '' }}>
                                            Company</option>
                                        <option value="partnership"
                                            {{ old('business_type', $user->business_type) == 'partnership' ? 'selected' : '' }}>
                                            Partnership</option>
                                    </select>
                                    @error('business_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Location Information -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-map-marker-alt me-2"></i>Location Information</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror"
                                    id="state" name="state" value="{{ old('state', $user->state) }}">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="local_government" class="form-label">Local Government</label>
                                <input type="text" class="form-control @error('local_government') is-invalid @enderror"
                                    id="local_government" name="local_government"
                                    value="{{ old('local_government', $user->local_government) }}">
                                @error('local_government')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror"
                                    id="city" name="city" value="{{ old('city', $user->city) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror"
                                    id="address" name="address" value="{{ old('address', $user->address) }}">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Profile Image -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-image me-2"></i>Profile Image</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <div class="current-image mb-3">
                                    <p class="mb-2">Current Profile Picture:</p>
                                    <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-avatar.png') }}"
                                        alt="{{ $user->first_name }}" class="rounded" width="150" height="150"
                                        style="object-fit: cover;">
                                </div>

                                <div class="mb-3">
                                    <label for="profile_image" class="form-label">Change Profile Picture</label>
                                    <input type="file"
                                        class="form-control @error('profile_image') is-invalid @enderror"
                                        id="profile_image" name="profile_image" accept="image/*">
                                    @error('profile_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Max size: 2MB. Leave empty to keep current image.</small>
                                </div>

                                <div id="imagePreview" class="mt-3" style="display: none;">
                                    <p class="mb-2">New Image Preview:</p>
                                    <img id="previewImage" class="rounded" width="150" height="150"
                                        style="object-fit: cover;">
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-share-alt me-2"></i>Social Media</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="facebook_url" class="form-label">Facebook URL</label>
                                <input type="url" class="form-control @error('facebook_url') is-invalid @enderror"
                                    id="facebook_url" name="facebook_url"
                                    value="{{ old('facebook_url', $user->facebook_url) }}">
                                @error('facebook_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="instagram_url" class="form-label">Instagram URL</label>
                                <input type="url" class="form-control @error('instagram_url') is-invalid @enderror"
                                    id="instagram_url" name="instagram_url"
                                    value="{{ old('instagram_url', $user->instagram_url) }}">
                                @error('instagram_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="twitter_url" class="form-label">Twitter URL</label>
                                <input type="url" class="form-control @error('twitter_url') is-invalid @enderror"
                                    id="twitter_url" name="twitter_url"
                                    value="{{ old('twitter_url', $user->twitter_url) }}">
                                @error('twitter_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="whatsapp_number" class="form-label">WhatsApp Number</label>
                                <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror"
                                    id="whatsapp_number" name="whatsapp_number"
                                    value="{{ old('whatsapp_number', $user->whatsapp_number) }}">
                                @error('whatsapp_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Newsletter Subscription -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="newsletter_subscribed"
                                    name="newsletter_subscribed" value="1"
                                    {{ old('newsletter_subscribed', $user->newsletter_subscribed) ? 'checked' : '' }}>
                                <label class="form-check-label" for="newsletter_subscribed">
                                    Subscribe to newsletter
                                </label>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">
                                    <i class="fas fa-arrow-left me-2"></i>Cancel
                                </a>
                                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                    <i class="fas fa-trash me-2"></i>Delete User
                                </button>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update User
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Delete Form -->
                    <form id="deleteForm" action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                        class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Toggle business fields based on user type
        function toggleBusinessFields() {
            const userType = document.getElementById('user_type').value;
            const businessFields = document.getElementById('businessFields');

            if (userType === 'vendor') {
                businessFields.style.display = 'block';

                // Make business name required for vendors
                document.getElementById('business_name').required = true;
                document.getElementById('business_category').required = true;
            } else {
                businessFields.style.display = 'none';

                // Remove required attribute for non-vendors
                document.getElementById('business_name').required = false;
                document.getElementById('business_category').required = false;
            }
        }

        // Profile image preview
        document.getElementById('profile_image').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const previewImage = document.getElementById('previewImage');

            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(this.files[0]);
            } else {
                preview.style.display = 'none';
            }
        });

        // Confirm delete
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                document.getElementById('deleteForm').submit();
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleBusinessFields();
        });
    </script>
@endpush
