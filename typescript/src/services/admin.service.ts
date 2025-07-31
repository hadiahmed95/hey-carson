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
}

export default new AdminService();
