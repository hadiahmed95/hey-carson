import { createApi } from '@/services/api.service';

class AdminService {
    private api = createApi('admin'); // Creates /api/v2/admin base

    async fetchAdmin() {
        return this.api.get('/profile');
    }

    async fetchExperts(params: Record<string, any>) {
        const queryString = new URLSearchParams(params).toString();
        return this.api.get(`/listings?${queryString}`);
    }

    async fetchExpertFilterOptions() {
        return this.api.get('/filter-options');
    }

    async loginAsUser(userId: number) {
        return this.api.post(`/login-as/${userId}`, {});
    }

    async updateExpertStatus(expertId: number, action: string) {
        return this.api.post(`/listings/${expertId}/status`, { 
            action
        });
    }

    async updateExpertStatusAndRefresh(expertId: number, action: string, currentFilters: Record<string, any> = {}) {
        return this.api.post(`/listings/${expertId}/status`, { 
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
