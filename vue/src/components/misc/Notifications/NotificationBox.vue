<script>
import moment from "moment";
import AvatarFrame from "@/components/misc/AvatarFrame.vue";
import NoteIcon from "@/components/icons/NoteIcon.vue";
import PlusCircleIcon from "@/components/icons/PlusCircleIcon.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";

export default {
  name: "NotificationBox",
  components: {AvatarFrame},

  props: {
    item: {
      type: Object,
      default: () => {}
    },

    userType: {
      type: String,
      default: 'Client'
    }
  },

  data() {
    return {
      NoteIcon,
      PlusCircleIcon,
      CheckCircle,
    }
  },

  methods: {
    dateFormat(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").fromNow()
    },

    open() {
      this.$emit('goToProject', {projectId: this.item.project.id, eventId: this.item.id, isAvailable: this.item.project.status === "available"})
    },

    checkEventOne() {
      return this.item.event.type === 'client-project-payment' ||
          this.item.event.type === 'expert-project-completed'
    },

    checkEventTwo() {
      return this.item.event.type === 'client-project-complete' ||
          this.item.event.type === 'expert-project-finished'
    },

    offerEvent() {
      return this.item.event.type === 'client-project-offer' || this.item.event.type === 'expert-project-payment-offer'
    },

    scopeEvent() {
      return this.item.event.type === 'client-project-scope' || this.item.event.type === 'expert-project-payment-scope'
    }
  }
}
</script>

<template>
  <Box padding="300" class="hover-message" @click="open">
    <InlineStack gap="400" blockAlign="start" >
      <template v-if="checkEventOne()">
        <div style="min-width:40px; min-height:40px; padding: 10px; border-radius: 100%; background: #b4fed2">
          <Icon :source="CheckCircle" />
        </div>
      </template>
      <template v-else-if="checkEventTwo()">
        <div style="min-width:40px; min-height:40px; padding: 10px; border-radius: 100%; background: #d5ebff">
          <Icon :source="CheckCircle" />
        </div>
      </template>
      <template v-else-if="offerEvent()">
        <div style="min-width:40px; min-height:40px; padding: 10px; border-radius: 100%; background: #EAF4FF">
          <Icon :source="NoteIcon" />
        </div>
      </template>
      <template v-else-if="scopeEvent()">
        <div style="min-width:40px; min-height:40px; padding: 10px; border-radius: 100%; background: #FFD6A4">
          <Icon :source="PlusCircleIcon" />
        </div>
      </template>
      <template v-else>
        <AvatarFrame v-if="this.userType === 'Client' && item.project?.active_assignment" rounded size="lg" :user="item.project?.active_assignment.expert" />
        <AvatarFrame v-else rounded size="lg" :user="item.project?.client" />
      </template>

      <BlockStack style="flex: 1">
        <Text as="p" variant="headingMd">
          {{ item.event.title }}
        </Text>

        <Text as="p" variant="bodyMd" tone="subdued">
          {{ item.project?.name }}
        </Text>

        <InlineStack align="end" gap="200" blockAlign="center" :wrap="false">
          <div v-if="!item.seen" class="not-seen" />

          <Text as="p" variant="bodyMd" tone="subdued" alignment="end">
            {{ dateFormat(item.created_at) }}
          </Text>
        </InlineStack>
      </BlockStack>
    </InlineStack>
  </Box>
</template>

<style scoped>
.not-seen {
  background: #0094d5;
  width: 12px;
  height: 12px;
  border-radius: 100%;
  font-size: 8px;
  text-align: center;
  line-height: 8px;
}

.hover-message {
  border-radius: 12px;
  background: #ffffff;
  cursor: pointer;
}

.hover-message:hover {
  background: #f9f9f9;
}

.hover-message:active {
  background: #f2f2f2;
}
</style>