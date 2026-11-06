@props(['product', 'size' => 'sm'])

@php($contact = $product->sellerContact())

{{-- Contact buttons shown in place of a price on service listings --}}
<div {{ $attributes->merge(['class' => 'seller-contact d-flex gap-2 flex-wrap']) }}>
    @if ($contact->isAvailable())
        @if ($contact->phoneUrl())
            <a href="{{ $contact->phoneUrl() }}" class="btn btn-outline-success btn-{{ $size }}">
                <i class="bi bi-telephone me-1"></i> Call Seller
            </a>
        @endif
        @if ($contact->whatsappUrl())
            <a href="{{ $contact->whatsappUrl('Hi, I am interested in your service: '.$product->title) }}"
                target="_blank" rel="noopener" class="btn btn-success btn-{{ $size }}">
                <i class="bi bi-whatsapp me-1"></i> WhatsApp
            </a>
        @endif
    @else
        <span class="text-muted small">Contact seller</span>
    @endif
</div>
