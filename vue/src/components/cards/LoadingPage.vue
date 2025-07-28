<script>
import MobileCard from "@/components/MobileCard.vue";
export default {
  name: "LoadingCards",

  components: {
    MobileCard,
  },

  data() {
    return {
      isMobile: screen.width <= 760,
    }
  }
}
</script>

<template>
  <Page :style="isMobile ? {padding: '32px 0'} : {padding: '56px 0'}" >

    <div style="padding: 24px 0">
      <SkeletonDisplayText />
    </div>

    <MobileCard>
      <BlockStack gap="400">
        <SkeletonDisplayText size="small" />

        <Divider />

        <BlockStack gap="600">
          <SkeletonBodyText :lines="4" />
          <SkeletonBodyText :lines="2" />
          <SkeletonBodyText :lines="4" />
          <SkeletonBodyText :lines="2" />
        </BlockStack>

        <Divider />

        <SkeletonBodyText :lines="1" />
      </BlockStack>
    </MobileCard>
  </Page>
</template>

<style scoped>

</style>