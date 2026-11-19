@props(['videos', 'existing' => [], 'replacesPhotos' => false])

<div class="lf-field" data-field="videos">
    @if ($existing)
        <div class="lf-photos__title">Current videos (tick to remove)</div>

        {{-- Ticked videos are sent as remove_videos[] and deleted on save --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:8px;margin-bottom:12px;">
            @foreach ($existing as $path)
                <label style="position:relative;display:block;cursor:pointer;">
                    <video src="{{ asset($path) }}" controls preload="metadata" style="width:100%;border-radius:6px;background:#000;"></video>
                    <input type="checkbox" name="remove_videos[]" value="{{ $path }}" style="position:absolute;top:6px;right:6px;">
                </label>
            @endforeach
        </div>
    @endif

    <div class="lf-photos__title">{{ $existing ? 'Add another video' : ($replacesPhotos ? 'Upload a video instead of photos' : 'Add a video') }}</div>

    {{-- A plain file input, it is posted with the form like any other field --}}
    <input type="file" name="videos[]" accept="{{ implode(',', $videos['mime_types']) }}" multiple class="form-control">

    <div class="lf-hint lf-hint--block">
        {{ strtoupper(implode(', ', $videos['mimes'])) }} only, up to {{ $videos['max'] }} videos, each not exceeding {{ $videos['max_size_mb'] }} Mb
    </div>
    <div class="lf-error" data-error></div>
</div>
