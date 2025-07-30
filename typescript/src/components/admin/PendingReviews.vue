<script setup lang="ts">

import {ref} from "vue";
import ReviewCard from "../common/cards/ReviewCard.vue";
import type {IReview} from "@/types.ts";

const yourReviews = ref<IReview[]>([
  {
    expert: {
      id: 101,
      name: 'Expert Name',
      photo: "https://randomuser.me/api/portraits/men/19.jpg",
      company_type: "Agency",            // dummy value
      recurringExpert: false,            // dummy, set as needed
      isShopexpertUser: true,
      rank: "Senior",                    // dummy rank
      storeUrl: "https://www.trustpilot.com/",
      storeTitle: "OasisCofee",
    },
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
    response: "",
    status: 'Pending Review',
  },
  {
    expert: {
      id: 102,
      name: 'Expert Name',
      photo: "https://randomuser.me/api/portraits/women/11.jpg",
      company_type: "Agency",
      recurringExpert: false,
      isShopexpertUser: true,
      rank: "Senior",
      storeUrl: "https://www.trustpilot.com/",
      storeTitle: "OasisCofee",
    },
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
    response: "",
    status: 'Pending Review',
  },
  {
    expert: {
      id: 103,
      name: 'Expert Name',
      photo: "https://randomuser.me/api/portraits/men/16.jpg",
      company_type: "Agency",
      recurringExpert: true,
      isShopexpertUser: true,
      rank: "Senior",
      storeUrl: "https://www.trustpilot.com/",
      storeTitle: "OasisCofee",
    },
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
    response: "",
    status: 'Pending Review',
  }
])


</script>

<template>
  <div >
    <ReviewCard
        v-for="(review, index) in yourReviews"
        :key="index"
        :review="review"
        isAdmin
    />
  </div>
</template>
