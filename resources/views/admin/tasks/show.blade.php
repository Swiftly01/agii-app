@extends('layout.marketer')

@section('title', 'Task Details - Agii')
@section('page-title', 'Task Details')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- Task Details Card -->
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">TASK #{{ $task->id }} DETAILS</h6>
                <div>
                    <a href="{{ route('admin.tasks.edit', $task->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <a href="{{ route('admin.tasks.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Status Badges -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="border rounded p-3 text-center">
                            <small class="text-muted d-block">Status</small>
                            <span class="badge bg-{{ $task->status_color }} fs-6">
                                {{ ucfirst($task->status) }}
                                @if($task->is_overdue && $task->status != 'completed')
                                    (Overdue)
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 text-center">
                            <small class="text-muted d-block">Priority</small>
                            <span class="badge bg-{{ $task->priority_color }} fs-6">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 text-center">
                            <small class="text-muted d-block">Progress</small>
                            <h4 class="mb-0">{{ $task->progress }}%</h4>
                        </div>
                    </div>
                </div>

                <!-- Task Information Table -->
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 200px;">Title:</th>
                        <td>{{ $task->title }}</td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td>{{ $task->description ?: 'No description provided' }}</td>
                    </tr>
                    <tr>
                        <th>Assigned To:</th>
                        <td>
                            @if($task->marketer)
                                <strong>{{ $task->marketer->first_name }} {{ $task->marketer->last_name }}</strong>
                                <br>
                                <small class="text-muted">{{ $task->marketer->email }}</small>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Assigned By:</th>
                        <td>
                            @if($task->assigner)
                                {{ $task->assigner->first_name }} {{ $task->assigner->last_name }}
                                <br>
                                <small class="text-muted">{{ $task->assigner->email }}</small>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Report To:</th>
                        <td>
                            @if(is_array($task->report_to) || is_object($task->report_to))
                                @foreach($task->report_to as $reporter)
                                    <span class="badge bg-secondary">{{ $reporter }}</span>
                                @endforeach
                            @else
                                {{ $task->report_to ?: 'N/A' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Deadline:</th>
                        <td>
                            @if($task->deadline)
                                {{ $task->deadline->format('l, d F Y H:i') }}
                                @if($task->days_remaining > 0)
                                    <span class="badge bg-info ms-2">{{ $task->days_remaining }} days remaining</span>
                                @elseif($task->days_remaining < 0 && $task->status != 'completed')
                                    <span class="badge bg-danger ms-2">{{ abs($task->days_remaining) }} days overdue</span>
                                @endif
                            @else
                                <span class="text-muted">No deadline set</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Target Leads:</th>
                        <td>
                            @if($task->target_leads)
                                <strong>{{ $task->target_leads }}</strong> leads
                            @else
                                <span class="text-muted">Not specified</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Target Conversion:</th>
                        <td>
                            @if($task->target_conversion)
                                <strong>{{ $task->target_conversion }}%</strong>
                            @else
                                <span class="text-muted">Not specified</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created At:</th>
                        <td>{{ $task->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated:</th>
                        <td>{{ $task->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>

                <!-- Progress Bar -->
                <div class="mt-4">
                    <label class="form-label fw-bold">Progress: {{ $task->progress }}%</label>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-{{ 
                            $task->progress == 100 ? 'success' : 
                            ($task->progress >= 50 ? 'info' : 'warning') 
                        }}" 
                            role="progressbar" 
                            style="width: {{ $task->progress }}%;"
                            aria-valuenow="{{ $task->progress }}" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                            {{ $task->progress }}%
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                @if($task->notes)
                <div class="mt-4">
                    <h6 class="fw-bold">Notes & Updates:</h6>
                    <div class="bg-light p-3 rounded">
                        <pre class="mb-0" style="white-space: pre-wrap; font-family: inherit;">{{ $task->notes }}</pre>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection