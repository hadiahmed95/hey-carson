import ApiService from '@/services/api.service';

class AdminService {
    private base = '/admin';

    async getExperts(params: Record<string, any>) {
        // return ApiService.get(`${this.base}/experts`, params);
        return ApiService.get('http://127.0.0.1:8000/api/test-experts', params);
    }

    async getExpert(id: number) {
        return ApiService.get(`${this.base}/experts/${id}`);
    }

    async updateExpert(id: number, data: any) {
        return ApiService.post(`${this.base}/experts/${id}`, data);
    }

    async getClients(params: Record<string, any> = {}) {
        return ApiService.get(`${this.base}/clients`, params);
    }

    // New leads methods - reusing clients endpoint since leads are essentially clients
    async getLeads(params: Record<string, any> = {}) {
        // return ApiService.get(`${this.base}/clients`, params);
        return ApiService.get('http://127.0.0.1:8000/api/test-clients', params);
    }

    async getPayouts(params: Record<string, any> = {}) {
        return ApiService.get(`${this.base}/payouts`, params);
    }

    async getTransactions() {
        return ApiService.get(`${this.base}/transactions`);
    }

    async getExpertFilterOptions(params: Record<string, any> = {}) {
        // return ApiService.get(`${this.base}/filter-options`, params);
        return ApiService.get('http://127.0.0.1:8000/api/filter-options', params);
    }

    // New leads filter options method
    async getLeadFilterOptions(params: Record<string, any> = {}) {
        // For now, reuse the client filter options since leads are clients
        // This can be extended to have specific lead filters if needed
        return ApiService.get('http://127.0.0.1:8000/api/lead-filter-options', params);
    }
}

export default new AdminService();
