<script>
import AdminLayout from "@/layout/AdminLayout.vue";
import PlusCircleIcon from "@/components/icons/PlusCircleIcon.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import LoadingPage from "@/components/cards/LoadingPage.vue";
import AssignExpertModal from "@/components/modals/AssignExpertModal.vue";
import AvatarFrame from "@/components/misc/AvatarFrame.vue";
import ProjectStatusChangeAlert from "@/components/misc/ProjectStatusChangeAlert.vue";
import axios from "axios";
import InputBtn from "@/components/misc/InputBtn.vue";
import ChatroomTab from "@/components/tabs/ChatroomTab.vue";
import ProjectInvoicesTab from "@/components/tabs/ProjectInvoicesTab.vue";
import ProjectHistoryTab from "@/components/tabs/ProjectHistoryTab.vue";
import QuotaCard from "@/components/cards/QuotaCard.vue";
import StarFullIcon from "@/components/icons/StarFullIcon.vue";
import StarEmptyIcon from "@/components/icons/StarEmptyIcon.vue";
import UserBox from "@/components/misc/UserBox.vue";
import moment from "moment/moment";
import {debounce} from "@/directives/debounce";

export default {
  name: "ProjectPendingMatchPage",

  components: {
    InputBtn,
    UserBox,
    StarEmptyIcon, StarFullIcon,
    QuotaCard,
    ProjectInvoicesTab,
    ProjectHistoryTab,
    ChatroomTab,
    AdminLayout,
    LoadingPage,
    AssignExpertModal,
    AvatarFrame,
    ProjectStatusChangeAlert
  },

  async mounted() {
    await this.getProject(this.$route.params.id)

    let openTab = JSON.parse(sessionStorage.getItem('openTab'))

    if (openTab && openTab.projectId === parseInt(this.$route.params.id)) this.activeTab = openTab.tab
  },

  // eslint-disable-next-line
  beforeRouteUpdate(to, from) {
    this.activeTab = 0;

    sessionStorage.removeItem('openTab')

    this.getProject(to.params.id);
  },

  unmounted() {
    this.activeTab = 0;

    sessionStorage.removeItem('openTab')
  },

  data() {
    return {
      PlusCircleIcon,
      CheckCircle,

      pageLoading: true,

      loading: false,

      assignModal: false,

      activeTab: 0,

      project: {},
      notificationMessage: "",
      notificationType: ""
    }
  },

  computed: {
    totalReview() {
      if (this.project.active_assignment) {
        return this.project.active_assignment.expert.reviews.length
      } else {
        return 0
      }
    },
    expertRating() {
      let rating = 0;

      if (this.totalReview) {
        this.project.active_assignment.expert.reviews.forEach(rev => {
          rating += rev.rate;
        })

        return (rating / this.totalReview).toFixed(1)
      } else {
        return rating.toFixed(1)
      }
    },

    completedProjects() {
      if (this.project.active_assignment) {
        return this.project.active_assignment.expert.active_assignments.filter(a => a.project?.status === 'completed').length
      } else {
        return 0;
      }
    },

    projectFiles() {
      let files = [];
      if (this.project.project_files && this.project.project_files.length) {
        files.push(...this.project.project_files.filter(file => file.message_id === null));
      }
      return files
    },

    messageFiles() {
      let files = [];
      if (this.project.project_files && this.project.project_files.length) {
        files.push(...this.project.project_files.filter(file => file.message_id !== null));
      }
      return files
    },

    isAssigned() {
      const { status } = this.project || {};
      return !(status === 'pending_match' || status === 'claimed');
    },

    actionGroup() {
      return this.project.deleted_at ? 'Restore Project' : 'Archive Project';
    },

    tabs() {
      return [
        {
          id: 'details',
          content: 'Project Details'
        },
        {
          id: 'chat',
          content: 'Chatroom',
          disabled: !this.isAssigned,
        },
        {
          id: 'transactions',
          content: 'Transactions',
          disabled: !this.isAssigned,
        },
        {
          id: 'history',
          content: 'Status History',
        },
      ]
    }
  },

  methods: {
    async getProject(id) {
      this.pageLoading = true;
      await axios.get('api/admin/projects/' + id).then(res => {
        this.project = res.data.project;

        this.pageLoading = false;
      }).catch(() => {
        this.$router.push('/admin/projects')
      });
    },

    updateProject: debounce(async function() {
      this.loading = true;
      await axios.post('api/admin/projects/' + this.$route.params.id, {status: 'move_pending_match_to_available'}).then(() => {
        this.getProject(this.$route.params.id)

        this.loading = false;
      }).catch(() => {
        this.$router.push('/admin/projects')
      });
    }, 200),

    moveToAvailable: debounce(async function() {
      this.loading = true;
      await axios.post('api/admin/projects/' + this.$route.params.id, {status: 'move_matched_to_available', expertIdToRemove: this.project.active_assignment.expert_id}).then(() => {
        this.getProject(this.$route.params.id)

        this.loading = false;
      }).catch(() => {
        this.$router.push('/admin/projects')
      });
    }, 200),

    assignPreferred: debounce(async function() {
      this.loading = true;
      await axios.post('api/admin/projects/' + this.project.id, {status: 'preferred'}).then(() => {
        this.getProject()
        this.loading = false;
      }).catch(() => {
        this.$router.push('/admin/projects')
      });
    }, 200),

    toggleAssignModal(expertFilter = false) {
      this.projectId = expertFilter ? this.project.id : null;
      this.assignModal = !this.assignModal;
    },

    closeModal: debounce(async function(event) {
      this.toggleAssignModal();

      this.loading = true;

      try {
        const { data: { message } } = await axios.post('api/admin/projects/' + this.$route.params.id, { expertIdToAssign: event.userId });

        this.getProject(this.$route.params.id);

        if (message === "Expert already matched") {
          this.notificationMessage = "Expert already matched";
          this.notificationType = "error";
        } else if (message === "OK") {
          this.notificationMessage = "Expert updated successfully!";
          this.notificationType = "success";
        }
      } catch (error) {
        this.notificationMessage = "Failed to update the Expert!";
        this.notificationType = "error";
        this.$router.push('/admin/projects');
      } finally {
        this.loading = false;
      }
    }, 200),

    projectDescription() {
      return this.project.description.replaceAll("\n", "<br/>")
    },

    changeTab(tab) {
      window.sessionStorage.setItem('openTab', JSON.stringify({'projectId': this.project.id, 'tab': tab}))

      this.activeTab = tab;
    },

    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do YYYY')
    },

    encodeS3URI(filename) {
      const encoding = {
        '+': "%2B",
        '!': "%21",
        '"': "%22",
        '#': "%23",
        '$': "%24",
        '&': "%26",
        "'": "%27",
        '(': "%28",
        ')': "%29",
        '*': "%2A",
        ',': "%2C",
        ':': "%3A",
        ';': "%3B",
        '=': "%3D",
        '?': "%3F",
        '@': "%40",
      };

      return encodeURI(filename) // Do the standard url encoding
          .replace(
              /(\+|!|"|#|\$|&|'|\(|\)|\*|\+|,|:|;|=|\?|@)/img,
              function (match) {
                return encoding[match];
              }
          );

    },

    openFile(file) {
      const url = process.env.VUE_APP_AWS_LINK
          + (file.url.includes(file.name) ? this.encodeS3URI(file.url) : file.url)

      return window.open(url, '_blank')
    },

    async toggleProjectState(projectId) {
      if (this.project.deleted_at) {
        await this.restoreProject(projectId);
      } else {
        await this.archiveProject(projectId);
      }
    },

    archiveProject: async function (projectId) {
      try {
        await axios.post(`/api/admin/projects/${projectId}/archive`);

        this.$emit('updateList');
        this.project.deleted_at = Date.now();
        this.notificationMessage = "Project archived successfully!";
        this.notificationType = "success";
      } catch (err) {
        console.error("Failed to archive a project:", err);
        this.notificationMessage = "Failed to archive the project!";
        this.notificationType = "error";
      }
    },

    restoreProject: async function (projectId) {
      try {
        await axios.post(`/api/admin/projects/${projectId}/restore`);
        this.$emit('updateList');
        this.project.deleted_at = null;
        this.notificationMessage = "Project restored successfully!";
        this.notificationType = "success";
      } catch (err) {
        console.error("Failed to restore project:", err);
        this.notificationMessage = "Failed to restore the project!";
        this.notificationType = "error";
      }
    }
  }
}
</script>

