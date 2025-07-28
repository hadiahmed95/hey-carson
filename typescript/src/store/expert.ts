import { defineStore } from "pinia";
import { withLoader } from "@/utils/helpers.ts";
import type {
  ExpertReviewsResponse,
  ILeadd,
  IExpertStat,
  IProjectName,
} from "@/types.ts";
import ExpertService from "@/services/expert.service.ts";

export const useExpertStore = defineStore("expert", {
  state: () => ({
    reviews: null as ExpertReviewsResponse | null,
    leads: {} as {
      leads: ILeadd[];
    },
    stats: {} as {
      expert_stats: IExpertStat;
    } | null,
    user: null as any,
  }),

  actions: {
    async fetchReviews() {
      await withLoader(async () => {
        this.reviews = (await ExpertService.getReviews())
          .data as ExpertReviewsResponse;
      });
    },
    async fetchLeads(params: { type?: string } = {}) {
      await withLoader(async () => {
        this.leads = (await ExpertService.getLeads(params)).data;
      });
    },
    async fetchLeadsDetails(leadId: number) {
      await withLoader(async () => {
        const response = await ExpertService.getLeadsDetails(leadId);
        return response;
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
    async fetchProjectNames() {
      return await withLoader(async () => {
        return (await ExpertService.fetchProjectNames()).data
          .project_names as IProjectName[];
      });
    },
    async fetchClient() {
      try {
        // const response = await
        this.user = {
          id: 2,
          program_id: null,
          partner_id: null,
          click_id: null,
          role_id: 2,
          usertype: "paid",
          first_name: "Client",
          last_name: "User",
          email: "client.user@example.com",
          url: "client.example.com",
          is_featured_expert: null,
          company_type: null,
          shopify_plan: null,
          email_verified_at: null,
          timezone: null,
          new_messages: "instant",
          project_notifications: "instant",
          photo: "profile-photo/2/avatar.jpg",
          password_changed: null,
          created_at: "2024-06-23T18:04:33.000000Z",
          updated_at: "2024-11-04T07:53:49.000000Z",
          is_tester: 1,
          deleted_at: null,
          is_migrated: 0,
          source: "Website Direct",
          availability_status: "available",
          business_address: null,
          phone_number: null,
          languages: null,
          is_disable: 0,
          saved_cards: [
            {
              id: 1,
              card_type: "visa",
              last_digits: "4242",
              exp_date: "12/26",
              last_used: "2025-06-28T14:35:00Z",
              default: true,
            },
            {
              id: 2,
              card_type: "mastercard",
              last_digits: "5100",
              exp_date: "11/25",
              last_used: "2025-05-19T10:22:00Z",
              default: false,
            },
          ],
        };
      } catch (error) {
        console.log(error);
      }
    },
  },
});
