<script>
import MobileCard from "@/components/MobileCard.vue";
import moment from "moment";

export default {
  name: "InvoiceCard",
  components: {MobileCard},

  props: {
    item: {
      type: Object,
      default: () => {}
    },

    project: {
      type: Object,
      default: () => {}
    }
  },

  data() {
    return {
      isMobile: screen.width <= 760,
    }
  },

  methods: {
    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do YYYY')
    },
  }
}
</script>

<template>
  <MobileCard padding="600">
    <template v-if="isMobile">
      <BlockStack gap="300">
        <BlockStack gap="100" style="min-width: 160px">
          <Text variant="bodyMd" tone="subdued">Project ID: #{{ project.id }}</Text>

          <Text variant="headingMd"  v-if="item.offer">
            {{ item.offer.type === 'offer' ? 'Custom Quote Offer' : 'Add to Scope' }}
            ({{ item.amount}} hours)
          </Text>

          <Text variant="bodyMd" tone="subdued">
            Paid via
            {{ item.status === 'prepaid' ? 'Prepaid Hours' : 'Direct Payment' }}
          </Text>
        </BlockStack>

        <BlockStack gap="100" inlineAlign="end" style="min-width: 240px">
          <Text variant="headingMd">${{ item.total.toFixed(2) }}</Text>

          <Text variant="bodyMd" tone="subdued">Payment date: {{ formatDate(item.created_at) }}</Text>
        </BlockStack>
      </BlockStack>
    </template>

    <template v-else>
      <InlineStack align="space-between">
        <InlineStack align="space-between" style="flex: 4">
          <InlineStack align="space-between" style="flex: 2">
            <BlockStack gap="100" style="min-width: 160px">
              <Text variant="bodyMd" tone="subdued">Project ID: #{{ project.id }}</Text>

              <Text variant="headingMd"  v-if="item.offer">
                {{ item.offer.type === 'offer' ? 'Custom Quote Offer' : 'Add to Scope' }}
                ({{ item.amount}} hours)
              </Text>

              <Text variant="bodyMd" tone="subdued">
                Paid via
                {{ item.status === 'prepaid' ? 'Prepaid Hours' : 'Direct Payment' }}
              </Text>
            </BlockStack>
          </InlineStack>

          <InlineStack align="start" style="flex: 1">
            <BlockStack gap="100" style="min-width: 240px">
              <Text variant="headingMd">${{ item.total.toFixed(2) }}</Text>

              <Text variant="bodyMd" tone="subdued">Payment date: {{ formatDate(item.created_at) }}</Text>
            </BlockStack>
          </InlineStack>
        </InlineStack>
      </InlineStack>
    </template>
  </MobileCard>
</template>

<style scoped>

</style>