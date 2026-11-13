@extends('layout.marketer')

@section('title', 'Marketer Details - Agii')
@section('page-title', 'Marketer Details')

@section('content')
    @include('staff.partials.flash')

    <div class="card mb-4">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">{{ $marketer->user?->first_name }} {{ $marketer->user?->last_name }}</h5>
                <div class="text-muted">{{ $marketer->user?->email }} · {{ $marketer->user?->phone }}</div>
                <div class="mt-2">
                    Referral code: <code class="fs-6">{{ $marketer->referral_code }}</code>
                    <span class="badge bg-{{ $marketer->is_active ? 'success' : 'secondary' }} ms-2">
                        {{ $marketer->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="badge {{ $marketer->status->badgeClass() }} ms-1">{{ $marketer->status->label() }}</span>
                </div>
                @if ($marketer->application_note)
                    <p class="mt-2 mb-0"><strong>Application note:</strong> {{ $marketer->application_note }}</p>
                @endif
                @if ($marketer->isRejected() && $marketer->rejection_reason)
                    <p class="mt-2 mb-0 text-danger"><strong>Rejected:</strong> {{ $marketer->rejection_reason }}</p>
                @endif
            </div>
            <div class="d-flex gap-2 align-items-start">
                @include('admin.marketers.partials.review-actions', ['marketer' => $marketer])
                <a href="{{ route('admin.marketers.vendors.export-pdf', ['marketer' => $marketer->id]) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf me-2"></i>Export Vendors PDF
                </a>
                <a href="{{ route('admin.marketers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Marketers
                </a>
            </div>
        </div>
    </div>

    {{-- This marketer's headline numbers --}}
    <div class="row mb-4">
        @foreach ([['Vendors Onboarded', number_format($marketer->referred_vendors_count)], ['Vendors With Products', number_format($marketer->vendors_with_products_count)], ['Total Earnings', '₦' . number_format($marketer->total_earnings, 2)], ['Pending', '₦' . number_format($marketer->pending_earnings, 2)], ['Paid Out', '₦' . number_format($marketer->paid_earnings, 2)]] as [$label, $value])
            <div class="col-lg col-md-4 col-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <small class="text-muted">{{ $label }}</small>
                        <h5 class="mb-0">{{ $value }}</h5>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="card-title mb-0">Referrals</h6>
            @if ($referrals->total() > 0)
                <a href="{{ route('admin.marketers.vendors.export-pdf', ['marketer' => $marketer->id]) }}" class="btn btn-sm btn-danger">
                    <i class="fas fa-file-pdf me-1"></i>Export Referrals PDF
                </a>
            @endif
        </div>
        <div class="card-body">
            @if ($referrals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Referee (Vendor)</th>
                                <th>Referrer</th>
                                <th>Referral Code</th>
                                <th>Referred On</th>
                                <th>Products</th>
                                <th>Subscription</th>
                                <th>Commission</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($referrals as $vendor)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.vendors.show', $vendor->id) }}">
                                            {{ $vendor->first_name }} {{ $vendor->last_name }}
                                        </a>
                                        <br><small
                                            class="text-muted">{{ $vendor->business_name ?? $vendor->email }}</small>
                                    </td>
                                    <td>{{ $marketer->user?->first_name }} {{ $marketer->user?->last_name }}</td>
                                    <td><code>{{ $marketer->referral_code }}</code></td>
                                    <td>
                                        {{ $vendor->created_at?->format('M d, Y') ?? 'N/A' }}
                                        <br><small class="text-muted">{{ $vendor->created_at?->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $vendor->products_count > 0 ? 'success' : 'warning' }}">
                                            {{ $vendor->products_count > 0 ? $vendor->products_count . ' product(s)' : 'No products' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $vendor->activeSubscription ? 'success' : 'secondary' }}">
                                            {{ $vendor->activeSubscription ? 'Active' : 'None' }}
                                        </span>
                                    </td>
                                    <td>₦{{ number_format($vendor->commission_earned, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $referrals->links() }}
                </div>
            @else
                <div class="text-center py-5 text-muted">This marketer has not referred any vendors yet.</div>
            @endif
        </div>
    </div>
    @include('admin.marketers.partials.reject-modal')
@endsection
