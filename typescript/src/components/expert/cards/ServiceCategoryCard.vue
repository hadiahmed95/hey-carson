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
            v-for="(subcategory, index) in category.subcategories"
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
import { ref } from 'vue'
import Trash from '../../../assets/icons/trash.svg'
import Pencil from '../../../assets/icons/pencil.svg'
import ConfirmationModal from '../modals/ConfirmationModal.vue'

const showDeleteModal = ref(false)
const props = defineProps<{
  category: {
    id: number
    title: string
    subcategories: string[]
  }
}>()

const emit = defineEmits<{

  (e: 'edit', category: typeof props.category): void

  (e: 'delete', categoryId: number): void

}>()

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
