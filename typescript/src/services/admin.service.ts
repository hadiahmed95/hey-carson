import ApiService from '@/services/api.service';

class AdminService {
    private base = '/v2/admin';

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

    async updateExpertStatus(expertId: number, action: string) {
        return ApiService.post(`${this.base}/listings/${expertId}/status`, { 
            action
        });
    }

    async updateExpertStatusAndRefresh(expertId: number, action: string, currentFilters: Record<string, any> = {}) {
        return ApiService.post(`${this.base}/listings/${expertId}/status`, { 
            action,
            ...currentFilters 
        });
    }

    async fetchReviews(params: Record<string, any>) {
        const queryString = new URLSearchParams(params).toString();
        return ApiService.get(`${this.base}/reviews?${queryString}`);
    }

    async fetchReviewFilterOptions() {
        return ApiService.get(`${this.base}/reviews/filter-options`);
    }

    async updateReviewStatus(reviewId: number, status: string) {
        return ApiService.post(`${this.base}/reviews/${reviewId}/status`, { 
            status
        });
    }
}

export default new AdminService();
