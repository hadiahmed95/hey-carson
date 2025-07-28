<script>
import XIcon from "@/components/icons/XIcon.vue";
import InputBtn from "@/components/misc/InputBtn.vue";
export default {
  name: "ProjectIdeasInfoModal",
  components: {InputBtn},

  data() {
    return {
      XIcon
    }
  }
}
</script>

<template>
  <div style="position: fixed; overflow-y: hidden; top: 0; left: 0; width: 100%; z-index: 1000; background: #00000033;"
       @click="() => this.$emit('close')">
    <BlockStack inlineAlign="end">
      <Box background="bg-surface" border-radius="300" @click.stop="null">
        <BlockStack gap="800" align="start" style="min-height: 100vh; width: 550px; padding: 40px">
          <InlineStack align="space-between">
            <BlockStack gap="300">
              <Text variant="headingXl" as="p" alignment="start">
                *Project Idea Title*
              </Text>
              <Text variant="bodyLg" as="p" alignment="start" style="color: #005BD3">
                Development
              </Text>
            </BlockStack>

            <div>
              <Icon :source="XIcon" @click="() => this.$emit('close')"/>
            </div>
          </InlineStack>

          <Text as="p" variant="bodyMd">
            Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
          </Text>

          <Divider />

          <InlineStack align="space-between" blockAlign="center">
            <InlineStack gap="200">

              <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

              <BlockStack gap="050">
                <Text as="p" variant="headingSm">
                  *Expert Full Name*
                </Text>

                <Text as="p" variant="bodyMd">
                  4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                </Text>
              </BlockStack>
            </InlineStack>

            <BlockStack gap="100">
              <Text as="p" variant="bodySm" tone="subdued">Price</Text>

              <Text as="p" variant="headingMd" alignment="end">$300.00</Text>
            </BlockStack>
          </InlineStack>

          <InputBtn>Hire this Expert for this Project</InputBtn>
        </BlockStack>
      </Box>
    </BlockStack>
  </div>
</template>

<style scoped>

</style>