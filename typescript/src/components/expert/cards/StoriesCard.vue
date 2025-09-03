<script setup lang="ts">
import Trash from '@/assets/icons/trash.svg'
import Pencil from '@/assets/icons/pencil.svg'
import Badge from '@/assets/icons/hired_on_shopify.svg'

defineProps<{
  project: {
    id: number
    title: string
    duration: string
    status: string
    images: Array<{
      url: string
      alt?: string
    }>
  }
}>()
</script>

<template>
  <div class="border rounded-md shadow-sm bg-white p-card-padding-lg mb-4 border-grey">
    <div class="flex justify-between items-start">
      <div class="flex items-center gap-2">
        <Badge />
      </div>
      <div class="flex gap-card-padding">
        <button class="border border-lightGray p-2 rounded-sm">
          <Pencil />
        </button>
        <button class="border border-lightGray p-2 rounded-sm">
          <Trash />
        </button>
      </div>
    </div>

    <h3 class="font-semibold text-lg my-card-padding w-390">{{ project.title }}</h3>

    <p class="text-tertiary text-sm mb-card-padding">{{ project.duration }}</p>

    <div class="flex gap-card-padding">
      <div
          v-for="(image, index) in project.images"
          :key="index"
          class="flex-1"
      >
        <img
            :src="image.url"
            :alt="image.alt || `Project image ${index + 1}`"
            class="w-full h-42 object-cover rounded-md"
        />
      </div>
    </div>
  </div>
</template>