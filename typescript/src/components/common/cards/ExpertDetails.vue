<script setup lang="ts">
import Star from '../../../assets/icons/star.svg'
import { defineProps } from 'vue'
import type {IExpertt} from "@/types.ts";
import {formatDate} from "@/utils/date.ts";
import {getS3URL, handleImgError} from "@/utils/helpers.ts";
import VerticalLoadingCard from "@/components/common/VerticalLoadingCard.vue";

defineProps<{
  type: string,
  expert: IExpertt,
  requestType: string,
  requestCreatedAt: string,
  isLoading: boolean
}>()
</script>

<template>

  <aside class="bg-white border border-gray-200 py-4 px-6 rounded-lg w-full max-w-xs shadow-sm h-fit">
    <VerticalLoadingCard v-if="isLoading"/>
    <div v-else class="flex flex-col gap-4">
      <div class="flex items-center justify-between">
        <h4 class="font-semibold text-primary">Expert Details</h4>
        <svg class="w-5 h-5 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
          <path d="M19 9l-7 7-7-7" />
        </svg>
      </div>

      <!-- Avatar + Info -->
      <div class="flex flex-col items-start text-center" v-if="expert">
        <img
          :src="getS3URL(expert.photo)"
          alt="avatar"
          class="w-16 h-16 mb-2 rounded-full object-cover"
          @error="handleImgError"
        />
        <p class="font-semibold text-primary">{{ expert.first_name }} {{ expert.last_name }}</p>
        <h4 class="text-primary font-normal">{{ expert.company_type }}</h4>
        <h4 class="text-primary font-normal">{{ expert.profile.role }}</h4>
        <div class="flex items-center gap-2">
          <Star class="w-4 h-4" />
          <div class="flex gap-1">
            <p class="font-semibold text-primary">{{ expert?.reviews_stat?.rating }}</p>
            <p class="font-normal text-slateGray">({{ expert?.reviews_stat?.reviews_count }} reviews)</p>
          </div>
        </div>
      </div>

      <!-- Info List -->
      <div class="flex flex-col gap-2">
        <div class="flex justify-between">
          <h5 class="text-tertiary">Type</h5>
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
          <h5 class="text-tertiary">Budget</h5>
          <h5 class="font-normal text-primary">-</h5>
        </div>
        <div class="flex justify-between">
          <h5 class="text-tertiary">Request Submitted</h5>
          <h5 class="font-normal text-primary">{{ formatDate(requestCreatedAt) }}</h5>
        </div>
        <div class="flex justify-between">
          <h5 class="text-tertiary">Experts Location</h5>
          <h5 class="font-normal text-primary">{{ expert?.profile.country }}</h5>
        </div>
        <div class="flex justify-between">
          <h5 class="text-tertiary">Local Time</h5>
          <h5 class="font-normal text-primary">-</h5>
        </div>
        <div class="flex justify-between">
          <h5 class="text-tertiary">Avg. response time:</h5>
          <!-- //Todo: Need to add the Avg. response time -->
          <h5 class="font-normal text-primary">-</h5>
        </div>
      </div>

      <!-- CTA Button -->
      <button
          class="w-full bg-black text-white text-sm font-medium py-2 rounded hover:bg-gray-800 transition"
      >
        Open Expert Profile
      </button>
    </div>
  </aside>
</template>
