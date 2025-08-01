<script setup lang="ts">

import ExternalLink from "../../../assets/icons/externalLink.svg";
import { ref } from "vue";
import type { IListing } from "../../../types.ts";

const props = defineProps<{
  listing: IListing,
}>()

const action = ref('')

// Handle Login As button click
const handleLoginAs = () => {
  if (props.listing.onLoginAs) {
    props.listing.onLoginAs();
  }
}
</script>

<template>
  <div class="bg-white border rounded-md shadow-sm p-card-padding mb-5">
    <!-- Header -->
    <div class="grid grid-cols-5 gap-6 mb-5 items-start">
      <div class="flex gap-4 col-span-2">
        <!-- Profile Image or Initials Avatar -->
        <div class="w-[64px] h-[64px] rounded-full overflow-hidden">
          <!-- Show initials avatar only if it's the default placeholder AND has avatarInfo -->
          <div
              v-if="listing.displayUrl === 'https://randomuser.me/api/portraits/men/32.jpg' && listing.avatarInfo"
              :class="[
                'w-full h-full rounded-full flex items-center justify-center text-white font-semibold text-lg',
                listing.avatarInfo.bgColor
              ]"
          >
            {{ listing.avatarInfo.initials }}
          </div>
          <!-- Show actual image for real photos -->
          <img
              v-else-if="listing.displayUrl && listing.displayUrl !== 'https://randomuser.me/api/portraits/men/32.jpg'"
              :src="listing.displayUrl"
              alt="Profile avatar"
              class="w-full h-full rounded-full object-cover"
          />
          <!-- Fallback placeholder image -->
          <img
              v-else
              src="https://randomuser.me/api/portraits/men/32.jpg"
              alt="Profile avatar"
              class="w-full h-full rounded-full object-cover"
          />
        </div>
        
        <div>
          <div class="flex items-center gap-2">
            <p class="text-primary font-medium">{{ listing.name }}</p>
            <h5
                class="font-medium px-2 py-0.5 rounded-sm"
                :class="{
                  'text-pending bg-pending-light': listing.status === 'Pending',
                  'text-success bg-success-light': listing.status === 'Active',
                  'text-link bg-link-light': listing.status === 'Claimed',
                  'text-white bg-red-500': listing.status === 'Inactive'
                }"
            >
              {{ listing.status }}
            </h5>
          </div>
          <h4 class="font-light">{{ listing.type }}</h4>
          <a :href="`mailto:${listing.email}`" class="text-h4 text-link hover:underline">
            {{ listing.email }}
          </a>
          <a :href="listing.storeUrl" target="_blank" class="flex items-center gap-1 text-h4 text-link hover:underline">
            {{ listing.storeTitle }}
            <ExternalLink />
          </a>
        </div>
      </div>

      <h4 class="flex flex-col">
        <span>{{ listing.country }}</span>
        <span>{{ listing.jobTitle }}</span>
        <span>{{ listing.language }}</span>
      </h4>

      <h4 class="flex flex-col">
        <span>Minimum Project Budget</span>
        <span class="font-medium text-h3">{{ listing.minimumProjectBudget }}</span>
      </h4>

      <div class="font-light text-h5">
        <div class="justify-items-end space-y-2 space-x-2">
          <select v-model="action" class="border rounded-sm px-1 py-2 text-h4 font-medium w-fit hover:bg-gray-100">
            <option value="">Actions</option>
            <option value="last_7_days">Last 7 Days</option>
            <option value="last_week">Last Week</option>
            <option value="last_30_days">Last 30 Days</option>
            <option value="last_month">Last Month</option>
            <option value="last_year">Last Year</option>
          </select>

          <!-- Only show Login As button for non-inactive users -->
          <button
              v-if="listing.status !== 'Pending' && listing.status !== 'Inactive'"
              @click="handleLoginAs"
              class="bg-primary text-white text-h4 py-2 px-4 rounded-sm hover:bg-primary-dark"
          >
            Login As
          </button>

          <h5>Submitted on: {{ listing.statusUpdatedAt }}</h5>
        </div>
      </div>
    </div>

    <!--  Body  -->
    <div>
      <h4 class="text-gray-500 font-normal pb-2">Services Offered</h4>
      <div class="flex flex-row">
        <h4
            class="text-h4 font-normal py-1 px-2 border mr-2 rounded-full"
            v-for="(service, index) in listing.servicesOffered"
            :key="index"
        >
          {{ service }}
        </h4>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
