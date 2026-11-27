@props(['products', 'selected' => null])

{{-- Vendor's live listings, the one chosen here is what the hero shows --}}
<select name="product_id" class="form-select" required>
    <option value="" disabled @selected(! $selected)>Choose a product</option>
    @foreach ($products as $product)
        <option value="{{ $product->id }}" @selected((int) $selected === $product->id)>{{ $product->title }}</option>
    @endforeach
</select>
