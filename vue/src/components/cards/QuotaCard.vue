<script>
import NoteIcon from "@/components/icons/NoteIcon.vue";
import PlusCircleIcon from "@/components/icons/PlusCircleIcon.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import MobileCard from "@/components/MobileCard.vue";
import moment from "moment/moment";
import InputBtn from "@/components/misc/InputBtn.vue";
import common from "@/mixins/common";

export default {
  name: "QuotaCard",

  components: {InputBtn, MobileCard},

  props: {
    quota: {
      default: () => ({
        type: 'offer', // offer or scope,
        time: '',
        rate: '',
        hours: '',
        total: '',
        paid: false
      }),
      type: Object
    },

    expert: {
      default: false,
      type: Boolean
    },

    user: {
      default: () => {},
      type: Object
    }
  },

  mixins: [common],

  data() {
    return {
      NoteIcon,
      PlusCircleIcon,
      CheckCircle,
      isMobile: screen.width <= 760,

      showOfferModal: false,
      loading: false,

    }
  },

  methods: {
    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do YYYY')
    },

    decline() {
      this.loading = true;

      this.$emit('decline')
    },
  },

  computed: {
    total() {
      return (this.quota.hours * this.quota.rate).toFixed(2);
    }
  }
}
</script>

<template>
  <MobileCard v-if="isMobile">
    <BlockStack gap="600">
        <InlineStack gap="200" v-if="quota.type === 'offer'">
          <div style="min-width:40px; min-height:40px; padding: 10px; border-radius: 100%; background: #EAF4FF">
            <Icon :source="NoteIcon" />
          </div>

          <BlockStack gap="100">
            <Text as="p" variant="bodyMd" fontWeight="semibold">
              {{ expert ? 'Submitted project quote' : 'Expert submitted project quote' }}
              <Badge tone="success" v-if="quota.status === 'paid'">Paid</Badge>
              <Badge tone="critical" v-if="quota.status === 'decline'">Declined</Badge>
              <Badge tone="critical" v-if="quota.status === 'expired'">Expired</Badge>
            </Text>
            <Text as="p" variant="bodySm" tone="subdued">
              {{ formatDate(quota.updated_at) }}
            </Text>
          </BlockStack>
        </InlineStack>

        <InlineStack gap="200" v-if="quota.type === 'scope'">
          <div style="min-width:40px; min-height:40px; padding: 10px; border-radius: 100%; background: #FFD6A4">
            <Icon :source="PlusCircleIcon" />
          </div>

          <BlockStack gap="100">
            <Text as="p" variant="bodyMd" fontWeight="semibold">
              Additional project scope
              <Badge tone="success" v-if="quota.status === 'paid'">Paid</Badge>
              <Badge tone="critical" v-if="quota.status === 'decline'">Declined</Badge>
              <Badge tone="critical" v-if="quota.status === 'expired'">Expired</Badge>
            </Text>
            <Text as="p" variant="bodySm" tone="subdued">
              {{ formatDate(quota.updated_at) }}
            </Text>
          </BlockStack>
        </InlineStack>


        <BlockStack gap="100">
          <InlineStack align="space-between">
            <Text as="p" variant="bodyMd" tone="subdued">
              Hourly rate
            </Text>
            <Text as="p" variant="headingMd">
              ${{ quota.rate.toFixed(2) }}
            </Text>
          </InlineStack>

          <InlineStack align="space-between">
            <Text as="p" variant="bodyMd" tone="subdued">
              {{ quota.type === 'offer' ? 'Estimated time' : 'Additional time' }}
            </Text>
            <Text as="p" variant="headingMd">
              {{ quota.hours }}
            </Text>
          </InlineStack>

          <InlineStack align="space-between">
            <Text as="p" variant="bodyMd" tone="subdued">
              Deadline
            </Text>
            <Text as="p" variant="headingMd">
              {{ quota.deadline ? formatDeadline( new Date(quota.deadline) ) : '-' }}
            </Text>
          </InlineStack>

          <InlineStack align="space-between">
            <Text as="p" variant="bodyMd" tone="subdued">
              Total to Pay
            </Text>
            <Text as="p" variant="headingMd">
              ${{ total }}
            </Text>
          </InlineStack>
        </BlockStack>

      <template v-if="quota.status === 'send' && !expert && !user.deleted_at">
        <Divider />

        <BlockStack gap="200">
          <InputBtn :icon="CheckCircle" @click="() => this.$emit('showModal')">Confirm & Pay</InputBtn>

          <Button variant="tertiary" :loading="loading" @click="decline">Decline</Button>
        </BlockStack>
      </template>
    </BlockStack>
  </MobileCard>

  <Card :padding="null" v-else>
    <Box padding="600" class="hover-card">
      <BlockStack gap="600">
        <InlineStack align="space-between" blockAlign="center">
          <InlineStack gap="200" v-if="quota.type === 'offer'">
            <div style="min-width:40px; min-height:40px; padding: 10px; border-radius: 100%; background: #EAF4FF">
              <Icon :source="NoteIcon" />
            </div>

            <BlockStack gap="100">
              <Text as="p" variant="bodyMd" fontWeight="semibold">
                {{ expert ? 'Submitted project quote' : 'Expert submitted project quote' }}
                <Badge tone="success" v-if="quota.status === 'paid'">Paid</Badge>
                <Badge tone="critical" v-if="quota.status === 'decline'">Declined</Badge>
                <Badge tone="critical" v-if="quota.status === 'expired'">Expired</Badge>
              </Text>
              <Text as="p" variant="bodySm" tone="subdued">
                {{ formatDate(quota.updated_at) }}
              </Text>
            </BlockStack>
          </InlineStack>

          <InlineStack gap="200" v-if="quota.type === 'scope'">
            <div style="min-width:40px; min-height:40px; padding: 10px; border-radius: 100%; background: #FFD6A4">
              <Icon :source="PlusCircleIcon" />
            </div>

            <BlockStack gap="100">
              <Text as="p" variant="bodyMd" fontWeight="semibold">
                Additional project scope
                <Badge tone="success" v-if="quota.status === 'paid'">Paid</Badge>
                <Badge tone="critical" v-if="quota.status === 'decline'">Declined</Badge>
                <Badge tone="critical" v-if="quota.status === 'expired'">Expired</Badge>
              </Text>
              <Text as="p" variant="bodySm" tone="subdued">
                {{ formatDate(quota.updated_at) }}
              </Text>
            </BlockStack>
          </InlineStack>


          <InlineStack gap="1600">
            <BlockStack gap="100">
              <Text as="p" variant="bodyMd" tone="subdued">
                Hourly rate
              </Text>
              <Text as="p" variant="headingMd">
                ${{ quota.rate.toFixed(2) }}
              </Text>
            </BlockStack>

            <BlockStack gap="100">
              <Text as="p" variant="bodyMd" tone="subdued">
                {{ quota.type === 'offer' ? 'Estimated time' : 'Additional time' }}
              </Text>
              <Text as="p" variant="headingMd">
                {{ quota.hours }}
              </Text>
            </BlockStack>

            <BlockStack gap="100">
              <Text as="p" variant="bodyMd" tone="subdued">
                Deadline
              </Text>
              <Text as="p" variant="headingMd" class="deadline-width">
                {{ quota.deadline ? formatDeadline(new Date(quota.deadline)) : '-' }}
              </Text>
            </BlockStack>

            <BlockStack gap="100">
              <Text as="p" variant="bodyMd" tone="subdued">
                Total to pay
              </Text>
              <Text as="p" variant="headingMd">
                ${{ total }}
              </Text>
            </BlockStack>
          </InlineStack>
        </InlineStack>

        <template v-if="quota.status === 'send' && !expert && !user.deleted_at">
          <Divider />

          <InlineStack align="end" gap="200">
            <Button variant="tertiary" :loading="loading" @click="decline">Decline</Button>

            <InputBtn :icon="CheckCircle" @click="() => this.$emit('showModal')">Confirm & Pay</InputBtn>
          </InlineStack>
        </template>
      </BlockStack>
    </Box>
  </Card>
</template>

<style scoped>
.hover-card:hover {
  background: #f9f9f9;
}

.deadline-width {
  width: 90px;
}
</style>