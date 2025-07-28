<script>
import ExpertLayout from "@/layout/ExpertLayout.vue";
import AddIcon from "@/components/icons/AddIcon.vue";
import ProjectDescriptionTab from "@/components/tabs/ProjectDescriptionTab.vue";
import AlertBubbleIcon from "@/components/icons/AlertBubbleIcon.vue";
import PersonAddIcon from "@/components/icons/PersonAddIcon.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import ReportModal from "@/components/modals/ReportModal.vue";
import AddTeamModal from "@/components/modals/AddTeamMemberModal.vue";
import ProjectInvoicesTab from "@/components/tabs/ProjectInvoicesTab.vue";
import ProjectHistoryTab from "@/components/tabs/ProjectHistoryTab.vue";
import axios from 'axios';
import ChatroomTab from "@/components/tabs/ChatroomTab.vue";
import PlusCircleIcon from "@/components/icons/PlusCircleIcon.vue";
import ProjectClientTab from "@/components/tabs/ProjectClientTab.vue";
import ProjectClaimedTab from "@/components/tabs/ProjectClaimedTab.vue";
import AddQuotaModal from "@/components/modals/AddQuotaModal.vue";
import ClaimModal from "@/components/modals/ClaimModal.vue";
import ExpertCompleteModal from "@/components/modals/ExpertCompleteModal.vue";
import LoadingPage from "@/components/cards/LoadingPage.vue";
import DeleteModal from "@/components/modals/DeleteModal.vue";
import {debounce} from "@/directives/debounce";
import newMessagesCount from "@/mixins/newMessagesCount";

