<template>
  <div class="flex flex-col gap-8 w-[53.6875rem]">
    <div class="flex flex-col gap-3">
      <div class="flex flex-row justify-between items-start">
        <h2 class="font-semibold text-primary"><span class="italic font-besley">Let’s complete your listing!</span><br> You’re now an official shopexperts member.</h2>
        <button class="text-paragraph font-semibold px-4 py-2 bg-primary text-white rounded-md flex items-center gap-2">
          <Star/>
          Upgrade to PRO
        </button>
      </div>
      <p class="font-archivo font-normal">Your profile will be featured in our expert directory. Fill in all the profile details to <br> make sure it’s appealing and highlights your best skills and services!</p>
    </div>
    <PersonalAndBusinessDetails :isOnboarding="false" />
  </div>
</template>

<script setup lang="ts">
import PersonalAndBusinessDetails from "../../../components/expert/PersonalAndBusinessDetails.vue";
import Star from "../../../assets/icons/shopexpertsStar.svg";
</script>
