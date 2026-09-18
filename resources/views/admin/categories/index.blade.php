@extends('layout.marketer')

@section('title', 'Manage Categories - Agii')
@section('page-title', 'Category Management')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="card-title mb-0">All Categories</h6>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Category
            </a>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Slug</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            @include('admin.categories.partials.row', ['category' => $category, 'depth' => 0])
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No categories yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function confirmDeleteCategory(id, name) {
            if (confirm(`Delete "${name}"? This will also delete any of its subcategories. This cannot be undone.`)) {
                document.getElementById('deleteCategoryForm' + id).submit();
            }
        }
    </script>
@endsection
