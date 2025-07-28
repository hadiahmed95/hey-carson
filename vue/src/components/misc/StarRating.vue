<template>
  <BlockStack gap="200" align="start">
    <Text variant="bodyMd" as="p" tone="bold">{{ label }}</Text>
    <InlineStack gap="200" align="start" blockAlign="center">
      <InlineStack style="cursor:pointer">
        <div v-for="starIndex in 5" :key="starIndex"
             @click="() => updateRating(starIndex)"
             style="width: 40px; height: 40px">
          <StarFullIcon v-if="rating >= starIndex" />
          <StarEmptyIcon v-else />
        </div>
      </InlineStack>
      <Text variant="bodyMd" as="p" tone="bold">{{ rating.toFixed(1) }}</Text>
    </InlineStack>
  </BlockStack>
</template>

<script>
import StarFullIcon from "@/components/icons/StarFullIcon.vue";
import StarEmptyIcon from "@/components/icons/StarEmptyIcon.vue";

export default {
  name: "StarRating",
  props: ['label', 'rating'],
  components: {
    StarFullIcon,
    StarEmptyIcon
  },
  methods: {
    updateRating(value) {
      this.$emit('update:rating', value)
    }
  }
}
</script>