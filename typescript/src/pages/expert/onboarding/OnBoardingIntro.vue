<template>
  <div class="min-h-screen w-screen flex items-start justify-center px-4 pt-5">
    <div class="flex flex-col gap-8 border border-gray rounded-xl shadow p-8 w-[30%]">

      <!-- Heading -->
      <div class="flex flex-col gap-3">
        <div>
          <h2 class="font-semibold italic font-besley text-primary">Congratulations!</h2>
          <h2 class="font-semibold text-primary">You’re now an official shopexperts member.</h2>
        </div>
        <p class="font-normal text-primary">
          After these steps your profile will be visible inside experts directory. <br />
          Follow the steps to improve your visibility and credibility.
        </p>
      </div>

      <!-- Checklist -->
      <ul class="flex flex-col gap-6">
        <li class="flex items-start gap-4">
          <img src="../../../assets/icons/onBoardProfile.png" alt="Profile" class="w-6 h-6" />
          <p>Upload high-quality <span class="font-semibold">profile image</span>.</p>
        </li>
        <li class="flex items-start gap-4">
          <img src="../../../assets/icons/onBoardBusinessAddress.png" alt="Address" class="w-6 h-6" />
          <p>Add your physical <span class="font-semibold">business address</span>.</p>
        </li>
        <li class="flex items-start gap-4">
          <img src="../../../assets/icons/onBoardLink.png" alt="Links" class="w-6 h-6" />
          <p>Include all <span class="font-semibold">other links</span> (social media links).</p>
        </li>
        <li class="flex items-start gap-4">
          <img src="../../../assets/icons/onBoardRegion.png" alt="Regions" class="w-6 h-6" />
          <p>
            Specify your <span class="font-semibold">regions served</span> and
            <span class="font-semibold">languages</span> spoken.
          </p>
        </li>
        <li class="flex items-start gap-4">
          <img src="../../../assets/icons/claimProfileService.png" alt="Package" class="w-6 h-6" />
          <p>Create your first <span class="font-semibold">packaged service</span>.</p>
        </li>
      </ul>

      <!-- Locked Info Box -->
      <div class="bg-veryLightGray border border-primary rounded-md p-3 flex flex-col gap-3">
        <div class="flex flex-col gap-2">
          <div class="flex items-center justify-between">
            <h4 class="font-semibold text-primary">
              Why some fields are locked inside your listing?
            </h4>
            <h5 class="flex items-center gap-2 bg-primary text-white rounded-md px-3 py-1 font-normal">
              <Lock />
              <span class="text-h6 font-semibold">LOCKED</span>
            </h5>
          </div>
          <h4 class="text-darkGray">
            Some fields are only available to Premium members. <br />
            Upgrade to shopexperts Premium to unlock every option and showcase your full capabilities and experience.
          </h4>
        </div>
        <a href="#" class="text-primary font-semibold underline hover:text-primary/80 transition">
          Upgrade to Pro ($499/year)
        </a>
      </div>

      <!-- Button and Footer -->
      <div class="flex flex-col gap-6">
        <BaseButton @click="goto" :is-primary="true">Complete Your Listing</BaseButton>
        <p class="text-primary font-normal text-center">
          Have a question?
          <a href="#" class="font-normal text-brandBlue hover:underline">Send us an email.</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import BaseButton from "../../../components/common/InputFields/BaseButton.vue";
import Lock from "../../../assets/icons/lock-white.svg";
import { useRouter } from 'vue-router'
const router = useRouter()

const goto = () => {

  router.push('/expert/onboarding-steps')
}
</script>