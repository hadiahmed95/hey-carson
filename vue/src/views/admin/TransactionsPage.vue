<script>
import AdminLayout from "@/layout/AdminLayout.vue";
import LoadingCards from "@/components/cards/LoadingCards.vue";
import TransactionCard from "@/components/cards/admin/TransactionCard.vue";
import axios from 'axios';
import emptyState from "@/assets/empty-state.png";
import LoadingSpinner from '@/components/misc/LoadingSpinner.vue';

export default {
  name: "TransactionPage",

  components: {
    LoadingSpinner,
    LoadingCards,
    AdminLayout,
    TransactionCard,
  },

  data() {
    return {
      emptyState,

      isMobile: screen.width <= 760,

      transactions: [],
      pageLoading: false,
      spinnerLoading: false,
      pageCount: 1,
      lastPage: 1,
    }
  },

  async mounted() {
    await this.getTransactions()
  },

  methods: {
    async getTransactions() {
      this.pageLoading = true
      this.spinnerLoading = true
      await axios.get(`/api/admin/transactions?page=${this.pageCount}`).then(res => {
        this.transactions.push(...res.data.transactions.data)
        this.pageCount += 1
        this.lastPage = res.data.transactions.last_page

      }).catch(err => {
        console.log(err)
      }).finally(() => {
        this.pageLoading = false
        this.spinnerLoading = false
      })
    },

    loadMore() {
      if (this.pageCount > this.lastPage)
        return

      if (this.transactions.length === 0)
        return

      this.getTransactions()
    }
  }
}
</script>

<template>
  <AdminLayout v-infinite-scroll="{ threshold: 1, callback: loadMore }" class="scrollable-container">
    <Page :style="isMobile ? {padding: '32px 0'} : {padding: '56px 0'}">
      <template #pageTitle>
        <Text variant="headingLg" as="p">Transactions</Text>
      </template>

      <div :style="isMobile ? {padding: '0 16px'} : null">
        <BlockStack gap="200" v-if="transactions.length">
          <TransactionCard v-for="transaction in transactions" :key="transaction.id" :item="transaction" />
        </BlockStack>

        <LoadingCards gap="300" v-else-if="pageLoading" />

        <BlockStack gap="200" v-else>
          <Card>
            <EmptyState
                heading="No transactions found"
                :image="emptyState"
            >
              <p>Currently there are no transactions with selected status</p>
            </EmptyState>
          </Card>
        </BlockStack>
      </div>
    </Page>
  </AdminLayout>
  <LoadingSpinner :isLoading="spinnerLoading" />
</template>

<style scoped>
.scrollable-container {
  height: 100vh;
  overflow-y: auto;
}
</style>