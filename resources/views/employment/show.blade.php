@extends('layout.layout')
@section('title', 'Application Details - ' . $application->full_name)

@section('content')
<div class="container-fluid px-4">
   



    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.employment.index') }}">Applications</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $application->full_name }}</li>
                </ol>
            </nav>
            <h1 class="h2 mb-0">
                <i class="fas fa-user-tie me-2 text-primary"></i>Application Details
            </h1>
            <p class="text-muted mb-0">View and manage application #{{ $application->application_reference }}</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Print
            </button>
            <div class="dropdown d-none">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-cog me-2"></i>Actions
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i class="fas fa-edit me-2"></i>Change Status
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.employment.download', ['application' => $application, 'type' => 'resume']) }}">
                        <i class="fas fa-download me-2"></i>Download Resume
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.employment.download', ['application' => $application, 'type' => 'passport']) }}">
                        <i class="fas fa-image me-2"></i>Download Photo
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-2"></i>Delete Application
                    </a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Application Header Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        @if($application->passport_photo)
                            <img src="{{ asset(  $application->passport_photo) }}" 
                                 alt="Applicant Photo" class="rounded-circle me-4" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-4" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-2x text-muted"></i>
                            </div>
                        @endif
                        <div>
                            <h2 class="h4 mb-1">{{ $application->full_name }}</h2>
                            <p class="text-muted mb-2">
                                <i class="fas fa-briefcase me-1"></i>{{ $application->position_applying_for }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-phone me-1"></i>{{ $application->contact_number }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $application->state_of_origin }}
                            </p>
                            <div class="d-flex align-items-center">
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'under_review' => 'info',
                                        'shortlisted' => 'primary',
                                        'rejected' => 'danger',
                                        'hired' => 'success'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$application->status] }} fs-6 me-3">
                                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                </span>
                                <span class="badge bg-light text-dark border">
                                    <i class="fas fa-hashtag me-1"></i>{{ $application->application_reference }}
                                </span>
                                <span class="badge bg-light text-dark border ms-2">
                                    <i class="fas fa-calendar me-1"></i>{{ $application->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="mb-2">
                        <span class="text-muted">Age:</span>
                        <strong>{{ $application->age ?? \Carbon\Carbon::parse($application->date_of_birth)->age }} years</strong>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted">Qualification:</span>
                        <strong>{{ $application->educational_qualification }}</strong>
                    </div>
                    <div>
                        <span class="text-muted">Application ID:</span>
                        <strong>#{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Personal & Application Details -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Full Name</label>
                            <p class="mb-0 fw-bold">{{ $application->surname }} {{ $application->first_name }} {{ $application->other_name ?? '' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Gender</label>
                            <p class="mb-0 fw-bold text-capitalize">{{ $application->sex }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Date of Birth</label>
                            <p class="mb-0 fw-bold">{{ $application->date_of_birth->format('F j, Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Marital Status</label>
                            <p class="mb-0 fw-bold text-capitalize">{{ $application->marital_status }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">State of Origin</label>
                            <p class="mb-0 fw-bold">{{ $application->state_of_origin }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">L.G.A.</label>
                            <p class="mb-0 fw-bold">{{ $application->lga }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted small mb-1">Contact Number</label>
                            <p class="mb-0 fw-bold">{{ $application->contact_number }}</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small mb-1">Residential Address</label>
                            <p class="mb-0 fw-bold">{{ $application->residential_address }}</p>
                            <p class="text-muted small mb-0">{{ $application->residential_lga_state }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next of Kin -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Next of Kin</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Name</label>
                            <p class="mb-0 fw-bold">{{ $application->next_of_kin_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Relationship</label>
                            <p class="mb-0 fw-bold">{{ $application->next_of_kin_relationship }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Contact Number</label>
                            <p class="mb-0 fw-bold">{{ $application->next_of_kin_contact }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted small mb-1">Residential Address</label>
                            <p class="mb-0 fw-bold">{{ $application->next_of_kin_address }}</p>
                        </div>
                        @if($application->parent_guardian_name)
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Parent/Guardian Name</label>
                            <p class="mb-0 fw-bold">{{ $application->parent_guardian_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Parent/Guardian Address</label>
                            <p class="mb-0 fw-bold">{{ $application->parent_guardian_address }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Educational Qualifications -->
            @php
                $educationalQualifications = $application->educational_qualifications ?? [];
            @endphp

            @if(!empty($educationalQualifications))
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Educational Qualifications</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Institution</th>
                                    <th>Qualification</th>
                                    <th>Date Obtained</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($educationalQualifications as $qualification)
                                <tr>
                                    <td>
                                        <strong>{{ $qualification['institution'] ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $qualification['qualification'] ?? 'N/A' }}</td>
                                    <td>
                                        @if(isset($qualification['date_obtained']))
                                            {{ \Carbon\Carbon::parse($qualification['date_obtained'])->format('M Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Professional Qualifications -->
            @php
                $professionalQualifications = $application->professional_qualifications ?? [];
            @endphp

            @if(!empty($professionalQualifications))
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-award me-2"></i>Professional Qualifications</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Professional Body</th>
                                    <th>Grade of Membership</th>
                                    <th>Year Obtained</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($professionalQualifications as $qualification)
                                <tr>
                                    <td>
                                        <strong>{{ $qualification['professional_body'] ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $qualification['membership_grade'] ?? 'N/A' }}</td>
                                    <td>
                                        @if(isset($qualification['year_obtained']))
                                            {{ \Carbon\Carbon::parse($qualification['year_obtained'])->format('Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
          @else
                <p>No professional qualifications provided.</p>
            @endif

            <!-- Employment History -->
          @php
                $employmentHistory = $application->employment_history ?? [];
            @endphp

            @if(!empty($employmentHistory))
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Employment History</h5>
                </div>
                <div class="card-body">
                    @foreach($employmentHistory as $index => $employment)
                    <div class="card mb-3 border">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="fw-bold mb-1">{{ $employment['position'] ?? 'N/A' }}</h6>
                                    <p class="text-primary mb-1">
                                        <i class="fas fa-building me-1"></i>{{ $employment['organization'] ?? 'N/A' }}
                                    </p>
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $employment['address'] ?? 'N/A' }}
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-phone me-1"></i>{{ $employment['phone'] ?? 'N/A' }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <span class="badge bg-light text-dark border">
                                        @if(isset($employment['from_date']) && isset($employment['to_date']))
                                            {{ \Carbon\Carbon::parse($employment['from_date'])->format('M Y') }} - 
                                            {{ \Carbon\Carbon::parse($employment['to_date'])->format('M Y') }}
                                            @php
                                                $from = \Carbon\Carbon::parse($employment['from_date']);
                                                $to = \Carbon\Carbon::parse($employment['to_date']);
                                                $duration = $from->diffInMonths($to);
                                            @endphp
                                            <br>
                                            <small class="text-muted">({{ floor($duration/12) }} yrs {{ $duration%12 }} mos)</small>
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Guarantors -->
            @php
                $guarantors = $application->guarantors ?? [];
            @endphp


            @if(!empty($guarantors))
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i>Guarantors ({{ count($guarantors) }})</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($guarantors as $index => $guarantor)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0">Guarantor #{{ $index + 1 }}</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm mb-0">
                                        <tr>
                                            <th width="40%">Name:</th>
                                            <td>{{ $guarantor['name'] ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Address:</th>
                                            <td>{{ $guarantor['address'] ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>LGA/State:</th>
                                            <td>{{ $guarantor['lga_state'] ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <td>{{ $guarantor['residential_phone'] ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Occupation:</th>
                                            <td>{{ $guarantor['occupation'] ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Office Address:</th>
                                            <td>{{ $guarantor['office_address'] ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Designation:</th>
                                            <td>{{ $guarantor['designation'] ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Office Phone:</th>
                                            <td>{{ $guarantor['office_phone'] ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Years Known:</th>
                                            <td>{{ $guarantor['years_known'] ?? 'N/A' }} years</td>
                                        </tr>
                                        <tr>
                                            <th>Relationship:</th>
                                            <td>{{ $guarantor['relationship'] ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Signature:</th>
                                            <td class="font-italic"><img src="{{ url($guarantor['signature'] ?? 'N/A') }}"  alt='signature'></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            
            
            @if($staffProfile)
<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">
            <i class="fas fa-briefcase me-2"></i>Employment Information
        </h5>
    </div>

    <div class="card-body">
        <div class="row">

            @if(!empty($staffProfile->staff_id))
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Staff ID</label>
                <p class="mb-0 fw-bold">{{ $staffProfile->staff_id }}</p>
            </div>
            @endif

            @if(!empty($staffProfile->department))
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Department</label>
                <p class="mb-0 fw-bold">{{ $staffProfile->department }}</p>
            </div>
            @endif

            @if(!empty($staffProfile->designation))
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Designation</label>
                <p class="mb-0 fw-bold">{{ $staffProfile->designation }}</p>
            </div>
            @endif

            @if(!empty($staffProfile->date_of_employment))
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Date of Employment</label>
                <p class="mb-0 fw-bold">
                    {{ \Carbon\Carbon::parse($staffProfile->date_of_employment)->format('F j, Y') }}
                </p>
            </div>
            @endif

            @if(!empty($staffProfile->employment_type))
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Employment Type</label>
                <p class="mb-0 fw-bold text-capitalize">
                    {{ str_replace('_', ' ', $staffProfile->employment_type) }}
                </p>
            </div>
            @endif

            @if(!empty($staffProfile->salary))
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Salary</label>
                <p class="mb-0 fw-bold">
                    ₦{{ number_format($staffProfile->salary, 2) }}
                </p>
            </div>
            @endif

            @if(!empty($staffProfile->next_of_review))
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Next Review Date</label>
                <p class="mb-0 fw-bold">
                    {{ \Carbon\Carbon::parse($staffProfile->next_of_review)->format('F j, Y') }}
                </p>
            </div>
            @endif

            @if(!empty($staffProfile->status))
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted small mb-1">Status</label>
                <p class="mb-0 fw-bold text-capitalize">
                    {{ $staffProfile->status }}
                </p>
            </div>
            @endif

            @if(!empty($staffProfile->notes))
            <div class="col-12 mb-3">
                <label class="form-label text-muted small mb-1">Notes</label>
                <p class="mb-0 fw-bold">{{ $staffProfile->notes }}</p>
            </div>
            @endif

        </div>
    </div>
</div>
@endif


            <!-- Declaration -->
            <div class="card mb-4" style='overflow:hidden'>
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-file-signature me-2"></i>Declaration</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <p class="mb-0">
                            <i class="fas fa-quote-left me-2"></i>
                            I {{ $application->surname }} {{ $application->first_name }}, declare that the above details are correct and true to the best of my knowledge and I acknowledge that I am liable to any action against me by the Organization if, at any point in time during my employment with the Organization, any of the above details are found to be untrue.
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Signature</label>
                            <p class="fw-bold w-100"><img src="{{ url($application->declaration_signature) }}" alt='signature' class='w-100'></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Date</label>
                            <p class="fw-bold">{{ $application->declaration_date->format('F j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Status & Documents -->
        <div class="col-lg-4">
            <!-- Status & Notes -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Application Status</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label text-muted small mb-2">Current Status</label>
                        <div class="d-flex align-items-center">
                            @php
                                $statusIcons = [
                                    'pending' => 'clock',
                                    'under_review' => 'search',
                                    'shortlisted' => 'list-check',
                                    'rejected' => 'times-circle',
                                    'hired' => 'check-circle'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$application->status] }} fs-6 me-3">
                                <i class="fas fa-{{ $statusIcons[$application->status] }} me-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                    data-bs-toggle="modal" data-bs-target="#statusModal">
                                Change
                            </button>
                            
                            @if($staffProfile)
                                <a href="{{ route('admin.staff.edit-from-application', $application->id) }}" 
                                   class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit Staff Profile
                                </a>
                            @endif
                            
                        </div>
                    </div>

                    @if($application->notes)
                    <div>
                        <label class="form-label text-muted small mb-2">Notes</label>
                        <div class="border rounded p-3 bg-light">
                            <p class="mb-0">{{ $application->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="mt-4">
                        <label class="form-label text-muted small mb-2">Timeline</label>
                        <div class="timeline">
                            <div class="timeline-step {{ $application->created_at ? 'active' : '' }}">
                                <div class="timeline-icon">
                                    <i class="fas fa-paper-plane"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Submitted</h6>
                                    <p class="small text-muted mb-0">{{ $application->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="timeline-step {{ $application->status != 'pending' ? 'active' : '' }}">
                                <div class="timeline-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Under Review</h6>
                                    @if($application->status != 'pending')
                                        <p class="small text-muted mb-0">Reviewed on {{ $application->updated_at->format('M d') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="timeline-step {{ in_array($application->status, ['shortlisted', 'rejected', 'hired']) ? 'active' : '' }}">
                                <div class="timeline-icon">
                                    <i class="fas fa-list-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Decision</h6>
                                    @if(in_array($application->status, ['shortlisted', 'rejected', 'hired']))
                                        <p class="small text-muted mb-0">
                                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Documents</h5>
                </div>
                <div class="card-body">
                    <!-- Passport Photo -->
                    @if($application->passport_photo)
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-2">Passport Photograph</label>
                        <div class="text-center">
                            <img src="{{ asset( $application->passport_photo) }}" 
                                 alt="Passport Photo" class="img-thumbnail mb-2" 
                                 style="max-height: 150px;">
                            <div>
                                <a href="{{ route('admin.employment.download', ['application' => $application, 'type' => 'passport']) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i>Download
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Resume -->
                    @if($application->resume)
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-2">Resume/CV</label>
                        <div class="d-flex align-items-center justify-content-between border rounded p-2">
                            <div>
                                <i class="fas fa-file-pdf text-danger fa-lg me-2"></i>
                                <span class="fw-semibold">Resume</span>
                                <br>
                                <small class="text-muted">PDF Document</small>
                            </div>
                            <a href="{{ route('admin.employment.download', ['application' => $application, 'type' => 'resume']) }}" 
                               class="btn btn-sm btn-outline-success">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Certificates -->
                  @php
                    $certificates = $application->certificates ?? [];
                @endphp

                    @if(!empty($certificates))
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-2">Certificates ({{ count($certificates) }})</label>
                        <div class="list-group">
                            @foreach($certificates as $index => $certificate)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-alt text-primary me-2"></i>
                                        <span>Certificate {{ $index + 1 }}</span>
                                        <br>
                                        <small class="text-muted">{{ basename($certificate) }}</small>
                                    </div>
                                    <a href="{{ asset( $certificate) }}" 
                                       target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Means of Identification -->
                    @php
                        $identifications = $application->means_of_identification ?? [];
                    @endphp

                    @if(!empty($identifications))
                    <div>
                        <label class="form-label text-muted small mb-2">Means of Identification ({{ count($identifications) }})</label>
                        <div class="list-group">
                            @foreach($identifications as $index => $identification)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-id-card text-success me-2"></i>
                                        <span>ID Document {{ $index + 1 }}</span>
                                        <br>
                                        <small class="text-muted">{{ basename($identification) }}</small>
                                    </div>
                                    <a href="{{ asset( $identification) }}" 
                                       target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Application Meta -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Application Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <th width="50%">Application ID:</th>
                            <td>#{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                        <tr>
                            <th>Reference:</th>
                            <td>{{ $application->application_reference }}</td>
                        </tr>
                        <tr>
                            <th>Submitted:</th>
                            <td>{{ $application->created_at->format('F j, Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $application->updated_at->format('F j, Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Total Documents:</th>
                            <td>{{ 1 + count($certificates) + count($identifications) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
            
                <div class="card-body">
                    <div class="d-grid gap-2">
            
                        {{-- View Attendance --}}
                        @if(isset($staffProfile) && $staffProfile)
                            <a href="{{ route('admin.attendance.staff', $staffProfile->id) }}"
                               class="btn btn-primary">
                                <i class="fas fa-calendar-check me-2"></i>
                                View Attendance
                            </a>
                        @else
                            <button class="btn btn-secondary" disabled>
                                <i class="fas fa-calendar-check me-2"></i>
                                Attendance Not Available
                            </button>
                        @endif
                        @if($staffProfile)
                            <a href="{{ route('admin.staff.documents', $staffProfile->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-folder-open me-1"></i> Documents
                            </a>
                            
                            <a href="{{ url('/admin/tasks?marketer=' . $staffProfile->user_id) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-folder-open me-1"></i> Tasks
                            </a>
                            <a href="{{ url('/admin/hr-queries') }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-folder-open me-1"></i> Query
                            </a>



                        @endif

                    
                    </div>
                </div>
            </div>

        </div>
        
        
    </div>
</div>

<!-- Status Change Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Application Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.employment.updateStatus', $application) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">New Status</label>
                        <select class="form-select" name="status" id="status" required>
                            <option value="">Select Status</option>
                            <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="under_review" {{ $application->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                            <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="hired" {{ $application->status == 'hired' ? 'selected' : '' }}>Hired</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3" 
                                  placeholder="Add any notes about this status change...">{{ $application->notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
            
            
            
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone.
                </div>
                <p>Are you sure you want to delete the application for <strong>{{ $application->full_name }}</strong>?</p>
                <p class="text-muted">This will permanently remove all application data and uploaded documents.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Font Awesome Free -->
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
/>

<style>
    .breadcrumb {
        background-color: transparent;
        padding-left: 0;
    }
    
    .card-header.bg-light {
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #dee2e6;
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #dee2e6;
    }
    
    .timeline-step {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-step.active:before {
        background-color: #0d6efd;
    }
    
    .timeline-icon {
        position: absolute;
        left: -30px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #dee2e6;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }
    
    .timeline-step.active .timeline-icon {
        background-color: #0d6efd;
        color: white;
    }
    
    .timeline-content {
        margin-left: 10px;
    }
    
    .img-thumbnail {
        max-width: 100%;
        height: auto;
    }
    
    .list-group-item {
        border-left: none;
        border-right: none;
    }
    
    .list-group-item:first-child {
        border-top: none;
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
    
    @media print {
        .d-print-none {
            display: none !important;
        }
        
        .card {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
        }
        
        .card-header {
            background: #f8f9fa !important;
            color: #000 !important;
        }
        
        .btn, .modal, .dropdown {
            display: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    
    // Status change modal
    const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
    
    // Status change form submission
    const statusForm = document.getElementById('statusModal').querySelector('form');
    if (statusForm) {
        statusForm.addEventListener('submit', function(e) {
            // You can add additional validation here if needed
        });
    }
    
    // Delete modal confirmation
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    
    // Print functionality
    function printApplication() {
        const printContent = document.querySelector('.container-fluid').innerHTML;
        const originalContent = document.body.innerHTML;
        
        document.body.innerHTML = `
            <div class="container mt-4">
                <div class="text-center mb-4">
                    <h2>Application Details</h2>
                    <p class="text-muted">#{{ $application->application_reference }} - {{ $application->full_name }}</p>
                    <hr>
                </div>
                ${printContent}
            </div>
        `;
        
        window.print();
        document.body.innerHTML = originalContent;
        location.reload();
    }
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl + P to print
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            printApplication();
        }
        
        // Ctrl + Backspace to go back
        if ((e.ctrlKey || e.metaKey) && e.key === 'Backspace') {
            window.history.back();
        }
    });
    
    // Auto-refresh status every 30 seconds
    setInterval(function() {
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse the HTML and extract status
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newStatus = doc.querySelector('.badge.fs-6')?.textContent.trim();
            
            if (newStatus) {
                const currentStatus = document.querySelector('.badge.fs-6').textContent.trim();
                if (newStatus !== currentStatus) {
                    // Show notification if status changed
                    showToast('Status Updated', `Application status changed to: ${newStatus}`, 'info');
                    location.reload(); // Reload page to show updated status
                }
            }
        })
        .catch(error => console.error('Error checking status:', error));
    }, 30000);
    
    // Toast notification function
    function showToast(title, message, type = 'info') {
        const toastContainer = document.getElementById('toastContainer') || createToastContainer();
        
        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-bg-${type === 'error' ? 'danger' : type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.id = toastId;
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}:</strong> ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Remove toast after it's hidden
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    }
    
    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '1060';
        document.body.appendChild(container);
        return container;
    }
    
    // Document viewer for images
    document.querySelectorAll('a[target="_blank"]').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href.match(/\.(jpg|jpeg|png|gif)$/i)) {
                e.preventDefault();
                openImageViewer(href);
            }
        });
    });
    
    function openImageViewer(imageUrl) {
        const modalId = 'imageViewerModal';
        let modal = document.getElementById(modalId);
        
        if (!modal) {
            modal = document.createElement('div');
            modal.id = modalId;
            modal.className = 'modal fade';
            modal.tabIndex = -1;
            modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Image Viewer</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center p-0">
                            <img src="" class="img-fluid" style="max-height: 70vh; object-fit: contain;">
                        </div>
                        <div class="modal-footer">
                            <a href="" class="btn btn-primary" download>
                                <i class="fas fa-download me-2"></i>Download
                            </a>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        modal.querySelector('img').src = imageUrl;
        modal.querySelector('a[download]').href = imageUrl;
        
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }
});
</script>
@endpush