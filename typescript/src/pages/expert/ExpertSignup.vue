<!-- pages/ExpertSignup.vue -->
<template>
  <div
      class="min-h-screen w-screen"
      :style="{ backgroundImage: `url(${backgroundImage}), linear-gradient(180deg, #FFC2B1, #FFFFFF)`, backgroundSize: 'cover', backgroundPosition: 'center' }"
  >
    <div class="flex flex-col md:flex-row w-full h-screen">
      <RouterView /> <!-- Now responsible for rendering full step UI -->

      <!-- RIGHT IMAGE PANEL -->
      <div class="flex-1 h-screen relative w-full md:w-2/3 px-6 flex items-center justify-center">
        <div class="relative w-[720px] max-w-full">
          <img :src="mainImage" alt="Main Expert" class="w-full shadow-2xl" />
          <img :src="topLeftImage" alt="Top Left" class="w-50 h-50 object-cover absolute -top-20 -left-20" />
          <img :src="bottomRightImage" alt="Bottom Right" class="w-50 h-50 absolute -bottom-20 -right-20" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const mainImage = new URL('../../assets/icons/centeredImage.png', import.meta.url).href
const topLeftImage = new URL('../../assets/icons/leftImage.png', import.meta.url).href
const bottomRightImage = new URL('../../assets/icons/rightImage.png', import.meta.url).href
const backgroundImage = new URL('../../assets/icons/background.svg', import.meta.url).href
</script>
