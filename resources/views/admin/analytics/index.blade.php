@extends('layout.marketer')

@section('title', 'App Analytics - Agii')
@section('page-title', 'App Analytics')

@section('content')
    @php
        $users = $report['users'];
        $logins = $report['logins'];
        $products = $report['products'];
        $engagement = $report['engagement'];
    @endphp

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h5 class="mb-0">Showing: {{ $period->label() }}</h5>
        <div class="btn-group">
            @foreach ($periods as $option)
                <a href="{{ route('admin.analytics', ['period' => $option->value]) }}"
                    class="btn btn-sm {{ $option === $period ? 'btn-primary' : 'btn-outline-primary' }}">
                    {{ $option->label() }}
                </a>
            @endforeach
        </div>
    </div>

    <h6 class="text-uppercase text-muted mb-3">Users</h6>
    <div class="row">
        @include('admin.analytics.partials.stat-card', ['label' => 'Total Users', 'value' => $users['total'], 'icon' => 'fa-users'])
        @include('admin.analytics.partials.stat-card', ['label' => 'New Users', 'value' => $users['new'], 'icon' => 'fa-user-plus', 'color' => 'success', 'hint' => $period->label()])
        @include('admin.analytics.partials.stat-card', ['label' => 'Sellers', 'value' => $users['sellers'], 'icon' => 'fa-store', 'color' => 'warning'])
        @include('admin.analytics.partials.stat-card', ['label' => 'Buyers', 'value' => $users['buyers'], 'icon' => 'fa-shopping-bag', 'color' => 'info'])
    </div>

    <h6 class="text-uppercase text-muted mb-3">Logins</h6>
    <div class="row">
        @include('admin.analytics.partials.stat-card', ['label' => 'Total Logins', 'value' => $logins['total'], 'icon' => 'fa-sign-in-alt'])
        @include('admin.analytics.partials.stat-card', ['label' => 'Unique Users Logged In', 'value' => $logins['uniqueUsers'], 'icon' => 'fa-user-check', 'color' => 'success'])
        @include('admin.analytics.partials.stat-card', ['label' => 'Sellers Logged In', 'value' => $logins['sellerUsers'], 'icon' => 'fa-store', 'color' => 'warning', 'hint' => number_format($logins['sellerLogins']) . ' logins'])
        @include('admin.analytics.partials.stat-card', ['label' => 'Buyers Logged In', 'value' => $logins['buyerUsers'], 'icon' => 'fa-shopping-bag', 'color' => 'info', 'hint' => number_format($logins['buyerLogins']) . ' logins'])
    </div>

    <h6 class="text-uppercase text-muted mb-3">Products</h6>
    <div class="row">
        @include('admin.analytics.partials.stat-card', ['label' => 'Product Clicks', 'value' => $products['clicks'], 'icon' => 'fa-mouse-pointer'])
        @include('admin.analytics.partials.stat-card', ['label' => 'Unique Visitors', 'value' => $products['uniqueVisitors'], 'icon' => 'fa-eye', 'color' => 'success'])
        @include('admin.analytics.partials.stat-card', ['label' => 'Clicks by Logged-in Users', 'value' => $products['loggedInClicks'], 'icon' => 'fa-user', 'color' => 'info', 'hint' => number_format($products['guestClicks']) . ' from guests'])
        @include('admin.analytics.partials.stat-card', ['label' => 'Total Products', 'value' => $products['total'], 'icon' => 'fa-box', 'color' => 'warning', 'hint' => number_format($products['new']) . ' new'])
    </div>

    <h6 class="text-uppercase text-muted mb-3">Engagement</h6>
    <div class="row">
        @include('admin.analytics.partials.stat-card', ['label' => 'Online Now', 'value' => $logins['onlineNow'], 'icon' => 'fa-circle', 'color' => 'success', 'hint' => 'Active in last 5 min'])
        @include('admin.analytics.partials.stat-card', ['label' => 'Seller Inquiries', 'value' => $engagement['inquiries'], 'icon' => 'fa-envelope', 'color' => 'primary'])
        @include('admin.analytics.partials.stat-card', ['label' => 'Reviews', 'value' => $engagement['reviews'], 'icon' => 'fa-star', 'color' => 'warning'])
        @include('admin.analytics.partials.stat-card', ['label' => 'Push Subscribers', 'value' => $engagement['pushSubscribers'], 'icon' => 'fa-bell', 'color' => 'info'])
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <h6 class="mb-3">Logins and product clicks per day</h6>
                    <canvas id="trendChart" height="110"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <h6 class="mb-3">Most clicked products</h6>
                    @forelse ($products['topClicked'] as $item)
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-truncate me-2">{{ $item['title'] }}</span>
                            <strong>{{ number_format($item['clicks']) }}</strong>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No product clicks in this period yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .stat-card { border: none; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05); }
        .stat-icon { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
    </style>
@endpush

@push('scripts')
    <script>
        const trend = @json($report['trend']);

        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: trend.labels,
                datasets: [
                    { label: 'Logins', data: trend.logins, borderColor: '#667eea', tension: 0.3 },
                    { label: 'Product clicks', data: trend.clicks, borderColor: '#f59e0b', tension: 0.3 },
                ],
            },
            options: { scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } },
        });
    </script>
@endpush
