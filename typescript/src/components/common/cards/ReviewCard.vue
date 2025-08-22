<script setup lang="ts">
import { ref, watchEffect, computed } from "vue";
import ExternalLink from "../../../assets/icons/externalLink.svg";
import Pencil from "../../../assets/icons/pencil.svg";
import Star from "../../../assets/icons/star.svg";
import ShopexpertMini from "../../../assets/icons/shopexpert-mini.svg";
import Recurring from "../../../assets/icons/recurring.svg";
import Info from "../../../assets/icons/info-sm.svg";
import ReviewModal from "../../../components/client//modals/ReviewModal.vue";
import { useClientStore } from "@/store/client.ts";
import type { IReview } from "@/types.ts";
import { getS3URL } from "@/utils/helpers.ts";
import ReviewResponse from "@/components/expert/cards/ReviewResponse.vue";
import { useExpertStore } from "@/store/expert.ts";

const clientStore = useClientStore();
const expertStore = useExpertStore();

const props = defineProps<{
  review: IReview;
  isAdmin?: boolean;
  isClient?: boolean;
  isExpert?: boolean;
  isReviewRequest?: boolean;
  isWrittenReview?: boolean;
}>();

const showModal = ref(false);
const showResponseBox = ref(false);

// Check if expert already has a response
const hasExistingResponse = computed(() => {
  return props.review.response && props.review.response.trim().length > 0;
});

// Optional: pre-fill existing values (not required for modal since state is handled inside ReviewModal)
watchEffect(() => {
  if (props.isWrittenReview && props.review.reviewer?.comment) {
    // Modal will handle this inside itself
  }
});

async function handleModalSubmit(payload: any) {
  try {
    if (props.isWrittenReview && props.review.id) {
      await clientStore.updateReview(props.review.id, payload);
      console.log("Review updated!");
    } else {
      await clientStore.createReview(payload);
      console.log("Review created!");
    }
    showModal.value = false;
  } catch (error) {
    console.error("Review submission failed:", error);
  }
}

async function handleWriteResponse(payload: any) {
  try {
    if (props.isExpert && props.review.id) {
      await expertStore.updateReview(props.review.id, payload);
      console.log("Review updated!");
    }

    showResponseBox.value = false;
  } catch (error) {
    console.error("Review submission failed:", error);
  }
}
</script>

