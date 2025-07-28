<script setup lang="ts">
defineProps({
  content: {
    type: Object,
    required: true
  }
})
</script>


<template>
  <div class="w-full bg-card border border-grey rounded-lg p-card-padding">
    <!-- Header Section -->
    <div class="flex items-center gap-4 mb-6">
      <!-- PayPal Icon -->
      <div class="rounded-full overflow-hidden flex-shrink-0">
        <component :is="content.icon" class="text-sm" />
      </div>

      <!-- Header Text -->
      <div>
        <p class="text-primary font-semibold text-">{{ content.title }}</p>
        <h4 class="text-greyDark mt-1">{{ content.description }}</h4>
      </div>
    </div>

    <!-- Form Section -->
    <div class="space-y-4">
      <h5 class="block text-primary">
        {{ content.emailLabel }}
      </h5>

      <input
          type="email"
          :placeholder="content.emailPlaceholder"
          class="w-full px-4 py-2 border border-grey rounded-md bg-white text-greyDark text-paragraph placeholder-greyDark focus:outline-none focus:ring-1 focus:ring-darkGray focus:border-darkGray"
      />

      <p class="text-greyDark text-custom1 mt-3">
        {{ content.feeNote }}
      </p>
    </div>
  </div>
</template>