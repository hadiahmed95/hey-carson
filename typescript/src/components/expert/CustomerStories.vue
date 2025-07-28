<template>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div>
      <h3 class="font-semibold mb-2">Customer Stories</h3>
      <h4 class="mb-4">
        Based on your plan, you can select 1 category and up to 3
        <br />subcategories within the selected service category.
      </h4>
      <h4>
        Upgrade your plan if you want to select up to 3 categories
        <br />and 6 subcategories.
      </h4>
    </div>

    <div class="md:col-span-2 space-y-4">
      <CustomerStoryCard
          v-for="story in customerStories"
          :key="story.id"
          :story="story"
      />
    </div>
  </div>
</template>

<script setup lang="ts">

import CustomerStoryCard from "./cards/CustomerStoryCard.vue";
import { ref } from "vue";

const customerStories = ref([
  {
    id: 1,
    title: "Macguire Shoes",
    subcategory: "Macguire Shoes faced low online sales due to an outdated website with poor mobile responsiveness and slow loading speeds. The checkout process was complicated, leading to high cart abandonment rates and lost opportunities for conversion.",
    solution: "Macguire Shoes faced low online sales due to an outdated website with poor mobile responsiveness and slow loading speeds. The checkout process was complicated, leading to high cart abandonment rates and lost opportunities for conversion.",
    result: "Thanks to the developer’s expertise, Macguire Shoes saw a 40% boost in sales within just three months. The site’s faster performance and enhanced checkout flow resulted in a 25% reduction in cart abandonment and a 30% increase in conversion rates.",
  },
  {
    id: 2,
    title: "Macguire Shoes",
    subcategory: "Macguire Shoes faced low online sales due to an outdated website with poor mobile responsiveness and slow loading speeds. The checkout process was complicated, leading to high cart abandonment rates and lost opportunities for conversion.",
    solution: "Macguire Shoes faced low online sales due to an outdated website with poor mobile responsiveness and slow loading speeds. The checkout process was complicated, leading to high cart abandonment rates and lost opportunities for conversion.",
    result: "Thanks to the developer’s expertise, Macguire Shoes saw a 40% boost in sales within just three months. The site’s faster performance and enhanced checkout flow resulted in a 25% reduction in cart abandonment and a 30% increase in conversion rates.",
  },
  {
    id: 3,
    title: "Macguire Shoes",
    subcategory: "Macguire Shoes faced low online sales due to an outdated website with poor mobile responsiveness and slow loading speeds. The checkout process was complicated, leading to high cart abandonment rates and lost opportunities for conversion.",
    solution: "Macguire Shoes faced low online sales due to an outdated website with poor mobile responsiveness and slow loading speeds. The checkout process was complicated, leading to high cart abandonment rates and lost opportunities for conversion.",
    result: "Thanks to the developer’s expertise, Macguire Shoes saw a 40% boost in sales within just three months. The site’s faster performance and enhanced checkout flow resulted in a 25% reduction in cart abandonment and a 30% increase in conversion rates.",
  },
  {
    id: 4,
    title: "Macguire Shoes",
    subcategory: "Macguire Shoes faced low online sales due to an outdated website with poor mobile responsiveness and slow loading speeds. The checkout process was complicated, leading to high cart abandonment rates and lost opportunities for conversion.",
    solution: "Macguire Shoes faced low online sales due to an outdated website with poor mobile responsiveness and slow loading speeds. The checkout process was complicated, leading to high cart abandonment rates and lost opportunities for conversion.",
    result: "Thanks to the developer’s expertise, Macguire Shoes saw a 40% boost in sales within just three months. The site’s faster performance and enhanced checkout flow resulted in a 25% reduction in cart abandonment and a 30% increase in conversion rates.",
  },
]);

</script>
