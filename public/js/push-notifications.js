/*
 * Web push opt-in. Configured through data-* attributes on #push-opt-in
 * (see resources/views/components/push-opt-in.blade.php).
 *
 * UI hooks:
 *   #push-opt-in            banner (shown only when it makes sense)
 *   [data-push-enable]      "Turn on" button inside the banner
 *   [data-push-dismiss]     "Not now" button inside the banner
 *   [data-push-toggle]      optional menu item that flips push on/off
 *   [data-push-toggle-label] text node inside the toggle showing On/Off
 */
(function () {
    'use strict';

    const root = document.getElementById('push-opt-in');
    if (!root) return;

    const cfg = {
        key: root.dataset.vapidKey,
        subscribeUrl: root.dataset.subscribeUrl,
        swUrl: root.dataset.swUrl,
        swScope: root.dataset.swScope,
        csrf: root.dataset.csrf,
    };

    const DISMISS_KEY = 'agii_push_dismissed_until';
    const DISMISS_DAYS = 14;

    const supported = 'serviceWorker' in navigator && 'PushManager' in window && 'Notification' in window;
    const toggles = document.querySelectorAll('[data-push-toggle]');

    if (!supported) {
        toggles.forEach((el) => el.closest('li, .push-toggle-wrap')?.remove());
        return;
    }

    // ---- helpers ---------------------------------------------------------

    function urlBase64ToUint8Array(base64) {
        const padded = (base64 + '='.repeat((4 - (base64.length % 4)) % 4)).replace(/-/g, '+').replace(/_/g, '/');
        const raw = atob(padded);
        return Uint8Array.from(raw, (c) => c.charCodeAt(0));
    }

    function whenActive(reg) {
        if (reg.active) return Promise.resolve(reg);
        const worker = reg.installing || reg.waiting;
        return new Promise((resolve, reject) => {
            const check = () => {
                if (worker.state === 'activated') resolve(reg);
                else if (worker.state === 'redundant') reject(new Error('Push service worker failed to install'));
            };
            worker.addEventListener('statechange', check);
            check();
        });
    }

    async function getRegistration() {
        const reg = await navigator.serviceWorker.register(cfg.swUrl, { scope: cfg.swScope });
        return whenActive(reg);
    }

    function api(method, body) {
        return fetch(cfg.subscribeUrl, {
            method,
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': cfg.csrf,
            },
            body: JSON.stringify(body),
        }).then((res) => {
            if (!res.ok) throw new Error('Request failed: ' + res.status);
            return res;
        });
    }

    function serialize(subscription) {
        const json = subscription.toJSON();
        return {
            endpoint: json.endpoint,
            keys: json.keys,
            contentEncoding: (PushManager.supportedContentEncodings || ['aesgcm'])[0],
        };
    }

    function dismissed() {
        return Number(localStorage.getItem(DISMISS_KEY) || 0) > Date.now();
    }

    function render(isOn) {
        toggles.forEach((el) => {
            const label = el.querySelector('[data-push-toggle-label]');
            if (label) label.textContent = isOn ? 'On' : 'Off';
            el.dataset.pushState = isOn ? 'on' : 'off';
        });
    }

    // ---- actions ---------------------------------------------------------

    async function enable() {
        // Must run from a user gesture (click) or browsers block the prompt.
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') return false;

        const reg = await getRegistration();
        const subscription =
            (await reg.pushManager.getSubscription()) ||
            (await reg.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(cfg.key),
            }));

        await api('POST', serialize(subscription));
        return true;
    }

    async function disable() {
        const reg = await navigator.serviceWorker.getRegistration(cfg.swScope);
        const subscription = reg && (await reg.pushManager.getSubscription());
        if (!subscription) return;

        const endpoint = subscription.endpoint;
        await subscription.unsubscribe();
        await api('DELETE', { endpoint });
    }

    async function currentState() {
        if (Notification.permission !== 'granted') return false;
        const reg = await navigator.serviceWorker.getRegistration(cfg.swScope);
        return !!(reg && (await reg.pushManager.getSubscription()));
    }

    // ---- wiring ----------------------------------------------------------

    root.querySelector('[data-push-enable]')?.addEventListener('click', async (e) => {
        const btn = e.currentTarget;
        btn.disabled = true;
        try {
            const ok = await enable();
            root.hidden = true;
            render(ok);
        } catch (err) {
            console.error('[push] enable failed', err);
            btn.disabled = false;
        }
    });

    root.querySelector('[data-push-dismiss]')?.addEventListener('click', () => {
        localStorage.setItem(DISMISS_KEY, String(Date.now() + DISMISS_DAYS * 864e5));
        root.hidden = true;
    });

    toggles.forEach((el) =>
        el.addEventListener('click', async (e) => {
            e.preventDefault();
            try {
                if (el.dataset.pushState === 'on') {
                    await disable();
                    render(false);
                } else if (Notification.permission === 'denied') {
                    alert('Notifications are blocked for this site. Enable them in your browser settings, then try again.');
                } else {
                    render(await enable());
                }
            } catch (err) {
                console.error('[push] toggle failed', err);
            }
        })
    );

    (async function init() {
        try {
            const isOn = await currentState();
            render(isOn);

            if (isOn) {
                // Keep the server in sync if the browser rotated the subscription.
                const reg = await navigator.serviceWorker.getRegistration(cfg.swScope);
                const sub = await reg.pushManager.getSubscription();
                if (sub) api('POST', serialize(sub)).catch(() => {});
                return;
            }

            if (Notification.permission === 'default' && !dismissed()) {
                root.hidden = false;
            }
        } catch (err) {
            console.error('[push] init failed', err);
        }
    })();
})();
