<script>
import ExpertLayout from "@/layout/ExpertLayout.vue";
import PayoutsCard from "@/components/cards/expert/PayoutsCard.vue";
import LoadingCards from "@/components/cards/LoadingCards.vue";
import MobileCard from "@/components/MobileCard.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import LoadingSpinner from '@/components/misc/LoadingSpinner.vue';
import axios from "axios";
import emptyState from "@/assets/empty-state.png";
export default {
  name: "PayoutsPage",

  components: {
    LoadingSpinner,
    LoadingCards,
    PayoutsCard,
    ExpertLayout,
    MobileCard,
  },

  async mounted() {
    await this.getPayouts()
  },

  data() {
    return {
      emptyState,
      SearchIcon,

      isMobile: screen.width <= 760,

      statusPopover: false,
      activeStatus: 'all',
      statusList: [
        {
          content: 'All',
          role: 'all',
          onAction: () => this.filterByStatus()
        },
        {
          content: 'Pending',
          role: 'created',
          onAction: () => this.filterByStatus('created')
        },
        {
          content: 'Approved',
          role: 'completed',
          onAction: () => this.filterByStatus('completed')
        },
        {
          content: 'Declined',
          role: 'declined',
          onAction: () => this.filterByStatus('declined')
        },
      ],
      spinnerLoading: false,
      pageCount: 1,
      lastPage: 1,
      pageLoading: false,

      payouts: [],
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

  methods: {
    toggleStatusPopover() {
      this.statusPopover = !this.statusPopover;
    },

    async getPayouts(status = null) {
      this.statusPopover = false;
      this.activeStatus = status ?? "all";
      this.pageLoading = true;
      this.spinnerLoading = true;
      await axios.get(`api/expert/payouts?page=${this.pageCount}`, {params: {"status": status}}).then(res => {
        this.payouts.push(...res.data.payouts.data)
        this.pageCount += 1
        this.lastPage = res.data.payouts.last_page
      }).catch(err => {
        console.log(err)
      }).finally(() => {
        this.pageLoading = false
        this.spinnerLoading = false
      })
    },

    defaultPageSettings() {
      this.pageCount = 1
      this.payouts = []
    },

    filterByStatus(status = null) {
      this.defaultPageSettings()
      this.getPayouts(status)
    },

    loadMore() {
      if (this.pageCount > this.lastPage)
        return

      if (this.payouts.length === 0)
        return

      this.getPayouts(this.activeStatus)
    },

  }
}
</script>

<template>
  <ExpertLayout v-infinite-scroll="{ threshold: 1, callback: loadMore }" class="scrollable-container">
    <Page :style="isMobile ? {padding: '32px 0'} : {padding: '56px 0'}"
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

      <div :style="isMobile ? {padding: '0 16px'} : null">
        <BlockStack gap="300" v-if="payouts.length">
          <PayoutsCard v-for="payout in payouts" :key="payout.id" :payout="payout" />
        </BlockStack>

        <LoadingCards gap="300" v-else-if="pageLoading" />

        <BlockStack gap="200" v-else>
          <MobileCard>
            <EmptyState
                heading="No past withdraws available"
                :image="emptyState"
            >
              <p>You have not made any withdraws yet.</p>
            </EmptyState>
          </MobileCard>
        </BlockStack>
      </div>
    </Page>
  </ExpertLayout>
  <LoadingSpinner :isLoading="spinnerLoading" />
</template>

<style scoped>
.scrollable-container {
  height: 100vh;
  overflow-y: auto;
}
</style>