import { defineStore } from 'pinia';

export const useLoaderStore = defineStore('loader', {
    state: () => ({
        isLoading: false
    }),

    getters: {
        isLoadingState: (state) => state.isLoading,
    }
});