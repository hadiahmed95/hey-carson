<script setup lang="ts">
import ExternalLink from "../../../assets/icons/externalLink.svg";
import type { IClient } from "../../../types.ts";
import { getS3URL, generateInitialsAvatar } from "@/utils/helpers.ts";

const props = defineProps<{
  client: IClient,
}>()

const emit = defineEmits<{
  (e: 'openLoginModal', expert: IClient): void
}>()

// Handle Login As button click
const handleLoginAs = () => {
  emit('openLoginModal', props.client);
}
</script>

<template>
  <div class="bg-white border rounded-md shadow-sm p-card-padding mb-5">
    <div class="grid grid-cols-6 gap-6 mb-5 items-start">
      <div class="flex gap-4 col-span-2">
        <!-- Profile Image or Initials Avatar -->
        <div class="w-16 h-16 rounded-full overflow-hidden">
          <!-- Show actual image if photo exists -->
          <img
              v-if="client.photo && client.photo !== null && client.photo !== ''"
              :src="getS3URL(client.photo)"
              alt="Expert avatar"
              class="w-full h-full rounded-full object-cover"
          />
          <!-- Show initials avatar if no photo -->
          <div
              v-else
             :class="[
               'w-full h-full rounded-full flex items-center justify-center text-white font-semibold text-h3',
               generateInitialsAvatar(client.name).bgColor
             ]"
          >
           {{ generateInitialsAvatar(client.name).initials }}
          </div>
        </div>
        <div>
          <p class="text-primary font-medium">{{ client.name }}</p>
          <div class="flex items-center gap-2 mb-1">
          </div>
          <a :href="client.url" target="_blank" class="flex items-center gap-1 text-h4 text-link hover:underline">
            {{ client.url }}
            <ExternalLink />
          </a>
          <a :href="`mailto:${client.email}`" class="flex items-center gap-1 text-h4 text-link hover:underline">
            {{ client.email }}
          </a>
          <h4 class="text-primary">Shopify Plan: {{ client.shopify_plan }}</h4>
        </div>
      </div>

      <h4 class="flex flex-col">
        <span>Direct Chats</span>
        <span class="font-medium text-h3">{{ client.direct_messages_count }} Chats</span>
      </h4>

      <h4 class="flex flex-col">
        <span>Quote Requests</span>
        <span class="font-medium text-h3">{{ client.quote_requests_count }} Requests</span>
      </h4>

      <h4 class="flex flex-col">
        <span>Lifetime Spend</span>
        <span class="font-medium text-h3">{{ client.lifetime_spend ? `$${client.lifetime_spend}` : '$0.00' }}</span>
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

          <h5 class="text-right text-light-grey">Joined on: {{ client.created_at ? new Date(client.created_at).toLocaleDateString() : '' }}</h5>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>