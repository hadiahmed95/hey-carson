<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { computed, watchEffect } from "vue";
import LockIcon from "../assets/icons/lock.svg";

// Add `props` to the tab definition
const props = defineProps<{
  tabs: {
    value: string,
    label: string,
    component: any,
    isLocked?: boolean,
    count?: number,
    props?: Record<string, any>
  }[]
}>()

const route = useRoute()
const router = useRouter()

// Set default tab if not present in query
watchEffect(() => {
  if (!route.query.page && props.tabs.length > 0 && props.tabs[0]?.value) {
    router.replace({
      path: route.path,
      query: { ...route.query, page: props.tabs[0].value },
    })
  }
})

const activeTab = computed(() => {
  return (typeof route.query.page === 'string' && route.query.page) || (props.tabs[0]?.value ?? '')
})

// Utility to check active tab
const isActive = (page: { value: string }) => route.query.page === page.value

// Get the full active tab object with component and props
const activeTabObj = computed(() => {
  return props.tabs.find(tab => tab.value === activeTab.value)
})
</script>


<template>
  <nav class="border-gray-200">
    <h3 class="flex mb-6 gap-3">
      <router-link
          v-for="tab in props.tabs"
          :key="tab.value"
          :to="{
          path: route.path,
          query: { ...route.query, page: tab.value }
        }"
          tag="button"
          class="py-2 font-light relative flex gap-2 items-center"
          :class="{
          'font-semibold border-b-2 border-accent': isActive(tab),
          disabled: tab.isLocked
        }"
      >
        <LockIcon v-if="tab.isLocked" />
        {{ tab.label }}
        <h5
            class="font-normal border px-1.5 rounded-full bg-danger text-white"
            v-if="!tab.isLocked && tab.count"
        >
          {{ tab.count }}
        </h5>
      </router-link>
    </h3>
  </nav>

  <component
      :is="activeTabObj?.component"
      v-bind="activeTabObj?.props"
      v-if="activeTabObj"
  />
</template>

<style scoped>
.disabled {
  opacity: 0.5;
  pointer-events: none;
}
</style>
