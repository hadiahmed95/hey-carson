<script>
import AdminLayout from "@/layout/AdminLayout.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import TransactionCard from "@/components/cards/TransactionCard.vue";
export default {
  name: "TransactionsPage",

  components: {
    AdminLayout,
    TransactionCard
  },

  data() {
    return {
      SearchIcon,

      search: '',
    }
  }
}
</script>

<template>
  <AdminLayout>
    <Page style="padding-top: 56px; padding-bottom: 56px"
          title="Transactions"
    >
      <template #primaryAction>
        <InlineStack gap="200">
          <TextField
              style="min-width: 220px"
              :label="null"
              type="text"
              v-model="search"
              autoComplete="off"
              placeholder="Search transactions ..."
          >
            <template #prefix>
              <Icon :source="SearchIcon" />
            </template>
          </TextField>
        </InlineStack>
      </template>

      <BlockStack gap="300">
        <TransactionCard expert quote hours="10" />
        <TransactionCard expert scope hours="5" />
        <TransactionCard hours="25" />
        <TransactionCard hours="50" />
        <TransactionCard expert quote hours="20" />
        <TransactionCard expert scope hours="5" />
        <TransactionCard expert scope hours="10" />
      </BlockStack>
    </Page>
  </AdminLayout>
</template>

<style scoped>

</style>