<template>
  <div
    class="mx-auto bg-card border rounded-md shadow-sm p-card-padding space-y-4 mb-4"
  >
    <!-- Header -->
    <div class="flex justify-between items-start">
      <div v-if="isAdmin || isExpert" class="flex items-center space-x-4">
        <img
          :src="getS3URL(review.reviewer.photo)"
          alt="Reviewer avatar"
          class="w-12 h-12 rounded-full object-cover"
        />
        <div>
          <div class="flex items-center space-x-2">
            <p class="font-normal">{{ review.reviewer.name }}</p>
            <Recurring v-if="review.reviewer.recurringClient" />
            <div
              v-if="review.reviewer.isShopexpertUser"
              class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-accent bg-accent-light text-sm font-medium text-gray-900"
            >
              <ShopexpertMini class="w-4 h-4" />
              Hired on shopexperts
            </div>
          </div>
          <a
            v-if="review.reviewer.storeTitle"
            :href="review.reviewer.storeUrl ? review.reviewer.storeUrl : '#'"
            class="flex text-link text-h4 hover:underline items-center gap-1"
          >
            {{ review.reviewer.storeTitle }}
            <ExternalLink />
          </a>
        </div>
      </div>
      <div v-else class="flex items-center space-x-4">
        <img
          :src="getS3URL(review?.expert?.photo)"
          alt="Expert avatar"
          class="w-12 h-12 rounded-full object-cover"
        />
        <div>
          <div class="flex items-center space-x-2">
            <p class="font-normal">{{ review.expert.name }}</p>
            <Recurring v-if="review.expert?.recurringExpert" />
            <div
              v-if="review.expert?.isShopexpertUser"
              class="flex items-center gap-1.5 px-3 py-1 rounded-full border border-accent bg-accent-light text-sm font-medium text-gray-900"
            >
              <ShopexpertMini class="w-5 h-5" />
              <span>Hired on shopexperts</span>
            </div>
          </div>
          <h4 class="text-primary">{{ review.expert?.rank }}</h4>
          <h4 class="text-primary">{{ review.expert?.company_type }}</h4>
        </div>
      </div>
      <div class="text-h5 font-light">
        <div class="justify-items-end space-y-2">
          <h5
            v-if="isAdmin"
            class="font-normal px-2 py-1 rounded-sm"
            :class="{
              'text-pending bg-pending-light':
                review.status === 'Pending Review',
              'text-success bg-success-light': review.status === 'Approved',
            }"
          >
            {{ review.status }}
          </h5>
          <h5>Requested: {{ review.postedAt }}</h5>
        </div>
      </div>
    </div>

    <!-- Body -->
    <div v-if="isClient && isReviewRequest" class="space-y-6">
      <div class="p-4 space-y-2 border border-grey rounded-md bg-greyLight">
        <p class="text-greyDark font-normal">Expert message</p>
        <p class="text-primary font-normal" v-html="review.response"></p>
      </div>
    </div>
    <div v-else>
      <div class="text-h4 flex items-center space-x-2 font-light mb-4">
        <span>Rating:</span>
        <span class="font-semibold">{{
          Number(review.reviewer.rating).toFixed(1)
        }}</span>
        <div class="flex space-x-1 text-accent">
          <template
            v-for="(_, i) in Math.floor(review.reviewer.rating)"
            :key="i"
          >
            <Star :index="i" />
          </template>
        </div>
      </div>

      <p class="font-light leading-relaxed mb-4">
        {{ review.reviewer.comment }}
      </p>

      <div class="text-h4 flex flex-wrap divide-x divide-grey font-light mb-6">
        <div class="pr-8">
          <h6 class="block font-normal mb-1">Likely to recommend:</h6>
          <h4 class="text-success font-medium">
            {{ review.reviewer.recommendation }}
          </h4>
        </div>
        <div class="px-8">
          <h6 class="block font-normal mb-1">Project Value</h6>
          <h4 class="font-medium">{{ review.projectValue }}</h4>
        </div>
        <div class="pl-8">
          <div class="flex items-center gap-1 mb-1">
            <h6 class="block font-normal">Review Source</h6>
            <Info />
          </div>
          <h4 class="font-medium">{{ review.reviewSource }}</h4>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div v-if="isAdmin" class="pt-6 border-t border-grey flex justify-between">
      <div class="flex items-start gap-2">
        <img
          :src="review.expert.photo"
          alt="Reviewer avatar"
          class="w-12 h-12 rounded-full object-cover"
        />
        <div>
          <h4 class="text-tertiary">Expert</h4>
          <p class="text-primary font-medium">{{ review.expert.name }}</p>
          <a
            :href="review.expert.storeUrl"
            class="flex text-link text-h4 hover:underline items-center gap-1"
          >
            {{ review.expert.storeTitle }}
            <ExternalLink />
          </a>
        </div>
      </div>
      <div class="flex items-center space-x-4">
        <div v-if="review.status === 'Pending Review'" class="space-x-4">
          <button
            class="rounded-sm bg-primary text-h4 text-white border py-1 px-2"
          >
            Approve Review
          </button>
          <button
            class="rounded-sm bg-white text-h4 text-primary border py-1 px-2"
          >
            Decline Review
          </button>
        </div>
        <div v-else>
          <button
            class="rounded-sm bg-white text-h4 text-primary border py-1 px-2"
          >
            Hide Review
          </button>
        </div>
        <button class="flex gap-2 text-h4 hover:underline">
          <Pencil />
          Edit Review
        </button>
      </div>
    </div>

    <!-- Review toggle button -->
    <div v-if="isClient" class="pt-4 border-t border-grey">
      <button
        @click="showModal = true"
        class="flex items-center space-x-2 font-medium text-h4 hover:underline"
      >
        <Pencil />
        <span>{{
          props.isWrittenReview ? "Edit Your Review" : "Write Your Review"
        }}</span>
      </button>

      <ReviewModal
        v-if="showModal"
        :review="props.review"
        :isEdit="props.isWrittenReview"
        @close="showModal = false"
        @submit="handleModalSubmit"
      />
    </div>

    <!-- Expert response section -->
    <div v-if="isExpert" class="pt-4 border-t border-grey">
      <!-- Show existing response if available -->
      <div v-if="hasExistingResponse && !showResponseBox" class="mb-4">
        <h6 class="block text-h4 font-medium text-greyExtraDark mb-2">
          Your Response
        </h6>
        <p class="text-primary font-light mb-4">{{ review.response }}</p>
      </div>

      <!-- Toggle button -->
      <button
        v-if="!showResponseBox"
        @click="showResponseBox = !showResponseBox"
        class="flex items-center space-x-2 font-medium text-h4 hover:underline"
      >
        <Pencil />
        <span>{{
          hasExistingResponse ? "Update Response" : "Write Your Response"
        }}</span>
      </button>

      <!-- Response form -->
      <ReviewResponse
        :response="props.review.response"
        :showResponseBox="showResponseBox"
        @submit="handleWriteResponse"
        @cancel="showResponseBox = false"
      />
    </div>
  </div>
</template>

<style scoped></style>
