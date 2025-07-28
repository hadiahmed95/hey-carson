<template>
  <div class="flex flex-col gap-8 w-[53.6875rem]">
    <div class="flex flex-col gap-3">
      <div class="flex flex-row justify-between items-start w-full">
        <div class="flex flex-col gap-2 max-w-[70%]">
          <h6 class="w-fit flex items-center bg-primary text-white rounded-md px-2 py-[2px] font-semibold">
            LOCKED
          </h6>
          <h2 class="font-semibold text-primary">
            <span class="italic font-besley">Showcase past projects on your listing!</span><br>
            Success stories attract more quality leads.
          </h2>
        </div>

        <button class="text-paragraph font-semibold px-4 py-2 bg-primary text-white rounded-md flex items-center gap-2">
          <Star />
          Upgrade to PRO
        </button>
      </div>
      <p class="font-archivo font-normal">Customer stories are available on the Premium plan only. Upgrade to share <br> feedback from happy clients, explain your process, and show how you solve <br> complex problems.</p>
    </div>
    <p class="bg-gainsBoro w-[859px] h-[308px] rounded-md flex items-center justify-center text-coolGray font-normal">
      Example or illustration
    </p>
  </div>
</template>

<script setup lang="ts">
// No logic needed, purely presentational layout
import Star from "../../../assets/icons/shopexpertsStar.svg";
</script>
