<template>
  <div class="mb-4 w-full">
    <div class="flex justify-between items-center flex-wrap gap-6">
      <!-- LEFT: Avatar + Info -->
      <div class="flex gap-4 items-center">
        <img
            :src="getS3URL(expert.photo)"
            alt="Avatar"
            class="w-16 h-16 rounded-full"
            @error="handleImgError"
        />
        <div class="flex flex-col">
          <div class="flex items-center gap-2">
            <h4 class="text-primary font-medium">{{ expert.first_name }} {{ expert.last_name }}</h4>
            <span
                v-if="request_type === 'Quote Request'"
                class="text-xs font-medium px-2 py-0.5 rounded-sm"
                :class="{
                'text-pending bg-pending-light': !expert.quotes?.length,
                'text-linkDarkBlue bg-linkBlue': expert.quotes?.length
              }"
            >
              {{ expert.quotes?.length ? 'Quote Submitted' : 'Pending Quote' }}
            </span>
          </div>
          <p class="text-gray-700 text-sm">{{ expert.company_type }}</p>
          <h4 class="text-primary font-normal">{{ expert.profile.role }}</h4>
          <div class="flex items-center gap-1 text-h5">
            <Star />
            <p class="font-semibold">
              {{ expert?.reviews_stat?.rating }}
              <span class="text-tertiary font-normal"> ({{ expert?.reviews_stat?.reviews_count }} reviews)</span>
            </p>
          </div>
        </div>
      </div>

      <div
          class="flex items-center flex-wrap justify-end lg:gap-6">
        <!-- Quote Meta Grid -->
        <div
            v-if="expert.quotes?.[0]"
            class="grid grid-cols-4 text-h5 text-gray-800 gap-14"
        >
          <div class="flex flex-col gap-1">
            <h5 class="text-primary font-normal">Hourly rate</h5>
            <p class="text-primary font-normal">${{ expert.quotes[0].rate.toFixed(2) }}</p>
          </div>
          <div class="flex flex-col gap-1">
            <h5 class="text-primary font-normal">Estimated time</h5>
            <p class="text-primary font-normal">{{ expert.quotes[0].hours }} hours</p>
          </div>
          <div class="flex flex-col gap-1">
            <h5 class="text-primary font-normal">Deadline</h5>
            <p class="text-primary font-normal">{{ formatDate(expert.quotes[0].deadline) }}</p>
          </div>
          <div class="flex flex-col gap-1">
            <h5 class="text-primary font-normal">Total to pay</h5>
            <p class="text-primary font-normal">{{ (expert.quotes[0].rate * expert.quotes[0].hours).toFixed(2) }}</p>
          </div>
        </div>

        <!-- Action Buttons (Always Shown) -->
        <div class="flex gap-2 items-center min-w-[180px] justify-end">
          <router-link
              to=""
              class="px-4 py-2 border rounded text-h4 flex items-center gap-2 opacity-60"
              :class="{
                'text-black bg-white hover:bg-gray-100': expert.quotes?.length,
                'text-white bg-primary hover:bg-gray-800': !expert.quotes?.length,
              }"
          >
            <Chat v-if="!expert.quotes?.length" />
            <ChatBlack v-else />
            <span>Chat Now</span>
          </router-link>

          <button
              v-if="expert.quotes?.length"
              @click.stop="acceptQuote()"
              class="px-4 py-2 rounded text-h4 flex items-center gap-2 text-white bg-primary hover:bg-gray-800 opacity-60"
          >
            <span>Accept Quote</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import Chat from '@/assets/icons/chat.svg'
import ChatBlack from '@/assets/icons/chatBlack.svg'
import Star from '@/assets/icons/star.svg'
import type { IExpertt } from '@/types'
import {formatDate} from "@/utils/date.ts";
import {getS3URL, handleImgError} from "@/utils/helpers.ts";
import { defineEmits } from 'vue'

const emit = defineEmits<{
  (e: 'accept-quote', expert: IExpertt): void
}>()

const acceptQuote = () => {
  emit('accept-quote', props.expert)
}

const props = withDefaults(defineProps<{
  expert: IExpertt;
  request_type?: string;
}>(), {
  request_type: '',
});
</script>
