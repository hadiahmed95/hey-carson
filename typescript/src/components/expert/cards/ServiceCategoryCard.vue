<template>
  <div class="flex flex-row border rounded-md shadow-sm bg-white mb-4 p-6 justify-between">
    <div>
      <h5 class="text-gray-500 font-medium pb-2">Service Category #{{ category.id }}</h5>
      <h3 class="font-semibold">{{ category.title }}</h3>

      <br>
      <h5 class="text-gray-500 font-medium pb-2">Subcategories</h5>
      <div class="flex flex-row">
        <h4
            class="font-normal py-1 px-2 border mr-2 rounded-sm"
            v-for="(subcategory, index) in category.subcategories"
            :key="index"
        >
          {{ subcategory }}
        </h4>
      </div>
    </div>
    <div class="flex gap-5 items-start">
      <button class="border border-gray-300 p-2 rounded-sm">
        <Pencil />
      </button>
      <button class="border border-gray-300 p-2 rounded-sm">
        <Trash />
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import Trash from '../../../assets/icons/trash.svg'
import Pencil from '../../../assets/icons/pencil.svg'

defineProps<{
  category: {
    id: number
    title: string
    subcategories: string[]
  }
}>()

</script>

<style scoped>

</style>
