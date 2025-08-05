import { defineStore } from 'pinia';
import { withLoader } from "@/utils/helpers.ts";
import ApiService from '@/services/api.service';
import AdminService from '@/services/admin.service';

interface Admin {
    id: number;
    name: string;
    email: string;
}

interface AdminState {
    admin: Admin | null;
    loading: boolean;
    error: string | null;
    experts: {
        data: any[];
        total: number;
        current_page: number;
        last_page: number;
        per_page: number;
    };
    expert: any;
    leads: {
        data: any[];
        total: number;
        current_page: number;
        last_page: number;
        per_page: number;
    };
    quotes: {
        data: any[];
        total: number;
        current_page: number;
        last_page: number;
        per_page: number;
    };
}

export const useAdminStore = defineStore('admin', {
    state: (): AdminState => ({
        admin: null,
        loading: false,
        error: null,
        experts: {
            data: [],
            total: 0,
            current_page: 1,
            last_page: 1,
            per_page: 15,
        },
        expert: null,
        leads: {
            data: [],
            total: 0,
            current_page: 1,
            last_page: 1,
            per_page: 15,
        },
        quotes: {
            data: [],
            total: 0,
            current_page: 1,
            last_page: 1,
            per_page: 15,
        },
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

        async fetchExperts(params: Record<string, any> = {}) {
            return await withLoader(async () => {
                const response = await AdminService.getExperts(params);
                this.experts = response.data.experts;
                return response.data;
            });
        },

        async fetchExpert(id: number) {
            return await withLoader(async () => {
                const response = await AdminService.getExpert(id);
                this.expert = response.data.expert;
                return response.data;
            });
        },

        async updateExpert(id: number, data: any) {
            return await withLoader(async () => {
                await AdminService.updateExpert(id, data);
                await this.fetchExpert(id);
            });
        },

        async fetchExpertFilterOptions() {
            try {
                const response = await AdminService.getExpertFilterOptions()
                return response.data
            } catch (error) {
                console.error('Error fetching expert filter options:', error)
                throw error
            }
        },

        // New leads methods
        async fetchLeads(params: Record<string, any> = {}) {
            return await withLoader(async () => {
                const response = await AdminService.getLeads(params);
                this.leads = response.data.clients;
                return response.data;
            });
        },

        async fetchLeadFilterOptions() {
            try {
                const response = await AdminService.getLeadFilterOptions()
                return response.data
            } catch (error) {
                console.error('Error fetching lead filter options:', error)
                throw error
            }
        },

        // New quotes methods
        async fetchQuotesSent(params: Record<string, any> = {}) {
            return await withLoader(async () => {
                const response = await AdminService.getQuotesSent(params);
                this.quotes = response.data.quotes;
                return response.data;
            });
        },

        async fetchQuoteFilterOptions() {
            try {
                const response = await AdminService.getQuoteFilterOptions()
                return response.data
            } catch (error) {
                console.error('Error fetching quote filter options:', error)
                throw error
            }
        },
    },
});
