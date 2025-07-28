<template>
  <div
      v-for="service in services"
      :key="service.id"
      class="border rounded-md bg-white p-4 shadow-sm hover:shadow-md transition flex flex-col gap-4"
  >
    <!-- Image + Add to Cart -->
    <div class="relative">
      <img
          :src="service.thumbnail"
          alt="Service Image"
          class="rounded-md w-full h-40 object-cover"
      />
      <button
          class="absolute inset-x-0 bottom-4 mx-auto w-fit bg-white text-primary font-medium rounded-full px-4 py-1 flex items-center shadow"
      >
        <CartIcon />
        Add to Cart
      </button>
    </div>

    <!-- Title + Delivery Time -->
    <div class="flex flex-col gap-2 min-h-[78px]">
      <h3 class="text-primary font-semibold leading-snug">
        {{ service.title }}
      </h3>
      <h4 class="text-gray-600">{{ service.delivery_time }}</h4>
    </div>

    <!-- Price Section -->
    <div class="flex flex-col gap-2">
      <h4 class="font-archivo text-tertiary">Starting price:</h4>
      <h3 class="text-primary font-semibold">${{ service.price }}</h3>
    </div>
  </div>
</template>
<script setup lang="ts">
import CartIcon from "@/assets/icons/cart.svg"
import type { IPackagedService } from "@/types.ts"

defineProps<{
  services: IPackagedService[]
}>()
</script>
