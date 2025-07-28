<script>
import MobileCard from "@/components/MobileCard.vue";
import AlertCircleIcon from "@/components/icons/AlertCircleIcon.vue";
import moment from "moment";
import UserBox from "@/components/misc/UserBox.vue";

export default {
  name: "ProjectCard",
  components: {UserBox, MobileCard},

  props: {
    project: {
      type: Object,
      default: () => {
        return {
          "id": 1,
          "name": "asfasfasf",
          "preferred_expert": null,
          "status": "pending_match",
          "url": "asfasfasf",
          "description": "asfasfasfa",
          "company_type": "Agency",
          "client_id": 1,
          "created_at": "2024-04-21T22:00:20.000000Z",
          "updated_at": "2024-04-21T22:00:20.000000Z"
        }
      }
    }
  },

  data() {
    return {
      AlertCircleIcon,

      isMobile: screen.width <= 760,
    }
  },

  methods: {
    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do YYYY')
    }
  }
}
</script>

<template>
  <MobileCard v-if="isMobile" class="project-card">
    <BlockStack gap="400">
      <InlineStack align="space-between">
        <InlineStack>
          <Badge v-if="['pending_match', 'available', 'claimed'].includes(project.status)" tone="attention">Pending Match</Badge>
          <Badge v-else-if="project.status === 'matched'" tone="magic">Matched</Badge>
          <Badge v-else-if="project.status === 'pending_payment'" tone="critical">Pending Payment</Badge>
          <Badge v-else-if="project.status === 'in_progress'" tone="info">In Progress</Badge>
          <Badge v-else-if="project.status === 'expert_completed'" tone="info">Awaiting Approval</Badge>
          <Badge v-else-if="project.status === 'completed'" tone="success">Completed</Badge>
          <Badge v-if="project.urgent" tone="critical">Urgent</Badge>
        </InlineStack>

        <Text variant="bodySm" as="p" alignment="start" tone="subdued">
          Submitted: {{ formatDate(project.created_at) }}
        </Text>
      </InlineStack>

      <BlockStack gap="100">
        <Text as="h2" variant="headingMd">
          {{ project.name }}
        </Text>
        <Text as="p" variant="bodyMd" tone="subdued">
          {{ project.url }}
        </Text>
      </BlockStack>

      <Divider />

      <InlineStack align="start" gap="100" :wrap="false" v-if="project.status === 'pending_match' || project.status === 'available'">
        <Box>
          <Icon :source="AlertCircleIcon" />
        </Box>

        <Text variant="bodySm" as="p" alignment="start" tone="subdued">
          We are working on matching you with the best possible expert for your project. Thanks for your patience.
        </Text>
      </InlineStack>

      <UserBox role :user="project.active_assignment?.expert" v-else />
    </BlockStack>
  </MobileCard>

  <Card v-else :padding="null">
    <Box padding="300" class="project-card">
      <BlockStack gap="400">
        <InlineStack align="space-between">

          <InlineStack gap="200">
            <Badge v-if="['pending_match', 'available', 'claimed'].includes(project.status)" tone="attention">Pending Match</Badge>
            <Badge v-else-if="project.status === 'matched'" tone="magic">Matched</Badge>
            <Badge v-else-if="project.status === 'pending_payment'" tone="critical">Pending Payment</Badge>
            <Badge v-else-if="project.status === 'in_progress'" tone="info">In Progress</Badge>
            <Badge v-else-if="project.status === 'expert_completed'" tone="info">Awaiting Approval</Badge>
            <Badge v-else-if="project.status === 'completed'" tone="success">Completed</Badge>
            <Badge v-else>Missing Status</Badge>

            <Badge v-if="project.urgent" tone="critical">Urgent</Badge>
          </InlineStack>

          <Text variant="bodySm" as="p" alignment="start" tone="subdued" v-if="project.status === 'matched'">
            Matched: {{ formatDate(project.active_assignment.created_at) }}
          </Text>
          <Text variant="bodySm" as="p" alignment="start" tone="subdued" v-else>
            Submitted: {{ formatDate(project.created_at) }}
          </Text>
        </InlineStack>

        <BlockStack gap="100">
          <Text as="h2" variant="headingMd">
            {{ project.name }}
          </Text>
          <Text as="p" variant="bodyMd" tone="subdued">
            {{ project.url }}
          </Text>
        </BlockStack>

        <Divider />
        <InlineStack align="start" gap="100" :wrap="false"
                     v-if="project.status === 'pending_match' || project.status === 'available' || project.status === 'claimed'">
          <Box>
            <Icon :source="AlertCircleIcon" />
          </Box>

          <Text variant="bodySm" as="p" alignment="start" tone="subdued">
            We are working on matching you with the best possible expert for your project. Thanks for your patience.
          </Text>
        </InlineStack>

        <UserBox role :user="project.active_assignment?.expert" v-else />
      </BlockStack>
    </Box>
  </Card>
</template>

<style scoped>
.project-card:hover {
  cursor: pointer;
  background: #f9f9f9;
}
.project-card:active {
  cursor: pointer;
  background: #f2f2f2;
}
</style>