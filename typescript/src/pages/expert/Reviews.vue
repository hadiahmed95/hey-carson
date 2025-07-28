<template>
  <main class="flex-1 p-6 overflow-y-auto bg-secondary font-light">
    <div class="flex flex-row mb-6 justify-between">
      <div>
        <h1>
          Reviews
        </h1>
        <p class="mt-1">
          Encourage past clients to share their feedback. Positive reviews and testimonials
          <br>are powerful social proof that can boost your profile’s attractiveness..
        </p>
      </div>

      <button
          class="flex bg-primary text-white font-light rounded-md h-fit py-2 px-4 items-center gap-2"
          @click="showRequestReviewModal = true"
      >
        <span>Request a Review</span>
        <Arrow />
      </button>
    </div>

    <TabNav
        :tabs="tabs"
    />

    <BaseModal
      v-if="showRequestReviewModal"
      @close="showRequestReviewModal = false"
      title="Request a Review"
      description="Send a personalized review request to your past client."
      :form="RequestReviewForm" />
  </main>
</template>

<script setup lang="ts">
import LatestReviews from "../../components/expert/LatestReviews.vue";
import TabNav from "../../components/TabNav.vue";
import InvitedList from "../../components/expert/InvitedList.vue";
import YourReviews from "../../components/expert/YourReviews.vue";
import Arrow from "../../assets/icons/arrow.svg";
import {ref} from "vue";
import BaseModal from "../../components/expert/BaseModal.vue";
import RequestReviewForm from "../../components/expert/forms/RequestReviewForm.vue";

const showRequestReviewModal = ref(false)

const tabs = [
  { value: 'your-reviews', label: 'Your Reviews', component: YourReviews },
  { value: 'invited-list', label: 'Invited List', component: InvitedList },
  { value: 'widgets', label: 'Widgets', component: LatestReviews },
]
</script>
