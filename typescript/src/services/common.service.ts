import ApiService from '@/services/api.service';

class CommonService {
    async addCreditCard(cardData: any) {
        return ApiService.post('/v2/payment/save-card', cardData)
    }

    async cardPayment(paymentData: any) {
        return ApiService.post('/v2/payment/card-payment', paymentData)
    }

    async fetchAppNotifications(params: Record<string, any> = {}) {
        return ApiService.get(`/v2/events`, { ...params });
    }

    async updateAppNotification(eventId: number) {
        return ApiService.patch(`/v2/events/${eventId}`);
    }

    async updateAppNotifications() {
        return ApiService.patch(`/v2/events`);
    }
}

export default new CommonService();