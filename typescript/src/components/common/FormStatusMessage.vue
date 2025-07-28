<template>
  <div v-if="visible && status">
    <div
        v-if="status === 'success'"
        class="bg-light-green inline-block rounded-sm px-4 py-1"
    >
      {{ message }}
    </div>
    <div
        v-else
        class="bg-danger text-white inline-block rounded-sm px-2 py-1"
    >
      {{ status }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'

const props = defineProps<{
  status: string | null
  message: string | null
  trigger: number
}>()

const emit = defineEmits(['updateStatus']);

const visible = ref(false)
let timeout: ReturnType<typeof setTimeout>

watch(() => props.trigger, () => {
  if (props.status) {
    visible.value = true
    clearTimeout(timeout)
    timeout = setTimeout(() => {
      visible.value = false
      emit('updateStatus');
    }, 3000)
  }
})
</script>
