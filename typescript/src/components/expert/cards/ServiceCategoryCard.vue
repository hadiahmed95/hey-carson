<template>
  <div class="flex flex-row border border-grey rounded-md shadow-sm bg-white mb-card-padding p-card-padding-md justify-between">
    <div>
      <h5 class="text-grey-black-combo font-medium pb-2">Service Category #{{ category.id }}</h5>
      <h3 class="font-semibold">{{ category.title }}</h3>

      <br>
      <h5 class="text-grey-black-combo font-medium pb-2">Subcategories</h5>
      <div class="flex flex-row">
        <h4
            class="font-normal py-1 px-2 border border-grey mr-2 rounded-sm"
            v-for="(subcategory, index) in category.subcategories"
            :key="index"
        >
          {{ subcategory }}
        </h4>
      </div>
    </div>
    <div class="flex gap-card-padding items-start">
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
