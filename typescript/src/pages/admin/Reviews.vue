<script setup lang="ts">
import { ref, onMounted, computed, watch } from "vue";
import ReviewCard from "../../components/admin/cards/ReviewCard.vue";
import Search from "../../assets/icons/search.svg";
import AdminService from "@/services/admin.service";
import { useAlertStore } from "@/store/alert.ts";
import type { IRevieww } from "@/types.ts";
import EmptyDataPlaceholder from "@/components/common/EmptyDataPlaceholder.vue";

const status = ref('')
const rating = ref('')
const likelyToRecommend = ref('')
const projectValue = ref('')
const reviewSource = ref('')
const searchQuery = ref('')

const reviews = ref<IRevieww[]>([]);
const filterOptions = ref({
  statuses: [],
  ratings: [],
  recommendations: [],
  projectValues: [],
  reviewSources: [],
});
const pagination = ref({
  current_page: 1,
  last_page: 1,
  total: 0,
});
const isLoading = ref(false);
const isLoadingMore = ref(false);
const alertStore = useAlertStore();

// Computed property to check if any filters are active
const hasFilters = computed(() => {
  return status.value !== '' || 
         rating.value !== '' || 
         likelyToRecommend.value !== '' || 
         projectValue.value !== '' || 
         reviewSource.value !== '' || 
         searchQuery.value !== '';
});

const fetchFilterOptions = async () => {
  try {
    const response = await AdminService.fetchReviewFilterOptions();
    filterOptions.value = response.data;
  } catch (error) {
    console.error("Error fetching filter options:", error);
  }
};

const fetchReviews = async (page = 1, resetData = false) => {
  try {
    if (!resetData) {
      isLoadingMore.value = true;
    } else {
      isLoading.value = true;
    }

    const params: Record<string, any> = { page };
    
    if (searchQuery.value) params.search = searchQuery.value;
    if (status.value) params.status = status.value;
    if (rating.value) params.rating = rating.value;
    if (likelyToRecommend.value) params.recommendation = likelyToRecommend.value;
    if (projectValue.value) params.project_value = projectValue.value;
    if (reviewSource.value) params.review_source = reviewSource.value;

    const response = await AdminService.fetchReviews(params);
    
    if (response.data.reviews) {
      const transformedReviews: IRevieww[] = response.data.reviews.map((review: any) => ({
        id: review.id || 0, // Ensure id is always a number
        reviewer: {
          id: review.reviewer?.id || 0,
          name: review.reviewer?.name || 'Unknown Client',
          photo: review.reviewer?.photo || '',
          storeTitle: review.reviewer?.storeUrl || 'No Store URL',
          storeUrl: review.reviewer?.storeUrl || '#',
          recurringClient: review.reviewer?.recurringClient || false,
          rating: review.rating || review.rate || 0,
          comment: review.comment || '',
          recommendation: review.recommendation || 'Not specified',
          isShopexpertUser: review.reviewer?.isShopexpertUser || false,
        },
        expert: {
          id: review.expert?.id || 0,
          name: review.expert?.name || 'Unknown Expert',
          photo: review.expert?.photo || '',
          company_type: review.expert?.company_type || 'agency',
          recurringExpert: review.expert?.recurringExpert || false,
          isShopexpertUser: review.expert?.isShopexpertUser || false,
          rank: review.expert?.rank || 'senior',
          storeUrl: review.expert?.storeUrl || '#',
          storeTitle: review.expert?.storeTitle || 'No Store',
        },
        postedAt: review.postedAt || 'Unknown Date',
        projectValue: review.projectValue || 'Not specified',
        reviewSource: review.reviewSource || 'Unknown',
        response: review.response || '',
        status: review.status || 'pending',
      }));

      if (resetData) {
        reviews.value = transformedReviews;
      } else {
        reviews.value.push(...transformedReviews);
      }

      pagination.value = response.data.pagination || {
        current_page: 1,
        last_page: 1,
        total: 0,
      };
    } else {
      // Fallback with mock data if no real data
      reviews.value = [{
        id: 1,
        reviewer: {
          id: 1,
          name: "John Doe",
          photo: "https://randomuser.me/api/portraits/men/79.jpg",
          storeTitle: "SuperSport",
          storeUrl: "https://www.trustpilot.com/",
          recurringClient: true,
          rating: 5,
          comment: "Working with Lautaro was a game-changer for our mobile store experience.",
          recommendation: "Very Likely",
          isShopexpertUser: true,
        },
        expert: {
          id: 101,
          name: 'Expert Name',
          photo: "https://randomuser.me/api/portraits/men/19.jpg",
          company_type: 'agency',
          recurringExpert: true,
          isShopexpertUser: true,
          rank: 'senior',
          storeUrl: "https://www.trustpilot.com/",
          storeTitle: "OasisCofee",
        },
        postedAt: "Dec 10, 2025",
        projectValue: "$1000-$2000",
        reviewSource: "Organic",
        response: "",
        status: 'pending',
      }];
    }
  } finally {
    isLoading.value = false;
    isLoadingMore.value = false;
  }
};

