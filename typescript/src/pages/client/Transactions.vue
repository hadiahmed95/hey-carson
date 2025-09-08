<script setup lang="ts">
import {computed, onMounted, ref} from 'vue'
import Search from "../../assets/icons/search.svg"
import TransactionCard from "../../components/common/TransactionCard.vue";
import {useClientStore} from "@/store/client.ts";
import {useLoaderStore} from "@/store/loader.ts";
import LoadingCard from "@/components/common/LoadingCard.vue";
import EmptyDataPlaceholder from "@/components/common/EmptyDataPlaceholder.vue";

const clientStore = useClientStore();
const loader = useLoaderStore();
const isLoading = computed(() => loader.isLoadingState);

onMounted(async () => {
  await clientStore.fetchTransactions();
});

const searchQuery = ref('')

</script>

<template>
  <main class="flex-1 p-8 overflow-y-auto bg-gray-50 font-light space-y-8">
    <div class="flex justify-between items-center">
      <h1>
        Transactions
      </h1>
      <div class="flex items-center border border-grey rounded-sm bg-white py-1 px-3 w-[300px] max-w-md shadow-sm">
        <Search />
        <input
            type="text"
            placeholder="Search Transactions..."
            class="w-full ml-3 text-h4 outline-none placeholder-tertiary"
            v-model="searchQuery"
        />
      </div>
    </div>
    <LoadingCard v-if="isLoading" />
    <EmptyDataPlaceholder title="Looks like you don't have any transactions yet" v-else-if="clientStore.transactions.transactions?.length === 0"/>
    <div v-else class="space-y-4">
      <TransactionCard
          v-for="(transaction, index) in clientStore.transactions.transactions"
          :key="index"
          :transaction="transaction"
          :isAdmin="false"
      />
    </div>
  </main>
</template>
