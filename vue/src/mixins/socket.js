import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

export default {
    methods: {
        initializeSocket(token) {
            if (token) {
                window.pusher = Pusher;
                window.Echo = new Echo({
                    broadcaster: 'reverb',
                    key: process.env.VUE_APP_REVERB_APP_KEY, // Your Reverb app key
                    wsHost: window.location.hostname, // The host where Reverb is running
                    wsPort: process.env.VUE_APP_REVERB_PORT ?? 80, // The port where Reverb is running
                    wssPort: process.env.VUE_APP_REVERB_PORT ?? 443, // The port where Reverb is running
                    enabledTransports: ['ws', 'wss'], // Enable websockets
                    forceTLS: (process.env.VUE_APP_REVERB_SCHEME ?? 'https') === 'https',
                    disableStats: true,
                    auth: {
                        headers: {
                            Authorization: 'Bearer ' + token,
                        },
                    },
                });
            }
        },
    }
};
