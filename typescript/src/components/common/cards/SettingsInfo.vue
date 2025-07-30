<script setup lang="ts">
defineProps<{
  title: string
  description?: string
}>()
</script>

<template>
  <div class="dynamic-title-description font-archivo">
    <h4 class="text-paragraph font-semibold">{{ title }}</h4>
    <p
        v-if="description"
        class="text-h4 text-coolGray"
        v-html="description"
    ></p>
  </div>
</template>


docum