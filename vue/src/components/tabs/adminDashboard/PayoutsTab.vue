<script>
import PayoutsCard from "@/components/cards/admin/PayoutsCard.vue";
import emptyState from "@/assets/empty-state.png";
export default {
  name: "PayoutsTab",

  props: {
    payouts: {
      default: () => [],
      type: Array,
    },
  },

  components: {
    PayoutsCard
  },

  data() {
    return {
      emptyState,
    }
  },

  methods: {
    refresh() {
      this.$emit('refresh')
    }
  }
}
</script>

<template>
  <BlockStack gap="300" v-if="payouts.length">
    <PayoutsCard v-for="payout in payouts" :key="payout.id" :payout="payout" @refresh="refresh"/>
  </BlockStack>

  <BlockStack gap="200" v-else>
    <Card>
      <EmptyState
          heading="No payouts found"
          :image="emptyState"
      >
        <p>Currently there aren't any payouts</p>
      </EmptyState>
    </Card>
  </BlockStack>
</template>

<style scoped>

</style>