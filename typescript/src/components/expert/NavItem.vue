<template>
  <div
      :class="[
      'flex items-center space-x-3 px-3 py-2 rounded-md cursor-pointer transition',
      active ? 'bg-muted text-primary font-semibold' : 'hover:bg-muted text-muted-foreground'
    ]"
  >
    <component :is="iconComponent" class="w-5 h-5" />
    <h4>{{ label }}</h4>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Home, User, BarChart2, FileText, Star } from 'lucide-vue-next'

const props = defineProps<{
  icon: string
  label: string
  active?: boolean
}>()

const iconComponent = computed(() => {
  return { Home, User, BarChart2, FileText, Star }[props.icon]
})
</script>
