<script setup lang="ts">
import { ref, computed, onMounted, watch } from "vue";
import { useAdminStore } from "@/store/admin.ts";
import { useLoaderStore } from "@/store/loader.ts";
import LeadCard from "../../components/admin/cards/LeadCard.vue";
import Search from "../../assets/icons/search.svg";
import LoginAsLeadModal from "../../components/admin/modals/LoginAsLeadModal.vue";
import type { ILeadd } from "../../types.ts";

const adminStore = useAdminStore();
const loader = useLoaderStore();
const isLoading = computed(() => loader.isLoadingState);

const leads = ref<any[]>([]);
const totalLeads = ref(0);
const currentPage = ref(1);
const lastPage = ref(1);
const isLoadingMore = ref(false);

const shopifyPlan = ref('');
const searchQuery = ref('');

// Login As Modal
const showLoginAsModal = ref(false);
const selectedLead = ref<any>(null);

// Filter options
const filterOptions = ref<{
  shopifyPlans: string[];
}>({
  shopifyPlans: []
});

// Transform leads data to match ILeadd interface
const transformedLeads = computed((): ILeadd[] => {
  return leads.value.map(lead => {
    return {
      id: lead.id,
      name: `${lead.first_name} ${lead.last_name}`,
      website: lead.url ? (lead.url.startsWith('http') ? lead.url : `https://${lead.url}`) : '#',
      email: lead.email,
      plan: lead.shopify_plan || 'Unknown',
      displayUrl: lead.photo ? `https://shopexperts.s3.amazonaws.com/${lead.photo}` : 'https://randomuser.me/api/portraits/men/32.jpg',
      directChatCount: lead.projects_count || 0,
      quoteRequestCount: lead.quote_requests_count || 0,
      lifetimeSpendCount: lead.lifetime_spend ? `$${lead.lifetime_spend}` : '$0.00',
      joinedOn: lead.created_at ? new Date(lead.created_at).toLocaleDateString() : '',
      type: 'Lead', // Default type
      created_at: lead.created_at,
      project: {
        name: 'N/A' // Default project name for clients
      },
      client: {
        first_name: lead.first_name,
        last_name: lead.last_name,
        email: lead.email,
        url: lead.url,
        photo: lead.photo,
        shopify_plan: lead.shopify_plan
      },
      onLoginAs: () => openLoginAsModal(lead)
    };
  });
});

const hasFilters = computed(() => {
  return shopifyPlan.value;
});

// Login As functionality
const openLoginAsModal = (lead: any) => {
  selectedLead.value = lead;
  showLoginAsModal.value = true;
};

const closeLoginAsModal = () => {
  showLoginAsModal.value = false;
  selectedLead.value = null;
};

// Fetch filter options using admin store
const fetchFilterOptions = async () => {
  try {
    const response = await adminStore.fetchLeadFilterOptions();
    filterOptions.value = response;
  } catch (error) {
    console.error('Error fetching lead filter options:', error);
  }
};

const fetchLeads = async (page = 1, resetData = false) => {
  if (isLoading.value && !isLoadingMore.value) return;
  
  if (resetData) {
    currentPage.value = 1;
  } else {
    isLoadingMore.value = true;
  }

  try {
    const params: any = {
      page,
      version: 'v2',
    };

    if (searchQuery.value) params.search = searchQuery.value;
    if (shopifyPlan.value) params.shopify_plan = shopifyPlan.value;

    const response = await adminStore.fetchLeads(params);
    
    if (resetData) {
      leads.value = response.clients.data || [];
    } else {
      leads.value.push(...(response.clients.data || []));
    }
    
    totalLeads.value = response.clients_count || 0;
    currentPage.value = response.clients.current_page || 1;
    lastPage.value = response.clients.last_page || 1;
    
  } catch (error: any) {
    console.error('Error fetching leads:', error);
    if (resetData) {
      leads.value = [];
      totalLeads.value = 0;
    }
  } finally {
    isLoadingMore.value = false;
  }
};

const loadMore = () => {
  if (currentPage.value < lastPage.value && !isLoadingMore.value) {
    fetchLeads(currentPage.value + 1, false);
  }
};

const resetFilters = () => {
  shopifyPlan.value = '';
  searchQuery.value = '';
  fetchLeads(1, true);
};

const applyFilters = () => {
  fetchLeads(1, true);
};

let searchTimeout: ReturnType<typeof setTimeout> | undefined;
watch([searchQuery, shopifyPlan], () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    applyFilters();
  }, 500);
});

onMounted(async () => {
  await fetchFilterOptions();
  fetchLeads(1, true);
});
</script>

<template>
  <main class="flex-1 p-8 overflow-y-auto bg-secondary font-light">
    <div class="flex flex-row justify-between mb-4">
      <div>
        <h1>
          Leads <span class="text-gray-500">({{ totalLeads }})</span>
        </h1>
      </div>

      <div class="flex items-center border border-grey rounded-sm bg-white py-1 px-3 w-[300px] max-w-md shadow-sm">
        <Search />
        <input
            type="text"
            placeholder="Search Leads ..."
            class="w-full ml-3 text-h4 outline-none placeholder-tertiary"
            v-model="searchQuery"
        />
      </div>
    </div>

    <div class="mt-1 text-paragraph space-x-3 mb-8">
      <select v-model="shopifyPlan" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Shopify Plan: All</option>
        <option 
          v-for="planOption in filterOptions.shopifyPlans" 
          :key="planOption" 
          :value="planOption"
        >
          {{ planOption }}
        </option>
      </select>

      <!-- Reset Filters Button (only show when filters are applied) -->
      <button 
        v-if="hasFilters" 
        @click="resetFilters"
        class="ml-3 px-4 py-2 text-h4 text-primary hover:bg-gray-100 border rounded-sm"
      >
        Reset Filters
      </button>
    </div>

    <div>
      <LeadCard
          v-for="lead in transformedLeads"
          :key="lead.id"
          :lead="lead"
      />
      
      <!-- Load More Button -->
      <div v-if="currentPage < lastPage" class="flex justify-center mt-6">
        <button 
          @click="loadMore"
          :disabled="isLoadingMore"
          class="px-6 py-2 bg-primary text-white rounded-sm hover:bg-primary-dark disabled:opacity-50"
        >
          {{ isLoadingMore ? 'Loading...' : 'Load More' }}
        </button>
      </div>
      
      <!-- No Results Message -->
      <div v-if="!isLoading && transformedLeads.length === 0" class="text-center py-8">
        <p class="text-gray-500">{{ searchQuery || hasFilters ? 'No leads match your criteria.' : 'No leads found.' }}</p>
      </div>
    </div>

    <!-- Login As Modal -->
    <LoginAsLeadModal
      v-if="showLoginAsModal && selectedLead"
      :user="selectedLead"
      @close="closeLoginAsModal"
    />
  </main>
</template>
