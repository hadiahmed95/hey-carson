<script>
import AdminLayout from "@/layout/AdminLayout.vue";
import QuotaCard from "@/components/cards/QuotaCard.vue";

export default {
  name: "ProjectPendingPage",

  components: {
    QuotaCard,
    AdminLayout
  },

  data() {
    return {
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
        <Badge tone="critical" size="large">Awaiting Payment</Badge>
      </template>

      <BlockStack gap="600" ref="chatBox">
        <Tabs style="padding: 0"
              :tabs="tabs"
              :selected="activeTab"
              @select="changeTab"
        />

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
      </BlockStack>
    </Page>
  </AdminLayout>

</template>

<style scoped>

</style>