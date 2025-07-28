<template>
  <div class="flex flex-row border rounded-md shadow-sm bg-white mb-4 p-6 justify-between">
    <div>
      <h5 class="text-gray-500 font-medium pb-2">Question #{{ question.id }}</h5>
      <h3 class="font-semibold pb-5">{{ question.title }}</h3>

      <h5 class="font-semibold pb-2">Answer</h5>
      <h5 class="font-normal">{{ question.answer }}</h5>
    </div>
    <div class="flex gap-4 items-start shrink-0">
      <button>
        <Pencil/>
      </button>
      <button>
        <Trash/>
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import Trash from '../../../assets/icons/trash.svg'
import Pencil from '../../../assets/icons/pencil.svg'

defineProps<{
  question: {
    id: number
    title: string
    answer: string
  }
}>()

</script>

<style scoped>

</style>
