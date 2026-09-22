@php
    $reasons = $reasons ?? [];
@endphp

@if (count($reasons))
    <div class="affiliate-reasons bg-light-2 pt-6 pb-6 py-5">
        <div class="container">
            <h2 class="title text-center mb-5">Why Refer People to Agii?</h2>

            <div class="row g-4">
                @foreach ($reasons as $reason)
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="{{ $reason['icon'] }} text-success mb-3" style="font-size: 2rem;"></i>
                                <h5 class="fw-bold mb-2">{{ $reason['title'] }}</h5>
                                <p class="text-muted mb-0">{{ $reason['description'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
