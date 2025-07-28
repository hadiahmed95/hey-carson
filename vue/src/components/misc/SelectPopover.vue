<script>
export default {
  name: "SelectPopover",

  props: {
    active: {
      default: null,
    },

    items: {
      type: Array,
      default: () => []
    },

    name: {
      type: Text,
      default: ''
    }
  },

  computed: {
    popoverItems() {
      let popoverItems = [];
      this.items.forEach(item => {
        popoverItems.push({
          content: item.name,
          role: item.name,
          onAction: () => this.selectItem(item),
          active: this.active && item.name === this.active.name
        })
      });

      return popoverItems
    }
  },

  data() {
    return {
      popover: false,
    }
  },

  methods: {
    togglePopover() {
      this.popover = !this.popover
    },

    selectItem(item) {
      this.popover = false;
      this.$emit('selected', item);
    },
  }
}
</script>

<template>
  <Popover
      :active="popover"
      autofocusTarget="first-node"
      @close="togglePopover"
  >
    <template #activator>
      <Button @click="togglePopover"
              :disclosure="popover ? 'up' : 'down'"
              :class="active ? 'active-selector' : 'inactive-selector'" variant="tertiary">
        {{ name }}
      </Button>
    </template>
    <ActionList
        actionRole="menuitem"
        :items="popoverItems"
    />
  </Popover>
</template>

<style scoped>
.inactive-selector {
  border: dashed #E3E3E3 1px; background: #FFFFFF
}

.inactive-selector:hover {
  background: #F8F8F8
}

.active-selector {
  border: solid #E3E3E3 1px; background: #E3E3E3
}

.active-selector:hover {
  background: #F8F8F8
}
</style>