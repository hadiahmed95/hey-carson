<script>
import MobileModal from "@/components/MobileModal.vue";
import XIcon from "@/components/icons/XIcon.vue";
import InputBtn from "@/components/misc/InputBtn.vue";

export default {
  name: "ReportModal",
  components: {InputBtn, MobileModal},

  data() {
    return {

      XIcon,

      problemText: '',
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
          <InlineStack align="space-between">
            <Text variant="bodyLg" fontWeight="bold" as="p">
              Report a Problem
            </Text>

            <div>
              <Icon :source="XIcon"  @click="() => this.$emit('close')"/>
            </div>
          </InlineStack>
        </BlockStack>
      </template>

      <Box style="padding: 16px">
        <BlockStack gap="300">
          <Text variant="bodyMd" as="p" tone="subdued">
            If you experience any problems during your project or if something is unclear, please feel free to reach out, and we will get back to you as soon as possible.
          </Text>

          <TextField
              label="Write your message "
              autoComplete="off"
              v-model="problemText"
              :multiline="5"
              placeholder="Please try to be as detailed as possible ... "
              :error="null" />
        </BlockStack>
      </Box>

      <template #footer>
        <InlineStack align="end" gap="200">
          <Button @click="() => this.$emit('close')">Cancel</Button>

          <InputBtn @click="() => this.$emit('close')">Send Message</InputBtn>
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
                Report a Problem
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
            <BlockStack gap="400">
              <Text variant="bodyMd" as="p" tone="subdued">
                If you experience any problems during your project or if something is unclear, please feel free to reach out, and we will get back to you as soon as possible.
              </Text>

              <TextField
                  label="Write your message "
                  autoComplete="off"
                  v-model="problemText"
                  :multiline="5"
                  placeholder="Please try to be as detailed as possible ... "
                  :error="null" />
            </BlockStack>
          </Box>

          <Box padding="400">
            <InlineStack align="end" gap="200">
              <Button @click="() => this.$emit('close')">Cancel</Button>

              <InputBtn @click="() => this.$emit('close')">Send Message</InputBtn>
            </InlineStack>
          </Box>
        </Card>
      </BlockStack>
    </div>
  </template>
</template>

<style scoped>

</style>