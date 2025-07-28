<script>
import ClientLayout from "@/layout/ClientLayout.vue";
import PlusCircleIcon from "@/components/icons/PlusCircleIcon.vue";
import StarFullIcon from "@/components/icons/StarFullIcon.vue";
import MobileCard from "@/components/MobileCard.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";

export default {
  name: "ProjectIdeasA",

  components: {MobileCard, ClientLayout},

  data() {
    return {
      PlusCircleIcon,
      StarFullIcon,
      SearchIcon,

      search: '',

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
  <ClientLayout :modal="false">
    <template v-if="isMobile">
      <BlockStack gap="600" style="padding: 32px 16px;">
        <InlineStack align="space-between" blockAlign="center">
          <Text variant="headingLg" as="p">Project Ideas</Text>
        </InlineStack>

        <TextField
            style="min-width: 220px"
            :label="null"
            type="text"
            v-model="search"
            autoComplete="off"
            placeholder="Search projects ..."
        >
          <template #prefix>
            <Icon :source="SearchIcon" />
          </template>
        </TextField>

        <BlockStack gap="300">
          <Tabs style="padding: 0"
                :tabs="tabs"
                :selected="activeTab"
                @select="changeTab"
          />

          <MobileCard padding="800">
            <BlockStack gap="400">
                <BlockStack gap="100">
                  <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                  <Text as="p" variant="bodyMd" style="color: #005BD3">Development</Text>
                </BlockStack>

                <BlockStack gap="100">
                  <Text as="p" variant="bodySm" tone="subdued">Estimated Cost</Text>

                  <Text as="p" variant="headingMd">$300.00</Text>
                </BlockStack>

              <BlockStack gap="100">
                <Text as="p" variant="bodySm" tone="subdued">Project Description</Text>
                <Text as="p" variant="bodyMd">
                  Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
                </Text>
              </BlockStack>

              <Divider />

                <InlineStack gap="200">

                  <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                  <BlockStack gap="050">
                    <Text as="p" variant="headingSm">
                      *Expert Full Name*
                    </Text>

                    <InlineStack gap="050">
                      <Icon :source="StarFullIcon"></Icon>
                      <Text as="p" variant="bodyMd">
                        4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                      </Text>
                    </InlineStack>
                  </BlockStack>
                </InlineStack>

                <Button variant="primary" :icon="PlusCircleIcon">Hire Me</Button>
            </BlockStack>
          </MobileCard>
          <MobileCard padding="800">
            <BlockStack gap="400">
                <BlockStack gap="100">
                  <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                  <Text as="p" variant="bodyMd" style="color: #005BD3">Design</Text>
                </BlockStack>

                <BlockStack gap="100">
                  <Text as="p" variant="bodySm" tone="subdued">Estimated Cost</Text>

                  <Text as="p" variant="headingMd">$400.00</Text>
                </BlockStack>

              <BlockStack gap="100">
                <Text as="p" variant="bodySm" tone="subdued">Project Description</Text>
                <Text as="p" variant="bodyMd">
                  Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
                </Text>
              </BlockStack>

              <Divider />

                <InlineStack gap="200">

                  <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                  <BlockStack gap="050">
                    <Text as="p" variant="headingSm">
                      *Expert Full Name*
                    </Text>

                    <InlineStack gap="050">
                      <Icon :source="StarFullIcon"></Icon>
                      <Text as="p" variant="bodyMd">
                        4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                      </Text>
                    </InlineStack>
                  </BlockStack>
                </InlineStack>

                <Button variant="primary" :icon="PlusCircleIcon">Hire Me</Button>
            </BlockStack>
          </MobileCard>
          <MobileCard padding="800">
            <BlockStack gap="400">
                <BlockStack gap="100">
                  <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                  <Text as="p" variant="bodyMd" style="color: #005BD3">Development</Text>
                </BlockStack>

                <BlockStack gap="100">
                  <Text as="p" variant="bodySm" tone="subdued">Estimated Cost</Text>

                  <Text as="p" variant="headingMd">$300.00</Text>
                </BlockStack>

              <BlockStack gap="100">
                <Text as="p" variant="bodySm" tone="subdued">Project Description</Text>
                <Text as="p" variant="bodyMd">
                  Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
                </Text>
              </BlockStack>

              <Divider />

                <InlineStack gap="200">

                  <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                  <BlockStack gap="050">
                    <Text as="p" variant="headingSm">
                      *Expert Full Name*
                    </Text>

                    <InlineStack gap="050">
                      <Icon :source="StarFullIcon"></Icon>
                      <Text as="p" variant="bodyMd">
                        4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                      </Text>
                    </InlineStack>
                  </BlockStack>
                </InlineStack>

                <Button variant="primary" :icon="PlusCircleIcon">Hire Me</Button>
            </BlockStack>
          </MobileCard>
          <MobileCard padding="800">
            <BlockStack gap="400">
                <BlockStack gap="100">
                  <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                  <Text as="p" variant="bodyMd" style="color: #005BD3">Design</Text>
                </BlockStack>

                <BlockStack gap="100">
                  <Text as="p" variant="bodySm" tone="subdued">Estimated Cost</Text>

                  <Text as="p" variant="headingMd">$400.00</Text>
                </BlockStack>

              <BlockStack gap="100">
                <Text as="p" variant="bodySm" tone="subdued">Project Description</Text>
                <Text as="p" variant="bodyMd">
                  Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
                </Text>
              </BlockStack>

              <Divider />

                <InlineStack gap="200">

                  <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                  <BlockStack gap="050">
                    <Text as="p" variant="headingSm">
                      *Expert Full Name*
                    </Text>

                    <InlineStack gap="050">
                      <Icon :source="StarFullIcon"></Icon>
                      <Text as="p" variant="bodyMd">
                        4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                      </Text>
                    </InlineStack>
                  </BlockStack>
                </InlineStack>

                <Button variant="primary" :icon="PlusCircleIcon">Hire Me</Button>
            </BlockStack>
          </MobileCard>
        </BlockStack>
      </BlockStack>
    </template>
    <template v-else>
      <Page style="padding-top: 56px; padding-bottom: 56px"
            title="Project Ideas"
      >
        <BlockStack gap="600">
          <Tabs style="padding: 0"
                :tabs="tabs"
                :selected="activeTab"
                @select="changeTab"
          />

          <BlockStack gap="300">
            <Card padding="800">
              <BlockStack gap="400">
                <InlineStack align="space-between">
                  <BlockStack gap="100">
                    <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                    <Text as="p" variant="bodyMd" style="color: #005BD3">Development</Text>
                  </BlockStack>

                  <BlockStack gap="100">
                    <Text as="p" variant="bodySm" tone="subdued">Estimated Cost</Text>

                    <Text as="p" variant="headingMd">$300.00</Text>
                  </BlockStack>
                </InlineStack>

                <BlockStack gap="100">
                  <Text as="p" variant="bodySm" tone="subdued">Project Description</Text>
                  <Text as="p" variant="bodyMd">
                    Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
                  </Text>
                </BlockStack>

                <Divider />

                <InlineStack align="space-between" blockAlign="center">
                  <InlineStack gap="200">

                    <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                    <BlockStack gap="050">
                      <Text as="p" variant="headingSm">
                        *Expert Full Name*
                      </Text>

                      <InlineStack gap="050">
                        <Icon :source="StarFullIcon"></Icon>
                        <Text as="p" variant="bodyMd">
                          4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                        </Text>
                      </InlineStack>
                    </BlockStack>
                  </InlineStack>

                  <Button variant="primary" :icon="PlusCircleIcon">Hire Me</Button>
                </InlineStack>
              </BlockStack>
            </Card>
            <Card padding="800">
              <BlockStack gap="400">
                <InlineStack align="space-between">
                  <BlockStack gap="100">
                    <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                    <Text as="p" variant="bodyMd" style="color: #005BD3">Design</Text>
                  </BlockStack>

                  <BlockStack gap="100">
                    <Text as="p" variant="bodySm" tone="subdued">Estimated Cost</Text>

                    <Text as="p" variant="headingMd">$400.00</Text>
                  </BlockStack>
                </InlineStack>

                <BlockStack gap="100">
                  <Text as="p" variant="bodySm" tone="subdued">Project Description</Text>
                  <Text as="p" variant="bodyMd">
                    Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
                  </Text>
                </BlockStack>

                <Divider />

                <InlineStack align="space-between" blockAlign="center">
                  <InlineStack gap="200">

                    <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                    <BlockStack gap="050">
                      <Text as="p" variant="headingSm">
                        *Expert Full Name*
                      </Text>

                      <InlineStack gap="050">
                        <Icon :source="StarFullIcon"></Icon>
                        <Text as="p" variant="bodyMd">
                          4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                        </Text>
                      </InlineStack>
                    </BlockStack>
                  </InlineStack>

                  <Button variant="primary" :icon="PlusCircleIcon">Hire Me</Button>
                </InlineStack>
              </BlockStack>
            </Card>
            <Card padding="800">
              <BlockStack gap="400">
                <InlineStack align="space-between">
                  <BlockStack gap="100">
                    <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                    <Text as="p" variant="bodyMd" style="color: #005BD3">Development</Text>
                  </BlockStack>

                  <BlockStack gap="100">
                    <Text as="p" variant="bodySm" tone="subdued">Estimated Cost</Text>

                    <Text as="p" variant="headingMd">$300.00</Text>
                  </BlockStack>
                </InlineStack>

                <BlockStack gap="100">
                  <Text as="p" variant="bodySm" tone="subdued">Project Description</Text>
                  <Text as="p" variant="bodyMd">
                    Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
                  </Text>
                </BlockStack>

                <Divider />

                <InlineStack align="space-between" blockAlign="center">
                  <InlineStack gap="200">

                    <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                    <BlockStack gap="050">
                      <Text as="p" variant="headingSm">
                        *Expert Full Name*
                      </Text>

                      <InlineStack gap="050">
                        <Icon :source="StarFullIcon"></Icon>
                        <Text as="p" variant="bodyMd">
                          4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                        </Text>
                      </InlineStack>
                    </BlockStack>
                  </InlineStack>

                  <Button variant="primary" :icon="PlusCircleIcon">Hire Me</Button>
                </InlineStack>
              </BlockStack>
            </Card>
            <Card padding="800">
              <BlockStack gap="400">
                <InlineStack align="space-between">
                  <BlockStack gap="100">
                    <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                    <Text as="p" variant="bodyMd" style="color: #005BD3">Design</Text>
                  </BlockStack>

                  <BlockStack gap="100">
                    <Text as="p" variant="bodySm" tone="subdued">Estimated Cost</Text>

                    <Text as="p" variant="headingMd">$400.00</Text>
                  </BlockStack>
                </InlineStack>

                <BlockStack gap="100">
                  <Text as="p" variant="bodySm" tone="subdued">Project Description</Text>
                  <Text as="p" variant="bodyMd">
                    Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
                  </Text>
                </BlockStack>

                <Divider />

                <InlineStack align="space-between" blockAlign="center">
                  <InlineStack gap="200">

                    <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                    <BlockStack gap="050">
                      <Text as="p" variant="headingSm">
                        *Expert Full Name*
                      </Text>

                      <InlineStack gap="050">
                        <Icon :source="StarFullIcon"></Icon>
                        <Text as="p" variant="bodyMd">
                          4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                        </Text>
                      </InlineStack>
                    </BlockStack>
                  </InlineStack>

                  <Button variant="primary" :icon="PlusCircleIcon">Hire Me</Button>
                </InlineStack>
              </BlockStack>
            </Card>
          </BlockStack>
        </BlockStack>
      </Page>
    </template>
  </ClientLayout>
</template>

<style scoped>

</style>