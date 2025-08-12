import ApiService from '@/services/api.service';

class AdminService {
    private base = '/admin';

    async fetchAdmin() {
        return ApiService.get(`${this.base}/profile`);
    }

    async fetchExperts(params: Record<string, any>) {
        const queryString = new URLSearchParams(params).toString();
        return ApiService.get(`${this.base}/listings?${queryString}`);
    }

    async fetchExpertFilterOptions() {
        return ApiService.get(`${this.base}/filter-options`);
    }

    async loginAsUser(userId: number) {
        return ApiService.post(`${this.base}/login-as/${userId}`, {});
    }

    async updateExpertStatus(expertId: number, action: string, currentFilters: Record<string, any> = {}) {
        return ApiService.post(`${this.base}/listings/${expertId}/status`, { 
            action,
            ...currentFilters 
        });
    }
}

export default new AdminService();
