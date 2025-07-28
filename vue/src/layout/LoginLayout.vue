<script>
import LongLogo from "@/components/logo/LongLogo.vue";

export default {
  name: "LoginLayout",
  components: {LongLogo},

  data() {
    return {
      isMobile: screen.width <= 760,
    }
  }
}
</script>

<template>
  <BlockStack inlineAlign="center" align="center" style="min-height: 100vh; background:  linear-gradient(#DCF1FD, #FFFFFF);">
    <BlockStack inlineAlign="center" align="center" style="width: 100%; padding: 0 16px">
      <div class="boxBorder" :style="isMobile ? 'width: 100%' : ''">
      <Box background="bg-surface" border-radius="300" :class="isMobile ? 'mobile-view' : 'web-view'">
        <BlockStack gap="800">
          <LongLogo/>

          <slot></slot>
        </BlockStack>
      </Box>
      </div>
    </BlockStack>
  </BlockStack>
</template>

<style scoped>
.boxBorder {
  display: block;
  box-sizing: border-box;
  position: relative;
  border-radius: 12px;
  border-color: rgb(48, 48, 48);
  box-shadow: rgba(26, 26, 26, 0.07) 0 1px 0 0;
}

.boxBorder::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border-radius: 12px;
  box-sizing: border-box;
  scrollbar-color: rgb(138, 138, 138) rgba(0, 0, 0, 0);
  z-index: 32;
  pointer-events: none;
  mix-blend-mode: luminosity;
  box-shadow: rgba(0, 0, 0, 0.13) 1px 0 0 0 inset, rgba(0, 0, 0, 0.13) -1px 0 0 0 inset, rgba(0, 0, 0, 0.17) 0 -1px 0 0 inset, rgba(204, 204, 204, 0.5) 0 1px 0 0 inset;
}

.web-view {
  padding: 40px;
  width: 470px;
}

.mobile-view {
  padding: 24px;
  width: 100%
}
</style>