/*
 * Agii push service worker.
 * Registered on its own scope (/push/) so it never replaces the app's
 * existing caching service workers. Push events are delivered to it
 * regardless of which pages it controls.
 */
self.addEventListener('install', () => self.skipWaiting());
self.addEventListener('activate', (event) => event.waitUntil(self.clients.claim()));

self.addEventListener('push', (event) => {
    let payload = {};

    try {
        payload = event.data ? event.data.json() : {};
    } catch (e) {
        payload = { body: event.data ? event.data.text() : '' };
    }

    // The server omits nothing, so drop null/undefined before showNotification.
    const { title = 'Agii', ...rest } = payload;
    const options = Object.fromEntries(Object.entries(rest).filter(([, v]) => v != null));

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const target = new URL(
        (event.notification.data && event.notification.data.url) || '/',
        self.location.origin
    ).href;

    event.waitUntil(
        self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windows) => {
            const open = windows.find((w) => w.url === target && 'focus' in w);
            return open ? open.focus() : self.clients.openWindow(target);
        })
    );
});
