<script setup lang="ts">
import PaymentCard from '@/components/client/cards/PaymentCard.vue'
import { ref } from "vue";
import AddCreditCardModal from "@/components/client/modals/AddCreditCardModal.vue";
import FormStatusMessage from "@/components/common/FormStatusMessage.vue";
import {useClientStore} from "@/store/client.ts";
import {useExpertStore} from "@/store/expert.ts";
import type {Card} from "@/types.ts";

const clientStore = useClientStore();
const expertStore = useExpertStore();

const props = defineProps<{
  store: {
    user: {
      saved_cards: Array<Card>
    }
  },
  userType?: string
}>()

const triggerCounters = ref({
  card: 0,
});

const toggleAddCreditCard = ref(false)

const status = ref<string | null>(null);
const message = ref<string | null>(null);

const resetStatus = () => {
  status.value = null;
  message.value = null;
};

const handleDelete = async (cardId: number) => {
  try {
    let result;
    if (props.userType === 'expert') {
      result = await expertStore.deleteCard(cardId);
    } else {
      result = await clientStore.deleteCard(cardId);
    }

    if (result.success) {
      if (props.userType === 'expert') {
        await expertStore.fetchExpert();
      } else {
        await clientStore.fetchClient();
      }

      status.value = 'success';
      message.value = 'Card deleted successfully.';
    } else {
      status.value = 'error';
      message.value = 'Failed to delete card.';
    }

    triggerCounters.value.card++;
  } catch (e) {
    status.value = 'error';
    message.value = 'Something went wrong.';
    triggerCounters.value.card++;
  }
}

const handleSetPrimary = async (cardId: number) => {
  try {
    let result;
    if (props.userType === 'expert') {
      result = await expertStore.setDefaultCard(cardId);
    } else {
      result = await clientStore.setDefaultCard(cardId);
    }

    if (result.success) {
      status.value = 'success';
      message.value = 'Primary card updated.';
    } else {
      status.value = 'error';
      message.value = 'Failed to set primary card.';
    }
    triggerCounters.value.card++;
  } catch (e) {
    status.value = 'error';
    message.value = 'Something went wrong while updating the primary card.';
  }
};

const handleSaved = async () => {
  toggleAddCreditCard.value = false;
  if (props.userType === 'expert') {
    await expertStore.fetchExpert();
  } else {
    await clientStore.fetchClient();
  }
  status.value = 'success';
  message.value = 'Card added successfully.';

  triggerCounters.value.card++;
};
</script>


<template>
  <div class="p-6 bg-white rounded-md border border-gray-200 shadow-sm max-w-[45rem] w-full">
    <FormStatusMessage
        class="mb-4"
        :status="status"
        :trigger="triggerCounters.card"
        :message="message"
        @updateStatus="resetStatus"
    />
    <button @click="() => toggleAddCreditCard = true"
            class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm font-medium mb-4">
      Add Payment Method
    </button>

    <h5 class="text-gray-600 mb-6">
      At this moment, we support credit cards and PayPal.
    </h5>

    <div class="mb-4">
      <h5 class="font-medium text-gray-700 mb-1">Saved Cards:</h5>

      <div class="space-y-4" v-if="store.user">
        <PaymentCard
            v-for="card in store.user?.saved_cards"
            :key="card.id"
            :card="card"
            @delete="handleDelete"
            @setPrimary="handleSetPrimary"
        />
      </div>

      <AddCreditCardModal :userType="userType === 'expert' ? userType : undefined" @close="() => toggleAddCreditCard = false" @saved="handleSaved" v-if="toggleAddCreditCard" />
    </div>
  </div>
</template>
