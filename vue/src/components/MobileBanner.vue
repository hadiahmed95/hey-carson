<script>
import XIcon from "@/components/icons/XIcon.vue";
import AlertCircleIcon from "@/components/icons/AlertCircleIcon.vue";
import CheckIcon from "@/components/icons/CheckIcon.vue";
export default {
  name: "MobileCard",
  props: {
    info: {
      default: false,
      type: Boolean
    }
  },

  data() {
    return {
      XIcon,
      AlertCircleIcon,
      CheckIcon
    }
  }
}
</script>

<template>
  <Box border-color="border" border-width="025"
       background="bg-surface" border-radius="300"
  >
    <Box borderStartStartRadius="300" borderStartEndRadius="300"
        :background="info ? 'bg-fill-info' : 'bg-fill-success'" :color="info ? '' : 'text-inverse'" padding="300">
      <InlineStack align="space-between" :wrap="false">
        <InlineStack blockAlign="start" gap="100" :wrap="false">
          <div>
            <Icon :source="info ? AlertCircleIcon : CheckIcon"/>
          </div>

          <Text as="p" variant="bodyMd" fontWeight="bold">
            <slot name="title"></slot>
          </Text>
        </InlineStack>
        <div>
          <Icon :source="XIcon" />
        </div>
      </InlineStack>
    </Box>
    <Box padding="300">
      <slot></slot>
    </Box>
  </Box>
</template>

<style scoped>

</style>