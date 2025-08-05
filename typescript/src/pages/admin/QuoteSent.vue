<script setup lang="ts">
import { ref, computed, onMounted, watch } from "vue";
import { useAdminStore } from "@/store/admin.ts";
import { useLoaderStore } from "@/store/loader.ts";
import QuoteSentCard from "../../components/admin/cards/QuoteSentCard.vue";
import Search from "../../assets/icons/search.svg";
import type { IQuotee } from '../../types.ts';
import { getS3URL } from "@/utils/helpers.ts";

const adminStore = useAdminStore();
const loader = useLoaderStore();
const isLoading = computed(() => loader.isLoadingState);

const quotes = ref<any[]>([]);
const totalQuotes = ref(0);
const currentPage = ref(1);
const lastPage = ref(1);
const isLoadingMore = ref(false);

const quoteStatus = ref('');
const searchQuery = ref('');

// Filter options
const filterOptions = ref<{
  statuses: any[];
}>({
  statuses: []
});

// Transform quotes data to match IQuote interface
const transformedQuotes = computed((): IQuotee[] => {
  return quotes.value.map(project => {
    // Get the latest offer from activeAssignment
    const latestOffer = project.active_assignment?.offers?.[0];
    
    const clientName = `${project.client?.first_name || ''} ${project.client?.last_name || ''}`.trim();
    const expertName = `${project.active_assignment?.expert?.first_name || ''} ${project.active_assignment?.expert?.last_name || ''}`.trim();
    
    const hasClientPhoto = project.client?.photo && 
                          project.client?.photo !== null && 
                          project.client?.photo !== '';
    
    const hasExpertPhoto = project.active_assignment?.expert?.photo && 
                          project.active_assignment?.expert?.photo !== null && 
                          project.active_assignment?.expert?.photo !== '';

    // Generate initials avatar for client if no photo
    const clientAvatarInfo = hasClientPhoto ? undefined : generateInitialsAvatar(clientName);
    const expertAvatarInfo = hasExpertPhoto ? undefined : generateInitialsAvatar(expertName);

    return {
      title: project.name || 'Untitled Project',
      link: project.url || '#', // Use projects.url directly
      hourlyRate: `$${latestOffer?.rate || 0}/hour`, // Use offers.rate directly
      estimatedTime: `${latestOffer?.hours || 0} hours`,
      deadline: latestOffer?.deadline ? new Date(latestOffer.deadline).toLocaleDateString() : 'No deadline',
      total: `$${((latestOffer?.rate || 0) * (latestOffer?.hours || 0)).toFixed(2)}`,
      status: latestOffer?.status || 'unknown', // Use exact database status
      sentDate: latestOffer?.created_at ? new Date(latestOffer.created_at).toLocaleDateString() : '',
      paidDate: latestOffer?.status === 'accepted' && latestOffer?.status_updated_at ? new Date(latestOffer.status_updated_at).toLocaleDateString() : undefined,
      rejectedDate: latestOffer?.status === 'declined' && latestOffer?.status_updated_at ? new Date(latestOffer.status_updated_at).toLocaleDateString() : undefined,
      client: {
        name: clientName || 'Unknown Client',
        email: project.client?.email || '',
        avatar: hasClientPhoto ? getS3URL(project.client.photo) : 'https://randomuser.me/api/portraits/women/43.jpg',
        plan: project.client?.shopify_plan || 'Unknown',
        avatarInfo: clientAvatarInfo, // Add avatar info for initials
      },
      expert: {
        name: expertName || 'Unknown Expert',
        email: project.active_assignment?.expert?.email || '',
        avatar: hasExpertPhoto ? getS3URL(project.active_assignment.expert.photo) : 'https://randomuser.me/api/portraits/men/44.jpg',
        avatarInfo: expertAvatarInfo, // Add avatar info for initials
      }
    };
  });
});

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

const hasFilters = computed(() => {
  return quoteStatus.value;
});

const resetFilters = () => {
  quoteStatus.value = '';
  searchQuery.value = '';
  currentPage.value = 1;
  fetchQuotesSent(1, true);
};

// Fetch filter options using admin store
const fetchFilterOptions = async () => {
  try {
    const response = await adminStore.fetchQuoteFilterOptions();
    filterOptions.value = response;
  } catch (error) {
    console.error('Error fetching quote filter options:', error);
  }
};

