<script>
import AdminLayout from "@/layout/AdminLayout.vue";
import ExpertsCard from "@/components/cards/admin/ExpertsCard.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import LoadingCards from "@/components/cards/LoadingCards.vue";
import emptyState from "@/assets/empty-state.png";
import axios from "axios";
import SelectPopover from "@/components/misc/SelectPopover.vue";
import TagBtn from "@/components/misc/TagBtn.vue";
import LoadingSpinner from '@/components/misc/LoadingSpinner.vue';
import {debounce} from "@/directives/debounce";

export default {
  name: "ExpertsPage",

  components: {
    LoadingSpinner,
    LoadingCards,
    ExpertsCard,
    AdminLayout,
    SelectPopover,
    TagBtn,
  },

  data() {
    return {
      spinnerLoading: false,
      pageCount: 1,
      lastPage: 1,
      SearchIcon,
      emptyState,

      search: '',

      statusPopover: false,
      activeStatus: null,
      statusList: [
        {
          content: 'All',
          role: null,
          onAction: () => this.selectAction(null)
        },
        {
          content: 'Pending',
          role: 'pending',
          onAction: () => this.selectAction('pending')
        },
        {
          content: 'Active',
          role: 'active',
          onAction: () => this.selectAction('active')
        },
        {
          content: 'Deactivated',
          role: 'inactive',
          onAction: () => this.selectAction('inactive')
        },
      ],

      activeRole: null,
      activeExp: null,
      activeLang: null,
      activeAvailability: null,
      activeHour: null,
      roles: [
        {
          name: 'Designer'
        },
        {
          name: 'Frontend Developer'
        },
        {
          name: 'Backend Developer'
        },
      ],
      experiences: [
        {
          name: 'Less than a year'
        },
        {
          name: '1-3 years'
        },
        {
          name: '3-5 years'
        },
        {
          name: '5-10 years'
        },
        {
          name: '10+ years'
        }
      ],
      english: [
        {
          name: 'Basic'
        },
        {
          name: 'Conversational'
        },
        {
          name: 'Fluent'
        },
        {
          name: 'Native'
        },
      ],
      availabilities: [
        {
          name: '10-20 hours per week',
        },
        {
          name: '20-30 hours per week',
        },
        {
          name: '30-40 hours per week',
        },
        {
          name: '40+ hours per week'
        }
      ],
      hours: [
        {
          name: 'Less than $60 per hour',
          from: null,
          to: 59.99,
        },
        {
          name: '$60-$75 per hour',
          from: 60.00,
          to: 74.99
        },
        {
          name: '$75-$90 per hour',
          from: 75.00,
          to: 89.99
        },
        {
          name: '$90-$115 per hour',
          from: 90.00,
          to: 114.99
        },
        {
          name: 'More than $115 per hour',
          from: 115.00,
          to: null
        },
      ],

      experts: [],
      expertsCount: 0,
      pageLoading: true,
      expertStatusFilter: null,
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

  watch: {
    search: debounce(async function () {
      await this.getExperts(true);
    }, 400),
  },

  async mounted() {
    this.activeStatus = this.getExpertStatusFilter() || 'active';
    if (this.activeStatus === 'all') this.activeStatus = null;
    await this.getExperts(true);
  },

  methods: {
    async getExperts(isDefaultSettings = false) {
      if (isDefaultSettings)
        this.defaultPageSettings()
      this.pageLoading = true;
      this.spinnerLoading = true

      let params = {
        filter: {}
      }

      if (this.search) {
        params.search = this.search
      }

      if (this.activeStatus) {
        params.filter.status = this.activeStatus
      }
      if (this.activeRole) {
        params.filter.role = this.activeRole.name
      }
      if (this.activeExp) {
        params.filter.experience = this.activeExp.name
      }
      if (this.activeLang) {
        params.filter.eng_level = this.activeLang.name
      }
      if (this.activeAvailability) {
        params.filter.availability = this.activeAvailability.name
      }
      if (this.activeHour) {
        params.filter.hourly_rate = {
          from: this.activeHour.from,
          to: this.activeHour.to
        }
      }

      await axios.get(`api/admin/experts?page=${this.pageCount}`, {params}).then(res => {
        if (isDefaultSettings)
          this.experts = []

        this.experts.push(...res.data.experts.data)
        this.expertsCount = res.data.experts_count
        this.pageCount += 1
        this.lastPage = res.data.experts.last_page
      }).catch(err => {
        console.log(err)
      }).finally(() => {
        this.pageLoading = false
        this.spinnerLoading = false
      })
    },

    defaultPageSettings() {
      this.pageCount = 1
      this.experts = []
    },

    setActiveRole(item) {
      this.activeRole = item
      this.getExperts(true)
    },

    setActiveExp(item) {
      this.activeExp = item
      this.getExperts(true)
    },

    setActiveLang(item) {
      this.activeLang = item
      this.getExperts(true)
    },

    setActiveAvailability(item) {
      this.activeAvailability = item
      this.getExperts(true)
    },

    setActiveHour(item) {
      this.activeHour = item
      this.getExperts(true)
    },

    loadMore() {
      if (this.pageCount > this.lastPage)
        return
      if (this.experts.length === 0)
        return

      this.getExperts()
    },

    refresh() {
      this.getExperts(true)
    },

    toggleStatusPopover() {
      this.statusPopover = !this.statusPopover;
    },

    selectAction(status) {
      this.statusPopover = false;
      const value = status === null ? 'all' : status;
      localStorage.setItem("expertStatusFilter", value);
      this.activeStatus = status;
      this.getExperts(true)
    },

    clearAll() {
      this.activeRole = null;
      this.activeExp = null;
      this.activeLang = null;
      this.activeAvailability = null;
      this.activeHour = null
      this.getExperts(true)
    },

    getExpertStatusFilter() {
      return localStorage.getItem('expertStatusFilter');
    }

  }
}
</script>

<template>
  <AdminLayout v-infinite-scroll="{ threshold: 1, callback: loadMore }" class="scrollable-container">
    <Page style="padding-top: 56px; padding-bottom: 56px"
          :title="'Experts ('+expertsCount+')'"
    >
      <template #primaryAction>
        <InlineStack gap="200">
          <TextField
              style="min-width: 220px"
              :label="null"
              type="text"
              v-model="search"
              autoComplete="off"
              placeholder="Search experts ..."
          >
            <template #prefix>
              <Icon :source="SearchIcon" />
            </template>
          </TextField>
        </InlineStack>
      </template>

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
          />
        </Popover>
      </template>

      <BlockStack gap="600">
        <BlockStack gap="300">
          <InlineStack gap="100">
            <SelectPopover name="Role"
                           :active="activeRole"
                           :items="roles"
                           @selected="(item) => setActiveRole(item)" />
            <SelectPopover name="Experience"
                           :active="activeExp"
                           :items="experiences"
                           @selected="(item) => setActiveExp(item)" />
            <SelectPopover name="English Level"
                           :active="activeLang"
                           :items="english"
                           @selected="(item) => setActiveLang(item)" />
            <SelectPopover name="Availability"
                           :active="activeAvailability"
                           :items="availabilities"
                           @selected="(item) => setActiveAvailability(item)" />
            <SelectPopover name="Hourly Rate"
                           :active="activeHour"
                           :items="hours"
                           @selected="(item) => setActiveHour(item)" />

            <Button variant="tertiary" @click="clearAll">Clear all</Button>
          </InlineStack>

          <InlineStack gap="100">
            <TagBtn name="Role"
                    :item="activeRole"
                    @remove="() => setActiveRole(null)"
                    v-if="activeRole" />

            <TagBtn name="Experience"
                    :item="activeExp"
                    @remove="() => setActiveExp(null)"
                    v-if="activeExp" />

            <TagBtn name="English Level"
                    :item="activeLang"
                    @remove="() => setActiveLang(null)"
                    v-if="activeLang" />

            <TagBtn name="Availability"
                    :item="activeAvailability"
                    @remove="() => setActiveAvailability(null)"
                    v-if="activeAvailability" />

            <TagBtn name="Hourly Rate"
                    :item="activeHour"
                    @remove="() => setActiveHour(null)"
                    v-if="activeHour" />
          </InlineStack>
        </BlockStack>

        <BlockStack gap="300" v-if="experts.length">
          <ExpertsCard :expert="expert" v-for="expert in experts" :key="expert.id" @reload="refresh" />
        </BlockStack>

        <LoadingCards gap="300" v-else-if="pageLoading" />

        <BlockStack gap="200" v-else>
          <Card>
            <EmptyState
                heading="No experts found"
                :image="emptyState"
            >
              <p>We couldn't find any Expert that match search criteria</p>
            </EmptyState>
          </Card>
        </BlockStack>
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