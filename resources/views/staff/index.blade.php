@extends('layout.marketer')

@section('title', 'Staff Dashboard - Agii')
@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Staff Management</h1>
        </div>
        <div class="col-md-6 text-end">
          <a href="{{ url('https://agii.ng/admin/employment/applications') }}" class="btn btn-outline-primary">
                View Applications
            </a> 
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-0">All Staff Members</h5>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Search staff..." id="searchStaff">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Staff ID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Employment Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staff as $member)
                        <tr>
                            <td>{{ $member->staff_id }}</td>
                            <td>{{ $member->user->name ?? 'N/A' }}</td>
                            <td>{{ $member->department }}</td>
                            <td>{{ $member->designation }}</td>
                            <td>{{ $member->date_of_employment->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $member->status == 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('staff.show', $member->id) }}" class="btn btn-sm btn-info">
                                    View
                                </a>
                                <a href="{{ route('staff.edit', $member->id) }}" class="btn btn-sm btn-primary">
                                    Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $staff->links() }}
        </div>
    </div>
</div>
@endsection