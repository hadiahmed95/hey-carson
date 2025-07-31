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

    updateReview(id: number, data: any) {
        return ApiService.put(`${this.base}/reviews/${id}`, data);
    }

    createReviewRequest(payload: any) {
        return ApiService.post(`${this.base}/review-requests`, payload);
    }

    fetchProjectNames() {
        return ApiService.get(`${this.base}/project-names`);
    }

    async getStats() {
        return ApiService.get(`${this.base}/stats`);
    }
}

export default new ExpertService();