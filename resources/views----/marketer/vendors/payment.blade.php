<!-- resources/views/marketer/vendors/payment.blade.php -->
@extends('layout.layout')
@section('title', 'Payment Setup - Marketer Dashboard')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-credit-card"></i> Setup Vendor Payment</h4>
                    </div>
                    <div class="card-body">
                        <!-- Vendor Info -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">Vendor Information</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Name:</strong> {{ $vendor->first_name }} {{ $vendor->last_name }}<br>
                                            <strong>Email:</strong> {{ $vendor->email }}<br>
                                            <strong>Phone:</strong> {{ $vendor->phone }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Business:</strong> {{ $vendor->business_name }}<br>
                                            <strong>Category:</strong> {{ $vendor->business_category }}<br>
                                            <strong>Location:</strong> {{ $vendor->city }}, {{ $vendor->state }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Plans -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-4">Select Payment Plan</h5>
                            </div>

                            @foreach ($paymentPlans as $plan)
                                <div class="col-md-6 mb-4">
                                    <div class="card plan-card h-100 {{ $plan->is_popular ? 'border-primary' : '' }}">
                                        @if ($plan->is_popular)
                                            <div class="card-header bg-primary text-white text-center">
                                                <strong>Most Popular</strong>
                                            </div>
                                        @endif
                                        <div class="card-body text-center">
                                            <h4 class="card-title">{{ $plan->name }}</h4>
                                            <h2 class="text-primary">
                                                ₦{{ number_format($plan->monthly_price) }}
                                                <small class="text-muted fs-6">/month</small>
                                            </h2>
                                            <p class="text-muted">or ₦{{ number_format($plan->yearly_price) }}/year</p>

                                            <ul class="list-unstyled mb-4">
                                                @foreach (explode("\n", $plan->features) as $feature)
                                                    @if (trim($feature))
                                                        <li class="mb-2">
                                                            <i class="bi bi-check-circle text-success me-2"></i>
                                                            {{ trim($feature) }}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>

                                            <!-- Plan Selection Form -->
                                            <form
                                                action="{{ route('marketer.vendors.checkout', [$vendor->id, $plan->id]) }}"
                                                method="GET">
                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <select name="billing_cycle" class="form-select form-select-sm"
                                                            required>
                                                            <option value="monthly">Monthly</option>
                                                            <option value="yearly">Yearly</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <select name="months" class="form-select form-select-sm" required>
                                                            <option value="1">1 Month</option>
                                                            <option value="3">3 Months</option>
                                                            <option value="6">6 Months</option>
                                                            <option value="12">12 Months</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary w-100">
                                                    Select Plan
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Commission Info -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Commission Information</h6>
                                    <p class="mb-0">
                                        You will earn <strong>10% commission</strong> on all vendor subscription payments.
                                        Commission will be added to your pending earnings after successful payment.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('marketer.vendors.show', $vendor->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Vendor
                            </a>
                            <a href="{{ route('marketer.dashboard') }}" class="btn btn-outline-primary">
                                <i class="bi bi-speedometer"></i> Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .plan-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 2px solid transparent;
        }

        .plan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .plan-card.border-primary {
            border-color: #0d6efd !important;
        }
    </style>
@endsection