export default {
  name: "ProjectPage",

  components: {
    DeleteModal,
    LoadingPage,
    ExpertCompleteModal,
    ClaimModal,
    AddQuotaModal,
    ProjectClientTab,
    ProjectClaimedTab,
    ChatroomTab,
    AddTeamModal,
    ReportModal,
    ExpertLayout,
    ProjectDescriptionTab,
    ProjectInvoicesTab,
    ProjectHistoryTab,

  },

  mixins: [newMessagesCount],

  data() {
    return {
      AddIcon,
      CheckCircle,
      AlertBubbleIcon,
      PersonAddIcon,
      PlusCircleIcon,
      isQuotePaid: false,
      editQuote: false,

      isMobile: screen.width <= 760,

      activeTab: 0,

      pageLoading: false,
      showOfferModal: false,
      showUpdateOfferModal: false,
      showAddScopeModal: false,
      showAddTeamModal: false,
      showReportModal: false,
      showClaimModal: false,
      showCompleteModal: false,
      showDeleteModal: false,

      releaseProject: false,

      project: {},
      projectClient: {},
      projectMessages: {},
      projectDescription: {},
      projectInvoices: {},
      projectHistory: {},
      tabLoading: false,
      tabId: {
        USER: 0,
        CHAT: 1,
        INVOICES: 2,
        HISTORY: 3,
      },

      interval: null,
      newMessagesCount: null
    }
  },

  mounted() {
    let projectId = this.$route.params.id
    let openTab = JSON.parse(sessionStorage.getItem('openTab'))

    if (openTab && projectId) {
      this.activeTab = openTab.tab
      this.changeTab(this.activeTab)
    } else if (projectId) {
      this.onCreate(projectId)
    }
  },

  // eslint-disable-next-line
  beforeRouteUpdate(to, from) {
    this.onDestroy()

    let projectId = to.params.id

    projectId ? this.onCreate(projectId) : null;
  },

  unmounted() {
    this.onDestroy()
  },

  beforeRouteLeave() {
    this.onDestroy()
  },

  computed: {
    projectStatus() {
      if (this.project.status === 'pending_match'
          || this.project.status === 'claimed'
          || this.project.status === 'available') {
        return 0
      } else {
        return 1
      }
    },

    tabs() {
      if (this.projectStatus === 0) {
        return [
          {
            id: 'description',
            content: 'Project Description'
          },
          {
            id: 'chat',
            content: 'Chatroom'
          },
        ];
      } else if (this.projectStatus === 1) {
        return [
          {
            id: 'client',
            content: 'Client'
          },
          {
            id: 'chat',
            content: 'Chatroom'
          },
          {
            id: 'invoices',
            content: 'Invoices'
          },
          {
            id: 'history',
            content: 'Status History'
          },
        ];
      } else {
        return []
      }
    },

    actionGroup() {
      return this.project.status === 'matched' ? [
        {
          title: 'More Actions',
          actions: [
            {
              icon: PlusCircleIcon,
              content: 'Create an Offer',
              accessibilityLabel: 'Create an Offer',
              onAction: () => this.toggleOfferModal(),
            },
            {
              disabled: true,
              icon: PersonAddIcon,
              content: 'Add Team Member',
              accessibilityLabel: 'Add Team Member',
              onAction: () => this.toggleAddTeamModal(),
            },
            {
              disabled: true,
              icon: AlertBubbleIcon,
              content: 'Report a Problem',
              accessibilityLabel: 'Report a Problem',
              onAction: () => this.toggleReportModal(),
            }
          ],
        },
      ] : [
        {
          title: 'More Actions',
          actions: [
            {
              icon: PlusCircleIcon,
              content: 'Add to Scope',
              accessibilityLabel: 'Add to Scope',
              onAction: () => this.toggleAddScopeModal(),
              disabled: !this.isQuotePaid,
            },
            {
              disabled: true,
              icon: PersonAddIcon,
              content: 'Add Team Member',
              accessibilityLabel: 'Add Team Member',
              onAction: () => this.toggleAddTeamModal(),
            },
            {
              disabled: true,
              icon: AlertBubbleIcon,
              content: 'Report a Problem',
              accessibilityLabel: 'Report a Problem',
              onAction: () => this.toggleReportModal(),
            },
          ],
        },
      ]
    }
  },

  watch: {
    projectClient(data) {
      const QuoteData = data?.active_assignment?.offers || [];
      this.isQuotePaid = !QuoteData.some(offer => offer.status === 'send');
    }
  },

  methods: {
    onCreate(projectId) {
      this.pageLoading = true;
      this.project = {};
      if (projectId) this.updateProject(projectId)
    },

    async getMessages(id) {
      try {
        const res = await axios.get('api/expert/projects/' + id, { params: { 'tabName': 'chatroom' } });
        this.projectMessages = res.data.project;
        this.project = res.data.project;
        this.tabLoading = false;
      } catch (error) {
        this.$router.push('/expert');
      }
    },

    getDescription(id) {
      this.tabLoading = true;
      axios.get('api/expert/projects/' + id, {params: {'tabName': 'description'}}).then(res => {
        this.projectDescription = res.data.project;
        this.project = res.data.project;
        this.tabLoading = false;
      }).catch(() => {
        this.$router.push('/expert')
      });
    },

    getInvoices(id) {
      this.tabLoading = true;
      axios.get('api/expert/projects/' + id, {params: {'tabName': 'invoices'}}).then(res => {
        this.projectInvoices = res.data.project;
        this.project = res.data.project;
        this.tabLoading = false;
      }).catch(() => {
        this.$router.push('/expert')
      });
    },

    getHistory(id) {
      this.tabLoading = true;
      axios.get('api/expert/projects/' + id, {params: {'tabName': 'history'}}).then(res => {
        this.projectHistory = res.data.project;
        this.project = res.data.project;
        this.tabLoading = false;
      }).catch(() => {
        this.$router.push('/expert')
      });
    },

    onDestroy() {
      clearInterval(this.interval)

      this.activeTab = this.tabId.USER;

      sessionStorage.removeItem('openTab')
    },

    async updateProject(id) {
      await axios.get('api/expert/projects/' + id).then(res => {
        this.projectClient = res.data.project
        this.project = res.data.project
        this.pageLoading = false;
      }).catch(() => {
        this.$router.push('/expert')
      });
    },

    async changeTab(tab) {
      clearInterval(this.interval)
      let projectId = this.$route.params.id
      if (!projectId)
        return

      window.sessionStorage.setItem('openTab', JSON.stringify({'projectId': this.project.id, 'tab': tab}))

      if (tab === this.tabId.USER) {
        if (!Object.keys(this.projectClient).length) {
          this.onCreate(projectId)
        }
      }
      else if (tab === this.tabId.CHAT) {
        this.tabLoading = true;
        await this.getMessages(projectId)
        this.getDescription(projectId)
      } else if (tab === this.tabId.DESCRIPTION) {
        if (!Object.keys(this.projectDescription).length) {
          this.getDescription(projectId)
        }
      } else if (tab === this.tabId.INVOICES) {
        if (!Object.keys(this.projectInvoices).length) {
          this.getInvoices(projectId)
        }
      } else if (tab === this.tabId.HISTORY) {
          this.getHistory(projectId)
      }
      this.activeTab = tab;
    },

    toggleOfferModal() {
      this.showOfferModal = !this.showOfferModal
    },

    toggleUpdateOfferModal() {
      this.showUpdateOfferModal = !this.showUpdateOfferModal
      this.editQuote = !this.editQuote
    },

    toggleAddScopeModal() {
      this.showAddScopeModal = !this.showAddScopeModal
    },

    toggleCompletedModal() {
      this.showCompletedModal = !this.showCompletedModal
    },

    toggleAddTeamModal() {
      this.showAddTeamModal = !this.showAddTeamModal
    },

    toggleReportModal() {
      this.showReportModal = !this.showReportModal
    },

    toggleClaimModal() {
      this.updateProject(this.$route.params.id)
      this.showClaimModal = !this.showClaimModal
    },

    toggleCompleteModal() {
      this.showCompleteModal = !this.showCompleteModal
    },

    toggleDeleteModal() {
      this.showDeleteModal = !this.showDeleteModal
    },

    toggleReleaseModal() {
      this.releaseProject = true;
      this.showDeleteModal = !this.showDeleteModal
    },

    markAsComplete: debounce(async function() {
      this.toggleCompleteModal();

      let id = this.$route.params.id;

      await axios.put('api/expert/projects/' + id + '/completed').then(() => {
        this.updateProject(id)
      }).catch(() => {
        this.$router.push('/expert')
      });
    }, 200),

    updateProjectMessagesFiles(newMessageFile){
      this.projectDescription.project_files.push(newMessageFile);
    }
  }
}
</script>

