<template>
  <div class="flex items-start justify-center w-[80%] bg-secondary px-8 py-8">
    <div class="w-[80%]">
      <RouterView />
    </div>

    <UpgradePlanCard class="fixed bottom-8 w-[60%]" />
  </div>
</template>
<script setup lang="ts">
import UpgradePlanCard from "@/components/expert/cards/UpgradePlanCard.vue";
</script>