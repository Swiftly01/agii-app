@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Staff Profile</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('staff.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-primary">Edit Profile</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <!-- Add photo placeholder or actual photo -->
                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                            <span class="text-muted">Photo</span>
                        </div>
                    </div>
                    <h4>{{ $staff->user->name ?? 'N/A' }}</h4>
                    <p class="text-muted">{{ $staff->designation }}</p>
                    <span class="badge bg-{{ $staff->status == 'active' ? 'success' : 'warning' }} mb-3">
                        {{ ucfirst($staff->status) }}
                    </span>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Employment Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Staff ID:</strong> {{ $staff->staff_id }}</p>
                    <p><strong>Department:</strong> {{ $staff->department }}</p>
                    <p><strong>Employment Type:</strong> {{ ucfirst(str_replace('_', ' ', $staff->employment_type)) }}</p>
                    <p><strong>Date Employed:</strong> {{ $staff->date_of_employment->format('M d, Y') }}</p>
                    <p><strong>Next Review:</strong> {{ $staff->next_of_review ? $staff->next_of_review->format('M d, Y') : 'Not set' }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Personal Information</h5>
                </div>
                <div class="card-body">
                    <!-- Display information from employment application -->
                    @if($staff->employmentApplication)
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Email:</strong> {{ $staff->user->email ?? $staff->employmentApplication->email }}</p>
                            <p><strong>Phone:</strong> {{ $staff->employmentApplication->contact_number }}</p>
                            <p><strong>Date of Birth:</strong> {{ $staff->employmentApplication->date_of_birth }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Address:</strong> {{ $staff->employmentApplication->residential_address }}</p>
                            <p><strong>State/LGA:</strong> {{ $staff->employmentApplication->state_of_origin }} / {{ $staff->employmentApplication->lga }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Financial Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Salary:</strong> ₦{{ number_format($staff->salary, 2) }}</p>
                            <p><strong>Bank:</strong> {{ $staff->bank_name ?? 'Not set' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Account Number:</strong> {{ $staff->account_number ?? 'Not set' }}</p>
                            <p><strong>Account Name:</strong> {{ $staff->account_name ?? 'Not set' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Notes</h5>
                </div>
                <div class="card-body">
                    <p>{{ $staff->notes ?? 'No notes available.' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection