<template>
  <div class="py-14 px-20 flex flex-col gap-2 border border-grey rounded-sm items-center justify-center bg-white">
    <img v-if="isReviewsPage" class="w-64 h-18" :src="ReviewsPlaceholder" alt="Reviews Placeholder">
    <img v-else class="w-40 h-20" :src="PlaceholderEmptyData" alt="Placeholder Empty Data">
    <div >
      <p class="font-archivo font-semibold mb-1">{{ title }}</p>
      <p class="font-archivo font-normal">{{ description }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import PlaceholderEmptyData from '@/assets/icons/placeholder-empty-data.png'
import ReviewsPlaceholder from '@/assets/icons/reviews-placeholder.png'

defineProps<{
  title: string
  description?: string
  isReviewsPage?: boolean
}>()
</script>