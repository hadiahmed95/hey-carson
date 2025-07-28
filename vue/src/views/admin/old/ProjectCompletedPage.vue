<script>
import AdminLayout from "@/layout/AdminLayout.vue";
import QuotaCard from "@/components/cards/QuotaCard.vue";
import ChatroomTab from "@/components/tabs/ChatroomTab.vue";
import ProjectHistoryTab from "@/components/tabs/ProjectHistoryTab.vue";

export default {
  name: "ProjectCompletedPage",

  components: {
    ProjectHistoryTab,
    ChatroomTab,
    QuotaCard,
    AdminLayout
  },

  data() {
    return {
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
        },
      ],
      activeTab: 0,
      tabs: [
        {
          id: 'description',
          content: 'Project Details'
        },
        {
          id: 'chat',
          content: 'Chatroom'
        },
        {
          id: 'transactions',
          content: 'Transactions'
        },
        {
          id: 'history',
          content: 'Status History'
        },
      ],

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
      ],

      invoices: [
        {
          id: 2063,
          type: 'Add to Scope',
          date: '29 May, 2024',
          amount: '$90.00',
        },
        {
          id: 2062,
          type: 'Add to Scope',
          date: '07 Mar, 2024',
          amount: '$450.00',
        },
        {
          id: 2061,
          type: 'Custom Quote Offer',
          date: '20 Feb, 2024',
          amount: '$950.00',
        }
      ]

    }
  },

  methods: {
    changeTab(tab) {
      this.activeTab = tab;
    },
  }
}
</script>

<template>
  <AdminLayout>
    <Page style="padding-top: 56px; padding-bottom: 56px"
          title="*dynamic title*"
          subtitle="*project url*"
          :actionGroups="[
    {
      title: 'More Actions',
      actions: [
        {
          content: 'WIP',
          accessibilityLabel: 'WIP',
          onAction: () => null,
        },
      ],
    },
  ]"
    >
      <template #pageTitle>
        <Badge tone="success" size="large">Completed</Badge>
      </template>

      <BlockStack gap="600" ref="chatBox">
        <Tabs style="padding: 0"
              :tabs="tabs"
              :selected="activeTab"
              @select="changeTab"
        />

        <template v-if="activeTab === 0">
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
            </BlockStack>
          </Card>

          <BlockStack gap="200">
          <Text as="p" variant="bodyMd">Matched Expert</Text>

          <Card padding="600">
            <BlockStack gap="800">
              <InlineStack align="space-between" blockAlign="center">
                <InlineStack gap="200">

                  <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                  <BlockStack gap="050">
                    <Text as="p" variant="bodyLg" fontWeight="semibold">
                      *developer full name*
                    </Text>
                    <Text as="p" variant="bodySm" tone="subdued">
                      *developer title*
                    </Text>
                  </BlockStack>
                </InlineStack>

                <BlockStack gap="050">
                  <Text as="p" variant="bodyLg" fontWeight="semibold">
                    4.9
                    <Text as="span" variant="bodyMd" fontWight="default">(17 Reviews)</Text>
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    28 Completed Projects
                  </Text>
                </BlockStack>

                <Badge size="large">Local Time: 09:36am</Badge>
              </InlineStack>

              <BlockStack gap="300" v-if="quotas.length">
                <QuotaCard expert v-for="quota in quotas" :quota="quota" :key="quota.id" />
              </BlockStack>
            </BlockStack>
          </Card>
        </BlockStack>
        </template>

        <ChatroomTab v-if="activeTab === 1" userType="admin" :userActive="false" :messages="messages" />

        <Card v-if="activeTab === 2" padding="600">
          <IndexTable
              :selectable="false"
              :itemCount="invoices.length"
              :headings="[
                    {title: 'ID', alignment: 'center'},
                    {title: 'Transaction Type'},
                    {title: 'Date'},
                    {title: 'Amount', alignment: 'center'},
                    {title: 'Invoices', alignment: 'center'},
                  ]"
          >
            <IndexTableRow
                v-for="({ id, type, date, amount }, index) in invoices"
                :id="id"
                :key="id"
                :position="index"
            >
              <IndexTableCell>
                <Text as="span" alignment="center">#{{ id }}</Text>
              </IndexTableCell>
              <IndexTableCell>
                <Box paddingBlock="300">
                  {{ type }}
                </Box>
              </IndexTableCell>
              <IndexTableCell>
                <Box paddingBlock="300">
                  {{ date }}
                </Box>
              </IndexTableCell>
              <IndexTableCell>
                <Text as="span" alignment="center">{{ amount }}</Text>
              </IndexTableCell>
              <IndexTableCell>
                <InlineStack align="center">
                  <Button variant="plain">Download Invoice</Button>
                </InlineStack>
              </IndexTableCell>
            </IndexTableRow>
          </IndexTable>
        </Card>

        <ProjectHistoryTab v-if="activeTab === 3"/>
      </BlockStack>
    </Page>
  </AdminLayout>

</template>

<style scoped>

</style>