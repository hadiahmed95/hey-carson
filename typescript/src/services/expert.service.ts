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

    async getLeadsDetails(leadId: string) {
        return ApiService.get(`${this.base}/lead/${leadId}`);
    }

    updateReview(id: number, data: any) {
        return ApiService.put(`${this.base}/reviews/${id}`, data);
    }

    createReviewRequest(payload: any) {
        return ApiService.post(`${this.base}/review-requests`, payload);
    }

    async getStats() {
        return ApiService.get(`${this.base}/stats`);
    }

    async fetchExpert() {
        return ApiService.get(`${this.base}/settings`)
    }

    async updateProfile(data: Partial<any>) {
        return ApiService.post(`${this.base}/settings`, data)
    }

    async createQuoteOrOffer(url: string, data: any) {
        return ApiService.post(`${this.base}/${url}`, data)
    }

    fetchProjectNames(userId?: number) {
        const params = userId ? `?user_id=${userId}` : "";
        return ApiService.get(`${this.base}/project-names${params}`);
    }

    searchUsers(searchTerm: string) {
        return ApiService.get(
            `${this.base}/search-users?search=${encodeURIComponent(searchTerm)}`
        );
    }

    fetchExpertProfile(searchTerm: string) {
        return ApiService.get(
            `${this.base}/expert-profile?search=${encodeURIComponent(searchTerm)}`
        );
    }

    setExpertPayout(data: Partial<any>) {
        return ApiService.post(`${this.base}/payouts`, data);
    }

    fetchCountries() {
        return ApiService.get(
            `https://restcountries.com/v3.1/all?fields=name,cca2`
        );
    }

    fetchFaqs() {
        return ApiService.get(`${this.base}/faqs`);
    }

    createFaq(data: { question: string; answer: string }) {
        return ApiService.post(`${this.base}/faqs`, data);
    }

    updateFaq(id: number, data: { question?: string; answer?: string }) {
        return ApiService.put(`${this.base}/faqs/${id}`, data);
    }

    deleteFaq(id: number) {
        return ApiService.delete(`${this.base}/faqs/${id}`);
    }

    fetchCustomerStories() {
        return ApiService.get(`${this.base}/customer-stories`);
    }

    createCustomerStory(data: FormData) {
        return ApiService.post(`${this.base}/customer-stories`, data, true); // true for multipart
    }

    updateCustomerStory(id: number, data: FormData) {
        data.append('_method', 'PUT');
        return ApiService.post(`${this.base}/customer-stories/${id}`, data, true);
    }

    deleteCustomerStory(id: number) {
        return ApiService.delete(`${this.base}/customer-stories/${id}`);
    }
}

export default new ExpertService();