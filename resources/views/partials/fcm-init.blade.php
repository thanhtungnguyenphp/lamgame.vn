{{-- Firebase Cloud Messaging — Push Notification Setup --}}
@if(config('firebase.web'))
<script type="module">
import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js';
import { getMessaging, getToken, onMessage } from 'https://www.gstatic.com/firebasejs/10.12.0/firebase-messaging.js';

const firebaseConfig = @json(config('firebase.web'));
const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

async function registerFcmToken() {
    try {
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') return;

        const token = await getToken(messaging, {
            vapidKey: firebaseConfig.vapidKey,
            serviceWorkerRegistration: await navigator.serviceWorker.register('/firebase-messaging-sw.js')
        });

        if (token) {
            await fetch('/api/v1/notifications/fcm-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({ token, platform: 'web' }),
            });
        }
    } catch (e) {
        console.warn('FCM registration failed:', e.message);
    }
}

onMessage(messaging, (payload) => {
    const { title, body, icon } = payload.notification || {};
    if (Notification.permission === 'granted') {
        new Notification(title || 'Làm Game', {
            body: body || '',
            icon: icon || '/images/lamgame-icon-192.png',
        });
    }
});

// Auto-register if user is logged in
@auth('customer')
if ('serviceWorker' in navigator && 'Notification' in window) {
    registerFcmToken();
}
@endauth

// Expose for manual trigger (e.g., after login)
window.registerFcmToken = registerFcmToken;
</script>
@endif
