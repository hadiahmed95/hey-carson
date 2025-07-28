<script setup lang="ts">
import {ref} from "vue";

interface Props {
  response: string;
  showResponseBox?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  showResponseBox: false
})
const response = ref(props.response || '');

const emit = defineEmits(['cancel', 'submit'])

function submit() {
  if (!response.value.trim()) return;

  const payload = {
    expert_response: response.value,
  };

  emit('submit', payload);
}
</script>

<template>
  <div v-if="props.showResponseBox">
    <div class="mb-4">
      <label class="block text-h4 font-medium text-greyExtraDark mb-2">
        Your Response
      </label>
      <textarea
          v-model="response"
          placeholder="Write your response to the review above ..."
          rows="4"
          class="w-full border border-grey rounded-md px-3 py-2 text-paragraph font-normal text-greyExtraDark placeholder-coolGray focus:outline-none focus:ring-2 focus:ring-grey focus:border-transparent resize-none"
      />
    </div>

    <div class="flex items-center space-x-3">
      <button
          @click="submit()"
          class="bg-primary text-white p-4 py-2 rounded-sm text-h4 font-medium hover:bg-greyDark transition-colors"
      >
        Submit Your Response
      </button>
      <button @click="emit('cancel')" class="text-coolGray px-2 py-2 rounded-sm text-h4 font-medium hover:text-greyDark transition-colors">
        Cancel
      </button>
    </div>
  </div>
</template>

<style scoped>

</style>