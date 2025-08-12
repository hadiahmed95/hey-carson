import { defineStore } from 'pinia';
import AdminService from '@/services/admin.service';
import { withLoader } from '@/utils/helpers.ts';

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
                const { data } = await AdminService.fetchAdmin();
                this.admin = data;
            } catch (err: any) {
                this.error = err.response?.data?.message || 'Failed to load admin data';
            } finally {
                this.loading = false;
            }
        },

        async fetchExperts(params: Record<string, any>) {
            return await withLoader(async () => {
                const response = await AdminService.fetchExperts(params);
                return response.data;
            });
        },

        async fetchExpertFilterOptions() {
            return await withLoader(async () => {
                const response = await AdminService.fetchExpertFilterOptions();
                return response.data;
            });
        },

        async loginAsUser(userId: number) {
            return await withLoader(async () => {
                const response = await AdminService.loginAsUser(userId);
                return response.data;
            });
        },
    },
});
