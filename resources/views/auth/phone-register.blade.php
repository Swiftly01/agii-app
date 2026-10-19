<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up with your phone number</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    @include('auth.partials.phone-styles')
</head>

<body>
    <div class="phone-card">
        <div class="phone-header">
            <div class="phone-icon"><i class="bi bi-phone"></i></div>
            <h1 class="h3 mb-0">Sign up with your phone</h1>
        </div>

        <div class="phone-body">
            <form method="POST" action="{{ route('phone.register.store') }}" novalidate>
                @csrf

                <div class="row g-3">
                    <div class="col-sm-6">
                        <label for="first_name" class="form-label fw-semibold">First name</label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}"
                            class="form-control @error('first_name') is-invalid @enderror" required>
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-sm-6">
                        <label for="last_name" class="form-label fw-semibold">Last name</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                            class="form-control @error('last_name') is-invalid @enderror" required>
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="phone" class="form-label fw-semibold">Phone number</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="0803 123 4567"
                            autocomplete="tel" class="form-control @error('phone') is-invalid @enderror" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="user_type" class="form-label fw-semibold">I want to</label>
                        <select id="user_type" name="user_type" class="form-select @error('user_type') is-invalid @enderror" required>
                            @foreach (config('phone_verification.allowed_user_types') as $type)
                                <option value="{{ $type }}" @selected(old('user_type', 'customer') === $type)>
                                    {{ $type === 'vendor' ? 'Sell on the platform' : 'Buy on the platform' }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-sm-6">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" id="password" name="password" autocomplete="new-password"
                            class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-sm-6">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirm password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            autocomplete="new-password" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label for="referral_code" class="form-label fw-semibold">Referral code <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="text" id="referral_code" name="referral_code" value="{{ old('referral_code', request('ref')) }}"
                            class="form-control @error('referral_code') is-invalid @enderror">
                        @error('referral_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" id="agree_terms" name="agree_terms" value="1"
                                class="form-check-input @error('agree_terms') is-invalid @enderror" @checked(old('agree_terms'))>
                            <label for="agree_terms" class="form-check-label">
                                I agree to the <a href="/terms-and-condition" target="_blank">terms and conditions</a>
                            </label>
                            @error('agree_terms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-4">
                    <i class="bi bi-send me-2"></i>Create account and send code
                </button>
            </form>

            <p class="text-center small text-muted mt-4 mb-0">
                Already have an account? <a href="{{ route('login') }}">Log in</a>
                &middot; <a href="{{ route('register') }}">Use email instead</a>
            </p>
        </div>
    </div>
</body>

</html>
