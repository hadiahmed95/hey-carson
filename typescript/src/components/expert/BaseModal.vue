<script setup lang="ts">

import type {Component} from "vue";

const emit = defineEmits<{
  (e: 'close'): void
}>()

defineProps<{
  title: string
  description: string
  form: Component
}>()

</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto flex justify-end z-50">
    <div class="bg-white rounded-md max-w-xl w-full p-8 shadow-lg max-h-screen overflow-y-auto">
      <div class="relative">
        <h1 class="font-semibold text-primary mb-2">{{title}}</h1>
        <button
            @click="emit('close')"
            class="absolute top-0 right-0 text-grey hover:text-gray-400 text-h1 leading-none focus:outline-none"
            aria-label="Close"
        >
          &times;
        </button>
      </div>
      <p class="mb-6">
        {{ description }}
      </p>

      <component @close="emit('close')" :is="form" />

      <h4 class="text-center mt-8">
        Have a questions?
        <a href="mailto:support@example.com" class="text-link underline">Send us an email.</a>
      </h4>

      <h5 class="text-left mt-8 text-gray-500">
        This site is protected by reCAPTCHA and the Google Privacy Policy and Terms of Service apply.
      </h5>
    </div>
  </div>
</template>
