<script setup lang="ts">
import { ref, onMounted } from "vue";
import ReviewCard from "../../components/common/cards/ReviewCard.vue";
import Search from "../../assets/icons/search.svg";
import AdminService from "@/services/admin.service";
import { useAlertStore } from "@/store/alert.ts";
import type { IRevieww } from "@/types.ts";

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
      const transformedReviews: IRevieww[] = response.data.reviews.map(review => ({
        id: review.id,
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
          storeTitle: review.expert?.storeUrl || 'No Store URL',
        },
        postedAt: review.postedAt || 'Unknown Date',
        projectValue: review.projectValue || 'Not specified',
        reviewSource: review.reviewSource || 'Not specified',
        response: review.response || '',
        status: review.status || 'pending',
      }));

      if (resetData || page === 1) {
        reviews.value = transformedReviews;
      } else {
        reviews.value = [...reviews.value, ...transformedReviews];
      }

      if (response.data.pagination) {
        pagination.value = response.data.pagination;
      }
    }
  } catch (error) {
    console.error("Error fetching reviews:", error);
    if (resetData || page === 1) {
      reviews.value = [{
        id: 1,
        reviewer: {
          id: 1,
          name: "Michael O.",
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
    if (reviewIndex !== -1) {
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
    if (reviewIndex !== -1) {
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
    if (reviewIndex !== -1) {
      reviews.value[reviewIndex].status = 'hidden';
    }
  } catch (error) {
    alertStore.show("Failed to hide review", "error");
  }
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
  // Convert "$100-$1,000" back to "100_1000" for API
  if (displayValue.includes('$') && displayValue.includes('-')) {
    const parts = displayValue.replace(/\$/g, '').replace(/,/g, '').split('-');
    if (parts.length === 2) {
      return parts[0] + '_' + parts[1];
    }
  }
  return displayValue;
};

onMounted(() => {
  fetchFilterOptions(); // Fetch filter options first
  fetchReviews(1, true);
});
</script>

<template>
  <main class="flex-1 p-8 overflow-y-auto bg-secondary font-light space-y-8">
    <div class="flex flex-row justify-between">
      <h1>
        Reviews <span class="text-gray-500">({{ pagination.total || reviews.length }})</span>
      </h1>

      <div class="flex items-center border border-grey rounded-sm bg-white py-1 px-3 w-[300px] max-w-md shadow-sm">
        <Search />
        <input
            type="text"
            placeholder="Search Reviews ..."
            class="w-full ml-3 text-h4 outline-none placeholder-tertiary"
            v-model="searchQuery"
            @input="applyFilters"
        />
      </div>
    </div>

    <div class="text-paragraph space-x-3">
      <select v-model="status" @change="applyFilters" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Status: All</option>
        <option v-for="statusOption in filterOptions.statuses" :key="statusOption" :value="getStatusValue(statusOption)">
          {{ statusOption }}
        </option>
      </select>

      <select v-model="rating" @change="applyFilters" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Rating: All</option>
        <option v-for="ratingOption in filterOptions.ratings" :key="ratingOption" :value="getRatingValue(ratingOption)">
          {{ ratingOption }}
        </option>
      </select>

      <select v-model="likelyToRecommend" @change="applyFilters" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Likely To Recommend: All</option>
        <option v-for="recommendation in filterOptions.recommendations" :key="recommendation" :value="getRecommendationValue(recommendation)">
          {{ recommendation }}
        </option>
      </select>

      <select v-model="projectValue" @change="applyFilters" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Project Value: All</option>
        <option v-for="value in filterOptions.projectValues" :key="value" :value="getProjectValueValue(value)">
          {{ value }}
        </option>
      </select>

      <select v-model="reviewSource" @change="applyFilters" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Review Source: All</option>
        <option v-for="source in filterOptions.reviewSources" :key="source" :value="source">
          {{ source }}
        </option>
      </select>
    </div>

    <div>
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