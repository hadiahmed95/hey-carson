<script>
import ClientLayout from "@/layout/ClientLayout.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import AlertCircleIcon from "@/components/icons/AlertCircleIcon.vue";
import ClockIcon from "@/components/icons/ClockIcon.vue";
import DoubleCheckIcon from "@/components/icons/DoubleCheckIcon.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import UploadIcon from "@/components/icons/UploadIcon.vue";
import SendIcon from "@/components/icons/SendIcon.vue";
import NoteIcon from "@/components/icons/NoteIcon.vue";
import ProjectDescriptionTab from "@/components/tabs/ProjectDescriptionTab.vue";
import ProjectExpertTab from "@/components/tabs/ProjectExpertTab.vue";
import ChatroomTab from "@/components/tabs/ChatroomTab.vue";
import QuoteModal from "@/components/modals/QuoteModal.vue";
import AlertBubbleIcon from "@/components/icons/AlertBubbleIcon.vue";
import PersonAddIcon from "@/components/icons/PersonAddIcon.vue";
import ReportModal from "@/components/modals/ReportModal.vue";
import AddTeamModal from "@/components/modals/AddTeamMemberModal.vue";
import CompleteModal from "@/components/modals/CompleteModal.vue";
import ProjectInvoicesTab from "@/components/tabs/ProjectInvoicesTab.vue";

export default {
  name: "ProjectPage",

  components: {
    CompleteModal,
    AddTeamModal,
    ReportModal,
    ChatroomTab,
    ProjectExpertTab,
    ProjectDescriptionTab,
    ProjectInvoicesTab,
    ClientLayout,
    QuoteModal
  },

  data() {
    return {
      CheckCircle,
      AlertCircleIcon,
      ClockIcon,
      DoubleCheckIcon,
      SearchIcon,
      UploadIcon,
      SendIcon,
      NoteIcon,
      AlertBubbleIcon,
      PersonAddIcon,
      isMobile: screen.width <= 760,

      activeTab: 0,

      showOfferModal: false,
      showCompletedModal: false,
      showAddTeamModal: false,
      showReportModal: false,

      tabs: [
        {
          id: 'expert',
          content: 'Expert'
        },
        {
          id: 'chat',
          content: 'Chatroom'
        },
        {
          id: 'description',
          content: 'Project Description'
        },
        {
          id: 'invoices',
          content: 'Invoices'
        },
      ],

      quotas: [
        {
          id: 1,
          type: 'offer',
          time: 'May 13, 2024 / 02:45pm',
          rate: '$95.00',
          hours: '10 hours',
          total: '$950.00',
          paid: false
        }
      ],

      quota: {
        id: 1,
        type: 'offer',
        time: 'May 13, 2024 / 02:45pm',
        rate: '$95.00',
        hours: '10 hours',
        total: '$950.00',
        paid: false
      },

      project: {
        status: 'pending',
      },

      messages: [
        {
          id: 6,
          type: 'quote',
          content: {
            rate: '$95.00',
            time: '10 hours',
            total: '$950.00'
          },
          time: 'May 13, 2024 / 02:45pm',
          quoteType: 'offer',
          paid: false,
        },
        {
          id: 5,
          type: 'text',
          content: 'We\'ve matched you with the best expert for this project type. Should the conversation not go in the direction you were hoping, please let us know, and we will find you another match.',
          time: '2 hours ago',
          userType: 'expert',
          userName: '*EXPERT FULL NAME*',
          seen: true,
        },
        {
          id: 4,
          type: 'text',
          content: 'We\'ve matched you with the best expert for this project type. Should the conversation not go in the direction you were hoping, please let us know, and we will find you another match.',
          time: '3 hours ago',
          userType: 'client',
          userName: '*CLIENT FULL NAME*',
          seen: true,
        },
        {
          id: 3,
          type: 'text',
          content: 'We\'ve matched you with the best expert for this project type. Should the conversation not go in the direction you were hoping, please let us know, and we will find you another match.',
          time: '4 hours ago',
          userType: 'expert',
          userName: '*EXPERT FULL NAME*',
          seen: true,
        },
        {
          id: 2,
          type: 'text',
          content: 'We\'ve matched you with the best expert for this project type. Should the conversation not go in the direction you were hoping, please let us know, and we will find you another match.',
          time: '4 hours ago',
          userType: 'client',
          userName: '*CLIENT FULL NAME*',
          seen: true,
        },
        {
          id: 1,
          type: 'banner',
          bannerType: 'info',
          content: null,
          contentType: 'expertMatched',
          userType: 'client',
          time: null,
        },
      ]
    }
  },

  methods: {
    changeTab(tab) {
      this.activeTab = tab;
    },

    toggleOfferModal() {
      this.showOfferModal = !this.showOfferModal
    },

    toggleCompletedModal() {
      this.showCompletedModal = !this.showCompletedModal
    },

    toggleAddTeamModal() {
      this.showAddTeamModal = !this.showAddTeamModal
    },

    toggleReportModal() {
      this.showReportModal = !this.showReportModal
    }
  }
}
</script>

