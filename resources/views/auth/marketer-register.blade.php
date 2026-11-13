<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become a marketer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    @include('auth.partials.phone-styles')
</head>

<body>
    <div class="phone-card">
        <div class="phone-header">
            <div class="phone-icon"><i class="bi bi-megaphone"></i></div>
            <h1 class="h3 mb-1">Become a marketer</h1>
            <p class="mb-0 small">Onboard vendors and earn commission. Applications are reviewed by our team.</p>
        </div>

        <div class="phone-body">
            <form method="POST" action="{{ route('marketer.register.store') }}" novalidate>
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
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email"
                            class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="phone" class="form-label fw-semibold">Phone number</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="0803 123 4567"
                            autocomplete="tel" class="form-control @error('phone') is-invalid @enderror" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-sm-6">
                        <label for="state" class="form-label fw-semibold">State</label>
                        <select id="state" name="state" data-old="{{ old('state') }}"
                            class="form-select @error('state') is-invalid @enderror" required>
                            <option value="">Select state</option>
                        </select>
                        @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-sm-6">
                        <label for="local_government" class="form-label fw-semibold">Local government</label>
                        <select id="local_government" name="local_government" data-old="{{ old('local_government') }}"
                            class="form-select @error('local_government') is-invalid @enderror" required disabled>
                            <option value="">Select state first</option>
                        </select>
                        @error('local_government')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="address" class="form-label fw-semibold">Address</label>
                        <input type="text" id="address" name="address" value="{{ old('address') }}"
                            class="form-control @error('address') is-invalid @enderror" required>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="application_note" class="form-label fw-semibold">
                            How will you find vendors? <span class="text-muted fw-normal">(optional)</span>
                        </label>
                        <textarea id="application_note" name="application_note" rows="3" maxlength="1000"
                            class="form-control @error('application_note') is-invalid @enderror">{{ old('application_note') }}</textarea>
                        @error('application_note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-sm-6">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group has-validation">
                            <input type="password" id="password" name="password" autocomplete="new-password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            <button type="button" class="btn btn-outline-secondary" data-toggle-password="password"
                                aria-label="Show or hide password"><i class="bi bi-eye"></i></button>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirm password</label>
                        <div class="input-group has-validation">
                            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                                class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary" data-toggle-password="password_confirmation"
                                aria-label="Show or hide password"><i class="bi bi-eye"></i></button>
                        </div>
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
                    <i class="bi bi-send me-2"></i>Submit application
                </button>
            </form>

            <p class="text-center small text-muted mt-4 mb-0">
                Already applied? <a href="{{ route('login') }}">Log in</a>
            </p>
        </div>
    </div>

    <script src="{{ asset('js/nigeria-locations.js') }}"></script>
    <script>
        const stateSelect = document.getElementById('state');
        const lgaSelect = document.getElementById('local_government');

        // Fills a select with plain options and keeps the previous choice after a failed submit
        function fillSelect(select, placeholder, values, selected) {
            select.innerHTML = '';
            select.add(new Option(placeholder, ''));
            values.forEach(value => select.add(new Option(value, value, false, value === selected)));
        }

        // Lists the local governments of the chosen state
        function loadLgas(selected = '') {
            const lgas = window.NIGERIA_LGAS[stateSelect.value] || [];
            fillSelect(lgaSelect, lgas.length ? 'Select local government' : 'Select state first', lgas, selected);
            lgaSelect.disabled = lgas.length === 0;
        }

        fillSelect(stateSelect, 'Select state', Object.keys(window.NIGERIA_LGAS), stateSelect.dataset.old);
        loadLgas(lgaSelect.dataset.old);
        stateSelect.addEventListener('change', () => loadLgas());

        // Eye button switches a password field between hidden and visible
        document.querySelectorAll('[data-toggle-password]').forEach(button => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.togglePassword);
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                button.querySelector('i').className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        });
    </script>
</body>

</html>
