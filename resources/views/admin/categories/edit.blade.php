@extends('layout.marketer')

@section('title', 'Edit Category - Agii')
@section('page-title', 'Edit Category')

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

            <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                @csrf
                @method('PUT')

                @include('admin.categories.partials.form', ['category' => $category, 'parentOptions' => $parentOptions])

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check me-2"></i>Save Changes
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
