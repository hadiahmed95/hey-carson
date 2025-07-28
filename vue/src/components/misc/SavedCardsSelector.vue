<script>
import CreditCardCard from "@/components/cards/CreditCardCard.vue";

export default {
  name: "SavedCardsSelector",

  props: {
    savedCards: {
      default: () => [],
      type: Array
    },

    selectCard: {
      default: false,
      type: Boolean
    },
  },

  components: {CreditCardCard},
}
</script>

<template>
  <BlockStack gap="200" v-if="savedCards">
    <CreditCardCard :selectCard="selectCard"
                    :card="card"
                    v-for="card in savedCards"
                    :key="card.id"
                    @selectCard="() => this.$emit('selectCard', card)"
                    @deleteCard="() => this.$emit('deleteCard', card)" />
  </BlockStack>
</template>

<style scoped>

</style>