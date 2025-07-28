<script>
import XIcon from "@/components/icons/XIcon.vue";
import MobileModal from "@/components/MobileModal.vue";
import InputBtn from "@/components/misc/InputBtn.vue";

export default {
  name: "AddTeamModal",

  components: {InputBtn, MobileModal},

  data() {
    return {
      XIcon,
      isMobile: screen.width <= 760,

      teamForm: {
        email1: '',
        email2: '',
        email3: ''
      },
    }
  },
}
</script>

<template>
  <template v-if="isMobile">
    <MobileModal>
      <template #heading>
        <BlockStack gap="100">
          <InlineStack align="space-between" blockAlign="center">
            <Text variant="headingMd" fontWeight="bold" as="h2">
              Invite team member
            </Text>

            <div>
              <Icon :source="XIcon" @click="() => this.$emit('close')"/>
            </div>
          </InlineStack>

          <Text variant="bodySm" as="p" alignment="start" tone="subdued">
            Invite team members to join your projects for improved communication and increased productivity.
          </Text>
        </BlockStack>
      </template>

      <Box style="padding: 16px">
        <BlockStack gap="400">
          <FormLayout>
            <TextField
                label="Email #1"
                autoComplete="off"
                v-model="teamForm.email1"
                placeholder="Enter team member email "
                :error="null" />


            <TextField
                label="Email #2"
                autoComplete="off"
                v-model="teamForm.email2"
                placeholder="Enter team member email "
                :error="null" />


            <TextField
                label="Email #3"
                autoComplete="off"
                v-model="teamForm.email3"
                placeholder="Enter team member email "
                :error="null" />
          </FormLayout>
        </BlockStack>
      </Box>

      <template #footer>
        <InlineStack align="end">
          <InputBtn>Send Invite</InputBtn>
        </InlineStack>
      </template>
    </MobileModal>
  </template>

  <template v-else>
    <div style="position: fixed; overflow-y: hidden; top: 0; left: 0; width: 100%; z-index: 1000; background: #00000033;"
         @click="() => this.$emit('close')">
      <BlockStack inlineAlign="end">
        <Box background="bg-surface" border-radius="300" @click.stop="null">
          <BlockStack gap="800" align="start" style="min-height: 100vh; width: 550px; padding: 40px">
            <BlockStack gap="300">
              <BlockStack gap="100">
                <InlineStack align="space-between" blockAlign="center">
                  <Text variant="headingXl" fontWeight="bold" as="h2">
                    Invite team member
                  </Text>

                  <div>
                    <Icon :source="XIcon" @click="() => this.$emit('close')"/>
                  </div>
                </InlineStack>

                <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
                  Invite team members to join your projects for improved communication and increased productivity.
                </Text>
              </BlockStack>
            </BlockStack>

            <BlockStack gap="800">
              <FormLayout>
                <TextField
                    label="Email #1"
                    autoComplete="off"
                    v-model="teamForm.email1"
                    placeholder="Enter team member email "
                    :error="null" />


                <TextField
                    label="Email #2"
                    autoComplete="off"
                    v-model="teamForm.email2"
                    placeholder="Enter team member email "
                    :error="null" />


                <TextField
                    label="Email #3"
                    autoComplete="off"
                    v-model="teamForm.email3"
                    placeholder="Enter team member email "
                    :error="null" />
              </FormLayout>
            </BlockStack>

            <InputBtn>Send Invite</InputBtn>
          </BlockStack>
        </Box>
      </BlockStack>
    </div>

  </template>
</template>

<style scoped>

</style>