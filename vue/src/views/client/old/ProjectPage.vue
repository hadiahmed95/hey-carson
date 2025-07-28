<script>
import ClientLayout from "@/layout/ClientLayout.vue";
import AddIcon from "@/components/icons/AddIcon.vue";
import ProjectExpertTab from "@/components/tabs/ProjectExpertTab.vue";
import ProjectDescriptionTab from "@/components/tabs/ProjectDescriptionTab.vue";
import AlertBubbleIcon from "@/components/icons/AlertBubbleIcon.vue";
import PersonAddIcon from "@/components/icons/PersonAddIcon.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
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
    ClientLayout,
    ProjectExpertTab,
    ProjectDescriptionTab,
    ProjectInvoicesTab,
  },

  data() {
    return {
      AddIcon,
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
          id: 'description',
          content: 'Project Description'
        },
        {
          id: 'invoices',
          content: 'Invoices'
        },
      ],

      project: {
        status: null
      }
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
              <Badge tone="attention" size="large">Pending Match</Badge>
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
            <ProjectExpertTab v-if="activeTab === 0" :project="project" />

            <ProjectDescriptionTab v-if="activeTab === 1" />

            <ProjectInvoicesTab v-if="activeTab === 2"/>
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
          <Badge tone="attention" size="large">Pending Match</Badge>
        </template>

        <BlockStack gap="600">
          <Tabs style="padding: 0"
                :tabs="tabs"
                :selected="activeTab"
                @select="changeTab"
          />
          <ProjectExpertTab v-if="activeTab === 0" :project="project" />

          <ProjectDescriptionTab v-if="activeTab === 1" />

          <ProjectInvoicesTab v-if="activeTab === 2"/>
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