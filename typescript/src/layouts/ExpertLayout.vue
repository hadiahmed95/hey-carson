<script setup lang="ts">
import { useRoute } from 'vue-router'
import SideNavigation from "../components/SideNavigation.vue";
import TopNavigation from "../components/expert/TopNavigation.vue";
import Overview from "../assets/icons/overview.svg";
import Leads from "../assets/icons/leads.svg";
import MyListing from "../assets/icons/listing.svg";
import Reviews from "../assets/icons/reviews.svg";
import {useAuthStore} from "@/store/auth.ts";
import ListingSideNav from "@/components/expert/listing-settings/ListingSideNav.vue";

const navItems = [
  { label: 'Overview', icon: Overview, path: '/expert/dashboard' },
  { label: 'Leads', icon: Leads, path: '/expert/leads' },
  { label: 'My Listing', icon: MyListing, path: '/expert/my-listings' },
  { label: 'Reviews', icon: Reviews, path: '/expert/reviews' },
]

const route = useRoute()
const authStore = useAuthStore()

const listingRoutes = [
  '/expert/listing-settings',
  '/expert/listing-settings/personal-details',
  '/expert/listing-settings/service-categories',
  '/expert/listing-settings/packaged-services',
  '/expert/listing-settings/customer-stories',
  '/expert/listing-settings/faq'
]

const isListingSettings = listingRoutes.includes(route.path)
</script>


<template>
  <div class="flex flex-col min-h-screen bg-muted">
    <TopNavigation
      :message-count="4"
      :profile-image="authStore.user.photo"
      :is-listing-settings="isListingSettings"
    />

    <div class="flex flex-1">
      <ListingSideNav
        v-if="isListingSettings"
        class="w-[20%]"
      />
      <SideNavigation
        v-else
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