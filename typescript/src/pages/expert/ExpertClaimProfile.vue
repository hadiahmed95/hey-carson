<template>
  <div
      class="min-h-screen w-screen"
      :style="{ backgroundImage: `url(${backgroundImage}), linear-gradient(180deg, #FFC2B1, #FFFFFF)`, backgroundSize: 'cover', backgroundPosition: 'center' }"
  >
    <div class="flex flex-row w-full h-screen">
      <!-- Left Panel (will contain router view) -->
      <RouterView />

      <!-- Right Image Panel -->
      <div class="flex-1 h-screen relative">
        <div
            class="absolute w-[407px] h-[573px] p-6 gap-3 rounded-[8px]"
            style="top: 164px; left: 38.9375rem;"
        >
          <img :src="mainImage" alt="Main Visual" class="w-full h-full object-cover rounded-[8px]" />
        </div>
        <img
            :src="topLeftImage"
            alt="Side Visual"
            class="absolute w-[242px] h-[259px] object-cover"
            style="top: 460px; left: 26.75rem;"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const mainImage = new URL('../../assets/icons/claimProfileCenter.png', import.meta.url).href
const topLeftImage = new URL('../../assets/icons/claimProfileSide.png', import.meta.url).href
const backgroundImage = new URL('../../assets/icons/background.svg', import.meta.url).href
</script>