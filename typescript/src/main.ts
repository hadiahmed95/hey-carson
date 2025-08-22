import { createApp } from 'vue'
import pinia from './store'
import './index.css'
import App from './App.vue'
import router from "./router";
import { api } from '@/services/api.service'
import { useAuthStore } from '@/store/auth.ts'
import "vue-search-select/dist/VueSearchSelect.css"

const app = createApp(App)

app.use(router)
app.use(pinia)

const token = localStorage.getItem('CURRENT_TOKEN')
const user = localStorage.getItem('CURRENT_USER')

if (token) {
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`

    const auth = useAuthStore()
    auth.token = token
    auth.user = user ? JSON.parse(user) : null
}

app.mount('#app')
