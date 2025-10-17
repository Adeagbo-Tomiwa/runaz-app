const CACHE_NAME = "runaz-cache-v1";
const ASSETS_TO_CACHE = [
  "./",
  "./index.php",
  "./manifest.json",
  "./assets/runaz-logo.png",
  "./assets/runaz-logo-512.png",
  "./assets/runaz-maskable.png",
  "./css/styles.css",
  "./js/main.js"
];

// Install Event - Cache App Shell
self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log("Caching app shell...");
      return cache.addAll(ASSETS_TO_CACHE);
    })
  );
  self.skipWaiting();
});

// Activate Event - Clean old caches
self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(
        keys.map((key) => {
          if (key !== CACHE_NAME) {
            console.log("Deleting old cache:", key);
            return caches.delete(key);
          }
        })
      )
    )
  );
  self.clients.claim();
});

// Fetch Event - Serve from cache or network
self.addEventListener("fetch", (event) => {
  event.respondWith(
    caches.match(event.request).then((cachedResponse) => {
      // Serve cached response if available, else fetch from network
      return (
        cachedResponse ||
        fetch(event.request)
          .then((networkResponse) => {
            // Cache new resource if request is GET
            if (event.request.method === "GET") {
              caches.open(CACHE_NAME).then((cache) => {
                cache.put(event.request, networkResponse.clone());
              });
            }
            return networkResponse;
          })
          .catch(() => caches.match("/offline.html"))
      );
    })
  );
});
