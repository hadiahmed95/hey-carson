<template>
  <div
      class="bg-white border rounded-md p-4 cursor-pointer transition-all duration-200 hover:shadow-sm flex flex-col gap-2"
      :class="{
      'border-blue-500 bg-blue-50': isActive,
      'border-grey': !isActive,
    }"
      @click="handleClick"
  >

    <div class="flex items-center justify-between">
      <div class="flex items-center justify-center gap-1">
        <Lock class="w-5 h-5" v-if="isLocked" />
        <h4 class="font-semibold text-primary">
          {{ title }}
        </h4>
      </div>
      <div v-if="isLocked" class="bg-primary px-1 rounded-sm h-4">
        <h6 class="text-white">LOCKED</h6>
      </div>
    </div>


    <h4 class="text-primary">
      {{ description }}
    </h4>


  </div>
</template>

<script setup lang="ts">
defineProps<{
  title: string
  description: string
  isActive?: boolean
  isLocked?: boolean
}>()

import Lock from '@/assets/icons/lock.svg'

const emit = defineEmits<{
  click: []
}>()

const handleClick = () => {
  emit('click')
}
</script>