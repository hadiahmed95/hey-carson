<template>
  <div class="py-16 px-6 flex flex-col gap-6 border border-grey rounded-sm items-center justify-center bg-white">
    <component :is="icon" v-if="icon" class="w-14 h-14" />
    <div>
      <p class="text-grey-black-combo font-normal text-center" v-html="content"></p>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  content: string,
  icon: String

}>()
</script>