<script>
import moment from "moment";
import axios from "axios";
import UserBox from "@/components/misc/UserBox.vue";
import {debounce} from "@/directives/debounce";

export default {
  name: "ProjectsCard",
  components: {UserBox},

  props: {
    preferred: {
      default: false,
      type: Boolean
    },

    project: {
      type: Object,
      default: () => {
        return {}
      }
    },

    status: {
      default: '',
      type: String
    }
  },

  data() {
    return {
      assignProjectModal: false,

      loading: false,
      actionsPopover: false,
      actionsList: this.project?.preferred_expert ? [
        {
          content: 'Assign Preferred',
          role: 'assign',
          onAction: () => this.assignPreferred()
        },
        {
          content: 'Assign Project',
          role: 'assign',
          onAction: () => this.toggleAssignProjectModal()
        },
        {
          content: 'Move to Available',
          role: 'assign',
          onAction: () => this.moveProjectToAvailable()
        }
      ] : [
        {
          content: 'Assign Project',
          role: 'assign',
          onAction: () => this.toggleAssignProjectModal()
        },
        {
          content: 'Move to Available',
          role: 'assign',
          onAction: () => this.moveProjectToAvailable()
        }
      ]
    }
  },

  methods: {
    toggleAssignProjectModal() {
      this.actionsPopover = false;
      this.$emit('assignProject', {projectId: this.project.id});
    },

    moveProjectToAvailable: debounce(async function() {
      this.actionsPopover = false;
      this.loading = true;
      await axios.post('api/admin/projects/' + this.project.id, {status: 'move_pending_match_to_available'}).then(() => {
        this.$emit('updateList')
        this.loading = false;
      }).catch(err => {
        console.log(err)
        this.loading = false;
      });
    }, 200),

    assignPreferred: debounce(async function() {
      this.actionsPopover = false;
      this.loading = true;
      await axios.post('api/admin/projects/' + this.project.id, {status: 'preferred'}).then(() => {
        this.$emit('updateList')
      }).catch(err => {
        console.log(err)
        this.loading = false;
      });
    }, 200),

    toggleActionsPopover() {
      this.actionsPopover = !this.actionsPopover;
    },

    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do YYYY')
    },

    goTo(project) {
      const newTab = window.open(`/admin/projects/${project.id}`, '_blank');

      newTab.onload = () => {
        newTab.document.title = project.name;
      };
    },

    projectDescription() {
      let text = this.project?.description.substr(0, 120)

      if (this.project?.description.length > 120) {
        text += '...'
      }

      return text;
    },

    restoreProject: async function (projectId) {
      this.loading = true;

      try {
        await axios.post(`/api/admin/projects/${projectId}/restore`);

        this.$emit('updateList');
      } catch (err) {
        console.error("Failed to restore project:", err);

      } finally {
        this.loading = false;
      }
    }
  }
}
</script>

<template>
  <Card :padding="null" @click="project?.client_id ? goTo(project) : null">
    <Box padding="600" class="project-card">
      <BlockStack gap="400">
        <InlineStack align="space-between">
          <InlineStack gap="200">
            <Badge v-if="project?.deleted_at" tone="critical">Archived</Badge>
            <Badge v-else-if="project?.status === 'pending_payment'" tone="critical">Pending Payment</Badge>
            <Badge v-else-if="project?.status === 'in_progress'" tone="info">In Progress</Badge>
            <Badge v-else-if="project?.status === 'expert_completed'" tone="info">Awaiting Approval</Badge>
            <Badge v-else-if="project?.status === 'completed'" tone="success">Completed</Badge>
            <Badge v-else-if="project?.status === 'pending_match'" tone="attention">Pending Match</Badge>
            <Badge v-else-if="project?.status === 'claimed'" tone="attention" size="large">Read</Badge>
            <Badge v-else-if="project?.status === 'available'">In Available</Badge>
            <Badge v-else-if="project?.status === 'matched'" tone="magic">Matched</Badge>
            <Badge v-else>Missing Status</Badge>

            <Badge v-if="project?.urgent" tone="critical">Urgent</Badge>
          </InlineStack>

          <Text as="p" variant="bodySm" tone="subdued">
            Submitted: {{ formatDate(project?.created_at) }}
          </Text>
        </InlineStack>

        <BlockStack gap="100">
          <Text as="p" variant="headingMd">{{ project?.name }}</Text>

          <Text as="p" variant="bodyMd" tone="subdued" @click.stop>
            <Link v-if="project && project.url" :url="project.url?.startsWith('https://') ? project?.url : 'https://' + project?.url" target="_blank" removeUnderline style="color: #6E6E73;">
              {{ project?.url }}
            </Link>
          </Text>
        </BlockStack>

        <Text as="p" variant="bodyMd" v-html="projectDescription()" />

        <Divider />

        <InlineStack align="space-between">
          <InlineStack align="space-between" style="flex: 1" v-if="project">
            <UserBox client :user="project.client" />

            <UserBox preferred :user="project?.preferred_expert" v-if="project?.preferred_expert" />
          </InlineStack>

          <InlineStack v-if="!project?.deleted_at" align="end" style="flex: 1">
            <Popover v-if="project?.status === 'pending_match'"
                     :active="actionsPopover"
                     autofocusTarget="first-node"
                     @close="toggleActionsPopover"
            >
              <template #activator>
                <Button @click.stop="toggleActionsPopover" :loading="loading"
                        :disclosure="actionsPopover ? 'up' : 'down'">Actions</Button>
              </template>
              <ActionList
                  actionRole="menuitem"
                  :items="actionsList"
              ></ActionList>
            </Popover>

            <InlineStack v-else-if="project?.status === 'available'" align="start" blockAlign="center" gap="200">
              <Text as="h2" variant="headingSm">
                Awaiting Expert to Claim this Project
              </Text>
            </InlineStack>

            <UserBox assigned :user="project?.active_assignment?.expert" v-else />
          </InlineStack>
          <InlineStack v-if="project?.deleted_at" align="end" style="flex: 1">
            <Popover
                     :active="actionsPopover"
                     autofocusTarget="first-node"
                     @close="toggleActionsPopover"
            >
              <template #activator>
                <Button @click.stop="toggleActionsPopover" :loading="loading"
                        :disclosure="actionsPopover ? 'up' : 'down'">Actions</Button>
              </template>
              <ActionList
                  actionRole="menuitem"
                  :items="[{
                    content: 'Restore Project',
                    role: 'restore',
                    onAction: () => this.restoreProject(this.project.id),
                  }]"
              ></ActionList>
            </Popover>
          </InlineStack>
        </InlineStack>
      </BlockStack>
    </Box>
  </Card>
</template>

<style scoped>
.project-card:hover {
  cursor: pointer;
  background: #f9f9f9;
}
.project-card:active {
  cursor: pointer;
  background: #f2f2f2;
}
</style>