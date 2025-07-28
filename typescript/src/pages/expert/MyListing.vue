<template>
  <main class="flex-1 p-6 overflow-y-auto bg-secondary font-light">
    <div class="flex flex-row mb-6 justify-between">
      <div>
        <h1>
          My Listing
        </h1>
        <p class="mt-1">
          Your profile will be featured in our expert directory. Fill in all the profile details
          <br>to make sure it’s appealing and highlights your best skills and services!
        </p>
      </div>

      <div class="flex gap-3">
        <button
            class="flex bg-primary text-white font-light rounded-md h-fit py-2 px-4 items-center gap-2"
        >
          <Eye />
          <span>Preview Public Profile</span>
        </button>
        <button
            class="flex border-2 font-medium rounded-md h-fit py-2 px-4 items-center gap-2"
        >
          <Link />
          <span>Copy Profile Link</span>
        </button>
      </div>
    </div>


    <TabNav
        :tabs="tabs"
    />
  </main>
</template>

<script setup lang="ts">
// Components

import TabNav from "../../components/TabNav.vue";
import PersonalAndBusinessDetails from "../../components/expert/PersonalAndBusinessDetails.vue";
import ServiceCategories from "../../components/expert/ServiceCategories.vue";
import PackagedService from "../../components/expert/PackagedService.vue";
import CustomerStories from "../../components/expert/CustomerStories.vue";
import Faq from "../../components/expert/Faq.vue";
import Eye from "../../assets/icons/eye.svg";
import Link from "../../assets/icons/link.svg";

const tabs = [
  { value: 'personal-business-details', label: 'Personal & Business Details', component: PersonalAndBusinessDetails, isLocked: false},
  { value: 'service-categories', label: 'Service Categories', component: ServiceCategories, isLocked: false},
  { value: 'packaged-service', label: 'Packaged Service', component: PackagedService, isLocked: true},
  { value: 'customer-stories', label: 'Customer Stories', component: CustomerStories, isLocked: true},
  { value: 'faq', label: 'FAQ', component: Faq, isLocked: true},
]
</script>
