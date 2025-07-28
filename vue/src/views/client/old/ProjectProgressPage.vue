<script>
import ClientLayout from "@/layout/ClientLayout.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import AlertCircleIcon from "@/components/icons/AlertCircleIcon.vue";
import ClockIcon from "@/components/icons/ClockIcon.vue";
import PlusCircleIcon from "@/components/icons/PlusCircleIcon.vue";
import NoteIcon from "@/components/icons/NoteIcon.vue";
import DoubleCheckIcon from "@/components/icons/DoubleCheckIcon.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import UploadIcon from "@/components/icons/UploadIcon.vue";
import SendIcon from "@/components/icons/SendIcon.vue";
import CheckIcon from "@/components/icons/CheckIcon.vue";
import AlertDiamondIcon from "@/components/icons/AlertDiamondIcon.vue";
import ProjectExpertTab from "@/components/tabs/ProjectExpertTab.vue";
import ChatroomTab from "@/components/tabs/ChatroomTab.vue";
import QuoteModal from "@/components/modals/QuoteModal.vue";
import FinishModal from "@/components/modals/FinishModal.vue";
import CompleteModal from "@/components/modals/CompleteModal.vue";
import AddTeamModal from "@/components/modals/AddTeamMemberModal.vue";
import ReportModal from "@/components/modals/ReportModal.vue";
import PersonAddIcon from "@/components/icons/PersonAddIcon.vue";
import AlertBubbleIcon from "@/components/icons/AlertBubbleIcon.vue";

export default {
  name: "ProjectPage",

  components: {
    QuoteModal,
    ChatroomTab,
    ProjectExpertTab,
    ClientLayout,
    FinishModal,
    CompleteModal,
    AddTeamModal,
    ReportModal
  },

  data() {
    return {
      CheckCircle,
      AlertCircleIcon,
      ClockIcon,
      PlusCircleIcon,
      AlertBubbleIcon,
      PersonAddIcon,
      NoteIcon,
      DoubleCheckIcon,
      SearchIcon,
      UploadIcon,
      SendIcon,
      CheckIcon,
      AlertDiamondIcon,

      isMobile: screen.width <= 760,
      activeTab: 0,

      tabs: [
        {
          id: 'paid',
          content: 'Paid'
        },
        {
          id: 'scope',
          content: 'Scope'
        },
        {
          id: 'completed',
          content: 'Completed'
        },
        {
          id: 'chatroom1',
          content: 'Chatroom 1'
        },
        {
          id: 'chatroom2',
          content: 'Chatroom 2'
        },
        {
          id: 'chatroom3',
          content: 'Chatroom 3'
        },
        {
          id: 'chatroom4',
          content: 'Chatroom 4'
        },
        {
          id: 'chatroom5',
          content: 'Chatroom 5'
        },
        {
          id: 'chatroom6',
          content: 'Chatroom 6'
        }
      ],

      showQuotaModal: false,
      showFinishModal: false,
      showCompletedModal: false,
      showAddTeamModal: false,
      showReportModal: false,

      quota: {
        id: 2,
        type: 'scope',
        time: 'May 14, 20024 / 10:21am',
        rate: '$90.00',
        hours: '5 hours',
        total: '$450.00',
        paid: false
      },

      quotas1: [
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
      quotas2: [
        {
          id: 2,
          type: 'scope',
          time: 'May 14, 20024 / 10:21am',
          rate: '$90.00',
          hours: '5 hours',
          total: '$450.00',
          paid: false
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
      quotas3: [
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
        status: 'progress',
      },
      project1: {
        status: 'expertCompleted',
      },

      messages1: [
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
      ],
      messages2: [
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
          paid: false,
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
      ],
      messages3: [
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
      ],
      messages4: [
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
      ],
      messages5: [
        {
          id: 16,
          type: 'banner',
          bannerType: 'info',
          content: false,
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
      ],
      messages6: [
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
      ],
    }
  },

  methods: {
    changeTab(tab) {
      this.activeTab = tab;
    },

    toggleQuotaModal() {
      this.showQuotaModal = !this.showQuotaModal
    },

    toggleFinishModal() {
      this.showFinishModal = !this.showFinishModal
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
              <Badge tone="info" size="large">In Progress</Badge>
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
            <ProjectExpertTab v-if="activeTab === 0" :project="project" :quotas="quotas1" />
            <ProjectExpertTab @showQuotaModal="toggleQuotaModal" v-if="activeTab === 1" :project="project" :quotas="quotas2" />
            <ProjectExpertTab @showFinishModal="toggleFinishModal" v-if="activeTab === 2" :project="project1" :quotas="quotas3" />

            <ChatroomTab v-if="activeTab === 3" userType="client" :userActive="true" :messages="messages1" />
            <ChatroomTab @showQuotaModal="toggleQuotaModal" v-if="activeTab === 4" userType="client" :userActive="true" :messages="messages2" />
            <ChatroomTab v-if="activeTab === 5" userType="client" :userActive="true" :messages="messages3" />
            <ChatroomTab v-if="activeTab === 6" userType="client" :userActive="true" :messages="messages4" />
            <ChatroomTab @showFinishModal="toggleFinishModal" v-if="activeTab === 7" userType="client" :userActive="true" :messages="messages5" />
            <ChatroomTab v-if="activeTab === 8" userType="client" :userActive="true" :messages="messages6" />
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
        <Badge tone="info" size="large">In Progress</Badge>
      </template>

      <BlockStack gap="600">
        <Tabs style="padding: 0"
            :tabs="tabs"
            :selected="activeTab"
            @select="changeTab"
        />
        <ProjectExpertTab v-if="activeTab === 0" :project="project" :quotas="quotas1" />
        <ProjectExpertTab @showQuotaModal="toggleQuotaModal" v-if="activeTab === 1" :project="project" :quotas="quotas2" />
        <ProjectExpertTab @showFinishModal="toggleFinishModal" v-if="activeTab === 2" :project="project1" :quotas="quotas3" />

        <ChatroomTab v-if="activeTab === 3" userType="client" :userActive="true" :messages="messages1" />
        <ChatroomTab @showQuotaModal="toggleQuotaModal" v-if="activeTab === 4" userType="client" :userActive="true" :messages="messages2" />
        <ChatroomTab v-if="activeTab === 5" userType="client" :userActive="true" :messages="messages3" />
        <ChatroomTab v-if="activeTab === 6" userType="client" :userActive="true" :messages="messages4" />
        <ChatroomTab @showFinishModal="toggleFinishModal" v-if="activeTab === 7" userType="client" :userActive="true" :messages="messages5" />
        <ChatroomTab v-if="activeTab === 8" userType="client" :userActive="true" :messages="messages6" />
      </BlockStack>
    </Page>
    </template>

    <QuoteModal v-show="showQuotaModal" :quota="quota" @close="toggleQuotaModal" />
    <FinishModal v-show="showFinishModal" @close="toggleFinishModal" />
    <CompleteModal v-show="showCompletedModal" @close="toggleCompletedModal" />
    <AddTeamModal v-show="showAddTeamModal" @close="toggleAddTeamModal" />
    <ReportModal v-show="showReportModal" @close="toggleReportModal" />
  </ClientLayout>
</template>

<style scoped>

</style>