<script>
export default {
  name: "MobileCard",

  props: {
    padding: {
      default: '400',
      type: String,
    },
    background: {
      default: 'bg-surface',
      type: String,
    },
    canHover: {
      default: false,
      type: Boolean,
    }
  }
}
</script>

<template>
  <div class="boxBorder">
    <Box :background="background"
         :class="canHover ? 'hover-card' : ''"
         border-radius="300"
         :padding="padding">
      <slot></slot>
    </Box>
  </div>
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
  z-index: 0;
  pointer-events: none;
  mix-blend-mode: luminosity;
  box-shadow: rgba(0, 0, 0, 0.13) 1px 0 0 0 inset, rgba(0, 0, 0, 0.13) -1px 0 0 0 inset, rgba(0, 0, 0, 0.17) 0 -1px 0 0 inset, rgba(204, 204, 204, 0.5) 0 1px 0 0 inset;
}
.hover-card {
  cursor: pointer;
}
.hover-card:hover {
  background: rgb(247, 247, 247) !important;
}
</style>