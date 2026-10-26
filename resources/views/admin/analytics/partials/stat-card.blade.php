<div class="col-xl-3 col-md-6 mb-4">
    <div class="card stat-card h-100">
        <div class="card-body d-flex align-items-center">
            <div class="stat-icon bg-{{ $color ?? 'primary' }} text-white rounded-circle me-3">
                <i class="fas {{ $icon }}"></i>
            </div>
            <div>
                <h6 class="text-muted mb-1">{{ $label }}</h6>
                <h4 class="mb-0">{{ is_null($value) ? 'N/A' : number_format($value) }}</h4>
                @isset($hint)
                    <small class="text-muted">{{ $hint }}</small>
                @endisset
            </div>
        </div>
    </div>
</div>
