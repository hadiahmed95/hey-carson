<template>
  <div class="flex flex-col md:flex-row justify-between border rounded-md shadow-sm bg-white mb-4 p-6 gap-4">
    <!-- Content Section -->
    <div class="flex-1">
      <h5 class="text-gray-500 font-medium mb-2">Customer Story #{{ story.id }}</h5>
      <h3 class="font-semibold mb-4">{{ story.title }}</h3>

      <div class="mb-4">
        <h5 class="font-semibold mb-1">Subcategories</h5>
        <h5 class="font-normal text-gray-700">{{ story.subcategory }}</h5>
      </div>

      <div class="mb-4">
        <h5 class="font-semibold mb-1">Solution</h5>
        <h5 class="font-normal text-gray-700">{{ story.solution }}</h5>
      </div>

      <div>
        <h5 class="font-semibold mb-1">Result</h5>
        <h5 class="font-normal text-gray-700">{{ story.result }}</h5>
      </div>
    </div>

    <div class="flex gap-4 items-start shrink-0">
      <button aria-label="Edit">
        <Pencil />
      </button>
      <button aria-label="Delete">
        <Trash />
      </button>
    </div>
  </div>
</template>


<script setup lang="ts">
import Trash from '../../../assets/icons/trash.svg'
import Pencil from '../../../assets/icons/pencil.svg'

defineProps<{
  story: {
    id: number
    title: string
    subcategory: string
    solution: string
    result: string
  }
}>()

</script>

<style scoped>

</style>
