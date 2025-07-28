<script>
import ExpertLayout from "@/layout/ExpertLayout.vue";
import ClaimModal from "@/components/modals/ClaimModal.vue";
import AddIcon from "@/components/icons/AddIcon.vue";
import CheckIcon from "@/components/icons/CheckIcon.vue";
import XIcon from "@/components/icons/XIcon.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import PlusCircleIcon from "@/components/icons/PlusCircleIcon.vue";
import AlertBubbleIcon from "@/components/icons/AlertBubbleIcon.vue";
import PersonAddIcon from "@/components/icons/PersonAddIcon.vue";
import AddQuotaModal from "@/components/modals/AddQuotaModal.vue";
import AddTeamModal from "@/components/modals/AddTeamMemberModal.vue";
import ReportModal from "@/components/modals/ReportModal.vue";
import MobileCard from "@/components/MobileCard.vue";
import MobileBanner from "@/components/MobileBanner.vue";

export default {
  name: "ProjectPage",

  components: {
    ExpertLayout,
    AddQuotaModal,
    AddTeamModal,
    ReportModal,
    ClaimModal,
    MobileCard,
    MobileBanner
  },

  data() {
    return {
      AddIcon,
      CheckIcon,
      CheckCircle,
      XIcon,
      PlusCircleIcon,
      AlertBubbleIcon,
      PersonAddIcon,
      isMobile: screen.width <= 760,

      activeTab: 0,

      showClaimModal: false,
      showAddScopeModal: false,
      showAddTeamModal: false,
      showReportModal: false,

      tabs: [
        {
          id: 'description',
          content: 'Project Description'
        },
        {
          id: 'chat',
          content: 'Chatroom'
        },
        {
          id: 'payout',
          content: 'Payouts'
        }
      ]
    }
  },

  methods: {
    changeTab(tab) {
      this.activeTab = tab;
    },

    toggleClaimModal() {
      this.showClaimModal = !this.showClaimModal
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
        <Badge tone="attention" size="large">Read</Badge>
      </template>

      <BlockStack gap="600" style="padding: 0 16px">
        <BlockStack gap="400">

          <template v-if="isMobile">
            <MobileBanner warning>
              <template #title>You claimed this project.</template>

              <Text as="p">
                You've got 5 minutes to go through the project description carefully and let us know if you're up for it. If you think you've got the skills for the job, go ahead and claim the project. If not, no worries! Just release it, and we'll find someone else who can help out.
              </Text>
            </MobileBanner>

            <MobileCard padding="600">
              <BlockStack gap="400">
                <InlineStack align="space-between" blockAlign="center">
                  <InlineStack gap="200">

                    <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                    <BlockStack gap="050">
                      <Text as="p" variant="bodyLg" fontWeight="semibold">
                        *client full name*
                      </Text>
                      <Text as="p" variant="bodySm" tone="subdued">
                        Client
                      </Text>
                    </BlockStack>
                  </InlineStack>

                  <Badge size="large">Local Time: 09:36am</Badge>
                </InlineStack>

                <Divider />

                <BlockStack gap="400">
                  <Text variant="bodyMd" as="p" fontWeight="semibold">
                    Project Description
                  </Text>

                  <Text variant="bodyMd" as="p">
                    We are seeking an experienced Shopify developer to create a custom theme for our online store. Our current store uses a standard Shopify theme, but we want to enhance our brand identity and improve user experience by implementing a unique and visually appealing design.
                    <br />
                    <br />
                    Requirements:
                    Proven experience in Shopify theme development, with a portfolio showcasing previous custom theme projects.
                    Proficiency in HTML, CSS, JavaScript, and Liquid programming language.
                    Strong understanding of Shopify's platform and API capabilities.
                    Ability to collaborate effectively with our design team to bring our vision to life.
                    Excellent communication skills and attention to detail.
                    Reliable and able to meet project deadlines.
                    <br />
                    <br />
                    If you have the skills and experience required to undertake this project, please submit your proposal along with examples of your previous work. We are looking for a dedicated developer who can deliver high-quality results and help us elevate our online store to the next level.
                  </Text>
                </BlockStack>

                <Divider />

                <BlockStack gap="200">
                  <InlineStack align="space-between">
                    <Button variant="primary" :icon="CheckCircle" @click="toggleClaimModal">Claim Project</Button>

                    <Button>Release Project</Button>
                  </InlineStack>

                  <InlineStack align="space-between">
                    <Text as="p" variant="bodySm" tone="subdued">
                      Time Left to Respond
                    </Text>
                    <Text as="p" variant="headingLg">
                      04:36
                    </Text>
                  </InlineStack>
                </BlockStack>
              </BlockStack>
            </MobileCard>
          </template>
          <template v-else>

            <Banner
                title="You claimed this project."
                tone="warning"
                @dismiss="() => {}" >
              <Text as="p">
                You've got 5 minutes to go through the project description carefully and let us know if you're up for it. If you think you've got the skills for the job, go ahead and claim the project. If not, no worries! Just release it, and we'll find someone else who can help out.
              </Text>
            </Banner>

            <Card padding="600">
              <BlockStack gap="400">
                <InlineStack align="space-between" blockAlign="center">
                  <InlineStack gap="200">

                    <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                    <BlockStack gap="050">
                      <Text as="p" variant="bodyLg" fontWeight="semibold">
                        *client full name*
                      </Text>
                      <Text as="p" variant="bodySm" tone="subdued">
                        Client
                      </Text>
                    </BlockStack>
                  </InlineStack>

                  <Badge size="large">Local Time: 09:36am</Badge>
                </InlineStack>

                <Divider />

                <BlockStack gap="400">
                  <Text variant="bodyMd" as="p" fontWeight="semibold">
                    Project Description
                  </Text>

                  <Text variant="bodyMd" as="p">
                    We are seeking an experienced Shopify developer to create a custom theme for our online store. Our current store uses a standard Shopify theme, but we want to enhance our brand identity and improve user experience by implementing a unique and visually appealing design.
                    <br />
                    <br />
                    Requirements:
                    Proven experience in Shopify theme development, with a portfolio showcasing previous custom theme projects.
                    Proficiency in HTML, CSS, JavaScript, and Liquid programming language.
                    Strong understanding of Shopify's platform and API capabilities.
                    Ability to collaborate effectively with our design team to bring our vision to life.
                    Excellent communication skills and attention to detail.
                    Reliable and able to meet project deadlines.
                    <br />
                    <br />
                    If you have the skills and experience required to undertake this project, please submit your proposal along with examples of your previous work. We are looking for a dedicated developer who can deliver high-quality results and help us elevate our online store to the next level.
                  </Text>
                </BlockStack>

                <Divider />

                <InlineStack align="space-between" blockAlign="center">
                  <InlineStack gap="200">
                    <Button variant="primary" :icon="CheckCircle" @click="toggleClaimModal">Claim Project</Button>

                    <Button>Release Project</Button>
                  </InlineStack>

                  <InlineStack gap="200" blockAlign="center">
                    <Text as="p" variant="bodySm" tone="subdued">
                      Time Left to Respond
                    </Text>
                    <Text as="p" variant="headingLg">
                      04:36
                    </Text>
                  </InlineStack>
                </InlineStack>
              </BlockStack>
            </Card>
          </template>
        </BlockStack>
      </BlockStack>
    </Page>

    <ClaimModal v-show="showClaimModal" @close="toggleClaimModal" />
    <AddQuotaModal scope v-show="showAddScopeModal" @close="toggleAddScopeModal" />
    <AddTeamModal v-show="showAddTeamModal" @close="toggleAddTeamModal" />
    <ReportModal v-show="showReportModal" @close="toggleReportModal" />
  </ExpertLayout>
</template>

<style scoped>

</style>