<script>
import AdminLayout from "@/layout/AdminLayout.vue";
import ProjectsTab from "@/components/tabs/adminDashboard/ProjectsTab.vue";
import ClientsTab from "@/components/tabs/adminDashboard/ClientsTab.vue";
import ExpertsTab from "@/components/tabs/adminDashboard/ExpertsTab.vue";
import PayoutsTab from "@/components/tabs/adminDashboard/PayoutsTab.vue";
import ProjectIdeasTab from "@/components/tabs/adminDashboard/ProjectIdeasTab.vue";
import QuestionsTab from "@/components/tabs/adminDashboard/QuestionsTab.vue";
import LoadingSpinner from '@/components/misc/LoadingSpinner.vue';
import axios from "axios";
export default {
  name: "DashboardPage",

  components: {
    LoadingSpinner,
    AdminLayout,
    ProjectsTab,
    ClientsTab,
    ExpertsTab,
    PayoutsTab,
    ProjectIdeasTab,
    QuestionsTab
  },

  data() {
    return {
      spinnerLoading: false,
      pageLoading: true,
      statusPopover: false,
      activeStatus: 'all',
      statusList: [
        {
          content: 'Past Week',
          role: 'week',
          onAction: () => this.selectAction('week')
        },
        {
          content: 'Past Month',
          role: 'month',
          onAction: () => this.selectAction('month')
        },
        {
          content: 'All Time',
          role: 'all',
          onAction: () => this.selectAction('all')
        },
      ],

      activeTab: 0,
      timePeriod: 'all',
      stats: [],
      migrated_merchants_stats: [],
      clients: [],
      experts: [],
      projects: [],
      payouts: [],
      pagination: {
        client: { pageCount: 1, lastPage: 1 },
        expert: { pageCount: 1, lastPage: 1 },
        project: { pageCount: 1, lastPage: 1 },
        payout: { pageCount: 1, lastPage: 1 }
      },
      total: {
        clients: 0,
        experts: 0,
        projects: 0,
        payouts: 0
      },
    }
  },

  computed: {
    tabs() {
      return [
        {
          id: 'projects',
          content: 'Projects',
          badge: this.total.projects,
        },
        {
          id: 'clients',
          content: 'Clients',
          badge: this.total.clients,
        },
        {
          id: 'experts',
          content: 'Experts',
          badge: this.total.experts,
        },
        {
          id: 'payouts',
          content: 'Payouts',
          badge: this.total.payouts,
        },
        {
          id: 'project-ideas',
          content: 'Project Ideas',
          badge: null,
        },
        {
          id: 'questions',
          content: 'Questions',
          badge: null,
        },
      ];
    }
  },

  async mounted() {
    await this.getData();
  },

  methods: {

    async getData() {
      this.pageLoading = true;
      this.createModal = false;
      await axios.get('api/admin', {params: { "period": this.activeStatus }}).then(res => {
        this.projects = res.data.projects.data;
        this.pagination.project.pageCount = 2
        this.pagination.project.lastPage = res.data.projects.last_page;
        this.total.clients = res.data.clients_count
        this.total.experts = res.data.experts_count
        this.total.projects = res.data.projects_count
        this.total.payouts = res.data.payouts_count
        this.stats = res.data.stats;
        this.migrated_merchants_stats = res.data.migrated_merchants_stats;
      }).catch(err => {
        console.log(err)
      }).finally(() => {
        this.pageLoading = false
      });
    },

    getProjects() {
      axios.get(`api/admin/projects?page=${this.pagination.project.pageCount}`, {params: {'period': this.activeStatus}}).then(res => {
        this.projects.push(...res.data.projects.data)
        this.pagination.project.pageCount += 1
        this.pagination.project.lastPage = res.data.projects.last_page
      }).catch(err => {
        console.log(err)
      }).finally(() => {
        this.spinnerLoading = false;
      })
    },

    getExperts() {
      axios.get(`api/admin/experts?page=${this.pagination.expert.pageCount}`, {params: {"period": this.activeStatus}}).then(res => {
        this.experts.push(...res.data.experts.data)
        this.pagination.expert.pageCount += 1
        this.pagination.expert.lastPage = res.data.experts.last_page
      }).catch(err => {
        console.log(err)
      }).finally(() => {
        this.spinnerLoading = false;
      })
    },

    getClients() {
      axios.get(`api/admin/clients?page=${this.pagination.client.pageCount}`, {params: {"period": this.activeStatus}}).then(res => {
        this.clients.push(...res.data.clients.data);
        this.pagination.client.pageCount += 1
        this.pagination.client.lastPage = res.data.clients.last_page
      }).catch(err => {
        console.log(err)
      }).finally(() => {
        this.spinnerLoading = false;
      })
    },

    getPayouts() {
      axios.get(`api/admin/payouts?page=${this.pagination.payout.pageCount}`, {params: {"period": this.activeStatus}}).then(res => {
        this.payouts.push(...res.data.payouts.data)
        this.pagination.payout.pageCount += 1
        this.pagination.payout.lastPage = res.data.payouts.last_page

      }).catch(err => {
        console.log(err)
      }).finally(() => {
        this.spinnerLoading = false;
      })
    },

    changeTab(tab) {
      this.activeTab = tab

      if (tab === 0) {
        if (this.projects.length)
          return
      } else if (tab === 1) {
        if (this.clients.length)
          return
      } else if (tab === 2) {
        if (this.experts.length)
          return
      } else if (tab === 3) {
        if (this.payouts.length)
          return
      }
      else {
        return
      }

      this.getDataByTab(this.activeTab)
    },

    getDataByTab(tab) {
      this.spinnerLoading = true;
      if (tab === 0) {
        this.getProjects()
      } else if (tab === 1) {
        this.getClients()
      } else if (tab === 2) {
        this.getExperts()
      } else if (tab === 3) {
        this.getPayouts()
      }
    },

    refreshPayouts() {
      this.pagination.payout.pageCount = 1
      this.payouts = []
      this.getDataByTab(this.activeTab)
    },

    loadMore() {
      if (this.activeTab === 0) {
        if (this.pagination.project.pageCount > this.pagination.project.lastPage || this.projects.length === 0)
          return
      } else if (this.activeTab === 1) {
        if (this.pagination.client.pageCount > this.pagination.client.lastPage || this.clients.length === 0)
          return
      } else if (this.activeTab === 2) {
        if (this.pagination.expert.pageCount > this.pagination.expert.lastPage || this.experts.length === 0)
          return
      } else if (this.activeTab === 3) {
        if (this.pagination.payout.pageCount > this.pagination.payout.lastPage || this.payouts.length === 0)
          return
      }
      this.getDataByTab(this.activeTab)
    },

    toggleStatusPopover() {
      this.statusPopover = !this.statusPopover
    },

    selectAction(status) {
      this.statusPopover = false
      this.activeStatus = status;


      this.getData()
    },
  }
}
</script>

