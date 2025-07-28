<script>
import ExpertLayout from "@/layout/ExpertLayout.vue";
import AlertCircleIcon from "@/components/icons/AlertCircleIcon.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import MobileCard from "@/components/MobileCard.vue";
export default {
  name: "DashboardPage",

  components: {
    MobileCard,
    ExpertLayout
  },

  data() {
    return {
      AlertCircleIcon,
      SearchIcon,
      isMobile: screen.width <= 760,

      statusPopover: false,

      activeStatus: 'all',

      statusList: [
        {
          content: 'All',
          role: 'all',
          onAction: () => this.selectAction('all')
        },
        {
          content: 'Matched',
          role: 'matched',
          onAction: () => this.selectAction('matched')
        },
        {
          content: 'Pending Payment',
          role: 'pending_payment',
          onAction: () => this.selectAction('pending_payment')
        },
        {
          content: 'In Progress',
          role: 'in_progress',
          onAction: () => this.selectAction('in_progress')
        },
        {
          content: 'Completed',
          role: 'completed',
          onAction: () => this.selectAction('completed')
        }
      ],

      search: ''
    }
  },

  computed: {
    selectedStatus() {
      this.statusList.forEach(status => {
        if (status.role === this.activeStatus) {
          status.active = true;
        } else {
          status.active = false;
        }
      });

      return this.statusList.find(status => status.role === this.activeStatus);
    }
  },

  methods: {
    toggleStatusPopover() {
      this.statusPopover = !this.statusPopover;
    },

    selectAction(status) {
      this.activeStatus = status;
    },

    goTo(page) {
      this.$router.push(page)
    }
  }
}
</script>

