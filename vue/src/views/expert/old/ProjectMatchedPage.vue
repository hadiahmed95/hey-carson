<script>
import ExpertLayout from "@/layout/ExpertLayout.vue";
import AddIcon from "@/components/icons/AddIcon.vue";
import CheckIcon from "@/components/icons/CheckIcon.vue";
import XIcon from "@/components/icons/XIcon.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import AlertCircleIcon from "@/components/icons/AlertCircleIcon.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import DoubleCheckIcon from "@/components/icons/DoubleCheckIcon.vue";
import SendIcon from "@/components/icons/SendIcon.vue";
import UploadIcon from "@/components/icons/UploadIcon.vue";
import PlusCircleIcon from "@/components/icons/PlusCircleIcon.vue";
import AlertBubbleIcon from "@/components/icons/AlertBubbleIcon.vue";
import PersonAddIcon from "@/components/icons/PersonAddIcon.vue";
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
    AddQuotaModal,
    AddTeamModal,
    ReportModal,
    ExpertLayout,
    ProjectHistoryTab
  },

  data() {
    return {
      AddIcon,
      CheckIcon,
      CheckCircle,
      XIcon,
      AlertCircleIcon,
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

      showOfferModal: false,
      showAddScopeModal: false,
      showAddTeamModal: false,
      showReportModal: false,

      activeTab: 0,

      project: {
        status: 'matched'
      },

      tabs: [
        {
          id: 'description',
          content: 'Project Description'
        },
        {
          id: 'chat',
          content: 'Chatroom Matched 1'
        },
        {
          id: 'chat1',
          content: 'Chatroom Matched 2'
        },
        {
          id: 'chat2',
          content: 'Chatroom Claimed 1'
        },
        {
          id: 'chat3',
          content: 'Chatroom Claimed 2'
        },
        // {
        //   id: 'payout',
        //   content: 'Payouts'
        // },
        // {
        //   id: 'history',
        //   content: 'Status History'
        // }
      ],

      asdf: '',

      messages1: [
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
      messages2: [
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
      messages3: [
        {
          id: 1,
          type: 'banner',
          bannerType: 'caution',
          content: null,
          contentType: 'expertClaimed',
          userType: 'expert',
          time: null,
        },
      ],
      messages4: [
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
          bannerType: 'caution',
          content: null,
          contentType: 'expertClaimed',
          userType: 'expert',
          time: null,
        },
      ],
    }
  },

  methods: {
    changeTab(tab) {
      this.activeTab = tab;
    },

    toggleOfferModal() {
      this.showOfferModal = !this.showOfferModal
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
  <ExpertLayout>

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
        },
      ],
    },
  ]"
      >
        <template #pageTitle>
          <BlockStack gap="100">
            <div>
              <Badge tone="magic" size="large">Matched</Badge>
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
            <ProjectClientTab v-if="activeTab === 0" :project="project" @createOffer="toggleOfferModal" />

            <ChatroomTab v-if="activeTab === 1" userType="expert" :userActive="true" :messages="messages1"
                         showButton="offer" @showOffer="toggleOfferModal" />

            <ChatroomTab v-if="activeTab === 2" userType="expert" :userActive="true" :messages="messages2"
                         showButton="offer" @showOffer="toggleOfferModal"/>


            <ChatroomTab v-if="activeTab === 3" userType="expert" :userActive="true" :messages="messages3"
                         showButton="offer" @showOffer="toggleOfferModal" />

            <ChatroomTab v-if="activeTab === 4" userType="expert" :userActive="true" :messages="messages4"
                         showButton="offer" @showOffer="toggleOfferModal"/>

            <ProjectHistoryTab v-if="activeTab === 7"/>
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
        },
      ],
    },
  ]"
    >
      <template #pageTitle>
        <Badge tone="magic" size="large">Matched</Badge>
      </template>

      <BlockStack gap="600">
        <Tabs style="padding: 0"
            :tabs="tabs"
            :selected="activeTab"
            @select="changeTab"
        />
        <ProjectClientTab v-if="activeTab === 0" :project="project" @createOffer="toggleOfferModal" />

        <ChatroomTab v-if="activeTab === 1" userType="expert" :userActive="true" :messages="messages1"
                     showButton="offer" @showOffer="toggleOfferModal" />

        <ChatroomTab v-if="activeTab === 2" userType="expert" :userActive="true" :messages="messages2"
                     showButton="offer" @showOffer="toggleOfferModal"/>


        <ChatroomTab v-if="activeTab === 3" userType="expert" :userActive="true" :messages="messages3"
                     showButton="offer" @showOffer="toggleOfferModal" />

        <ChatroomTab v-if="activeTab === 4" userType="expert" :userActive="true" :messages="messages4"
                     showButton="offer" @showOffer="toggleOfferModal"/>

        <ProjectHistoryTab v-if="activeTab === 7"/>

      </BlockStack>
    </Page>
    </template>

    <AddQuotaModal v-show="showOfferModal" @close="toggleOfferModal" />
    <AddQuotaModal scope v-show="showAddScopeModal" @close="toggleAddScopeModal" />
    <AddTeamModal v-show="showAddTeamModal" @close="toggleAddTeamModal" />
    <ReportModal v-show="showReportModal" @close="toggleReportModal" />
  </ExpertLayout>
</template>

<style scoped>

</style>