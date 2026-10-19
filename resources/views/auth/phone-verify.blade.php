<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify your phone number</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    @include('auth.partials.phone-styles')
</head>

<body>
    <div class="phone-card">
        <div class="phone-header">
            <div class="phone-icon"><i class="bi bi-chat-dots"></i></div>
            <h1 class="h3 mb-0">Verify your phone</h1>
        </div>

        <div class="phone-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('status') === 'code-sent')
                <div class="alert alert-success">A new code is on its way.</div>
            @endif

            <p class="text-muted">
                Enter the {{ $codeLength }}-digit code we sent to <strong>{{ $maskedPhone }}</strong>.
            </p>

            <form method="POST" action="{{ route('phone.verification.verify') }}" novalidate>
                @csrf
                <input type="text" name="code" inputmode="numeric" autocomplete="one-time-code" autofocus
                    maxlength="{{ $codeLength }}" pattern="[0-9]*" placeholder="{{ str_repeat('•', $codeLength) }}"
                    class="form-control otp-input @error('code') is-invalid @enderror" required>
                @error('code')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                <button type="submit" class="btn btn-primary w-100 mt-3">
                    <i class="bi bi-check2-circle me-2"></i>Verify phone number
                </button>
            </form>

            <form method="POST" action="{{ route('phone.verification.resend') }}" class="mt-3">
                @csrf
                <button type="submit" id="resendButton" class="btn btn-outline-secondary w-100" disabled>
                    <i class="bi bi-arrow-repeat me-2"></i><span id="resendLabel">Resend code</span>
                </button>
            </form>

            <a href="{{ route('logout') }}" class="d-block text-center small mt-4">Log out</a>
        </div>
    </div>

    <script>
        (function () {
            var remaining = {{ (int) $resendIn }};
            var button = document.getElementById('resendButton');
            var label = document.getElementById('resendLabel');

            // Counts down the server enforced cooldown before enabling resend
            function tick() {
                if (remaining <= 0) {
                    button.disabled = false;
                    label.textContent = 'Resend code';
                    return;
                }
                label.textContent = 'Resend code in ' + remaining + 's';
                remaining -= 1;
                setTimeout(tick, 1000);
            }

            tick();
        })();
    </script>
</body>

</html>
