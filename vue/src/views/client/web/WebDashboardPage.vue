<script>
import { mapActions, mapGetters } from 'vuex';
import AddIcon from "@/components/icons/AddIcon.vue";
import ProjectCard from "@/components/cards/client/ProjectCard.vue";
import emptyState from "@/assets/empty-state.png";
import LoadingCards from "@/components/cards/LoadingCards.vue";
import InputBtn from "@/components/misc/InputBtn.vue";

export default {
  name: "DashboardPage",

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

  components: {
    InputBtn,
    LoadingCards,
    ProjectCard,
  },
  data() {
    return {
      AddIcon,
      emptyState,
      statusPopover: false,
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
    ...mapGetters(['getClientProjectSortStatus']),
    selectedStatus() {
      this.statusList.forEach(status => {
        status.active = status.role === this.getClientProjectSortStatus;
      });
      return this.statusList.find(status => status.role === this.getClientProjectSortStatus);
    }
  },
  methods: {
    ...mapActions(['updateClientProjectSortStatus']),
    toggleStatusPopover() {
      this.statusPopover = !this.statusPopover;
    },
    selectAction(status) {
      this.statusPopover = false;
      this.updateClientProjectSortStatus(status);
    },
    toggleCreateModal() {
      this.$emit('createModal');
    },
    goTo(page) {
      this.$router.push('/client/project/' + page);
    }
  },
  mounted() {
    const currentStatus = this.getClientProjectSortStatus || 'all';
    if (currentStatus) {
      this.$emit('status', currentStatus);
    }
  }
}
</script>

<template>
  <Page style="padding: 56px 16px"
        title="My Projects"
  >
    <template #primaryAction>
      <InputBtn :icon="AddIcon" @click="toggleCreateModal">New Project</InputBtn>
    </template>

    <template #pageTitle>
      <Popover
          :active="statusPopover"
          autofocusTarget="first-node"
          @close="toggleStatusPopover"
      >
        <template #activator>
          <Button @click="toggleStatusPopover" size="large"
                  :disclosure="statusPopover ? 'up' : 'down'">Status:{{ selectedStatus.content }}</Button>
        </template>
        <ActionList
            actionRole="menuitem"
            :items="statusList"
        ></ActionList>
      </Popover>
    </template>

    <BlockStack gap="200" v-if="projects.length">
      <ProjectCard @click="goTo(project.id)" v-for="project in projects" :key="project.id" :project="project"/>
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
  </Page>
</template>

<style scoped>

</style>