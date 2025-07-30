<script setup lang="ts">
import type {IReferral} from '../../../types.ts'
import { computed } from 'vue'

const props = defineProps<{
  referral: IReferral
}>()

const statusStyle = computed(() => {
  switch (props.referral.status) {
    case 'Approved':
      return 'bg-softgreen text-success'
    case 'Rejected':
      return 'bg-softpink text-darkpink'
    case 'Pending Review':
    default:
      return 'bg-pending-light text-pending'
  }
})
</script>

<template>
  <div class="bg-white rounded-md p-card-padding space-y-4 border border-grey">
    <div class="grid grid-flow-col auto-cols-fr items-start">
      <div class="flex items-start space-x-3">
        <img
            :src="referral.referrer.avatar"
            alt="Expert avatar"
            class="w-[64px] h-[64px] rounded-full object-cover"
        />
        <div>
          <div class="text-h4 text-tertiary">Referred by</div>
          <p class="text-primary font-medium">{{ referral.referrer.name }}</p>
          <h4 class="text-primary">Freelancer</h4>
          <a :href="`mailto:${referral.referrer.email}`" class="flex items-center gap-1 text-h4 text-link hover:underline">
            {{ referral.referrer.email }}
          </a>
        </div>
      </div>
      <div class="flex items-start space-x-3">
        <img
            :src="referral.referral.avatar"
            alt="Client avatar"
            class="w-[64px] h-[64px] rounded-full object-cover"
        />
        <div>
          <h4 class="text-tertiary">Referral</h4>
          <p class="text-primary font-medium">{{ referral.referral.name }}</p>
          <a :href="`mailto:${referral.referral.email}`" class="flex items-center gap-1 text-h4 text-link hover:underline">
            {{ referral.referral.email }}
          </a>
          <h4 class="text-primary">Shopify Plan: {{ referral.referral.shopifyPlan }}</h4>
        </div>
      </div>

      <h5 class="flex flex-col items-end text-tertiary">
        <span :class="['px-3 py-1 rounded-md font-medium', statusStyle]">{{ referral.status }}</span>
        <span v-if="referral.status === 'Rejected'" class="mt-1">Rejected on {{ referral.rejectedOn }}</span>
        <span v-if="referral.status === 'Approved'" class="mt-1">Approved on {{ referral.approvedOn }}</span>
        <span>Referred on {{ referral.referredOn }}</span>
      </h5>
    </div>

    <div class="flex flex-row justify-between border-t">
      <div class="flex gap-32 text-sm pt-4">
        <h4 class="flex flex-col">
          <span>Referral Created Account</span>
          <span class="font-medium text-h3">{{ referral.createdAccount }}</span>
        </h4>
        <h4 class="flex flex-col">
          <span>Referral Complete Project</span>
          <span class="font-medium text-h3">{{ referral.completedProject }}</span>
        </h4>
        <h4 class="flex flex-col">
          <span>Earned Amount</span>
          <span class="font-medium text-h3">${{ referral.amount }}</span>
        </h4>
      </div>
      <div class="flex justify-center items-center">
        <div v-if="referral.status === 'Pending Review'" class="space-x-4">
          <button class="rounded-sm bg-primary text-h4 text-white border py-1 px-2">
            Approve Referral
          </button>
          <button class="rounded-sm bg-white text-h4 text-primary font-semibold border border-grey py-1 px-2">
            Decline
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
