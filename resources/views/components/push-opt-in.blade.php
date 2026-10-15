{{--
    Push notification opt-in banner + script.
    Rendered only for logged-in buyers, and only once VAPID keys are configured.
    Never prompts on page load: the browser permission dialog is triggered by
    the user clicking "Turn on".
--}}
@if (auth()->check() && auth()->user()->isCustomer() && config('webpush.vapid.public_key'))
    <div id="push-opt-in" hidden role="dialog" aria-label="Turn on notifications"
        data-vapid-key="{{ config('webpush.vapid.public_key') }}"
        data-subscribe-url="{{ route('push.subscribe') }}"
        data-sw-url="{{ asset('push-sw.js') }}"
        data-sw-scope="/push/"
        data-csrf="{{ csrf_token() }}"
        style="position:fixed; top:16px; right:16px; z-index:1080; max-width:340px; background:#fff;
               border-radius:12px; padding:16px; box-shadow:0 8px 30px rgba(0,0,0,.18); font-size:14px;">
        <div style="font-weight:700; margin-bottom:4px;">Never miss a new listing</div>
        <div style="opacity:.75; margin-bottom:12px;">Get notified the moment new products are posted on Agii.</div>
        <div style="display:flex; gap:8px; justify-content:flex-end;">
            <button type="button" data-push-dismiss
                style="background:none; border:none; padding:8px 12px; color:#666;">Not now</button>
            <button type="button" data-push-enable
                style="background:#90c74b; color:#fff; border:none; border-radius:8px; padding:8px 16px; font-weight:600;">Turn on</button>
        </div>
    </div>
    <script src="{{ asset('js/push-notifications.js') }}" defer></script>
@endif
