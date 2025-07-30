<template>
  <button
      class="icon-button"
      :style="{ backgroundColor: bgColor, color: textColor }"
  >
    <component
        v-if="icon && iconPosition === 'left'"
        :is="icon"
        class="icon"
    />
    <span class="label">
      <slot />
    </span>
    <component
        v-if="icon && iconPosition === 'right'"
        :is="icon"
        class="icon"
    />
  </button>
</template>

<script>
export default {
  name: 'IconButton',
  props: {
    icon: {
      type: [Object, Function],
      default: null
    },
    iconPosition: {
      type: String,
      default: 'right', // 'left' or 'right'
      validator: value => ['left', 'right'].includes(value)
    },
    bgColor: {
      type: String,
      default: '#1c1c1c'
    },
    textColor: {
      type: String,
      default: '#ffffff'
    }
  }
}
</script>

<style scoped>
.icon-button {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 4px;
  border: none;
  font-weight: 600;
  font-size: 15px;
  cursor: pointer;
}

.icon {
  font-size: 18px;
}
</style>
