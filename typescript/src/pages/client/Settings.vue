<script setup lang="ts">
import ProfileCard from "@/components/client/cards/ProfileCard.vue"
import EmailPreferences from "@/components/common/cards/EmailPreferences.vue"
import InfoCard from "@/components/common/cards/InfoCard.vue";
import Payment from "@/components/common/cards/Payment.vue";
import SettingsInfo from "@/components/common/cards/SettingsInfo.vue";
import {onMounted} from "vue";
import {useClientStore} from "@/store/client.ts";
import {formatDate} from "@/utils/date.ts";

const clientStore = useClientStore();

onMounted(async () => {
  await clientStore.fetchClient();
})
</script>

<template>
  <div class="p-6 bg-secondary flex-1">
    <div>
      <h1 class="font-bold mb-8">Settings</h1>

      <!-- Personal details -->
      <div class="flex flex-col lg:flex-row gap-4 lg:gap-12 justify-start mb-16">
        <SettingsInfo
            title="Personal Details"
            description="This is your profile details that experts will see<br>when communicating with you."
        />
        <ProfileCard
            v-if="clientStore.user"
            :user="clientStore.user"
            @update-email="clientStore.user.email = $event"
        />
      </div>

      <!-- Email preferences -->
      <div class="flex flex-col lg:flex-row gap-4 lg:gap-12 justify-start mb-16">
        <SettingsInfo
            title="Email Preferences"
            description="Customize notifications to fit your workflow. Get <br>
        instant updates or daily summaries for crucial<br> project information"
        />
        <EmailPreferences v-if="clientStore.user" :user="clientStore.user"/>
      </div>

      <!-- Billing Details -->
      <div class="flex flex-col lg:flex-row gap-4 lg:gap-12 justify-start mb-16">
        <SettingsInfo
            title="Billing Details"
            description="To save time and streamline payments on your<br>
        projects, add a payment method for faster <br>
        transactions."

        />
        <Payment
          :store="clientStore"
        />
      </div>

      <!-- Change Your Password -->
      <div class="flex flex-col lg:flex-row gap-4 lg:gap-12 justify-start mb-16">
        <SettingsInfo
            title="Change Your Password"
            description=""
        />
        <InfoCard
            v-if="clientStore.user"
            title="Change Your Password"
            isChangePassword
            :email="clientStore.user.email"
            :tagline="clientStore.user.password_changed
          ? `You last changed password ${formatDate(clientStore.user.password_changed)}`
          : 'You have never changed your password.'"
        />
      </div>

      <!-- Close Account -->
      <div class="flex flex-col lg:flex-row gap-4 lg:gap-12 justify-start mb-16">
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
  </div>
</template>