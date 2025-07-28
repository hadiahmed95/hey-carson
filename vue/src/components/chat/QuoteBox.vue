<script>
import NoteIcon from "@/components/icons/NoteIcon.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import PlusCircleIcon from "@/components/icons/PlusCircleIcon.vue";
import MobileCard from "@/components/MobileCard.vue";
import moment from "moment";
import InputBtn from "@/components/misc/InputBtn.vue";

export default {
  name: "QuoteBox",

  props: {
    userType: {
      default: 'client',
      type: String,
    },
    message: {
      default: () => {},
      type: Object
    },
    user: {
      default: () => {},
      type: Object
    }
  },
  components: {InputBtn, MobileCard},

  data() {
    return {
      NoteIcon,
      CheckCircle,
      PlusCircleIcon,
      isMobile: screen.width <= 760,

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
    }
  }
}
</script>

<template>
  <Box>
    <MobileCard v-if="isMobile" >
      <BlockStack gap="300">
        <InlineStack gap="200">
          <div style="min-width:40px; min-height:40px; padding: 10px; border-radius: 100%; background: #EAF4FF" v-if="message.offer.type === 'offer'">
            <Icon :source="NoteIcon" />
          </div>

          <div style="min-width:40px; min-height:40px; padding: 10px; border-radius: 100%; background: #FFD6A4" v-else>
            <Icon :source="PlusCircleIcon" />
          </div>

          <BlockStack gap="100">
            <Text as="p" variant="bodyMd" fontWeight="semibold" v-if="message.offer">
              {{ message.offer.type === 'offer' ? userType === 'client' ? 'Expert submitted project quote' : 'Submitted project quote' : 'Additional project scope' }}

              <Badge tone="success" v-if="message.offer.status === 'paid'">Paid</Badge>
              <Badge tone="critical" v-if="message.offer.status === 'decline'">Declined</Badge>
            </Text>
            <Text as="p" variant="bodySm" tone="subdued">
              {{ formatDate(message.created_at) }}
            </Text>
          </BlockStack>
        </InlineStack>

        <BlockStack gap="100">
            <InlineStack align="space-between">
              <Text as="p" variant="bodyMd" tone="subdued">
                Hourly rate
              </Text>
              <Text as="p" variant="headingMd">
                ${{ message.offer.rate.toFixed(2) }}
              </Text>
            </InlineStack>

            <InlineStack align="space-between">
              <Text as="p" variant="bodyMd" tone="subdued">
                {{ message.offer.type === 'offer' ? 'Estimated time' : 'Additional time' }}
              </Text>
              <Text as="p" variant="headingMd">
                {{ message.offer.hours }}
              </Text>
            </InlineStack>

            <InlineStack align="space-between">
              <Text as="p" variant="bodyMd" tone="subdued">
                Total to pay
              </Text>
              <Text as="p" variant="headingMd">
                ${{ (message.offer.hours * message.offer.rate).toFixed(2) }}
              </Text>
            </InlineStack>
          </BlockStack>

        <template  v-if="userType === 'client' && message.offer.status === 'send' && user?.deleted_at === null">
          <Divider />

          <InputBtn :icon="CheckCircle" @click="() => this.$emit('showModal')">Confirm & Pay</InputBtn>

          <Button variant="tertiary" :loading="loading" @click="decline">Decline</Button>
        </template>
      </BlockStack>
    </MobileCard>
    <Card v-else :padding="null">
      <Box padding="600" class="hover-card">
        <BlockStack gap="600">
          <InlineStack align="space-between" blockAlign="center">
            <InlineStack gap="200">
              <div style="min-width:40px; min-height:40px; padding: 10px; border-radius: 100%; background: #EAF4FF" v-if="message.offer.type === 'offer'">
                <Icon :source="NoteIcon" />
              </div>

              <div style="min-width:40px; min-height:40px; padding: 10px; border-radius: 100%; background: #FFD6A4" v-else>
                <Icon :source="PlusCircleIcon" />
              </div>

              <BlockStack gap="100" v-if="message.offer">
                <Text as="p" variant="bodyMd" fontWeight="semibold">
                  {{ message.offer.type === 'offer' ? userType === 'client' ? 'Expert submitted project quote' : 'Submitted project quote' : 'Additional project scope' }}

                  <Badge tone="success" v-if="message.offer.status === 'paid'">Paid</Badge>
                  <Badge tone="critical" v-if="message.offer.status === 'decline'">Declined</Badge>
                </Text>
                <Text as="p" variant="bodySm" tone="subdued">
                  {{ formatDate(message.created_at) }}
                </Text>
              </BlockStack>
            </InlineStack>


            <InlineStack gap="1600">
              <BlockStack gap="100">
                <Text as="p" variant="bodyMd" tone="subdued">
                  Hourly rate
                </Text>
                <Text as="p" variant="headingMd">
                  ${{ message.offer.rate.toFixed(2) }}
                </Text>
              </BlockStack>

              <BlockStack gap="100">
                <Text as="p" variant="bodyMd" tone="subdued">
                  {{ message.offer.type === 'offer' ? 'Estimated time' : 'Additional time' }}
                </Text>
                <Text as="p" variant="headingMd">
                  {{ message.offer.hours }}
                </Text>
              </BlockStack>

              <BlockStack gap="100">
                <Text as="p" variant="bodyMd" tone="subdued">
                  Total to pay
                </Text>
                <Text as="p" variant="headingMd">
                  ${{ (message.offer.hours * message.offer.rate).toFixed(2) }}
                </Text>
              </BlockStack>
            </InlineStack>
          </InlineStack>

          <template  v-if="userType === 'client' && message.offer.status === 'send' && user?.deleted_at === null">
            <Divider />

            <InlineStack align="end" gap="200">
              <Button variant="tertiary" :loading="loading" @click="decline">Decline</Button>

              <InputBtn :icon="CheckCircle" @click="() => this.$emit('showModal')">Confirm & Pay</InputBtn>
            </InlineStack>
          </template>
        </BlockStack>
      </Box>
    </Card>
  </Box>
</template>

<style scoped>
.hover-card:hover {
  background: #f9f9f9;
}
</style>