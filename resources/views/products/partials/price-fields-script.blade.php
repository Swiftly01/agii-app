{{-- Hides price inputs for service categories, services show seller contact buttons instead --}}
<script>
    window.PriceFields = {
        hidden: false,
        sync(isService) {
            this.hidden = isService;
            document.querySelectorAll('[data-price-field]').forEach(el => el.classList.toggle('d-none', isService));
            const price = document.getElementById('productPrice');
            if (price) price.required = !isService;
        },
    };

    document.addEventListener('DOMContentLoaded', () => {
        const select = document.querySelector('select[name="category_id"]');
        select?.addEventListener('change', () => PriceFields.sync(select.selectedOptions[0]?.dataset.type === 'service'));
        PriceFields.sync(@json($service ?? false));
    });
</script>
