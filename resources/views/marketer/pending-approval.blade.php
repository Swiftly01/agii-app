@extends('layout.marketer')

@section('title', 'Application Status - Agii Marketer')
@section('page-title', 'Dashboard')

@section('content')
    @include('staff.partials.flash')

    @php($rejected = $marketer?->isRejected())

    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas {{ $rejected ? 'fa-times-circle text-danger' : 'fa-hourglass-half text-warning' }} fa-3x mb-3"></i>

            <h3 class="mb-2">{{ $rejected ? 'Application not approved' : 'Pending approval' }}</h3>

            <p class="text-muted mb-3">
                @if ($rejected)
                    Unfortunately we could not approve your marketer application.
                @else
                    Thanks {{ auth()->user()->first_name }}, our team is reviewing your application. We will email you as soon as it is approved.
                @endif
            </p>

            <span class="badge {{ ($marketer?->status ?? \App\Enums\MarketerStatus::Pending)->badgeClass() }} fs-6">
                {{ ($marketer?->status ?? \App\Enums\MarketerStatus::Pending)->label() }}
            </span>

            @if ($rejected && $marketer->rejection_reason)
                <p class="mt-4 mb-0"><strong>Reason:</strong> {{ $marketer->rejection_reason }}</p>
                <p class="text-muted small mt-2 mb-0">Reply to the email we sent you if you believe this was a mistake.</p>
            @endif
        </div>
    </div>
@endsection
