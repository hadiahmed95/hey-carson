import ApiService from '@/services/api.service';

class ExpertService {
    private base = '/v2/expert';

    async getExperts(params: Record<string, any>) {
        const queryString = new URLSearchParams(params).toString();
        return ApiService.get(`/expert-list?${queryString}`);
    }

    async getReviews() {
        return ApiService.get(`${this.base}/reviews`);
    }

    async getLeads(params: Record<string, any> = {}) {
        return ApiService.get(`${this.base}/leads`, { ...params });
    }

    async getStats() {
        return ApiService.get(`${this.base}/stats`);
    }
}

export default new ExpertService();