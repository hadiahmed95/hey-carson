<template>
  <AppProvider>
    <router-view :key="$route.fullPath"></router-view>
  </AppProvider>
</template>

<script>
export default {
  name: 'App',
}
</script>

<style scoped>
:deep(.Polaris-TextField__Input) {
  font-size: 13px !important;
}

</style>