<template>
  <AdminLayout v-infinite-scroll="{ threshold: 1, callback: loadMore }" class="scrollable-container">
    <Page style="padding-top: 56px; padding-bottom: 56px"
          title="Dashboard"
    >
      <template #primaryAction>
        <Popover
            :active="statusPopover"
            autofocusTarget="first-node"
            @close="toggleStatusPopover"
        >
          <template #activator>
            <Button @click="toggleStatusPopover" size="large"
                    :disclosure="statusPopover ? 'up' : 'down'">{{ activeStatus === 'all' ? 'All Time' : activeStatus === 'month' ? 'Past Month' : 'Past Week' }}</Button>
          </template>
          <ActionList
              actionRole="menuitem"
              :items="statusList"
          ></ActionList>
        </Popover>
      </template>

      <BlockStack gap="800">
        <InlineGrid gap="400" :columns="4">
          <Card :padding="null" v-for="stat in stats" :key="stat.key">
            <Box padding="400" class="small-cards">
              <BlockStack gap="200">
                <Text as="p" variant="bodyMd" tone="subdued">{{ stat.key }}</Text>

                <Text as="p" variant="headingLg">{{ stat.value }}</Text>
              </BlockStack>
            </Box>
          </Card>
        </InlineGrid>

        <BlockStack gap="200">
          <Text as="p" variant="headingMd">Merchants</Text>

          <InlineGrid gap="400" :columns="4">
            <Card :padding="null" v-for="stat in migrated_merchants_stats" :key="stat.key">
              <Box padding="400" class="small-cards">
                <BlockStack gap="200">
                  <Text as="p" variant="bodyMd" tone="subdued">{{ stat.key }}</Text>

                  <Text as="p" variant="headingLg">{{ stat.value }}</Text>
                </BlockStack>
              </Box>
            </Card>
          </InlineGrid>
        </BlockStack>

        <BlockStack gap="200">
          <Text as="p" variant="headingMd">Last Updates</Text>

          <Tabs style="padding: 0"
                :tabs="tabs"
                :selected="activeTab"
                @select="changeTab"
          />
        </BlockStack>

        <ProjectsTab v-if="activeTab === 0" :projects="projects" :pageLoading="pageLoading" />
        <ClientsTab v-if="activeTab === 1" :clients="clients" />
        <ExpertsTab v-if="activeTab === 2" :experts="experts" />
        <PayoutsTab v-if="activeTab === 3" :payouts="payouts" @refresh="refreshPayouts" />
        <ProjectIdeasTab v-if="activeTab === 4" :projectIdeas="[]" />
        <QuestionsTab v-if="activeTab === 5" :questions="[]" />
      </BlockStack>
    </Page>
  </AdminLayout>
  <LoadingSpinner :isLoading="spinnerLoading" />
</template>

<style scoped>
.small-cards {
  cursor: default !important;
}
.small-cards:hover {
  background: #f9f9f9;
}
.scrollable-container {
  height: 100vh;
  overflow-y: auto;
}
</style>
