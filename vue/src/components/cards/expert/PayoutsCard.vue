<script>
import moment from "moment/moment";
import MobileCard from "@/components/MobileCard.vue";

export default {
  name: "PayoutsCard",

  components: {MobileCard},

  props: {
    payout: {
      default: () => {},
      type: Object
    },
  },

  data() {
    return {
      currentUser: JSON.parse(window.localStorage.getItem('CURRENT_USER')),
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
  <MobileCard v-if="isMobile" class="payouts-card">
    <BlockStack gap="200">
      <InlineStack align="space-between" blockAlign="center" style="flex: 2">
        <BlockStack gap="050">
          <Text as="p" variant="headingSm">
            {{ payout.type.charAt(0).toUpperCase() + payout.type.slice(1) }}
          </Text>

          <Text as="p" variant="bodySm" tone="subdued">
            {{ currentUser.email }}
          </Text>
        </BlockStack>

        <BlockStack gap="050">
          <Text as="p" variant="bodySm" tone="subdued">
            Requested: {{ formatDate(payout.created_at) }}
          </Text>

          <Text as="p" variant="bodySm" tone="subdued" v-if="payout.status === 'completed'">
            Approved: {{ formatDate(payout.updated_at) }}
          </Text>

          <Text as="p" variant="bodySm" tone="subdued" v-if="payout.status === 'declined'">
            Declined: {{ formatDate(payout.updated_at) }}
          </Text>
        </BlockStack>
      </InlineStack>

      <InlineStack align="start" gap="200" style="min-width: 300px;">
        <Text as="h2" variant="headingSm" alignment="end">
          ${{ payout.amount.toFixed(2)}}
        </Text>

        <Badge tone="success" v-if="payout.status === 'completed'">Approved</Badge>
        <Badge tone="critical" v-else-if="payout.status === 'declined'">Declined</Badge>
        <Badge tone="attention" v-else>Pending</Badge>
      </InlineStack>
    </BlockStack>
  </MobileCard>
  <Card v-else :padding="null">
    <Box padding="600" class="payouts-card">
      <InlineStack align="space-between" blockAlign="center">
        <InlineStack align="space-between" blockAlign="center" style="flex: 2">
          <BlockStack gap="050">
            <Text as="p" variant="headingSm">
              {{ payout.type.charAt(0).toUpperCase() + payout.type.slice(1) }}
            </Text>

            <Text as="p" variant="bodySm" tone="subdued">
              {{ currentUser.email }}
            </Text>
          </BlockStack>

          <BlockStack gap="050">
            <Text as="p" variant="bodySm" tone="subdued">
              Requested: {{ formatDate(payout.created_at) }}
            </Text>

            <Text as="p" variant="bodySm" tone="subdued" v-if="payout.status === 'completed'">
              Approved: {{ formatDate(payout.updated_at) }}
            </Text>

            <Text as="p" variant="bodySm" tone="subdued" v-if="payout.status === 'declined'">
              Declined: {{ formatDate(payout.updated_at) }}
            </Text>
          </BlockStack>
        </InlineStack>

        <InlineStack align="end" gap="200" style="min-width: 300px;">
          <Text as="h2" variant="headingSm" alignment="end">
                ${{ payout.amount.toFixed(2)}}
          </Text>

          <Badge tone="success" v-if="payout.status === 'completed'">Approved</Badge>
          <Badge tone="critical" v-else-if="payout.status === 'declined'">Declined</Badge>
          <Badge tone="attention" v-else>Pending</Badge>
        </InlineStack>
      </InlineStack>
    </Box>
  </Card>
</template>

<style scoped>
.payouts-card:hover {
  background: #f9f9f9;
}
</style>