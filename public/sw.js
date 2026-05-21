const CACHE_NAME = 'lamgame-v1';
const OFFLINE_URL = '/offline';

const PRECACHE_URLS = [
    '/',
    '/offline',
    '/css/app.css',
    '/images/lamgame-icon-192.png',
];

// Install — precache shell
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(PRECACHE_URLS))
    );
    self.skipWaiting();
});

// Activate — clean old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
        )
    );
    self.clients.claim();
});

// Fetch — network-first for API, cache-first for static
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET
    if (request.method !== 'GET') {
        // Queue failed POST/PUT for retry
        event.respondWith(
            fetch(request).catch(() => {
                if (request.method === 'POST' || request.method === 'PUT') {
                    queueForRetry(request.clone());
                }
                return new Response(JSON.stringify({ error: 'offline', queued: true }), {
                    headers: { 'Content-Type': 'application/json' },
                    status: 503,
                });
            })
        );
        return;
    }

    // API requests — network first, cache fallback
    if (url.pathname.startsWith('/api/')) {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, clone));
                    return response;
                })
                .catch(() => caches.match(request))
        );
        return;
    }

    // Static assets — cache first
    if (url.pathname.match(/\.(js|css|png|jpg|jpeg|webp|svg|woff2?|ttf)$/)) {
        event.respondWith(
            caches.match(request).then((cached) => cached || fetch(request).then((response) => {
                const clone = response.clone();
                caches.open(CACHE_NAME).then((cache) => cache.put(request, clone));
                return response;
            }))
        );
        return;
    }

    // Pages — network first, offline fallback
    event.respondWith(
        fetch(request)
            .then((response) => {
                const clone = response.clone();
                caches.open(CACHE_NAME).then((cache) => cache.put(request, clone));
                return response;
            })
            .catch(() => caches.match(request).then((cached) => cached || caches.match(OFFLINE_URL)))
    );
});

// Retry queue using IndexedDB
const DB_NAME = 'lamgame-retry';
const STORE_NAME = 'requests';

function openDB() {
    return new Promise((resolve, reject) => {
        const req = indexedDB.open(DB_NAME, 1);
        req.onupgradeneeded = (e) => e.target.result.createObjectStore(STORE_NAME, { autoIncrement: true });
        req.onsuccess = (e) => resolve(e.target.result);
        req.onerror = (e) => reject(e);
    });
}

async function queueForRetry(request) {
    try {
        const body = await request.text();
        const db = await openDB();
        const tx = db.transaction(STORE_NAME, 'readwrite');
        tx.objectStore(STORE_NAME).add({
            url: request.url,
            method: request.method,
            headers: Object.fromEntries(request.headers.entries()),
            body,
            timestamp: Date.now(),
        });
    } catch (e) { /* silent */ }
}

async function retryQueue() {
    try {
        const db = await openDB();
        const tx = db.transaction(STORE_NAME, 'readwrite');
        const store = tx.objectStore(STORE_NAME);
        const all = await new Promise((resolve) => {
            const req = store.getAll();
            req.onsuccess = () => resolve(req.result);
        });

        for (const item of all) {
            try {
                await fetch(item.url, {
                    method: item.method,
                    headers: item.headers,
                    body: item.body || undefined,
                });
            } catch (e) { return; } // Still offline, stop retrying
        }

        // Clear queue on success
        const clearTx = db.transaction(STORE_NAME, 'readwrite');
        clearTx.objectStore(STORE_NAME).clear();
    } catch (e) { /* silent */ }
}

// Retry when back online
self.addEventListener('message', (event) => {
    if (event.data === 'retry-queue') retryQueue();
});
