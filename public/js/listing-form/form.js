// Wires the listing form together: counters, clear, client validation and the AJAX submit
(() => {
    const root = document.querySelector('[data-listing-form]');
    if (!root) return;

    const form = root.querySelector('form');
    const pickers = window.ListingForm.initPickers(root);
    const photos = new window.ListingForm.PhotoGrid(root.querySelector('[data-photos]'));
    const submitButton = root.querySelector('[data-form-submit]');
    const formError = root.querySelector('[data-form-error]');

    const fieldNode = (name) => root.querySelector(`[data-field="${name}"]`);

    const showError = (name, message) => {
        const node = fieldNode(name);
        if (!node) return false;
        node.classList.add('has-error');
        node.querySelector('[data-error]').textContent = message;
        return true;
    };

    const clearErrors = () => {
        root.querySelectorAll('[data-field]').forEach((node) => { node.classList.remove('has-error'); node.querySelector('[data-error]').textContent = ''; });
        formError.textContent = '';
    };

    photos.onError = (message) => message ? showError('images', message) : fieldNode('images').classList.remove('has-error');

    // Live character counters next to limited inputs
    const updateCounter = (input) => {
        const counter = root.querySelector(`[data-counter-for="${input.id}"]`);
        if (counter) counter.textContent = `${input.value.length}/${counter.dataset.max}`;
    };
    form.querySelectorAll('input, textarea').forEach((input) => { updateCounter(input); input.addEventListener('input', () => { updateCounter(input); input.closest('[data-field]')?.classList.remove('has-error'); }); });

    // Returns the first field name that fails the required check
    const validate = () => {
        let firstInvalid = null;
        root.querySelectorAll('[data-field][data-required]').forEach((node) => {
            const name = node.dataset.field;
            const filled = name === 'images'
                ? photos.files.length >= Number(root.querySelector('[data-photos]').dataset.min)
                : [...node.querySelectorAll('input:not([type=hidden]):not([type=radio]), textarea, input[type=hidden], input[type=radio]:checked')].some((input) => input.value.trim() !== '');
            if (filled) return;
            showError(name, name === 'images' ? `Please add at least ${root.querySelector('[data-photos]').dataset.min} photos` : 'This field is required');
            firstInvalid ??= node;
        });
        return firstInvalid;
    };

    // Resets inputs, pickers and photos back to their defaults
    root.querySelector('[data-form-clear]').addEventListener('click', () => {
        form.reset();
        pickers.forEach((picker) => picker.reset());
        photos.clear();
        form.querySelectorAll('input, textarea').forEach(updateCounter);
        clearErrors();
    });

    const buildPayload = () => {
        const data = new FormData(form);
        photos.files.forEach((file) => data.append('images[]', file));
        return data;
    };

    // Maps Laravel validation errors back onto their fields
    const applyServerErrors = (errors) => {
        let first = null;
        Object.entries(errors).forEach(([key, messages]) => {
            const name = key.split('.')[0];
            if (showError(name, messages[0])) first ??= fieldNode(name);
            else formError.textContent = messages[0];
        });
        return first;
    };

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearErrors();

        const invalid = validate();
        if (invalid) return invalid.scrollIntoView({ behavior: 'smooth', block: 'center' });

        submitButton.disabled = true;
        try {
            const response = await fetch(root.dataset.action, {
                method: 'POST',
                body: buildPayload(),
                headers: { Accept: 'application/json', 'X-CSRF-TOKEN': form.querySelector('[name=_token]').value },
            });
            const result = await response.json().catch(() => ({}));

            if (response.ok && result.redirect_url) return (window.location.href = result.redirect_url);
            if (response.status === 422) return applyServerErrors(result.errors || {})?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            formError.textContent = result.message || 'Something went wrong, please try again.';
        } catch {
            formError.textContent = 'Network error, please check your connection and try again.';
        } finally {
            submitButton.disabled = false;
        }
    });
})();
