<script setup lang="ts">
import { ref, computed, onMounted, watch } from "vue";
import { useAdminStore } from "@/store/admin.ts";
import { useLoaderStore } from "@/store/loader.ts";
import ListingCard from "../../components/admin/cards/ListingCard.vue";
import EmptyDataPlaceholder from "../../components/common/EmptyDataPlaceholder.vue";
import Search from "../../assets/icons/search.svg";
import { getS3URL } from "@/utils/helpers.ts";
import LoginAsModal from "../../components/admin/modals/LoginAsModal.vue";

const adminStore = useAdminStore();
const loader = useLoaderStore();
const isLoading = computed(() => loader.isLoadingState);

const experts = ref<any[]>([]);
const totalExperts = ref(0);
const currentPage = ref(1);
const lastPage = ref(1);
const isLoadingMore = ref(false);

const status = ref('');
const typeOfAccount = ref('');
const plan = ref('');
const role = ref('');
const country = ref('');
const city = ref('');
const language = ref('');
const servicesOffered = ref('');
const shopifyPlan = ref('');
const searchQuery = ref('');

// Login As Modal
const showLoginAsModal = ref(false);
const selectedExpert = ref<any>(null);

const filterOptions = ref<{
  statuses: string[];
  roles: string[];
  countries: string[];
  citiesByCountry: Record<string, string[]>;
  languages: string[];
  userTypes: string[];
  expertTypes: string[];
  serviceCategories: string[];
  shopifyPlans: string[];
}>({
  statuses: [],
  roles: [],
  countries: [],
  citiesByCountry: {},
  languages: [],
  userTypes: [],
  expertTypes: [],
  serviceCategories: [],
  shopifyPlans: []
});

// Computed cities based on selected country
const availableCities = computed(() => {
  if (!country.value || !filterOptions.value.citiesByCountry[country.value]) {
    return [];
  }
  return filterOptions.value.citiesByCountry[country.value];
});

// Login As functionality
const openLoginAsModal = (expert: any) => {
  selectedExpert.value = expert;
  showLoginAsModal.value = true;
};

const closeLoginAsModal = () => {
  showLoginAsModal.value = false;
  selectedExpert.value = null;
};

// Fetch filter options using admin store
const fetchFilterOptions = async () => {
  try {
    const response = await adminStore.fetchExpertFilterOptions();
    filterOptions.value = response;
  } catch (error) {
    console.error('Error fetching filter options:', error);
  }
};

// Helper function to generate initials avatar
const generateInitialsAvatar = (name: string): { initials: string; bgColor: string } => {
  if (!name) return { initials: 'NA', bgColor: 'bg-gray-400' };
  
  const words = name.trim().split(' ');
  const initials = words.slice(0, 2).map(word => word.charAt(0).toUpperCase()).join('');
  
  const colors = [
    'bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-pink-500', 
    'bg-indigo-500', 'bg-yellow-500', 'bg-red-500', 'bg-teal-500',
    'bg-orange-500', 'bg-cyan-500', 'bg-lime-500', 'bg-amber-500'
  ];
  
  const charSum = initials.split('').reduce((sum, char) => sum + char.charCodeAt(0), 0);
  const colorIndex = charSum % colors.length;
  
  return {
    initials: initials || 'NA',
    bgColor: colors[colorIndex] || 'bg-gray-400'
  };
};

