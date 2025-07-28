<script>
import AdminLayout from "@/layout/AdminLayout.vue";
import PayoutsCard from "@/components/cards/admin/PayoutsCard.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import axios from "axios";
import LoadingCards from "@/components/cards/LoadingCards.vue";
import LoadingSpinner from '@/components/misc/LoadingSpinner.vue';
import emptyState from "@/assets/empty-state.png";

export default {
  name: "PayoutsPage",

  components: {
    LoadingSpinner,
    LoadingCards,
    PayoutsCard,
    AdminLayout
  },

  data() {
    return {
      emptyState,
      SearchIcon,

      search: '',

      pageLoading: false,
      spinnerLoading: false,
      pageCount: 1,
      lastPage: 1,
      payouts: [],

      statusPopover: false,
      activeStatus: 'all',
      statusList: [
        {
          content: 'All',
          role: 'all',
          onAction: () => this.updateStatus('all')
        },
        {
          content: 'Pending',
          role: 'created',
          onAction: () => this.updateStatus('created')
        },
        {
          content: 'Approved',
          role: 'completed',
          onAction: () => this.updateStatus('completed')
        },
        {
          content: 'Declined',
          role: 'declined',
          onAction: () => this.updateStatus('declined')
        },
      ],
    }
  },

  computed: {
    selectedStatus() {
      this.statusList.forEach(status => {
        if (status.role === this.activeStatus) {
          status.active = true;
        } else {
          status.active = false;
        }
      });

      return this.statusList.find(status => status.role === this.activeStatus);
    }
  },

  async mounted() {
    await this.getPayouts()
  },

  methods: {
    toggleStatusPopover() {
      this.statusPopover = !this.statusPopover;
    },

    updateStatus(status) {
      this.activeStatus = status;
      this.statusPopover = false;

      this.defaultPageSettings()
      this.getPayouts()
    },

    async getPayouts() {
      this.pageLoading = true;
      this.spinnerLoading = true
      await axios.get(`api/admin/payouts?page=${this.pageCount}`, {params: {"status": this.activeStatus}}).then(res => {
        this.payouts.push(...res.data.payouts.data)
        this.pageCount += 1
        this.lastPage = res.data.payouts.last_page

      }).catch(err => {
        console.log(err)
      }).finally(() => {
        this.spinnerLoading = false
        this.pageLoading = false
      })
    },

    defaultPageSettings() {
      this.pageCount = 1
      this.payouts = []
    },

    loadMore() {
      if (this.pageCount > this.lastPage)
        return

      if (this.payouts.length === 0)
        return

      this.getPayouts(this.activeStatus)
    },

    refresh() {
      this.defaultPageSettings()
      this.getPayouts()
    }

  }
}
</script>

<template>
  <AdminLayout v-infinite-scroll="{ threshold: 1, callback: loadMore }" class="scrollable-container">
    <Page style="padding-top: 56px; padding-bottom: 56px"
          title="Payouts"
    >
      <template #pageTitle>
        <Popover
            :active="statusPopover"
            autofocusTarget="first-node"
            @close="toggleStatusPopover"
        >
          <template #activator>
            <Button @click="toggleStatusPopover" size="large"
                    :disclosure="statusPopover ? 'up' : 'down'">Status: {{ selectedStatus.content }}</Button>
          </template>
          <ActionList
              actionRole="menuitem"
              :items="statusList"
          ></ActionList>
        </Popover>
      </template>

      <BlockStack gap="300" v-if="payouts.length">
        <PayoutsCard v-for="payout in payouts" :key="payout.id" :payout="payout" @refresh="refresh"/>
      </BlockStack>

      <LoadingCards gap="300" v-else-if="pageLoading" />

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