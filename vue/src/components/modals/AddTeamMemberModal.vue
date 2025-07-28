<script>

import MobileModal from "@/components/MobileModal.vue";
import XIcon from "@/components/icons/XIcon.vue";
import InputBtn from "@/components/misc/InputBtn.vue";

export default {
  name: "AddTeamMemberModal",
  components: {InputBtn, MobileModal},

  data() {
    return {
      XIcon,

      isMobile: screen.width <= 760,

      items: [
        {
          name: "*Team Member 1*"
        },
        {
          name: "*Team Member 2*"
        },
        {
          name: "*Team Member 3*"
        }
      ]
    }
  }
}
</script>

<template>

  <template v-if="isMobile">
    <MobileModal>
      <template #heading>
        <BlockStack gap="100">
          <InlineStack align="space-between" blockAlign="start" :wrap="false">
            <Text variant="headingMd" fontWeight="bold" as="h2">
              Select the team member you wish to add to this project:
            </Text>

            <div>
              <Icon :source="XIcon" @click="() => this.$emit('close')"/>
            </div>
          </InlineStack>
        </BlockStack>
      </template>

      <Box style="padding: 16px">
        <Text variant="bodyMd" as="p">
          Select the team member you wish to add to this project:
        </Text>
      </Box>

      <Divider />

      <BlockStack gap="400">
        <ResourceList
            :items="items"
            :showHeader="false"
        >
          <ResourceItem
              v-for="{id, url, name} in items"
              :key="id"
              :id="id"
              :url="url"
              :accessibilityLabel="`View details for ${name}`"
          >
            <template #media>
              <Avatar style="border-radius: 100%" customer name="Test" size="lg" />
            </template>
            <Text variant="bodyMd" fontWeight="bold" as="h3" style="padding: 8px 0">
              {{ name }}
            </Text>
          </ResourceItem>
        </ResourceList>
      </BlockStack>

      <template #footer>
        <InlineStack align="end" gap="200">
          <Button @click="() => this.$emit('close')">Cancel</Button>

          <InputBtn @click="() => this.$emit('close')">Add Team Member</InputBtn>
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
                Select the team member you wish to add to this project:
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
              <Text variant="bodyMd" as="p">
                Select the team member you wish to add to this project:
              </Text>

              <Divider />

              <BlockStack gap="600">
                <ResourceList
                    :items="items"
                    :showHeader="false"
                >
                  <ResourceItem
                      v-for="{id, url, name} in items"
                      :key="id"
                      :id="id"
                      :url="url"
                      :accessibilityLabel="`View details for ${name}`"
                  >
                    <template #media>
                      <Avatar style="border-radius: 100%" customer name="Test" size="lg" />
                    </template>
                    <Text variant="bodyMd" fontWeight="bold" as="h3" style="padding: 8px 0">
                      {{ name }}
                    </Text>
                  </ResourceItem>
                </ResourceList>
              </BlockStack>

            </BlockStack>
          </Box>

          <Box padding="400">
            <InlineStack align="end" gap="200">
              <Button @click="() => this.$emit('close')">Cancel</Button>

              <InputBtn @click="() => this.$emit('close')">Add Team Member</InputBtn>
            </InlineStack>
          </Box>
        </Card>
      </BlockStack>
    </div>
  </template>
</template>

<style scoped>

</style>