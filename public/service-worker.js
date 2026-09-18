const CACHE_NAME = "agii-v2";
const OFFLINE_URL = "/offline";

// Pre-cache these critical assets on install
const PRECACHE_ASSETS = [
  OFFLINE_URL,
  "/",
  // Add your critical CSS/JS/fonts here:
  // "/styles/main.css",
  // "/scripts/main.js",
];

// These URL patterns will use cache-first strategy
const CACHE_FIRST_PATTERNS = [
  /\.(?:woff2?|ttf|eot|otf)$/,   // Fonts
  /\.(?:png|jpg|jpeg|svg|gif|webp|ico)$/, // Images
  /\.(?:css|js)$/,                // Static assets
];

// ─── INSTALL ────────────────────────────────────────────────────────────────

self.addEventListener("install", (event) => {
  event.waitUntil(
    caches
      .open(CACHE_NAME)
      .then((cache) =>
        Promise.allSettled(
          PRECACHE_ASSETS.map((url) =>
            cache.add(url).catch((e) => console.warn(`Failed to cache ${url}:`, e))
          )
        )
      )
      .then(() => self.skipWaiting()) // Skip waiting AFTER caching
  );
});

// ─── ACTIVATE ───────────────────────────────────────────────────────────────

self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches
      .keys()
      .then((keys) =>
        Promise.all(
          keys
            .filter((key) => key !== CACHE_NAME)
            .map((key) => {
              console.log(`[SW] Deleting old cache: ${key}`);
              return caches.delete(key);
            })
        )
      )
      .then(() => self.clients.claim())
  );
});

// ─── FETCH ──────────────────────────────────────────────────────────────────

self.addEventListener("fetch", (event) => {
  if (event.request.method !== "GET") return;

  const { url } = event.request;

  // Skip cross-origin requests (analytics, CDNs, etc.)
  if (!url.startsWith(self.location.origin)) return;

  // Skip browser-extension and non-http requests
  if (!url.startsWith("http")) return;

  const isCacheFirst = CACHE_FIRST_PATTERNS.some((pattern) => pattern.test(url));

  if (isCacheFirst) {
    // Cache-first: serve from cache, fall back to network + update cache
    event.respondWith(cacheFirst(event.request));
  } else {
    // Network-first: try network, fall back to cache, then offline page
    event.respondWith(networkFirst(event.request));
  }
});

// ─── STRATEGIES ─────────────────────────────────────────────────────────────

async function cacheFirst(request) {
  const cached = await caches.match(request);
  if (cached) return cached;

  try {
    const response = await fetch(request);
    if (response.ok) {
      const cache = await caches.open(CACHE_NAME);
      cache.put(request, response.clone()); // Update cache in background
    }
    return response;
  } catch {
    return caches.match(OFFLINE_URL);
  }
}

async function networkFirst(request) {
  try {
    const response = await fetch(request);
    if (response.ok) {
      const cache = await caches.open(CACHE_NAME);
      cache.put(request, response.clone()); // Keep cache warm
    }
    return response;
  } catch {
    const cached = await caches.match(request);
    return cached ?? (await caches.match(OFFLINE_URL));
  }
}