<template>
  <AdminLayout>
    <ProjectStatusChangeAlert :message="notificationMessage" :type="notificationType" v-show="notificationMessage" />
    <LoadingPage v-if="pageLoading" />

    <Page v-else style="padding-top: 56px; padding-bottom: 56px"
          :actionGroups="[
    {
      title: 'More Actions',
      actions: [
        {
          content: actionGroup,
          onAction: () => this.toggleProjectState(this.project.id),
        },
        ...(this.project?.deleted_at === null && (['available', 'matched', 'claimed', 'in_progress'].includes(this.project?.status)) ? [{
          content: 'Assign Project',
          onAction: () => toggleAssignModal(true),
        }] : []),
        ...(this.project?.deleted_at === null && (this.project?.status === 'matched' || this.project?.status === 'claimed') ? [{
          content: 'Send To Available Projects',
          onAction: () => moveToAvailable(this.project.id),
        }] : [])
      ],
    },
  ]"
    >
      <template #pageTitle>
        <InlineStack gap="100">
          <Text variant="headingLg" as="p">{{ project.name }}</Text>
          <div>
            <Badge v-if="project.deleted_at" tone="critical">Archived</Badge>
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
          </div>
        </InlineStack>
        <Text as="p" variant="bodySm" tone="subdued">
          <Link :url="project?.url && project?.url.startsWith('https://') ? project?.url : 'https://' + project?.url" target="_blank" removeUnderline style="color: #6E6E73;">
            {{ project?.url }}
          </Link>
        </Text>
        <Text as="p" variant="bodyMd" tone="subdued"> Referred By: <span style="font-weight: 700;">{{ project.client?.source }}</span></Text>
      </template>

      <BlockStack gap="600">
        <Tabs style="padding: 0"
              :tabs="tabs"
              :selected="activeTab"
              @select="changeTab"
        />

        <template v-if="activeTab === 0">
          <BlockStack gap="400">
            <Card padding="600">
              <BlockStack gap="400">
                <InlineStack align="space-between" blockAlign="center">
                  <InlineStack align="space-between" blockAlign="center" style="flex: 1">
                    <InlineStack gap="200">
                      <AvatarFrame rounded size="lg" :user="project.client" />

                      <BlockStack gap="050">
                        <Text as="p" variant="bodyLg" fontWeight="semibold">
                          {{ project.client.first_name }} {{ project.client.last_name }}
                        </Text>
                        <Text as="p" variant="bodySm" tone="subdued">
                          {{ project.client.email }}
                        </Text>
                        <Text as="p" variant="bodySm" tone="subdued">
                          Client
                        </Text>
                      </BlockStack>
                    </InlineStack>

                    <InlineStack gap="200" v-if="project.preferred_expert">
                      <AvatarFrame rounded size="lg" :user="project.preferred_expert" />

                      <BlockStack gap="050">
                        <Text as="p" variant="bodyLg" fontWeight="semibold">
                          {{ project.preferred_expert.first_name }} {{ project.preferred_expert.last_name }}
                        </Text>
                        <Text as="p" variant="bodySm" tone="subdued">
                          Preferred Expert
                        </Text>
                      </BlockStack>
                    </InlineStack>
                  </InlineStack>

                  <InlineStack align="end" style="flex: 1">
                    <!--                  <Badge size="large">Local Time: 09:36am</Badge>-->
                  </InlineStack>
                </InlineStack>

                <Divider />

                <BlockStack gap="400">
                  <Text variant="bodyMd" as="p" fontWeight="semibold">
                    Project Description
                  </Text>

                  <Text variant="bodyMd" as="p" v-html="projectDescription()" />
                </BlockStack>

                <Divider v-if="project?.status === 'available' || project?.status === 'pending_match'"  />

                <InlineStack v-if="project?.status === 'available'" align="space-between" blockAlign="center">
                  <Text as="h2" variant="headingSm">
                    Awaiting Expert to Claim this Project
                  </Text>
                </InlineStack>

                <InlineStack v-else-if="project?.status === 'pending_match'" align="space-between" blockAlign="center">
                  <InlineStack gap="200">
                    <Button v-if="project.preferred_expert" variant="primary" tone="success" :icon="CheckCircle" @click="assignPreferred" :loading="loading">Assign Preferred</Button>

                    <InputBtn :icon="PlusCircleIcon" @click="toggleAssignModal(false)" :loading="loading">Assign Project</InputBtn>

                    <Button @click="updateProject('available')" :loading="loading">Send to Available Projects</Button>
                  </InlineStack>
                </InlineStack>

                <template v-if="projectFiles.length">
                  <Divider />

                  <BlockStack gap="200">
                    <Text fontWeight="semibold">Attached Files</Text>

                    <InlineStack gap="200">
                      <Button :icon="AttachmentIcon" v-for="file in projectFiles" :key="file.id" @click="openFile(file)">{{ file.name }}</Button>
                    </InlineStack>
                  </BlockStack>
                </template>

                <template v-if="messageFiles.length">
                  <Divider />

                  <BlockStack gap="200">
                    <Text fontWeight="semibold">Attached Files (Messages)</Text>

                    <InlineStack gap="200">
                      <Button :icon="AttachmentIcon" v-for="file in messageFiles" :key="file.id" @click="openFile(file)">{{ file.name }}</Button>
                    </InlineStack>
                  </BlockStack>
                </template>
              </BlockStack>
            </Card>
          </BlockStack>

          <BlockStack v-if="project.active_assignment && isAssigned" gap="200">
            <Text as="p" variant="bodyMd">Matched Expert</Text>

            <Card padding="600">
              <BlockStack gap="800">
                <InlineStack align="space-between" blockAlign="center">
                  <UserBox role :user="project.active_assignment.expert" />

                  <BlockStack gap="050">
                    <Text as="p" variant="bodyLg" fontWeight="semibold">
                      {{ expertRating }}
                      <Text as="span" variant="bodyMd" fontWight="default">({{ totalReview }} Reviews)</Text>
                    </Text>
                    <Text as="p" variant="bodySm" tone="subdued">
                      {{ completedProjects }} Completed Projects
                    </Text>
                  </BlockStack>
                </InlineStack>

                <template v-if="project.active_assignment.offers.length">
                  <Divider/>

                  <BlockStack gap="400">
                    <Text variant="bodyMd" as="p" fontWeight="semibold">
                      Project Quotes
                    </Text>

                    <BlockStack gap="300">
                      <QuotaCard expert v-for="offer in project.active_assignment.offers" :key="offer.id" :quota="offer" />
                    </BlockStack>
                  </BlockStack>
                </template>

                <template v-if="project?.status === 'completed'">
                  <Divider/>

                  <BlockStack gap="400">
                    <Text variant="bodyMd" as="p" fontWeight="semibold">
                      Client Review
                    </Text>

                    <InlineStack align="space-between" gap="200" blockAlign="start" :wrap="false">
                      <BlockStack gap="100">
                        <Text as="p" variant="bodyMd">
                          {{ project?.review?.comment }}
                        </Text>
                      </BlockStack>

                      <BlockStack gap="100">
                        <Text as="p" variant="bodyMd" tone="subdued">
                          Client's rating on Expert
                        </Text>

                        <InlineStack gap="200" :wrap="false" align="start" blockAlign="center" v-if="project?.review">
                          <InlineStack :wrap="false">
                            <div style="width: 24px; height: 24px">
                              <StarFullIcon style="width: 24px; height: 24px" v-if="project?.review?.rate > 0" />
                              <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                            </div>
                            <div style="width: 24px; height: 24px">
                              <StarFullIcon style="width: 24px; height: 24px" v-if="project?.review?.rate > 1" />
                              <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                            </div>
                            <div style="width: 24px; height: 24px">
                              <StarFullIcon style="width: 24px; height: 24px" v-if="project?.review?.rate > 2" />
                              <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                            </div>
                            <div style="width: 24px; height: 24px">
                              <StarFullIcon style="width: 24px; height: 24px" v-if="project?.review?.rate > 3" />
                              <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                            </div>
                            <div style="width: 24px; height: 24px">
                              <StarFullIcon style="width: 24px; height: 24px" v-if="project?.review?.rate > 4" />
                              <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                            </div>
                          </InlineStack>

                          <Text variant="headingMd" as="p">
                            {{ project?.review?.rate.toFixed(2) }}
                          </Text>
                        </InlineStack>
                      </BlockStack>
                    </InlineStack>
                  </BlockStack>
                </template>
              </BlockStack>
            </Card>
          </BlockStack>
        </template>

        <ChatroomTab v-if="activeTab === 1" userType="admin"
                     :userActive="false"
                     :project="project"
                     @updateProject="getProject"
        />

        <ProjectInvoicesTab v-if="activeTab === 2" :project="project" />
        <ProjectHistoryTab v-if="activeTab === 3" :project="project" />

      </BlockStack>
    </Page>

    <AssignExpertModal v-if="assignModal" :projectId="projectId" @close="toggleAssignModal" @assignProject="closeModal" />
  </AdminLayout>
</template>

<style scoped>

</style>