<template>
  <ExpertLayout>
    <template v-if="isMobile">
      <BlockStack gap="600" style="padding: 32px 16px;">
        <InlineStack align="space-between" blockAlign="center">
          <Text variant="headingLg" as="p">My Projects</Text>

          <Popover
              :active="statusPopover"
              autofocusTarget="first-node"
              @close="toggleStatusPopover"
          >
            <template #activator>
              <Button @click="toggleStatusPopover" size="large"
                      :disclosure="statusPopover ? 'up' : 'down'">Status: {{ selectedStatus.content }}</Button>
            </template>
            <ActionList
                actionRole="menuitem"
                :items="statusList"
            ></ActionList>
          </Popover>
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

          <MobileCard @click="goTo('/expert/project/matched')">
            <BlockStack gap="400">
              <InlineStack align="space-between">
                <Badge tone="magic">Matched</Badge>

                <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                  Matched: *date matched*
                </Text>
              </InlineStack>

              <BlockStack gap="100">
                <Text as="h2" variant="headingMd">
                  *project title*
                </Text>
                <Text as="p" variant="bodyMd" tone="subdued">
                  *project website*
                </Text>
              </BlockStack>

              <Divider />

              <InlineStack align="start" blockAlign="center" gap="200">
                <Avatar style="border-radius: 100%" customer name="Test" size="lg" />

                <BlockStack gap="050">
                  <Text as="h2" variant="headingSm">
                    *client full name*
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    Client
                  </Text>
                </BlockStack>
              </InlineStack>
            </BlockStack>
          </MobileCard>

          <MobileCard @click="goTo('/expert/project/pending')">
            <BlockStack gap="400">
              <InlineStack align="space-between">
                <Badge tone="critical">Pending Payment</Badge>

                <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                  Offer Sent: *date offer sent*
                </Text>
              </InlineStack>

              <BlockStack gap="100">
                <Text as="h2" variant="headingMd">
                  *project title*
                </Text>
                <Text as="p" variant="bodyMd" tone="subdued">
                  *project website*
                </Text>
              </BlockStack>

              <Divider />

              <InlineStack align="start" blockAlign="center" gap="200">
                <Avatar style="border-radius: 100%" customer name="Test" size="lg" />

                <BlockStack gap="050">
                  <Text as="h2" variant="headingSm">
                    *client full name*
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    Client
                  </Text>
                </BlockStack>
              </InlineStack>

              <BlockStack gap="100">

                <InlineStack align="space-between">
                    <Text as="p" variant="bodySm" tone="subdued">
                      Hourly Rate
                    </Text>
                    <Text as="h2" variant="headingSm">
                      $90.00
                    </Text>
                </InlineStack>

                <InlineStack align="space-between">
                  <Text as="p" variant="bodySm" tone="subdued">
                      Estimated Time
                    </Text>
                    <Text as="h2" variant="headingSm">
                      10 Hours
                    </Text>
                </InlineStack>

                <InlineStack align="space-between">
                    <Text as="p" variant="bodySm" tone="subdued">
                      Total to Pay
                    </Text>
                    <Text as="h2" variant="headingSm">
                      $900.00
                    </Text>
                </InlineStack>
              </BlockStack>
            </BlockStack>
          </MobileCard>

          <MobileCard @click="goTo('/expert/project/progress')">
            <BlockStack gap="400">
              <InlineStack align="space-between">
                <Badge tone="info">In Progress</Badge>

                <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                  Offer Accepted: *date accepted*
                </Text>
              </InlineStack>

              <BlockStack gap="100">
                <Text as="h2" variant="headingMd">
                  *project title*
                </Text>
                <Text as="p" variant="bodyMd" tone="subdued">
                  *project website*
                </Text>
              </BlockStack>

              <Divider />

              <InlineStack align="start" blockAlign="center" gap="200">
                <Avatar style="border-radius: 100%" customer name="Test" size="lg" />

                <BlockStack gap="050">
                  <Text as="h2" variant="headingSm">
                    *client full name*
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    Client
                  </Text>
                </BlockStack>
              </InlineStack>

              <BlockStack gap="100">

                <InlineStack align="space-between">
                  <Text as="p" variant="bodySm" tone="subdued">
                    Hourly Rate
                  </Text>
                  <Text as="h2" variant="headingSm">
                    $90.00
                  </Text>
                </InlineStack>

                <InlineStack align="space-between">
                  <Text as="p" variant="bodySm" tone="subdued">
                    Estimated Time
                  </Text>
                  <Text as="h2" variant="headingSm">
                    10 Hours
                  </Text>
                </InlineStack>

                <InlineStack align="space-between">
                  <Text as="p" variant="bodySm" tone="subdued">
                    Total to Pay
                  </Text>
                  <Text as="h2" variant="headingSm">
                    $900.00
                  </Text>
                </InlineStack>
              </BlockStack>            </BlockStack>
          </MobileCard>

          <MobileCard @click="goTo('/expert/project/completed')">
            <BlockStack gap="400">
              <InlineStack align="space-between">
                <Badge tone="success">Completed</Badge>

                <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                  Mark as Completed: *date marked*
                </Text>
              </InlineStack>

              <BlockStack gap="100">
                <Text as="h2" variant="headingMd">
                  *project title*
                </Text>
                <Text as="p" variant="bodyMd" tone="subdued">
                  *project website*
                </Text>
              </BlockStack>

              <Divider />

              <InlineStack align="start" blockAlign="center" gap="200">
                <Avatar style="border-radius: 100%" customer name="Test" size="lg" />

                <BlockStack gap="050">
                  <Text as="h2" variant="headingSm">
                    *client full name*
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    Client
                  </Text>
                </BlockStack>
              </InlineStack>

              <BlockStack gap="100">

                <InlineStack align="space-between">
                  <Text as="p" variant="bodySm" tone="subdued">
                    Hourly Rate
                  </Text>
                  <Text as="h2" variant="headingSm">
                    $90.00
                  </Text>
                </InlineStack>

                <InlineStack align="space-between">
                  <Text as="p" variant="bodySm" tone="subdued">
                    Estimated Time
                  </Text>
                  <Text as="h2" variant="headingSm">
                    10 Hours
                  </Text>
                </InlineStack>

                <InlineStack align="space-between">
                  <Text as="p" variant="bodySm" tone="subdued">
                    Total to Pay
                  </Text>
                  <Text as="h2" variant="headingSm">
                    $900.00
                  </Text>
                </InlineStack>
              </BlockStack>
            </BlockStack>
          </MobileCard>

          <MobileCard @click="goTo('/expert/project/claimed')">
            <BlockStack gap="400">
              <InlineStack align="space-between">
                <Badge tone="attention">Claimed</Badge>

                <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                  Claimed: *date claimed*
                </Text>
              </InlineStack>

              <BlockStack gap="100">
                <Text as="h2" variant="headingMd">
                  *project title*
                </Text>
                <Text as="p" variant="bodyMd" tone="subdued">
                  *project website*
                </Text>
              </BlockStack>

              <Divider />

              <InlineStack align="start" blockAlign="center" gap="200">
                <Avatar style="border-radius: 100%" customer name="Test" size="lg" />

                <BlockStack gap="050">
                  <Text as="h2" variant="headingSm">
                    *client full name*
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    Client
                  </Text>
                </BlockStack>
              </InlineStack>

              <BlockStack gap="100">

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
        </BlockStack>
      </BlockStack>
    </template>

    <template v-else>
    <Page style="padding-top: 56px; padding-bottom: 56px"
          title="My Projects"
    >
      <template #secondaryActions>
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

      <template #primaryAction>
        <Popover
            :active="statusPopover"
            autofocusTarget="first-node"
            @close="toggleStatusPopover"
        >
          <template #activator>
            <Button @click="toggleStatusPopover" size="large"
                    :disclosure="statusPopover ? 'up' : 'down'">Status: {{ selectedStatus.content }}</Button>
          </template>
          <ActionList
              actionRole="menuitem"
              :items="statusList"
          ></ActionList>
        </Popover>
      </template>

      <BlockStack gap="300">
        <Card @click="goTo('/expert/project/matched')">
          <BlockStack gap="400">
            <InlineStack align="space-between">
              <Badge tone="magic">Matched</Badge>

              <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                Matched: *date matched*
              </Text>
            </InlineStack>

            <BlockStack gap="100">
              <Text as="h2" variant="headingMd">
                *project title*
              </Text>
              <Text as="p" variant="bodyMd" tone="subdued">
                *project website*
              </Text>
            </BlockStack>

            <Divider />

            <InlineStack align="start" blockAlign="center" gap="100">
              <Box>
                <Icon :source="AlertCircleIcon" />
              </Box>

              <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                We found a potential match. Start a conversation with the client and send the suitable offer.
              </Text>
            </InlineStack>
          </BlockStack>
        </Card>

        <Card @click="goTo('/expert/project/pending')">
          <BlockStack gap="400">
            <InlineStack align="space-between">
              <Badge tone="critical">Pending Payment</Badge>

              <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                Offer Sent: *date offer sent*
              </Text>
            </InlineStack>

            <BlockStack gap="100">
              <Text as="h2" variant="headingMd">
                *project title*
              </Text>
              <Text as="p" variant="bodyMd" tone="subdued">
                *project website*
              </Text>
            </BlockStack>

            <Divider />

            <InlineStack align="space-between" blockAlign="center">
              <InlineStack align="start" blockAlign="center" gap="200">
              <Avatar style="border-radius: 100%" customer name="Test" size="lg" />

              <BlockStack gap="050">
                <Text as="h2" variant="headingSm">
                  *client full name*
                </Text>
                <Text as="p" variant="bodySm" tone="subdued">
                  Client
                </Text>
              </BlockStack>
              </InlineStack>

              <InlineStack align="start" blockAlign="center" gap="1000">
                <BlockStack gap="050">
                  <Text as="p" variant="bodySm" tone="subdued">
                    Hourly Rate
                  </Text>
                  <Text as="h2" variant="headingSm">
                    $90.00
                  </Text>
                </BlockStack>

                <BlockStack gap="050">
                  <Text as="p" variant="bodySm" tone="subdued">
                    Estimated Time
                  </Text>
                  <Text as="h2" variant="headingSm">
                    10 Hours
                  </Text>
                </BlockStack>

                <BlockStack gap="050">
                  <Text as="p" variant="bodySm" tone="subdued">
                    Total to Pay
                  </Text>
                  <Text as="h2" variant="headingSm">
                    $900.00
                  </Text>
                </BlockStack>
              </InlineStack>
            </InlineStack>
          </BlockStack>
        </Card>

        <Card @click="goTo('/expert/project/progress')">
          <BlockStack gap="400">
            <InlineStack align="space-between">
              <Badge tone="info">In Progress</Badge>

              <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                Offer Accepted: *date accepted*
              </Text>
            </InlineStack>

            <BlockStack gap="100">
              <Text as="h2" variant="headingMd">
                *project title*
              </Text>
              <Text as="p" variant="bodyMd" tone="subdued">
                *project website*
              </Text>
            </BlockStack>

            <Divider />

            <InlineStack align="space-between" blockAlign="center">
              <InlineStack align="start" blockAlign="center" gap="200">
                <Avatar style="border-radius: 100%" customer name="Test" size="lg" />

                <BlockStack gap="050">
                  <Text as="h2" variant="headingSm">
                    *client full name*
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    Client
                  </Text>
                </BlockStack>
              </InlineStack>

              <InlineStack align="start" blockAlign="center" gap="1000">
                <BlockStack gap="050">
                  <Text as="p" variant="bodySm" tone="subdued">
                    Hourly Rate
                  </Text>
                  <Text as="h2" variant="headingSm">
                    $80.00
                  </Text>
                </BlockStack>

                <BlockStack gap="050">
                  <Text as="p" variant="bodySm" tone="subdued">
                    Prepaid Hours
                  </Text>
                  <Text as="h2" variant="headingSm">
                    10 Hours
                  </Text>
                </BlockStack>

                <BlockStack gap="050">
                  <Text as="p" variant="bodySm" tone="subdued">
                    Total to Pay
                  </Text>
                  <Text as="h2" variant="headingSm">
                    $800.00
                  </Text>
                </BlockStack>
              </InlineStack>
            </InlineStack>
          </BlockStack>
        </Card>

        <Card @click="goTo('/expert/project/completed')">
          <BlockStack gap="400">
            <InlineStack align="space-between">
              <Badge tone="success">Completed</Badge>

              <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                Mark as Completed: *date marked*
              </Text>
            </InlineStack>

            <BlockStack gap="100">
              <Text as="h2" variant="headingMd">
                *project title*
              </Text>
              <Text as="p" variant="bodyMd" tone="subdued">
                *project website*
              </Text>
            </BlockStack>

            <Divider />

            <InlineStack align="space-between" blockAlign="center">
              <InlineStack align="start" blockAlign="center" gap="200">
                <Avatar style="border-radius: 100%" customer name="Test" size="lg" />

                <BlockStack gap="050">
                  <Text as="h2" variant="headingSm">
                    *client full name*
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    Client
                  </Text>
                </BlockStack>
              </InlineStack>

              <InlineStack align="start" blockAlign="center" gap="1000">
                <BlockStack gap="050">
                  <Text as="p" variant="bodySm" tone="subdued">
                    Hourly Rate
                  </Text>
                  <Text as="h2" variant="headingSm">
                    $80.00
                  </Text>
                </BlockStack>

                <BlockStack gap="050">
                  <Text as="p" variant="bodySm" tone="subdued">
                    Prepaid Hours
                  </Text>
                  <Text as="h2" variant="headingSm">
                    70 Hours
                  </Text>
                </BlockStack>

                <BlockStack gap="050">
                  <Text as="p" variant="bodySm" tone="subdued">
                    Total to Pay
                  </Text>
                  <Text as="h2" variant="headingSm">
                    $5600.00
                  </Text>
                </BlockStack>
              </InlineStack>
            </InlineStack>
          </BlockStack>
        </Card>

        <Card @click="goTo('/expert/project/claimed')">
          <BlockStack gap="400">
            <InlineStack align="space-between">
              <Badge tone="attention">Claimed</Badge>

              <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                Claimed: *date claimed*
              </Text>
            </InlineStack>

            <BlockStack gap="100">
              <Text as="h2" variant="headingMd">
                *project title*
              </Text>
              <Text as="p" variant="bodyMd" tone="subdued">
                *project website*
              </Text>
            </BlockStack>

            <Divider />

            <InlineStack align="space-between" blockAlign="center">
              <InlineStack align="start" blockAlign="center" gap="200">
                <Avatar style="border-radius: 100%" customer name="Test" size="lg" />

                <BlockStack gap="050">
                  <Text as="h2" variant="headingSm">
                    *client full name*
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    Client
                  </Text>
                </BlockStack>
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
      </BlockStack>
    </Page>
    </template>
  </ExpertLayout>
</template>

<style scoped>

</style>