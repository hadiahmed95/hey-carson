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
        <option v-for="plan in filterOptions.shopifyPlans" :key="plan" :value="plan">
          {{ plan }}
        </option>
      </select>
    </div>

    <!-- Loading State -->
    <LoadingCard v-if="isLoading" />

    <div>
      <LeadCard
          v-for="lead in transformedLeads"
          :key="lead.id"
          :lead="lead"
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
      v-if="showLoginAsModal && selectedLead"
      :user="selectedLead"
      :userId="selectedLead.id"
      :userName="selectedLead.display_name"
      :userEmail="selectedLead.email"
      :userPhoto="selectedLead.photo ? getS3URL(selectedLead.photo) : null"
      :userAvatarInfo="selectedLead.photo ? undefined : generateInitialsAvatar(selectedLead.display_name)"
      modalTitle="Login as Client"
      modalDescription="You are about to login as this client. You will be redirected to their dashboard where you can view and manage their account."
      buttonText="Login As Client"
      redirectPath="/client/dashboard"
      @close="closeLoginAsModal"
    />
  </main>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from "vue";
import { useAdminStore } from "@/store/admin.ts";
import { useLoaderStore } from "@/store/loader.ts";
import type { ILeadd } from "../../types.ts";
import { getS3URL, generateInitialsAvatar } from "@/utils/helpers.ts";
import LeadCard from "../../components/admin/cards/LeadCard.vue";
import Search from "../../assets/icons/search.svg";
import LoginAsModal from "../../components/admin/modals/LoginAsModal.vue";
import LoadingCard from "@/components/common/LoadingCard.vue";


const adminStore = useAdminStore();
const loader = useLoaderStore();

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

const isLoading = computed(() => loader.isLoadingState);

// Transform leads data to match ILeadd interface - FIXED avatar logic
const transformedLeads = computed((): ILeadd[] => {
  return leads.value.map(lead => {
    const name = `${lead.first_name} ${lead.last_name}`;
    const hasRealPhoto = lead.photo && 
                         lead.photo !== null && 
                         lead.photo !== '';
    
    return {
      id: lead.id,
      name,
      website: lead.url ? (lead.url.startsWith('http') ? lead.url : `https://${lead.url}`) : '#',
      email: lead.email,
      plan: lead.shopify_plan || 'Unknown',
      displayUrl: hasRealPhoto ? getS3URL(lead.photo) : null,
      avatarInfo: hasRealPhoto ? undefined : generateInitialsAvatar(name),
      directChatCount: lead.direct_messages_count || 0,
      quoteRequestCount: lead.quote_requests_count || 0,
      lifetimeSpendCount: lead.lifetime_spend ? `$${lead.lifetime_spend}` : '$0.00',
      joinedOn: lead.created_at ? new Date(lead.created_at).toLocaleDateString() : '',
      type: 'Lead',
      created_at: lead.created_at,
      project: {
        name: 'N/A'
      },
      client: {
        first_name: lead.first_name,
        last_name: lead.last_name,
        email: lead.email,
        url: lead.url,
        photo: lead.photo,
        shopify_plan: lead.shopify_plan
      }
    };
  });
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
  if (!resetData) {
    isLoadingMore.value = true;
  }

  try {
    const params: any = {
      page,
    };

    if (searchQuery.value) params.search = searchQuery.value;
    if (shopifyPlan.value) params.shopify_plan = shopifyPlan.value;

    const response = await adminStore.fetchLeads(params);
    
    if (resetData || page === 1) {
      leads.value = response.leads.data || [];
    } else {
      leads.value.push(...(response.leads.data || []));
    }
    
    totalLeads.value = response.leads_count || 0;
    currentPage.value = response.leads.meta.current_page || 1;
    lastPage.value = response.leads.meta.last_page || 1;
    
  } catch (error: any) {
    console.error('Error fetching leads:', error);
  } finally {
    isLoadingMore.value = false;
  }
};

const loadMore = () => {
  if (currentPage.value < lastPage.value && !isLoadingMore.value) {
    fetchLeads(currentPage.value + 1, false);
  }
};

let searchTimeout: ReturnType<typeof setTimeout> | undefined;
watch([searchQuery, shopifyPlan], () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetchLeads(1, true);
  }, 400);
});

onMounted(async () => {
  await fetchFilterOptions();
  await fetchLeads(1, true);
});
</script>