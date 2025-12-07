// src/services/PushService.ts

export class PushService {
    private key: HTMLElement | null = document.getElementById('VapidPublicKey');
    private VAPID_PUBLIC_KEY: string | undefined;
    constructor() {
        this.initializeVapidKey();
    }
    private initializeVapidKey(): void {

        if (this.key) {
            const keyFromData = this.key.dataset.key;

            if (keyFromData) {
                this.VAPID_PUBLIC_KEY = keyFromData;
             //   console.log("VAPID Key geladen:", this.VAPID_PUBLIC_KEY);
            } else {
                console.error("Fehler: Das data-key Attribut wurde nicht gefunden.");
            }
        } else {
            console.error("Fehler: Element mit ID 'VapidPublicKey' wurde nicht gefunden.");
        }
    }
    // Dein TYPO3-Endpunkt, der die Abonnements speichert
    private SUBSCRIPTION_ENDPOINT = '/api/push-subscribe';

    /**
     * Registriert den Service Worker und fordert die Push-Benachrichtigungsberechtigung an.
     */
    public async subscribeToPush(): Promise<void> {
        if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
            console.error('Push-Benachrichtigungen werden von diesem Browser nicht unterstützt.');
            return;
        }

        try {
            // 1. Service Worker registrieren

            const swRegistration = await navigator.serviceWorker.register('/service-worker.js');
          //  console.log('Service Worker erfolgreich registriert.');

            // 2. Berechtigung prüfen/anfordern
            let permission = Notification.permission;
            if (permission === 'default') {
                permission = await Notification.requestPermission();
            }
            if (permission !== 'granted') {
                console.warn('Benachrichtigungsberechtigung verweigert.');
                return;
            }

            // 3. Abonnement erstellen
            let vapidKey = this.VAPID_PUBLIC_KEY;
            if(!vapidKey) {
                return ;
            }
            const subscription = await swRegistration.pushManager.subscribe({
                userVisibleOnly: true, // Muss True sein
                applicationServerKey: vapidKey
            });

           // console.log('Abonnement erstellt:', subscription);

            // 4. Abonnement an das TYPO3-Backend senden
            await this.sendSubscriptionToServer(subscription);

        } catch (error) {
            console.error('Fehler beim Abonnieren von Push-Benachrichtigungen:', error);
        }
    }

    private async sendSubscriptionToServer(subscription: PushSubscription): Promise<void> {
        // Hier POSTen wir das JSON-Objekt an TYPO3.
        // TYPO3 muss es speichern (in DB) und später zum Senden verwenden.
        const response = await fetch(window.location.origin + this.SUBSCRIPTION_ENDPOINT, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(subscription),
        });
        if (!response.ok) {
          //  console.error('Fehler beim Speichern des Abonnements im Backend.');
        } else {
            console.log('Abonnement erfolgreich an das Backend gesendet.');
        }
    }
}