const loadMore = () => {
  if (pagination.value.current_page < pagination.value.last_page && !isLoadingMore.value) {
    fetchReviews(pagination.value.current_page + 1, false);
  }
};

const handleApproveReview = async (reviewId: number) => {
  try {
    await AdminService.updateReviewStatus(reviewId, 'approved');
    const reviewIndex = reviews.value.findIndex(r => r.id === reviewId);
    if (reviewIndex !== -1 && reviews.value[reviewIndex]) {
      reviews.value[reviewIndex].status = 'approved';
    }
  } catch (error) {
    alertStore.show("Failed to approve review", "error");
  }
};

const handleDeclineReview = async (reviewId: number) => {
  try {
    await AdminService.updateReviewStatus(reviewId, 'rejected');
    const reviewIndex = reviews.value.findIndex(r => r.id === reviewId);
    if (reviewIndex !== -1 && reviews.value[reviewIndex]) {
      reviews.value[reviewIndex].status = 'rejected';
    }
  } catch (error) {
    alertStore.show("Failed to decline review", "error");
  }
};

const handleHideReview = async (reviewId: number) => {
  try {
    await AdminService.updateReviewStatus(reviewId, 'hidden');
    const reviewIndex = reviews.value.findIndex(r => r.id === reviewId);
    if (reviewIndex !== -1 && reviews.value[reviewIndex]) {
      reviews.value[reviewIndex].status = 'hidden';
    }
  } catch (error) {
    alertStore.show("Failed to hide review", "error");
  }
};

// Function to reset all filters
const resetFilters = () => {
  status.value = '';
  rating.value = '';
  likelyToRecommend.value = '';
  projectValue.value = '';
  reviewSource.value = '';
  searchQuery.value = '';
  fetchReviews(1, true);
};

const applyFilters = () => {
  fetchReviews(1, true);
};

const getStatusValue = (displayValue: string) => {
  switch(displayValue) {
    case 'Pending Approval': return 'pending';
    case 'Published': return 'approved';
    case 'Rejected': return 'rejected';
    case 'Hidden': return 'hidden';
    default: return displayValue.toLowerCase();
  }
};

const getRatingValue = (displayValue: string) => {
  // Extract number from "5 Stars" -> "5"
  return displayValue.split(' ')[0];
};

const getRecommendationValue = (displayValue: string) => {
  // Convert "Very Likely" back to "very_likely" for API
  return displayValue.toLowerCase().replace(/ /g, '_');
};

