<script setup lang="ts">
import { ref, computed, onMounted, watch } from "vue";
import { useAdminStore } from "@/store/admin.ts";
import { useLoaderStore } from "@/store/loader.ts";
import ListingCard from "../../components/admin/cards/ListingCard.vue";
import Search from "../../assets/icons/search.svg";
import { getS3URL } from "@/utils/helpers.ts";

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
const searchQuery = ref('');

// Helper function to generate initials avatar
const generateInitialsAvatar = (name: string): { initials: string; bgColor: string } => {
  if (!name) return { initials: 'NA', bgColor: 'bg-gray-400' };
  
  const words = name.trim().split(' ');
  const initials = words.slice(0, 2).map(word => word.charAt(0).toUpperCase()).join('');
  
  // Array of background colors for different initials
  const colors = [
    'bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-pink-500', 
    'bg-indigo-500', 'bg-yellow-500', 'bg-red-500', 'bg-teal-500',
    'bg-orange-500', 'bg-cyan-500', 'bg-lime-500', 'bg-amber-500'
  ];
  
  // Generate consistent color based on initials
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
    // Check if user has a real photo (not null, empty)
    const hasRealPhoto = expert.photo && 
                         expert.photo !== null && 
                         expert.photo !== '';
    
    return {
      id: expert.id,
      name,
      displayUrl: hasRealPhoto ? getS3URL(expert.photo) : 'https://randomuser.me/api/portraits/men/32.jpg',
      avatarInfo: hasRealPhoto ? undefined : generateInitialsAvatar(name),
      type: expert.account_type === 'agency' ? 'Agency' : 'Freelancer',
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
      servicesOffered: expert.services_offered || [
        'Store Setup & Management',
        'Development and Troubleshooting', 
        'Training & Consultation'
      ],
    };
  });
});

const hasFilters = computed(() => {
  return status.value || typeOfAccount.value || plan.value || role.value || 
         country.value || city.value || language.value || servicesOffered.value;
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

    if (searchQuery.value) params.search = searchQuery.value;
    if (status.value) params.status = status.value.toLowerCase();

    const filters: { role?: string; eng_level?: string } = {};
    if (role.value) filters.role = role.value;
    if (language.value) filters.eng_level = language.value;
    
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
  searchQuery.value = '';
  fetchExperts(1, true);
};

const applyFilters = () => {
  fetchExperts(1, true);
};

let searchTimeout: ReturnType<typeof setTimeout> | undefined;
watch([searchQuery, status, role, language], () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    applyFilters();
  }, 500);
});

onMounted(() => {
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
            placeholder="Search Experts ..."
            class="w-full ml-3 text-h4 outline-none placeholder-tertiary"
            v-model="searchQuery"
        />
      </div>
    </div>

    <div class="mt-1 text-paragraph space-x-3">
      <select v-model="status" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Status: All</option>
        <option value="pending">Pending</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
      </select>

      <select v-model="typeOfAccount" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Type of Account: All</option>
        <option value="freelance">Freelance</option>
        <option value="agency">Agency</option>
      </select>

      <select v-model="plan" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Plan: All</option>
        <option value="free">Free</option>
        <option value="paid">Paid</option>
      </select>

      <select v-model="role" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Role: All</option>
        <option value="developer">Developer</option>
        <option value="designer">Designer</option>
        <option value="consultant">Consultant</option>
        <option value="marketing_specialist">Marketing Specialist</option>
      </select>

      <select v-model="country" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Country: All</option>
        <option value="united_states">United States</option>
        <option value="canada">Canada</option>
      </select>

      <select v-model="city" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">City: All</option>
        <option value="new_york">New York</option>
        <option value="toronto">Toronto</option>
      </select>

      <select v-model="language" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Language: All</option>
        <option value="basic">Basic</option>
        <option value="intermediate">Intermediate</option>
        <option value="advanced">Advanced</option>
        <option value="native">Native</option>
      </select>

      <select v-model="servicesOffered" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Services Offered: All</option>
        <option value="store_management">Store Management</option>
        <option value="store_marketing">Store Marketing</option>
      </select>

      <button 
        v-if="hasFilters" 
        @click="resetFilters"
        class="border rounded-sm px-3 py-2 text-h4 bg-gray-100 hover:bg-gray-200"
      >
        Clear All
      </button>
    </div>

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

    <div v-else class="bg-white border rounded-md shadow-sm p-12 text-center">
      <div class="space-y-4">
        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center">
          <Search class="w-8 h-8 text-gray-400" />
        </div>
        <h3 class="text-lg font-medium text-gray-900">No experts found</h3>
        <p class="text-gray-500">
          We couldn't find any experts that match your search criteria.
        </p>
        <button 
          @click="resetFilters"
          class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark"
        >
          Clear Filters
        </button>
      </div>
    </div>
  </main>
</template>
