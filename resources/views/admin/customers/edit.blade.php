@extends('layout.marketer')

@section('title', 'Edit ' . $customer->first_name . ' ' . $customer->last_name . ' - Agii')
@section('page-title', 'Edit Customer')

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

            <form method="POST" action="{{ route('admin.customers.update', $customer->id) }}">
                @csrf
                @method('PUT')

                <h6 class="fw-bold mb-3">Contact Details</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">First Name</label>
                        <input type="text" name="first_name" class="form-control"
                            value="{{ old('first_name', $customer->first_name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Last Name</label>
                        <input type="text" name="last_name" class="form-control"
                            value="{{ old('last_name', $customer->last_name) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $customer->email) }}" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
                    </div>
                </div>

                <h6 class="fw-bold mb-3">Location</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">State</label>
                        <input type="text" name="state" class="form-control"
                            value="{{ old('state', $customer->state) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">City</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city', $customer->city) }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Address</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $customer->address) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check me-2"></i>Save Changes
                </button>
                <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection