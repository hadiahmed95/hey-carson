<script>
import XIcon from "@/components/icons/XIcon.vue";
import MobileModal from "@/components/MobileModal.vue";

export default {
  name: "CompleteModal",
  components: {MobileModal},

  data() {
    return {
      XIcon,
      isMobile: screen.width <= 760,
    }
  }
}
</script>

<template>
  <template v-if="isMobile">
    <MobileModal>
      <template #heading>
        <BlockStack gap="100">
          <InlineStack align="space-between" blockAlign="center">
            <Text variant="bodyLg" fontWeight="bold" as="p">
              Remove team member
            </Text>

            <div>
              <Icon :source="XIcon"  @click="() => this.$emit('close')"/>
            </div>
          </InlineStack>
        </BlockStack>
      </template>

      <Box style="padding: 16px">
        <BlockStack gap="200">
          <Text variant="bodyMd" as="p" tone="subdued">
            Are you sure you want to remove this team member from your team?
          </Text>

          <Divider />

          <BlockStack gap="100">
            <Text variant="headingMd" as="p">
              *Team User Name*
            </Text>
            <Text variant="bodyMd" as="p" tone="subdued">
              *Team User Email*
            </Text>
          </BlockStack>
        </BlockStack>
      </Box>

      <template #footer>
        <InlineStack align="end" gap="200">
          <Button @click="() => this.$emit('close')">Cancel</Button>
          <Button variant="primary" tone="critical" @click="() => this.$emit('close')">Remove</Button>
        </InlineStack>
      </template>
    </MobileModal>
  </template>

  <template v-else>
    <div style="position: fixed; overflow-y: hidden; top: 0; left: 0; width: 100%; height: 100%; z-index: 1000; background: #00000033"
         @click="() => this.$emit('close')">
      <BlockStack inlineAlign="center" align="center" style="height: 100%">
        <Card style="width: 380px;" :padding="null" @click.stop="null">
          <Box background="bg-surface-secondary"
               borderBlockStartWidth="0"
               borderBlockEndWidth="025"
               borderInlineStartWidth="0"
               borderInlineEndWidth="0"
               borderColor="border"
               paddingBlock="300"
               paddingInline="400">
            <InlineStack align="space-between">
              <Text variant="bodyLg" fontWeight="bold" as="p">
                Remove team member
              </Text>

              <div>
                <Icon :source="XIcon"  @click="() => this.$emit('close')"/>
              </div>
            </InlineStack>
          </Box>
          <Box
              borderBlockStartWidth="0"
              borderBlockEndWidth="025"
              borderInlineStartWidth="0"
              borderInlineEndWidth="0"
              borderColor="border"
              padding="400">
            <BlockStack gap="200">
              <Text variant="bodyMd" as="p" tone="subdued">
                Are you sure you want to remove this team member from your team?
              </Text>

              <Divider />

              <BlockStack gap="100">
                <Text variant="headingMd" as="p">
                  *Team User Name*
                </Text>
                <Text variant="bodyMd" as="p" tone="subdued">
                  *Team User Email*
                </Text>
              </BlockStack>
            </BlockStack>
          </Box>

          <Box padding="400">
            <InlineStack align="end" gap="200">
              <Button @click="() => this.$emit('close')">Cancel</Button>
              <Button variant="primary" tone="critical" @click="() => this.$emit('close')">Remove</Button>
            </InlineStack>
          </Box>
        </Card>
      </BlockStack>
    </div>
  </template>
</template>

<style scoped>

</style>