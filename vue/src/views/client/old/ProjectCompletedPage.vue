<script>
import ClientLayout from "@/layout/ClientLayout.vue";
import ProjectExpertTab from "@/components/tabs/ProjectExpertTab.vue";
import ChatroomTab from "@/components/tabs/ChatroomTab.vue";
import ProjectDescriptionTab from "@/components/tabs/ProjectDescriptionTab.vue";
import ProjectInvoicesTab from "@/components/tabs/ProjectInvoicesTab.vue";
import AlertBubbleIcon from "@/components/icons/AlertBubbleIcon.vue";
import PersonAddIcon from "@/components/icons/PersonAddIcon.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import ReportModal from "@/components/modals/ReportModal.vue";
import AddTeamModal from "@/components/modals/AddTeamMemberModal.vue";
import CompleteModal from "@/components/modals/CompleteModal.vue";

export default {
  name: "ProjectPage",

  components: {
    CompleteModal,
    AddTeamModal,
    ReportModal,
    ClientLayout,
    ProjectExpertTab,
    ChatroomTab,
    ProjectDescriptionTab,
    ProjectInvoicesTab,
  },

  data() {
    return {
      CheckCircle,
      AlertBubbleIcon,
      PersonAddIcon,

      isMobile: screen.width <= 760,
      activeTab: 0,

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
          id: 2,
          type: 'scope',
          time: 'May 14, 20024 / 10:21am',
          rate: '$90.00',
          hours: '5 hours',
          total: '$450.00',
          paid: true
        },
        {
          id: 1,
          type: 'offer',
          time: 'May 13, 2024 / 02:45pm',
          rate: '$95.00',
          hours: '10 hours',
          total: '$950.00',
          paid: true
        }
      ],

      project: {
        status: 'completed',
        expertRating: '4.0',
        stars: [1,1,1,1,0]
      },

      messages: [
        {
          id: 21,
          type: 'banner',
          bannerType: 'success',
          content: true,
          contentType: 'clientCompleted',
          userType: 'client',
          time: 'May 14, 2024 / 08:36am',
        },
        {
          id: 20,
          type: 'banner',
          bannerType: 'info',
          content: true,
          contentType: 'expertCompleted',
          userType: 'expert',
          time: 'May 14, 2024 / 08:36am',
        },
        {
          id: 19,
          type: 'text',
          content: 'We\'ve matched you with the best expert for this project type. Should the conversation not go in the direction you were hoping, please let us know, and we will find you another match.',
          time: '25 seconds ago',
          userType: 'expert',
          userName: '*EXPERT FULL NAME*',
          seen: true,
        },
        {
          id: 18,
          type: 'text',
          content: 'We\'ve matched you with the best expert for this project type. Should the conversation not go in the direction you were hoping, please let us know, and we will find you another match.',
          time: '5 minutes ago',
          userType: 'client',
          userName: '*CLIENT FULL NAME*',
          seen: true,
        },
        {
          id: 17,
          type: 'banner',
          bannerType: 'critical',
          content: null,
          contentType: 'clientCompleted',
          userType: 'client',
          time: 'May 14, 2024 / 08:36am',
        },
        {
          id: 16,
          type: 'banner',
          bannerType: 'info',
          content: true,
          contentType: 'expertCompleted',
          userType: 'expert',
          time: 'May 14, 2024 / 08:36am',
        },
        {
          id: 15,
          type: 'text',
          content: 'We\'ve matched you with the best expert for this project type. Should the conversation not go in the direction you were hoping, please let us know, and we will find you another match.',
          time: '2 minutes ago',
          userType: 'expertTeam',
          userName: '*EXPERT TEAM MEMBER FULL NAME*',
          seen: true,
        },
        {
          id: 14,
          type: 'text',
          content: 'We\'ve matched you with the best expert for this project type. Should the conversation not go in the direction you were hoping, please let us know, and we will find you another match.',
          time: '15 minutes ago',
          userType: 'clientTeam',
          userName: '*CLIENT TEAM MEMBER FULL NAME*',
          seen: true,
        },
        {
          id: 13,
          type: 'banner',
          bannerType: 'info',
          content: {
            fullName: '*EXPERT TEAM MEMBER FULL NAME*',
            role: 'expert'
          },
          contentType: 'teamAdded',
          userType: 'expert',
          time: 'May 14, 2024 / 08:36am',
        },
        {
          id: 12,
          type: 'banner',
          bannerType: 'info',
          content: {
            fullName: '*CLIENT TEAM MEMBER FULL NAME*',
            role: 'team'
          },
          contentType: 'teamAdded',
          userType: 'client',
          time: 'May 14, 2024 / 08:36am',
        },
        {
          id: 11,
          type: 'banner',
          bannerType: 'success',
          content: true,
          contentType: 'clientScope',
          userType: 'client',
          time: 'May 14, 2024 / 08:36am',
        },
        {
          id: 10,
          type: 'quote',
          content: {
            rate: '$95.00',
            time: '5 hours',
            total: '$475.00'
          },
          time: 'May 14, 2024 / 10:21am',
          quoteType: 'scope',
          paid: true,
        },
        {
          id: 9,
          type: 'text',
          content: 'We\'ve matched you with the best expert for this project type. Should the conversation not go in the direction you were hoping, please let us know, and we will find you another match.',
          time: '7 minutes ago',
          userType: 'expert',
          userName: '*EXPERT FULL NAME*',
          seen: true,
        },
        {
          id: 8,
          type: 'text',
          content: 'We\'ve matched you with the best expert for this project type. Should the conversation not go in the direction you were hoping, please let us know, and we will find you another match.',
          time: '36 minutes ago',
          userType: 'client',
          userName: '*CLIENT FULL NAME*',
          seen: true,
        },
        {
          id: 7,
          type: 'banner',
          bannerType: 'success',
          content: true,
          contentType: 'clientOffer',
          userType: 'client',
          time: 'May 14, 2024 / 08:36am',
        },
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
          paid: true,
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
              <Badge tone="success" size="large">Completed</Badge>
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

            <ProjectExpertTab v-if="activeTab === 0" :quotas="quotas" :project="project" />

            <ChatroomTab v-if="activeTab === 1" userType="client" :userActive="false" :messages="messages" />

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
          <Badge tone="success" size="large">Completed</Badge>
        </template>

        <BlockStack gap="600">
          <Tabs style="padding: 0"
                :tabs="tabs"
                :selected="activeTab"
                @select="changeTab"
          />

          <ProjectExpertTab v-if="activeTab === 0" :quotas="quotas" :project="project" />

          <ChatroomTab v-if="activeTab === 1" userType="client" :userActive="false" :messages="messages" />

          <ProjectDescriptionTab v-if="activeTab === 2" />

          <ProjectInvoicesTab v-if="activeTab === 3"/>
        </BlockStack>
      </Page>
    </template>

    <CompleteModal v-show="showCompletedModal" @close="toggleCompletedModal" />
    <AddTeamModal v-show="showAddTeamModal" @close="toggleAddTeamModal" />
    <ReportModal v-show="showReportModal" @close="toggleReportModal" />
  </ClientLayout>
</template>

<style scoped>

</style>