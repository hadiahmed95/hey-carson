<script>
export default {
  name: "ProjectIdeasCard",

  data() {
    return {
      actionsPopover: false,
      actionsList: [
        {
          content: 'TBA',
          role: 'tba',
          onAction: () => console.log('tba')
        }
      ]
    }
  },

  methods: {
    toggleActionsPopover() {
      this.actionsPopover = !this.actionsPopover;
    }
  }
}
</script>

<template>
  <Card padding="600">
    <InlineStack align="space-between" blockAlign="center">
      <BlockStack gap="050">
        <Text as="p" variant="headingSm">
          *Project Idea Name*

          <Badge tone="attention">Pending</Badge>
        </Text>

        <Text as="p" variant="bodySm" tone="subdued">
          Development
        </Text>

        <Text as="p" variant="bodySm" tone="subdued">
          Expert:
          <Text as="span" variant="bodySm" style="color: #005BD3;">
            *Expert Full Name*
          </Text>
        </Text>
      </BlockStack>

      <BlockStack gap="050">
        <Text as="h2" variant="headingSm">
          $800.00
        </Text>

        <Text as="p" variant="bodySm" tone="subdued">
          Submitted: Dec 17, 2024
        </Text>
      </BlockStack>

      <Popover
          :active="actionsPopover"
          autofocusTarget="first-node"
          @close="toggleActionsPopover"
      >
        <template #activator>
          <Button @click="toggleActionsPopover"
                  :disclosure="actionsPopover ? 'up' : 'down'">Actions</Button>
        </template>
        <ActionList
            actionRole="menuitem"
            :items="actionsList"
        ></ActionList>
      </Popover>
    </InlineStack>
  </Card>
</template>

<style scoped>

</style>