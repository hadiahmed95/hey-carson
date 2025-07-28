<script>
export default {
  name: "TransactionCard",

  props: {
    expert: {
      default: false,
      type: Boolean
    },
    quote: {
      default: false,
      type: Boolean
    },
    scope: {
      default: false,
      type: Boolean
    },
    hours: {
      default: '5',
      type: String
    }
  }
}
</script>

<template>
  <Card padding="600">
    <BlockStack gap="400">
      <InlineStack align="space-between" blockAlign="center">
        <BlockStack gap="100" style="width: 300px">
          <Text variant="bodyMd" tone="subdued">#28192</Text>

          <Text variant="headingMd">*project name 1*</Text>

          <InlineStack gap="200" align="start">
            <Text as="p" variant="bodyMd" tone="subdued">Client:
              <Text as="span" variant="bodyMd" style="color: #005BD3;">
                *Full Name*
              </Text>
            </Text>

            <Text variant="bodyMd" tone="subdued" v-if="expert">Expert:
              <Text as="span" variant="bodyMd" style="color: #005BD3;">
                *Full Name*
              </Text>
            </Text>
          </InlineStack>
        </BlockStack>

        <BlockStack gap="100" inlineAlign="start" style="width: 180px">
          <Text variant="headingMd">${{ scope || quote ? 80 * hours : 98 * hours }}.00</Text>

          <Text variant="bodyMd" tone="subdued" v-if="quote">Custom Quote ({{ hours }} hours)</Text>
          <Text variant="bodyMd" tone="subdued" v-else-if="scope">Add to Scope ({{ hours }} hours)</Text>
          <Text variant="bodyMd" tone="subdued" v-else>{{ hours }} Hours Pack</Text>

          <Text variant="bodyMd" tone="subdued">Dec 17, 2024</Text>
        </BlockStack>

        <BlockStack gap="100">
          <Button variant="plain">Download Invoice</Button>
        </BlockStack>
      </InlineStack>
    </BlockStack>
  </Card>
</template>

<style scoped>

</style>