<template>
  <div class="border rounded-md shadow-sm bg-white mb-4 p-card-padding">
    <!-- Top row -->
    <div class="flex flex-col gap-2 mb-4">
      <div class="flex items-center justify-between">
        <p class="font-semibold text-custom1 px-1 py-1 rounded-sm"
          :class="{
            'bg-babyBlue text-deepBlue': type === 'Quote Request',
            'bg-lightApricot text-earthyOrangeBrown': type === 'Matched',
            'bg-lightPurple text-deepViolet': type === 'Direct Message'
          }"
        >
          {{ type }}
        </p>
        <h5 class="text-primary font-normal">Submitted on {{ submittedDate }}</h5>
      </div>

      <p class="font-semibold">{{ projectName }}</p>
    </div>

    <div class="flex justify-between gap-4">
      <div class="flex items-center gap-4">
        <img
            :src="getS3URL(avatarUrl)"
            alt="Avatar"
            class="w-16 h-16 rounded-full object-cover"
            @error="handleImgError"
        />

        <div class="flex flex-wrap items-center gap-4 text-h4">
          <div class="flex flex-col">
            <p class="font-normal">{{ name }}</p>
            <h4 class="text-gray-500">{{ email }}</h4>
          </div>

          <div class="border-l h-8 mx-2"></div>

          <a v-if="storeUrl" :href="storeUrl" target="_blank" class="text-h4 text-link hover:underline flex items-center gap-1">
            {{ storeName }}
            <ExternalLink />
          </a>

          <div class="border-l h-8 mx-2"></div>

          <h4 v-if="shopifyPlan">
            Shopify plan: {{ shopifyPlan }}
          </h4>

          <template v-if="budget">
            <div class="border-l h-8 mx-2"></div>
            <h4>
              Budget: {{ budget }}
            </h4>
          </template>
        </div>
      </div>

      <div class="flex items-center justify-center gap-2 mt-4">
        <select v-model="status" class="border rounded px-1 w-36 py-2 text-h4 hover:bg-gray-100">
          <option value="In Progress">In Progress</option>
          <option value="Completed">Completed</option>
          <option value="Closed">Closed</option>
        </select>
        <router-link
            :to="`/expert/lead/${1}/chatroom`"
            class="bg-primary text-white px-4 py-2 rounded text-h4 flex items-center gap-2 hover:bg-gray-800"
        >
          <Chat />
          <span>Chat Now</span>
        </router-link>
        <button class="border px-4 py-2 rounded text-h4 hover:bg-gray-100 flex items-center gap-2">
          <Quote />
          <span>Send Quote</span>
        </button>
      </div>
    </div>

    <!-- Bottom row: Actions -->
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Chat from '../../../assets/icons/chat.svg';
import Quote from '../../../assets/icons/quote-dark.svg';
import ExternalLink from '../../../assets/icons/externalLink.svg';
import {getS3URL, handleImgError} from "@/utils/helpers.ts";

const props = defineProps<{
  name: string
  email: string
  projectName: string
  storeName?: string
  storeUrl?: string
  shopifyPlan?: string
  avatarUrl: string
  submittedDate: string
  initialStatus?: string
  budget?: string | null
  type?: string | null
}>()

const status = ref(props.initialStatus || 'In Progress')
</script>

<style scoped>

</style>
