import { defineStore } from 'pinia';
import { withLoader } from '@/utils/helpers.ts';
import ClientService from '@/services/client.service';
import type {
    IRequest,
    IPackagedService,
    IShopifyProductUpdate,
    IExpertt,
    ReviewRequestsResponse,
    ClientReviewsResponse,
    ITranscationn
} from "@/types";
import { useAuthStore } from "@/store/auth.ts";

export const useClientStore = defineStore('client', {
    state: () => ({
        latestRequests: {} as {
            latest_requests: IRequest[]
        },
        featuredServicesAndExperts: {} as {
            'featured_services': IPackagedService[],
            'featured_experts': IExpertt[],
            'shopify_product_updates': IShopifyProductUpdate[]
        },
        requests: {} as {
            requests: IRequest[]
        },
        packagedServices: {} as {
            packaged_services: IPackagedService[]
        },
        reviewRequests: {
            pending_review_requests: []
        } as ReviewRequestsResponse,
        reviews: null as ClientReviewsResponse | null,
        request: {} as {
            request: IRequest
        },
        transactions: {} as {
            transactions: ITranscationn[]
        },
        user: null as any,
    }),

    actions: {
        async fetchLatestRequests() {
            await withLoader(async () => {
                this.latestRequests = (await ClientService.getLatestRequests()).data;
            });
        },

        async fetchFeaturedServicesAndExperts() {
            await withLoader(async () => {
                this.featuredServicesAndExperts = (await ClientService.getFeaturedServicesAndExperts()).data;
            });
        },

        async fetchRequests() {
            await withLoader(async () => {
                this.requests = (await ClientService.getRequests()).data;
            });
        },

        async fetchTransactions() {
            await withLoader(async () => {
                this.transactions = (await ClientService.getTransactions()).data;
            });
        },

        async fetchPackagedServices() {
            await withLoader(async () => {
                this.packagedServices = (await ClientService.getPackagedServices()).data;
            });
        },

        async fetchReviewRequests() {
            await withLoader(async () => {
                this.reviewRequests = (await ClientService.getReviewRequests()).data;
            });
        },

        async fetchReviews() {
            await withLoader(async () => {
                this.reviews = (await ClientService.getReviews()).data as ClientReviewsResponse;
            });
        },

        async getMatched(data: any) {
            await withLoader(async () => {
                const res = await ClientService.getMatched(data);
                if (res.data.status) {
                    const authStore = useAuthStore()
                    authStore.setCurrentUser( res.data.user, res.data.token );
                }
            });
        },

        async freeQuote(data: any) {
            await withLoader(async () => {
                const res = await ClientService.freeQuote(data);
                if (res.data.status) {
                    const authStore = useAuthStore()
                    authStore.setCurrentUser( res.data.user, res.data.token );
                }
            });
        },

        async createRequest(data: any, myRequestsPage = false) {
            await withLoader(async () => {
                await ClientService.createRequest(data);
                if (myRequestsPage) {
                    await this.fetchRequests();
                }
                else {
                    await this.fetchLatestRequests();
                }
            });
        },

        async updateReview(id: number, data: any) {
            await withLoader(async () => {
                await ClientService.updateReview(id, data);
                await this.fetchReviews();
            });
        },

        async fetchRequest(requestId: string) {
            await withLoader(async () => {
                this.request = (await ClientService.getRequest(requestId)).data;
            });
        },

        async fetchClient() {
            try {
                const response = await ClientService.fetchClient()
                this.user = response.data.user
            } catch (error) {
                console.log(error)
            }
        },

        async setDefaultCard(cardId: number) {
            return this.updateProfile({ default_card_id: cardId })
        },

        async deleteCard(cardId: number) {
            return this.updateProfile({ remove_card: cardId })
        },

        async updateOffer(projectId: number, offerId: string) {
            return await ClientService.updateOffer(projectId, offerId)
        },

        async declineOffer(projectId: number, offerId: number) {
            return await ClientService.declineOffer(projectId, offerId)
        },

        async createReview(data: any) {
            await withLoader(async () => {
                const response = await ClientService.postReview(data);
                if (response.data.review && this.reviews) {
                    this.reviews.written_reviews.push(response.data.review);
                }

                if (!this.reviews) {
                    await this.fetchReviews();
                }
            });
        },

        async updateProfile(data: Partial<any>) {
            try {
                const response = await ClientService.updateProfile(data);
                this.user = response.data.user;
                return { success: true, user: response.data.user };
            } catch (error) {
                console.log(error);
                return { success: false, error };
            }
        },
    },
});
