<script setup lang="ts">
import EmptyState from "@/components/expert/listing-settings/EmptyState.vue";
import Box from "@/assets/icons/box.svg";
import GreenPlus from "@/assets/icons/green-plus.svg";
import LeftIconButton from "@/components/common/buttons/LeftIconButton.vue";
import {ref} from "vue";
import PackageCard from "@/components/expert/cards/PackageCard.vue";

const packagedServices = ref([
  {
    id: 1,
    title: "Set Up Email Marketing & Automations with Klaviyo",
    price: 400.00,
    image: "https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=300&h=200&fit=crop&auto=format"
  },
  {
    id: 2,
    title: "Complete Shopify Store Setup & Configuration",
    price: 499.00,
    image: "https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=300&h=200&fit=crop&auto=format"
  },
  {
    id: 3,
    title: "Social Media Marketing Strategy & Implementation",
    price: 350.00,
    image: "https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=300&h=200&fit=crop&auto=format"
  },
]);
</script>

<template>
  <main class="w-full">
    <div class="flex justify-between items-center mb-3">
      <h3 class="font-semibold">
        Packaged Services
      </h3>
      <LeftIconButton :icon="GreenPlus">Add Service</LeftIconButton>
    </div>

    <EmptyState v-if="!packagedServices.length" class="w-full" :icon="Box" content="Create your first packaged service like “Shopify Store Setup – $499”<br/> and show leads exactly what they’ll get."/>
    <PackageCard
        v-else
        v-for="service in packagedServices"
        :key="service.id"
        :service="service"
    />

  </main>
</template>
