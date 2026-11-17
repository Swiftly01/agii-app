@props(['field', 'value' => null])

{{-- Dispatches a schema field to the component that matches its type --}}
@php
    $component = match ($field['type']) {
        'select', 'multiselect' => 'listing.select',
        'text', 'url', 'number' => 'listing.input',
        default => 'listing.' . $field['type'],
    };
@endphp

<div class="lf-field" data-field="{{ $field['name'] }}" @if ($field['required']) data-required @endif>
    <x-dynamic-component :component="$component" :field="$field" :value="$value" />
    <div class="lf-error" data-error></div>
</div>
