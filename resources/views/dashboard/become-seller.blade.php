@extends('layout.layout')
@section('title', 'Become a Seller - Agii')

@section('content')
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-shop text-success" style="font-size: 2.5rem;"></i>
                            <h2 class="fw-bold mt-2">Become a Seller</h2>
                            <p class="text-muted">
                                You're signed in as {{ $user->first_name }} {{ $user->last_name }}. A few details
                                and you're ready to start posting products.
                            </p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('become-seller.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="businessType" class="form-label fw-semibold">Business Type <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="businessType" name="business_type" required>
                                    <option value="">Select one</option>
                                    <option value="individual" {{ old('business_type') === 'individual' ? 'selected' : '' }}>
                                        Individual Seller
                                    </option>
                                    <option value="company" {{ old('business_type') === 'company' ? 'selected' : '' }}>
                                        Registered Company
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="businessName" class="form-label fw-semibold">Business Name</label>
                                <input type="text" class="form-control" id="businessName" name="business_name"
                                    value="{{ old('business_name') }}" placeholder="e.g. Chidi's Electronics">
                            </div>

                            <div class="mb-3">
                                <label for="businessCategory" class="form-label fw-semibold">What will you mostly
                                    sell?</label>
                                <select class="form-select" id="businessCategory" name="business_category">
                                    <option value="">Select your main category</option>
                                    <option value="electronics"
                                        {{ old('business_category') === 'electronics' ? 'selected' : '' }}>
                                        Electronics</option>
                                    <option value="fashion" {{ old('business_category') === 'fashion' ? 'selected' : '' }}>
                                        Fashion</option>
                                    <option value="home" {{ old('business_category') === 'home' ? 'selected' : '' }}>Home &
                                        Garden</option>
                                    <option value="vehicles" {{ old('business_category') === 'vehicles' ? 'selected' : '' }}>
                                        Vehicles</option>
                                    <option value="properties"
                                        {{ old('business_category') === 'properties' ? 'selected' : '' }}>Properties</option>
                                    <option value="services" {{ old('business_category') === 'services' ? 'selected' : '' }}>
                                        Services</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="whatsappNumber" class="form-label fw-semibold">WhatsApp Number</label>
                                <input type="text" class="form-control" id="whatsappNumber" name="whatsapp_number"
                                    value="{{ old('whatsapp_number', $user->phone) }}"
                                    placeholder="Buyers will use this to reach you">
                                <div class="form-text">Defaults to your account phone number — change it if you'd rather use
                                    a different one for sales.</div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-check-circle me-2"></i>Become a Seller
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary w-100 mt-2">
                                Not right now
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
