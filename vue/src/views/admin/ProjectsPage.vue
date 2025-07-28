<script>
import AdminLayout from "@/layout/AdminLayout.vue";
import AddIcon from "@/components/icons/AddIcon.vue";
import ProjectsCard from "@/components/cards/admin/ProjectsCard.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import axios from "axios";
import emptyState from "@/assets/empty-state.png";
import LoadingCards from "@/components/cards/LoadingCards.vue";
import AssignExpertModal from "@/components/modals/AssignExpertModal.vue";
import LoadingSpinner from '@/components/misc/LoadingSpinner.vue';
import InputBtn from "@/components/misc/InputBtn.vue";
import {debounce} from "@/directives/debounce";

export default {
  name: "ProjectsPage",

  components: {
    LoadingSpinner,
    InputBtn,
    AssignExpertModal,
    LoadingCards,
    ProjectsCard,
    AdminLayout
  },

  data() {
    return {
      spinnerLoading: false,
      pageCount: 1,
      lastPage: 1,
      AddIcon,
      SearchIcon,
      emptyState,

      pageLoading: true,
      search: '',
      assignModal: false,
      selectedProjectId: false,

      statusPopover: false,
      activeStatus: 'all',
      statusList: [
        {
          content: 'All',
          role: 'all',
          onAction: () => this.selectAction('all')
        },
        {
          content: 'Pending Match',
          role: 'pending_match',
          onAction: () => this.selectAction('pending_match')
        },
        {
          content: 'Available',
          role: 'available',
          onAction: () => this.selectAction('available')
        },
        {
          content: 'Matched',
          role: 'matched',
          onAction: () => this.selectAction('matched')
        },
        {
          content: 'Pending Payment',
          role: 'pending_payment',
          onAction: () => this.selectAction('pending_payment')
        },
        {
          content: 'In Progress',
          role: 'in_progress',
          onAction: () => this.selectAction('in_progress')
        },
        {
          content: 'Completed',
          role: 'completed',
          onAction: () => this.selectAction('completed')
        },
        {
          content: 'Archived',
          role: 'archived',
          onAction: () => this.selectAction('archived')
        }
      ],

      projects: [],

      moderateProjects: 0
    }
  },

  async mounted() {
    this.activeStatus = this.getProjectStatusFilter();
    await this.getSettings();
    await this.getProjects();
  },

  watch: {
    search: debounce(function (search) {
      this.defaultPageSettings()
      this.getProjects(search);
    }, 400),
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
    async getProjects(search = null) {
      this.pageLoading = true;
      this.spinnerLoading = true;
      await axios.get(`api/admin/projects?page=${this.pageCount}`, {params: {'status': this.activeStatus, 'search': search}}).then(res => {
        this.projects.push(...res.data.projects.data)
        this.pageCount += 1
        this.lastPage = res.data.projects.last_page
      }).catch(err => {
        console.log(err)
      }).finally(() => {
        this.pageLoading = false
        this.spinnerLoading = false
      })
    },

    async getSettings() {
      await axios.get('api/admin/settings', {params: {'type': 'moderate_projects'}}).then(res => {
        this.moderateProjects = res.data.moderate_projects
      })
    },

    updateSetting: debounce(async function() {
      await axios.post('api/admin/settings', {'type': 'moderate_projects', 'value': !this.moderateProjects}).then(() => {
        this.getSettings();
      })
    }, 200),

    toggleStatusPopover() {
      this.statusPopover = !this.statusPopover;
    },

    selectAction(status) {
      this.statusPopover = false;
      this.activeStatus = status;
      localStorage.setItem("projectStatusFilter", status);
      this.defaultPageSettings()
      this.getProjects()
    },

    openAssignModal(event) {
      this.selectedProjectId = event.projectId;

      this.toggleAssignModal();
    },

    toggleAssignModal() {
      this.assignModal = !this.assignModal
    },

    closeModal: debounce(async function(event) {
      this.toggleAssignModal()

      await axios.post('api/admin/projects/' + this.selectedProjectId, { expertIdToAssign: event.userId}).then(() => {
        this.defaultPageSettings()
        this.getProjects();
      }).catch(() => {
        this.$router.push('/admin/projects')
      });
    }, 200),

    defaultPageSettings() {
      this.pageCount = 1
      this.projects = []
    },

    loadMore() {
      if (this.pageCount > this.lastPage)
        return
      if (this.projects.length === 0)
        return

      this.getProjects(this.search)
    },

    updateList() {
      this.defaultPageSettings()
      this.getProjects(this.search)
    },

    getProjectStatusFilter() {
      const value = localStorage.getItem('projectStatusFilter');
      return value === null || value === 'null' ? 'all' : value;
    },
  }
}
</script>

<template>
  <AdminLayout v-infinite-scroll="{ threshold: 1, callback: loadMore }" class="scrollable-container">
    <Page style="padding-top: 56px; padding-bottom: 56px"
          title="Projects"
    >
      <template #primaryAction>
        <InlineStack gap="200">
          <TextField
              style="min-width: 220px"
              :label="null"
              type="text"
              v-model="search"
              autoComplete="off"
              placeholder="Search projects ..."
          >
            <template #prefix>
              <Icon :source="SearchIcon" />
            </template>
          </TextField>

<!--          <Button variant="primary" size="large" :icon="AddIcon" @click="() => null">New Project</Button>-->
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
        <InlineStack align="space-between" block-align="center">
          <BlockStack gap="100">
            <Text as="p" variant="headingMd">
              Do you want to moderate new projects?
            </Text>

            <Text as="p" variant="bodyMd" tone="subdued" v-if="moderateProjects">
              At this moment, you need to moderate and approve new projects.
            </Text>

            <Text as="p" variant="bodyMd" tone="subdued" v-else>
              At this moment, projects are moderated automatically.
            </Text>
          </BlockStack>

          <InlineStack gap="200" v-if="moderateProjects">
            <InputBtn>Yes</InputBtn>
            <Button @click="updateSetting">No</Button>
          </InlineStack>

          <InlineStack gap="200" v-else>
            <Button @click="updateSetting">Yes</Button>
            <InputBtn>No</InputBtn>
          </InlineStack>
        </InlineStack>

        <BlockStack gap="200" v-if="projects.length">
          <ProjectsCard v-for="project in projects" :key="project.id" :project="project"
                        @updateList="updateList" @assignProject="openAssignModal"/>
        </BlockStack>

        <LoadingCards gap="300" v-else-if="pageLoading" />

        <BlockStack gap="200" v-else>
          <Card>
            <EmptyState
                heading="No project found"
                :image="emptyState"
            >
              <p>Currently there are no projects with selected status</p>
            </EmptyState>
          </Card>
        </BlockStack>
      </BlockStack>
    </Page>

    <AssignExpertModal v-show="assignModal" @close="toggleAssignModal" @assignProject="closeModal" />
  </AdminLayout>
  <LoadingSpinner :isLoading="spinnerLoading" />
</template>

<style scoped>
.scrollable-container {
  height: 100vh;
  overflow-y: auto;
}
</style>