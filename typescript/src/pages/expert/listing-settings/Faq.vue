<template>
  <main class="w-full">
    <div class="flex justify-between items-center mb-3">
      <h3 class="font-semibold">
        Packaged Services
      </h3>
      <LeftIconButton :icon="GreenPlus">Add FAQ</LeftIconButton>
    </div>

    <EmptyState class="w-full" :icon="FAQ" content="No customer stories yet - but your past work matters! Show your skills<br/> in action, like “Shopify redesign that boosted sales by 35%."/>
  </main>
</template>

<script setup lang="ts">
import EmptyState from "@/components/expert/listing-settings/EmptyState.vue";
import FAQ from "@/assets/icons/faq.svg";
import GreenPlus from "@/assets/icons/green-plus.svg";
import LeftIconButton from "@/components/common/buttons/LeftIconButton.vue";

</script>
