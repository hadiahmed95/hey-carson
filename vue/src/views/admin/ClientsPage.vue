<script>
import AdminLayout from "@/layout/AdminLayout.vue";
import ClientsCard from "@/components/cards/admin/ClientsCard.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import emptyState from "@/assets/empty-state.png";
import axios from "axios";
import LoadingCards from "@/components/cards/LoadingCards.vue";
import LoadingSpinner from '@/components/misc/LoadingSpinner.vue';
import {debounce} from "@/directives/debounce";

export default {
  name: "ClientsPage",

  components: {
    LoadingSpinner,
    LoadingCards,
    AdminLayout,
    ClientsCard
  },

  data() {
    return {
      spinnerLoading: false,
      pageCount: 1,
      lastPage: 1,
      SearchIcon,
      emptyState,

      pageLoading: true,

      search: '',

      clients: [],
      clientsCount: 0
    }
  },

  watch: {
    search: debounce(async function (search) {
      await this.getClients(search, true);
    }, 400)
  },

  async mounted() {
    await this.getClients();
  },

  methods: {
    async getClients(search = null, isSearch = false) {
      this.pageLoading = true;
      this.spinnerLoading = true;
      if (isSearch)
        this.pageCount = 1

      await axios.get(`api/admin/clients?page=${this.pageCount}`, {params: {'search': search}}).then(res => {
        if (isSearch)
          this.clients = []

        this.clients.push(...res.data.clients.data);
        this.clientsCount = res.data.clients_count;
        this.pageCount += 1
        this.lastPage = res.data.clients.last_page
      }).catch(err => {
        console.log(err)
      }).finally(() => {
        this.pageLoading = false
        this.spinnerLoading = false
      });
    },

    defaultPageSettings() {
      this.pageCount = 1
      this.clients = []
    },

    loadMore() {
      if (this.pageCount > this.lastPage)
        return
      if (this.clients.length === 0)
        return

      this.getClients(this.search)
    },

  }
}
</script>

<template>
  <AdminLayout v-infinite-scroll="{ threshold: 1, callback: loadMore }" class="scrollable-container">
    <Page style="padding-top: 56px; padding-bottom: 56px"
          :title="'Clients ('+clientsCount+')'"
    >
      <template #primaryAction>
        <InlineStack gap="200">
          <TextField
              style="min-width: 220px"
              :label="null"
              type="text"
              v-model="search"
              autoComplete="off"
              placeholder="Search clients ..."
          >
            <template #prefix>
              <Icon :source="SearchIcon" />
            </template>
          </TextField>
        </InlineStack>
      </template>

      <BlockStack gap="300" v-if="clients.length">
        <ClientsCard v-for="client in clients" :key="client.id" :client="client" />
      </BlockStack>

      <LoadingCards gap="300" v-else-if="pageLoading" />

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