<template>
  <div
      class="min-h-screen w-screen"
      :style="{ backgroundImage: `url(${backgroundImage}), linear-gradient(180deg, #E7C8FF, #FFFFFF)`, backgroundSize: 'cover', backgroundPosition: 'center' }"
  >
    <div class="flex flex-row w-full h-screen">
      <!-- Left Panel (router view) -->
      <RouterView />

      <!-- Right Image Panel -->
      <div class="flex-1 flex items-center justify-center">
        <img :src="mainImage" alt="Main Visual" class="w-[680px] h-[573px] object-cover" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const mainImage = new URL('../../assets/icons/get-matched-cards.png', import.meta.url).href
const backgroundImage = new URL('../../assets/icons/background.svg', import.meta.url).href
</script>