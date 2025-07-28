<script setup lang="ts">

import SideNavigation from "../components/SideNavigation.vue";
import TopNavigation from "../components/client/TopNavigation.vue";
import Overview from "../assets/icons/overview.svg";
import Quote from "../assets/icons/quote-dark.svg";
import Reviews from "../assets/icons/reviews.svg";
import MultiUsers from "../assets/icons/multi-users.svg";

const navItems = [
  { label: 'Overview', icon: Overview, path: '/client/dashboard' },
  { label: 'My Requests', icon: Quote, path: '/client/my-requests' },
  { label: 'Reviews', icon: Reviews, path: '/client/reviews' },
  { label: 'Experts Directory', icon: MultiUsers, path: 'https://shopexperts.com/home-new', isLinkOnly: true },
]
</script>

<template>
  <div class="flex flex-col min-h-screen bg-muted">
    <TopNavigation
        :message-count="4"
        :notification-count="2"
        profile-image="https://randomuser.me/api/portraits/men/32.jpg"
    />

    <div class="flex">
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