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
    experts: any[];
    totalExperts: number;
    currentPage: number;
    lastPage: number;
}

export const useAdminStore = defineStore('admin', {
    state: (): AdminState => ({
        admin: null,
        loading: false,
        error: null,
        experts: [],
        totalExperts: 0,
        currentPage: 1,
        lastPage: 1,
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
                
                this.experts = response.data.experts.data || [];
                this.totalExperts = response.data.experts_count || 0;
                this.currentPage = response.data.experts.current_page || 1;
                this.lastPage = response.data.experts.last_page || 1;
                
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

        async updateExpertStatusAndRefresh(expertId: number, action: string, currentFilters: Record<string, any> = {}) {
            return await withLoader(async () => {
                const response = await AdminService.updateExpertStatus(expertId, action, currentFilters);
                
                this.experts = response.data.experts.data || [];
                this.totalExperts = response.data.experts_count || 0;
                this.currentPage = response.data.experts.current_page || 1;
                this.lastPage = response.data.experts.last_page || 1;
                
                return response.data;
            });
        },
    },
});
