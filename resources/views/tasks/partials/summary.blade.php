{{-- Status counts for the current scope --}}
<div class="row mb-4">
    @foreach ([
        ['Pending', $summary['pending'], 'warning', 'fa-hourglass-half'],
        ['In progress', $summary['in_progress'], 'primary', 'fa-spinner'],
        ['Completed', $summary['completed'], 'success', 'fa-check'],
        ['Overdue', $summary['overdue'], 'danger', 'fa-exclamation-triangle'],
    ] as [$label, $value, $color, $icon])
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-{{ $color }} text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:44px;height:44px">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">{{ $label }}</h6>
                        <h4 class="mb-0">{{ number_format($value) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
