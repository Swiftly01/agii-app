@php
    $shareUrl = url()->current();
    $shareText = $product->title . ' - ₦' . number_format($product->price);
@endphp

<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">Share this listing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="share-provider-grid">
                    @foreach (config('share.providers') as $key => $provider)
                        @if ($provider['url'])
                            <a href="{{ str_replace(['{url}', '{text}'], [urlencode($shareUrl), urlencode($shareText)], $provider['url']) }}"
                                target="_blank" rel="noopener noreferrer"
                                class="share-provider-btn" data-provider="{{ $key }}"
                                style="--provider-color: {{ $provider['color'] }};">
                                <i class="{{ $provider['icon'] }}"></i>
                                <span>{{ $provider['label'] }}</span>
                            </a>
                        @else
                            <button type="button" class="share-provider-btn share-copy-trigger"
                                data-provider="{{ $key }}" data-copy-url="{{ $shareUrl }}"
                                data-note="Link copied! Paste it into your Instagram bio, story, or DM."
                                style="--provider-color: {{ $provider['color'] }};">
                                <i class="{{ $provider['icon'] }}"></i>
                                <span>{{ $provider['label'] }}</span>
                            </button>
                        @endif
                    @endforeach
                </div>

                <div class="mt-3">
                    <label for="shareModalLinkInput" class="form-label small text-muted">Or copy link</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" id="shareModalLinkInput"
                            value="{{ $shareUrl }}" readonly>
                        <button class="btn btn-outline-secondary btn-sm" type="button" id="shareModalCopyBtn">
                            Copy
                        </button>
                    </div>
                    <div class="small text-success mt-1 d-none" id="shareModalCopyNote"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .share-provider-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
        gap: 12px;
    }

    .share-provider-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        padding: 14px 8px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        background: #fff;
        color: #333;
        text-decoration: none;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .share-provider-btn:hover {
        border-color: var(--provider-color, #8fc74a);
        color: var(--provider-color, #8fc74a);
        transform: translateY(-2px);
    }

    .share-provider-btn i {
        font-size: 1.4rem;
        color: var(--provider-color, #8fc74a);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const copyToClipboard = (text) => navigator.clipboard.writeText(text);

        // Generic "copy link" button in the modal body
        const copyBtn = document.getElementById('shareModalCopyBtn');
        const copyNote = document.getElementById('shareModalCopyNote');
        if (copyBtn) {
            copyBtn.addEventListener('click', function () {
                const input = document.getElementById('shareModalLinkInput');
                copyToClipboard(input.value).then(function () {
                    copyNote.textContent = 'Link copied!';
                    copyNote.classList.remove('d-none');
                    setTimeout(() => copyNote.classList.add('d-none'), 2500);
                });
            });
        }

        // Providers with no web share-intent URL (currently just Instagram)
        // copy the link and explain what to do with it instead.
        document.querySelectorAll('.share-copy-trigger').forEach(function (button) {
            button.addEventListener('click', function () {
                copyToClipboard(this.dataset.copyUrl).then(() => {
                    alert(this.dataset.note);
                });
            });
        });
    });
</script>
