<script setup lang="ts">
import {computed, defineProps} from "vue";
import type {IRequest} from "@/types.ts";
import StatusHistoryCard from "@/components/expert/cards/StatusHistoryCard.vue";

const props = defineProps<{
  request: IRequest;
}>();

const statusHistories = computed(() => {
  return props.request.project?.history || []
});
</script>

<template>
  <div class="space-y-4">
    <StatusHistoryCard v-for="(history, index) in statusHistories" :key="index" :statusHistory="history" />
  </div>
</template>

