<script>
import MobileCard from "@/components/MobileCard.vue";
import AlertCircleIcon from "@/components/icons/AlertCircleIcon.vue";
import moment from "moment";
import axios from "axios";
import UserBox from "@/components/misc/UserBox.vue";
import {debounce} from "@/directives/debounce";

export default {
  name: "ProjectCard",
  components: {UserBox, MobileCard},

  props: {
    expert: {
      type: Boolean,
      default: false,
    },
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

      duration: moment.duration(300000, 'milliseconds')
    }
  },

  created() {
    let claimTime = moment(this.project.created_at, "YYYY-MM-DDTHH:mm:ss.SSSSZ").add(5, 'minutes');
    const currentTime = moment();
    const interval = 1000;

    this.duration = moment.duration(claimTime.diff(currentTime), 'milliseconds');

    setInterval(() => {
      this.duration = moment.duration(this.duration - interval, 'milliseconds');
    }, interval);
  },

  computed: {
    timeLeft() {
      let timer = moment.utc(this.duration.as('milliseconds')).format('mm:ss')

      if (this.duration.as('milliseconds') > 0) {
        return timer;
      } else {
        this.releaseProject()
        return '00:00'
      }
    },
  },

  methods: {
    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do YYYY')
    },

    releaseProject: debounce(async function() {
      await axios.get('api/expert/projects/' + this.project.project.id + '/release').then(() => {
        this.$emit('updateList')
      }).catch(() => {
        this.$router.push('/expert')
      });
    }, 200),
  }
}
</script>

<template>
  <MobileCard v-if="isMobile" class="project-card">
    <BlockStack gap="400">
      <InlineStack align="space-between">
        <InlineStack>
          <Badge v-if="project.project.status === 'matched'" tone="magic">Matched</Badge>
          <Badge v-else-if="project.project.status === 'claimed'" tone="attention">Read</Badge>
          <Badge v-else-if="project.project.status === 'pending_payment'" tone="critical">Pending Payment</Badge>
          <Badge v-else-if="project.project.status === 'in_progress'" tone="info">In Progress</Badge>
          <Badge v-else-if="project.project.status === 'expert_completed'" tone="warning">Awaiting Approval</Badge>
          <Badge v-else-if="project.project.status === 'completed'" tone="success">Completed</Badge>

          <Badge v-if="project.project.urgent" tone="critical">Urgent</Badge>
        </InlineStack>

        <BlockStack gap="200">
          <Text variant="bodySm" as="p" alignment="start" tone="subdued" v-if="project.project.status === 'claimed'">
            Open For ReadRead: {{ formatDate(project.created_at) }}
          </Text>
          <Text variant="bodySm" as="p" alignment="start" tone="subdued" v-else>
            Matched: {{ formatDate(project.created_at) }}
          </Text>

          <Text variant="bodySm" as="p" alignment="start" tone="subdued"
                v-if="project.project.status !== 'matched' && project.project.status !== 'claimed'">
            Updated: {{ formatDate(project.created_at) }}
          </Text>
        </BlockStack>
      </InlineStack>

      <BlockStack gap="100">
        <Text as="h2" variant="headingMd">
          {{ project.project.name }}
        </Text>
        <Text as="p" variant="bodyMd" tone="subdued" @click.stop>
          <Link :url="project.project.url && project.project.url.startsWith('https://') ? project.project.url : 'https://' + project.project.url" target="_blank" removeUnderline style="color: #6E6E73;">
            {{ project.project.url }}
          </Link>
        </Text>
      </BlockStack>

      <Divider />

      <BlockStack gap="200" blockAlign="start">
        <UserBox client :user="project.project.client" />

        <InlineStack gap="200" blockAlign="center" v-if="project.project.status === 'claimed'">
          <Text as="p" variant="bodySm" tone="subdued">
            Time Left to Respond
          </Text>
          <Text as="p" variant="headingLg">
            {{ timeLeft }}
          </Text>
        </InlineStack>
      </BlockStack>
    </BlockStack>
  </MobileCard>

  <Card v-else :padding="null">
    <Box padding="300" class="project-card">
      <BlockStack gap="400">
        <InlineStack align="space-between" blockAling="start">
          <div>

            <InlineStack gap="200">
              <Badge v-if="project.project.status === 'matched'" tone="magic">Matched</Badge>
              <Badge v-else-if="project.project.status === 'claimed'" tone="attention">Read</Badge>
              <Badge v-else-if="project.project.status === 'pending_payment'" tone="critical">Pending Payment</Badge>
              <Badge v-else-if="project.project.status === 'in_progress'" tone="info">In Progress</Badge>
              <Badge v-else-if="project.project.status === 'expert_completed'" tone="warning">Awaiting Approval</Badge>
              <Badge v-else-if="project.project.status === 'completed'" tone="success">Completed</Badge>
              <Badge v-else>Missing Status</Badge>

              <Badge v-if="project.project.urgent" tone="critical">Urgent</Badge>
            </InlineStack>
          </div>
          <BlockStack gap="200" inlineAlign="end">
            <Text variant="bodySm" as="p" alignment="start" tone="subdued" v-if="project.project.status === 'claimed'">
              Open for read: {{ formatDate(project.created_at) }}
            </Text>
            <Text variant="bodySm" as="p" alignment="start" tone="subdued" v-else>
              Matched: {{ formatDate(project.created_at) }}
            </Text>

            <Text variant="bodySm" as="p" alignment="start" tone="subdued"
                  v-if="project.project.status !== 'matched' && project.project.status !== 'claimed'">
              Last updated on: {{ formatDate(project.created_at) }}
            </Text>
          </BlockStack>
        </InlineStack>

        <BlockStack gap="100">
          <Text as="h2" variant="headingMd">
            {{ project.project.name }}
          </Text>
          <Text as="p" variant="bodyMd" tone="subdued" @click.stop>
            <Link :url="project.project.url && project.project.url.startsWith('https://') ? project.project.url : 'https://' + project.project.url" target="_blank" removeUnderline style="color: #6E6E73;">
              {{ project.project.url }}
            </Link>
          </Text>
        </BlockStack>

        <Divider />
        <InlineStack align="space-between" blockAlign="center">
          <UserBox client :user="project.project.client" />

          <InlineStack gap="200" blockAlign="center" v-if="project.project.status === 'claimed'">
            <Text as="p" variant="bodySm" tone="subdued">
              Time Left to Respond
            </Text>
            <Text as="p" variant="headingLg">
              {{ timeLeft }}
            </Text>
          </InlineStack>
        </InlineStack>
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