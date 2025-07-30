<script setup lang="ts">
import PaymentCard from '../../client/cards/PaymentCard.vue'
import AddCreditCardModal from "@/components/client/modals/AddCreditCardModal.vue"
import { ref } from 'vue'

const props = defineProps({
  store: {
    type: Object,
    required: true
  }
})

const toggleAddCreditCard = ref(false)

const handleDelete = (cardId: number) => {
  props.store.deleteCard(cardId)
  props.store.fetchClient()
}

const handleSetPrimary = (cardId: number) => {
  props.store.setDefaultCard(cardId)
}
</script>


<template>
  <div class="mx-auto p-6 bg-white rounded-md border border-gray-200 shadow-sm w-[45rem]">
    <button @click="() => toggleAddCreditCard = true"
            class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm font-medium mb-4">
      Add Payment Method
    </button>

    <p class="text-h5 text-gray-600 mb-6">
      At this moment, we support credit cards and PayPal.
    </p>

    <div class="mb-4">
      <h3 class="text-h5 font-medium text-gray-700 mb-1">Saved Cards:</h3>

      <div class="space-y-4" v-if="store.user">
        <PaymentCard
            v-for="card in store.user?.saved_cards"
            :key="card.id"
            :card="card"
            @delete="handleDelete"
            @setPrimary="handleSetPrimary"
        />
      </div>

      <AddCreditCardModal
          @close="() => toggleAddCreditCard = false"
          @saved="() => { toggleAddCreditCard = false; store.fetchClient() }"
          v-if="toggleAddCreditCard"
      />
    </div>
  </div>
</template>
