<script setup lang="ts">
import ExternalLink from "../../../assets/icons/externalLink.svg";
import { ref } from "vue";
import type { ILeadd } from "../../../types.ts";

const props = defineProps<{
  lead: ILeadd,
}>()

const action = ref('')

// Handle Login As button click
const handleLoginAs = () => {
  if (props.lead.onLoginAs) {
    props.lead.onLoginAs();
  }
}

// Handle image error
const handleImageError = (event: Event) => {
  const target = event.target as HTMLImageElement;
  if (target) {
    target.src = 'https://randomuser.me/api/portraits/men/32.jpg';
  }
}
</script>

<template>
  <div class="bg-white border rounded-md shadow-sm p-card-padding mb-5">
    <div class="grid grid-cols-6 gap-6 mb-5 items-start">
      <div class="flex gap-4 col-span-2">
        <img
            :src="lead.displayUrl"
            alt="Lead avatar"
            class="w-[64px] h-[64px] rounded-full object-cover"
            @error="handleImageError"
        />
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
        <div class="justify-items-end space-y-2 space-x-2">
          <select v-model="action" class="border rounded-sm px-1 py-2 text-h4 font-medium w-fit hover:bg-gray-100">
            <option value="">Actions</option>
            <option value="view_profile">View Profile</option>
            <option value="view_projects">View Projects</option>
            <option value="send_message">Send Message</option>
          </select>

          <button
              @click="handleLoginAs"
              class="bg-primary text-white text-h4 py-2 px-4 rounded-sm hover:bg-primary-dark"
          >
            Login As
          </button>

          <h5>Joined on: {{ lead.joinedOn }}</h5>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
