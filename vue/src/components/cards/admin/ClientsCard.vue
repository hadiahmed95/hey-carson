<script>
import StarFullIcon from "@/components/icons/StarFullIcon.vue";
import moment from "moment";
import AvatarFrame from "@/components/misc/AvatarFrame.vue";
import LoginAsModal from "@/components/modals/LoginAsModal.vue";

export default {
  name: "ClientsCard",
  components: {AvatarFrame, LoginAsModal},

  props: {
    client: {
      type: Object,
      default: () => {
        return {}
      }
    }
  },

  data() {
    return {
      StarFullIcon,
      loginAs: false,
    }
  },

  methods: {
    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do YYYY')
    },
    goTo(id) {
      window.open('/admin/client/' + id, '_blank')
    },

    toggleLoginAsModal() {
      this.loginAs = !this.loginAs;
    },
  }
}
</script>

<template>
  <Card :padding="null" @click="goTo(client.id)">
    <Box padding="800" class="project-card">
      <InlineStack align="space-between" blockAlign="center" >
        <InlineStack align="start" blockAlign="center" gap="200">
          <AvatarFrame rounded size="lg" :user="client" />

          <BlockStack gap="100">
            <Text as="h2" variant="headingSm">
              {{ client.first_name }} {{ client.last_name }}
            </Text>
            <Text @click.stop>
              <Link :url="client.url.startsWith('https://') ? client.url : 'https://' + client.url" target="_blank" removeUnderline style="color: #005BD3;">
                {{ client.url }}
              </Link>
            </Text>
            <Text variant="bodySm" tone="subdued">Shopify Plan:<Text v-if="client.shopify_plan" as="span" variant="bodySm" fontWeight="semibold">{{ client.shopify_plan }}</Text></Text>
            <InlineStack gap="100">
              <div>
                <Icon :source="StarFullIcon" />
              </div>
              <Text as="p" variant="bodyMd">0.0 <Text as="span" variant="bodyMd" tone="subdued">(0 Completed Projects)</Text></Text>
            </InlineStack>
          </BlockStack>
        </InlineStack>

        <BlockStack gap="050">
          <Text as="p" variant="bodySm" tone="subdued">Submitted Projects: {{ client.projects_count }}</Text>

          <Text as="p" variant="bodySm" tone="subdued">Joined Date: {{ formatDate(client.created_at) }}</Text>
        </BlockStack>

        <InlineStack gap="100">
          <Button variant="secondary" size="large" @click.stop="toggleLoginAsModal">Login as</Button>
        </InlineStack>
      </InlineStack>
    </Box>
  </Card>
  <LoginAsModal v-if="loginAs" type='client' :user="client" @close="toggleLoginAsModal" />
</template>

<style scoped>
.project-card:hover {
  cursor: pointer;
  background: #f9f9f9;
}
.project-card:active {
  cursor: pointer;
  background: #f2f2f2;
}
</style>
