import {defineStore} from 'pinia';
import {withLoader} from "@/utils/helpers.ts";
import type {ExpertReviewsResponse, ILeadd, IExpertStat, IProjectName, ILeadDetail} from "@/types.ts";
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

        async fetchCountries() {
            const response = await ExpertService.fetchCountries();
            return response.data;
        }
    },
});
