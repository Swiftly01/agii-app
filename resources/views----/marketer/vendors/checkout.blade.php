<!-- resources/views/marketer/vendors/checkout.blade.php -->
@extends('layout.layout')
@section('title', 'Checkout - Marketer Dashboard')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-cart-check"></i> Checkout</h4>
                    </div>
                    <div class="card-body">
                        <!-- Order Summary -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <h5>Order Summary</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td><strong>Plan:</strong></td>
                                            <td>{{ $plan->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Billing Cycle:</strong></td>
                                            <td>{{ ucfirst($billingCycle) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Duration:</strong></td>
                                            <td>{{ $months }} month(s)</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Vendor:</strong></td>
                                            <td>{{ $vendor->first_name }} {{ $vendor->last_name }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5>Pricing</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>Subtotal:</td>
                                            <td class="text-end">₦{{ number_format($pricing['subtotal'], 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>VAT ({{ $pricing['vat_rate'] }}%):</td>
                                            <td class="text-end">₦{{ number_format($pricing['vat_amount'], 2) }}</td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td><strong>Total:</strong></td>
                                            <td class="text-end"><strong>₦{{ number_format($pricing['total'], 2) }}</strong>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Commission Info -->
                        <div class="alert alert-info mb-4">
                            <h6 class="alert-heading"><i class="bi bi-graph-up"></i> Commission Earnings</h6>
                            <p class="mb-0">
                                You will earn <strong>₦{{ number_format($pricing['total'] * 0.1, 2) }}</strong>
                                (10% of ₦{{ number_format($pricing['total'], 2) }}) as commission from this payment.
                            </p>
                        </div>

                        <!-- Payment Form -->
                        <form action="{{ route('marketer.vendors.process-payment', $vendor->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <input type="hidden" name="billing_cycle" value="{{ $billingCycle }}">
                            <input type="hidden" name="months" value="{{ $months }}">

                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2">Payment Method</h5>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="paystack"
                                            value="paystack" checked>
                                        <label class="form-check-label" for="paystack">
                                            <i class="bi bi-credit-card-2-front"></i> Pay with Paystack (Card, Bank
                                            Transfer, USSD)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('marketer.vendors.payment', $vendor->id) }}"
                                    class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Change Plan
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-lock"></i> Pay ₦{{ number_format($pricing['total'], 2) }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