<template>
  <ClientLayout>
    <template v-if="isMobile">
      <Page style="padding: 32px 0"
            subtitle="*project url*"
            :actionGroups="[
    {
      title: 'More Actions',
      actions: [
        {
          icon: CheckCircle,
          content: 'Mark as Completed',
          accessibilityLabel: 'Mark as Completed',
          onAction: () => this.toggleCompletedModal(),
        },
        {
          icon: PersonAddIcon,
          content: 'Add Team Member',
          accessibilityLabel: 'Add Team Member',
          onAction: () => this.toggleAddTeamModal(),
        },
        {
          icon: AlertBubbleIcon,
          content: 'Report a Problem',
          accessibilityLabel: 'Report a Problem',
          onAction: () => this.toggleReportModal(),
        },
      ],
    },
  ]"
      >
        <template #pageTitle>
          <BlockStack gap="100">
            <div>
              <Badge tone="critical" size="large">Pending Payment</Badge>
            </div>

            <Text variant="headingLg" as="p">*dynamic title*</Text>
          </BlockStack>
        </template>

        <div style="padding: 0 16px">
          <BlockStack gap="600">
            <Tabs style="padding: 0"
                  :tabs="tabs"
                  :selected="activeTab"
                  @select="changeTab"
            />
            <ProjectExpertTab @showQuotaModal="toggleOfferModal" v-if="activeTab === 0" :project="project" :quotas="quotas" />

            <ChatroomTab @showQuotaModal="toggleOfferModal" v-if="activeTab === 1" userType="client" :userActive="true" :messages="messages" />

            <ProjectDescriptionTab v-if="activeTab === 2" />

            <ProjectInvoicesTab v-if="activeTab === 3"/>
          </BlockStack>
        </div>
      </Page>
    </template>

    <template v-else>
      <Page style="padding-top: 56px; padding-bottom: 56px"
          title="*dynamic title*"
          subtitle="*project url*"
          :actionGroups="[
    {
      title: 'More Actions',
      actions: [
        {
          icon: CheckCircle,
          content: 'Mark as Completed',
          accessibilityLabel: 'Mark as Completed',
          onAction: () => this.toggleCompletedModal(),
        },
        {
          icon: PersonAddIcon,
          content: 'Add Team Member',
          accessibilityLabel: 'Add Team Member',
          onAction: () => this.toggleAddTeamModal(),
        },
        {
          icon: AlertBubbleIcon,
          content: 'Report a Problem',
          accessibilityLabel: 'Report a Problem',
          onAction: () => this.toggleReportModal(),
        },
      ],
    },
  ]"
    >
      <template #pageTitle>
        <Badge tone="critical" size="large">Pending Payment</Badge>
      </template>

      <BlockStack gap="600">
        <Tabs style="padding: 0"
            :tabs="tabs"
            :selected="activeTab"
            @select="changeTab"
        />

        <ProjectExpertTab @showQuotaModal="toggleOfferModal" v-if="activeTab === 0" :project="project" :quotas="quotas" />

        <ChatroomTab @showQuotaModal="toggleOfferModal" v-if="activeTab === 1" userType="client" :userActive="true" :messages="messages" />

        <ProjectDescriptionTab v-if="activeTab === 2" />

        <ProjectInvoicesTab v-if="activeTab === 3"/>
      </BlockStack>
    </Page>

    </template>

    <QuoteModal v-show="showOfferModal" :quota="quota" @close="toggleOfferModal" />
    <CompleteModal v-show="showCompletedModal" @close="toggleCompletedModal" />
    <AddTeamModal v-show="showAddTeamModal" @close="toggleAddTeamModal" />
    <ReportModal v-show="showReportModal" @close="toggleReportModal" />
  </ClientLayout>
</template>

<style scoped>

</style>