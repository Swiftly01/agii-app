@props(['product'])

{{-- Opens (or resumes) the buyer's in-app conversation about this listing --}}
@auth
    @if (auth()->id() !== $product->user_id)
        <form method="POST" action="{{ route('listing-chat.start', $product) }}" {{ $attributes->merge(['class' => 'd-grid']) }}>
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-chat-dots me-2"></i>Chat with Seller
            </button>
        </form>
    @endif
@else
    <div {{ $attributes->merge(['class' => 'd-grid']) }}>
        <a href="{{ route('login') }}" class="btn btn-primary">
            <i class="bi bi-chat-dots me-2"></i>Login to Chat with Seller
        </a>
    </div>
@endauth
