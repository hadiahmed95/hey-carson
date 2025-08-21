import ApiService from '@/services/api.service';

export default {
    async login(data: { email: string; password: string; role: string }) {
        return ApiService.post('/login', data)
    },
    async signupClient(data: any) {
        return ApiService.post('/register', data)
    },
    async signupExpert(data: any) {
        return ApiService.post('/v2/expert/register', data)
    },
    async switchToOldDashboard() {
        return ApiService.get('/sso/switch-to-old')
    },
    async forgotPassword(email: string) {
        return ApiService.post('/forgot-password', {email: email, is_new_dash: true})
    },
    async resetPassword(payload: { email: string; token: string; password: string; password_confirmation: string }) {
        return ApiService.post('/reset-password', payload)
    },
    async TempLogin(data: { email: string; password: string }) {
        return ApiService.post('/v2/login', data)
    },
}