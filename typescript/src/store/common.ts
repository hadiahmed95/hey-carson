// stores/Auth.ts
import {defineStore} from 'pinia'
import ExpertService from "@/services/expert.service.ts";
import CommonService from "@/services/common.service.ts";
import type {
    IAppNotification,
} from "@/types.ts";

export const useCommonStore = defineStore('common', {
    state: () => ({
        appNotifications: null as { events: IAppNotification[] } | null,
    }),

    actions: {
        async getExperts(params: Record<string, any>) {
            const res = await ExpertService.getExperts(params);
            return res.data.experts;
        },

        async addCreditCard(cardData: any) {
            return await CommonService.addCreditCard(cardData)
        },

        async cardPayment(paymentData: any) {
            return await CommonService.cardPayment(paymentData)
        },


        async fetchAppNotifications(params: { event?: string } = {}) {
            this.appNotifications = (await CommonService.fetchAppNotifications(params)).data;
        },

        async updateAppNotification(eventId: number) {
            await CommonService.updateAppNotification(eventId);
        },

        async updateAppNotifications() {
            await CommonService.updateAppNotifications();
        },
    }
})
