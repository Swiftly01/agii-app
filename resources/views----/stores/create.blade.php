@extends('layout.layout')
@section('title', 'Vendor Dashboard')
@section('content')

    <!-- resources/views/stores/create.blade.php -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Create Your Store</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('stores.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="store_name" class="form-label">Store Name *</label>
                                <input type="text" class="form-control" id="store_name" name="store_name" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Store Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="logo" class="form-label">Store Logo</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                            </div>

                            <div class="mb-3">
                                <label for="banner" class="form-label">Store Banner</label>
                                <input type="file" class="form-control" id="banner" name="banner" accept="image/*">
                            </div>

                            <div class="mb-3">
                                <label for="store_phone" class="form-label">Store Phone (Optional)</label>
                                <input type="text" class="form-control" id="store_phone" name="store_phone">
                            </div>

                            <button type="submit" class="btn btn-primary">Create Store</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
