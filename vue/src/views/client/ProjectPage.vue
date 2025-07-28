<script>
import ClientLayout from "@/layout/ClientLayout.vue";
import AddIcon from "@/components/icons/AddIcon.vue";
import ProjectExpertTab from "@/components/tabs/ProjectExpertTab.vue";
import ProjectDescriptionTab from "@/components/tabs/ProjectDescriptionTab.vue";
import AlertBubbleIcon from "@/components/icons/AlertBubbleIcon.vue";
import DeleteIcon from "@/components/icons/DeleteIcon.vue";
import PersonAddIcon from "@/components/icons/PersonAddIcon.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import ReportModal from "@/components/modals/ReportModal.vue";
import AddTeamModal from "@/components/modals/AddTeamMemberModal.vue";
import CompleteModal from "@/components/modals/CompleteModal.vue";
import ProjectInvoicesTab from "@/components/tabs/ProjectInvoicesTab.vue";
import ProjectHistoryTab from "@/components/tabs/ProjectHistoryTab.vue";
import axios from 'axios';
import ChatroomTab from "@/components/tabs/ChatroomTab.vue";
import QuoteModal from "@/components/modals/QuoteModal.vue";
import FinishModal from "@/components/modals/FinishModal.vue";
import LoadingPage from "@/components/cards/LoadingPage.vue";
import DeleteModal from "@/components/modals/DeleteModal.vue";
import newMessagesCount from "@/mixins/newMessagesCount";

