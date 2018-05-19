self.addEventListener('install', function(e) {
 e.waitUntil(
   caches.open('openbenches').then(function(cache) {
     return cache.addAll([
       '/style.css'
     ]);
   })
 );
});

self.addEventListener('fetch', function(event) {
  event.respondWith(
    fetch(event.request).catch(function() {
      return caches.match(event.request);
    })
  );
});

