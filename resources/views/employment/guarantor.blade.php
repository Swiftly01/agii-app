@extends('layout.layout')
@section('title', 'Agii - Employment Application Form')

@section('content')

    <div class="container py-5">
        <div class="application-form">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <h5><i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix the following errors:</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('guarantor.submit', $application->id) }}">
             @csrf

            <div class="card mb-4 section-card">
                <div class="card-header section-header">
                    <h4 class="mb-0">
                        <i class="bi bi-shield-fill-check me-2"></i>
                        GUARANTOR'S FORM
                    </h4>
                </div>
        
                <div class="card-body">
                    <div class="guarantor-section">
                        <div class="row g-3">
        
                            <div class="col-md-4">
                                <label class="form-label required">Guarantor's Name</label>
                                <input type="text" class="form-control" name="name" required
                                       value="{{ old('name') }}">
                            </div>
        
                            <div class="col-md-4">
                                <label class="form-label required">Residential Address</label>
                                <input type="text" class="form-control" name="address" required
                                       value="{{ old('address') }}">
                            </div>
        
                            <div class="col-md-4">
                                <label class="form-label required">LGA / State</label>
                                <input type="text" class="form-control" name="lga_state" required
                                       value="{{ old('lga_state') }}">
                            </div>
        
                            <div class="col-md-4">
                                <label class="form-label required">Residential Telephone</label>
                                <input type="tel" class="form-control" name="residential_phone" required
                                       value="{{ old('residential_phone') }}">
                            </div>
        
                            <div class="col-md-4">
                                <label class="form-label required">Occupation</label>
                                <input type="text" class="form-control" name="occupation" required
                                       value="{{ old('occupation') }}">
                            </div>
        
                            <div class="col-md-4">
                                <label class="form-label required">Office / Business Address</label>
                                <input type="text" class="form-control" name="office_address" required
                                       value="{{ old('office_address') }}">
                            </div>
        
                            <div class="col-md-4">
                                <label class="form-label required">Designation</label>
                                <input type="text" class="form-control" name="designation" required
                                       value="{{ old('designation') }}">
                            </div>
        
                            <div class="col-md-4">
                                <label class="form-label required">Office Telephone</label>
                                <input type="tel" class="form-control" name="office_phone" required
                                       value="{{ old('office_phone') }}">
                            </div>
        
                            <div class="col-md-2">
                                <label class="form-label required">Years Known</label>
                                <input type="number" class="form-control" name="years_known" min="1" required
                                       value="{{ old('years_known') }}">
                            </div>
        
                            <div class="col-md-2">
                                <label class="form-label required">Relationship</label>
                                <input type="text" class="form-control" name="relationship" required
                                       placeholder="e.g. Uncle, Friend"
                                       value="{{ old('relationship') }}">
                            </div>
        
                            <div class="col-md-4">
                                <label class="form-label required">Signature (Full Name)</label>
                                <input type="text" class="form-control" name="signature" required
                                       value="{{ old('signature') }}">
                            </div>
        
                        </div>
        
                        <button type="submit" class="btn btn-primary mt-4">
                            <i class="bi bi-check-circle me-1"></i>
                            Submit Guarantor Form
                        </button>
        
                    </div>
                </div>
            </div>
        </form>

        
        </div>
    </div>
    
@endsection

@push('styles')
    <style>
        body { background-color: #f8f9fa; }
      
        .guarantor-section { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 15px; }
    </style>
@endpush

@push('scripts')

@endpush

