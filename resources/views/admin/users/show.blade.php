@extends('layout.marketer')

@section('title', $user->first_name . ' ' . $user->last_name . ' - User Details - Agii')
@section('page-title', 'User Details')

@php
    // Badge colour per user type
    $typeColors = [
        'admin' => 'danger',
        'vendor' => 'success',
        'marketer' => 'purple',
        'staff' => 'info',
        'customer' => 'warning',
    ];
    $typeColor = $typeColors[$user->user_type] ?? 'secondary';
@endphp

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- Profile summary --}}
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ $user->profile_image ? asset($user->profile_image) : asset('images/default-avatar.png') }}"
                        class="rounded-circle mb-3" width="100" height="100" alt="{{ $user->first_name }}">
                    <h5 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h5>
                    <p class="text-muted mb-2">Member since {{ $user->created_at->format('M Y') }}</p>

                    <div class="mb-3">
                        <span class="badge bg-{{ $typeColor }} me-1">{{ ucfirst($user->user_type) }}</span>
                        <span class="badge bg-{{ $user->email_verified_at ? 'success' : 'warning' }}">
                            {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                        </span>
                    </div>

                    <div class="text-start">
                        <p class="mb-2"><i class="fas fa-envelope me-2 text-muted"></i>{{ $user->email ?? 'Not provided' }}</p>
                        <p class="mb-2"><i class="fas fa-phone me-2 text-muted"></i>{{ $user->phone ?? 'Not provided' }}</p>
                        <p class="mb-2"><i class="fab fa-whatsapp me-2 text-muted"></i>{{ $user->whatsapp_number ?? 'Not provided' }}</p>
                        <p class="mb-0"><i class="fas fa-map-marker-alt me-2 text-muted"></i>
                            {{ collect([$user->address, $user->city, $user->state])->filter()->implode(', ') ?: 'Not provided' }}
                        </p>
                    </div>
                </div>
                <div class="card-footer d-flex gap-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm flex-fill">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm flex-fill">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            {{-- Quick stats --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="mb-0">{{ number_format($user->products_count) }}</h3>
                            <small class="text-muted">Products</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="mb-0">{{ number_format($user->marketercommission_transactions_count) }}</h3>
                            <small class="text-muted">Commission Transactions</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Account details --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Account Details</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Referral Code</dt>
                        <dd class="col-sm-8">{{ $user->referral_code ?? '—' }}</dd>

                        <dt class="col-sm-4">Email Verified</dt>
                        <dd class="col-sm-8">{{ $user->email_verified_at?->format('M d, Y h:i A') ?? 'Not verified' }}</dd>

                        <dt class="col-sm-4">Phone Verified</dt>
                        <dd class="col-sm-8">{{ $user->phone_verified_at?->format('M d, Y h:i A') ?? 'Not verified' }}</dd>

                        <dt class="col-sm-4">Joined</dt>
                        <dd class="col-sm-8">{{ $user->created_at->format('M d, Y h:i A') }}</dd>
                    </dl>
                </div>
            </div>

            {{-- Business details, shown only for vendors --}}
            @if ($user->user_type === 'vendor')
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Business Details</h6>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Business Name</dt>
                            <dd class="col-sm-8">{{ $user->business_name ?? '—' }}</dd>

                            <dt class="col-sm-4">Business Type</dt>
                            <dd class="col-sm-8">{{ $user->business_type ?? '—' }}</dd>

                            <dt class="col-sm-4">Category</dt>
                            <dd class="col-sm-8">{{ $user->business_category ?? '—' }}</dd>

                            <dt class="col-sm-4">Vendor Status</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-{{ $user->vendor_status === 'approved' ? 'success' : ($user->vendor_status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($user->vendor_status ?? 'pending') }}
                                </span>
                            </dd>

                            @if ($user->vendor_rejection_reason)
                                <dt class="col-sm-4">Rejection Reason</dt>
                                <dd class="col-sm-8">{{ $user->vendor_rejection_reason }}</dd>
                            @endif
                        </dl>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection