<script>
import MobileCard from "@/components/MobileCard.vue";
import axios from "axios";
import AvatarFrame from "@/components/misc/AvatarFrame.vue";
import moment from "moment";
import {debounce} from "@/directives/debounce";

export default {
  name: "QuestionsCard",
  components: {AvatarFrame, MobileCard },
  props: {
    question: {
      type: Object,
      default: () => {
        return {}
      }
    },

    answers: {
      type: Array,
      default: () => {
        return []
      }
    },

    status: {
      default: '',
      type: String
    }
  },

  data() {
    return {
      actionsPopover: false,
      actionsLoading: false,
      actionsList: [
        {
          content: 'Approve',
          role: 'completed',
          onAction: () => this.updateQuestionStatus('completed')
        },
        {
          content: 'Decline',
          role: 'declined',
          onAction: () => this.updateQuestionStatus('declined')
        }
      ],
    }
  },

  methods: {
    toggleActionsPopover() {
      this.actionsPopover = !this.actionsPopover;
    },

    goTo(id) {
      if (!this.answers.length) {
        this.$router.push('/admin/questions/' + id)
      }
    },

    updateQuestionStatus: debounce(async function(status) {
      this.actionsPopover = false
      this.actionsLoading = true
      axios.put(`api/admin/questions/${this.question.id}`, {'status': status}).then(() => {
        this.actionsLoading = false
        this.$emit('refresh')
      }).catch(() => {
        this.actionsLoading = false
        this.$emit('refresh')
      })
    }, 200),

    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('Do MMMM, YYYY')
    },
  }
}
</script>

<template>
  <BlockStack gap="200">
    <MobileCard padding="600" :class="{ 'question-card': !answers.length }" @click="goTo(question.id)">
      <BlockStack gap="400">
        <InlineStack align="space-between">
          <Popover
              :active="actionsPopover"
              autofocusTarget="first-node"
              @close="toggleActionsPopover"
          >
            <template #activator>
              <Button
                  @click.stop="toggleActionsPopover"
                  :loading="actionsLoading"
                  :disabled="question.status !== 'created'"
                  :disclosure="actionsPopover ? 'up' : 'down'"
              >
                Actions
              </Button>
            </template>
            <ActionList
                actionRole="menuitem"
                :items="actionsList"
            ></ActionList>
          </Popover>


          <Badge v-if="question.status === 'declined'" tone="critical">Declined</Badge>
          <Badge v-else-if="question.status === 'completed'" tone="success">Approved</Badge>
          <Badge v-else-if="question.status === 'created'" tone="attention">Pending</Badge>
          <Badge v-else>Missing Status</Badge>
        </InlineStack>

        <InlineStack
            align="space-between"
            blockAlign="start"
        >
          <Text as="p" variant="bodyMd" tone="subdued">
            Question asked by:
            {{ question.client.full_name }}
          </Text>

          <BlockStack gap="050" :class="{ 'pt-16 inline-block':isMobile }">
            <Text as="p" variant="bodySm" tone="subdued">
              <Text as="span">
                Posted {{ formatDate(question.created_at) }}
              </Text>
            </Text>
          </BlockStack>
        </InlineStack>

        <Text variant="headingLg" fontWeight="bold" as="h2">
          {{ question.content }}
        </Text>

        <Divider
            v-if="answers.length"
        />
        <BlockStack v-for="answer in answers" :key="answer.id">
          <MobileCard padding="600">
            <BlockStack gap="800">
              <InlineStack
                  align="space-between"
                  blockAlign="start"
              >
                <InlineStack gap="200">
                  <AvatarFrame
                      rounded
                      size="lg"
                      :user="answer.expert"
                  />

                  <BlockStack gap="050">
                    <Text as="p" variant="bodyLg" fontWeight="semibold">
                      {{ answer.expert.full_name }}
                    </Text>
                    <Text as="p" variant="bodySm" tone="subdued">
                      {{ answer.expert.profile.role }}
                    </Text>
                  </BlockStack>
                </InlineStack>

                <BlockStack gap="050" :class="{ 'pt-16 inline-block':isMobile }">
                  <Text as="p" variant="bodySm" tone="subdued">
                    <Text as="span">
                      Posted {{ formatDate(answer.created_at) }}
                    </Text>
                  </Text>
                </BlockStack>
              </InlineStack>

              <InlineStack class="container" gap="200">
                <Text variant="bodyMd" as="span">
                  {{ answer.content }}
                </Text>
                <Text variant="bodySm" tone="subdued" as="p">
                  {{ answer.edited ? '(edited)' : '' }}
                </Text>
              </InlineStack>
            </BlockStack>
          </MobileCard>
        </BlockStack>
      </BlockStack>
    </MobileCard>

  </BlockStack>
</template>

<style scoped>
.question-card:hover {
  cursor: pointer;
  background: #f9f9f9;
}
</style>
