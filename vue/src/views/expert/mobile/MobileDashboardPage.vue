<script>
import MobileCard from "@/components/MobileCard.vue";
import AddIcon from "@/components/icons/AddIcon.vue";
import emptyState from "@/assets/empty-state.png";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import ProjectCard from "@/components/cards/expert/ProjectCard.vue";
import LoadingCards from "@/components/cards/LoadingCards.vue";
import {debounce} from "@/directives/debounce";
import InputBtn from "@/components/misc/InputBtn.vue";

export default {
  name: "MobileDashboardPage",

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

  components: {InputBtn, LoadingCards, ProjectCard, MobileCard},

  watch: {
    search: debounce(function () {
      this.$emit('search', this.search);
    }, 300),
  },

  data() {
    return {
      emptyState,
      SearchIcon,
      AddIcon,

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

    toggleCreateModal() {
      this.$emit('createModal')
    },

    goTo(page) {
      this.$router.push('/expert/project/' + page);
    },

    refreshProjects() {
      this.$emit('refresh')
    }
  }
}
</script>

<template>
  <BlockStack gap="600" style="padding: 32px 16px;">
    <InlineStack align="space-between" blockAlign="center">
      <Text variant="headingLg" as="p">My Projects</Text>

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
    </InlineStack>

    <InputBtn :icon="AddIcon" @click="toggleCreateModal">New Project</InputBtn>

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

    <BlockStack gap="200" v-if="projects.length">
      <ProjectCard expert
                   @updateList="refreshProjects"
                   @click="goTo(project.project.id)"
                   v-for="project in projects" :key="project.id" :project="project"/>
    </BlockStack>

    <LoadingCards gap="300" v-else-if="pageLoading" />

    <BlockStack gap="200" v-else>
      <MobileCard>
        <EmptyState
            heading="Shortly, we will try to assign a project to you."
            :image="emptyState"
        >
          <p>Sit in your chair and wait for your project. We are currently trying to find the perfect project for you. Thank you for your patience.</p>
        </EmptyState>
      </MobileCard>
    </BlockStack>
  </BlockStack>
</template>
<style scoped>

</style>