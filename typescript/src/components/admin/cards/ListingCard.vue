<script setup lang="ts">
import { ref, computed, watch } from "vue";
import type { IListing } from "../../../types.ts";
import { useAdminStore } from "@/store/admin.ts";
import ExternalLink from "../../../assets/icons/externalLink.svg";
import Star from "../../../assets/icons/star.svg";


const props = defineProps<{
  listing: IListing,
  currentFilters: Record<string, any>
}>()

const emit = defineEmits<{
  (e: 'openLoginModal', expert: IListing): void
}>()

const adminStore = useAdminStore();
const action = ref('')

const handleLoginAs = () => {
  emit('openLoginModal', props.listing);
}

// Computed property for action options based on status
const actionOptions = computed(() => {
  const status = props.listing.status?.toLowerCase();
  
  if (status === 'pending' || status === 'inactive') {
    return [{ value: 'activate', label: 'Activate' }];
  } else if (status === 'active') {
    return [{ value: 'deactivate', label: 'Deactivate' }];
  }
  
  return [];
});

const handleStatusChange = async () => {
  if (!action.value || !props.listing.id) return;
  
  try {
    await adminStore.updateExpertStatus(props.listing.id, action.value);
  } catch (error: any) {
    console.error('Failed to update status:', error);
  } finally {
    action.value = '';
  }
}

// Watch for action changes and trigger API call
watch(action, (newValue) => {
  if (newValue) {
    handleStatusChange();
  }
});
</script>

<template>
  <div class="bg-white border rounded-md shadow-sm p-card-padding mb-5">
    <!-- Header -->
    <div class="grid grid-cols-5 gap-6 mb-5 items-start">
      <div class="flex gap-4 col-span-2">
        <!-- Avatar with initials or image -->
        <div class="w-16 h-16 rounded-full overflow-hidden">
          <!-- Show initials avatar if no real photo -->
          <div
              v-if="!listing.displayUrl && listing.avatarInfo"
              :class="[
                'w-full h-full rounded-full flex items-center justify-center text-white font-semibold text-h3',
                listing.avatarInfo.bgColor
              ]"
          >
            {{ listing.avatarInfo.initials }}
          </div>
          <!-- Show actual image for real photos -->
          <img
              v-else-if="listing.displayUrl"
              :src="listing.displayUrl"
              alt="Expert avatar"
              class="w-full h-full rounded-full object-cover"
          />
          <!-- Fallback initials if neither condition is met -->
          <div
              v-else
              class="w-full h-full rounded-full flex items-center justify-center text-white font-semibold text-h3 bg-coolGray"
          >
            NA
          </div>
        </div>
        <div>
          <div class="flex items-center gap-2">
            <p class="text-primary font-medium">{{ listing.name }}</p>
            <h5
                class="font-medium px-2 py-0.5 rounded-sm capitalize"
                :class="{
                  'text-pending bg-pending-light': listing.status === 'Pending',
                  'text-success bg-success-light': listing.status === 'Active',
                  'text-link bg-link-light': !['Pending', 'Active'].includes(listing.status)
                }"
            >
              {{ (listing.status.toLocaleLowerCase() === "inactive") ? "Deactivated" : listing.status }}
            </h5>
          </div>
          <div class="space-y-1">
            <h4 class="font-light">{{ listing.type }}</h4>
            <div v-if="listing.totalReviews && listing.totalReviews > 0" class="flex items-center gap-1 text-h4">
              <Star class="w-4 h-4 text-success flex-shrink-0" />
              <span class="text-primary font-medium">{{ listing.averageRating }}</span>
              <span class="text-light-grey">({{ listing.totalReviews }} reviews)</span>
            </div>
            <a :href="`mailto:${listing.email}`" class="text-h4 text-link hover:underline">
              {{ listing.email }}
            </a>
            <a :href="listing.storeUrl.startsWith('http') ? listing.storeUrl : `https://${listing.storeUrl}`" target="_blank" class="flex items-center gap-1 text-h4 text-link hover:underline">
              {{ listing.storeTitle }}
              <ExternalLink />
            </a>
          </div>
        </div>
      </div>

      <h4 class="flex flex-col">
        <span>{{ listing.jobTitle }}</span>
        <span>{{ listing.country }}</span>
        <span>{{ listing.language }}</span>
      </h4>

      <h4 class="flex flex-col">
        <span>Min. Project Budget</span>
        <span class="font-medium text-h3">{{ listing.minimumProjectBudget }}</span>
      </h4>

      <div class="font-light text-h5">
        <div class="flex flex-col items-end space-y-2">
          <div class="flex items-center space-x-2">
            <select v-model="action" class="border rounded-sm px-1 py-2 text-h4 font-medium w-fit hover:bg-gray-100">
              <option value="">Actions</option>
              <option 
                v-for="option in actionOptions" 
                :key="option.value" 
                :value="option.value"
              >
                {{ option.label }}
              </option>
            </select>

            <button
                v-if="listing.status === 'Active'"
                @click="handleLoginAs"
                class="bg-primary text-white text-h4 py-2 px-4 rounded-sm hover:bg-primary-dark"
            >
              Login As
            </button>
          </div>

          <h5 class="text-right text-light-grey">Submitted on: {{ listing.statusUpdatedAt }}</h5>
        </div>
      </div>
    </div>

    <!--  Body  -->
    <div v-if="listing.servicesOffered && listing.servicesOffered.length > 0">
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
