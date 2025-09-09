<script setup lang="ts">
import { ref } from 'vue';
import type { ExpertFaq } from '@/types.ts';
import Trash from '@/assets/icons/trash.svg'
import Pencil from '@/assets/icons/pencil.svg'
import ConfirmationModal from '../modals/ConfirmationModal.vue'

const showDeleteModal = ref(false);
const isExpanded = ref(false);

const props = defineProps<{
  question: ExpertFaq
}>()

const emit = defineEmits<{
  (e: 'edit', question: typeof props.question): void
  (e: 'delete', questionId: number): void
}>();

const toggleAccordion = () => {
  isExpanded.value = !isExpanded.value;
};

const handleEdit = () => {
  emit('edit', props.question);
};

const handleDelete = () => {
  showDeleteModal.value = true;
};

const confirmDelete = () => {
  emit('delete', props.question.id);
  showDeleteModal.value = false;
};
</script>

<template>
  <div class="border rounded-md shadow-sm bg-white mb-4 p-card-padding border-grey">
      <div @click="toggleAccordion" class="flex flex-1 justify-between h-full">
          <h5 class="text-paragraph text-primary flex items-center">{{ question.title }}</h5>
          <div class="flex gap-card-padding ml-4">
            <button @click="handleEdit" class="border border-lightGray p-2 rounded-sm">
              <Pencil />
            </button>
            <button @click="handleDelete" class="border border-lightGray p-2 rounded-sm">
              <Trash />
            </button>
          </div>
      </div>
      <!-- Answer Content - Expandable -->
      <div 
        class="overflow-hidden transition-all duration-300 ease-in-out"
        :class="isExpanded ? 'opacity-100' : 'max-h-0 opacity-0'"
      >
        <div class="mt-2 pt-2 border-t border-grey">
          <p class="text-grey-black-combo text-h5 leading-relaxed">
            {{ question.answer || 'No answer provided yet.' }}
          </p>
        </div>
      </div>
  </div>
  <ConfirmationModal
    v-if="showDeleteModal"
    title="Delete FAQ"
    :message="`Are you sure you want to delete '${question.title}'? This action cannot be undone.`"
    confirm-text="Delete"
    loading-text="Deleting"
    @confirm="confirmDelete"
    @cancel="showDeleteModal = false"
  />
</template>
