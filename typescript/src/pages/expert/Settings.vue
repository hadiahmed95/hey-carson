<script setup lang="ts">
import EmailPreferences from "@/components/common/cards/EmailPreferences.vue"
import InfoCard from "@/components/common/cards/InfoCard.vue";
import Payment from "@/components/common/cards/Payment.vue";
import SettingsInfo from "@/components/common/cards/SettingsInfo.vue";
import { onMounted } from "vue";
import { formatDate } from "@/utils/date.ts";
import { useExpertStore } from "@/store/expert.ts";
import SubscriptionDetails from "@/components/expert/cards/SubscriptionDetails.vue";
import PayoutOptions from "@/components/expert/PayoutOptions.vue";

const expertStore = useExpertStore();

const subscription = {
  accountType: 'Freelancer',
  currentPlan: 'Premium',
  nextBillingDate: '17 Dec, 2024',
  memberSince: '06 Jan, 2024'
}

onMounted(async () => {
  await expertStore.fetchClient();
})
</script>

<template>
  <div class="p-6 bg-white rounded-lg shadow-sm">
    <h1 class="text-h1 font-bold mb-8">Settings Expert</h1>

    <!-- Personal details -->
    <div class="flex gap-40 justify-between mb-16">
      <SubscriptionDetails
          :subscription = "subscription"
      />
    </div>

    <!-- Email preferences -->
    <div class="flex gap-40 justify-between mb-16">
      <SettingsInfo
          title="Email Preferences"
          description="Customize notifications to fit your workflow. Get <br>
          instant updates or daily summaries for crucial<br> project information"
      />
      <EmailPreferences v-if="expertStore.user" :user="expertStore.user"/>
    </div>

    <!-- Billing Details -->
    <div class="flex gap-40 justify-between mb-16">
      <SettingsInfo
          title="Billing Details"
          description="To save time and streamline payments on your<br>
          projects, add a payment method for faster <br>
          transactions."
      />
      <Payment
          :store="expertStore"
      />
    </div>

    <!-- Payout Options -->
    <div class="flex gap-40 justify-between mb-16">
      <SettingsInfo
          title="Payout Options"
          description="Connect your PayPal or Wise accounts to your<br>
          shopexpert account so you can quickly and<br>
          easily request withdrawals from them."
      />
      <PayoutOptions />
    </div>

    <!-- Change Your Password -->
    <div class="flex gap-40 justify-between mb-16">
      <SettingsInfo
          title="Change Your Password"
          description=""
      />
      <InfoCard
          v-if="expertStore.user"
          title="Change Your Password"
          isChangePassword
          :email="expertStore.user.email"
          :tagline="expertStore.user.password_changed
          ? `You last changed password ${formatDate(expertStore.user.password_changed)}`
          : 'You have never changed your password.'"
      />
    </div>

    <!-- Close Account -->
    <div class="flex gap-40 justify-between mb-16">
      <SettingsInfo
          title="Close Account"
          description="By closing your account, you will be logged off all <br>
        your devices, and all the information associated <br>
        with your account will be deleted."
      />
      <InfoCard
          title="Send Close Request"
          tagline="We're sorry to see you go. Our team will receive your request and proceed to close your account."
      />
    </div>
  </div>
</template>