<template>
  <router-link
    :to="{
      name: 'RequestDetails',
      params: {
        requestId: request.id
      },
      query: {
        type: request.type
      }
    }"
    class="block border rounded-md shadow-sm bg-white mb-4 p-card-padding hover:shadow-md transition"
  >
    <div class="flex items-center justify-between mb-1">
      <div class="flex items-center bg-linkBlue text-[#176593] rounded-sm px-1">
        <p class="font-semibold text-custom1 ">
          Quote request
        </p>
      </div>
      <h5 class="text-gray-500 font-normal">Submitted on {{ formatDate(request.created_at)}}</h5>
    </div>
    <h3 class="mb-4 font-semibold">
      {{ request.project.name }}
    </h3>
    <div>
      <ExpertCard
        :expert="request.expert"
        :project-status="request.project.status"
        :offers="request.project?.active_assignment?.offers || request?.expert?.quotes || []"
        :request_type="request.type"
        class="flex flex-col gap-3"
      />
    </div>
    <div v-if="request.project.additional_expert_profiles">
      <h6 class="tracking-widest text- font-normal">
        {{ request.project.additional_expert_profiles?.length || 0 }} ADDITIONAL EXPERTS
      </h6>
      <div class="border-t w-full mb-1"></div>

      <div v-if="request.project.additional_expert_profiles?.length">
        <ExpertCard
          v-for="expert in request.project.additional_expert_profiles"
          :key="expert.id"
          :expert="expert"
          :project-status="request.project.status"
          :offers="request.project?.active_assignment?.offers || request?.expert?.quotes || []"
          :request_type="request.type"
          class="flex flex-col gap-3"
        />
      </div>
    </div>
  </router-link>
</template>
<script setup lang="ts">
import ExpertCard from "./ExpertCard.vue";
import type {IRequest} from "@/types.ts";
import {formatDate} from "@/utils/date";

defineProps<{
  request: IRequest
}>()
</script>