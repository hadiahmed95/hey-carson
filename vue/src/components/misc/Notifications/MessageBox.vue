<script>
import AvatarFrame from "@/components/misc/AvatarFrame.vue";
import moment from "moment/moment";

export default {
  name: "MessageBox",

  components: {AvatarFrame},

  props: {
    item: {
      type: Object,
      default: () => {}
    }
  },

  methods: {
    dateFormat(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").fromNow()
    },

    open() {
      this.$emit('goToProject', {projectId: this.item.project.id, messageId: this.item.id})
    }
  }
}
</script>

<template>
  <Box padding="300" class="hover-message" @click="open">
    <InlineStack gap="400" blockAlign="start" :wrap="false">
      <AvatarFrame rounded size="lg" :user="item.user" />

      <BlockStack style="flex: 1">
        <Text as="p" variant="headingMd">
          {{ item?.user.first_name }}
          {{ item?.user.last_name?.substring(0, 1) }}.
        </Text>

        <Text as="p" variant="bodyMd" tone="subdued">
          {{ item.project?.name }}
        </Text>

        <div style="overflow: hidden; max-height: 20px; max-width: 185px; text-overflow: ellipsis;">
          <InlineStack gap="100">
            <Text as="p" variant="bodyMd" v-if="item.type !== 'text'">Attachment:</Text>

            <Text as="p" variant="bodyMd" v-html="item.content" />
          </InlineStack>
        </div>

        <InlineStack align="end" gap="200" blockAlign="center">
          <div v-if="!item.seen" class="not-seen" />

          <Text as="p" variant="bodyMd" tone="subdued" alignment="end">
            {{ dateFormat(item.created_at) }}
          </Text>
        </InlineStack>
      </BlockStack>
    </InlineStack>
  </Box>
</template>

<style scoped>
.not-seen {
  background: #0094d5;
  width: 12px;
  height: 12px;
  border-radius: 100%;
  font-size: 8px;
  text-align: center;
  line-height: 8px;
}

.hover-message {
  border-radius: 12px;
  background: #ffffff;
  cursor: pointer;
}

.hover-message:hover {
  background: #f9f9f9;
}

.hover-message:active {
  background: #f2f2f2;
}
</style>