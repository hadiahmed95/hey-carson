import { defineStore } from 'pinia';
import AdminService from '@/services/admin.service';
import { withLoader } from '@/utils/helpers.ts';
import type { IListing, IClient } from '@/types.ts';

interface Admin {
    id: number;
    name: string;
    email: string;
}

interface AdminState {
    admin: Admin | null;
    loading: boolean;
    error: string | null;
    experts: IListing[];
    totalExperts: number;
    currentPage: number;
    lastPage: number;
    clients: IClient[];
    totalClients: number;
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
        clients: [],
        totalClients: 0,
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

        async fetchExperts(params: Record<string, any>, appendData = false) {
            return await withLoader(async () => {
                const response = await AdminService.fetchExperts(params);
                
                if (appendData && params.page > 1) {
                    this.experts.push(...(response.data.experts.data || []));
                } else {
                    this.experts = response.data.experts.data || [];
                }
                
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

        async updateExpertStatus(expertId: number, action: string) {
            return await withLoader(async () => {
                const response = await AdminService.updateExpertStatus(expertId, action);
                const expertIndex = this.experts.findIndex(expert => expert.id === expertId);
                if (expertIndex !== -1 && response.data.expert) {
                    this.experts[expertIndex] = { ...this.experts[expertIndex], ...response.data.expert };
                }
                
                return response.data;
            });
        },

        async updateExpertStatusAndRefresh(expertId: number, action: string) {
            return await withLoader(async () => {
                const response = await AdminService.updateExpertStatus(expertId, action);
                
                if (response.data.experts) {
                    this.experts = response.data.experts.data || [];
                    this.totalExperts = response.data.experts_count || 0;
                    this.currentPage = response.data.experts.current_page || 1;
                    this.lastPage = response.data.experts.last_page || 1;
                } else {
                    const expertIndex = this.experts.findIndex(expert => expert.id === expertId);
                    if (expertIndex !== -1 && response.data.expert) {
                        this.experts[expertIndex] = { ...this.experts[expertIndex], ...response.data.expert };
                    }
                }
                
                return response.data;
            });
        },

        async fetchClients(params: Record<string, any>, appendData = false) {
            return await withLoader(async () => {
                const response = await AdminService.fetchClients(params);
                
                if (appendData && params.page > 1) {
                    this.clients.push(...(response.data.clients.data || []));
                } else {
                    this.clients = response.data.clients.data || [];
                }
                
                this.totalClients = response.data.clients_count || 0;
                this.currentPage = response.data.clients.current_page || 1;
                this.lastPage = response.data.clients.last_page || 1;
                
                return response.data;
            });
        },

        async fetchClientFilterOptions() {
            return await withLoader(async () => {
                const response = await AdminService.fetchClientFilterOptions();
                return response.data;
            });
        },
    },
});
