@props(['field', 'value' => null])

<div class="lf-input">
    <textarea
        id="field-{{ $field['name'] }}"
        name="{{ $field['name'] }}"
        rows="3"
        placeholder=" "
        maxlength="{{ $field['max'] }}"
    >{{ old($field['name'], $value) }}</textarea>
    <label for="field-{{ $field['name'] }}">{{ $field['label'] }}{{ $field['required'] ? '*' : '' }}</label>
</div>

<div class="lf-hint">
    <span>{{ $field['hint'] ?? '' }}</span>
    <span data-counter-for="field-{{ $field['name'] }}" data-max="{{ $field['max'] }}">0/{{ $field['max'] }}</span>
</div>
