<script setup lang="ts">

import { ref } from "vue";
import ReviewCard from "../../components/common/cards/ReviewCard.vue";
import Search from "../../assets/icons/search.svg";

const status = ref('')
const rating = ref('')
const likelyToRecommend = ref('')
const projectValue = ref('')
const reviewSource = ref('')
const searchQuery = ref('')

const reviews = ref([
  {
    reviewer: {
      id: 1,
      name: "Michael O.",
      photo: "https://randomuser.me/api/portraits/men/79.jpg",
      storeTitle: "SuperSport",
      storeUrl: "https://www.trustpilot.com/",
      recurringClient: true,
      rating: 5,
      comment: "Working with Lautaro was a game-changer for our mobile store experience. The redesigned product grid is stunning and works flawlessly. Our mobile conversions have increased by 35%, thanks to his expertise!",
      recommendation: "Very Likely",
      isShopexpertUser: true,
    },
    postedAt: "Dec 10, 2025",
    projectValue: "$1000-$2000",
    reviewSource: "Organic",
    response: '',
    status: 'Pending Review',
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
    }
  },
  {
    reviewer: {
      id: 2,
      name: "Olivia R.",
      photo: "https://randomuser.me/api/portraits/women/71.jpg",
      storeTitle: "Archery",
      storeUrl: "https://www.trustpilot.com/",
      recurringClient: false,
      rating: 5,
      comment: "Lautaro built an advanced filtering system that completely transformed how our customers shop. It’s fast, intuitive, and beautifully integrated into our site. His attention to detail is unmatched.",
      recommendation: "Very Likely",
      isShopexpertUser: false,
    },
    postedAt: "Dec 10, 2025",
    projectValue: "$1000-$2000",
    reviewSource: "Invited",
    response: '',
    status: 'Approved',
    expert: {
      id: 102,
      name: 'Expert Name',
      photo: "https://randomuser.me/api/portraits/women/11.jpg",
      company_type: 'freelance',
      recurringExpert: false,
      isShopexpertUser: false,
      rank: 'mid-level',
      storeUrl: "https://www.trustpilot.com/",
      storeTitle: "OasisCofee",
    }
  },
  {
    reviewer: {
      id: 3,
      name: "Jack R.",
      photo: "https://randomuser.me/api/portraits/men/76.jpg",
      storeTitle: "United by Blue",
      storeUrl: "https://www.trustpilot.com/",
      recurringClient: false,
      rating: 5,
      comment: "We wanted a feature that let customers preview products in real-time, and Lautaro delivered beyond our expectations. The extension is seamless and our customers love it. He’s a Shopify wizard!",
      recommendation: "Very Likely",
      isShopexpertUser: true,
    },
    postedAt: "Dec 10, 2025",
    projectValue: "$1000-$2000",
    reviewSource: "Invited",
    response: '',
    status: 'Approved',
    expert: {
      id: 103,
      name: 'Expert Name',
      photo: "https://randomuser.me/api/portraits/men/16.jpg",
      company_type: 'agency',
      recurringExpert: true,
      isShopexpertUser: true,
      rank: 'lead',
      storeUrl: "https://www.trustpilot.com/",
      storeTitle: "OasisCofee",
    }
  },
]);

</script>

<template>
  <main class="flex-1 p-8 overflow-y-auto bg-secondary font-light space-y-8">
    <div class="flex flex-row justify-between">
      <h1>
        Reviews <span class="text-gray-500">({{ reviews.length }})</span>
      </h1>

      <div class="flex items-center border border-grey rounded-sm bg-white py-1 px-3 w-[300px] max-w-md shadow-sm">
        <Search />
        <input
            type="text"
            placeholder="Search Reviews ..."
            class="w-full ml-3 text-h4 outline-none placeholder-tertiary"
            v-model="searchQuery"
        />
      </div>
    </div>

    <p class="space-x-3">
      <select v-model="status" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Status: All</option>
        <option value="In Progress">In Progress</option>
        <option value="Completed">Completed</option>
        <option value="Closed">Closed</option>
      </select>

      <select v-model="rating" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Rating: All</option>
        <option value="freelance">Freelance</option>
        <option value="agency">Agency</option>
      </select>

      <select v-model="likelyToRecommend" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Likely To Recommend: All</option>
        <option value="free">free</option>
        <option value="paid">Paid</option>
      </select>

      <select v-model="projectValue" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Project Value: All</option>
        <option value="developer">Developer</option>
        <option value="designer">Designer</option>
      </select>

      <select v-model="reviewSource" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Review Source: All</option>
        <option value="united_states">United Sates</option>
        <option value="canada">Canada</option>
      </select>
    </p>

    <div>
      <ReviewCard
          v-for="(review, index) in reviews"
          :key="index"
          :review="review"
          isAdmin
      />
    </div>
    
  </main>
</template>
