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
                                            <p class="text-muted">or ₦{{ number_format($plan->yearly_price) }}/year (Save {{ number_format((1 - ($plan->yearly_price/($plan->monthly_price * 12))) * 100, 0) }}%)</p>

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
                                                @csrf
                                                <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                                                
                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <select name="billing_cycle" class="form-select form-select-sm" 
                                                                onchange="updateDurationOptions(this, {{ $plan->id }})" 
                                                                required>
                                                            <option value="monthly">Monthly Billing</option>
                                                            <option value="yearly">Yearly Billing</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <select name="months" id="duration-{{ $plan->id }}" class="form-select form-select-sm" 
                                                                onchange="calculateTotal(this, {{ $plan->id }})" 
                                                                required>
                                                            <option value="1">1 Month</option>
                                                            <option value="3">3 Months</option>
                                                            <option value="6">6 Months</option>
                                                            <option value="12">12 Months</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <!-- Display Price Calculation -->
                                                <div class="mb-3 p-2 bg-light rounded">
                                                    <strong>Total Amount:</strong> 
                                                    <span class="text-primary fw-bold" id="total-amount-{{ $plan->id }}">₦{{ number_format($plan->monthly_price) }}</span>
                                                    <span id="duration-display-{{ $plan->id }}"> (1 month)</span>
                                                </div>
                                                
                                                <button type="submit" class="btn btn-primary w-100">
                                                    Continue to Checkout
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

    <script>
        // Store plan prices globally
        const planPrices = {
            @foreach ($paymentPlans as $plan)
                {{ $plan->id }}: {
                    monthly: {{ $plan->monthly_price }},
                    yearly: {{ $plan->yearly_price }}
                },
            @endforeach
        };

        // Initialize calculations on page load
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($paymentPlans as $plan)
                calculateTotal(document.querySelector('#duration-{{ $plan->id }}'), {{ $plan->id }});
            @endforeach
        });

        function updateDurationOptions(selectElement, planId) {
            const billingCycle = selectElement.value;
            const durationSelect = document.getElementById(`duration-${planId}`);
            
            // Clear existing options
            durationSelect.innerHTML = '';
            
            if (billingCycle === 'monthly') {
                // Monthly billing options
                const monthlyOptions = [
                    { value: 1, text: '1 Month' },
                    { value: 3, text: '3 Months' },
                    { value: 6, text: '6 Months' },
                    { value: 12, text: '12 Months' }
                ];
                
                monthlyOptions.forEach(option => {
                    const opt = document.createElement('option');
                    opt.value = option.value;
                    opt.textContent = option.text;
                    durationSelect.appendChild(opt);
                });
            } else {
                // Yearly billing options
                const yearlyOptions = [
                    { value: 12, text: '1 Year' },
                    { value: 24, text: '2 Years' },
                    { value: 36, text: '3 Years' },
                    { value: 48, text: '4 Years' },
                    { value: 60, text: '5 Years' }
                ];
                
                yearlyOptions.forEach(option => {
                    const opt = document.createElement('option');
                    opt.value = option.value;
                    opt.textContent = option.text;
                    durationSelect.appendChild(opt);
                });
                
                // Auto-select 12 months for yearly
                durationSelect.value = 12;
            }
            
            // Recalculate total
            calculateTotal(durationSelect, planId);
        }

        function calculateTotal(selectElement, planId) {
            const billingCycleSelect = selectElement.closest('.row').querySelector('select[name="billing_cycle"]');
            const billingCycle = billingCycleSelect.value;
            const months = parseInt(selectElement.value);
            const prices = planPrices[planId];
            
            let totalAmount = 0;
            let durationText = '';
            
            if (billingCycle === 'monthly') {
                totalAmount = prices.monthly * months;
                durationText = ` (${months} month${months > 1 ? 's' : ''})`;
            } else {
                // For yearly billing, divide by 12 to get number of years
                const years = months / 12;
                totalAmount = prices.yearly * years;
                durationText = ` (${years} year${years > 1 ? 's' : ''})`;
            }
            
            // Update display
            const totalAmountElement = document.getElementById(`total-amount-${planId}`);
            const durationDisplayElement = document.getElementById(`duration-display-${planId}`);
            
            if (totalAmountElement) {
                totalAmountElement.textContent = `₦${totalAmount.toLocaleString()}`;
            }
            if (durationDisplayElement) {
                durationDisplayElement.textContent = durationText;
            }
        }
    </script>
@endsection