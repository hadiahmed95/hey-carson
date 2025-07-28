<script>
import ClientLayout from "@/layout/ClientLayout.vue";
import CreateProjectModal from "@/components/modals/CreateProjectModal.vue";
import AddIcon from "@/components/icons/AddIcon.vue";
import AlertCircleIcon from "@/components/icons/AlertCircleIcon.vue";
import MobileCard from "@/components/MobileCard.vue";
export default {
  name: "DashboardPage",

  components: {
    ClientLayout,
    CreateProjectModal,
    MobileCard
  },

  data() {
    return {
      AddIcon,
      AlertCircleIcon,

      isMobile: screen.width <= 760,

      statusPopover: false,

      activeStatus: 'all',

      createModal: false,

      statusList: [
        {
          content: 'All',
          role: 'all',
          onAction: () => this.selectAction('all')
        },
        {
          content: 'Pending Match',
          role: 'pending_match',
          onAction: () => this.selectAction('pending_match')
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
      ]
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
    },
  }
}
</script>

<template>
  <ClientLayout :modal="false">
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

        <Button variant="primary" size="large" :icon="AddIcon" @click="() => this.createModal = true">New Project</Button>

        <BlockStack gap="200">
          <MobileCard>
            <BlockStack gap="400">
              <InlineStack align="space-between">
                <Badge tone="attention">Pending Match</Badge>

                <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                  Submitted: *date created*
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

              <InlineStack align="start" gap="100" :wrap="false">
                <Box>
                  <Icon :source="AlertCircleIcon" />
                </Box>

                <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                  We are working on matching you with the best possible expert for your project. Thanks for your patience.
                </Text>
              </InlineStack>
            </BlockStack>
          </MobileCard>

          <MobileCard >
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
                    *developer full name*
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    *developer title*
                  </Text>
                </BlockStack>
              </InlineStack>
            </BlockStack>
          </MobileCard>

          <MobileCard >
            <BlockStack gap="400">
              <InlineStack align="space-between">
                <Badge tone="critical">Pending Payment</Badge>

                <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                  Approved: *date approved*
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
                    *developer full name*
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    *developer title*
                  </Text>
                </BlockStack>
              </InlineStack>
            </BlockStack>
          </MobileCard>

          <MobileCard >
            <BlockStack gap="400">
              <InlineStack align="space-between">
                <Badge tone="info">In Progress</Badge>

                <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                  Latest Update: *date updated*
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
                    *developer full name*
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    *developer title*
                  </Text>
                </BlockStack>
              </InlineStack>
            </BlockStack>
          </MobileCard>

          <MobileCard>
            <BlockStack gap="400">
              <InlineStack align="space-between">
                <Badge tone="success">Completed</Badge>

                <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                  Completed: *date completed*
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
                    *developer full name*
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    *developer title*
                  </Text>
                </BlockStack>
              </InlineStack>
            </BlockStack>
          </MobileCard>

          <MobileCard>
            <BlockStack gap="400">
              <InlineStack align="space-between">
                <Badge>Archived</Badge>

                <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                  Archived: *date completed*
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
                    *developer full name*
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    *developer title*
                  </Text>
                </BlockStack>
              </InlineStack>
            </BlockStack>
          </MobileCard>
        </BlockStack>
      </BlockStack>
    </template>
    <template v-else>
      <Page style="padding: 56px 16px"
            title="My Projects"
      >
        <template #primaryAction>
          <Button variant="primary" size="large" :icon="AddIcon" @click="() => this.createModal = true">New Project</Button>
        </template>

        <template #pageTitle>
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

        <BlockStack gap="200">

          <Card @click="goTo('/client/project')">
            <BlockStack gap="400">
              <InlineStack align="space-between">
                <Badge tone="attention">Pending Match</Badge>

                <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                  Submitted: *date created*
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
                  We are working on matching you with the best possible expert for your project. Thanks for your patience.
                </Text>
              </InlineStack>
            </BlockStack>
          </Card>

          <Card @click="goTo('/client/project/matched')">
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
                    *developer full name*
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    *developer title*
                  </Text>
                </BlockStack>
              </InlineStack>
            </BlockStack>
          </Card>

          <Card @click="goTo('/client/project/pending')">
            <BlockStack gap="400">
              <InlineStack align="space-between">
                <Badge tone="critical">Pending Payment</Badge>

                <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                  Approved: *date approved*
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
                    *developer full name*
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    *developer title*
                  </Text>
                </BlockStack>
              </InlineStack>
            </BlockStack>
          </Card>

          <Card @click="goTo('/client/project/progress')">
            <BlockStack gap="400">
              <InlineStack align="space-between">
                <Badge tone="info">In Progress</Badge>

                <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                  Latest Update: *date updated*
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
                    *developer full name*
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    *developer title*
                  </Text>
                </BlockStack>
              </InlineStack>
            </BlockStack>
          </Card>

          <Card @click="goTo('/client/project/completed')">
            <BlockStack gap="400">
              <InlineStack align="space-between">
                <Badge tone="success">Completed</Badge>

                <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                  Completed: *date completed*
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
                    *developer full name*
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    *developer title*
                  </Text>
                </BlockStack>
              </InlineStack>
            </BlockStack>
          </Card>

          <Card>
            <BlockStack gap="400">
              <InlineStack align="space-between">
                <Badge>Archived</Badge>

                <Text variant="bodySm" as="p" alignment="start" tone="subdued">
                  Archived: *date completed*
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
                    *developer full name*
                  </Text>
                  <Text as="p" variant="bodySm" tone="subdued">
                    *developer title*
                  </Text>
                </BlockStack>
              </InlineStack>
            </BlockStack>
          </Card>
        </BlockStack>
      </Page>
    </template>

    <CreateProjectModal v-show="createModal" @close="() => this.createModal = false"/>
  </ClientLayout>
</template>

<style scoped>

</style>