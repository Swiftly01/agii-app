{{-- resources/views/vendor/checkout.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Checkout - Agii</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <style>
        :root {
            --primary-color: #8fc74a;
            --secondary-color: #7ab436;
            --dark-color: #2c3e50;
        }

        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .plan-summary {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .payment-form {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
        }
    </style>
</head>

<body>
    <div class="container py-5 checkout-container">
        <div class="row">
            <div class="col-lg-8">
                <div class="payment-form">
                    <h2 class="mb-4">Complete Your Purchase</h2>

                    <form action="{{ route('vendor.initialize-payment') }}" method="POST" id="paymentForm">
                        @csrf
                        @php
                            $months = $months ?? 1;
                            // Use pricing total instead of totalAmount
                            $displayAmount = $pricing['total'] ?? 0;
                        @endphp
                        <input type="hidden" name="plan_id" value="{{ $plan->id ?? '' }}">
                        <input type="hidden" name="billing_cycle" value="{{ $billingCycle ?? 'monthly' }}">
                        <input type="hidden" name="months" value="{{ $months }}" id="monthsInput">

                        <!-- Billing Duration -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Subscription Duration</label>
                            <div class="row g-2">
                                @foreach ([1, 3, 6, 12] as $duration)
                                    <div class="col-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="duration"
                                                id="duration{{ $duration }}" value="{{ $duration }}"
                                                {{ $months == $duration ? 'checked' : '' }}>
                                            <label class="form-check-label" for="duration{{ $duration }}">
                                                {{ $duration }}
                                                {{ ($billingCycle ?? 'monthly') == 'yearly' ? 'Year' : 'Month' }}{{ $duration > 1 ? 's' : '' }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="duration"
                                            id="durationCustom" value="custom">
                                        <label class="form-check-label" for="durationCustom">
                                            Custom
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Duration Input -->
                            <div id="customDuration" class="mt-2" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" id="customMonths" min="1"
                                            max="60" placeholder="Enter months (1-60)">
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Maximum 60 months</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Payment Method</label>
                            <div class="alert alert-info">
                                <h6><i class="bi bi-credit-card"></i> Pay with Paystack</h6>
                                <p class="mb-0">You will be redirected to Paystack's secure payment page to complete
                                    your payment.</p>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="paystack"
                                    value="paystack" checked>
                                <label class="form-check-label" for="paystack">
                                    <strong>Paystack</strong> - Card, Bank Transfer, USSD, Mobile Money
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mt-4" id="payButton">
                            Pay Now - ₦{{ number_format($displayAmount, 2) }}
                        </button>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="bi bi-shield-check"></i> Your payment is secure and encrypted
                            </small>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="plan-summary">
                    <h4 class="mb-4">Order Summary</h4>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Plan:</span>
                        <strong>{{ ucfirst($plan->tier ?? '') }} - {{ ucfirst($plan->type ?? '') }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Billing Cycle:</span>
                        <strong>{{ ucfirst($billingCycle ?? 'monthly') }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Duration:</span>
                        <strong id="durationDisplay">
                            {{ $months }}
                            {{ ($billingCycle ?? 'monthly') == 'yearly' ? 'Year' : 'Month' }}{{ $months > 1 ? 's' : '' }}
                        </strong>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="price-breakdown">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="base-price-label">Base Price:</span>
                            <strong class="base-price">₦{{ number_format($pricing['subtotal'] ?? 0, 2) }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="vat-rate-label">VAT ({{ $pricing['vat_rate'] ?? 7.5 }}%):</span>
                            <strong class="vat-amount">₦{{ number_format($pricing['vat_amount'] ?? 0, 2) }}</strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Total Amount:</span>
                            <strong
                                class="fs-5 text-primary total-amount">₦{{ number_format($pricing['total'] ?? 0, 2) }}</strong>
                        </div>
                    </div>

                    <!-- VAT Notice -->
                    <div class="alert alert-info mt-3">
                        <small>
                            <i class="bi bi-info-circle"></i>
                            All prices include 7.5% Value Added Tax (VAT) as required by Nigerian law.
                        </small>
                    </div>

                    <!-- Plan Features -->
                    <div class="mt-4">
                        <h6>Plan Includes:</h6>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check text-success"></i>
                                @if ($plan->product_limit ?? 0)
                                    Up to {{ $plan->product_limit }} products
                                @else
                                    Unlimited products
                                @endif
                            </li>
                            <li><i class="bi bi-check text-success"></i>
                                {{ ucfirst($plan->support_level ?? 'basic') }} support
                            </li>
                            @if ($plan->featured_listings ?? false)
                                <li><i class="bi bi-check text-success"></i>
                                    {{ $plan->featured_listings_count ?? 0 }} featured listings
                                </li>
                            @endif
                            @if ($plan->analytics ?? false)
                                <li><i class="bi bi-check text-success"></i> Analytics dashboard</li>
                            @endif
                            @if ($plan->custom_storefront ?? false)
                                <li><i class="bi bi-check text-success"></i> Custom storefront</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const durationRadios = document.querySelectorAll('input[name="duration"]');
            const customDurationDiv = document.getElementById('customDuration');
            const customMonthsInput = document.getElementById('customMonths');
            const monthsInput = document.getElementById('monthsInput');
            const durationDisplay = document.getElementById('durationDisplay');
            const payButton = document.getElementById('payButton');

            // Duration selection
            durationRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'custom') {
                        customDurationDiv.style.display = 'block';
                        customMonthsInput.focus();
                    } else {
                        customDurationDiv.style.display = 'none';
                        monthsInput.value = this.value;
                        updateDurationDisplay(this.value);
                        updatePricing();
                    }
                });
            });

            // Custom months input
            customMonthsInput.addEventListener('input', function() {
                const months = parseInt(this.value) || 1;
                if (months >= 1 && months <= 60) {
                    monthsInput.value = months;
                    updateDurationDisplay(months);
                    updatePricing();
                }
            });

            function updateDurationDisplay(months) {
                const billingCycle = '{{ $billingCycle ?? 'monthly' }}';
                const unit = billingCycle === 'yearly' ? 'Year' : 'Month';
                const plural = months > 1 ? 's' : '';
                durationDisplay.textContent = `${months} ${unit}${plural}`;
            }

            function calculateVAT(subtotal, vatRate = 7.5) {
                const vatAmount = (subtotal * vatRate) / 100;
                const total = subtotal + vatAmount;

                return {
                    subtotal: subtotal,
                    vat_amount: vatAmount,
                    vat_rate: vatRate,
                    total: total,
                    total_kobo: total * 100
                };
            }

            function updatePricing() {
                const billingCycle = '{{ $billingCycle ?? 'monthly' }}';
                const basePrice = billingCycle === 'yearly' ? {{ $plan->yearly_price ?? 0 }} :
                    {{ $plan->monthly_price ?? 0 }};
                const months = parseInt(document.getElementById('monthsInput').value);

                let subtotal;
                if (billingCycle === 'yearly') {
                    subtotal = basePrice * Math.ceil(months / 12);
                } else {
                    subtotal = basePrice * months;
                }

                const pricing = calculateVAT(subtotal);

                // Update display
                document.querySelector('.base-price').textContent = '₦' + pricing.subtotal.toFixed(2);
                document.querySelector('.vat-amount').textContent = '₦' + pricing.vat_amount.toFixed(2);
                document.querySelector('.vat-rate-label').innerHTML = `VAT (${pricing.vat_rate}%):`;
                document.querySelector('.total-amount').textContent = '₦' + pricing.total.toFixed(2);

                // Update pay button
                payButton.textContent = `Pay Now - ₦${pricing.total.toFixed(2)}`;
            }

            // Initialize display
            updateDurationDisplay({{ $months }});

            // Initialize pricing on page load
            updatePricing();
        });
    </script>
</body>

</html>
