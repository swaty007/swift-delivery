importScripts('https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js');
if (workbox) {
    console.log(`Yay! Workbox is loaded 🎉`);
} else {
    console.log(`Boo! Workbox didn't load 😬`);
}



const escapeChars = string => {
    return string.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
};

// init workbox instance/cache
workbox.setConfig({ debug: true });
workbox.core.skipWaiting();

// assets to prefetch before considering it "installed"
workbox.precaching.precacheAndRoute([
    {
        "url": "css/site.css",
        "revision": "19752e6ee1ef3fab4a906e3c4df7b971"
    },
    {
        "url": "css/style.css",
        "revision": "7b25bb2660f3ac065763508a4af1f802"
    },
    {
        "url": "css/style.min.css",
        "revision": "4017f59287652da0694d59d2f26ba01a"
    },
    {
        "url": "img/blue_bg.png",
        "revision": "3ec50be23c7e37d631634143b38123d4"
    },
    {
        "url": "img/icon_glasses_emoji.png",
        "revision": "b80a8d034e8a2a4b6c9432e8b0f0d0f3"
    },
    {
        "url": "img/logo-144.png",
        "revision": "6edcf2dce5b7dc4d7eadc549acf93772"
    },
    {
        "url": "img/logo.png",
        "revision": "309aaf55436f0c9a9de446c005d0fe20"
    },
    {
        "url": "js/modules/Main.js",
        "revision": "bfa638eedbdec92899e87295084650da"
    },
    {
        "url": "js/scripts-bundled.js",
        "revision": "882d2aac43b01654598e2b8029c0166e"
    },
    {
        "url": "site/login",
        "revision": "882d2aac43b01654598e2b8049c0166e"
    },
]);

// Routes are now evaluated in a first-registered-wins order.

// Don't cache data routes
// workbox.routing.registerRoute(
//     new RegExp(`${escapeChars('/')}(bff|proxy).*`),
//     new workbox.strategies.NetworkOnly()
// );

// Always go to the network for account create and signin
workbox.routing.registerRoute(
    /\/signup\//,
    new workbox.strategies.NetworkOnly()
);
workbox.routing.registerRoute(
    /\/login\//,
    new workbox.strategies.NetworkOnly()
);
workbox.routing.registerRoute(
    /\/contact/,
    new workbox.strategies.NetworkFirst()
);
workbox.routing.registerRoute(
    /\/about/,
    new workbox.strategies.NetworkFirst()
);

// workbox.routing.registerRoute(
//     new RegExp('/site/about1'),
//     new workbox.strategies.NetworkFirst()
// );
// workbox.routing.registerNavigationRoute(
//     workbox.precaching.getCacheKeyForURL('/site/contact')
// );
// workbox.routing.registerNavigationRoute(
//     workbox.precaching.getCacheKeyForURL('/site/about')
// );
// workbox.routing.registerNavigationRoute(
//     workbox.precaching.getCacheKeyForURL('/site/about', {
//         whitelist: [/^(?!.*\.\w{1,7}$)/]
//     })
// );

// Go to network first for when "base html" needs updating.
// workbox.routing.registerRoute(
//     /[?&]bustprecache=.*$/i,
//     new workbox.strategies.NetworkFirst()
// );

// Go to network first when there is a "trailing slash"
// nginx will take care of removing it
// and hence the user will be redirected to the "slashless" route
// and eventually will be using the cached data if it exists
workbox.routing.registerRoute(/\/$/, new workbox.strategies.NetworkFirst());

// Fallback to "base html" for routes that
// don't end in a file extension
// workbox.routing.registerNavigationRoute('/', {
//     whitelist: [/^(?!.*\.\w{1,7}$)/]
// });

// Route for runtime caching of other local static images
// workbox.routing.registerRoute(
//     new RegExp(`${escapeChars('/weblx/images')}.*`),
//     new workbox.strategies.CacheFirst({
//         cacheName: 'local-static-images',
//         cacheableResponse: { statuses: [0, 200] }
//     })
// );

// route for runtime caching of other local static assets
// such as split bundles, etc.
// workbox.routing.registerRoute(
//     new RegExp(`${escapeChars('https://assets.web.starbucksassets.com/weblx/static')}.*`),
//     new workbox.strategies.CacheFirst({
//         cacheName: 'other-lazy-loaded',
//         cacheableResponse: { statuses: [0, 200] }
//     })
// );


workbox.routing.registerRoute(
    /\.(?:js|css)$/,
    new workbox.strategies.StaleWhileRevalidate(),
);
// route for runtime caching of image URLs that
// are referenced in API results
workbox.routing.registerRoute(
    // /https:\/\/globalassets.starbucks.com\/.+\.(png|jpg|jpeg|svg|gif|webp|bmp)/,
    /\.(?:png|gif|jpg|jpeg|svg|webp)$/,
    new workbox.strategies.CacheFirst({
        cacheName: 'images',
        cacheableResponse: { statuses: [0, 200] },
        // plugins: [
        //     new workbox.expiration.Plugin({
        //         maxEntries: 60,
        //         maxAgeSeconds: 30 * 24 * 60 * 60, // 30 Days
        //     }),
        // ],
    }),
);

self.addEventListener('activate', () => {
    self.clients.matchAll().then(clients => {
        clients.forEach(client => {
            client.postMessage({
                type: 'versionCheck',
                version: 32
            });
        });
    });
});