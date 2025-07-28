<script setup lang="ts">
import type {ITranscationn} from '@/types.ts'
import { ref } from 'vue'
import Search from "../../assets/icons/search.svg"
import TransactionCard from "../../components/common/TransactionCard.vue";

const searchQuery = ref('')

const transactions = ref<ITranscationn[]>([
  {
    id: 1,
    type: 'Expert Premium Subscription',
    created_at: '10 Dec, 2025',
    total: 950.00,
    expert: {
      profile: {
        role: 'Freelancer'
      },
      first_name: 'Jerome',
      last_name: 'Bell',
      email: 'jeromebell@belldev.com',
      photo: 'https://randomuser.me/api/portraits/men/44.jpg',
    },
  },
  {
    id: 1,
    type: 'Project Quote',
    created_at: '10 Dec, 2025',
    total: 950.00,
    client: {
      first_name: 'Jacob',
      last_name: 'Jones',
      email: 'jacob@suta.co',
      photo: 'https://randomuser.me/api/portraits/men/33.jpg',
      shopify_plan: 'Advanced',
    },
    expert: {
      profile: {
        role: 'Freelancer'
      },
      first_name: 'Darrell',
      last_name: 'Steward',
      email: 'darrellsteward@gmail.com',
      photo: 'https://randomuser.me/api/portraits/men/55.jpg',
    },
  },
  {
    id: 1,
    type: 'Add to Scope',
    created_at: '10 Dec, 2025',
    total: 950.00,
    client: {
      first_name: 'Albert',
      last_name: 'Flores',
      email: 'albert@unitedbyblue.com',
      photo: 'https://randomuser.me/api/portraits/men/22.jpg',
      shopify_plan: 'Plus',
    },
    expert: {
      profile: {
        role: 'Freelancer'
      },
      first_name: 'Guy',
      last_name: 'Hawkins',
      email: 'markusrasedev@yahoo.com',
      photo: 'https://randomuser.me/api/portraits/men/41.jpg',
    },
  },
  {
    id: 1,
    type: 'Prepaid Hours',
    created_at: '10 Dec, 2025',
    total: 950.00,
    client: {
      first_name: 'Albert',
      last_name: 'Flores',
      email: 'albert@unitedbyblue.com',
      photo: 'https://randomuser.me/api/portraits/men/22.jpg',
      shopify_plan: 'Plus',
    },
  },
])

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
            id=: 1,
            type="text"
            placeholder="Search Transactions ..."
            class="w-full ml-3 text-h4 outline-none placeholder-tertiary"
            v-model="searchQuery"
        />
      </div>
    </div>
    <div class="space-y-4">
      <TransactionCard
          v-for="(transaction, index) in transactions"
          :key="index"
          :transaction="transaction"
          :isAdmin="true"
      />
    </div>
  </main>
</template>
