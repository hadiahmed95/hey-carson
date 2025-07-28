<template>
  <div class="flex flex-col gap-8 w-[53.6875rem]">
    <div class="flex flex-col gap-3">
      <div class="flex flex-row justify-between items-start">
        <h2 class="font-semibold text-primary"><span class="italic font-besley">Which services do you want to offer?</span><br> Organize the main services of your business.</h2>
        <button class="text-paragraph font-semibold px-4 py-2 bg-primary text-white rounded-md flex items-center gap-2">
          <Star/>
          Upgrade to PRO
        </button>
      </div>
      <p class="font-archivo font-normal">On the Free plan, you can select 1 service, while Premium allows you to select <br> up to 3 services. Choose categories that best reflect your expertise and align <br> with your business goals.</p>
    </div>
    <ServiceCategoryCard
        v-for="category in serviceCategories"
        :key="category.id"
        :category="category"
    />
  </div>
</template>

<script setup lang="ts">
import Star from "../../../assets/icons/shopexpertsStar.svg";
import {ref} from "vue";
import ServiceCategoryCard from "../../../components/expert/cards/ServiceCategoryCard.vue";

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
