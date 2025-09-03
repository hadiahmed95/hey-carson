<template>
  <main class="w-full">
    <div class="flex justify-between items-center mb-3">
      <h3 class="font-semibold">
        Packaged Services
      </h3>
      <LeftIconButton :icon="GreenPlus">Add Service</LeftIconButton>
    </div>

    <EmptyState v-if="!serviceCategories.length" class="w-full" :icon="Box" content="Create your first packaged service like “Shopify Store Setup – $499”<br/> and show leads exactly what they’ll get."/>
    <ServiceCategoryCard
        v-else
        v-for="category in serviceCategories"
        :key="category.id"
        :category="category"
    />

  </main>
</template>

<script setup lang="ts">
import EmptyState from "@/components/expert/listing-settings/EmptyState.vue";
import Box from "@/assets/icons/box.svg";
import GreenPlus from "@/assets/icons/green-plus.svg";
import LeftIconButton from "@/components/common/buttons/LeftIconButton.vue";
import {ref} from "vue";
import ServiceCategoryCard from "@/components/expert/cards/ServiceCategoryCard.vue";

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
