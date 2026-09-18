
{{-- Banner HTML - always display:none, JS controls visibility 
<div id="pwa-install-banner" style="display:none; position:fixed; bottom:20px; left:50%;
    transform:translateX(-50%); background:#1a1a2e; color:#fff; padding:16px 20px;
    border-radius:16px; z-index:9999; box-shadow:0 8px 32px rgba(0,0,0,0.3);
    align-items:center; gap:12px; font-family:sans-serif; max-width:360px; width:90%;">
    <img src="/images/icons/launchericon-72x72.png" width="44" height="44"
         style="border-radius:10px; flex-shrink:0;" alt="Agii">
    <div style="flex:1; min-width:0;">
        <div style="font-weight:700; font-size:15px;">Install Agii</div>
        <div id="pwa-banner-subtitle" style="font-size:12px; opacity:0.7; margin-top:2px;">
            Add to home screen for quick access
        </div>
    </div>
    <div style="display:flex; flex-direction:column; gap:6px; flex-shrink:0;">
        <button id="pwa-install-btn" style="background:#90c74b; color:#fff; border:none;
            padding:8px 14px; border-radius:8px; cursor:pointer; font-weight:600; font-size:13px;">
            Install
        </button>
        <button id="pwa-dismiss-btn" style="background:transparent; color:#aaa; border:none;
            cursor:pointer; font-size:12px; padding:0;">
            Not now
        </button>
    </div>
</div>

{{-- PWA Install Prompt 
<div id="pwa-install-banner" style="display:none; position:fixed; bottom:20px; left:50%; transform:translateX(-50%);
    background:#1a1a2e; color:#fff; padding:16px 24px; border-radius:16px; z-index:9999;
    box-shadow:0 8px 32px rgba(0,0,0,0.3); display:flex; align-items:center; gap:16px;
    font-family:sans-serif; max-width:360px; width:90%;">

    <img src="/images/icons/launchericon-72x72.png" width="48" height="48" style="border-radius:10px;">

    <div style="flex:1">
        <div style="font-weight:700; font-size:15px;">Install {{ config('app.name') }}</div>
        <div style="font-size:12px; opacity:0.7; margin-top:2px;">Add to home screen for quick access</div>
    </div>

    <div style="display:flex; flex-direction:column; gap:6px;">
        <button id="pwa-install-btn" style="background:#4f8ef7; color:#fff; border:none;
            padding:8px 16px; border-radius:8px; cursor:pointer; font-weight:600; font-size:13px;">
            Install
        </button>
        <button id="pwa-dismiss-btn" style="background:transparent; color:#aaa; border:none;
            cursor:pointer; font-size:12px;">
            Not now
        </button>
    </div>
</div>

--}}

{{-- iOS "Add to Home Screen" tip --}}
<div id="pwa-ios-tip" style="display:none; position:fixed; bottom:20px; left:50%;
    transform:translateX(-50%); background:#1a1a2e; color:#fff; padding:16px 20px;
    border-radius:16px; z-index:9999; box-shadow:0 8px 32px rgba(0,0,0,0.3);
    font-family:sans-serif; max-width:340px; width:90%; text-align:center;">
    <div style="font-weight:700; font-size:15px; margin-bottom:8px;">Install Agii</div>
    <div style="font-size:13px; opacity:0.85; line-height:1.6;">
        Tap <strong>Share</strong> <span style="font-size:18px;">⎙</span>
        then <strong>"Add to Home Screen"</strong> <span style="font-size:18px;">＋</span>
    </div>
    <button id="pwa-ios-dismiss" style="margin-top:12px; background:transparent;
        color:#aaa; border:none; cursor:pointer; font-size:12px;">
        Dismiss
    </button>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('pwa-dismissed')) return;

    const banner     = document.getElementById('pwa-install-banner');
    const installBtn = document.getElementById('pwa-install-btn');
    const dismissBtn = document.getElementById('pwa-dismiss-btn');
    const iosTip     = document.getElementById('pwa-ios-tip');
    const iosDismiss = document.getElementById('pwa-ios-dismiss');

    const ua           = navigator.userAgent;
    const isIOS        = /iphone|ipad|ipod/i.test(ua);
    const isSafari     = /safari/i.test(ua) && !/chrome|crios|fxios/i.test(ua);
    const isStandalone = window.navigator.standalone === true
                      || window.matchMedia('(display-mode: standalone)').matches;

    if (isStandalone) return;

    function dismiss() {
        banner.style.display = 'none';
        iosTip.style.display = 'none';
        localStorage.setItem('pwa-dismissed', '1');
    }

    if (isIOS && isSafari) {
        setTimeout(() => { iosTip.style.display = 'block'; }, 3000);
        iosDismiss.addEventListener('click', dismiss);
        return;
    }

    // Register the show function globally so the <head> listener can call it
    // if beforeinstallprompt fires AFTER DOMContentLoaded
    window.__pwaShowBanner = function () {
        setTimeout(() => {
            banner.style.display = 'flex';
        }, 3000);
    };

    // If beforeinstallprompt already fired before DOMContentLoaded
    if (window.__pwaPromptReady) {
        window.__pwaShowBanner();
    }

    // Fallback: if prompt never fires (Chrome suppressed it, "Open in app" visible)
    setTimeout(() => {
        if (!window.__pwaPromptReady && banner.style.display !== 'flex') {
            document.getElementById('pwa-banner-subtitle').textContent =
                'Tap "Open in app" in your address bar';
            installBtn.textContent = 'Got it';
            installBtn.addEventListener('click', dismiss, { once: true });
            banner.style.display = 'flex';
        }
    }, 6000);

    // ── Install button ────────────────────────────────────────────
    installBtn.addEventListener('click', async () => {
        const prompt = window.__pwaPrompt;

        if (!prompt) {
            dismiss();
            return;
        }

        banner.style.display = 'none';
        window.__pwaPrompt = null;
        window.__pwaPromptReady = false;

        try {
            await prompt.prompt();                        // <-- actually shows native dialog
            const { outcome } = await prompt.userChoice;
            console.log('[PWA] Outcome:', outcome);
            if (outcome === 'accepted') {
                localStorage.setItem('pwa-dismissed', '1');
            }
        } catch (err) {
            console.warn('[PWA] Prompt error:', err);
        }
    });

    dismissBtn.addEventListener('click', dismiss);

    window.addEventListener('appinstalled', () => {
        dismiss();
        window.__pwaPrompt = null;
        window.__pwaPromptReady = false;
    });
    
    console.log('hello')
});
</script>