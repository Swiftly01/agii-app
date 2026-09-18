@extends('layout.layout')
@section('title', 'Agii Contact Us- Buy and Sell Anything in Nigeria')

@section('content')

<div class="container mt-5">
    <h2 class="text-center mb-4 display-4 fw-bold">Frequently Asked Questions</h2>
    <div class="accordion" id="faqAccordion">

        <!-- General Questions -->
        <h4 class="mt-4 fs-3 fw-bold">General Questions</h4>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button fs-4 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                    What is Agii.ng Nigeria?
                </button>
            </h2>
            <div id="faq1" class="accordion-collapse collapse show">
                <div class="accordion-body fs-5">
                    Agii.ng Nigeria is an online marketplace that connects users with reliable service providers and professionals across various industries.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                    How does Agii.ng work?
                </button>
            </h2>
            <div id="faq2" class="accordion-collapse collapse">
                <div class="accordion-body fs-5">
                    Simply sign up, search for the service or professional you need, and connect with them directly. If you are a service provider, you can list your services and get customers.
                </div>
            </div>
        </div>

        <!-- For Users (Customers) -->
        <h4 class="mt-4 fs-3 fw-bold">For Users (Customers)</h4>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                    How do I find a service provider?
                </button>
            </h2>
            <div id="faq5" class="accordion-collapse collapse">
                <div class="accordion-body fs-5">
                    Just search for the service you need, filter by location, and choose a verified professional that suits your needs.
                </div>
            </div>
        </div>

        <!-- For Service Providers -->
        <h4 class="mt-4 fs-3 fw-bold">For Service Providers</h4>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq9">
                    How do I list my services on Agii.ng?
                </button>
            </h2>
            <div id="faq9" class="accordion-collapse collapse">
                <div class="accordion-body fs-5">
                    Sign up, create a professional profile, and list your services with detailed information and pricing.
                </div>
            </div>
        </div>

        <!-- Security & Support -->
        <h4 class="mt-4 fs-3 fw-bold">Security & Support</h4>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq13">
                    How do I report a fake service provider or scam?
                </button>
            </h2>
            <div id="faq13" class="accordion-collapse collapse">
                <div class="accordion-body fs-5">
                    Use the “Report” button on their profile or contact Agii.ng support directly.
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('styles')
 
@endpush

@push('scripts')
    
@endpush
