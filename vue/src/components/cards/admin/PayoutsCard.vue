<script>
import axios from "axios";
import moment from "moment";
import {debounce} from "@/directives/debounce";

export default {
  name: "PayoutsCard",

  props: {
    payout: {
      default: () => {},
      type: Object
    },
  },

  data() {
    return {
      loading: false,
      actionsPopover: false,
      actionsList: [
        {
          content: 'Approve',
          role: 'completed',
          onAction: () => this.updatePayout('completed')
        },
        {
          content: 'Decline',
          role: 'declined',
          onAction: () => this.updatePayout('declined')
        }
      ]
    }
  },

  methods: {
    toggleActionsPopover() {
      this.actionsPopover = !this.actionsPopover;
    },

    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do YYYY')
    },

    updatePayout: debounce(async function(status) {
      this.actionsPopover = false;
      this.loading = true;
      await axios.post('api/admin/payouts/' + this.payout.id, {status: status}).then(() => {
        this.loading = false;
        this.$emit('refresh')
      }).catch(() => {
        this.loading = false;
        this.$emit('refresh')
      })
    }, 200),
  }
}
</script>

<template>
  <Card :padding="null">
    <Box padding="600" class="payouts-card">
      <InlineStack align="space-between" blockAlign="center">
        <InlineStack align="space-between" blockAlign="center" :wrap="false" style="flex: 2">
          <BlockStack gap="050">
            <Text as="p" variant="headingSm">
              {{ payout.user.first_name }}
              {{ payout.user.last_name }}
              <Badge v-if="payout.user.deleted_at" tone="warning">Deactivated</Badge>
            </Text>

            <Text as="p" variant="bodySm" style="color: #005BD3;">
              {{ payout.user.email }}
            </Text>

            <Text as="p" variant="bodySm" tone="subdued">
              {{ payout.type.charAt(0).toUpperCase() + payout.type.slice(1) }}
              <Text as="span" variant="bodySm" style="color: #005BD3;">
                {{ payout.user.email }}
              </Text>
            </Text>
          </BlockStack>

          <BlockStack gap="050">
            <Text as="h2" variant="headingSm">
              ${{ payout.amount.toFixed(2) }}

              <Badge tone="success" v-if="payout.status === 'completed'">Approved</Badge>
              <Badge tone="critical" v-else-if="payout.status === 'declined'">Declined</Badge>
              <Badge tone="attention" v-else>Pending</Badge>
            </Text>

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


        <InlineStack align="end" style="flex: 1">
          <Popover
              :active="actionsPopover"
              autofocusTarget="first-node"
              @close="toggleActionsPopover"
          >
            <template #activator>
              <Button @click="toggleActionsPopover" :loading="loading"
                      :disabled="payout.status !== 'created' || payout.user.deleted_at !== null"
                      :disclosure="actionsPopover ? 'up' : 'down'">Actions</Button>
            </template>
            <ActionList
                actionRole="menuitem"
                :items="actionsList"
            ></ActionList>
          </Popover>
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