@extends('layout.layout')
@section('title', 'Homepage Spotlight - Vendor Dashboard')
@section('content')

    @php
        $isActive = $spotlight?->isActive();
        $canPay = $purchasesOpen && $products->isNotEmpty();
    @endphp

    <div class="container py-4" style="max-width: 720px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 fw-bold mb-0">Homepage Spotlight</h1>
            <a href="{{ route('vendor.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to dashboard</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <p class="mb-3">
                    Show one of your listings in the hero banner on the home page. Buyers can open it
                    or chat with you straight from the slide.
                </p>

                <div class="mb-3">
                    <span class="fs-3 fw-bold">₦{{ number_format($monthlyPrice) }}</span>
                    <span class="text-muted">/ month</span>
                </div>

                @if ($isActive)
                    <div class="alert alert-success mb-0">
                        Live until <strong>{{ $spotlight->expires_at->format('M j, Y') }}</strong>
                        @if ($spotlight->product)
                            showing <strong>{{ $spotlight->product->title }}</strong>.
                        @endif
                    </div>
                @elseif ($spotlight)
                    <div class="alert alert-warning mb-0">
                        Your spotlight expired on {{ $spotlight->expires_at->format('M j, Y') }}. Pay again to put a product back in the hero.
                    </div>
                @endif
            </div>
        </div>

        @if ($products->isEmpty())
            <div class="alert alert-info">
                You need at least one live listing before you can use the spotlight.
                <a href="{{ route('vendor.showadvert') }}">Go to My Adverts</a>
            </div>
        @endif

        @unless ($purchasesOpen)
            <div class="alert alert-secondary">Spotlight purchases are closed right now. Please check back later.</div>
        @endunless

        @if ($isActive && $products->isNotEmpty())
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h6 fw-bold">Change the product shown</h2>
                    <form method="POST" action="{{ route('spotlight.product') }}" class="d-flex gap-2">
                        @csrf
                        @method('PUT')
                        <x-spotlight.product-select :products="$products" :selected="$spotlight->product_id" />
                        <button type="submit" class="btn btn-outline-success text-nowrap">Update</button>
                    </form>
                </div>
            </div>
        @endif

        @if ($products->isNotEmpty())
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h6 fw-bold">{{ $isActive ? 'Add another month' : 'Go live in the hero' }}</h2>
                    <form method="POST" action="{{ route('spotlight.checkout') }}" class="d-flex flex-column gap-2">
                        @csrf
                        <x-spotlight.product-select :products="$products" :selected="$spotlight?->product_id" />
                        <button type="submit" class="btn btn-success" @disabled(! $canPay)>
                            <i class="bi bi-credit-card me-2"></i>Pay ₦{{ number_format($monthlyPrice) }} with Paystack
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