export default {
  name: "ProjectPage",

  components: {
    LoadingPage,
    FinishModal,
    QuoteModal,
    ChatroomTab,
    CompleteModal,
    AddTeamModal,
    ReportModal,
    ClientLayout,
    ProjectExpertTab,
    ProjectDescriptionTab,
    ProjectInvoicesTab,
    ProjectHistoryTab,
    DeleteModal,
  },

  mixins: [newMessagesCount],

  data() {
    return {
      AddIcon,
      CheckCircle,
      AlertBubbleIcon,
      PersonAddIcon,
      DeleteIcon,

      isMobile: screen.width <= 760,

      activeTab: 0,

      showCompletedModal: false,
      showAddTeamModal: false,
      showReportModal: false,
      showOfferModal: false,
      showFinishModal: false,
      showDeleteModal: false,

      offer: null,

      project: {},
      projectExpert: {},
      projectMessages: {},
      projectDescription: {},
      projectInvoices: {},
      interval: null,
      pageLoading: false,
      tabLoading: false,
      newMessagesCount: null
    }
  },

  mounted() {
    let projectId = this.$route.params.id
    let openTab = JSON.parse(sessionStorage.getItem('openTab'))

    if (openTab && projectId) {
      this.activeTab = openTab.tab
      if (openTab.tabsLength === 2 && this.activeTab === 1) {
        this.getDescription(projectId)
      } else {
        this.changeTab(this.activeTab)
      }
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
    this.onDestroy();
  },

  beforeRouteLeave() {
    this.onDestroy();
  },

  computed: {
    projectStatus() {
      const { status, messages } = this.project || {};
      const hasMessages = Array.isArray(messages) && messages.length > 0;

      if (status === 'pending_match' || (status === 'available' && !hasMessages)) {
        return -1;
      }

      if (status === 'claimed') {
        return 0;
      }

      return 1;
    },

    tabs() {
      if (this.projectStatus < 1) {
        return [
          {
            id: 'expert',
            content: 'Expert'
          },
          {
            id: 'description',
            content: 'Project Description'
          },
        ];
      } else if (this.projectStatus === 1) {
        return [
          {
            id: 'expert',
            content: 'Expert'
          },
          {
            id: 'chat',
            content: 'Chatroom'
          },
          {
            id: 'invoices',
            content: 'Invoices'
          },
          // {
          //   id: 'history',
          //   content: 'Status History'
          // },
        ];
      } else {
        return []
      }
    },

    actionGroup() {
      if (this.projectStatus === -1) {
        return [
          {
            title: 'More Actions',
            actions: [
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
              {
                icon: DeleteIcon,
                content: 'Delete Request',
                accessibilityLabel: 'Delete Request',
                onAction: () => this.toggleDeleteModal(),
              }
            ],
          },
        ];
      } else {
        return [
          {
            title: 'More Actions',
            actions: [
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
        ];
      }
    }
  },

  methods: {
    onCreate(projectId) {
      this.pageLoading = true;
      this.project = {};

      if (projectId) this.updateProject(projectId)
      let openTab = JSON.parse(sessionStorage.getItem('openTab'))
      if (openTab && openTab.projectId === parseInt(projectId)) this.activeTab = openTab.tab
    },

    async getMessages(id) {
      try {
        const res = await axios.get('api/client/projects/' + id, { params: { 'tabName': 'chatroom' } });
        this.projectMessages = res.data.project;
        this.project = res.data.project;
        this.tabLoading = false;
      } catch (error) {
        this.$router.push('/client');
      }
    },

    getDescription(id) {
      this.tabLoading = true;
      axios.get('api/client/projects/' + id, {params: {'tabName': 'description'}}).then(res => {
        this.projectDescription = res.data.project;
        this.project = res.data.project;
        this.tabLoading = false;
      }).catch(() => {
        this.$router.push('/client')
      });
    },

    getInvoices(id) {
      this.tabLoading = true;
      axios.get('api/client/projects/' + id, {params: {'tabName': 'invoices'}}).then(res => {
        this.projectInvoices = res.data.project;
        this.project = res.data.project;
        this.tabLoading = false;
      }).catch(() => {
        this.$router.push('/client')
      });
    },

    onDestroy() {
      clearInterval(this.interval)

      this.activeTab = 0;

      sessionStorage.removeItem('openTab')
    },

    async updateProject(id) {
      await axios.get('api/client/projects/' + id).then(res => {
        this.projectExpert = res.data.project
        this.project = res.data.project
        this.pageLoading = false;
      }).catch(() => {
        this.$router.push('/client')
      });
    },

    async changeTab(tab) {
      clearInterval(this.interval)
      let projectId = this.$route.params.id
      if (!projectId)
        return

      window.sessionStorage.setItem('openTab', JSON.stringify({'projectId': this.project.id, 'tab': tab, 'tabsLength': this.tabs.length}))

      if (tab === 0) {
        if (!Object.keys(this.projectExpert).length) {
          this.onCreate(projectId)
        }
      }
      else if (tab === 1) {
        if(this.tabs.length === 2) {
          if (!Object.keys(this.projectDescription).length) {
            this.getDescription(projectId)
          }
        } else  {
          this.tabLoading = true;
          await this.getMessages(projectId)
          this.getDescription(projectId)
        }

      } else if (tab === 2) {
        if (!Object.keys(this.projectInvoices).length) {
          this.getInvoices(projectId)
        }
      }
      this.activeTab = tab;
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

    toggleDeleteModal() {
      this.showDeleteModal = !this.showDeleteModal
    },

    toggleOfferModal(offer) {
      this.offer = offer;
      this.showOfferModal = !this.showOfferModal
    },

    toggleFinishModal() {
      this.showFinishModal = !this.showFinishModal
    },

    payQuotaAndClose(offerId) {
      this.payQuota(offerId);
      this.showOfferModal = !this.showOfferModal
    },

    async payQuota(offerId) {
      let id = this.$route.params.id

      axios.get('api/client/projects/' + id + '/offer/' + offerId).then(() => {
        this.updateProject(id)
      }).catch(() => {
        this.$router.push('/client')
      });
    },

    async declineOffer(offerId) {
      let id = this.$route.params.id

      await axios.get('api/client/projects/' + id + '/offer/' + offerId + '/decline').then(() => {
        this.updateProject(id)
      }).catch(() => {
        this.$router.push('/client')
      });
    },

    async markAsCompleted(review) {
      this.showFinishModal = !this.showFinishModal

      let id = this.$route.params.id

      await axios.post('api/client/projects/' + id + '/complete', {
        'rate': review.rate,
        'comment': review.comment,
        'expert_id': this.project.active_assignment.expert.id,
        'communication': review.communication,
        'quality': review.quality,
        'recommendation': review.recommendation,
        'timeToStart': review.timeToStart,
        'valueForMoney': review.valueForMoney,
        'valueRange': review.valueRange
      }).then(() => {
        this.updateProject(id)
      }).catch(() => {
        this.$router.push('/client')
      });
    },

    async notThereYet() {
      let id = this.$route.params.id

      await axios.get('api/client/projects/' + id + '/not_yet').then(() => {
        this.updateProject(id)
      }).catch(() => {
        this.$router.push('/client')
      });
    },

    updateProjectMessagesFiles(newMessageFile){
      this.projectDescription.project_files.push(newMessageFile);
    }
  }
}
</script>

<template>
  <ClientLayout :newMessagesCount="newMessagesCount">
    <LoadingPage v-if="pageLoading" />

    <Page v-else
          :style="isMobile ? {padding: '32px 0'} : {padding: '56px 0'}"
          :subtitle="project.url"
          :actionGroups="(project.status !== 'pending_match' || project.status !== 'available') ? actionGroup : null"
    >
      <template #pageTitle>
        <BlockStack gap="100" v-if="isMobile">
          <div>
            <Badge v-if="['pending_match', 'available', 'claimed'].includes(project.status)"
                   tone="attention" size="large">Pending Match</Badge>
            <Badge v-else-if="project.status === 'matched'"
                   tone="magic" size="large">Matched</Badge>
            <Badge v-else-if="project.status === 'pending_payment'"
                   tone="critical" size="large">Pending Payment</Badge>
            <Badge  v-else-if="project.status === 'in_progress'"
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
            <Badge v-if="['pending_match', 'available', 'claimed'].includes(project.status)"
                   tone="attention" size="large">Pending Match</Badge>
            <Badge v-else-if="project.status === 'matched'"
                   tone="magic" size="large">Matched</Badge>
            <Badge v-else-if="project.status === 'pending_payment'"
                   tone="critical" size="large">Pending Payment</Badge>
            <Badge  v-else-if="project.status === 'in_progress'"
                    tone="info" size="large">In Progress</Badge>
            <Badge v-else-if="project.status === 'expert_completed'"
                   tone="info" size="large">Awaiting Approval</Badge>
            <Badge v-else-if="project.status === 'completed'"
                   tone="success" size="large">Completed</Badge>
            <Badge v-if="project?.urgent" tone="critical" size="large">Urgent</Badge>
          </div>
        </InlineStack>
      </template>

      <div :style="isMobile ? {padding: '0 16px'} : null">
        <LoadingPage v-if="tabLoading" />
        <BlockStack gap="600" v-else>
          <Tabs style="padding: 0"
                :tabs="tabs"
                :selected="activeTab"
                @select="changeTab"
          />
          <ProjectExpertTab v-if="activeTab === 0" :project="projectExpert"
                            @showQuotaModal="toggleOfferModal"
                            @declineOffer="declineOffer"
                            @showFinishModal="toggleFinishModal"
                            @notYet="notThereYet"/>

          <template v-if="projectStatus === 1 && activeTab === 1">
            <ProjectDescriptionTab :project="projectDescription" />
            <ChatroomTab v-if="activeTab === 1" userType="client"
                         :userActive="false"
                         :project="projectMessages"
                         @updateProject="getMessages"
                         @showQuotaModal="toggleOfferModal"
                         @declineOffer="declineOffer"
                         @showFinishModal="toggleFinishModal"
                         @updateMessagesCount="updateMessagesCount"
                         @updateProjectMessagesFiles="updateProjectMessagesFiles"
                         @notYet="notThereYet"/>
          </template>

          <ProjectDescriptionTab v-if="this.activeTab === 1 && tabs.length  === 2" :project="projectDescription" />

          <ProjectInvoicesTab v-if="activeTab === 2" :project="projectInvoices" />

          <ProjectHistoryTab v-if="activeTab === 4" :project="project"/>
        </BlockStack>
      </div>
    </Page>

    <FinishModal v-show="showFinishModal" @close="toggleFinishModal" @createReview="markAsCompleted" :project="project"  />
    <QuoteModal v-show="showOfferModal" :quota="offer" @close="toggleOfferModal" @confirm="payQuotaAndClose" @confirmNoClose="payQuota" :project="project" />
    <CompleteModal v-show="showCompletedModal" @close="toggleCompletedModal" />
    <AddTeamModal v-show="showAddTeamModal" @close="toggleAddTeamModal" />
    <ReportModal v-show="showReportModal" @close="toggleReportModal" />
    <DeleteModal v-show="showDeleteModal" @close="toggleDeleteModal" :projectId="project.id" />
  </ClientLayout>
</template>

<style scoped>

</style>
