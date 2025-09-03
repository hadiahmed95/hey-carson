<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from "vue-router";
import ListingSection from "@/components/expert/listing-settings/ListingSection.vue";

const route = useRoute()

const navItems = [
  {
    title: 'Personal & Business Details',
    description: 'Share who you are, your background, and what makes your business unique.',
    path: '/expert/listing-settings/personal-details',
    locked: false
  },
  {
    title: 'Offered Services',
    description: 'Highlight the specific services you provide so clients know exactly how you can help them.',
    path: '/expert/listing-settings/service-categories',
    locked: false
  },
  {
    title: 'Packaged Services',
    description: 'Present ready-to-go bundles with clear pricing and outcomes for easier client decisions.',
    path: '/expert/listing-settings/packaged-services',
    locked: false
  },
  {
    title: 'Customer Stories',
    description: 'Showcase success stories and case studies to build trust and credibility with potential clients.',
    path: '/expert/listing-settings/customer-stories',
    locked: true
  },
  {
    title: 'FAQ',
    description: 'Answer common questions upfront to save time and show transparency in how you work.',
    path: '/expert/listing-settings/faq',
    locked: true,
  },
]

const activeItem = computed(() => {
  const match = navItems.find(item => route.path.startsWith(item.path))
  return match ? match.title : null
})
</script>

<template>
  <aside>
    <div class="flex flex-col p-8">
      <h4 class="text-grey-black-combo text-sm mb-2">Manage your listing sections</h4>

      <nav class="flex flex-col gap-3">
        <ListingSection
          v-for="item in navItems"
          :key="item.path"
          :title="item.title"
          :description="item.description"
          :isActive="activeItem === item.title"
          :isLocked="item.locked"
          @click="$router.push(item.path)"
        />
      </nav>
    </div>
  </aside>
</template>
