<script setup lang="ts">
import { useRoute } from 'vue-router'
import SideNavigation from "../components/SideNavigation.vue";
import TopNavigation from "../components/expert/TopNavigation.vue";
import Overview from "../assets/icons/overview.svg";
import Leads from "../assets/icons/leads.svg";
import MyListing from "../assets/icons/listing.svg";
import Reviews from "../assets/icons/reviews.svg";

const navItems = [
  { label: 'Overview', icon: Overview, path: '/expert/dashboard' },
  { label: 'Leads', icon: Leads, path: '/expert/leads' },
  { label: 'My Listing', icon: MyListing, path: '/expert/my-listings' },
  { label: 'Reviews', icon: Reviews, path: '/expert/reviews' },
]

const route = useRoute()

const onboardingRoutes = [
  '/expert/onboarding',
  '/expert/onboarding-steps/personalDetails',
  '/expert/onboarding-steps/serviceCategories',
  '/expert/onboarding-steps/packagedServices',
  '/expert/onboarding-steps/customerStories',
  '/expert/onboarding-steps/faq'
]

const isOnboarding = onboardingRoutes.includes(route.path)
const hideSidebar = isOnboarding

// Control counts for TopNavigation
const messageCount = isOnboarding ? 0 : 4
const notificationCount = isOnboarding ? 0 : 2
</script>


<template>
  <div class="flex flex-col min-h-screen bg-muted">
    <TopNavigation
        :message-count="messageCount"
        :notification-count="notificationCount"
        :profile-image="isOnboarding ? null : 'https://randomuser.me/api/portraits/men/32.jpg'"
        :is-onboarding="isOnboarding"
    />

    <div class="flex flex-1">
      <SideNavigation
        v-if="!hideSidebar"
        class="w-[15%]"
        :menu-items="navItems"
        :is-expert-side-nav="true"
      />

      <RouterView />
    </div>
  </div>
</template>

<style scoped>

</style>