<template>
  <div class="flex flex-col gap-6">
    <h3 class="text-primary font-semibold">Featured Packaged Services</h3>

    <div class="bg-white grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      <PackagedServices :services="services?.slice(0, 3)" />
    </div>
  </div>
</template>

<script setup lang="ts">
import PackagedServices from "./PackagedServices.vue";
import type {IPackagedService} from '@/types.ts'

defineProps<{
  services: IPackagedService[]
}>()
</script>
