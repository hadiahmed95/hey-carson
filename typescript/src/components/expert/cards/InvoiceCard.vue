<script setup lang="ts">
import type { ITranscationn } from '@/types.ts'
import Download from "@/assets/icons/download.svg";
import {formatDate} from "../../../utils/date.ts";

defineProps<{
  invoice: ITranscationn
}>()
</script>

<template>
  <div class="bg-white rounded-md p-card-padding space-y-2 border border-grey">
    <div class="flex justify-between text-sm">
      <h4 class="flex flex-col">
        <span>Invoice #{{ invoice.id }}</span>
        <span class="font-medium text-h3">{{ invoice.type }}</span>
        <span class="text-h4 font-normal">Custom rate ${{ invoice?.price }}/hour</span>
      </h4>
      <h4 class="flex flex-col">
        <span>Paid via</span>
        <span class="font-medium text-h3">Direct Payment</span>
      </h4>
      <h4 class="flex flex-col">
        <span>Payment Date</span>
        <span class="font-medium text-h3">{{ formatDate(invoice.created_at) }}</span>
      </h4>
      <h4 class="flex flex-col">
        <span>Transaction Amount</span>
        <span class="font-medium text-h3">${{ invoice.total.toFixed(2) }}</span>
      </h4>
    </div>

    <div class="flex justify-end items-center">
      <div class="flex justify-center items-center">
        <button
            class="flex justify-center items-start w-full px-3 py-1 bg-primary text-white rounded-sm gap-1.5"
        >
          <Download class="w-4 h-4" />
          <span class="text-h4">Download Invoice</span>
        </button>
      </div>
    </div>
  </div>
</template>
