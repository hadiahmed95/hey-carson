<template>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div>
      <h3 class="font-semibold pb-2">Service Categories</h3>
      <h4 class="mb-5">
        Based on your plan, you can select 1 category and up to 3
        <br />subcategories within the selected service category.
      </h4>
      <h4 class="mb-4">
        Upgrade your plan if you want to select up to 3 categories
        <br />and 6 subcategories.
      </h4>
      <button class="text-h5 font-medium px-2 py-1 bg-primary text-white rounded-sm mb-4">
        Upgrade Plan
      </button>
      <h5 class="font-light p-4 bg-accent rounded-sm mb-4">
        A paid profile gives you many more options to stand out in the directory and attract more leads. It also makes you part of the <span class="font-semibold">shopexperts Premium Partner Program</span>.
      </h5>
    </div>

    <div class="md:col-span-2 space-y-4">
      <ServiceCategoryCard
          v-for="category in serviceCategories"
          :key="category.id"
          :category="category"
      />
    </div>
  </div>
</template>

<script setup lang="ts">

import {ref} from "vue";
import ServiceCategoryCard from "./cards/ServiceCategoryCard.vue";

const serviceCategories = ref([
  {
    id: 1,
    title: "Shopify Development and Troubleshooting",
    subcategories: [
      'Shopify Development',
      'Shopify Troubleshooting',
      'Shopify UX Enhancement',
    ],
  },
  {
    id: 1,
    title: "Shopify Marketing and Sales",
    subcategories: [
      'Shopify SEO Services',
      'Shopify Email Marketing',
      'Shopify Banner Ads',
    ],
  },
  {
    id: 1,
    title: "Technical Support and Maintenance",
    subcategories: [
      'Shopify Security Audits',
      'Shopify Performance Monitoring',
      'Shopify Disaster Recovery Planning',
    ],
  },
]);

</script>
