<script>
import ExpertLayout from "@/layout/ExpertLayout.vue";
import AddIcon from "@/components/icons/AddIcon.vue";
import AlertCircleIcon from "@/components/icons/AlertCircleIcon.vue";
import XIcon from "@/components/icons/XIcon.vue";
import emptyState from '../../../assets/empty-state.png';
import MobileCard from "@/components/MobileCard.vue";

export default {
  name: "TeamPage",

  components: {MobileCard, ExpertLayout},

  data() {
    return {
      AddIcon,
      AlertCircleIcon,
      XIcon,
      emptyState,
      isMobile: screen.width <= 760,

      selected1: 'admin',
      selected2: 'team',

      options: [
        {label: 'Admin', value: 'admin'},
        {label: 'Team Member', value: 'team'}
      ]
    }
  },
}
</script>

<template>
  <ExpertLayout>
    <template v-if="isMobile">
      <BlockStack gap="600" style="padding: 32px 16px;">
        <InlineStack align="space-between" blockAlign="center">
          <Text variant="headingLg" as="p">My Team</Text>

          <Button variant="primary" size="large" :icon="AddIcon" @click="toggleAddTeamModal">Add Member</Button>
        </InlineStack>

        <BlockStack gap="300">
          <MobileCard>
            <BlockStack gap="400">
              <BlockStack>
                <Text variant="bodyMd" as="p" fontWeight="semibold">
                  *User Name*
                </Text>

                <Text variant="bodyMd" as="p" tone="subdued">
                  *User Email*
                </Text>
              </BlockStack>

              <div style="width: 236px">
                <Text variant="bodyMd" as="p" tone="subdued">
                  Account Owner
                </Text>
              </div>
            </BlockStack>
          </MobileCard>


          <MobileCard>
            <BlockStack gap="400">
              <BlockStack>
                <Text variant="bodyMd" as="p" fontWeight="semibold">
                  *Team Name 1*
                </Text>

                <Text variant="bodyMd" as="p" tone="subdued">
                  *Team Email 1*
                </Text>
              </BlockStack>

              <InlineStack gap="400" blockAlign="center">
                <div style="flex: auto">
                  <Select
                      :options="options"
                      v-model="selected1"
                  />
                </div>

                <div>
                  <Icon :source="XIcon" @click="toggleRemoveTeamModal"></Icon>
                </div>
              </InlineStack>
            </BlockStack>
          </MobileCard>

          <MobileCard>
            <BlockStack gap="400">
              <BlockStack>
                <Text variant="bodyMd" as="p" fontWeight="semibold">
                  *Team Name 2*
                </Text>

                <Text variant="bodyMd" as="p" tone="subdued">
                  *Team Email 2*
                </Text>
              </BlockStack>

              <InlineStack gap="400" blockAlign="center">
                <div style="flex: auto">
                  <Select
                      :options="options"
                      v-model="selected2"
                  />
                </div>

                <div style="row-span: 1">
                  <Icon :source="XIcon" @click="toggleRemoveTeamModal"></Icon>
                </div>
              </InlineStack>
            </BlockStack>
          </MobileCard>
        </BlockStack>
      </BlockStack>
    </template>
    <template v-else>
      <Page style="padding-top: 56px; padding-bottom: 56px"
            title="My Team"
      >
        <template #primaryAction>
          <Button variant="primary" size="large" :icon="AddIcon">Add Member</Button>
        </template>
        <template #secondaryActions>
          <Popover
              :active="false"
              autofocusTarget="first-node"
          >
            <template #activator>
              <Button size="large"
                      :icon="AlertCircleIcon"
                      :disclosure="'down'">Permissions</Button>
            </template>
            <ActionList
                actionRole="menuitem"
                :items="statusList"
            ></ActionList>
          </Popover>
        </template>

        <BlockStack gap="300">
          <Card padding="600">
            <InlineStack align="space-between" blockAlign="center">
              <BlockStack>
                <Text variant="bodyMd" as="p" fontWeight="semibold">
                  *User Name*
                </Text>

                <Text variant="bodyMd" as="p" tone="subdued">
                  *User Email*
                </Text>
              </BlockStack>

              <div style="width: 236px">
                <Text variant="bodyMd" as="p" tone="subdued">
                  Account Owner
                </Text>
              </div>
            </InlineStack>
          </Card>

          <Card padding="600">
            <InlineStack align="space-between" blockAlign="center">
              <BlockStack>
                <Text variant="bodyMd" as="p" fontWeight="semibold">
                  *Team Name 1*
                </Text>

                <Text variant="bodyMd" as="p" tone="subdued">
                  *Team Email 1*
                </Text>
              </BlockStack>

              <InlineStack gap="400">
                <div style="width: 200px">
                  <Select
                      :options="options"
                      v-model="selected1"
                  />
                </div>

                <div>
                  <Icon :source="XIcon"></Icon>
                </div>
              </InlineStack>
            </InlineStack>
          </Card>

          <Card padding="600">
            <InlineStack align="space-between" blockAlign="center">
              <BlockStack>
                <Text variant="bodyMd" as="p" fontWeight="semibold">
                  *Team Name 2*
                </Text>

                <Text variant="bodyMd" as="p" tone="subdued">
                  *Team Email 2*
                </Text>
              </BlockStack>

              <InlineStack gap="400">
                <div style="width: 200px">
                  <Select
                      :options="options"
                      v-model="selected2"
                  />
                </div>

                <div>
                  <Icon :source="XIcon"></Icon>
                </div>
              </InlineStack>
            </InlineStack>
          </Card>
        </BlockStack>
      </Page>
    </template>
  </ExpertLayout>
</template>

<style scoped>

</style>