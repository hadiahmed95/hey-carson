import ApiService from '@/services/api.service';

class ClientService {
    private base = '/v2/client';

    getLatestRequests() {
        return ApiService.get(`${this.base}/latest-requests`);
    }

    getFeaturedServicesAndExperts() {
        return ApiService.get(`${this.base}/featured-services-and-experts`);
    }

    getRequests() {
        return ApiService.get(`${this.base}/requests`);
    }

    getTransactions() {
        return ApiService.get(`${this.base}/transactions`);
    }

    getPackagedServices() {
        return ApiService.get(`${this.base}/packaged-services`);
    }

    getReviewRequests() {
        return ApiService.get(`${this.base}/review-requests`);
    }

    postReview(data: any) {
        return ApiService.post(`${this.base}/reviews`, data);
    }

    getReviews() {
        return ApiService.get(`${this.base}/reviews`);
    }

    updateReview(id: number, data: any) {
        return ApiService.put(`${this.base}/reviews/${id}`, data);
    }

    getRequest(requestId: string) {
        return ApiService.get(`${this.base}/request/${requestId}`);
    }

    async getMatched(data: any) {
        return ApiService.post('/get-matched', data)
    }

    async freeQuote(data: any) {
        return ApiService.post('/free-quote', data)
    }

    async createRequest(data: any) {
        return ApiService.post(`${this.base}/create-request`, data)
    }

    async fetchClient() {
        return ApiService.get('/client/settings')
    }

    async addCreditCard(cardData: any) {
        return ApiService.post('/payment/save-card', cardData)
    }

    async updateProfile(data: Partial<any>) {
        return ApiService.post('/client/settings', data)
    }
}

export default new ClientService();