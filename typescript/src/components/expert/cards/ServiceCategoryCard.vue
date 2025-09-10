<template>
  <div class="flex flex-row border border-grey rounded-md shadow-sm bg-white mb-card-padding p-card-padding-md justify-between">
    <div>
      <h5 class="text-grey-black-combo font-medium pb-2">Service Category #{{ category.id }}</h5>
      <h3 class="font-semibold">{{ category.title }}</h3>

      <br>
      <h5 class="text-grey-black-combo font-medium pb-2">Subcategories</h5>
      <div class="flex flex-row">
        <h4
            class="font-normal py-1 px-2 border border-grey mr-2 rounded-sm"
            v-for="(subcategory, index) in displaySubcategories"
            :key="index"
        >
          {{ subcategory }}
        </h4>
      </div>
    </div>
    <div class="flex gap-card-padding items-start">
      <button @click="handleEdit" class="border border-gray-300 p-2 rounded-sm">
        <Pencil />
      </button>
      <button @click="handleDelete" class="border border-gray-300 p-2 rounded-sm">
        <Trash />
      </button>
    </div>
  </div>

  <ConfirmationModal
    v-if="showDeleteModal"
    title="Delete Service Category"
    :message="`Are you sure you want to delete '${category.title}'? This action cannot be undone.`"
    confirm-text="Delete"
    loading-text="Deleting"
    @confirm="confirmDelete"
    @cancel="showDeleteModal = false"
  />
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import Trash from '../../../assets/icons/trash.svg'
import Pencil from '../../../assets/icons/pencil.svg'
import ConfirmationModal from '../modals/ConfirmationModal.vue'
import type { ExpertServiceCategory } from '@/types.ts';

const showDeleteModal = ref(false)
const props = defineProps<{
  category: ExpertServiceCategory
}>()

const emit = defineEmits<{
  (e: 'edit', category: typeof props.category): void
  (e: 'delete', categoryId: number): void
}>()

// Map slug values to display names
const subcategoryDisplayMap: Record<string, string> = {
  'shopify-development': 'Shopify Development',
  'shopify-troubleshooting': 'Shopify Troubleshooting',
  'shopify-ux-enhancement': 'Shopify UX Enhancement',
  'shopify-seo-services': 'Shopify SEO Services',
  'shopify-email-marketing': 'Shopify Email Marketing',
  'shopify-banner-ads': 'Shopify Banner Ads',
  'store-maintenance-monitoring': 'Store Maintenance & Monitoring',
  'app-updates-integrations': 'App Updates & Integrations',
  'performance-optimization': 'Performance Optimization',
  'shopify-security-audits': 'Shopify Security Audits',
  'shopify-performance-monitoring': 'Shopify Performance Monitoring',
  'shopify-disaster-recovery-planning': 'Shopify Disaster Recovery Planning'
};

// Convert stored subcategory slugs to display names
const displaySubcategories = computed(() => {
  return props.category.subcategories.map(slug => 
    subcategoryDisplayMap[slug] || slug
  );
});

// Handle edit button click
const handleEdit = () => {
  emit('edit', props.category)
}

// Handle delete button click
const handleDelete = () => {
  showDeleteModal.value = true
}

const confirmDelete = () => {
  emit('delete', props.category.id)
  showDeleteModal.value = false
}
</script>

<style scoped>

</style>
