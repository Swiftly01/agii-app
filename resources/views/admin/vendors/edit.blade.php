@extends('layout.marketer')

@section('title', 'Edit ' . $vendor->business_name . ' - Agii')
@section('page-title', 'Edit Vendor')

@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.vendors.update', $vendor->id) }}">
                @csrf
                @method('PUT')

                <h6 class="fw-bold mb-3">Contact Details</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">First Name</label>
                        <input type="text" name="first_name" class="form-control"
                            value="{{ old('first_name', $vendor->first_name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Last Name</label>
                        <input type="text" name="last_name" class="form-control"
                            value="{{ old('last_name', $vendor->last_name) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $vendor->email) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $vendor->phone) }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">WhatsApp Number</label>
                    <input type="text" name="whatsapp_number" class="form-control"
                        value="{{ old('whatsapp_number', $vendor->whatsapp_number) }}">
                </div>

                <h6 class="fw-bold mb-3">Business Details</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Business Name</label>
                        <input type="text" name="business_name" class="form-control"
                            value="{{ old('business_name', $vendor->business_name) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Business Type</label>
                        <input type="text" name="business_type" class="form-control"
                            value="{{ old('business_type', $vendor->business_type) }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Business Category</label>
                    <input type="text" name="business_category" class="form-control"
                        value="{{ old('business_category', $vendor->business_category) }}">
                </div>

                <h6 class="fw-bold mb-3">Location</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">State</label>
                        <input type="text" name="state" class="form-control"
                            value="{{ old('state', $vendor->state) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">City</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city', $vendor->city) }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Address</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $vendor->address) }}</textarea>
                </div>

                <h6 class="fw-bold mb-3">Social Links</h6>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Facebook URL</label>
                    <input type="url" name="facebook_url" class="form-control"
                        value="{{ old('facebook_url', $vendor->facebook_url) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Instagram URL</label>
                    <input type="url" name="instagram_url" class="form-control"
                        value="{{ old('instagram_url', $vendor->instagram_url) }}">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Twitter URL</label>
                    <input type="url" name="twitter_url" class="form-control"
                        value="{{ old('twitter_url', $vendor->twitter_url) }}">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check me-2"></i>Save Changes
                </button>
                <a href="{{ route('admin.vendors.show', $vendor->id) }}" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
