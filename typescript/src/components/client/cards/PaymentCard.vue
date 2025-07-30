<script setup lang="ts">
import { defineProps, defineEmits } from 'vue'
import {formatDate} from "@/utils/date.ts";
import type {Card} from "@/types.ts";

defineProps<{
  card: Card
}>()

const emit = defineEmits<{
  (e: 'delete', cardId: number): void
  (e: 'setPrimary', cardId: number): void
}>()
</script>


<template>
  <div class="flex items-center justify-between p-4 border border-gray-200 rounded-md">
    <!-- Left Side: Logo and Card Info -->
    <div class="flex items-center space-x-4">
      <!-- Card Logo -->
      <div class="w-12 h-8 flex items-center justify-center">
        <div v-if="card.card_type === 'mastercard'" class="w-8 h-5 bg-red-500 rounded-sm flex items-center justify-center">
          <div class="w-6 h-4 bg-orange-400 rounded-sm opacity-80"></div>
        </div>
        <div v-else-if="card.card_type === 'visa'" class="w-10 h-6 bg-blue-600 rounded-sm flex items-center justify-center text-white text-3xs font-bold">
          VISA
        </div>
      </div>

      <!-- Card Details -->
      <div class="text-h4">
        <div class="flex items-center space-x-2 mb-1">
          <span class="font-medium">{{ card.card_type }} ({{ card.last_digits }})</span>
          <span v-if="card.default" class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-sm font-medium">
            Primary
          </span>
        </div>
        <p class="text-h4">Exp. date: {{ card.exp_date }}</p>
        <p class="text-coolGray text-h4">Last time used: {{ formatDate(card.last_used) }}</p>
      </div>
    </div>

    <!-- Right Side: Action Buttons -->
    <div class="flex items-center space-x-2">
      <button
          v-if="!card.default"
          @click="emit('setPrimary', card.id)"
          class="px-3 py-1 text-h6 border border-gray-300 rounded-sm hover:bg-greyLight"
      >
        Set as Primary
      </button>
      <button
          @click="emit('delete', card.id)"
          class="px-3 py-1 text-h6 border border-gray-300 rounded-sm hover:bg-greyLight"
      >
        Delete
      </button>
    </div>
  </div>
</template>
