<script>
import CheckCircle from "@/components/icons/CheckCircle.vue";
import FilterIcon from "@/components/icons/FilterIcon.vue";
import LoadingCards from "@/components/cards/LoadingCards.vue";

export default {
  name: "IconNotifier",

  props: {
    isLoading: {
      type: Boolean,
      default: false
    },

    icon: {
      default: null
    },

    newCount: {
      type: Number,
      default: 0,
    },

    totalCount: {
      type: Number,
      default: 0
    },

    windowText: {
      type: String,
      default: 'Latest Messages'
    },

    emptyText: {
      type: String,
      default: 'Your conversations with experts will show here.'
    },
  },

  components: {
    LoadingCards,
  },

  data() {
    return {
      CheckCircle,
      FilterIcon,

      notifyPopover: false,
      seenActive: false,
      filterActive: false,
      filterType: 'New', //New or All
    }
  },

  methods: {
    toggleNotifyPopover() {
      this.notifyPopover = !this.notifyPopover;
    },

    markAsSeen() {
      this.$emit('markSeen')
    },

    filterItems() {
      this.filterType = this.filterType === 'All' ? 'New' : 'All'
      this.$emit('filterItems', this.filterType)
    }
  }
}
</script>

<template>
  <Box class="nav-btn" minWidth="32" minHeight="32"  borderRadius="200">
    <Popover
        :active="notifyPopover"
        autofocusTarget="first-node"
        @close="toggleNotifyPopover"
        preferredAlignment="right"
    >
      <template #activator>
        <Box padding="150" style="position: relative" @click="toggleNotifyPopover">
          <Icon :source="icon"/>

          <div class="small-badge" v-if="newCount > 0">
            <div>{{ newCount }}</div>
          </div>
        </Box>
      </template>

      <Card style="width: 400px;" padding="400">
        <LoadingCards v-if="isLoading"/>

        <BlockStack gap="400" v-else>
          <InlineStack align="space-between">
            <Text variant="bodyLg" as="p" fontWeight="semibold" alignment="start">
              {{ windowText }}
            </Text>

            <InlineStack gap="400">
              <Tooltip @mouseenter="filterActive = true"
                       @mouseleave="filterActive = false"
                       :content="'Filtering by: ' + filterType">
                <Icon :source="FilterIcon" :tone="filterActive ? 'primary' : 'subdued'"
                      style="cursor: pointer" @click="filterItems"/>
              </Tooltip>


              <Tooltip @mouseenter="seenActive = true"
                       @mouseleave="seenActive = false"
                       content="Mark all as Seen">
                <Icon :source="CheckCircle" :tone="seenActive ? 'primary' : 'subdued'"
                      style="cursor: pointer"
                      @click="markAsSeen"/>
              </Tooltip>
            </InlineStack>
          </InlineStack>

          <Box background="bg-fill-secondary" padding="400" v-if="!totalCount">
            <Text as="p" tone="subdued">
              {{ emptyText }}
            </Text>
          </Box>

          <BlockStack gap="100" @click="toggleNotifyPopover" v-else>
            <slot></slot>
          </BlockStack>
        </BlockStack>
      </Card>
    </Popover>
  </Box>
</template>

<style scoped>
.small-badge {
  background: #EF4D2F;
  color: #FFFFFF;
  width: 12px;
  height: 12px;
  border-radius: 100%;
  font-size: 8px;
  text-align: center;
  line-height: 8px;
  position: absolute;
  top: 3px;
  right: 3px;
  padding: 2px 3px;
}
</style>