<script setup lang="ts">
import { ref, computed, onMounted, watch } from "vue";
import { useAdminStore } from "@/store/admin.ts";
import { useLoaderStore } from "@/store/loader.ts";
import QuoteSentCard from "../../components/admin/cards/QuoteSentCard.vue";
import EmptyDataPlaceholder from "../../components/common/EmptyDataPlaceholder.vue";
import Search from "../../assets/icons/search.svg";
import LoadingCard from "@/components/common/LoadingCard.vue";

const adminStore = useAdminStore();
const loader = useLoaderStore();

const isLoading = computed(() => loader.isLoadingState);
const quotes = computed(() => adminStore.quotes);
const totalQuotes = computed(() => adminStore.totalQuotes);
const currentPage = computed(() => adminStore.currentPage);
const lastPage = computed(() => adminStore.lastPage);

const isLoadingMore = ref(false);
const quoteStatus = ref('');
const searchQuery = ref('');
const isResetting = ref(false);

// Filter options state
const filterOptions = ref<{
  statuses: string[];
}>({
  statuses: [],
});

/**
 * Fetch filter options using admin store
 */
const fetchFilterOptions = async () => {
  try {
    const response = await adminStore.fetchQuoteFilterOptions();
    filterOptions.value = response;
  } catch (error) {
    console.error('Error fetching filter options:', error);
  }
};

/**
 * Fetch quotes with filtering and pagination
 */
const fetchQuotes = async (page = 1, resetData = false) => {
  if (isLoading.value && !isLoadingMore.value) return;
  if (!resetData) {
    isLoadingMore.value = true;
  }

  try {
    const params: any = {
      page,
    };

    if (searchQuery.value) params.search = searchQuery.value;
    if (quoteStatus.value) params.status = quoteStatus.value;
    
    await adminStore.fetchQuotes(params, !resetData && page > 1);
    
  } catch (error: any) {
    console.error('Error fetching quotes:', error);
  } finally {
    isLoadingMore.value = false;
  }
};

/**
 * Load more quotes for pagination
 */
const loadMore = () => {
  if (currentPage.value < lastPage.value && !isLoadingMore.value) {
    fetchQuotes(currentPage.value + 1, false);
  }
};

/**
 * Apply filters and fetch quotes
 */
const applyFilters = () => {
  fetchQuotes(1, true);
};

/**
 * Format status for display (capitalize first letter)
 */
const formatStatus = (status: string) => {
  if (!status) return status;
  return status.charAt(0).toUpperCase() + status.slice(1).toLowerCase();
};

let searchTimeout: ReturnType<typeof setTimeout> | undefined;
watch([searchQuery, quoteStatus], () => {
  if (isResetting.value) return;
  
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    applyFilters();
  }, 500);
});

onMounted(async () => {
  await fetchFilterOptions();
  fetchQuotes();
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
      <!-- Dynamic Status Filter -->
      <select 
        v-model="quoteStatus" 
        class="border border-grey w-[155px] rounded-sm px-3 py-1 text-h4 bg-white"
      >
        <option value="">Quote Status: All</option>
        <option 
          v-for="statusOption in filterOptions.statuses" 
          :key="statusOption" 
          :value="statusOption"
        >
          {{ formatStatus(statusOption) }}
        </option>
      </select>
    </div>

    <!-- Loading State -->
    <LoadingCard v-if="isLoading" />

    <!-- Data State -->
    <div v-else-if="quotes.length > 0">
      <div class="space-y-4">
        <QuoteSentCard 
          v-for="quote in quotes" 
          :key="quote.hours + quote.created_at" 
          :quote="quote" 
        />
      </div>
      
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

    <!-- Empty State -->
    <div v-else>
      <EmptyDataPlaceholder
        title="No quotes found"
        description="No quotes match your current filters."
      />
    </div>
  </main>
</template>