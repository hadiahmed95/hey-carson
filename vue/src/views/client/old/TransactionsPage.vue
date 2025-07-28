<script>
import ClientLayout from "@/layout/ClientLayout.vue";
import MobileCard from "@/components/MobileCard.vue";

export default {
  name: "TransactionsPage",

  components: {MobileCard, ClientLayout},

  data() {
    return {
      showDetails: false,

      isMobile: screen.width <= 760,
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
    toggleDetails() {
      this.showDetails = !this.showDetails
    }
  }
}
</script>

<template>
  <ClientLayout>

    <template v-if="isMobile">
      <BlockStack gap="600" style="padding: 32px 16px;">
        <InlineStack align="space-between" blockAlign="center">
          <Text variant="headingLg" as="p">Transactions</Text>
        </InlineStack>

        <BlockStack gap="300">
          <MobileCard padding="600">
            <BlockStack gap="400">
              <BlockStack gap="100">
                <Text variant="bodyMd" tone="subdued">#28192</Text>

                <Text variant="headingMd">*project name 1*</Text>

                <Text variant="bodyMd" tone="subdued">Client: *full name*</Text>
              </BlockStack>

              <InlineStack gap="100" inlineAlign="center">
                <Text variant="bodyMd" tone="subdued">Transactions:</Text>
                <Text variant="headingMd">3</Text>
              </InlineStack>

              <BlockStack gap="100">
                <Button variant="secondary" :disclosure="showDetails ? 'up' : 'down'"
                        @click="toggleDetails">View Details</Button>
              </BlockStack>
            </BlockStack>
          </MobileCard>

          <MobileCard padding="600">
            <BlockStack gap="400">
              <BlockStack gap="100">
                <Text variant="bodyMd" tone="subdued">#28192</Text>

                <Text variant="headingMd">*project name 2*</Text>

                <Text variant="bodyMd" tone="subdued">Client: *full name*</Text>
              </BlockStack>

              <InlineStack gap="100" inlineAlign="center">

                <Text variant="bodyMd" tone="subdued">Transactions:</Text>
                <Text variant="headingMd">17</Text>
              </InlineStack>

              <BlockStack gap="100">
                <Button variant="secondary" disclosure="down">View Details</Button>
              </BlockStack>
            </BlockStack>
          </MobileCard>

          <MobileCard padding="600">
            <BlockStack gap="400">
              <BlockStack gap="100">
                <Text variant="bodyMd" tone="subdued">#28192</Text>

                <Text variant="headingMd">*project name 3*</Text>

                <Text variant="bodyMd" tone="subdued">Client: *full name*</Text>
              </BlockStack>

              <InlineStack gap="100" inlineAlign="center">
                <Text variant="bodyMd" tone="subdued">Transactions:</Text>
                <Text variant="headingMd">2</Text>
              </InlineStack>

              <BlockStack gap="100">
                <Button variant="secondary" disclosure="down">View Details</Button>
              </BlockStack>
            </BlockStack>
          </MobileCard>

          <MobileCard padding="600">
            <BlockStack gap="400">
              <BlockStack gap="100">
                <Text variant="bodyMd" tone="subdued">#28192</Text>

                <Text variant="headingMd">*project name 4*</Text>

                <Text variant="bodyMd" tone="subdued">Client: *full name*</Text>
              </BlockStack>

              <InlineStack gap="100" inlineAlign="center">

                <Text variant="bodyMd" tone="subdued">Transactions:</Text>
                <Text variant="headingMd">11</Text>
              </InlineStack>

              <BlockStack gap="100">
                <Button variant="secondary" disclosure="down">View Details</Button>
              </BlockStack>
            </BlockStack>
          </MobileCard>

          <MobileCard padding="600">
            <BlockStack gap="400">
              <BlockStack gap="100">
                <Text variant="bodyMd" tone="subdued">#28192</Text>

                <Text variant="headingMd">*project name 5*</Text>

                <Text variant="bodyMd" tone="subdued">Client: *full name*</Text>
              </BlockStack>

              <InlineStack gap="100" inlineAlign="center">

                <Text variant="bodyMd" tone="subdued">Transactions:</Text>
                <Text variant="headingMd">11</Text>
              </InlineStack>

              <BlockStack gap="100">
                <Button variant="secondary" disclosure="down">View Details</Button>
              </BlockStack>
            </BlockStack>
          </MobileCard>
        </BlockStack>
      </BlockStack>
    </template>

    <template v-else>

      <Page style="padding-top: 56px; padding-bottom: 56px"
            title="Transactions"
            :backAction="{ content: 'Dashboard', onAction: () => {this.$router.push('/client')} }"
      >
        <BlockStack gap="300">
          <Card padding="600">
            <BlockStack gap="400">
              <InlineStack align="space-between" blockAlign="center">
                <BlockStack gap="100">
                  <Text variant="bodyMd" tone="subdued">#28192</Text>

                  <Text variant="headingMd">*project name 1*</Text>

                  <Text variant="bodyMd" tone="subdued">Client: *full name*</Text>
                </BlockStack>

                <BlockStack gap="100" inlineAlign="center">
                  <Text variant="headingMd">3</Text>

                  <Text variant="bodyMd" tone="subdued">Transactions</Text>
                </BlockStack>

                <BlockStack gap="100">
                  <Button variant="secondary" :disclosure="showDetails ? 'up' : 'down'"
                          @click="toggleDetails">View Details</Button>
                </BlockStack>
              </InlineStack>

              <template v-if="showDetails">
                <Divider />

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
              </template>
            </BlockStack>
          </Card>

          <Card padding="600">
            <InlineStack align="space-between" blockAlign="center">
              <BlockStack gap="100">
                <Text variant="bodyMd" tone="subdued">#28192</Text>

                <Text variant="headingMd">*project name 2*</Text>

                <Text variant="bodyMd" tone="subdued">Client: *full name*</Text>
              </BlockStack>

              <BlockStack gap="100" inlineAlign="center">
                <Text variant="headingMd">17</Text>

                <Text variant="bodyMd" tone="subdued">Transactions</Text>
              </BlockStack>

              <BlockStack gap="100">
                <Button variant="secondary" disclosure="down">View Details</Button>
              </BlockStack>
            </InlineStack>
          </Card>

          <Card padding="600">
            <InlineStack align="space-between" blockAlign="center">
              <BlockStack gap="100">
                <Text variant="bodyMd" tone="subdued">#28192</Text>

                <Text variant="headingMd">*project name 3*</Text>

                <Text variant="bodyMd" tone="subdued">Client: *full name*</Text>
              </BlockStack>

              <BlockStack gap="100" inlineAlign="center">
                <Text variant="headingMd">2</Text>

                <Text variant="bodyMd" tone="subdued">Transactions</Text>
              </BlockStack>

              <BlockStack gap="100">
                <Button variant="secondary" disclosure="down">View Details</Button>
              </BlockStack>
            </InlineStack>
          </Card>

          <Card padding="600">
            <InlineStack align="space-between" blockAlign="center">
              <BlockStack gap="100">
                <Text variant="bodyMd" tone="subdued">#28192</Text>

                <Text variant="headingMd">*project name 4*</Text>

                <Text variant="bodyMd" tone="subdued">Client: *full name*</Text>
              </BlockStack>

              <BlockStack gap="100" inlineAlign="center">
                <Text variant="headingMd">11</Text>

                <Text variant="bodyMd" tone="subdued">Transactions</Text>
              </BlockStack>

              <BlockStack gap="100">
                <Button variant="secondary" disclosure="down">View Details</Button>
              </BlockStack>
            </InlineStack>
          </Card>

          <Card padding="600">
            <InlineStack align="space-between" blockAlign="center">
              <BlockStack gap="100">
                <Text variant="bodyMd" tone="subdued">#28192</Text>

                <Text variant="headingMd">*project name 5*</Text>

                <Text variant="bodyMd" tone="subdued">Client: *full name*</Text>
              </BlockStack>

              <BlockStack gap="100" inlineAlign="center">
                <Text variant="headingMd">11</Text>

                <Text variant="bodyMd" tone="subdued">Transactions</Text>
              </BlockStack>

              <BlockStack gap="100">
                <Button variant="secondary" disclosure="down">View Details</Button>
              </BlockStack>
            </InlineStack>
          </Card>
        </BlockStack>
      </Page>
    </template>

  </ClientLayout>
</template>

<style scoped>

</style>