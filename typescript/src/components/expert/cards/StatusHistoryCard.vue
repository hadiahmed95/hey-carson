<script setup lang="ts">
import type { IStatusHistory } from '@/types.ts'
import {formatDate} from "@/utils/date.ts";

defineProps<{
  statusHistory: IStatusHistory
}>()
</script>

<template>
  <div class="px-6 py-3 border border-grey bg-white flex justify-between items-center">
    <p class="text-p font-normal">{{ statusHistory.action }}</p>
    <p class="text-p font-normal">{{ formatDate(statusHistory.created_at, true) }}</p>
  </div>
</template>