const fetchQuotesSent = async (page = 1, resetData = false) => {
  if (isLoading.value && !isLoadingMore.value) return;
  
  if (resetData) {
    currentPage.value = 1;
  } else {
    isLoadingMore.value = true;
  }

  try {
    const params: any = {
      page,
    };

    if (searchQuery.value) params.search = searchQuery.value;
    if (quoteStatus.value) params.status = quoteStatus.value;

    const response = await adminStore.fetchQuotesSent(params);
    
    if (resetData) {
      quotes.value = response.quotes.data;
    } else {
      quotes.value.push(...response.quotes.data);
    }
    
    totalQuotes.value = response.quotes.total;
    currentPage.value = response.quotes.current_page;
    lastPage.value = response.quotes.last_page;
    
  } catch (error) {
    console.error('Error fetching quotes:', error);
  } finally {
    isLoadingMore.value = false;
  }
};

const loadMore = () => {
  if (currentPage.value < lastPage.value && !isLoadingMore.value) {
    fetchQuotesSent(currentPage.value + 1, false);
  }
};

// Watch for search and filter changes
watch([searchQuery, quoteStatus], () => {
  fetchQuotesSent(1, true);
}, { deep: true });

onMounted(async () => {
  await fetchFilterOptions();
  await fetchQuotesSent(1, true);
});
</script>

<template>
  <main class="flex-1 p-8 overflow-y-auto bg-gray-50 font-light">
    <div class="flex justify-between items-center mb-4">
      <h1>
        Quotes Sent <span class="text-gray-500">({{ totalQuotes }})</span>
      </h1>
      <div class="flex items-center border border-grey rounded-sm bg-white py-1 px-3 w-[300px] max-w-md shadow-sm">
        <Search />
        <input
            type="text"
            placeholder="Search Quotes ..."
            class="w-full ml-3 text-h4 outline-none placeholder-tertiary"
            v-model="searchQuery"
        />
      </div>
    </div>

    <div class="flex items-center space-x-3 mb-8">
      <select v-model="quoteStatus" class="border border-grey w-[155px] rounded-sm px-3 py-1 text-h4 bg-white">
        <option value="">Quote Status: All</option>
        <option v-for="status in filterOptions.statuses" :key="status.value" :value="status.value">
          {{ status.label }}
        </option>
      </select>
    </div>

    <div class="space-y-4">
      <!-- Loading State -->
      <div v-if="isLoading && quotes.length === 0" class="space-y-4">
        <div v-for="i in 3" :key="i" class="bg-white rounded-md p-6 border border-grey animate-pulse">
          <div class="h-4 bg-gray-200 rounded w-3/4 mb-4"></div>
          <div class="h-3 bg-gray-200 rounded w-1/2 mb-2"></div>
          <div class="h-3 bg-gray-200 rounded w-1/4"></div>
        </div>
      </div>

      <!-- Quotes List -->
      <div v-else>
        <QuoteSentCard v-for="(quote, index) in transformedQuotes" :key="index" :quote="quote" />
      </div>

      <!-- Load More Button -->
      <div v-if="currentPage < lastPage" class="flex justify-center mt-8">
        <button 
          @click="loadMore"
          :disabled="isLoadingMore"
          class="px-6 py-2 bg-primary text-white rounded-sm hover:bg-primary-dark disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ isLoadingMore ? 'Loading...' : 'Load More' }}
        </button>
      </div>
      
      <!-- No Results Message -->
      <div v-if="!isLoading && transformedQuotes.length === 0" class="text-center py-12">
        <div class="bg-white border rounded-md shadow-sm p-8">
          <div class="flex flex-col items-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
              <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
              {{ searchQuery || hasFilters ? 'No quotes match your criteria' : 'No quotes found' }}
            </h3>
            <p class="text-gray-500 mb-4">
              {{ searchQuery || hasFilters ? 'Try adjusting your search or filter criteria.' : 'There are no quotes in the system yet.' }}
            </p>
            <button 
              v-if="hasFilters" 
              @click="resetFilters"
              class="px-4 py-2 bg-primary text-white rounded-sm hover:bg-primary-dark"
            >
              Clear Filters
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
