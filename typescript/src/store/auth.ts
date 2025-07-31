// stores/Auth.ts
import { defineStore } from 'pinia'
import AuthService from '@/services/auth.service'
import { api } from '@/services/api.service';
import {withLoader} from "@/utils/helpers.ts";

export const useAuthStore = defineStore('auth', {
    state: () => ({
        token: '' as string,
        user: null as any,
    }),

    actions: {
        async login(email: string, password: string, role: string) {
            await withLoader( async () => {
                const response = await AuthService.login({ email, password, role })
                api.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.token;
                this.token = response.data.token
                this.user = response.data.user
                localStorage.setItem('CURRENT_USER', JSON.stringify(this.user))
                localStorage.setItem('CURRENT_TOKEN', this.token)
            })
        },
        setCurrentUser(user: {}, token: string) {
            api.defaults.headers.common['Authorization'] = 'Bearer ' + token;
            this.token = token
            this.user = user
            localStorage.setItem('CURRENT_USER', JSON.stringify(this.user))
            localStorage.setItem('CURRENT_TOKEN', this.token)
        },
        logout() {
            this.token = ''
            this.user = null
            localStorage.removeItem('CURRENT_USER')
            localStorage.removeItem('CURRENT_TOKEN')
            delete api.defaults.headers.common['Authorization'];
        },
        async switchToOldDashboard() {
            return await AuthService.switchToOldDashboard();
        },
        async forgotPassword(email: string) {
            return await withLoader( async () => {
                return await AuthService.forgotPassword(email);
            })
        },
        async resetPassword( payload: { email: string; token: string; password: string; password_confirmation: string } ) {
            return await withLoader( async () => {
                return await AuthService.resetPassword(payload);
            })
        },
        async loginAs(email: string, role: string) {
            try {
                const response = await ApiService.post('/admin/login-as', {
                    email,
                    role
                })
                
                this.token = response.data.token
                this.user = response.data.user
                
                // Store in localStorage
                localStorage.setItem('token', this.token)
                localStorage.setItem('user', JSON.stringify(this.user))
                
                return response.data
            } catch (error) {
                throw error
            }
        }
    }
})
