<script setup lang="ts">
import { ref, computed, onMounted, watch } from "vue";
import { useAdminStore } from "@/store/admin.ts";
import { useLoaderStore } from "@/store/loader.ts";
import { getS3URL, generateInitialsAvatar } from "@/utils/helpers.ts";
import type { IListing } from "@/types.ts";
import ListingCard from "../../components/admin/cards/ListingCard.vue";
import EmptyDataPlaceholder from "../../components/common/EmptyDataPlaceholder.vue";
import Search from "../../assets/icons/search.svg";
import LoginAsModal from "../../components/admin/modals/LoginAsModal.vue";
import LoadingCard from "@/components/common/LoadingCard.vue";

const adminStore = useAdminStore();
const loader = useLoaderStore();

const isLoading = computed(() => loader.isLoadingState);
const experts = computed(() => adminStore.experts);
const totalExperts = computed(() => adminStore.totalExperts);
const currentPage = computed(() => adminStore.currentPage);
const lastPage = computed(() => adminStore.lastPage);

// Computed cities based on selected country
const availableCities = computed(() => {
  if (!country.value || !filterOptions.value.citiesByCountry[country.value]) {
    return [];
  }
  return filterOptions.value.citiesByCountry[country.value];
});

const mapExpertToIListing = (expert: any): IListing => {
  return {
    displayUrl: expert.has_real_photo ? getS3URL(expert.photo) : null,
    avatarInfo: expert.has_real_photo ? undefined : generateInitialsAvatar(expert.display_name),
  } as IListing;
};

const filteredAndMappedExperts = computed((): IListing[] => {
  return experts.value.map(mapExpertToIListing);
});

const hasFilters = computed(() => {
  return status.value || typeOfAccount.value || plan.value || role.value || 
         country.value || city.value || language.value || servicesOffered.value || shopifyPlan.value;
});


const [isLoadingMore, status, typeOfAccount, plan, role, country, city, language, servicesOffered, shopifyPlan, searchQuery] = 
  [ref(false), ref(''), ref(''), ref(''), ref(''), ref(''), ref(''), ref(''), ref(''), ref(''), ref('')];

// Login As Modal
const showLoginAsModal = ref(false);
const selectedExpert = ref<IListing | null>(null);

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

// Get current filters
const getCurrentFilters = () => ({
    search: searchQuery.value,
    status: status.value,
    city: city.value,
    userType: plan.value,
    expertType: typeOfAccount.value,
    serviceCategory: servicesOffered.value,
    shopifyPlan: shopifyPlan.value,
    language: language.value,
    role: role.value,
    page: currentPage.value
});

const fetchExperts = async (page = 1, resetData = false) => {
  if (isLoading.value && !isLoadingMore.value) return;
  if (!resetData) {
    isLoadingMore.value = true;
  }

  try {
    const params: any = {
      page,
    };

    if (searchQuery.value) params.search = searchQuery.value;
    if (status.value) params.status = status.value;
    if (country.value) params.country = country.value;
    if (city.value) params.city = city.value;
    if (plan.value) params.userType = plan.value;
    if (typeOfAccount.value) params.expertType = typeOfAccount.value;
    if (servicesOffered.value) params.serviceCategory = servicesOffered.value;
    if (shopifyPlan.value) params.shopifyPlan = shopifyPlan.value;
    if (language.value) params.language = language.value;
    if (role.value) params.role = role.value;
    
    await adminStore.fetchExperts(params, !resetData && page > 1);
    
  } catch (error: any) {
    console.error('Error fetching experts:', error);
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
watch([searchQuery, status, role, language, typeOfAccount, plan, servicesOffered, shopifyPlan, country, city], () => {
  // Clear city when country changes
  if (country.value === '') {
    city.value = '';
  }
  
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    applyFilters();
  }, 500);
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
      <div class="flex items-center border border-grey rounded-sm bg-white py-1 px-3 w-80 max-w-md shadow-sm">
        <Search />
        <input
            type="text"
            placeholder="Search by name"
            class="w-full ml-3 text-h4 outline-none placeholder-tertiary"
            v-model="searchQuery"
        />
      </div>
    </div>

    <div class="mt-1 text-paragraph flex flex-wrap items-center gap-3">
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
        class="border rounded-sm px-3 py-1 text-h4 bg-gray-100 hover:bg-gray-200"
      >
        Clear All
      </button>
    </div>

    <!-- Loading State -->
    <LoadingCard v-if="isLoading" />

    <!-- Data State -->
    <div v-else-if="filteredAndMappedExperts.length > 0">
      <ListingCard
          v-for="listingItem in filteredAndMappedExperts"
          :key="listingItem.id"
          :listing="listingItem"
          :current-filters="getCurrentFilters()"
          @openLoginModal="openLoginAsModal"
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
      :user="selectedExpert"
      :userId="selectedExpert.id"
      :userName="selectedExpert.name"
      :userEmail="selectedExpert.email"
      :userPhoto="selectedExpert.displayUrl"
      :userAvatarInfo="selectedExpert.avatarInfo"
      modalTitle="Login as Expert"
      modalDescription="You are about to login as this expert. You will be redirected to their dashboard where you can view and manage their account."
      buttonText="Login As Expert"
      redirectPath="/expert/dashboard"
      @close="closeLoginAsModal"
    />
  </main>
</template>
