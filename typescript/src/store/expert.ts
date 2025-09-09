import {defineStore} from 'pinia';
import {withLoader} from "@/utils/helpers.ts";
import type {ExpertReviewsResponse, ILeadd, IExpertStat, IProjectName, ILeadDetail, ExpertStories, ExpertFaq} from "@/types.ts";
import ExpertService from "@/services/expert.service.ts";

export const useExpertStore = defineStore('expert', {
    state: () => ({
        reviews: null as ExpertReviewsResponse | null,
        leads: {} as {
            'leads': ILeadd[]
        },
        lead: {} as {
            lead: ILeadDetail
        },
        stats: {} as {
            expert_stats: IExpertStat
        } | null,
        user: null as any,
        payouts: [] as any[],
        balance: 0 as number,
        customerStories: [] as ExpertStories[],
        faqs: [] as ExpertFaq[],
    }),

    actions: {
        async fetchReviews() {
            await withLoader(async () => {
                this.reviews = (await ExpertService.getReviews()).data as ExpertReviewsResponse;
            });
        },

        async fetchLeads(params: { type?: string } = {}) {
            await withLoader(async () => {
                this.leads = (await ExpertService.getLeads(params)).data;
            });
        },

        // ADDED: New method to fetch single lead details
        async fetchLeadsDetails(leadId: string) {
            return await withLoader(async () => {
                this.lead = (await ExpertService.getLeadsDetails(leadId)).data;
            });
        },

        async fetchStats() {
            await withLoader(async () => {
                this.stats = (await ExpertService.getStats()).data as any;
            });
        },

        async updateReview(id: number, data: any) {
            await withLoader(async () => {
                await ExpertService.updateReview(id, data);
                await this.fetchReviews();
            });
        },

        async createReviewRequest(payload: any) {
            await withLoader(async () => {
                await ExpertService.createReviewRequest(payload);

            });
        },

        async createQuoteOrOffer(url: string, payload: any) {
            return await withLoader(async () => {
                return await ExpertService.createQuoteOrOffer(url, payload);
            });
        },

        async fetchProjectNames(userId?: number) {
            const response = await ExpertService.fetchProjectNames(userId);
            return response.data.project_names as IProjectName[];
        },

        async fetchExpert() {
            try {
                const response = await ExpertService.fetchExpert()
                this.user = response.data.user
            } catch (error) {
                console.log(error)
            }
        },

        async updateProfile(data: Partial<any>) {
            return await withLoader(async () => {
                const response = await ExpertService.updateProfile(data)
                this.user = response.data.user
                return {success: true, user: response.data.user};
            })
        },

        async setDefaultCard(cardId: number) {
            return this.updateProfile({default_card_id: cardId})
        },

        async deleteCard(cardId: number) {
            return this.updateProfile({remove_card: cardId})
        },

        async searchUsers(searchTerm: string) {
            const response = await ExpertService.searchUsers(searchTerm);
            return response.data;
        },

        async fetchExpertProfile(searchTerm: string) {
            const response = await ExpertService.fetchExpertProfile(searchTerm);
            return response.data;
        },

        async setExpertPayout(data: any) {
            const response = await ExpertService.setExpertPayout(data);
            return response.data;
        },

        async fetchCountries() {
            const response = await ExpertService.fetchCountries();
            return response.data;
        },

        async fetchFaqs() {
            return await withLoader(async () => {
                const response = await ExpertService.fetchFaqs();
                this.faqs = response.data.data;
                return response.data;
            });
        },

        async createFaq(data: { question: string; answer: string }) {
            return await withLoader(async () => {
                const response = await ExpertService.createFaq(data);
                this.faqs.unshift(response.data.data);
                return response.data;
            });
        },

        async updateFaq(id: number, data: { question?: string; answer?: string }) {
            return await withLoader(async () => {
                const response = await ExpertService.updateFaq(id, data);
                const index = this.faqs.findIndex(faq => faq.id == id);
                if (index !== -1) {
                    this.faqs[index] = response.data.data;
                }
                return response.data.data;
            });
        },

        async deleteFaq(id: number) {
            return await withLoader(async () => {
                await ExpertService.deleteFaq(id);
                this.faqs = this.faqs.filter(faq => faq.id != id);
            });
        },

        async fetchCustomerStories() {
            return await withLoader(async () => {
                const response = await ExpertService.fetchCustomerStories();
                this.customerStories = response.data.data;
                return response.data;
            });
        },

        async createCustomerStory(data: FormData) {
            return await withLoader(async () => {
                const response = await ExpertService.createCustomerStory(data);
                this.customerStories.unshift(response.data.data);
                return response.data;
            });
        },

        async updateCustomerStory(id: number, data: FormData) {
            return await withLoader(async () => {
                const response = await ExpertService.updateCustomerStory(id, data);
                const index = this.customerStories.findIndex(story => story.id == id);
                if (index !== -1) {
                    this.customerStories[index] = response.data.data;
                }
                return response.data.data;
            });
        },

        async deleteCustomerStory(id: number) {
            return await withLoader(async () => {
                await ExpertService.deleteCustomerStory(id);
                this.customerStories = this.customerStories.filter(story => story.id != id);
            });
        },
    },
});
