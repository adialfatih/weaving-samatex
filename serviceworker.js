const cacheVersion = 'rjsStorage';

const filesToCache = [
  
  '/',
  
  //----------JavaScripts----------//
  
  '/assets/vendors/scripts/core.js',
  '/assets/vendors/scripts/script.min.js',
  '/assets/vendors/scripts/process.js',
  '/assets/vendors/scripts/layout-settings.js',

  //----------CSS----------//
  //themes
  
  '/assets/vendors/styles/core.css',
  '/assets/vendors/styles/style.css',
  '/assets/vendors/styles/icon-font.min.css',
  '/new_db/style.css'

];

self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open(cacheVersion)
      .then(function(cache) {
        return cache.addAll(filesToCache)
      })
  )
});

self.addEventListener("activate", event => {
  console.log("Service worker activated");
});

self.addEventListener('fetch', function(event) {
  event.respondWith(
    caches.match(event.request)
      .then(function(res) {
        if (res) return res;

        return fetch(event.request);
      })
  );
});