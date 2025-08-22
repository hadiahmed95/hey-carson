<template>
  <div class="fixed inset-0 z-50 flex justify-end bg-black/50">
    <div class="flex flex-col gap-8 bg-white w-[550px] h-full overflow-y-auto shadow-xl p-8 relative">

      <!-- Header -->
      <div class="relative flex justify-start items-center pb-2">
        <h1 class="font-normal text-primary text-center">
          {{ isEdit ? 'Edit your' : 'Write your' }} <em class="font-besley">review</em>
        </h1>
        <button
            @click="$emit('close')"
            class="absolute right-0 text-gray-500 hover:text-black text-2xl font-light"
        >
          &times;
        </button>
      </div>

      <!-- Description -->
      <p class="font-normal text-primary">
        {{
          isEdit
              ? "Edit your review only if you made a mistake or if you think the initial review doesn’t accurately reflect your experience with this expert."
              : "This expert sent you a review request. Your feedback matters! Provide a rating and a detailed review to reflect your experience with this expert on the project you worked on in the past."
        }}
      </p>

      <!-- Review Requested Card -->
      <div class="flex flex-col gap-1">
        <h5 class="font-archivo text-tertiary-dark">Review requested from</h5>

        <div class="border border-grey rounded-lg p-4 flex flex-col gap-2">
          <!-- Expert Info -->
          <div class="flex items-start gap-3">
            <img
                :src="getS3URL(review?.expert?.photo)"
                alt="Expert avatar"
                class="w-14 h-14 rounded-full object-cover"
            />
            <div>
              <div class="flex items-center gap-2">
                <p class="font-semibold text-primary">{{ review.expert.name }}</p>
                <Recurring v-if="review.expert?.recurringExpert" />
                <div
                    v-if="review.expert?.isShopexpertUser"
                    class="flex items-center justify-center rounded-full border border-[#ACE46F] bg-[#F0FBE4] w-5 h-5"
                >
                  <ShopexpertMini class="w-4 h-4 text-[#69C200]" />
                </div>
              </div>
              <h4 class="text-primary">{{ review.expert?.rank }}</h4>
              <h4 class="text-primary">{{ review.expert?.company_type }}</h4>
            </div>
          </div>

          <div class="border-t text-grey my-4"></div>

          <!-- Project Info -->
          <div class="flex flex-col gap-1">
            <h5 class="text-primary">Project Name</h5>
            <p class="font-semibold text-primary">{{ review.projectTitle || 'N/A' }}</p>
            <h5 class="text-tertiary-dark font-archivo font-normal">
              This is the project name that the expert defined so you can have a reference.
            </h5>
          </div>
        </div>
      </div>

      <!-- Ratings -->
      <div class="flex flex-col gap-4">
        <div class="grid grid-cols-1 gap-4">
          <RatingInput label="Quality of Services" v-model="ratings.quality" />
          <RatingInput label="Communication" v-model="ratings.communication" />
          <RatingInput label="Turnaround Time" v-model="ratings.turnaroundTime" />
          <RatingInput label="Value for Money" v-model="ratings.valueForMoney" />
        </div>


        <div class="border-t"></div>

        <p v-if="errors.rating" class="text-red-600 text-sm">{{ errors.rating }}</p>
        <div class="flex justify-between items-center">
          <span class="text-h4 text-primary w-32">Total Score:</span>
          <h2 class="font-semibold text-primary">
            {{ totalScore }}
          </h2>
        </div>
      </div>

      <!-- Recommendation -->
      <div class="flex flex-col gap-2">
        <label class="block font-normal text-primary">
          How likely are you to recommend this expert to others?
        </label>
        <div class="flex rounded-md border border-gray overflow-hidden w-full">
          <button
              v-for="(option, index) in recommendationOptions"
              :key="option"
              @click="recommendation = option"
              :class="[
              'flex-1 text-center flex items-center justify-center h-10 text-sm font-semibold focus:outline-none',
              recommendation === option ? 'bg-success text-white' : 'bg-white text-primary',
              index !== recommendationOptions.length - 1 ? 'border-r border-gray-300' : '',
            ]"
          >
            {{ option }}
          </button>
        </div>
      </div>

      <!-- Comment -->
      <div class="flex flex-col gap-2">
        <label class="block text-h5 font-normal text-primary">Write Your Review</label>
        <textarea
            v-model="comment"
            rows="3"
            class="w-full border border-gray rounded-md p-3 focus:outline-none focus:ring focus:ring-lightGray"
            placeholder="Please try to be as detailed as possible. Share your experience with this expert and help us make your next match even better ..."
        ></textarea>
        <h5 class="text-tertiary-dark font-normal">
          This feedback is public and will be shared on the expert's profile.
        </h5>
        <p v-if="errors.comment" class="text-red-600 text-sm">{{ errors.comment }}</p>
      </div>

      <!-- Anonymous Checkbox -->
      <div class="flex items-center gap-2">
        <input
            type="checkbox"
            v-model="anonymous"
            class="h-4 w-4 rounded border-grey"
        />
        <div class="flex items-center gap-1 text-h4 font-normal text-primary">
          <label>Post as Anonymous</label>
          <Info class="w-4 h-4 text-gray-400" />
        </div>
      </div>

      <!-- Footer Buttons -->
      <p class="bg-primary text-white rounded-md py-2 font-semibold hover:bg-success hover:cursor-pointer"
         :class="[isLoading  && 'opacity-50 cursor-not-allowed']"
      >
        <Spinner v-if="isLoading" />
        <button class="flex w-full justify-center items-center gap-2"
          v-else
          @click="submit"
        >
          <span>Submit Review</span>
          <Arrow class="w-4 h-4" />
        </button>
      </p>

      <h5 class="text-tertiary-dark font-normal">
        This site is protected by reCAPTCHA and the Google Privacy Policy and Terms of Service apply.
      </h5>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import RatingInput from '../Inputs/RatingInput.vue'; // ✅ Fixed casing
