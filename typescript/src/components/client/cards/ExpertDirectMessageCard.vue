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
      class="block border border-grey rounded-md bg-white p-4 mb-4 hover:shadow-md transition"
  >
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center bg-[#dcbcff] text-[#633f8a] rounded-sm px-1">
        <p class="font-semibold text-custom1 ">
          Direct Message
        </p>
      </div>
      <h5 class="text-tertiary font-normal">Submitted on {{ formatDate(request.created_at) }}</h5>
    </div>
    <div>
      <div class="flex justify-between gap-4">
        <div class="flex items-start gap-4">
          <img
              :src="getS3URL(request.expert.photo)"
              alt="Avatar"
              class="w-16 h-16 rounded-full"
              @error="handleImgError"
          />
          <div class="flex flex-wrap items-center gap-4 text-h4">
            <div class="flex flex-col">
              <div class="flex items-center gap-2">
                <p class="text-primary font-medium">{{ request.expert.first_name }} {{ request.expert.last_name }}</p>
<!--                <h5-->
<!--                    v-if="expert.pendingQuote"-->
<!--                    class="font-medium px-2 py-0.5 rounded-sm"-->
<!--                    :class="{-->
<!--                  'text-pending bg-pending-light': expert.pendingQuote === 'Pending',-->
<!--                  'text-success bg-success-light': expert.pendingQuote === 'Active',-->
<!--                  'text-link bg-link-light': expert.pendingQuote === 'Submitted'-->
<!--                }"-->
<!--                >-->
<!--                  {{ expert.pendingQuote }}-->
<!--                </h5>-->
              </div>
              <h4 class="font-normal text-primary ">{{ request.expert.company_type }}</h4>
              <h4 class="text-primary font-normal">{{ request.expert.profile.role }}</h4>
              <div class="flex items-center gap-2">
                <star/>
                <p class="font-semibold">{{request.expert?.reviews_stat?.rating}} <span class="text-tertiary font-normal"> ({{request.expert?.reviews_stat?.reviews_count}} reviews) </span></p>
              </div>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-center gap-2 mt-4">
<!--          <select v-model="status" class="border rounded px-1 w-36 py-2 text-h4 hover:bg-gray-100">-->
<!--            <option value="In Progress">In Progress</option>-->
<!--            <option value="Completed">Completed</option>-->
<!--            <option value="Closed">Closed</option>-->
<!--          </select>-->
          <router-link
              to=""
              @click.stop
              class="px-4 py-2 border rounded text-h4 flex items-center gap-2 opacity-60"
              :class="{
                  'text-black bg-white hover:bg-gray-100': request?.pendingQuote === 'Submitted',
                  'text-white bg-primary hover:bg-gray-800': request?.pendingQuote !== 'Submitted',
                }"
          >

            <Chat v-if="request?.pendingQuote !== 'Submitted'" />
            <ChatBlack v-else />
            <span>Chat Now</span>
          </router-link>
          <router-link v-if="request?.pendingQuote === 'Submitted'"
                       to=""
                       class="px-4 py-2 rounded text-h4 flex items-center gap-2 text-white bg-primary hover:bg-gray-800 opacity-60"
          >
            <span>Accept Quote</span>
          </router-link>
        </div>
      </div>
    </div>
  </router-link>
</template>

<script setup lang="ts">
import Chat from '@/assets/icons/chat.svg';
import ChatBlack from '@/assets/icons/chatBlack.svg';
import Star from '@/assets/icons/star.svg'
import type {IRequest} from "@/types.ts";
import {formatDate} from "@/utils/date.ts";
import {getS3URL, handleImgError} from "@/utils/helpers.ts";

defineProps<{
  request: IRequest
}>()

// const status = ref(props.expert.initialStatus || 'In Progress')

</script>