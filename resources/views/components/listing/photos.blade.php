@props(['photos'])

<div class="lf-field" data-field="images" data-required>
    <div class="lf-photos__title">Add at least {{ $photos['min'] }} photos</div>

    <div class="lf-photos" data-photos data-min="{{ $photos['min'] }}" data-max="{{ $photos['max'] }}" data-max-mb="{{ $photos['max_size_mb'] }}" data-mimes='@json($photos['mimes'])'>
        <div class="lf-photos__grid" data-photos-grid>
            <button type="button" class="lf-photos__add" data-photos-add aria-label="Add photos"><i class="bi bi-plus-lg"></i></button>
        </div>
        <input type="file" accept="image/*,.heic" multiple hidden data-photos-input>
    </div>

    <div class="lf-hint lf-hint--block">
        First picture - is the title picture. Drag to reorder.<br>
        Supported image formats: {{ strtoupper(implode(', ', array_diff($photos['mimes'], ['jpeg']))) }}<br>
        Each picture must not exceed {{ $photos['max_size_mb'] }} Mb
    </div>
    <div class="lf-error" data-error></div>
</div>
