<template>
  <router-view :key="$route.fullPath" />
  <Alert />
</template>

<script setup lang="ts">
import Alert from '@/components/common/alert.vue'
</script>