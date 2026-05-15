const CACHE_NAME = 'kviet-shop-v1';
const urlsToCache = [
    '/',
    '/assets/global/css/bootstrap.min.css',
    '/assets/global/css/all.min.css',
    '/assets/global/js/jquery-3.7.1.min.js',
    '/assets/global/js/bootstrap.bundle.min.js'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(urlsToCache);
            })
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                if (response) {
                    return response;
                }
                return fetch(event.request);
            })
    );
});
