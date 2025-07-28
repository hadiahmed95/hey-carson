<script>
import emptyState from "@/assets/empty-state.png";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import ProjectCard from "@/components/cards/expert/ProjectCard.vue";
import LoadingCards from "@/components/cards/LoadingCards.vue";
import {debounce} from "@/directives/debounce";

export default {
  name: "WebDashboardPage",
  components: {LoadingCards, ProjectCard},

  props: {
    pageLoading: {
      default: false,
      type: Boolean
    },
    projects: {
      default: () => {
        return [];
      },
      type: Array
    }
  },

  watch: {
    search: debounce(function () {
      this.$emit('search', this.search);
    }, 300),
  },

  data() {
    return {
      emptyState,
      SearchIcon,

      search: '',

      statusPopover: false,

      activeStatus: 'all',

      createModal: false,

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
          content: 'Awaiting Approval',
          role: 'expert_completed',
          onAction: () => this.selectAction('expert_completed')
        },
        {
          content: 'Completed',
          role: 'completed',
          onAction: () => this.selectAction('completed')
        }
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

  methods: {
    toggleStatusPopover() {
      this.statusPopover = !this.statusPopover;
    },

    selectAction(status) {
      this.statusPopover = false;
      this.$emit('status', status)
      this.activeStatus = status;
    },

    goTo(project) {
      const newTab = window.open(`/expert/project/${project.id}`, '_blank');

      newTab.onload = () => {
        newTab.document.title = project.name;
      };
    },

    refreshProjects() {
      this.$emit('refresh')
    }
  }
}
</script>

<template>
  <Page style="padding-top: 56px; padding-bottom: 56px"
        title="My Projects"
  >
    <template #secondaryActions>
      <TextField
          style="min-width: 220px; z-index: 1"
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
        ></ActionList>
      </Popover>
    </template>

    <BlockStack gap="200" v-if="projects.length">
      <ProjectCard expert @updateList="refreshProjects"
                   @click="project.client_id ? goTo(project) : null"
                   v-for="project in projects" :key="project.id" :project="project"/>
    </BlockStack>

    <LoadingCards gap="300" v-else-if="pageLoading" />

    <BlockStack gap="200" v-else>
      <Card>
        <EmptyState
            heading="Shortly, we will try to assign a project to you."
            :image="emptyState"
        >
          <p>Sit in your chair and wait for your project. We are currently trying to find the perfect project for you. Thank you for your patience.</p>
        </EmptyState>
      </Card>
    </BlockStack>
  </Page>
</template>

<style scoped>

</style>