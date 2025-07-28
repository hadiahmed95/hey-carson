<script>
import ClientsCard from "@/components/cards/admin/ClientsCard.vue";
import emptyState from "@/assets/empty-state.png";
export default {
  name: "ClientsTab",

  props: {
    clients: {
      default: () => [],
      type: Array,
    },
  },

  components: {
    ClientsCard
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
  <BlockStack gap="300" v-if="clients.length">
    <ClientsCard v-for="client in clients" :key="client.id" :client="client" />
  </BlockStack>

  <BlockStack gap="200" v-else>
    <Card>
      <EmptyState
          heading="No clients found"
          :image="emptyState"
      >
        <p>We couldn't find any Client that match search criteria</p>
      </EmptyState>
    </Card>
  </BlockStack>
</template>

<style scoped>

</style>