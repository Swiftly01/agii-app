@props(['photos', 'existing' => []])

{{-- When editing, kept photos count towards the limits so the uploader only asks for what is still needed --}}
@php
    // When a video can replace photos the minimum is enforced on the server, the form script must not block the submit
    $minNew = ($photos['optional_with_video'] ?? false) ? 0 : max(0, $photos['min'] - count($existing));
    $maxNew = max(0, $photos['max'] - count($existing));
@endphp

<div class="lf-field" data-field="images" @if ($minNew > 0) data-required @endif>
    @if ($existing)
        <div class="lf-photos__title">Current photos (tick to remove)</div>

        {{-- Ticked photos are sent as remove_images[] and deleted on save --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(90px,1fr));gap:8px;margin-bottom:12px;">
            @foreach ($existing as $path)
                <label style="position:relative;display:block;cursor:pointer;">
                    <img src="{{ asset($path) }}" alt="Listing photo {{ $loop->iteration }}" style="width:100%;height:90px;object-fit:cover;border-radius:6px;">
                    <input type="checkbox" name="remove_images[]" value="{{ $path }}" style="position:absolute;top:6px;right:6px;">
                </label>
            @endforeach
        </div>
    @endif

    <div class="lf-photos__title">{{ $existing ? 'Add more photos' : (($photos['optional_with_video'] ?? false) ? "Add at least {$photos['min']} photos or a video" : "Add at least {$photos['min']} photos") }}</div>

    <div class="lf-photos" data-photos data-min="{{ $minNew }}" data-max="{{ $maxNew }}" data-max-mb="{{ $photos['max_size_mb'] }}" data-mimes='@json($photos['mimes'])'>
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
