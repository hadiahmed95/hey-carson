<script setup lang="ts">
import Star from '../../../assets/icons/star.svg'
import { defineProps } from 'vue'
import type {IExpertt} from "@/types.ts";
import {formatDate} from "@/utils/date.ts";
import {getS3URL} from "@/utils/helpers.ts";

defineProps<{
  type: string,
  expert: IExpertt,
  requestType: string,
  requestCreatedAt: string,
}>()
</script>

<template>
  <aside class="bg-white border border-gray-200 p-4 flex flex-col gap-4 rounded-lg w-full max-w-xs shadow-sm self-start">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <h4 class="font-semibold text-primary">Expert Details</h4>
      <svg class="w-4 h-4 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
        <path d="M19 9l-7 7-7-7" />
      </svg>
    </div>

    <!-- Avatar + Info -->
    <div class="flex flex-col gap-2 items-start text-center" v-if="expert">
      <img :src="getS3URL(expert.photo)" alt="avatar" class="w-16 h-16 rounded-full object-cover" />
      <p class="font-semibold text-primary">{{ expert.first_name }} {{ expert.last_name }}</p>
      <h4 class="text-primary font-normal">{{ expert.company_type }}</h4>
      <h4 class="text-primary font-normal">{{ expert.profile.role }}</h4>
      <div class="flex items-center gap-2">
        <Star class="w-4 h-4" />
        <div class="flex gap-1">
          <span class="text-paragraph font-semibold text-primary">{{ expert?.reviews_stat?.rating }}</span>
          <span class="text-paragraph font-normal text-slateGray">({{ expert?.reviews_stat?.reviews_count }} reviews)</span>
        </div>
      </div>
    </div>

    <!-- Info List -->
    <div class="flex flex-col gap-2">
      <div class="flex justify-between">
        <span class="text-tertiary text-h5">Type</span>
        <span
            class="text-custom1 font-semibold px-2 py-0.5 rounded"
            :class="{
            'bg-babyBlue text-deepBlue': requestType === 'Quote Request',
            'bg-lightApricot text-earthyOrangeBrown': requestType === 'Matched',
            'bg-lightPurple text-deepViolet': requestType === 'Direct Message'
          }"
        >
          {{ requestType }}
        </span>
      </div>
      <div class="flex justify-between">
        <span class="text-tertiary text-h5">Budget</span>
        <span class="font-normal text-h5 text-primary">-</span>
      </div>
      <div class="flex justify-between">
        <span class="text-tertiary text-h5">Request Submitted</span>
        <span class="font-normal text-h5 text-primary">{{ formatDate(requestCreatedAt) }}</span>
      </div>
      <div class="flex justify-between">
        <span class="text-tertiary text-h5">Experts Location</span>
        <span class="font-normal text-h5 text-primary">{{ expert?.profile.country }}</span>
      </div>
      <div class="flex justify-between">
        <span class="text-tertiary text-h5">Local Time</span>
        <span class="font-normal text-h5 text-primary">-</span>
      </div>
      <div class="flex justify-between">
        <span class="text-tertiary text-h5">Avg. response time:</span>
        <!-- //Todo: Need to add the Avg. response time -->
        <span class="font-normal text-h5 text-primary">-</span>
      </div>
    </div>

    <!-- CTA Button -->
    <button
        class="w-full bg-black text-white text-sm font-medium py-2 rounded hover:bg-gray-800 transition"
    >
      Open Expert Profile
    </button>
  </aside>
</template>
