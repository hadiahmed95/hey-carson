<template>
  <main class="flex-1 p-6 overflow-y-auto bg-secondary font-light">
    <div class="flex flex-row mb-6 justify-between">
      <div>
        <h1>
          Your Leads
        </h1>
        <p class="mt-1">
          Stay on top of your leads easily and manage all your leads in one place.
          <br>Quickly track their progress and strategize how to turn them into clients.
        </p>
      </div>

      <button
          class="flex bg-primary text-white font-light rounded-md h-fit py-2 px-4 items-center gap-2"
          @click="showInviteLeadModal = true"
      >
        <span>Invite Leads</span>
        <Arrow />
      </button>

<!--      <AppButton-->
<!--          title="Invite Leads"-->
<!--          icon-position="right"-->
<!--          :icon="Arrow"-->
<!--          size="md"-->
<!--          rounded="md"-->
<!--          variant="primary"-->
<!--      />-->
    </div>

    <TabNav
        :tabs="tabs"
    />

    <BaseModal
      v-if="showInviteLeadModal"
      @close="showInviteLeadModal = false"
      title="Invite Your Lead"
      description="Send a personalized invitation so your leads can join shopexperts and streamline your project."
      :form="InviteLeadForm" />
  </main>
</template>

<script setup lang="ts">
import LatestLeads from '../../components/expert/LatestLeads.vue'
import LatestReviews from "../../components/expert/LatestReviews.vue";
import TabNav from "../../components/TabNav.vue";
import Arrow from '../../assets/icons/arrow.svg';
import { ref } from "vue";
import InviteLeadForm from "../../components/expert/forms/InviteLeadForm.vue";
import BaseModal from "../../components/expert/BaseModal.vue";

const showInviteLeadModal = ref(false)

const tabs = [
  { value: 'all-leads', label: 'All Leads', component: LatestLeads },
  {
    value: 'quote-requests',
    label: 'Quote Requests',
    component: LatestLeads,
    props: {
      isQuoteRequest: true,
    }
  },
  {
    value: 'direct-messages',
    label: 'Direct Messages',
    component: LatestLeads,
    props: {
      isDirectMessage: true,
    }
  },
  { value: 'invited-leads', label: 'Invited Leads', component: LatestReviews },
]
</script>
