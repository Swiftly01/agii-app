@php
    // Sensible fallbacks so this partial never breaks if included without data.
    $howItWorks = $howItWorks ?? [];
    $benefits = $benefits ?? [];
@endphp

<div class="bg-light-2 pt-6 pb-6 mb-6 mb-lg-8 py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="title">How It Works</h2>
                <ol class="list-unstyled">
                    @foreach ($howItWorks as $index => $step)
                        <li class="d-flex align-items-start mb-3">
                            <span class="badge bg-success rounded-circle me-3 flex-shrink-0"
                                style="width: 28px; height: 28px; line-height: 20px;">
                                {{ $index + 1 }}
                            </span>
                            <span>{{ $step }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>

            <div class="col-lg-6">
                <h2 class="title">What You Get</h2>
                <ul class="list-unstyled">
                    @foreach ($benefits as $benefit)
                        <li class="d-flex align-items-start mb-3">
                            <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                            <span>{{ $benefit }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
