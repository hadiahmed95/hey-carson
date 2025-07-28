<script>
import AvatarFrame from "@/components/misc/AvatarFrame.vue";

export default {
  name: "UserBox",

  props: {
    user: {
      default: () => {},
      type: Object
    },
    client: {
      default: false,
      type: Boolean
    },
    assigned: {
      default: false,
      type: Boolean
    },
    preferred: {
      default: false,
      type: Boolean
    },
    role: {
      default: false,
      type: Boolean
    },
    isMobile: {
      default: false,
      type: Boolean
    },
    isClientUserOnline: {
      default: false,
      type: Boolean
    },
    isShowEmail: {
      default: false,
      type: Boolean
    },
    isShowClientUrl: {
      default: false,
      type: Boolean
    },
    isShowShopifyPlan: {
      default: false,
      type: Boolean
    },
    isShowBalance: {
      default: false,
      type: Boolean
    }
  },

  components: {AvatarFrame}
}
</script>

<template>
  <template  v-if="isMobile">
    <BlockStack v-if="user" align="start" gap="200">
      <AvatarFrame rounded size="lg" :user="user" />

      <BlockStack gap="100">
        <Text as="h2" variant="headingSm">
          {{ user.first_name }} {{ user.last_name }}
        </Text>
        <Text as="p" variant="bodySm" tone="subdued" v-if="role">
          {{ user.profile.role }}
        </Text>
        <Text as="p" variant="bodySm" tone="subdued" v-if="client && !isShowEmail && !isShowClientUrl">
          Client
        </Text>
        <Text as="p" variant="bodySm" tone="subdued" v-if="client && !isShowClientUrl && isShowBalance && user.hasOwnProperty('prepaid_hours')">
          Balance:
          <Text as="span" variant="bodySm" fontWeight="semibold">
            {{ user.prepaid_hours }}
          </Text>
          Prepaid Hours
        </Text>
        <Text variant="bodySm" tone="subdued" v-if="client && !isShowEmail && !isShowClientUrl && isShowShopifyPlan">Shopify Plan:<Text v-if="user.shopify_plan" as="span" variant="bodySm" fontWeight="semibold">{{ user.shopify_plan }}</Text></Text>
        <Text as="p" variant="bodySm" tone="subdued" v-if="client && !isShowEmail && isShowClientUrl">
          <Link :url="user.url.startsWith('https://') ? user.url : 'https://' + user.url" target="_blank" removeUnderline style="color: #6E6E73;">
            {{ user.url }}
          </Link>
        </Text>
        <Text as="p" variant="bodySm" v-if="isShowEmail">
          Client's Email: <span class="email-color"> {{ user.email }} </span>
        </Text>
        <Text as="p" variant="bodySm" tone="subdued" v-if="assigned">
          Assigned Expert
        </Text>
        <Text as="p" variant="bodySm" tone="subdued" v-if="preferred">
          Preferred Expert
        </Text>
      </BlockStack>
      <span v-if="isClientUserOnline" class="online-status">
        &#8226;
      </span>
    </BlockStack>
  </template>
  <template v-else>
    <InlineStack v-if="user" align="start" blockAlign="center" gap="200">
      <AvatarFrame rounded size="lg" :user="user"  />

      <BlockStack gap="100">
        <Text as="h2" variant="headingSm">
          {{ user.first_name }} {{ user.last_name }}
          <span v-if="isClientUserOnline" style="font-size: 45px; color: #34C759; position: relative; top: 8px;">&#8226;</span>
          <Badge v-if="user.deleted_at !== null" tone="warning">Deactivated</Badge>
        </Text>
        <Text as="p" variant="bodySm" tone="subdued" v-if="role">
          {{ user.profile.role }}
        </Text>
        <Text as="p" variant="bodySm" tone="subdued" v-if="client && !isShowEmail && !isShowClientUrl && !isShowBalance">
          Client
        </Text>
        <Text variant="bodySm" tone="subdued" v-if="client && !isShowEmail && !isShowClientUrl && isShowShopifyPlan">Shopify Plan: <Text v-if="user.shopify_plan" as="span" variant="bodySm" fontWeight="semibold">{{ user.shopify_plan }}</Text></Text>
        <Text as="p" variant="bodySm" tone="subdued" v-if="client && !isShowClientUrl && isShowBalance && user.hasOwnProperty('prepaid_hours')">
          Balance:
          <Text as="span" variant="bodySm" fontWeight="semibold">
            {{ user.prepaid_hours }}
          </Text>
          Prepaid Hours
        </Text>
        <Text as="p" variant="bodySm" tone="subdued" v-if="client && !isShowEmail && isShowClientUrl">
          <Link :url="user.url.startsWith('https://') ? user.url : 'https://' + user.url" target="_blank" removeUnderline style="color: #6E6E73;">
            {{ user.url }}
          </Link>
        </Text>
        <Text as="p" variant="bodySm" v-if="isShowEmail">
          Client's Email: <span class="email-color"> {{ user.email }} </span>
        </Text>
        <Text as="p" variant="bodySm" tone="subdued" v-if="assigned">
          Assigned Expert
        </Text>
        <Text as="p" variant="bodySm" tone="subdued" v-if="preferred">
          Preferred Expert
        </Text>
      </BlockStack>
    </InlineStack>
  </template>
</template>

<style scoped>
.online-status {
  color: #34C759; /* green color */
  font-size: 45px;
  margin-bottom: 27px;
  margin-left: -8px;
}

.email-color {
  color: #005BD3;
}
</style>
