// stores/Auth.ts
import {defineStore} from 'pinia'
import ExpertService from "@/services/expert.service.ts";

export const useCommonStore = defineStore('common', {

    actions: {
        async getExperts(params: Record<string, any>) {
            const res = await ExpertService.getExperts(params);
            return res.data.experts;
        },
    }
})