import type { IReview } from '@/types';
import Recurring from '@/assets/icons/recurring.svg';
import ShopexpertMini from '@/assets/icons/shopexpert-mini.svg';
import Arrow from '@/assets/icons/arrow.svg';
import Info from '@/assets/icons/info1.svg';
import {getS3URL} from "@/utils/helpers.ts";
import {useLoaderStore} from "@/store/loader.ts";
import Spinner from "@/components/common/Spinner.vue";

const loader = useLoaderStore();
const isLoading = computed(() => loader.isLoadingState);

const props = defineProps<{
  review: IReview;
  isEdit?: boolean;
}>();

const emit = defineEmits(['close', 'submit']);

const comment = ref(props.review.reviewer?.comment || '');
const recommendation = ref(props.review.reviewer?.recommendation || '');
const anonymous = ref(false);

const errors = ref<Record<string, string>>({
  comment: '',
  rating: '',
});

const ratings = ref({
  quality: props.review.reviewer?.quality ?? 3,
  communication: props.review.reviewer?.communication ?? 3,
  turnaroundTime: props.review.reviewer?.timeToStart ?? 3,
  valueForMoney: props.review.reviewer?.valueForMoney ?? 3,
});

const totalScore = computed(() => {
  const values = [
    ratings.value.quality,
    ratings.value.communication,
    ratings.value.turnaroundTime,
    ratings.value.valueForMoney,
  ];

  const validValues = values.filter((v): v is number => typeof v === 'number');

  if (validValues.length === 0) return '0.00';

  const total = validValues.reduce((sum, val) => sum + val, 0);
  return (total / validValues.length).toFixed(2);
});

const recommendationOptions = ['Very Likely', 'Neutral', 'Not Very Likely'];

function submit() {
  errors.value = {};
  if (!comment.value.trim()) {
    errors.value.comment = 'Review comment is required.';
    return;
  }

  if (totalScore.value == '0.00') {
    errors.value.rating = 'Review rating is required.';
    return;
  }

  const payload = {
    expert_id: props.review.expert.id,
    project_id: props.review.projectId,
    comment: comment.value,
    rate: Number(totalScore.value),
    recommendation: recommendation.value,
    quality: ratings.value.quality,
    communication: ratings.value.communication,
    timeToStart: ratings.value.turnaroundTime,
    valueForMoney: ratings.value.valueForMoney,
    valueRange: anonymous.value ? 'anonymous' : '',
  };

  emit('submit', payload);
}
</script>

