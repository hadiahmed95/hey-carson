<template>
  <main class="flex-1 p-8 overflow-y-auto bg-secondary font-light">
    <div class="flex flex-row justify-between mb-4">
      <div>
        <h1>
          Clients <span class="text-gray-500">({{ totalClients }})</span>
        </h1>
      </div>

      <div class="flex items-center border border-grey rounded-sm bg-white py-1 px-3 w-[300px] max-w-md shadow-sm">
        <Search />
        <input
            type="text"
            placeholder="Search Clients ..."
            class="w-full ml-3 text-h4 outline-none placeholder-tertiary"
            v-model="searchQuery"
        />
      </div>
    </div>

    <div class="mt-1 text-paragraph space-x-3 mb-8">
      <select v-model="shopifyPlan" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Shopify Plan: All</option>
        <option v-for="plan in filterOptions.shopifyPlans" :key="plan" :value="plan">
          {{ plan }}
        </option>
      </select>
    </div>

    <!-- Loading State -->
    <LoadingCard v-if="isLoading" />

    <div>
      <ClientCard
          v-for="client in clients"
          :key="client.id"
          :client="client"
          @openLoginModal="openLoginAsModal"
      />
      
      <!-- Load More Button -->
      <div v-if="currentPage < lastPage" class="flex justify-center py-4">
        <button 
          @click="loadMore"
          :disabled="isLoadingMore"
          class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary-dark disabled:opacity-50"
        >
          <span v-if="isLoadingMore">Loading...</span>
          <span v-else>Load More</span>
        </button>
      </div>
    </div>

    <!-- Login As Modal -->
    <LoginAsModal
      v-if="showLoginAsModal && selectedClient"
      :user="selectedClient"
      :userId="selectedClient.id"
      :userName="selectedClient.name"
      :userEmail="selectedClient.email"
      :userPhoto="selectedClient.photo ? getS3URL(selectedClient.photo) : null"
      :userAvatarInfo="selectedClient.photo ? undefined : generateInitialsAvatar(selectedClient.name)"
      modalTitle="Login as Client"
      :modalDescription="`You are about to login as ${selectedClient.name}. You will be redirected to their dashboard where you can view and manage their account.`"
      :buttonText="`Login As ${selectedClient.name}`"
      redirectPath="/client/dashboard"
      @close="closeLoginAsModal"
    />
  </main>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from "vue";
import { useAdminStore } from "@/store/admin.ts";
import { useLoaderStore } from "@/store/loader.ts";
import type { IClient } from "../../types.ts";
import { getS3URL, generateInitialsAvatar } from "@/utils/helpers.ts";
import ClientCard from "../../components/admin/cards/ClientCard.vue";
import Search from "../../assets/icons/search.svg";
import LoginAsModal from "../../components/admin/modals/LoginAsModal.vue";
import LoadingCard from "@/components/common/LoadingCard.vue";


const adminStore = useAdminStore();
const loader = useLoaderStore();

const totalClients = ref(0);
const currentPage = ref(1);
const lastPage = ref(1);
const isLoadingMore = ref(false);
const shopifyPlan = ref('');
const searchQuery = ref('');

// Login As Modal
const showLoginAsModal = ref(false);
const selectedClient = ref<IClient | null>(null);

// Filter options
const filterOptions = ref<{
  shopifyPlans: string[];
}>({
  shopifyPlans: []
});

const isLoading = computed(() => loader.isLoadingState);
const clients = computed(() => adminStore.clients);

// Login As functionality
const openLoginAsModal = (client: any) => {
  selectedClient.value = client;
  showLoginAsModal.value = true;
};

const closeLoginAsModal = () => {
  showLoginAsModal.value = false;
  selectedClient.value = null;
};

// Fetch filter options using admin store
const fetchFilterOptions = async () => {
  try {
    const response = await adminStore.fetchClientFilterOptions();
    filterOptions.value = response;
  } catch (error) {
    console.error('Error fetching client filter options:', error);
  }
};

const fetchClients = async (page = 1, resetData = false) => {
  if (isLoading.value && !isLoadingMore.value) return;
  if (!resetData) {
    isLoadingMore.value = true;
  }

  try {
    const params: any = {
      page,
    };

    if (searchQuery.value) params.search = searchQuery.value;
    if (shopifyPlan.value) params.shopify_plan = shopifyPlan.value;

    const response = await adminStore.fetchClients(params);
    
    if (resetData || page === 1) {
      clients.value = response.clients.data || [];
    } else {
      clients.value.push(...(response.clients.data || []));
    }
    
    totalClients.value = response.clients_count || 0;
    currentPage.value = response.clients.meta.current_page || 1;
    lastPage.value = response.clients.meta.last_page || 1;
    
  } catch (error: any) {
    console.error('Error fetching clients:', error);
  } finally {
    isLoadingMore.value = false;
  }
};

const loadMore = () => {
  if (currentPage.value < lastPage.value && !isLoadingMore.value) {
    fetchClients(currentPage.value + 1, false);
  }
};

let searchTimeout: ReturnType<typeof setTimeout> | undefined;
watch([searchQuery, shopifyPlan], () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetchClients(1, true);
  }, 400);
});

onMounted(async () => {
  await fetchFilterOptions();
  await fetchClients(1, true);
});
</script>