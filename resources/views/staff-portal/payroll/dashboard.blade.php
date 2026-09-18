{{-- resources/views/staff-portal/payroll/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Payroll & Benefits</h1>
            <p class="lead">View your salary details and benefits</p>
        </div>
        <div class="col-md-4 text-end">
            @if($currentPayslip)
                <a href="{{ route('staff.payroll.payslip', $currentPayslip->id) }}" 
                   class="btn btn-primary" target="_blank">
                    <i class="fas fa-file-invoice-dollar"></i> Current Payslip
                </a>
            @endif
        </div>
    </div>

    <!-- Salary Summary -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Salary Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted">Basic Salary</h6>
                                <h3 class="text-primary">₦{{ number_format($salaryInfo['basic_salary'], 2) }}</h3>
                                <small>Monthly</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted">Gross Salary</h6>
                                <h3 class="text-success">₦{{ number_format($salaryInfo['gross_salary'], 2) }}</h3>
                                <small>Monthly</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted">Net Salary</h6>
                                <h3 class="text-info">₦{{ number_format($salaryInfo['net_salary'], 2) }}</h3>
                                <small>Monthly (Average)</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted">Next Payday</h6>
                                <h3 class="text-warning">{{ optional($nextPayday)->format('j M') ?? 'N/A' }}</h3>
                                <small>{{ optional($nextPayday)->format('l') ?? '' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payslips -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-alt"></i> Recent Payslips</h5>
                    <a href="{{ route('staff.payroll.history') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Payslip #</th>
                                    <th>Period</th>
                                    <th>Payment Date</th>
                                    <th>Gross</th>
                                    <th>Deductions</th>
                                    <th>Net Pay</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayslips as $payslip)
                                <tr>
                                    <td>
                                        <strong>{{ $payslip->payslip_number }}</strong>
                                        @if(!$payslip->is_viewed)
                                            <span class="badge bg-danger">New</span>
                                        @endif
                                    </td>
                                    <td>{{ $payslip->payroll->payroll_period }}</td>
                                    <td>{{ $payslip->payment_date->format('M d, Y') }}</td>
                                    <td>₦{{ number_format($payslip->gross_earning, 2) }}</td>
                                    <td class="text-danger">-₦{{ number_format($payslip->total_deductions, 2) }}</td>
                                    <td class="text-success">
                                        <strong>₦{{ number_format($payslip->net_salary, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $payslip->status == 'paid' ? 'success' : ($payslip->status == 'approved' ? 'info' : 'warning') }}">
                                            {{ ucfirst($payslip->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('staff.payroll.payslip', $payslip->id) }}" 
                                               class="btn btn-primary" target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('staff.payroll.download', $payslip->id) }}" 
                                               class="btn btn-success">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            @if($payslip->status == 'generated')
                                                <button class="btn btn-info" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#queryModal"
                                                        data-payslip-id="{{ $payslip->id }}">
                                                    <i class="fas fa-question-circle"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No payslips available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Benefits Summary -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-shield-alt"></i> Benefits Summary</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($benefits as $benefit)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <h6 class="mb-0">{{ $benefit->name }}</h6>
                                <small class="text-muted">{{ ucfirst($benefit->type) }}</small>
                            </div>
                            <div class="text-end">
                                @if($benefit->company_contribution)
                                    <small class="text-success">+₦{{ number_format($benefit->company_contribution, 2) }}</small>
                                    <br>
                                @endif
                                @if($benefit->employee_contribution)
                                    <small class="text-danger">-₦{{ number_format($benefit->employee_contribution, 2) }}</small>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="list-group-item text-center text-muted">
                            No benefits assigned
                        </div>
                        @endforelse
                    </div>
                    
                    <!-- Total Benefits Value -->
                    <div class="mt-3 p-3 bg-light rounded">
                        <div class="d-flex justify-content-between">
                            <strong>Total Company Contribution:</strong>
                            <span class="text-success">₦{{ number_format($totalBenefits, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Salary Breakdown -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-pie"></i> Salary Breakdown (Average)</h6>
                </div>
                <div class="card-body">
                    <canvas id="salaryChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Yearly Summary -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar"></i> Yearly Earnings Summary - {{ date('Y') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Month</th>
                                    <th>Basic Salary</th>
                                    <th>Allowances</th>
                                    <th>Overtime</th>
                                    <th>Bonus</th>
                                    <th>Gross</th>
                                    <th>Deductions</th>
                                    <th>Tax</th>
                                    <th>Pension</th>
                                    <th>Net Pay</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($yearlySummary as $month)
                                <tr>
                                    <td><strong>{{ $month['month'] }}</strong></td>
                                    <td>₦{{ number_format($month['basic'], 2) }}</td>
                                    <td>₦{{ number_format($month['allowances'], 2) }}</td>
                                    <td>₦{{ number_format($month['overtime'], 2) }}</td>
                                    <td>₦{{ number_format($month['bonus'], 2) }}</td>
                                    <td class="table-success">₦{{ number_format($month['gross'], 2) }}</td>
                                    <td class="table-danger">-₦{{ number_format($month['deductions'], 2) }}</td>
                                    <td class="table-warning">-₦{{ number_format($month['tax'], 2) }}</td>
                                    <td class="table-info">-₦{{ number_format($month['pension'], 2) }}</td>
                                    <td class="table-primary">
                                        <strong>₦{{ number_format($month['net'], 2) }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                                <!-- Totals -->
                                <tr class="table-active">
                                    <td><strong>TOTAL</strong></td>
                                    <td><strong>₦{{ number_format($yearlyTotals['basic'], 2) }}</strong></td>
                                    <td><strong>₦{{ number_format($yearlyTotals['allowances'], 2) }}</strong></td>
                                    <td><strong>₦{{ number_format($yearlyTotals['overtime'], 2) }}</strong></td>
                                    <td><strong>₦{{ number_format($yearlyTotals['bonus'], 2) }}</strong></td>
                                    <td class="table-success"><strong>₦{{ number_format($yearlyTotals['gross'], 2) }}</strong></td>
                                    <td class="table-danger"><strong>-₦{{ number_format($yearlyTotals['deductions'], 2) }}</strong></td>
                                    <td class="table-warning"><strong>-₦{{ number_format($yearlyTotals['tax'], 2) }}</strong></td>
                                    <td class="table-info"><strong>-₦{{ number_format($yearlyTotals['pension'], 2) }}</strong></td>
                                    <td class="table-primary">
                                        <strong>₦{{ number_format($yearlyTotals['net'], 2) }}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Query Modal -->
<div class="modal fade" id="queryModal" tabindex="-1" aria-labelledby="queryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="queryModalLabel">Query Payslip</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('staff.payroll.query') }}" method="POST">
                @csrf
                <input type="hidden" name="payslip_id" id="query_payslip_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="query_subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="query_subject" name="subject" 
                               placeholder="e.g., Incorrect overtime calculation" required>
                    </div>
                    <div class="mb-3">
                        <label for="query_message" class="form-label">Message</label>
                        <textarea class="form-control" id="query_message" name="message" 
                                  rows="4" placeholder="Please describe your query in detail..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Submit Query</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Salary Chart
        const salaryCtx = document.getElementById('salaryChart').getContext('2d');
        new Chart(salaryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Basic Salary', 'Allowances', 'Overtime', 'Deductions', 'Tax', 'Pension'],
                datasets: [{
                    data: @json($salaryChartData),
                    backgroundColor: [
                        '#3498db', // Basic
                        '#2ecc71', // Allowances
                        '#f39c12', // Overtime
                        '#e74c3c', // Deductions
                        '#9b59b6', // Tax
                        '#1abc9c'  // Pension
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Query Modal
        const queryModal = document.getElementById('queryModal');
        if (queryModal) {
            queryModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                document.getElementById('query_payslip_id').value = button.getAttribute('data-payslip-id');
            });
        }
    });
</script>
@endpush