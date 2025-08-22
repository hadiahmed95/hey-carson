<template>
  <div class="bg-muted mt-8 rounded-md">
    <h3 class="font-semibold mb-4">Shopify Product Updates</h3>

    <div v-for="(update, index) in updates?.slice(0,5)" :key="index" class="bg-white border rounded-md p-4 mb-3">
      <div class="flex justify-between mb-1">
        <p class="font-semibold">{{ update.title }}</p>
        <h5 class="text-gray-500 font-normal">{{ update.published_at }}</h5>
      </div>
      <h5 class="font-sm mb-1">{{ update.category }}</h5>
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
