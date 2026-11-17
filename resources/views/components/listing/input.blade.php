@props(['field', 'value' => null, 'readonly' => false])

@php($isNumber = $field['type'] === 'number')

<div class="lf-input">
    <input
        type="{{ $isNumber ? 'number' : ($field['type'] === 'url' ? 'url' : 'text') }}"
        id="field-{{ $field['name'] }}"
        name="{{ $field['name'] }}"
        value="{{ old($field['name'], $value) }}"
        placeholder=" "
        @if (isset($field['max']) && ! $isNumber) maxlength="{{ $field['max'] }}" @endif
        @if ($isNumber) min="0" step="any" inputmode="decimal" @endif
        @readonly($readonly)
        @if ($field['required']) data-required-input @endif
    >
    <label for="field-{{ $field['name'] }}">{{ $field['label'] }}{{ $field['required'] ? '*' : '' }}</label>
</div>

@if (! empty($field['hint']) || ! empty($field['counter']))
    <div class="lf-hint">
        <span>{{ $field['hint'] ?? '' }}</span>
        @if (! empty($field['counter']))
            <span data-counter-for="field-{{ $field['name'] }}" data-max="{{ $field['max'] }}">0/{{ $field['max'] }}</span>
        @endif
    </div>
@endif
