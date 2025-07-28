<script>
import ClientLayout from "@/layout/ClientLayout.vue";
import StarFullIcon from "@/components/icons/StarFullIcon.vue";
import ProjectIdeasInfoModal from "@/components/modals/ProjectIdeasInfoModal.vue";

export default {
  name: "ProjectIdeasB",

  components: {
    ClientLayout,
    ProjectIdeasInfoModal
  },

  data() {
    return {
      StarFullIcon,

      isMobile: screen.width <= 760,
      activeTab: 0,
      tabs: [
        {
          id: 'all',
          content: 'All'
        },
        {
          id: 'development',
          content: 'Development'
        },
        {
          id: 'design',
          content: 'Design'
        },
      ],

      showInfo: false,
    }
  },

  methods: {
    changeTab(tab) {
      this.activeTab = tab;
    },

    toggleInfo() {
      this.showInfo = !this.showInfo
    }
  }
}
</script>

<template>
  <ClientLayout :modal="false">
    <Page style="padding-top: 56px; padding-bottom: 56px"
          title="Project Ideas"
    >
      <BlockStack gap="600">
        <Tabs style="padding: 0"
              :tabs="tabs"
              :selected="activeTab"
              @select="changeTab"
        />

        <InlineGrid gap="300" :columns="3">
          <Card padding="600">
            <BlockStack gap="400">
              <BlockStack gap="400">
                <BlockStack gap="100" style="min-height: 84px">
                  <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                  <Text as="p" variant="bodyMd" style="color: #005BD3">Development</Text>
                </BlockStack>

                <BlockStack gap="100">
                  <Text as="p" variant="bodySm" tone="subdued">Price</Text>

                  <Text as="p" variant="headingMd">$300.00</Text>
                </BlockStack>
              </BlockStack>

              <Divider />

              <InlineStack gap="200">

                <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                <BlockStack gap="050">
                  <Text as="p" variant="headingSm">
                    *Expert Full Name*
                  </Text>


                  <Text as="p" variant="bodyMd">
                    4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                  </Text>
                </BlockStack>
              </InlineStack>

              <Button @click="toggleInfo">Check Details</Button>
            </BlockStack>
          </Card>
          <Card padding="600">
            <BlockStack gap="400">
              <BlockStack gap="400">
                <BlockStack gap="100" style="min-height: 84px">
                  <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                  <Text as="p" variant="bodyMd" style="color: #005BD3">Design</Text>
                </BlockStack>

                <BlockStack gap="100">
                  <Text as="p" variant="bodySm" tone="subdued">Price</Text>

                  <Text as="p" variant="headingMd">$400.00</Text>
                </BlockStack>
              </BlockStack>

              <Divider />

              <InlineStack gap="200">

                <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                <BlockStack gap="050">
                  <Text as="p" variant="headingSm">
                    *Expert Full Name*
                  </Text>


                  <Text as="p" variant="bodyMd">
                    4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                  </Text>
                </BlockStack>
              </InlineStack>

              <Button @click="toggleInfo">Check Details</Button>
            </BlockStack>
          </Card>
          <Card padding="600">
            <BlockStack gap="400">
              <BlockStack gap="400">
                <BlockStack gap="100" style="min-height: 84px">
                  <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                  <Text as="p" variant="bodyMd" style="color: #005BD3">Development</Text>
                </BlockStack>

                <BlockStack gap="100">
                  <Text as="p" variant="bodySm" tone="subdued">Price</Text>

                  <Text as="p" variant="headingMd">$300.00</Text>
                </BlockStack>
              </BlockStack>

              <Divider />

              <InlineStack gap="200">

                <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                <BlockStack gap="050">
                  <Text as="p" variant="headingSm">
                    *Expert Full Name*
                  </Text>


                  <Text as="p" variant="bodyMd">
                    4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                  </Text>
                </BlockStack>
              </InlineStack>

              <Button @click="toggleInfo">Check Details</Button>
            </BlockStack>
          </Card>
          <Card padding="600">
            <BlockStack gap="400">
              <BlockStack gap="400">
                <BlockStack gap="100" style="min-height: 84px">
                  <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                  <Text as="p" variant="bodyMd" style="color: #005BD3">Development</Text>
                </BlockStack>

                <BlockStack gap="100">
                  <Text as="p" variant="bodySm" tone="subdued">Price</Text>

                  <Text as="p" variant="headingMd">$300.00</Text>
                </BlockStack>
              </BlockStack>

              <Divider />

              <InlineStack gap="200">

                <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                <BlockStack gap="050">
                  <Text as="p" variant="headingSm">
                    *Expert Full Name*
                  </Text>


                  <Text as="p" variant="bodyMd">
                    4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                  </Text>
                </BlockStack>
              </InlineStack>

              <Button @click="toggleInfo">Check Details</Button>
            </BlockStack>
          </Card>
          <Card padding="600">
            <BlockStack gap="400">
              <BlockStack gap="400">
                <BlockStack gap="100" style="min-height: 84px">
                  <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                  <Text as="p" variant="bodyMd" style="color: #005BD3">Design</Text>
                </BlockStack>

                <BlockStack gap="100">
                  <Text as="p" variant="bodySm" tone="subdued">Price</Text>

                  <Text as="p" variant="headingMd">$400.00</Text>
                </BlockStack>
              </BlockStack>

              <Divider />

              <InlineStack gap="200">

                <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                <BlockStack gap="050">
                  <Text as="p" variant="headingSm">
                    *Expert Full Name*
                  </Text>


                  <Text as="p" variant="bodyMd">
                    4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                  </Text>
                </BlockStack>
              </InlineStack>

              <Button @click="toggleInfo">Check Details</Button>
            </BlockStack>
          </Card>
          <Card padding="600">
            <BlockStack gap="400">
              <BlockStack gap="400">
                <BlockStack gap="100" style="min-height: 84px">
                  <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                  <Text as="p" variant="bodyMd" style="color: #005BD3">Design</Text>
                </BlockStack>

                <BlockStack gap="100">
                  <Text as="p" variant="bodySm" tone="subdued">Price</Text>

                  <Text as="p" variant="headingMd">$400.00</Text>
                </BlockStack>
              </BlockStack>

              <Divider />

              <InlineStack gap="200">

                <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                <BlockStack gap="050">
                  <Text as="p" variant="headingSm">
                    *Expert Full Name*
                  </Text>


                  <Text as="p" variant="bodyMd">
                    4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                  </Text>
                </BlockStack>
              </InlineStack>

              <Button @click="toggleInfo">Check Details</Button>
            </BlockStack>
          </Card>
        </InlineGrid>
      </BlockStack>
    </Page>

    <ProjectIdeasInfoModal v-show="showInfo" @close="toggleInfo" />
  </ClientLayout>
</template>

<style scoped>

</style>