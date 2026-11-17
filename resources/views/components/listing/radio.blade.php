@props(['field', 'value' => null])

<div class="lf-radio-group">
    <div class="lf-radio-group__title">{{ $field['label'] }}</div>
    <div class="lf-radio-group__options">
        @foreach ($field['options'] as $option)
            <label class="lf-radio">
                <input type="radio" name="{{ $field['name'] }}" value="{{ $option }}" @checked(old($field['name'], $value) === $option)>
                <span class="lf-radio__dot"></span>
                <span>{{ $option }}</span>
            </label>
        @endforeach
    </div>
</div>
