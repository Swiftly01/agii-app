{{-- resources/views/admin/holidays/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Holidays Management</h1>
            <p class="lead">Manage public and company holidays</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Total Holidays</h6>
                    <h2>{{ $holidayStats['total'] }}</h2>
                    <small class="opacity-75">{{ $year }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Public Holidays</h6>
                    <h2>{{ $holidayStats['public'] }}</h2>
                    <small class="opacity-75">National holidays</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Company Holidays</h6>
                    <h2>{{ $holidayStats['company'] }}</h2>
                    <small class="opacity-75">Company-specific</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Upcoming</h6>
                    <h2>{{ $holidayStats['upcoming'] }}</h2>
                    <small class="opacity-75">Next 30 days</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <form method="GET" class="d-flex">
                                <select class="form-select me-2" name="year" onchange="this.form.submit()">
                                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                                <select class="form-select" name="type" onchange="this.form.submit()">
                                    <option value="">All Types</option>
                                    <option value="public" {{ $type == 'public' ? 'selected' : '' }}>Public</option>
                                    <option value="company" {{ $type == 'company' ? 'selected' : '' }}>Company</option>
                                    <option value="optional" {{ $type == 'optional' ? 'selected' : '' }}>Optional</option>
                                </select>
                            </form>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addHolidayModal">
                                    <i class="fas fa-plus"></i> Add Holiday
                                </button>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                                    <i class="fas fa-upload"></i> Import CSV
                                </button>
                                <a href="{{ route('admin.holidays.export', ['year' => $year, 'type' => $type]) }}" 
                                   class="btn btn-info">
                                    <i class="fas fa-download"></i> Export CSV
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Holidays List -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Holidays for {{ $year }}</h5>
                </div>
                <div class="card-body">
                    @if($holidays->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No holidays found for the selected year and type.
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Repeats</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($holidays as $holiday)
                                <tr>
                                    <td>
                                        <strong>{{ $holiday->date->format('M d, Y') }}</strong>
                                        @if($holiday->date < today())
                                            <br><small class="text-muted">Past</small>
                                        @elseif($holiday->date->isToday())
                                            <br><small class="text-success">Today</small>
                                        @else
                                            <br><small class="text-info">In {{ $holiday->date->diffForHumans(today()) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $holiday->date->format('l') }}</td>
                                    <td>
                                        <strong>{{ $holiday->title }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $holiday->type == 'public' ? 'success' : ($holiday->type == 'company' ? 'info' : 'secondary') }}">
                                            {{ ucfirst($holiday->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($holiday->repeats_annually)
                                            <span class="badge bg-success">
                                                <i class="fas fa-sync-alt"></i> Annual
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">One-time</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($holiday->description)
                                            {{ Str::limit($holiday->description, 50) }}
                                        @else
                                            <span class="text-muted">No description</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editHolidayModal"
                                                    data-id="{{ $holiday->id }}"
                                                    data-title="{{ $holiday->title }}"
                                                    data-date="{{ $holiday->date->format('Y-m-d') }}"
                                                    data-type="{{ $holiday->type }}"
                                                    data-description="{{ $holiday->description }}"
                                                    data-repeats="{{ $holiday->repeats_annually }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.holidays.destroy', $holiday->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('Delete this holiday?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $holidays->firstItem() }} to {{ $holidays->lastItem() }} 
                            of {{ $holidays->total() }} records
                        </div>
                        {{ $holidays->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Holiday Modal -->
<div class="modal fade" id="addHolidayModal" tabindex="-1" aria-labelledby="addHolidayModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addHolidayModalLabel">Add New Holiday</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.holidays.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_title" class="form-label">Holiday Title *</label>
                        <input type="text" class="form-control" id="add_title" name="title" 
                               placeholder="e.g., New Year's Day" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_date" class="form-label">Date *</label>
                        <input type="date" class="form-control" id="add_date" name="date" 
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_type" class="form-label">Type *</label>
                        <select class="form-select" id="add_type" name="type" required>
                            <option value="public">Public Holiday</option>
                            <option value="company">Company Holiday</option>
                            <option value="optional">Optional Holiday</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add_description" class="form-label">Description</label>
                        <textarea class="form-control" id="add_description" name="description" 
                                  rows="3" placeholder="Optional description..."></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="add_repeats" name="repeats_annually" checked>
                            <label class="form-check-label" for="add_repeats">
                                Repeats Annually
                            </label>
                        </div>
                        <small class="form-text text-muted">
                            If checked, this holiday will repeat every year automatically
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Holiday</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Holiday Modal -->
<div class="modal fade" id="editHolidayModal" tabindex="-1" aria-labelledby="editHolidayModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editHolidayModalLabel">Edit Holiday</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.holidays.update', 0) }}" method="POST" id="editHolidayForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="holiday_id" id="edit_holiday_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Holiday Title *</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_date" class="form-label">Date *</label>
                        <input type="date" class="form-control" id="edit_date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_type" class="form-label">Type *</label>
                        <select class="form-select" id="edit_type" name="type" required>
                            <option value="public">Public Holiday</option>
                            <option value="company">Company Holiday</option>
                            <option value="optional">Optional Holiday</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_repeats" name="repeats_annually">
                            <label class="form-check-label" for="edit_repeats">
                                Repeats Annually
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Holiday</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import CSV Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="importModalLabel">Import Holidays from CSV</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.holidays.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>CSV Format:</strong> Title,Date,Type,Description<br>
                        <strong>Example:</strong> "New Year's Day","2024-01-01","public","First day of the year"
                    </div>
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">CSV File *</label>
                        <input type="file" class="form-control" id="csv_file" name="csv_file" 
                               accept=".csv,.txt" required>
                        <small class="form-text text-muted">
                            Max file size: 2MB. CSV should have headers: Title,Date,Type,Description
                        </small>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="skip_duplicates" name="skip_duplicates" checked>
                            <label class="form-check-label" for="skip_duplicates">
                                Skip duplicate entries
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Import CSV</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Edit Holiday Modal
    const editModal = document.getElementById('editHolidayModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const holidayId = button.getAttribute('data-id');
            
            // Update form action URL
            const form = document.getElementById('editHolidayForm');
            form.action = form.action.replace('/0', '/' + holidayId);
            
            // Set form values
            document.getElementById('edit_holiday_id').value = holidayId;
            document.getElementById('edit_title').value = button.getAttribute('data-title');
            document.getElementById('edit_date').value = button.getAttribute('data-date');
            document.getElementById('edit_type').value = button.getAttribute('data-type');
            document.getElementById('edit_description').value = button.getAttribute('data-description') || '';
            document.getElementById('edit_repeats').checked = button.getAttribute('data-repeats') === '1';
        });
    }
    
    // Set min date for add holiday to today
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('add_date').min = new Date().toISOString().split('T')[0];
    });
</script>
@endpush

@push('styles')
<style>
    .badge {
        font-size: 0.8em;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .alert ul {
        margin-bottom: 0;
        padding-left: 1.5rem;
    }
</style>
@endpush