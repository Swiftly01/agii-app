@extends('layout.marketer')

@section('title', 'Create New User - Agii')
@section('page-title', 'Create New User')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">User Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Personal Information -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-user me-2"></i>Personal Information</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                    id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                    id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Account Information -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-key me-2"></i>Account Information</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimum 8 characters</small>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required>
                            </div>

                            <div class="col-md-12">
                                <label for="user_type" class="form-label">User Role *</label>
                                <select class="form-select @error('user_type') is-invalid @enderror" id="user_type"
                                    name="user_type" required onchange="toggleBusinessFields()">
                                    <option value="">Select Role</option>
                                    <option value="admin" {{ old('user_type') == 'admin' ? 'selected' : '' }}>
                                        Administrator</option>
                                    <option value="vendor" {{ old('user_type') == 'vendor' ? 'selected' : '' }}>Vendor
                                    </option>
                                    <option value="customer" {{ old('user_type') == 'customer' ? 'selected' : '' }}>
                                        Customer</option>
                                    <option value="marketer" {{ old('user_type') == 'marketer' ? 'selected' : '' }}>
                                        Marketer</option>
                                </select>
                                @error('user_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Business Information (for vendors) -->
                        <div id="businessFields" style="display: {{ old('user_type') == 'vendor' ? 'block' : 'none' }};">
                            <h6 class="mb-3 text-primary"><i class="fas fa-store me-2"></i>Business Information</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="business_name" class="form-label">Business Name</label>
                                    <input type="text" class="form-control @error('business_name') is-invalid @enderror"
                                        id="business_name" name="business_name" value="{{ old('business_name') }}">
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
                                            {{ old('business_category') == 'fashion' ? 'selected' : '' }}>Fashion</option>
                                        <option value="electronics"
                                            {{ old('business_category') == 'electronics' ? 'selected' : '' }}>Electronics
                                        </option>
                                        <option value="home" {{ old('business_category') == 'home' ? 'selected' : '' }}>
                                            Home & Living</option>
                                        <option value="beauty"
                                            {{ old('business_category') == 'beauty' ? 'selected' : '' }}>Beauty & Health
                                        </option>
                                        <option value="food" {{ old('business_category') == 'food' ? 'selected' : '' }}>
                                            Food & Drinks</option>
                                        <option value="services"
                                            {{ old('business_category') == 'services' ? 'selected' : '' }}>Services
                                        </option>
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
                                            {{ old('business_type') == 'individual' ? 'selected' : '' }}>Individual
                                        </option>
                                        <option value="company" {{ old('business_type') == 'company' ? 'selected' : '' }}>
                                            Company</option>
                                        <option value="partnership"
                                            {{ old('business_type') == 'partnership' ? 'selected' : '' }}>Partnership
                                        </option>
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
                                    id="state" name="state" value="{{ old('state') }}">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="local_government" class="form-label">Local Government</label>
                                <input type="text"
                                    class="form-control @error('local_government') is-invalid @enderror"
                                    id="local_government" name="local_government" value="{{ old('local_government') }}">
                                @error('local_government')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror"
                                    id="city" name="city" value="{{ old('city') }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror"
                                    id="address" name="address" value="{{ old('address') }}">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Profile Image -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-image me-2"></i>Profile Image</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="profile_image" class="form-label">Profile Picture</label>
                                    <input type="file"
                                        class="form-control @error('profile_image') is-invalid @enderror"
                                        id="profile_image" name="profile_image" accept="image/*">
                                    @error('profile_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Max size: 2MB. Supported formats: JPG, PNG, GIF</small>
                                </div>

                                <div id="imagePreview" class="mt-3" style="display: none;">
                                    <img id="previewImage" class="rounded" width="150" height="150"
                                        style="object-fit: cover;">
                                </div>
                            </div>
                        </div>

                        <!-- Social Media (Optional) -->
                        <h6 class="mb-3 text-primary"><i class="fas fa-share-alt me-2"></i>Social Media (Optional)</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="facebook_url" class="form-label">Facebook URL</label>
                                <input type="url" class="form-control @error('facebook_url') is-invalid @enderror"
                                    id="facebook_url" name="facebook_url" value="{{ old('facebook_url') }}">
                                @error('facebook_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="instagram_url" class="form-label">Instagram URL</label>
                                <input type="url" class="form-control @error('instagram_url') is-invalid @enderror"
                                    id="instagram_url" name="instagram_url" value="{{ old('instagram_url') }}">
                                @error('instagram_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="twitter_url" class="form-label">Twitter URL</label>
                                <input type="url" class="form-control @error('twitter_url') is-invalid @enderror"
                                    id="twitter_url" name="twitter_url" value="{{ old('twitter_url') }}">
                                @error('twitter_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="whatsapp_number" class="form-label">WhatsApp Number</label>
                                <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror"
                                    id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number') }}">
                                @error('whatsapp_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input @error('terms_accepted') is-invalid @enderror"
                                    type="checkbox" id="terms_accepted" name="terms_accepted" value="1" checked>
                                <label class="form-check-label" for="terms_accepted">
                                    I agree to the <a href="#" target="_blank">Terms and Conditions</a>
                                </label>
                                @error('terms_accepted')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create User
                            </button>
                        </div>
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

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleBusinessFields();
        });
    </script>
@endpush
