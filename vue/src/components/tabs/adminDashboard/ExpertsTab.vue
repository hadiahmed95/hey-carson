<script>
import ExpertsCard from "@/components/cards/admin/ExpertsCard.vue";
import emptyState from "@/assets/empty-state.png";
export default {
  name: "ExpertsTab",

  props: {
    experts: {
      default: () => [],
      type: Array,
    },
  },

  components: {
    ExpertsCard
  },

  data() {
    return {
      emptyState,
    }
  },

  methods: {

  }
}
</script>

<template>
  <BlockStack gap="300" v-if="experts.length">
    <ExpertsCard :expert="expert" v-for="expert in experts" :key="expert.id" />
  </BlockStack>

  <BlockStack gap="200" v-else>
    <Card>
      <EmptyState
          heading="No clients found"
          :image="emptyState"
      >
        <p>We couldn't find any Expert that match search criteria</p>
      </EmptyState>
    </Card>
  </BlockStack>
</template>

<style scoped>

</style>