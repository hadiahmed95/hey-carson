<script setup lang="ts">
import type {ITranscationn} from '@/types.ts'
import Download from "../../assets/icons/download.svg";
import {formatDate} from "../../utils/date.ts";
import {getS3URL} from "@/utils/helpers.ts";

defineProps<{
  transaction: ITranscationn
  isAdmin?: boolean
}>()
</script>

<template>
  <div class="bg-white rounded-md p-card-padding space-y-4 border border-grey">
    <div class="flex justify-between text-sm items-start">
      <div class="flex flex-col text-h4">
        <span>Invoice #{{transaction.id}}/Project #{{transaction?.project?.id}}</span>
        <p class="font-semibold">{{ transaction?.project?.name }}</p>
      </div>
      <div class="flex flex-wrap gap-12 ml-auto text-h4">
        <div class="flex flex-col">
          <span>Type</span>
          <h3 class="font-medium">{{ transaction.type }}</h3>
        </div>
        <div class="flex flex-col">
          <span>Paid via</span>
          <h3 class="font-medium">Direct Payment</h3>
        </div>
        <div class="flex flex-col">
          <span>Payment Data</span>
          <h3 class="font-medium">{{ formatDate(transaction.created_at) }}</h3>
        </div>
        <div class="flex flex-col">
          <span>Total Amount</span>
          <h3 class="font-medium">${{ transaction.total.toFixed(2) }}</h3>
        </div>
      </div>
    </div>


    <div class="flex justify-between border-t pt-4 items-center">
      <div class="grid grid-flow-col auto-cols-fr">
        <div v-if="transaction.expert" class="w-96 flex items-start space-x-3">
          <img
              :src="getS3URL(transaction.expert.photo)"
              alt="Expert avatar"
              class="w-[64px] h-[64px] rounded-full object-cover"
          />
          <div>
            <h4 class="text-tertiary">Expert</h4>
            <p class="text-primary font-medium">{{ transaction.expert.first_name }} {{ transaction.expert.last_name }}</p>
            <h4 class="text-primary">Freelancer</h4>
            <a :href="`mailto:${transaction.expert.email}`" class="flex items-center gap-1 text-h4 text-link hover:underline">
              {{ transaction.expert.email }}
            </a>
          </div>
        </div>
        <div v-if="transaction.client && isAdmin" class="w-96 flex items-start space-x-3">
          <img
              :src="transaction.client.photo"
              alt="Client avatar"
              class="w-[64px] h-[64px] rounded-full object-cover"
          />
          <div>
            <h4 class="text-tertiary">Client</h4>
            <p class="text-primary font-medium">{{ transaction.client.first_name }} {{ transaction.client.last_name }}</p>
            <a :href="`mailto:${transaction.client.email}`" class="flex items-center gap-1 text-h4 text-link hover:underline">
              {{ transaction.client.email }}
            </a>
            <h4 class="text-primary">Shopify Plan: {{ transaction.client.shopify_plan }}</h4>
          </div>
        </div>
      </div>

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
