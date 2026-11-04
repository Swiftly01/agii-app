@extends('layout.marketer')

@section('title', 'Edit Staff - Agii')
@section('page-title', 'Edit Staff Profile')

@section('content')
<div class="container-fluid">
    @include('staff.partials.flash')
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.staff.index') }}">Staff Management</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.staff.show', $staff->id) }}">{{ $staff->user->first_name ?? '' }} {{ $staff->user->last_name ?? '' }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Staff Profile</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-briefcase"></i> Employment Details</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department</label>
                                <select name="department" id="department" class="form-select" required>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept }}" {{ old('department', $staff->department) == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                    @endforeach
                                    <option value="other" {{ old('department') === 'other' ? 'selected' : '' }}>Other (Specify)</option>
                                </select>
                                <input type="text" name="other_department" id="other_department"
                                    class="form-control mt-2 {{ old('department') === 'other' ? '' : 'd-none' }}"
                                    value="{{ old('other_department') }}" placeholder="Please specify the category">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Designation</label>
                                <input type="text" name="designation" class="form-control"
                                    value="{{ old('designation', $staff->designation) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Employment Type</label>
                                <select name="employment_type" class="form-select" required>
                                    @foreach (['full_time' => 'Full Time', 'part_time' => 'Part Time', 'contract' => 'Contract', 'probation' => 'Probation'] as $val => $label)
                                        <option value="{{ $val }}" {{ old('employment_type', $staff->employment_type) == $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    @foreach (['active' => 'Active', 'suspended' => 'Suspended', 'terminated' => 'Terminated', 'on_leave' => 'On Leave'] as $val => $label)
                                        <option value="{{ $val }}" {{ old('status', $staff->status) == $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Salary</label>
                                <input type="number" step="0.01" name="salary" class="form-control"
                                    value="{{ old('salary', $staff->salary) }}">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-university"></i> Bank Details</h6>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Bank Name</label>
                                <input type="text" name="bank_name" class="form-control"
                                    value="{{ old('bank_name', $staff->bank_name) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Account Number</label>
                                <input type="text" name="account_number" class="form-control"
                                    value="{{ old('account_number', $staff->account_number) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Account Name</label>
                                <input type="text" name="account_name" class="form-control"
                                    value="{{ old('account_name', $staff->account_name) }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.staff.show', $staff->id) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-lock"></i> Login Access</h5>
                </div>
                <div class="card-body d-flex justify-content-between align-items-center">
                    <p class="mb-0 text-muted">Generates a new password and emails it to {{ $staff->user->email }}.</p>
                    @include('staff.partials.regenerate-password')
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    // Shows the free text field only while Other is selected
    document.getElementById('department').addEventListener('change', function () {
        const other = document.getElementById('other_department');
        const isOther = this.value === 'other';
        other.classList.toggle('d-none', !isOther);
        other.required = isOther;
    });
</script>
@endpush
</div>
@endsection
