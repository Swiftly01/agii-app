@extends('layout.layout')
@section('title', 'Post new ad')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/listing-form.css') }}">
@endpush

@section('content')
<div class="lf" data-listing-form data-action="{{ route('products.store.real-estate') }}">
    <header class="lf-bar">
        <a href="{{ url()->previous() }}" class="lf-bar__back" aria-label="Back"><i class="bi bi-arrow-left"></i></a>
        <h1 class="lf-bar__title">Post new ad</h1>
        <button type="button" class="lf-bar__clear" data-form-clear><i class="bi bi-eraser-fill"></i> Clear</button>
    </header>

    <form id="listingForm" class="lf-form" novalidate enctype="multipart/form-data">
        @csrf

        @foreach ($schema->sections() as $section => $fields)
            <section class="lf-section lf-section--{{ $section }}">
                {{-- Category is fixed for this form, photos sit right after the basic fields --}}
                @if ($section === 'basic')
                    <x-listing.field :field="$fields['title']" :value="$defaults['title']" />

                    <div class="lf-input lf-input--static">
                        <span class="lf-input__small">Category*</span>
                        <span>Houses &amp; Apartments for Rent</span>
                    </div>

                    <x-listing.photos :photos="$schema->photos()" />
                    @php($fields = $fields->except('title'))
                @endif

                @foreach ($fields as $name => $field)
                    <x-listing.field :field="$field" :value="$defaults[$name]" />
                @endforeach

                @if ($section === 'contact')
                    <x-listing.input :field="['name' => 'contact_name', 'type' => 'text', 'label' => 'Name', 'required' => true]" :value="$user->name" readonly />
                    <x-listing.input :field="['name' => 'contact_phone', 'type' => 'text', 'label' => 'Phone number (digits only)', 'required' => true]" :value="$user->phone" readonly />
                @endif
            </section>
        @endforeach

        <div class="lf-submit">
            <div class="lf-error lf-error--form" data-form-error></div>
            <button type="submit" class="lf-btn lf-btn--primary" data-form-submit>Post ad</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/listing-form/picker.js') }}"></script>
    <script src="{{ asset('js/listing-form/photos.js') }}"></script>
    <script src="{{ asset('js/listing-form/form.js') }}"></script>
@endpush
