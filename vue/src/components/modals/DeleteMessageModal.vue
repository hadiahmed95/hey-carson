<script>
import XIcon from "@/components/icons/XIcon.vue";
import MobileModal from "@/components/MobileModal.vue";
import InputBtn from "@/components/misc/InputBtn.vue";

export default {
  name: "DeleteMessageModal",
  components: { MobileModal, InputBtn },

  props: {
    messageId: {
      default: null,
      type: Number
    }
  },

  data() {
    return {
      XIcon,
      isMobile: screen.width <= 760,
    }
  },

  methods: {
    close() {
      this.$emit('close')
      this.showDeleteModal = false;
    },

    deleteMessage() {
      this.$emit('deleteMessage', this.messageId);
      this.$emit('close');
    }
  }
}
</script>

<template>
  <MobileModal :mobile="isMobile">
    <template #heading>
      <BlockStack gap="100">
        <InlineStack align="space-between">
          <Text variant="bodyLg" fontWeight="bold" as="p">
            Delete message
          </Text>
          <div>
            <Icon :source="XIcon"  @click="close"/>
          </div>
        </InlineStack>
      </BlockStack>
    </template>

    <Box padding="400">
      <BlockStack align="center" inlineAlign="center">
        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56" fill="none">
          <path d="M28.0004 16.7999C29.1602 16.7999 30.1004 17.7401 30.1004 18.8999L30.1002 28.6999C30.1002 29.8597 29.16 30.7999 28.0002 30.7999C26.8404 30.7999 25.9002 29.8597 25.9002 28.6999L25.9004 18.8999C25.9004 17.7401 26.8406 16.7999 28.0004 16.7999Z" fill="#FF7474"/>
          <path d="M30.8004 36.3999C30.8004 37.9463 29.5468 39.1999 28.0004 39.1999C26.454 39.1999 25.2004 37.9463 25.2004 36.3999C25.2004 34.8535 26.454 33.5999 28.0004 33.5999C29.5468 33.5999 30.8004 34.8535 30.8004 36.3999Z" fill="#FF7474"/>
          <path fill-rule="evenodd" clip-rule="evenodd" d="M47.6004 27.9999C47.6004 38.8247 38.8252 47.5999 28.0004 47.5999C17.1756 47.5999 8.40039 38.8247 8.40039 27.9999C8.40039 17.1751 17.1756 8.3999 28.0004 8.3999C38.8252 8.3999 47.6004 17.1751 47.6004 27.9999ZM43.4004 27.9999C43.4004 36.5051 36.5056 43.3999 28.0004 43.3999C19.4952 43.3999 12.6004 36.5051 12.6004 27.9999C12.6004 19.4947 19.4952 12.5999 28.0004 12.5999C36.5056 12.5999 43.4004 19.4947 43.4004 27.9999Z" fill="#FF7474"/>
        </svg>

        <Text alignment="center">
          Are you sure you want to delete this message? This cannot be undone.
        </Text>
      </BlockStack>
    </Box>
    <Card>
      <InputBtn @click="deleteMessage">Delete</InputBtn>
    </Card>
  </MobileModal>
</template>

<style scoped>

</style>