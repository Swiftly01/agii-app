{{-- resources/views/admin/offer-letters/create.blade.php --}}
@extends('layout.marketer')

@section('title', 'Send Offer Letter')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Send Offer Letter to Staff</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.offer-letters.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-list"></i> View All Letters
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif

                    @if(session('failed_staff'))
                        <div class="alert alert-warning alert-dismissible fade show">
                            <h5><i class="fas fa-exclamation-triangle"></i> Failed to send to some staff:</h5>
                            <ul class="mb-0">
                                @foreach(session('failed_staff') as $failed)
                                    <li>{{ $failed['name'] }} (ID: {{ $failed['staff_id'] }}): {{ $failed['error'] }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <form action="{{ route('admin.offer-letters.send-selected') }}" method="POST" enctype="multipart/form-data" id="offerLetterForm">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title" class="font-weight-bold">Document Title *</label>
                                            <input type="text" name="title" id="title" 
                                                   class="form-control @error('title') is-invalid @enderror" 
                                                   value="{{ old('title') }}" 
                                                   placeholder="e.g., Employment Offer Letter" required>
                                            @error('title')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="effective_date" class="font-weight-bold">Effective Date *</label>
                                            <input type="date" name="effective_date" id="effective_date" 
                                                   class="form-control @error('effective_date') is-invalid @enderror" 
                                                   value="{{ old('effective_date', date('Y-m-d')) }}" required>
                                            @error('effective_date')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="offer_letter" class="font-weight-bold">Offer Letter File *</label>
                                    <div class="custom-file">
                                        <input type="file" name="offer_letter" id="offer_letter" 
                                               class="custom-file-input @error('offer_letter') is-invalid @enderror" 
                                               accept=".pdf,.doc,.docx" required>
                                        <label class="custom-file-label" for="offer_letter">Choose file</label>
                                        <small class="form-text text-muted">Accepted formats: PDF, DOC, DOCX (Max: 5MB)</small>
                                        @error('offer_letter')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="send_email" name="send_email" value="1">
                                        <label class="custom-control-label font-weight-bold" for="send_email">
                                            <i class="fas fa-envelope"></i> Send email notification to staff
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group email-message" style="display: none;">
                                    <label for="email_message" class="font-weight-bold">Custom Email Message (Optional)</label>
                                    <textarea name="email_message" id="email_message" rows="4" 
                                              class="form-control" 
                                              placeholder="Add a personal message for the staff member...">{{ old('email_message') }}</textarea>
                                    <small class="form-text text-muted">This message will be included in the email notification</small>
                                </div>

                                <hr>

                                <div class="form-group">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <label class="font-weight-bold mb-0">Select Staff Members *</label>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-primary" id="select-all">
                                                <i class="fas fa-check-double"></i> Select All
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" id="deselect-all">
                                                <i class="fas fa-times"></i> Deselect All
                                            </button>
                                            <button type="button" class="btn btn-outline-info" id="select-active">
                                                <i class="fas fa-user-check"></i> Active Only
                                            </button>
                                        </div>
                                    </div>

                                    <div class="selected-count alert alert-info py-2" style="display: none;">
                                        <i class="fas fa-users"></i> <span id="selected-count">0</span> staff selected
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-sm" id="staffTable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th width="50">
                                                        <input type="checkbox" id="select-all-checkbox">
                                                    </th>
                                                    <th>Staff ID</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Department</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($staff as $member)
                                                    <tr class="staff-row" data-status="{{ $member->status }}">
                                                        <td>
                                                            <input type="checkbox" name="staff_ids[]" 
                                                                   value="{{ $member->id }}" 
                                                                   class="staff-checkbox"
                                                                   {{ in_array($member->id, old('staff_ids', [])) ? 'checked' : '' }}>
                                                        </td>
                                                        <td><strong>{{ $member->staff_id }}</strong></td>
                                                        <td>{{ $member->user->name ?? 'N/A' }}</td>
                                                        <td>{{ $member->user->email ?? 'N/A' }}</td>
                                                        <td>{{ $member->department ?? 'N/A' }}</td>
                                                        <td>
                                                            <span class="badge badge-{{ $member->status == 'active' ? 'success' : 'warning' }}">
                                                                {{ ucfirst($member->status) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @error('staff_ids')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                        <i class="fas fa-paper-plane"></i> Send Offer Letter to Selected Staff
                                    </button>
                                    
                                    <button type="button" class="btn btn-primary btn-lg" id="sendAllBtn">
                                        <i class="fas fa-broadcast-tower"></i> Send to All Staff
                                    </button>
                                    
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-info-circle"></i> Instructions
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <ol class="pl-3">
                                        <li class="mb-2">Fill in the document title</li>
                                        <li class="mb-2">Select effective date</li>
                                        <li class="mb-2">Upload the offer letter file</li>
                                        <li class="mb-2">Choose staff members to send to</li>
                                        <li class="mb-2">Optionally enable email notification</li>
                                        <li>Click "Send Offer Letter"</li>
                                    </ol>
                                    
                                    <div class="alert alert-warning mt-3">
                                        <h6><i class="fas fa-exclamation-triangle"></i> Note:</h6>
                                        <p class="mb-0 small">
                                            Files are stored in: <code>public/uploads/staff-documents/[staff-id]/offer-letters/</code>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // File input label
    $('#offer_letter').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').text(fileName);
    });

    // Email message toggle
    $('#send_email').change(function() {
        if ($(this).is(':checked')) {
            $('.email-message').slideDown();
        } else {
            $('.email-message').slideUp();
        }
    });

    // Selection functions
    function updateSelectedCount() {
        var count = $('.staff-checkbox:checked').length;
        $('#selected-count').text(count);
        if (count > 0) {
            $('.selected-count').slideDown();
        } else {
            $('.selected-count').slideUp();
        }
    }

    // Select all
    $('#select-all-checkbox, #select-all').click(function() {
        $('.staff-checkbox').prop('checked', true);
        updateSelectedCount();
    });

    // Deselect all
    $('#deselect-all').click(function() {
        $('.staff-checkbox').prop('checked', false);
        updateSelectedCount();
    });

    // Select active only
    $('#select-active').click(function() {
        $('.staff-checkbox').prop('checked', false);
        $('.staff-row[data-status="active"] .staff-checkbox').prop('checked', true);
        updateSelectedCount();
    });

    // Individual checkbox change
    $('.staff-checkbox').change(function() {
        updateSelectedCount();
    });

    // Master checkbox
    $('#select-all-checkbox').change(function() {
        var isChecked = $(this).is(':checked');
        $('.staff-checkbox').prop('checked', isChecked);
        updateSelectedCount();
    });

    // Send to all staff
    $('#sendAllBtn').click(function(e) {
        e.preventDefault();
        
        if (confirm('Are you sure you want to send this offer letter to ALL staff members?')) {
            var form = $('#offerLetterForm');
            var action = form.attr('action');
            form.attr('action', "{{ route('admin.offer-letters.send-all') }}");
            form.submit();
        }
    });

    // Form validation
    $('#offerLetterForm').submit(function() {
        var selectedCount = $('.staff-checkbox:checked').length;
        if (selectedCount === 0) {
            alert('Please select at least one staff member.');
            return false;
        }
        
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');
        return true;
    });

    // Initial count update
    updateSelectedCount();
});
</script>
@endpush