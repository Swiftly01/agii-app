@extends('layout.marketer')

@section('title', 'Marketers - Agii')
@section('page-title', 'Marketers')

@section('content')
    @include('staff.partials.flash')

    {{-- Platform-wide marketer totals --}}
    <div class="row mb-4">
        @foreach ([
            ['Total Marketers', number_format($totals['marketers']), 'fa-bullhorn', 'primary'],
            ['Vendors Onboarded', number_format($totals['vendors']), 'fa-store', 'success'],
            ['Marketer Earnings', '₦' . number_format($totals['earnings'], 2), 'fa-wallet', 'info'],
            ['Total Commission', '₦' . number_format($totals['commission'], 2), 'fa-coins', 'warning'],
        ] as [$label, $value, $icon, $color])
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas {{ $icon }} fa-2x text-{{ $color }} me-3"></i>
                        <div>
                            <small class="text-muted">{{ $label }}</small>
                            <h5 class="mb-0">{{ $value }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6 d-flex align-items-center gap-3">
                    <h6 class="card-title mb-0">All Marketers</h6>
                    <a href="{{ route('admin.marketers.vendors.export-pdf') }}" class="btn btn-sm btn-danger">
                        <i class="fas fa-file-pdf me-1"></i>Export Vendors PDF
                    </a>
                    @if ($totals['pending'] > 0)
                        <a href="{{ route('admin.marketers.index', ['status' => 'pending']) }}"
                            class="badge bg-warning text-dark text-decoration-none">
                            {{ $totals['pending'] }} awaiting approval
                        </a>
                    @endif
                </div>
                <div class="col-md-6">
                    <form method="GET" action="{{ route('admin.marketers.index') }}" class="d-flex justify-content-end">
                        <select name="status" class="form-select me-2" style="max-width: 170px;" onchange="this.form.submit()">
                            <option value="">All statuses</option>
                            @foreach (\App\Enums\MarketerStatus::cases() as $option)
                                <option value="{{ $option->value }}" @selected(request('status') === $option->value)>{{ $option->label() }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="search" class="form-control me-2" style="max-width: 300px;"
                            placeholder="Search name, email, phone or code" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if ($marketers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Marketer</th>
                                <th>Referral Code</th>
                                <th>Vendors Onboarded</th>
                                <th>Vendors With Products</th>
                                <th>Earnings</th>
                                <th>Total Commission</th>
                                <th>Status</th>
                                <th>Approval</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($marketers as $marketer)
                                <tr>
                                    <td>
                                        <h6 class="mb-1">{{ $marketer->user?->first_name }} {{ $marketer->user?->last_name }}</h6>
                                        <small class="text-muted">{{ $marketer->user?->email }}</small>
                                    </td>
                                    <td><code>{{ $marketer->referral_code }}</code></td>
                                    <td class="text-center">{{ $marketer->referred_vendors_count }}</td>
                                    <td class="text-center">{{ $marketer->vendors_with_products_count }}</td>
                                    <td>₦{{ number_format($marketer->total_earnings, 2) }}</td>
                                    <td>₦{{ number_format($marketer->total_commission ?? 0, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $marketer->is_active ? 'success' : 'secondary' }}">
                                            {{ $marketer->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $marketer->status->badgeClass() }}">{{ $marketer->status->label() }}</span>
                                        <div class="mt-1">@include('admin.marketers.partials.review-actions', ['marketer' => $marketer])</div>
                                    </td>
                                   <td>{{ $marketer->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('admin.marketers.show', $marketer) }}" class="btn btn-sm btn-primary"
                                            title="View Referrals">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $marketers->links() }}
                </div>
            @else
                <div class="text-center py-5 text-muted">No marketers found.</div>
            @endif
        </div>
    </div>
    @include('admin.marketers.partials.reject-modal')
@endsection
