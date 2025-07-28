<template>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div>
      <h3 class="font-semibold pb-2">Packaged Service</h3>
      <h4 class="pb-5">
        Based on your plan, you can select 1 category and up to 3
        <br />subcategories within the selected service category.
      </h4>
      <h4>
        Upgrade your plan if you want to select up to 3 categories
        <br />and 6 subcategories.
      </h4>
    </div>

    <div class="md:col-span-2 space-y-4">
      <PackagedServiceCard
          v-for="service in packagedServices"
          :key="service.id"
          :service="service"
      />
    </div>
  </div>
</template>

<script setup lang="ts">

import {ref} from "vue";
import PackagedServiceCard from "./cards/PackagedServiceCard.vue";

const packagedServices = ref([
  {
    id: 1,
    title: "Update theme to latest version/Move to a different theme",
    startingPrice: "$950.00",
    image: "",
  },
  {
    id: 2,
    title: "Progress bar to show amount left to qualify for \"Free Shipping\"",
    startingPrice: "$350.00",
    image: "",
  },
  {
    id: 3,
    title: "Sticky \"Add to Cart\" button on product page",
    startingPrice: "$190.00",
    image: "",
  },
]);
</script>