<template>
  <ExpertLayout :newMessagesCount="newMessagesCount">
    <LoadingPage v-if="pageLoading" />

    <Page v-else
          :style="isMobile ? {padding: '32px 0'} : {padding: '56px 0'}"
          :actionGroups="!['claimed', 'pending_match', 'available'].includes(project.status) ? actionGroup : []"
    >
      <template #pageTitle>
        <BlockStack gap="100" v-if="isMobile">
          <div>
            <Badge v-if="project.status === 'claimed'"
                   tone="attention" size="large">Read</Badge>
            <Badge v-else-if="project.status === 'matched'"
                   tone="magic" size="large">Matched</Badge>
            <Badge v-else-if="project.status === 'pending_payment'"
                   tone="critical" size="large">Pending Payment</Badge>
            <Badge v-else-if="project.status === 'in_progress'"
                    tone="info" size="large">In Progress</Badge>
            <Badge v-else-if="project.status === 'expert_completed'"
                   tone="info" size="large">Awaiting Approval</Badge>
            <Badge v-else-if="project.status === 'completed'"
                   tone="success" size="large">Completed</Badge>
            <Badge v-if="project?.urgent" tone="critical" size="large">Urgent</Badge>
          </div>

          <Text variant="headingLg" as="p">{{ project.name }}</Text>
        </BlockStack>

        <InlineStack gap="200" v-else>
          <Text variant="headingLg" as="p">{{ project.name }}</Text>

          <div>
            <Badge v-if="project.status === 'claimed'"
                   tone="attention" size="large">Read</Badge>
            <Badge v-else-if="project.status === 'matched'"
                   tone="magic" size="large">Matched</Badge>
            <Badge v-else-if="project.status === 'pending_payment'"
                   tone="critical" size="large">Pending Payment</Badge>
            <Badge v-else-if="project.status === 'in_progress'"
                    tone="info" size="large">In Progress</Badge>
            <Badge v-else-if="project.status === 'expert_completed'"
                   tone="info" size="large">Awaiting Approval</Badge>
            <Badge v-else-if="project.status === 'completed'"
                   tone="success" size="large">Completed</Badge>
            <Badge v-if="project?.urgent" tone="critical" size="large">Urgent</Badge>
          </div>
        </InlineStack>
        <Text as="p" variant="bodySm" tone="subdued">
          <Link
              :url="project?.url && project.url.startsWith('https://') ? project.url : 'https://' + project?.url"
              target="_blank"
              removeUnderline
              style="color: #6E6E73;">
            {{ project?.url }}
          </Link>
        </Text>
        <Text as="p" variant="bodyMd" tone="subdued"> Referred By: <span style="font-weight: 700;">{{ project.client?.source }}</span></Text>
      </template>

      <div :style="isMobile ? {padding: '0 16px'} : null" v-if="project">
        <LoadingPage v-if="tabLoading" />
        <BlockStack gap="600" v-else>
          <Tabs v-if="project.status !== 'claimed'" style="padding: 0"
                :tabs="tabs"
                :selected="activeTab"
                @select="changeTab"
          />
          <template  v-if="project.status === 'claimed'">
            <ProjectClaimedTab :project="project"
                               @claimedProject="toggleClaimModal"
                               @releaseProject="toggleReleaseModal"/>
          </template>

          <template v-else>
              <ProjectClientTab v-if="activeTab === this.tabId.USER" :project="projectClient"
                                :isQuotePaid = isQuotePaid
                                @createOffer="toggleOfferModal"
                                @updateOffer="toggleUpdateOfferModal"
                                @addScope="toggleAddScopeModal"
                                @markComplete="toggleCompleteModal"
                                @releaseProject="toggleDeleteModal"
              />

              <template v-if="activeTab === this.tabId.CHAT">
                <ProjectDescriptionTab :project="projectDescription" />
                <ChatroomTab userType="expert"
                             @showOffer="toggleOfferModal"  @showScope="toggleAddScopeModal" @updateProjectMessagesFiles="updateProjectMessagesFiles"
                             :userActive="false" :project="projectMessages" @updateMessagesCount="updateMessagesCount" @updateProject="updateProject" />
              </template>

              <ProjectInvoicesTab v-if="activeTab === this.tabId.INVOICES" :project="projectInvoices" expert />

              <ProjectHistoryTab v-if="activeTab === this.tabId.HISTORY" :project="projectHistory"/>
          </template>
        </BlockStack>
      </div>
    </Page>

    <ExpertCompleteModal v-show="showCompleteModal" @close="toggleCompleteModal" @complete="markAsComplete"/>
    <ClaimModal v-show="showClaimModal" @close="toggleClaimModal" :id="project.id" />
    <AddQuotaModal v-show="showOfferModal" @close="toggleOfferModal"
                   :clientPrepaidHours="project?.client?.prepaid_hours" :projectId="project.id" @update="() => {
                     this.toggleOfferModal();
                     this.updateProject(this.$route.params.id)
                   }" />
    <AddQuotaModal scope v-show="showAddScopeModal" @close="toggleAddScopeModal"
                   :clientPrepaidHours="project?.client?.prepaid_hours" :projectId="project.id" @update="() => {
                     this.toggleAddScopeModal()
                     this.updateProject(this.$route.params.id)
                   }" />
    <AddQuotaModal v-show="showUpdateOfferModal" :editQuote="editQuote" @close="toggleUpdateOfferModal"
                   :clientPrepaidHours="project?.client?.prepaid_hours" :projectId="project.id" @update="() => {
                     this.toggleUpdateOfferModal();
                     this.updateProject(this.$route.params.id)
                   }" />
    <AddTeamModal v-show="showAddTeamModal" @close="toggleAddTeamModal" />
    <ReportModal v-show="showReportModal" @close="toggleReportModal" />
    <DeleteModal v-show="showDeleteModal" @close="toggleDeleteModal" userType="expert" :toAvailable="releaseProject" :projectId="project.id" />
  </ExpertLayout>
</template>

<style scoped>

</style>
