import { defineStore } from 'pinia';
import ApiService from '@/services/api.service';

interface Admin {
    id: number;
    name: string;
    email: string;
}

interface AdminState {
    admin: Admin | null;
    loading: boolean;
    error: string | null;
}

export const useAdminStore = defineStore('admin', {
    state: (): AdminState => ({
        admin: null,
        loading: false,
        error: null,
    }),

    actions: {
        async fetchAdmin() {
            this.loading = true;
            this.error = null;
            try {
                const { data } = await ApiService.get('/admin/profile');
                this.admin = data;
            } catch (err: any) {
                this.error = err.response?.data?.message || 'Failed to load admin data';
            } finally {
                this.loading = false;
            }
        },
    },
});
