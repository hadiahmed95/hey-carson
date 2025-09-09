<script setup lang="ts">
import Trash from '@/assets/icons/trash.svg'
import Pencil from '@/assets/icons/pencil.svg'
import Badge from '@/assets/icons/hired_on_shopify.svg'
import ConfirmationModal from '../modals/ConfirmationModal.vue'
import { ref } from 'vue'
import { getS3URL } from '@/utils/helpers.ts'
import type { ExpertStories } from '@/types.ts'

const showDeleteModal = ref(false)
const props = defineProps<{
  project: ExpertStories
}>()

const emit = defineEmits<{
  (e: 'edit', story: ExpertStories): void
  (e: 'delete', storyId: number): void
}>()

const handleEdit = () => {
  emit('edit', props.project)
}

const handleDelete = () => {
  showDeleteModal.value = true
}

const confirmDelete = () => {
  emit('delete', props.project.id)
  showDeleteModal.value = false
}
</script>

<template>
  <div class="border rounded-md shadow-sm bg-white p-card-padding-lg mb-4 border-grey">
    <div class="flex justify-between items-start">
      <div class="flex items-center gap-2">
        <Badge />
      </div>
      <div class="flex gap-card-padding">
        <button @click="handleEdit" class="border border-lightGray p-2 rounded-sm">
          <Pencil />
        </button>
        <button @click="handleDelete" class="border border-lightGray p-2 rounded-sm">
          <Trash />
        </button>
      </div>
    </div>

    <h3 class="font-semibold text-lg my-card-padding w-390">{{ project.title }}</h3>

    <p class="text-tertiary text-sm mb-card-padding">{{ project.duration }}</p>

    <div class="flex gap-card-padding">
      <div
          v-for="(image, index) in project.images"
          :key="index"
          class="flex-1"
      >
        <img
          :src="getS3URL(image.url)"
          :alt="image.alt || `Project image ${index + 1}`"
          class="w-[265px] h-[150px] object-cover rounded-md"
        />
      </div>
    </div>
  </div>

  <ConfirmationModal
    v-if="showDeleteModal"
    title="Delete Customer Story"
    :message="`Are you sure you want to delete '${project.title}'? This action cannot be undone.`"
    confirm-text="Delete"
    loading-text="Deleting"
    @confirm="confirmDelete"
    @cancel="showDeleteModal = false"
  />
</template>