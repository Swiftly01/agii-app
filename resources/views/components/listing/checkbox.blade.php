@props(['field', 'value' => null])

<label class="lf-check">
    <input type="checkbox" name="{{ $field['name'] }}" value="1" @checked(old($field['name'], $value))>
    <span class="lf-check__box"></span>
    <span>{{ $field['label'] }}</span>
</label>
