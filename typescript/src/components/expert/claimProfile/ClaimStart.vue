<template>
  <StepPanel>
    <div class="flex flex-col gap-8">
      <div class="flex flex-col gap-4">
        <div class="flex justify-between items-center">
          <h4 class="font-normal font-archivo text-primary tracking-10p">
            Claim Directory Listing
          </h4>
          <h4 class="font-normal font-archivo text-primary">1/2</h4>
        </div>
        <h1 class="font-normal text-primary">
          <i>Claim listing,</i> get full control and start attracting high-quality leads.
        </h1>
      </div>

      <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-1">
          <h4 class="font-normal text-primary">Directory listing you want to claim</h4>
          <div class="border border-lightGray rounded-md p-4 flex items-center gap-4">
            <img src="../../../assets/icons/prefferedExpert.png" class="w-12 h-12 rounded-full object-cover" />
            <div>
              <p class="font-archivo font-semibold">Axome</p>
              <h4 class="text-primary font-normal">Agency</h4>
            </div>
          </div>
          <h5 class="font-normal font-archivo text-tertiary">
            Only the company owner or an authorized employee can claim this listing.
          </h5>
        </div>

        <div class="flex flex-col gap-4">
          <div class="gap-1">
            <h5 class="font-semibold text-primary">Just a few reasons to claim your profile:</h5>
            <div class="border-t border-gray-200"></div>
          </div>
          <ul class="flex flex-col gap-6">
            <li class="flex items-start gap-4 text-paragraph text-primary">
              <img src="../../../assets/icons/claimProfile1.png" class="w-6 h-6 mt-1" />
              Complete profile with company details and contact information.
            </li>
            <li class="flex items-start gap-4 text-paragraph text-primary">
              <img src="../../../assets/icons/claimProfileQuote.png" class="w-6 h-6 mt-1" />
              Enable instant quote requests directly from your listing.
            </li>
            <li class="flex items-start gap-4 text-paragraph text-primary">
              <img src="../../../assets/icons/claimProfileStar.png" class="w-6 h-6 mt-1" />
              Build trust by collecting and displaying client reviews.
            </li>
            <li class="flex items-start gap-4 text-paragraph text-primary">
              <img src="../../../assets/icons/claimProfileService.png" class="w-6 h-6 mt-1" />
              Offer packaged services for quick, turn-key projects.
            </li>
            <li class="flex items-start gap-4 text-paragraph text-primary">
              <img src="../../../assets/icons/claimProfileStory.png" class="w-6 h-6 mt-1" />
              Showcase your best work with client success stories.
            </li>
          </ul>
        </div>

        <div class="flex flex-col gap-2">
          <BaseButton :isPrimary="true" @click="proceed">Claim This Listing</BaseButton>
          <BaseButton :isPrimary="false" @click="goBackToWebsite">Back to Website</BaseButton>
        </div>
      </div>
    </div>
  </StepPanel>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import BaseButton from '../../../components/common/InputFields/BaseButton.vue'
import StepPanel from './StepPanel.vue'

const router = useRouter()

const proceed = () => {
  router.push('/expert/claim-profile/verify')
}

const goBackToWebsite = () => {
  window.location.href = '/'
}
</script>