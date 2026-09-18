@extends('layout.layout')
@section('title', 'Thank You')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm text-center application-form">
        <div class="card-body p-5">

            <div class="mb-4 text-success">
                <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
            </div>

            <h3 class="mb-3">Thank You!</h3>

            <p class="text-muted mb-4">
                Your guarantor information has been successfully submitted.
            </p>

            <div class="alert alert-success">
                No further action is required from you.
            </div>

        </div>
    </div>
</div>
@endsection