const getProjectValueValue = (displayValue: string) => {
  // Handle special cases first
  if (displayValue === 'Under $100') {
    return 'under_100';
  }
  
  // Handle other special cases if they exist
  if (displayValue.startsWith('Over $')) {
    // Example: "Over $10,000" -> "over_10000"
    const amount = displayValue.replace('Over $', '').replace(/,/g, '');
    return `over_${amount}`;
  }
  
  // Convert standard range format: "$100-$1,000" back to "100_1000" for API
  if (displayValue.includes('$') && displayValue.includes('-')) {
    const parts = displayValue.replace(/\$/g, '').replace(/,/g, '').split('-');
    if (parts.length === 2) {
      return parts[0] + '_' + parts[1];
    }
  }
  
  // Return as-is for any other format
  return displayValue;
};

// Watch for all filters with debouncing (like in Listings.vue)
let searchTimeout: ReturnType<typeof setTimeout> | undefined;
watch([searchQuery, status, rating, likelyToRecommend, projectValue, reviewSource], () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    applyFilters();
  }, 500);
});

onMounted(() => {
  fetchFilterOptions(); // Fetch filter options first
  fetchReviews(1, true);
});
</script>

<template>
  <main class="flex-1 p-8 overflow-y-auto bg-secondary font-light space-y-6">
    <div class="flex flex-row justify-between">
      <div>
        <h1>
          Reviews <span class="text-gray-500">({{ pagination.total }})</span>
        </h1>
      </div>
      <div class="flex items-center border border-grey rounded-sm bg-white py-1 px-3 w-80 max-w-md shadow-sm">
        <Search />
        <input
            type="text"
            placeholder="Search reviews..."
            class="w-full ml-3 text-h4 outline-none placeholder-tertiary"
            v-model="searchQuery"
        />
      </div>
    </div>

    <div class="mt-1 text-paragraph flex flex-wrap items-center gap-3">
      <!-- Status Filter -->
      <select v-model="status" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Status: All</option>
        <option v-for="statusOption in filterOptions.statuses" :key="statusOption" :value="getStatusValue(statusOption)">
          {{ statusOption }}
        </option>
      </select>

      <!-- Rating Filter -->
      <select v-model="rating" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Rating: All</option>
        <option v-for="ratingOption in filterOptions.ratings" :key="ratingOption" :value="getRatingValue(ratingOption)">
          {{ ratingOption }}
        </option>
      </select>

      <!-- Recommendation Filter -->
      <select v-model="likelyToRecommend" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Likely To Recommend: All</option>
        <option v-for="recommendation in filterOptions.recommendations" :key="recommendation" :value="getRecommendationValue(recommendation)">
          {{ recommendation }}
        </option>
      </select>

      <!-- Project Value Filter -->
      <select v-model="projectValue" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Project Value: All</option>
        <option v-for="value in filterOptions.projectValues" :key="value" :value="getProjectValueValue(value)">
          {{ value }}
        </option>
      </select>

      <!-- Review Source Filter -->
      <select v-model="reviewSource" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Review Source: All</option>
        <option v-for="source in filterOptions.reviewSources" :key="source" :value="source">
          {{ source }}
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

    <!-- Reviews Data -->
    <div v-else-if="reviews.length > 0">
      <ReviewCard
          v-for="(review, index) in reviews"
          :key="index"
          :review="review"
          isAdmin
          @approve-review="handleApproveReview"
          @decline-review="handleDeclineReview"
          @hide-review="handleHideReview"
      />
    </div>

    <!-- Empty State -->
    <div v-else>
      <EmptyDataPlaceholder
        title="No reviews found"
        description="Try adjusting your filters or search criteria."
      />
    </div>

    <!-- Load More Button -->
    <div v-if="pagination.current_page < pagination.last_page" class="flex justify-center mt-8">
      <button 
        @click="loadMore"
        :disabled="isLoadingMore"
        class="px-6 py-2 bg-primary text-white rounded hover:bg-primary-dark disabled:opacity-50"
      >
        {{ isLoadingMore ? 'Loading...' : 'Load More' }}
      </button>
    </div>
  </main>
</template>