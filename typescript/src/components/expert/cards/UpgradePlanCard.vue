<script setup lang="ts">
import ShopexpertsBlackLogo from '@/assets/icons/shopexperts-black.svg';
import Lock from '@/assets/icons/lock.svg';
import LeftIconButton from "@/components/common/buttons/LeftIconButton.vue";
</script>

<template>
  <div class="bg-primary text-white p-4 rounded-md flex items-start justify-between">
    <div class="flex flex-col gap-3">
      <div class="flex flex-col gap-1">
        <p class="font-semibold">
          Ready to stand out? Unlock your full potential with shopexperts PRO.
        </p>
        <h4>
          Upgrade to <span class="font-semibold">shopexperts PRO</span> to unlock all options and showcase your full capabilities and experience. <br/> You can fill out every field and section, but locked items won’t appear on your public profile.
        </h4>
      </div>

      <div>
        <LeftIconButton :is-light-theme="true" :icon="ShopexpertsBlackLogo">Upgrade to PRO</LeftIconButton>
      </div>
    </div>

    <div class="bg-white text-primary px-1 py-1 rounded-sm flex items-center gap-1">
      <Lock />
      <h6 class="font-semibold">LOCKED</h6>
    </div>

  </div>
</template>