const filteredAndMappedExperts = computed(() => {
  return experts.value.map((expert: any) => {
    const name = expert.display_name || `${expert.first_name} ${expert.last_name}`;
    const hasRealPhoto = expert.photo && 
                         expert.photo !== null && 
                         expert.photo !== '';
    
    return {
      id: expert.id,
      name,
      displayUrl: hasRealPhoto ? getS3URL(expert.photo) : 'https://randomuser.me/api/portraits/men/32.jpg',
      avatarInfo: hasRealPhoto ? undefined : generateInitialsAvatar(name),
      type: expert.profile?.expert_type ? expert.profile.expert_type.charAt(0).toUpperCase() + expert.profile.expert_type.slice(1) : 'Freelancer',
      email: expert.email,
      storeTitle: 'Check Website',
      storeUrl: expert.url || 'https://www.trustpilot.com/',
      country: expert.profile?.country || 'N/A',
      jobTitle: expert.profile?.role || 'Expert',
      language: expert.profile?.eng_level || 'English',
      minimumProjectBudget: expert.hourly_rate_formatted || `$${expert.profile?.hourly_rate || 0}/hour`,
      status: expert.status_info?.status 
        ? expert.status_info.status.charAt(0).toUpperCase() + expert.status_info.status.slice(1) 
        : expert.profile?.status 
          ? expert.profile.status.charAt(0).toUpperCase() + expert.profile.status.slice(1)
          : 'Pending',
      statusUpdatedAt: expert.status_info?.updated_at || new Date().toLocaleDateString(),
      servicesOffered: expert.services_offered || [],
      expertData: expert,
      onLoginAs: () => openLoginAsModal(expert)
    };
  });
});

const hasFilters = computed(() => {
  return status.value || typeOfAccount.value || plan.value || role.value || 
         country.value || city.value || language.value || servicesOffered.value || shopifyPlan.value;
});

const fetchExperts = async (page = 1, resetData = false) => {
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

    if (searchQuery.value) {
      params.search = searchQuery.value;
    }
    if (status.value) params.status = status.value;

    const filters: any = {};
    if (role.value) filters.role = role.value;
    if (language.value) filters.eng_level = language.value;
    
    if (city.value) {
      params.city = city.value;
    }
    
    // Plan filter
    if (plan.value) params.usertype = plan.value;
    
    // Expert type filter
    if (typeOfAccount.value) params.expert_type = typeOfAccount.value;
    
    // Service category filter
    if (servicesOffered.value) params.service_category = servicesOffered.value;
    
    // Shopify plan filter
    if (shopifyPlan.value) params.shopify_plan = shopifyPlan.value;
    
    if (Object.keys(filters).length > 0) {
      params.filter = filters;
    }

    const response = await adminStore.fetchExperts(params);
    
    if (resetData) {
      experts.value = response.experts.data || [];
    } else {
      experts.value.push(...(response.experts.data || []));
    }
    
    totalExperts.value = response.experts_count || 0;
    currentPage.value = response.experts.current_page || 1;
    lastPage.value = response.experts.last_page || 1;
    
  } catch (error: any) {
    console.error('Error fetching experts:', error);
    if (resetData) {
      experts.value = [];
      totalExperts.value = 0;
    }
  } finally {
    isLoadingMore.value = false;
  }
};

const loadMore = () => {
  if (currentPage.value < lastPage.value && !isLoadingMore.value) {
    fetchExperts(currentPage.value + 1, false);
  }
};

const resetFilters = () => {
  status.value = '';
  typeOfAccount.value = '';
  plan.value = '';
  role.value = '';
  country.value = '';
  city.value = '';
  language.value = '';
  servicesOffered.value = '';
  shopifyPlan.value = '';
  searchQuery.value = '';
  fetchExperts(1, true);
};

const applyFilters = () => {
  fetchExperts(1, true);
};

let searchTimeout: ReturnType<typeof setTimeout> | undefined;
watch([searchQuery, status, role, language, city, typeOfAccount, plan, servicesOffered, shopifyPlan], () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    applyFilters();
  }, 500);
});

watch(country, (newCountry) => {
  if (!newCountry) {
    city.value = '';
  } else {
    city.value = '';
  }
});

onMounted(async () => {
  await fetchFilterOptions();
  fetchExperts(1, true);
});
</script>

