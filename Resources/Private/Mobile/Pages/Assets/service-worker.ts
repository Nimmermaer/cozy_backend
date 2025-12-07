// src/service-worker.ts
const sw = self as unknown as ServiceWorkerGlobalScope;
 // console.log('Service Worker geladen und aktiv.');

// Event-Listener, um die Push-Nachricht zu empfangen
sw.addEventListener('push', (event: any) => {
    // Daten aus der Push-Nachricht extrahieren
    const data = event.data ? event.data.json() : {
        title: 'Neue News verfügbar',
        body: 'Klicken Sie hier, um die neuesten Informationen zu sehen.',
        url: '#/'
    };

    const title = data.title;
    const options = {
        body: data.body,
        icon: '/favicon.ico', // Pfad zu einem Icon
        badge: '/favicon.ico', // Pfad zu einem Badge (optional)
        data: {
            url: data.url // URL, die beim Klick geöffnet werden soll
        }
    };
    event.waitUntil(
        sw.registration.showNotification(title, options)
    );

});

// Event-Listener, wenn der Benutzer auf die Benachrichtigung klickt
sw.addEventListener('notificationclick', (event: any) => {
    event.notification.close();

    const urlToOpen = event.notification.data.url;

    event.waitUntil(
        sw.clients.matchAll({ type: 'window' }).then((windowClients) => {
            for (let i = 0; i < windowClients.length; i++) {
                const client = windowClients[i];
                if (client.url.includes(sw.location.origin) && 'focus' in client) {
                    if (client.url.includes(urlToOpen)) {
                        return client.focus();
                    }
                    return client.navigate(sw.location.origin + urlToOpen).then(client => client?.focus());
                }
            }
            if (sw.clients.openWindow) {
                return sw.clients.openWindow(urlToOpen);
            }
        })
    );
});