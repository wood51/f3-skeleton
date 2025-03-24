if ('serviceWorker' in navigator && 'PushManager' in window) {
    navigator.serviceWorker.register('app/views/assets/js/PushServiceWorker.js').then(function(registration) {
        Notification.requestPermission().then(function(permission) {
            if (permission === 'granted') {
                registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array('{{@VAPID_PUBLIC}}')
                }).then(function(subscription) {
                    fetch('/notif/register', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(subscription)
                    });
                });
            }
        });
    });
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
    const rawData = atob(base64);
    return Uint8Array.from([...rawData].map(char => char.charCodeAt(0)));
}
