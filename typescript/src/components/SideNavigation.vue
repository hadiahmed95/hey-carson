<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from "vue-router";
import type { INav } from "../types.ts";

const props = defineProps<{
  menuItems: INav[];
  isExpertSideNav?: boolean;
}>()

const route = useRoute()

const activeItem = computed(() => {
  const match = props.menuItems.find(item => route.path.startsWith(item.path))
  return match ? match.label : null
})

// Account Info
const accountInfo = {
  type: 'Freelancer',
  plan: 'Premium',
  listedSince: '17 Dec,2025',
}
</script>

<template>
  <aside class="flex flex-col h-screen bg-white font-sans">
    <div class="flex flex-col flex-1 p-7 space-y-96">
      <nav class="flex flex-col gap-4">
        <component
            v-for="item in menuItems"
            :key="item.label"
            :is="item?.isLinkOnly ? 'a' : 'router-link'"
            :href="item?.isLinkOnly ? item.path : null"
            :to="!item?.isLinkOnly ? item.path : null"
            :target="item?.isLinkOnly ? '_blank' : null"
            class="flex items-center gap-3 p-2 rounded-sm cursor-pointer hover:bg-gray-100"
            :class="{ 'bg-secondary border': activeItem === item.label }"
        >
          <component :is="item.icon" class="w-5 h-5" />
          <p class="font-light">{{ item.label }}</p>
        </component>
      </nav>

      <h4 v-if="isExpertSideNav" class="fixed bottom-8 left-8 w-[11%] flex flex-col bg-primary text-white p-4 rounded-md gap-2 mt-8 max-w-full">
        <div>
          <h6 class="text-gray-400">Account type:</h6>
          <p class="font-normal">{{ accountInfo.type }}</p>
        </div>
        <div>
          <h6 class="text-gray-400">Current plan:</h6>
          <p class="font-normal">{{ accountInfo.plan }}</p>
        </div>
        <div>
          <h6 class="text-gray-400">Listed since:</h6>
          <p class="font-normal">{{ accountInfo.listedSince }}</p>
        </div>
        <button class="mt-4 bg-white text-gray-900 text-h5 font-semibold px-4 py-2 rounded-md hover:bg-gray-100">
          Upgrade Plan
        </button>
      </h4>
    </div>
  </aside>
</template>

