<script>
import MobileCard from "@/components/MobileCard.vue";
export default {
  name: "LoadingCards",

  components: {
    MobileCard
  }
}
</script>

<template>
  <BlockStack gap="300">
    <MobileCard>
      <BlockStack gap="400">
        <SkeletonDisplayText size="small" />

        <SkeletonBodyText :lines="4" />

        <Divider />

        <SkeletonBodyText :lines="1" />
      </BlockStack>
    </MobileCard>

    <MobileCard>
      <BlockStack gap="400">
        <SkeletonDisplayText size="small" />

        <SkeletonBodyText :lines="4" />

        <Divider />

        <SkeletonBodyText :lines="1" />
      </BlockStack>
    </MobileCard>

    <MobileCard>
      <BlockStack gap="400">
        <SkeletonDisplayText size="small" />

        <SkeletonBodyText :lines="4" />

        <Divider />

        <SkeletonBodyText :lines="1" />
      </BlockStack>
    </MobileCard>

    <MobileCard>
      <BlockStack gap="400">
        <SkeletonDisplayText size="small" />

        <SkeletonBodyText :lines="4" />

        <Divider />

        <SkeletonBodyText :lines="1" />
      </BlockStack>
    </MobileCard>
  </BlockStack>
</template>

<style scoped>

</style>