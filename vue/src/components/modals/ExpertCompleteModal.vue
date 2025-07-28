<script>
import CheckCircle from "@/components/icons/CheckCircle.vue";
import MobileModal from "@/components/MobileModal.vue";
import XIcon from "@/components/icons/XIcon.vue";
import InputBtn from "@/components/misc/InputBtn.vue";

export default {
  name: "CompleteModal",
  components: {InputBtn, MobileModal},

  data() {
    return {
      CheckCircle,
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
        <InlineStack align="space-between" blockAlign="start" :wrap="false">
          <Text variant="bodyLg" fontWeight="bold" as="p">
            Submit Project for Review
          </Text>

          <div>
            <Icon :source="XIcon"  @click="() => this.$emit('close')"/>
          </div>
        </InlineStack>
      </template>

      <Box style="padding: 16px">
        <Text variant="bodyMd" as="p" tone="subdued">
          After the client receives a notification, they will be able to confirm that everything has been completed properly. Once the client confirms, payments will be released to you.
        </Text>
      </Box>

      <template #footer>
        <InlineStack align="end" gap="200">
          <Button @click="() => this.$emit('close')">Cancel</Button>

          <InputBtn :icon="CheckCircle"
                    @click="() => this.$emit('complete')">Submit for Review</InputBtn>
        </InlineStack>
      </template>
    </MobileModal>
  </template>

  <template v-else>
    <div style="position: fixed; overflow-y: hidden; top: 0; left: 0; width: 100%; height: 100%; z-index: 1000; background: #00000033"
       @click="() => this.$emit('close')">
    <BlockStack inlineAlign="center" align="center" style="height: 100%">
      <Card style="width: 620px;" :padding="null" @click.stop="null">
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
              Submit Project for Review
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
              After the client receives a notification, they will be able to confirm that everything has been completed properly. Once the client confirms, payments will be released to you.
            </Text>
          </BlockStack>
        </Box>

        <Box padding="400">
          <InlineStack align="end" gap="200">
            <Button @click="() => this.$emit('close')">Cancel</Button>

            <InputBtn :icon="CheckCircle" @click="() => this.$emit('complete')">Submit for Review</InputBtn>
          </InlineStack>
        </Box>
      </Card>
    </BlockStack>
  </div>
  </template>
</template>

<style scoped>

</style>