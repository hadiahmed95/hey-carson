import { createApp } from 'vue'
import PolarisVue from '@ownego/polaris-vue'
import '@ownego/polaris-vue/dist/style.css'
import store from './store';

import router from "@/routes";
import App from './App.vue'
import axios from 'axios';
import InfiniteScrollDirective from './directives/v-infinite-scroll';
import socket from "@/mixins/socket";

axios.defaults.withCredentials = true;
axios.defaults.baseURL = process.env.VUE_APP_API_URL;
axios.defaults.headers.post['Content-Type'] = 'application/json';
axios.defaults.headers.post['Accept'] = 'application/json';

let token = localStorage.getItem('CURRENT_TOKEN')

if (token) {
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
    socket.methods.initializeSocket(token);
}

axios.get('api/auth-check').catch(() => {
    delete axios.defaults.headers.common['Authorization'];

    const user = JSON.parse(window.localStorage.getItem('CURRENT_USER'));
    if (user) {
        if (user.role_id === 2) {
            window.location.href = '/client/login'
        } else if (user.role_id === 3) {
            window.location.href = '/expert/login'
        } else {
            window.location.href = '/admin/login'
        }
    }

    window.localStorage.removeItem('CURRENT_USER')
    window.localStorage.removeItem('CURRENT_TOKEN')
})



const app = createApp(App)

app.directive('infinite-scroll', InfiniteScrollDirective);

app.use(PolarisVue).use(store).use(router).mount('#app')
