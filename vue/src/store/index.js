import { createStore } from 'vuex';

const store = createStore({
    state() {
        return {
            clientProjectSortStatus: 'all'
        };
    },
    mutations: {
        setClientProjectSortStatus(state, status) {
            state.clientProjectSortStatus = status;
        }
    },
    actions: {
        updateClientProjectSortStatus({ commit }, status) {
            commit('setClientProjectSortStatus', status);
        }
    },
    getters: {
        getClientProjectSortStatus: (state) => state.clientProjectSortStatus,
    }
});

export default store;