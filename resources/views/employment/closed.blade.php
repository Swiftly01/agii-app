@extends('layout.layout')
@section('title', 'Guarantor Form Closed')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm text-center application-form">
        <div class="card-body p-5">

            <div class="mb-4 text-danger">
                <i class="bi bi-lock-fill" style="font-size: 3rem;"></i>
            </div>

            <h3 class="mb-3">Guarantor Form Closed</h3>

            <p class="text-muted mb-4">
                The required number of guarantors for this employment application
                has already been submitted.
            </p>

            <div class="alert alert-info">
                Thank you for your willingness to support this application.
            </div>

        </div>
    </div>
</div>
@endsection
