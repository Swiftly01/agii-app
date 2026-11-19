@props(['field', 'value' => null])

@php
    $multiple = $field['type'] === 'multiselect';
    $selected = collect(old($field['name'], $value))->filter(fn ($v) => $v !== null && $v !== '')->values()->all();
    $inputName = $field['name'] . ($multiple ? '[]' : '');
    $default = collect($value ?? $field['default'] ?? null)->filter(fn ($v) => $v !== null && $v !== '')->values()->all();
    $config = [
        'title' => $field['label'],
        'mode' => $field['mode'] ?? 'page',
        'multiple' => $multiple,
        'any' => $field['any'],
        'popular' => $field['popular'],
        'options' => $field['options'],
    ];
@endphp

{{-- Trigger button, the picker overlay is built on demand by listing-form/picker.js --}}
<button type="button" class="lf-select" data-picker data-name="{{ $inputName }}" data-config='@json($config)' data-default='@json($default)'>
    @if (! empty($field['icon']))
        <i class="bi bi-{{ $field['icon'] }} lf-select__icon"></i>
    @endif
    <span class="lf-select__text">
        <span class="lf-select__label">{{ $field['label'] }}{{ $field['required'] ? '*' : '' }}</span>
        <span class="lf-select__value" data-picker-value></span>
    </span>
    <i class="bi bi-chevron-right lf-select__chevron"></i>
</button>

<div data-picker-values>
    @foreach ($selected as $item)
        <input type="hidden" name="{{ $inputName }}" value="{{ $item }}">
    @endforeach
</div>
