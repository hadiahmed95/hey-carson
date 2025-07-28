<template>
  <div class="flex flex-col gap-8 w-[53.6875rem]">
    <div class="flex flex-col gap-3">
      <div class="flex flex-row justify-between items-start">
        <h2 class="font-semibold text-primary"><span class="italic font-besley">Create services you can sell quickly.</span><br> A quick way to offer your favorite Shopify tasks!</h2>
        <button class="text-paragraph font-semibold px-4 py-2 bg-primary text-white rounded-md flex items-center gap-2">
          <Star/>
          Upgrade to PRO
        </button>
      </div>
      <p class="font-archivo font-normal">On the Free plan, you can create 1 packaged service, while Premium allows <br> you to create up to 6. Packaged services give you another way to sell your <br> offerings to both potential and existing clients.</p>
    </div>
    <PackagedServiceCard
        v-for="service in packagedServices"
        :key="service.id"
        :service="service"
    />
  </div>
</template>

<script setup lang="ts">
// No logic needed, purely presentational layout
import Star from "../../../assets/icons/shopexpertsStar.svg";
import {ref} from "vue";
import PackagedServiceCard from "../../../components/expert/cards/PackagedServiceCard.vue";

const packagedServices = ref([
  {
    id: 1,
    title: "Update theme to latest version/Move to a different theme",
    startingPrice: "$950.00",
    image: "",
  }
]);
</script>
