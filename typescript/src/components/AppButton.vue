<script setup lang="ts">
import type { IButton } from './../types'
import { computed } from 'vue'

const props = withDefaults(defineProps<IButton>(), {
  iconPosition: 'right',
  variant: 'primary',
  size: 'md',
  rounded: 'md',
  type: 'button',
  fullWidth: false
})

const sizeClass = computed(() => {
  return {
    sm: 'text-h5 px-2 py-1',
    md: 'text-h4 px-4 py-2',
    lg: 'text-paragraph px-6 py-3',
    icon: 'p-2'
  }[props.size]
})

const roundedClass = computed(() => {
  return {
    sm: 'rounded-sm',
    md: 'rounded-md',
    full: 'rounded-full'
  }[props.rounded]
})

const variantClass = computed(() => {
  return {
    primary: 'bg-primary text-white hover:bg-opacity-90',
    secondary: 'bg-white text-gray-900 hover:bg-gray-100',
    outline: 'border-2 border-primary text-primary hover:bg-primary/10',
    ghost: 'text-muted hover:text-primary',
    icon: 'hover:bg-muted'
  }[props.variant]
})
</script>

<template>
  <button
      :type="type"
      :aria-label="ariaLabel"
      :class="[
      'inline-flex items-center justify-center gap-2 transition-colors font-medium h-fit',
      sizeClass,
      variantClass,
      roundedClass,
      fullWidth ? 'w-full' : 'w-fit'
    ]"
      @click="$emit('clicked')"
  >
    <template v-if="icon && iconPosition === 'left'">
      <component :is="icon" class="w-4 h-4" />
    </template>

    <span v-if="title" class="truncate">{{ title }}</span>

    <template v-if="icon && iconPosition === 'right'">
      <component :is="icon" class="w-4 h-4" />
    </template>
  </button>
</template>
