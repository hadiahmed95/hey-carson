<script>
import AdminLayout from "@/layout/AdminLayout.vue";
import PlusCircleIcon from "@/components/icons/PlusCircleIcon.vue";

export default {
  name: "ProjectPendingMatchPage",

  components: {
    AdminLayout
  },

  data() {
    return {
      PlusCircleIcon,
    }
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
        <Badge tone="attention" size="large">Pending Match</Badge>
      </template>

      <BlockStack gap="600">
        <BlockStack gap="400">
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
                  <Button variant="primary" :icon="PlusCircleIcon" @click="null">Assign Project</Button>

                  <Button>Sent to Available Projects</Button>
                </InlineStack>
              </InlineStack>
            </BlockStack>
          </Card>
        </BlockStack>
      </BlockStack>
    </Page>
  </AdminLayout>

</template>

<style scoped>

</style>