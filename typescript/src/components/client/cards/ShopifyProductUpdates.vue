<template>
  <div class="bg-muted mt-8 rounded-md">
    <h2 class="text-h3 font-semibold mb-4">Shopify Product Updates</h2>

    <div v-for="(update, index) in updates?.slice(0,5)" :key="index" class="bg-white border rounded-md p-4 mb-3">
      <div class="flex justify-between mb-1">
        <span class="text-paragraph font-semibold">{{ update.title }}</span>
        <span class="text-gray-500 text-h5 font-normal">{{ update.published_at }}</span>
      </div>
      <div class="text-h5 font-sm mb-1">{{ update.category }}</div>
      <p class="font-sm">{{ update.description }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { IShopifyProductUpdate } from "@/types";

defineProps<{
  updates: IShopifyProductUpdate[]
}>()
</script>
