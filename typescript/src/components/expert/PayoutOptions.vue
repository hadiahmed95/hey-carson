<script setup lang="ts">
import { ref } from "vue"
import ConnectAccount from "@/components/expert/cards/ConnectAccount.vue"
import PaypalIcon from "@/assets/icons/paypal.svg"
import WiseIcon from "@/assets/icons/wise.svg"

const accounts = ref([
  {
    title: 'Connect your PayPal Account',
    icon: PaypalIcon,
    description: 'Allows you to request payouts on your PayPal account.',
    emailLabel: 'Enter email associated with your PayPal account',
    emailPlaceholder: 'Enter email ...',
    feeNote: 'The expert is responsible for PayPal fees.',
  },
  {
    title: 'Connect your Wise Account',
    icon: WiseIcon,
    description: 'Allow you to request payouts on your Wise account.',
    emailLabel: 'Enter email associated with your Wise account',
    emailPlaceholder: 'Enter email ...',
    feeNote: 'The expert is responsible for Wise fees.',
  }
])
</script>


<template>
  <div class="w-[45rem] space-y-6">
    <ConnectAccount
        v-for="(account, index) in accounts"
        :key="index"
        :content="account"
    />
  </div>
</template>

<style scoped>

</style>