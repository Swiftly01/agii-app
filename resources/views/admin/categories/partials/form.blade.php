@php $category = $category ?? null; @endphp

<div class="mb-3">
    <label for="categoryName" class="form-label fw-semibold">Name</label>
    <input type="text" name="name" id="categoryName" class="form-control"
        value="{{ old('name', $category->name ?? '') }}" required autofocus>
</div>

<div class="mb-3">
    <label for="categorySlug" class="form-label fw-semibold">Slug</label>
    <input type="text" name="slug" id="categorySlug" class="form-control"
        value="{{ old('slug', $category->slug ?? '') }}" placeholder="Auto-generated from the name if left blank">
    <div class="form-text">Used in URLs. Leave blank to auto-generate from the name.</div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="categoryType" class="form-label fw-semibold">Type</label>
        <select name="type" id="categoryType" class="form-select" required>
            <option value="product" {{ old('type', $category->type ?? 'product') === 'product' ? 'selected' : '' }}>
                Product</option>
            <option value="service" {{ old('type', $category->type ?? '') === 'service' ? 'selected' : '' }}>
                Service</option>
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label for="categoryParent" class="form-label fw-semibold">Parent Category</label>
        <select name="parent_id" id="categoryParent" class="form-select">
            <option value="">— None (top-level category) —</option>
            @foreach ($parentOptions as $option)
                <option value="{{ $option->id }}"
                    {{ (string) old('parent_id', $category->parent_id ?? '') === (string) $option->id ? 'selected' : '' }}>
                    {{ $option->parent_id ? '— ' : '' }}{{ $option->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-3">
    <label for="categoryIcon" class="form-label fw-semibold">Icon</label>
    <input type="text" name="icon" id="categoryIcon" class="form-control"
        value="{{ old('icon', $category->icon ?? '') }}" placeholder="e.g. fan, egg-fried (Bootstrap Icons name)">
</div>

<div class="mb-3">
    <label for="categoryDescription" class="form-label fw-semibold">Description</label>
    <textarea name="description" id="categoryDescription" class="form-control" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
</div>

<div class="mb-4 form-check">
    <input type="checkbox" name="is_active" id="categoryIsActive" class="form-check-input" value="1"
        {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="categoryIsActive">Active (visible to vendors when posting)</label>
</div>

<script>
    (function () {
        const nameInput = document.getElementById('categoryName');
        const slugInput = document.getElementById('categorySlug');
        // On edit, don't auto-overwrite an existing slug just because the
        // admin touched the name field.
        let slugManuallyEdited = {{ $category ? 'true' : 'false' }};

        slugInput.addEventListener('input', () => slugManuallyEdited = true);

        nameInput.addEventListener('input', function () {
            if (slugManuallyEdited) return;

            slugInput.value = this.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
        });
    })();
</script>
