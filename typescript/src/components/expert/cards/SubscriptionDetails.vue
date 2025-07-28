<script setup lang="ts">
defineProps({
  subscription: {
    type: Object,
    required: true
  }
})
</script>

<template>
  <div class="w-full">
    <div class="flex justify-between items-center mb-1">
      <h4 class="text-greyDark">Subscription Details</h4>
      <button class="text-danger hover:text-danger text-h4">
        Cancel Subscription
      </button>
    </div>

    <div class="w-full bg-card border border-grey rounded-md p-7">
      <div class="flex justify-between gap-6">
        <div class="space-y-2">
          <h4 class="text-greyDark">Account Type:</h4>
          <h2 class="text-primary">{{ subscription.accountType }}</h2>
        </div>

        <div class="space-y-2">
          <h4 class="text-greyDark">Current Plan</h4>
          <h2 class="text-primary">{{ subscription.currentPlan }}</h2>
        </div>

        <div class="space-y-2">
          <h4 class="text-greyDark">Next Billing Date</h4>
          <h2 class="text-primary">{{ subscription.nextBillingDate }}</h2>
        </div>

        <div class="space-y-2">
          <h4 class="text-greyDark">Shopexpert Since</h4>
          <h2 class="text-primary">{{ subscription.memberSince }}</h2>
        </div>
      </div>
    </div>
  </div>
</template>
