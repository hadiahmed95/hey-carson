<script>
import ClientLayout from "@/layout/ClientLayout.vue";
import AddIcon from "@/components/icons/AddIcon.vue";
import AlertCircleIcon from "@/components/icons/AlertCircleIcon.vue";
import XIcon from "@/components/icons/XIcon.vue";
import UploadIcon from "@/components/icons/UploadIcon.vue";
import emptyState from '../../../assets/empty-state.png';
import MobileCard from "@/components/MobileCard.vue";

export default {
  name: "TeamPage",

  components: {MobileCard, ClientLayout},

  data() {
    return {
      AddIcon,
      AlertCircleIcon,
      XIcon,
      UploadIcon,
      emptyState,
      isMobile: screen.width <= 760,

      referLink: "jonathankennedy",

      showDetails: false,

      orders: [
        {
          id: 2061,
          payment: '$350.00',
          date: '29 May, 2023',
          commission: '$7.00'
        },
        {
          id: 2062,
          payment: '$800.00',
          date: '07 Mar, 2023',
          commission: '$16.00'
        },
        {
          id: 2063,
          payment: '$1000.00',
          date: '20 Jan, 2024',
          commission: '$20.00'
        },
        {
          id: 2064,
          payment: '$500.00',
          date: '13 Feb, 2024',
          commission: '$10.00'
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
          <Text variant="headingLg" as="p">Refer a Client</Text>

          <Button variant="primary" size="large" :icon="UploadIcon">Withdraw</Button>
        </InlineStack>


        <MobileCard padding="600">
          <BlockStack gap="400">

            <BlockStack gap="100">
              <Text variant="bodyMd" as="p" fontWeight="semibold">
                Earn from your referral link
              </Text>

              <Text variant="bodyMd" as="p">
                Invite clients to sign up using your link, and you'll earn 2% of their project spending for any job they post on HeyCarson.
              </Text>
            </BlockStack>

            <BlockStack gap="100">
              <TextField
                  label=""
                  prefix="https://heycarson.com/"
                  v-model="referLink"
                  autoComplete="off"
                  style="width: 100%"
              >
              </TextField>

              <Button  size="large">Copy Link</Button>
            </BlockStack>

            <Divider />

            <BlockStack gap="200" style="width: ">
              <InlineStack align="space-between">
                <InlineStack gap="050">
                  <Text variant="bodyMd" as="p" tone="subdued">
                    Active Referrals
                  </Text>
                  <Icon :source="AlertCircleIcon" />
                </InlineStack>

                <Text variant="headingMd" as="p" fontWeight="semibold">
                  $95.00
                </Text>
              </InlineStack>

              <InlineStack align="space-between">
                <InlineStack gap="050">
                  <Text variant="bodyMd" as="p" tone="subdued">
                    Completed Projects
                  </Text>
                  <Icon :source="AlertCircleIcon" />
                </InlineStack>

                <Text variant="headingMd" as="p" fontWeight="semibold">
                  10 hours
                </Text>
              </InlineStack>

              <InlineStack align="space-between">
                <InlineStack gap="050">
                  <Text variant="bodyMd" as="p" tone="subdued">
                    Earned Commission (2%)
                  </Text>
                  <Icon :source="AlertCircleIcon" />
                </InlineStack>

                <Text variant="headingMd" as="p" fontWeight="semibold">
                  $950.00
                </Text>
              </InlineStack>
            </BlockStack>

          </BlockStack>
        </MobileCard>

        <BlockStack gap="400">
          <Text variant="headingLg" as="p">Refer a Client</Text>

          <BlockStack gap="300">
            <MobileCard >
              <BlockStack gap="400">

                <BlockStack gap="100">
                  <InlineStack align="space-between" blockAlign="center" :wrap="false">
                    <Text variant="bodyLg" as="p" fontWeight="semibold">
                      *Client 1*
                    </Text>
                    <Text variant="bodyLg" as="p" fontWeight="semibold">
                      $350.00
                    </Text>
                  </InlineStack>

                  <InlineStack align="space-between" blockAlign="center" :wrap="false">
                    <Text variant="bodyMd" as="p" tone="subdued">
                      Referral From: 20 May, 2024
                    </Text>
                    <Text variant="bodyMd" as="p" tone="subdued">
                      Total Earned
                    </Text>
                  </InlineStack>
                </BlockStack>

                <Button variant="secondary" :disclosure="showDetails ? 'up' : 'down'"
                        @click="toggleDetails">View Details</Button>
              </BlockStack>
            </MobileCard>

            <MobileCard >
              <BlockStack gap="400">

                <BlockStack gap="100">
                  <InlineStack align="space-between" blockAlign="center" :wrap="false">
                    <Text variant="bodyLg" as="p" fontWeight="semibold">
                      *Client 2*
                    </Text>
                    <Text variant="bodyLg" as="p" fontWeight="semibold">
                      $258.00
                    </Text>
                  </InlineStack>

                  <InlineStack align="space-between" blockAlign="center" :wrap="false">
                    <Text variant="bodyMd" as="p" tone="subdued">
                      Referral From: 08 Mar, 2024
                    </Text>
                    <Text variant="bodyMd" as="p" tone="subdued">
                      Total Earned
                    </Text>
                  </InlineStack>
                </BlockStack>

                <Button variant="secondary" :disclosure="showDetails ? 'up' : 'down'"
                        @click="toggleDetails">View Details</Button>
              </BlockStack>
            </MobileCard>

            <MobileCard >
              <BlockStack gap="400">
                <BlockStack gap="100">
                  <InlineStack align="space-between" blockAlign="center" :wrap="false">
                    <Text variant="bodyLg" as="p" fontWeight="semibold">
                      *Client 3*
                    </Text>
                    <Text variant="bodyLg" as="p" fontWeight="semibold">
                      $258.00
                    </Text>
                  </InlineStack>

                  <InlineStack align="space-between" blockAlign="center" :wrap="false">
                    <Text variant="bodyMd" as="p" tone="subdued">
                      Referral From: 11 Feb, 2024
                    </Text>
                    <Text variant="bodyMd" as="p" tone="subdued">
                      Total Earned
                    </Text>
                  </InlineStack>
                </BlockStack>

                <Button variant="secondary" :disclosure="showDetails ? 'up' : 'down'"
                        @click="toggleDetails">View Details</Button>
              </BlockStack>
            </MobileCard>
          </BlockStack>
        </BlockStack>
      </BlockStack>
    </template>
    <template v-else>
      <Page style="padding-top: 56px; padding-bottom: 48px"
            title="Refer a Client"
      >
        <template #primaryAction>
          <Button variant="primary" size="large" :icon="UploadIcon">Withdraw</Button>
        </template>


        <Card padding="600">
          <BlockStack gap="400">
            <Grid>
              <GridCell :column-span="{ xs: 12, sm: 12, md: 6, lg: 6, xl: 6 }">
                <BlockStack gap="100">
                  <Text variant="bodyMd" as="p" fontWeight="semibold">
                    Earn from your referral link
                  </Text>

                  <Text variant="bodyMd" as="p">
                    Invite clients to sign up using your link, and you'll earn 2% of their project spending for any job they post on HeyCarson.
                  </Text>
                </BlockStack>
              </GridCell>
            </Grid>

            <Grid>
              <GridCell :column-span="{ xs: 12, sm: 12, md: 7, lg: 7, xl: 7 }">
                <InlineStack gap="100">
                  <TextField
                      label=""
                      prefix="https://heycarson.com/"
                      v-model="referLink"
                      autoComplete="off"
                      style="width: 100%"
                  >
                    <template #connectedRight>
                      <Button  size="large">Copy Link</Button>
                    </template>
                  </TextField>
                </InlineStack>
              </GridCell>
            </Grid>

            <Divider />

            <InlineStack gap="1600">
              <BlockStack gap="100">
                <InlineStack gap="050">
                  <Text variant="bodyMd" as="p" tone="subdued">
                    Active Referrals
                  </Text>
                  <Icon :source="AlertCircleIcon" />
                </InlineStack>

                <Text variant="headingLg" as="p" fontWeight="semibold">
                  $95.00
                </Text>
              </BlockStack>

              <BlockStack gap="100">
                <InlineStack gap="050">
                  <Text variant="bodyMd" as="p" tone="subdued">
                    Completed Projects
                  </Text>
                  <Icon :source="AlertCircleIcon" />
                </InlineStack>

                <Text variant="headingLg" as="p" fontWeight="semibold">
                  10 hours
                </Text>
              </BlockStack>

              <BlockStack gap="100">
                <InlineStack gap="050">
                  <Text variant="bodyMd" as="p" tone="subdued">
                    Earned Commission (2%)
                  </Text>
                  <Icon :source="AlertCircleIcon" />
                </InlineStack>

                <Text variant="headingLg" as="p" fontWeight="semibold">
                  $950.00
                </Text>
              </BlockStack>
            </InlineStack>
          </BlockStack>
        </Card>
      </Page>

      <Page style="padding-bottom: 56px"
          title="Active Referrals"
    >
      <BlockStack gap="300">
        <Card padding="600">
          <BlockStack gap="400">
            <InlineStack align="space-between" blockAlign="center">
              <BlockStack style="width: 380px" gap="100">
                <Text variant="bodyLg" as="p" fontWeight="semibold">
                  *Client 1*
                </Text>

                <Text variant="bodyMd" as="p" tone="subdued">
                  *Client email 1*
                </Text>
              </BlockStack>

              <BlockStack gap="100">
                <Text variant="bodyLg" as="p" fontWeight="semibold">
                  Total Earned: $350.00
                </Text>

                <Text variant="bodyMd" as="p" tone="subdued">
                  Referral From: 20 May, 2024
                </Text>
              </BlockStack>

              <Button variant="secondary" :disclosure="showDetails ? 'up' : 'down'"
                      @click="toggleDetails">View Details</Button>
            </InlineStack>

            <template v-if="showDetails">
              <Divider />

              <IndexTable
                  :selectable="false"
                  :itemCount="orders.length"
                  :headings="[
                    {title: 'Project Id'},
                    {title: 'Payment', alignment: 'center'},
                    {title: 'Payment Date', alignment: 'center'},
                    {title: 'Commission (2%)', alignment: 'center'},
                  ]"
              >
                <IndexTableRow
                    v-for="({ id, payment, date, commission }, index) in orders"
                    :id="id"
                    :key="id"
                    :position="index"
                >
                  <IndexTableCell>
                    <Box paddingBlock="300">#{{ id }}</Box>
                  </IndexTableCell>
                  <IndexTableCell>
                    <Text as="span" alignment="center">{{ payment }}</Text>
                  </IndexTableCell>
                  <IndexTableCell>
                    <Text as="span" alignment="center">{{ date }}</Text>
                  </IndexTableCell>
                  <IndexTableCell>
                    <Text as="span" alignment="center">{{ commission }}</Text>
                  </IndexTableCell>
                </IndexTableRow>
              </IndexTable>
            </template>
          </BlockStack>
        </Card>

        <Card padding="600">
          <InlineStack align="space-between" blockAlign="center">
            <BlockStack style="width: 380px" gap="100">
              <Text variant="bodyLg" as="p" fontWeight="semibold">
                *Client 2*
              </Text>

              <Text variant="bodyMd" as="p" tone="subdued">
                *Client email 2*
              </Text>
            </BlockStack>

            <BlockStack gap="100">
              <Text variant="bodyLg" as="p" fontWeight="semibold">
                Total Earned: 258.00
              </Text>

              <Text variant="bodyMd" as="p" tone="subdued">
                Referral From: 08 Mar, 2024
              </Text>
            </BlockStack>

            <Button variant="secondary" disclosure="down">View Details</Button>
          </InlineStack>
        </Card>

        <Card padding="600">
          <InlineStack align="space-between" blockAlign="center">
            <BlockStack style="width: 380px" gap="100">
              <Text variant="bodyLg" as="p" fontWeight="semibold">
                *Client 3*
              </Text>

              <Text variant="bodyMd" as="p" tone="subdued">
                *Client email 3*
              </Text>
            </BlockStack>

            <BlockStack gap="100">
              <Text variant="bodyLg" as="p" fontWeight="semibold">
                Total Earned: $258.00
              </Text>

              <Text variant="bodyMd" as="p" tone="subdued">
                Referral From: 11 Feb, 2024
              </Text>
            </BlockStack>

            <Button variant="secondary" disclosure="down">View Details</Button>
          </InlineStack>
        </Card>
      </BlockStack>
    </Page>
    </template>
  </ClientLayout>
</template>

<style scoped>

</style>