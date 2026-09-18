{{-- resources/views/admin/payroll/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Payroll Management</h1>
            <p class="lead">Process and manage staff payroll</p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Total Payroll Cost</h6>
                    <h2>₦{{ number_format($stats['total_payroll'], 2) }}</h2>
                    <small class="opacity-75">This Month</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Staff Paid</h6>
                    <h2>{{ $stats['staff_paid'] }}/{{ $stats['total_staff'] }}</h2>
                    <small class="opacity-75">{{ $stats['paid_percentage'] }}% completed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Pending Queries</h6>
                    <h2>{{ $stats['pending_queries'] }}</h2>
                    <small class="opacity-75">Requires attention</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Avg. Salary</h6>
                    <h2>₦{{ number_format($stats['avg_salary'], 2) }}</h2>
                    <small class="opacity-75">Per staff</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Payroll Period Selection -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Select Payroll Period</h5>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Month</label>
                            <select class="form-select" name="month">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i == date('m') ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Year</label>
                            <select class="form-select" name="year">
                                @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Load Payroll</button>
                                <a href="{{ route('admin.payroll.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Create New Payroll
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Payroll List -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Payroll for {{ $selectedPeriod }}</h5>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#runPayrollModal">
                            <i class="fas fa-cogs"></i> Run Payroll
                        </button>
                        <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exportPayrollModal">
                            <i class="fas fa-file-export"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Staff</th>
                                    <th>Department</th>
                                    <th>Basic Salary</th>
                                    <th>Allowances</th>
                                    <th>Overtime</th>
                                    <th>Deductions</th>
                                    <th>Tax</th>
                                    <th>Net Pay</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payrollData as $data)
                                <tr>
                                    <td>
                                        <strong>{{ $data->staffProfile->user->name ?? 'N/A' }}</strong>
                                        <br><small class="text-muted">{{ $data->staffProfile->staff_id }}</small>
                                    </td>
                                    <td>{{ $data->staffProfile->department }}</td>
                                    <td>₦{{ number_format($data->basic_salary, 2) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info" 
                                                data-bs-toggle="popover" 
                                                data-bs-html="true"
                                                data-bs-content="
                                                    @foreach(json_decode($data->earnings ?? '[]', true) as $earning)
                                                    <div>{{ $earning['name'] }}: ₦{{ number_format($earning['amount'], 2) }}</div>
                                                    @endforeach
                                                ">
                                            ₦{{ number_format(collect(json_decode($data->earnings ?? '[]', true))->sum('amount'), 2) }}
                                        </button>
                                    </td>
                                    <td>
                                        @if($data->overtime_amount > 0)
                                            <span class="badge bg-warning">
                                                {{ $data->overtime_hours }}h: ₦{{ number_format($data->overtime_amount, 2) }}
                                            </span>
                                        @else
                                            <span class="text-muted">None</span>
                                        @endif
                                    </td>
                                    <td class="text-danger">
                                        -₦{{ number_format($data->total_deductions, 2) }}
                                    </td>
                                    <td class="text-warning">
                                        -₦{{ number_format($data->tax_amount, 2) }}
                                    </td>
                                    <td class="text-success">
                                        <strong>₦{{ number_format($data->net_salary, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $data->status == 'paid' ? 'success' : ($data->status == 'approved' ? 'info' : 'warning') }}">
                                            {{ ucfirst($data->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.payroll.edit', $data->id) }}" 
                                               class="btn btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.payroll.payslip', $data->id) }}" 
                                               class="btn btn-info" target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($data->status != 'paid')
                                                <button class="btn btn-success" 
                                                        onclick="markAsPaid({{ $data->id }}, '{{ $data->staffProfile->user->name }}')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">No payroll data for this period</td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($payrollData->isNotEmpty())
                            <tfoot class="table-active">
                                <tr>
                                    <td colspan="2"><strong>TOTALS</strong></td>
                                    <td><strong>₦{{ number_format($payrollData->sum('basic_salary'), 2) }}</strong></td>
                                    <td><strong>₦{{ number_format($payrollData->sum(function($item) {
                                        return collect(json_decode($item->earnings ?? '[]', true))->sum('amount');
                                    }), 2) }}</strong></td>
                                    <td><strong>₦{{ number_format($payrollData->sum('overtime_amount'), 2) }}</strong></td>
                                    <td class="text-danger"><strong>-₦{{ number_format($payrollData->sum('total_deductions'), 2) }}</strong></td>
                                    <td class="text-warning"><strong>-₦{{ number_format($payrollData->sum('tax_amount'), 2) }}</strong></td>
                                    <td class="text-success"><strong>₦{{ number_format($payrollData->sum('net_salary'), 2) }}</strong></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Payroll by Department</h5>
                </div>
                <div class="card-body">
                    <canvas id="departmentPayrollChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Cost Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="costDistributionChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Run Payroll Modal -->
<div class="modal fade" id="runPayrollModal" tabindex="-1" aria-labelledby="runPayrollModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="runPayrollModalLabel">Run Payroll for {{ $selectedPeriod }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.payroll.run') }}" method="POST">
                @csrf
                <input type="hidden" name="month" value="{{ date('m') }}">
                <input type="hidden" name="year" value="{{ date('Y') }}">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        This will generate payroll for all active staff members for the selected period.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payroll_date" class="form-label">Payment Date</label>
                                <input type="date" class="form-control" id="payroll_date" name="payment_date" 
                                       value="{{ date('Y-m-25') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select class="form-select" id="payment_method" name="payment_method">
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="cash">Cash</option>
                                    <option value="cheque">Cheque</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Include Components</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="include_overtime" name="include_overtime" checked>
                            <label class="form-check-label" for="include_overtime">
                                Overtime Payments
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="include_bonuses" name="include_bonuses" checked>
                            <label class="form-check-label" for="include_bonuses">
                                Bonuses & Allowances
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="include_deductions" name="include_deductions" checked>
                            <label class="form-check-label" for="include_deductions">
                                Deductions (Tax, Pension, etc.)
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="payroll_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="payroll_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Run Payroll</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mark as Paid Script -->
<script>
    function markAsPaid(payslipId, staffName) {
        if(confirm('Mark ' + staffName + ' as paid?')) {
            fetch('{{ route("admin.payroll.mark-paid") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    payslip_id: payslipId
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                }
            });
        }
    }
</script>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize popovers
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });

        // Charts
        const deptCtx = document.getElementById('departmentPayrollChart').getContext('2d');
        new Chart(deptCtx, {
            type: 'bar',
            data: @json($chartData['department']),
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Payroll by Department'
                    }
                }
            }
        });

        const costCtx = document.getElementById('costDistributionChart').getContext('2d');
        new Chart(costCtx, {
            type: 'pie',
            data: @json($chartData['cost']),
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Payroll Cost Distribution'
                    }
                }
            }
        });
    });
</script>
@endpush