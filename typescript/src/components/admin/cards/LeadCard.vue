<script setup lang="ts">

import ExternalLink from "../../../assets/icons/externalLink.svg";
import type { ILeadd } from "../../../types.ts";

defineProps<{
  lead: ILeadd,
}>()

const emit = defineEmits<{
  (e: 'openLoginModal', expert: ILeadd): void
}>()

// Handle Login As button click
const handleLoginAs = () => {
  emit('openLoginModal', props.lead);
}
</script>

<template>
  <div class="bg-white border rounded-md shadow-sm p-card-padding mb-5">
    <div class="grid grid-cols-6 gap-6 mb-5 items-start">
      <div class="flex gap-4 col-span-2">
        <!-- Profile Image or Initials Avatar -->
        <div class="w-16 h-16 rounded-full overflow-hidden">
          <!-- Show actual image for real photos -->
          <img
              v-if="lead.displayUrl"
              :src="lead.displayUrl"
              alt="Lead avatar"
              class="w-full h-full rounded-full object-cover"
          />
          <!-- Show initials avatar when no real photo -->
          <div
              v-else-if="lead.avatarInfo"
              :class="[
                'w-full h-full rounded-full flex items-center justify-center text-white font-semibold text-lg',
                lead.avatarInfo.bgColor
              ]"
          >
            {{ lead.avatarInfo.initials }}
          </div>
          <!-- Fallback -->
          <div
              v-else
              class="w-full h-full rounded-full flex items-center justify-center text-white font-semibold text-lg bg-coolGray"
          >
            NA
          </div>
        </div>
        <div>
          <p class="text-primary font-medium">{{ lead.name }}</p>
          <div class="flex items-center gap-2 mb-1">
          </div>
          <a :href="lead.website" target="_blank" class="flex items-center gap-1 text-h4 text-link hover:underline">
            {{ lead.website }}
            <ExternalLink />
          </a>
          <a :href="`mailto:${lead.email}`" class="flex items-center gap-1 text-h4 text-link hover:underline">
            {{ lead.email }}
          </a>
          <h4 class="text-primary">Shopify Plan: {{ lead.plan }}</h4>
        </div>
      </div>

      <h4 class="flex flex-col">
        <span>Direct Chats</span>
        <span class="font-medium text-h3">{{ lead.directChatCount }} Chats</span>
      </h4>

      <h4 class="flex flex-col">
        <span>Quote Requests</span>
        <span class="font-medium text-h3">{{ lead.quoteRequestCount }} Requests</span>
      </h4>

      <h4 class="flex flex-col">
        <span>Lifetime Spend</span>
        <span class="font-medium text-h3">{{ lead.lifetimeSpendCount }}</span>
      </h4>

      <div class="font-light text-h5">
        <div class="flex flex-col items-end space-y-2">
          <div class="flex items-center space-x-2">
            <!-- <select class="border rounded-sm px-1 py-2 text-h4 font-medium w-fit hover:bg-gray-100">
              <option value="">Actions</option>
              <option value="last_7_days">Last 7 Days</option>
              <option value="last_week">Last Week</option>
              <option value="last_30_days">Last 30 Days</option>
              <option value="last_month">Last Month</option>
              <option value="last_year">Last Year</option>
            </select> -->

            <button
                @click="handleLoginAs"
                class="bg-primary text-white text-h4 py-2 px-4 rounded-sm hover:bg-primary-dark"
            >
              Login As
            </button>
          </div>

          <h5 class="text-right text-light-grey">Joined on: {{ lead.joinedOn }}</h5>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>