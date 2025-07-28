<script>
export default {
  name: "TagBtn",

  props: {
    name: {
      type: String,
      default: '',
    },
    item: {
      type: Object,
      default: null
    }
  }
}
</script>

<template>
  <Button @click="() => this.$emit('remove')"
          class="btn-tag" variant="tertiary">
<!--    {{ name }}{{ ': ' + item.name }}-->
    {{ item.name }}
  </Button>
</template>

<style scoped>
.btn-tag {
  border: dashed #E3E3E3 1px; background: #FFFFFF
}

.btn-tag:hover {
  background: #F8F8F8
}
</style>