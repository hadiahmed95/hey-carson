<template>
  <div class="flex flex-col gap-8 w-full">
    <PersonalAndBusinessDetails :isOnboarding="false" />
  </div>
</template>

<script setup lang="ts">
import PersonalAndBusinessDetails from "../../../components/expert/PersonalAndBusinessDetails.vue";
</script>
