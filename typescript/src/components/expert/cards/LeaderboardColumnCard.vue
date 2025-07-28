<script setup lang="ts">
import Star from '../../../assets/icons/star.svg'
import type { ILeaderboard } from "../../../types.ts";

defineProps<{
  title: string,
  leaders: ILeaderboard[],
  isTime?: boolean
}>()
</script>

<template>
  <div>
    <p class="text-white  font-normal px-4 py-2 rounded-t-sm bg-primary">
      {{ title }}
    </p>
    <div class="bg-white border border-grey rounded-b-sm p-4">
      <div
          v-for="(person, index) in leaders"
          :key="index"
          class="flex items-center gap-4 p-4 mb-4 border  rounded-sm hover:bg-gray-100 text-paragraph"
      >
        <div class="text-center font-semibold w-6">
          <span
            :class="[
              'w-8 h-8 rounded-full border flex items-center justify-center',
              person.rank === 1
                ? 'bg-[linear-gradient(45deg,#B78D19,#FFCF4A,#B78D19)]'
                : person.rank === 2
                ? 'bg-[linear-gradient(45deg,#6E6E6E,#CCCBCA,#6E6E6E)]'
                : person.rank === 3
                ? 'bg-[linear-gradient(45deg,#633302,#FAB89C,#633302)]'
                : 'bg-transparent'
            ]"
          >
            {{ person.rank }}
          </span>
        </div>
        <img :src="person.avatar" alt="avatar" class="w-16 h-16 rounded-full object-cover border" />
        <div class="flex-1">
          <p class="font-semibold">{{ person.name }}</p>
          <h4>{{ person.role }}</h4>
        </div>
        <h4 v-if="!isTime" class="flex items-center">
          <Star />
          <span class="font-bold text-paragraph">{{ person.rating }}</span>
          <span class="text-gray-400 ml-1">({{ person.reviews }} reviews)</span>
        </h4>
        <h4 v-else>
          Response Time: <span class="font-semibold text-paragraph">{{ person.responseTime }}</span>
        </h4>
      </div>
    </div>
  </div>
</template>

