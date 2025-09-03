<script setup lang="ts">

import SideNavigation from "../components/SideNavigation.vue";
import TopNavigation from "../components/expert/TopNavigation.vue";

import OverviewIcon from "../assets/icons/overview.svg";
import LeadsIcon from "../assets/icons/leads.svg";
import ListingIcon from "../assets/icons/listing.svg";
import QuoteIcon from "../assets/icons/quote-dark.svg";
import ReviewIcon from "../assets/icons/reviews.svg";
import TransactionIcon from "../assets/icons/transaction.svg";
import ReferralIcon from "../assets/icons/referral.svg";

const navItems = [
  { label: 'Overview', icon: OverviewIcon, path: '/admin/dashboard' },
  { label: 'Listings', icon: ListingIcon, path: '/admin/listings' },
  { label: 'Leads', icon: LeadsIcon, path: '/admin/leads' },
  { label: 'Quotes Sent', icon: QuoteIcon, path: '/admin/quotes-sent' },
  { label: 'Transactions', icon: TransactionIcon, path: '/admin/transactions' },
  { label: 'Reviews', icon: ReviewIcon, path: '/admin/reviews' },
  { label: 'Referrals', icon: ReferralIcon, path: '/admin/referrals' },
]

</script>

<template>
  <div class="flex flex-col min-h-screen bg-muted">
    <TopNavigation
        :message-count="4"
        :notification-count="2"
        profile-image="https://randomuser.me/api/portraits/men/32.jpg"
    />

    <div class="flex flex-1">
      <SideNavigation
          class="w-[15%]"
          :menu-items="navItems"
      />

      <RouterView />
    </div>
  </div>
</template>

<style scoped>

</style>