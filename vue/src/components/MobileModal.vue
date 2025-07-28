<script>
export default {
  name: "MobileModal",
  props: {
    mobile: {
      default: true,
      type: Boolean
    }
  }
}
</script>

<template>
  <div style="position: fixed; overflow-y: hidden; top: 0; left: 0; width: 100%; height: 100vh; z-index: 100; background: #00000033;"
       @click="() => this.$emit('close')">
    <Box background="bg-surface" border-radius="400"
         :style="mobile ? 'margin: 50% 16px;' : 'margin: auto; margin-top: 10%; width: 450px;'" @click.stop="null">
      <Box borderStartStartRadius="400" borderStartEndRadius="400"
           borderBlockEndWidth="025" borderColor="border"
           background="bg-surface-secondary"
           :style="mobile ? 'padding: 16px' : 'padding: 12px 16px'">
        <slot name="heading" />
      </Box>

      <slot />

      <Box borderBlockStartWidth="025"
           borderColor="border"
           :style="mobile ? 'padding: 16px' : 'padding: 12px 16px'"
           v-if="$slots.footer">
        <slot name="footer" />
      </Box>
    </Box>
  </div>
</template>

<style scoped>

</style>