<template>
  <main class="flex-1 p-8 overflow-y-auto bg-secondary font-light space-y-6">
    <div class="flex flex-row justify-between">
      <div>
        <h1>
          Listings <span class="text-gray-500">({{ totalExperts }})</span>
        </h1>
      </div>
      <div class="flex items-center border border-grey rounded-sm bg-white py-1 px-3 w-[300px] max-w-md shadow-sm">
        <Search />
        <input
            type="text"
            placeholder="Search by name or email..."
            class="w-full ml-3 text-h4 outline-none placeholder-tertiary"
            v-model="searchQuery"
        />
      </div>
    </div>

    <div class="mt-1 text-paragraph space-x-3">
      <!-- Status Filter -->
      <select v-model="status" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Status: All</option>
        <option 
          v-for="statusOption in filterOptions.statuses" 
          :key="statusOption" 
          :value="statusOption.toLowerCase()"
        >
          {{ statusOption }}
        </option>
      </select>

      <!-- Type of Account Filter -->
      <select v-model="typeOfAccount" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Type of Account: All</option>
        <option 
          v-for="expertType in filterOptions.expertTypes" 
          :key="expertType" 
          :value="expertType.toLowerCase()"
        >
          {{ expertType }}
        </option>
      </select>

      <!-- Plan Filter -->
      <select v-model="plan" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Plan: All</option>
        <option 
          v-for="userType in filterOptions.userTypes" 
          :key="userType" 
          :value="userType.toLowerCase()"
        >
          {{ userType }}
        </option>
      </select>

      <!-- Role Filter -->
      <select v-model="role" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Role: All</option>
        <option 
          v-for="roleOption in filterOptions.roles" 
          :key="roleOption" 
          :value="roleOption"
        >
          {{ roleOption.charAt(0).toUpperCase() + roleOption.slice(1).replace('_', ' ') }}
        </option>
      </select>

      <!-- Country Filter -->
      <select v-model="country" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Country: All</option>
        <option 
          v-for="countryOption in filterOptions.countries" 
          :key="countryOption" 
          :value="countryOption"
        >
          {{ countryOption }}
        </option>
      </select>

      <!-- City Filter -->
      <select 
        v-model="city" 
        :disabled="!country || !availableCities || availableCities.length === 0"
        class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        <option value="">City: All</option>
        <option 
          v-for="cityOption in availableCities" 
          :key="cityOption" 
          :value="`${cityOption}, ${country}`"
        >
          {{ cityOption }}
        </option>
      </select>

      <!-- Language Filter -->
      <select v-model="language" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Language: All</option>
        <option 
          v-for="languageOption in filterOptions.languages" 
          :key="languageOption" 
          :value="languageOption"
        >
          {{ languageOption.charAt(0).toUpperCase() + languageOption.slice(1) }}
        </option>
      </select>

      <!-- Services Offered Filter -->
      <select v-model="servicesOffered" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Services Offered: All</option>
        <option 
          v-for="serviceOption in filterOptions.serviceCategories" 
          :key="serviceOption" 
          :value="serviceOption"
        >
          {{ serviceOption }}
        </option>
      </select>

      <!-- Shopify Plan Filter -->
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

      <!-- Clear filters button -->
      <button 
        v-if="hasFilters" 
        @click="resetFilters"
        class="border rounded-sm px-3 py-2 text-h4 bg-gray-100 hover:bg-gray-200"
      >
        Clear All
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="space-y-4">
      <div v-for="i in 5" :key="i" class="bg-white border rounded-md shadow-sm p-6">
        <div class="flex space-x-4">
          <div class="w-16 h-16 bg-gray-300 rounded-full"></div>
          <div class="flex-1 space-y-2">
            <div class="h-4 bg-gray-300 rounded w-1/4"></div>
            <div class="h-3 bg-gray-300 rounded w-1/2"></div>
            <div class="h-3 bg-gray-300 rounded w-1/3"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Data State -->
    <div v-else-if="filteredAndMappedExperts.length > 0">
      <ListingCard
          v-for="listingItem in filteredAndMappedExperts"
          :key="listingItem.id"
          :listing="listingItem"
      />
      
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

    <!-- Empty State using EmptyDataPlaceholder component -->
    <div v-else>
      <EmptyDataPlaceholder
        title="Looks like you don't have any active listings yet."
        description="Your listings show up here soon."
      />
    </div>

    <!-- Login As Modal -->
    <LoginAsModal 
      v-if="showLoginAsModal && selectedExpert"
      :expert="selectedExpert"
      @close="closeLoginAsModal"
    />
  </main>
</template>
