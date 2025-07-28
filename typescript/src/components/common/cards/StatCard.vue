<template>
  <div class="group bg-card border border-border rounded-sm p-4 flex justify-between items-start">
    <div>
      <p class="text-primary">{{ title }}</p>
      <h1 class="mt-1">{{ value }}</h1>
    </div>

    <div class="relative">
      <Info class="block group-hover:hidden" />
      <InfoBlack class="hidden group-hover:block" />
    </div>
  </div>
</template>

<script setup lang="ts">
import Info from '../../../assets/icons/info.svg';
import InfoBlack from '../../../assets/icons/infoBlack.svg';

defineProps<{
  title: string
  value: string | number
}>()
</script>
