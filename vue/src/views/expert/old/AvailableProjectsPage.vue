<script>
import ExpertLayout from "@/layout/ExpertLayout.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import emptyState from '../../../assets/empty-state.png';
import MobileCard from "@/components/MobileCard.vue";

export default {
  name: "TeamPage",

  components: {MobileCard, ExpertLayout},

  data() {
    return {
      SearchIcon,
      isMobile: screen.width <= 760,

      emptyState,

      search: ''
    }
  },
}
</script>

<template>
  <ExpertLayout>

    <template v-if="isMobile">
      <BlockStack gap="600" style="padding: 32px 16px;">
        <InlineStack align="space-between" blockAlign="center">
          <Text variant="headingLg" as="p">Available Projects</Text>
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

        <BlockStack gap="200">

          <MobileCard padding="600">
            <BlockStack gap="400">

              <InlineStack align="space-between" blockAlign="start">
                <BlockStack gap="100">
                  <Text as="h2" variant="headingMd">
                    Product Page Redesign
                  </Text>
                  <Text as="p" variant="bodyMd" tone="subdued">
                    urbancollective.com
                  </Text>
                </BlockStack>

                <Text as="p" variant="bodySm" tone="subdued">
                  Posted: 18 May, 2024
                </Text>
              </InlineStack>

              <Text variant="bodySm" as="p" alignment="start">
                Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
              </Text>
            </BlockStack>
          </MobileCard>

          <MobileCard padding="600">
            <BlockStack gap="400">

              <InlineStack align="space-between" blockAlign="start">
                <BlockStack gap="100">
                  <Text as="h2" variant="headingMd">
                    Product Page Redesign
                  </Text>
                  <Text as="p" variant="bodyMd" tone="subdued">
                    urbancollective.com
                  </Text>
                </BlockStack>

                <Text as="p" variant="bodySm" tone="subdued">
                  Posted: 18 May, 2024
                </Text>
              </InlineStack>

              <Text variant="bodySm" as="p" alignment="start">
                Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
              </Text>
            </BlockStack>
          </MobileCard>

          <MobileCard padding="600">
            <BlockStack gap="400">

              <InlineStack align="space-between" blockAlign="start">
                <BlockStack gap="100">
                  <Text as="h2" variant="headingMd">
                    Product Page Redesign
                  </Text>
                  <Text as="p" variant="bodyMd" tone="subdued">
                    urbancollective.com
                  </Text>
                </BlockStack>

                <Text as="p" variant="bodySm" tone="subdued">
                  Posted: 18 May, 2024
                </Text>
              </InlineStack>

              <Text variant="bodySm" as="p" alignment="start">
                Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
              </Text>
            </BlockStack>
          </MobileCard>

          <MobileCard padding="600">
            <BlockStack gap="400">

              <InlineStack align="space-between" blockAlign="start">
                <BlockStack gap="100">
                  <Text as="h2" variant="headingMd">
                    Product Page Redesign
                  </Text>
                  <Text as="p" variant="bodyMd" tone="subdued">
                    urbancollective.com
                  </Text>
                </BlockStack>

                <Text as="p" variant="bodySm" tone="subdued">
                  Posted: 18 May, 2024
                </Text>
              </InlineStack>

              <Text variant="bodySm" as="p" alignment="start">
                Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
              </Text>
            </BlockStack>
          </MobileCard>
        </BlockStack>
      </BlockStack>
    </template>

    <template v-else>
      <Page style="padding-top: 56px; padding-bottom: 56px"
            title="Available Projects"
      >
        <template #primaryAction>
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
        </template>
        <BlockStack gap="300">
          <Card padding="600">
            <BlockStack gap="400">

              <InlineStack align="space-between" blockAlign="start">
                <BlockStack gap="100">
                  <Text as="h2" variant="headingMd">
                    Product Page Redesign
                  </Text>
                  <Text as="p" variant="bodyMd" tone="subdued">
                    urbancollective.com
                  </Text>
                </BlockStack>

                <Text as="p" variant="bodySm" tone="subdued">
                  Posted: 18 May, 2024
                </Text>
              </InlineStack>

              <Text variant="bodySm" as="p" alignment="start">
                Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
              </Text>
            </BlockStack>
          </Card>

          <Card padding="600">
            <BlockStack gap="400">

              <InlineStack align="space-between" blockAlign="start">
                <BlockStack gap="100">
                  <Text as="h2" variant="headingMd">
                    Product Page Redesign
                  </Text>
                  <Text as="p" variant="bodyMd" tone="subdued">
                    urbancollective.com
                  </Text>
                </BlockStack>

                <Text as="p" variant="bodySm" tone="subdued">
                  Posted: 18 May, 2024
                </Text>
              </InlineStack>

              <Text variant="bodySm" as="p" alignment="start">
                Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
              </Text>
            </BlockStack>
          </Card>

          <Card padding="600">
            <BlockStack gap="400">

              <InlineStack align="space-between" blockAlign="start">
                <BlockStack gap="100">
                  <Text as="h2" variant="headingMd">
                    Product Page Redesign
                  </Text>
                  <Text as="p" variant="bodyMd" tone="subdued">
                    urbancollective.com
                  </Text>
                </BlockStack>

                <Text as="p" variant="bodySm" tone="subdued">
                  Posted: 18 May, 2024
                </Text>
              </InlineStack>

              <Text variant="bodySm" as="p" alignment="start">
                Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
              </Text>
            </BlockStack>
          </Card>

          <Card padding="600">
            <BlockStack gap="400">

              <InlineStack align="space-between" blockAlign="start">
                <BlockStack gap="100">
                  <Text as="h2" variant="headingMd">
                    Product Page Redesign
                  </Text>
                  <Text as="p" variant="bodyMd" tone="subdued">
                    urbancollective.com
                  </Text>
                </BlockStack>

                <Text as="p" variant="bodySm" tone="subdued">
                  Posted: 18 May, 2024
                </Text>
              </InlineStack>

              <Text variant="bodySm" as="p" alignment="start">
                Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
              </Text>
            </BlockStack>
          </Card>
        </BlockStack>
      </Page>
    </template>
  </ExpertLayout>
</template>

<style scoped>

</style>