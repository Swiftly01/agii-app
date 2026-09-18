@extends('layout.layout')
@section('title', 'Edit Store - Vendor Dashboard')
@section('content')

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Your Store</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('stores.update', $store->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="store_name" class="form-label">Store Name *</label>
                                <input type="text" class="form-control @error('store_name') is-invalid @enderror"
                                    id="store_name" name="store_name" value="{{ old('store_name', $store->store_name) }}"
                                    required>
                                @error('store_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Store Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description', $store->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Logo Preview -->
                            <div class="mb-3">
                                <label for="logo" class="form-label">Store Logo</label>
                                @if ($store->logo)
                                    <div class="mb-2">
                                        <p class="text-muted small">Current Logo:</p>
                                        <img src="{{ asset($store->logo) }}" alt="Store Logo" class="img-thumbnail"
                                            style="max-height: 100px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                    id="logo" name="logo" accept="image/*">
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Leave empty to keep current logo</div>
                            </div>

                            <!-- Banner Preview -->
                            <div class="mb-3">
                                <label for="banner" class="form-label">Store Banner</label>
                                @if ($store->banner)
                                    <div class="mb-2">
                                        <p class="text-muted small">Current Banner:</p>
                                        <img src="{{ asset($store->banner) }}" alt="Store Banner" class="img-thumbnail"
                                            style="max-height: 150px; width: 100%; object-fit: cover;">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('banner') is-invalid @enderror"
                                    id="banner" name="banner" accept="image/*">
                                @error('banner')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Leave empty to keep current banner</div>
                            </div>

                            <div class="mb-3">
                                <label for="store_phone" class="form-label">Store Phone (Optional)</label>
                                <input type="text" class="form-control @error('store_phone') is-invalid @enderror"
                                    id="store_phone" name="store_phone"
                                    value="{{ old('store_phone', $store->store_phone) }}">
                                @error('store_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Update Store</button>
                                <a href="{{ route('stores.show', $store->id) }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
