<script setup lang="ts">
import { ref, computed } from "vue"
import ConnectAccount from "@/components/expert/cards/ConnectAccount.vue"
import PaypalIcon from "@/assets/icons/paypal.svg"
import WiseIcon from "@/assets/icons/wise.svg"

// Accept user prop with expert data structure
const props = defineProps<{
  user?: {
    profile?: {
      wise_email?: string
      paypal_email?: string
    }
    email: string
    first_name?: string
  }
}>()

// Get payout emails from user profile (expert data structure)
const paypalEmail = computed(() => props.user?.profile?.paypal_email || '')
const wiseEmail = computed(() => props.user?.profile?.wise_email || '')

// Determine connection status
const hasPayPalAccount = computed(() => paypalEmail.value && paypalEmail.value.trim() !== '')
const hasWiseAccount = computed(() => wiseEmail.value && wiseEmail.value.trim() !== '')

const accounts = ref([
  {
    title: 'Connect your PayPal Account',
    icon: PaypalIcon,
    description: 'Allows you to request payouts on your PayPal account.',
    emailLabel: 'Enter email associated with your PayPal account',
    emailPlaceholder: 'Enter email ...',
    feeNote: 'The expert is responsible for PayPal fees.',
    currentEmail: paypalEmail,
    isConnected: hasPayPalAccount,
    type: 'paypal'
  },
  {
    title: 'Connect your Wise Account',
    icon: WiseIcon,
    description: 'Allow you to request payouts on your Wise account.',
    emailLabel: 'Enter email associated with your Wise account',
    emailPlaceholder: 'Enter email ...',
    feeNote: 'The expert is responsible for Wise fees.',
    currentEmail: wiseEmail,
    isConnected: hasWiseAccount,
    type: 'wise'
  }
])
</script>

<template>
  <div class="w-[45rem] space-y-6">
    <!-- Dynamic ConnectAccount components -->
    <ConnectAccount
        v-for="(account, index) in accounts"
        :key="index"
        :content="account"
    />
    
    <!-- Status Summary -->
    <div class="mt-6 p-4 rounded-md" 
         :class="hasPayPalAccount || hasWiseAccount ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200'">
      <div class="flex items-center gap-2">
        <div class="w-2 h-2 rounded-full" 
             :class="hasPayPalAccount || hasWiseAccount ? 'bg-green-500' : 'bg-yellow-500'">
        </div>
        <p class="text-sm font-medium"
           :class="hasPayPalAccount || hasWiseAccount ? 'text-green-800' : 'text-yellow-800'">
          {{ hasPayPalAccount || hasWiseAccount 
             ? 'Payout methods connected - you can request withdrawals' 
             : 'Connect at least one payout method to receive payments' }}
        </p>
      </div>
      
      <!-- Show connected accounts -->
      <div v-if="hasPayPalAccount || hasWiseAccount" class="mt-2 space-y-1">
        <p v-if="hasPayPalAccount" class="text-xs text-green-700">
          ✓ PayPal: {{ paypalEmail }}
        </p>
        <p v-if="hasWiseAccount" class="text-xs text-green-700">
          ✓ Wise: {{ wiseEmail }}
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Add any additional styling here */
</style>