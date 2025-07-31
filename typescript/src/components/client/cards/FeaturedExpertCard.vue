<!-- src/components/client/FeaturedExperts.vue -->
<template>
  <div class="flex flex-col gap-6">
    <div class="flex justify-between items-center">
      <h3 class="text-primary font-semibold">Featured Experts</h3>
      <a href="https://shopexperts.com/home-new" target="_blank" class="text-primary text-paragraph font-normal hover:underline">Check Experts Directory</a>
    </div>

    <LoadingCard :is-horizontal-direction="true" v-if="isLoading" />
    <EmptyDataPlaceholder title="Looks like there are no featured experts yet" v-else-if="experts?.length === 0"/>
    <div v-else class="border rounded-md shadow-sm bg-white">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <div
            v-for="expert in experts?.slice(0, 3)"
            :key="expert.id"
            class="border rounded-md bg-white shadow-sm p-4 hover:shadow-md transition flex flex-col justify-between"
        >

          <div class="flex flex-col gap-4">
            <div class="flex justify-between">
              <img :src="getS3URL(expert.photo)" alt="Avatar" class="w-16 h-16 rounded-full" />
              <div class="text-h4 text-right text-tertiary font-sm">
                <p>Starting price:</p>
                <h2 class="text-primary font-semibold">${{ expert.profile.hourly_rate }}</h2>
              </div>
            </div>
            <div class="flex flex-col gap-2">
              <h2 class="text-primary font-semibold">{{ expert.first_name }} {{ expert.last_name }}</h2>
              <h4 class="font-normal text-primary ">{{ expert.profile.role }}</h4>
              <h4 class="font-normal text-primary ">{{ expert.company_type }}</h4>
              <div class="flex items-center gap-2">
                <star class="w-5 h-5"/>
                <p class="font-semibold">{{expert?.reviews_stat?.rating}} <span class="text-tertiary font-normal"> ({{expert?.reviews_stat?.reviews_count}} reviews) </span></p>
              </div>
            </div>
            <!-- Service Categories -->
            <div class="flex flex-col gap-2">
              <h6 class="text-primary font-semibold">Service categories:</h6>
              <div v-if="expert?.service_categories?.length" class="flex flex-wrap gap-2">
              <h4
                  v-for="category in expert.service_categories"
                  :key="category"
                  class="px-3 py-1 rounded-full text-primary border"
              >
                {{ category }}
              </h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import Star from '@/assets/icons/star.svg'
import {computed} from "vue";
import type { IExpertt } from '@/types.ts'
import {getS3URL} from "@/utils/helpers.ts";
import LoadingCard from "@/components/common/LoadingCard.vue";
import {useLoaderStore} from "@/store/loader.ts";
import EmptyDataPlaceholder from "@/components/common/EmptyDataPlaceholder.vue";

const loader = useLoaderStore();
const isLoading = computed(() => loader.isLoadingState);


defineProps<{
  experts: IExpertt[]
}>()
</script>
