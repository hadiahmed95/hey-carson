<script setup lang="ts">
import EmptyState from "@/components/expert/listing-settings/EmptyState.vue";
import GreenPlus from "@/assets/icons/green-plus.svg";
import LeftIconButton from "@/components/common/buttons/LeftIconButton.vue";
import StoriesCard from "@/components/expert/cards/StoriesCard.vue";
import {ref} from "vue";
import Box from "@/assets/icons/box.svg";

const customerStories = ref([
  {
    id: 1,
    title: "Boosting Organic Reach with a Complete SEO & Google Ads Overhaul",
    duration: "Apr 2024 – May 2026",
    status: "Hired on shopexperts",
    images: [
      { url: "https://images.unsplash.com/photo-1556155092-8707de31f9c4?w=300&h=200&fit=crop&auto=format", alt: "E-commerce dashboard" },
      { url: "https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=300&h=200&fit=crop&auto=format", alt: "Analytics report" },
      { url: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=300&h=200&fit=crop&auto=format", alt: "Marketing charts" }
    ]
  },
  {
    id: 3,
    title: "Multi-Channel Marketing Campaign for Fashion Brand",
    duration: "Oct 2023 – Dec 2023",
    status: "Successfully delivered",
    images: [
      { url: "https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=300&h=200&fit=crop&auto=format", alt: "Social media campaigns" },
      { url: "https://images.unsplash.com/photo-1432888622747-4eb9a8efeb07?w=300&h=200&fit=crop&auto=format", alt: "Email marketing" },
      { url: "https://images.unsplash.com/photo-1504868584819-f8e8b4b6d7e3?w=300&h=200&fit=crop&auto=format", alt: "Campaign results" }
    ]
  },
  {
    id: 2,
    title: "Complete Shopify Store Redesign & Performance Optimization",
    duration: "Jan 2024 – Mar 2024",
    status: "Project completed",
    images: [
      { url: "https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=300&h=200&fit=crop&auto=format", alt: "Shopify store homepage" },
      { url: "https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=300&h=200&fit=crop&auto=format", alt: "Product pages" },
      { url: "https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=300&h=200&fit=crop&auto=format", alt: "Lead generation dashboard" },
    ]
  },
  {
    id: 4,
    title: "B2B SaaS Website Development & Lead Generation Setup",
    duration: "Aug 2023 – Sep 2023",
    status: "Client milestone achieved",
    images: [
      { url: "https://images.unsplash.com/photo-1551650975-87deedd944c3?w=300&h=200&fit=crop&auto=format", alt: "SaaS website" },
      { url: "https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=300&h=200&fit=crop&auto=format", alt: "Lead generation dashboard" },
      { url: "https://images.unsplash.com/photo-1533750349088-cd871a92f312?w=300&h=200&fit=crop&auto=format", alt: "Conversion analytics" }
    ]
  }
]);
</script>

<template>
  <main class="w-full">
    <div class="flex justify-between items-center mb-3">
      <h3 class="font-semibold">
        Customer Stories
      </h3>
      <LeftIconButton :icon="GreenPlus">Add Customer Story</LeftIconButton>
    </div>

    <EmptyState v-if="!customerStories.length" class="w-full" :icon="Box" content="Create your first packaged service like “Shopify Store Setup – $499”<br/> and show leads exactly what they’ll get."/>
    <StoriesCard
        v-else
        v-for="story in customerStories"
        :key="story.id"
        :project="story"
    />
  </main>
</template>