<script>
import ExpertLayout from "@/layout/ExpertLayout.vue";
import AddIcon from "@/components/icons/AddIcon.vue";
import CheckIcon from "@/components/icons/CheckIcon.vue";
import XIcon from "@/components/icons/XIcon.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import AlertCircleIcon from "@/components/icons/AlertCircleIcon.vue";
import ClockIcon from "@/components/icons/ClockIcon.vue";
import NoteIcon from "@/components/icons/NoteIcon.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import DoubleCheckIcon from "@/components/icons/DoubleCheckIcon.vue";
import SendIcon from "@/components/icons/SendIcon.vue";
import UploadIcon from "@/components/icons/UploadIcon.vue";
import PlusCircleIcon from "@/components/icons/PlusCircleIcon.vue";
import AlertBubbleIcon from "@/components/icons/AlertBubbleIcon.vue";
import PersonAddIcon from "@/components/icons/PersonAddIcon.vue";
import ProjectDescriptionTab from "@/components/tabs/ProjectDescriptionTab.vue";
import ReportModal from "@/components/modals/ReportModal.vue";
import AddTeamModal from "@/components/modals/AddTeamMemberModal.vue";
import AddQuotaModal from "@/components/modals/AddQuotaModal.vue";
import ChatroomTab from "@/components/tabs/ChatroomTab.vue";
import ProjectClientTab from "@/components/tabs/ProjectClientTab.vue";
import ProjectHistoryTab from "@/components/tabs/ProjectHistoryTab.vue";

export default {
  name: "ProjectPage",

  components: {
    ProjectClientTab,
    ChatroomTab,
    ProjectHistoryTab,
    AddQuotaModal,
    AddTeamModal,
    ReportModal,
    ExpertLayout,
    ProjectDescriptionTab,
  },

  data() {
    return {
      AddIcon,
      CheckIcon,
      CheckCircle,
      AlertCircleIcon,
      ClockIcon,
      XIcon,
      NoteIcon,
      SearchIcon,
      DoubleCheckIcon,
      SendIcon,
      UploadIcon,
      PlusCircleIcon,
      AlertBubbleIcon,
      PersonAddIcon,
      isMobile: screen.width <= 760,

      chatText: '',
      searchText: '',

      activeTab: 0,

      showAddScopeModal: false,
      showAddTeamModal: false,
      showReportModal: false,

      tabs: [
        {
          id: 'client',
          content: 'Client'
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
          id: 'payout',
          content: 'Payouts'
        },
        {
          id: 'history',
          content: 'Status History'
        }
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
      ],
    }
  },

  methods: {
    changeTab(tab) {
      this.activeTab = tab;
    },

    toggleAddScopeModal() {
      this.showAddScopeModal = !this.showAddScopeModal
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

  <template v-if="isMobile">
    <Page style="padding: 32px 0"
          subtitle="*project url*"
          :actionGroups="[
    {
      title: 'More Actions',
      actions: [
       {
          icon: PlusCircleIcon,
          content: 'Add to Scope',
          accessibilityLabel: 'Add to Scope',
          onAction: () => this.toggleAddScopeModal(),
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
        }
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
          <ProjectClientTab v-if="activeTab === 0" :quotas="quotas" :project="project" />

          <ChatroomTab v-if="activeTab === 1" userType="expert" :userActive="true" :messages="messages"  />

          <ProjectDescriptionTab expert v-if="activeTab === 2"/>

          <ProjectHistoryTab v-if="activeTab === 4"/>
        </BlockStack>
      </div>
    </Page>
  </template>

  <template v-else>
  <ExpertLayout>
    <Page style="padding-top: 56px; padding-bottom: 56px"
          title="*dynamic title*"
          subtitle="*project url*"
          :actionGroups="[
    {
      title: 'More Actions',
      actions: [
        {
          icon: PlusCircleIcon,
          content: 'Add to Scope',
          accessibilityLabel: 'Add to Scope',
          onAction: () => this.toggleAddScopeModal(),
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
        }
      ]
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

        <ProjectClientTab v-if="activeTab === 0" :quotas="quotas" :project="project" />

        <ChatroomTab v-if="activeTab === 1" userType="expert" :userActive="true" :messages="messages"  />

        <ProjectDescriptionTab expert v-if="activeTab === 2"/>

        <ProjectHistoryTab v-if="activeTab === 4"/>
      </BlockStack>
    </Page>

    <AddQuotaModal scope v-show="showAddScopeModal" @close="toggleAddScopeModal" />
    <AddTeamModal v-show="showAddTeamModal" @close="toggleAddTeamModal" />
    <ReportModal v-show="showReportModal" @close="toggleReportModal" />
  </ExpertLayout>
  </template>
</template>

<style scoped>

</style>