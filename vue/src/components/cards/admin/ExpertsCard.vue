<script>
import StarFullIcon from "@/components/icons/StarFullIcon.vue";
import moment from "moment";
import axios from "axios";
import AvatarFrame from "@/components/misc/AvatarFrame.vue";
import {debounce} from "@/directives/debounce";
import LoginAsModal from "@/components/modals/LoginAsModal.vue";

export default {
  name: "ExpertsCard",
  components: {AvatarFrame, LoginAsModal},

  props: {
    expert: {
      default: () => {},
      type: Object
    }
  },

  data() {
    return {
      StarFullIcon,

      loading: false,
      loginAs: false,

      actionsPopover: false,
      actionsList: [
        {
          content: 'Activate',
          role: 'assign',
          onAction: () => this.updateUserStatus('active')
        },
        {
          content: 'Deactivated',
          role: 'assign',
          onAction: () => this.updateUserStatus('inactive')
        }
      ]
    }
  },

  computed: {
    completedProjects() {
      return this.expert.active_assignments.length ?
          this.expert.active_assignments.filter(a => a.project?.status === 'completed').length : 0
    },

    totalReview() {
      if (this.expert.reviews) {
        return this.expert.reviews.length
      } else {
        return 0
      }
    },
    expertRating() {
      let rating = 0;

      if (this.expert.reviews.length) {
        this.expert.reviews.forEach(rev => {
          rating += rev.rate;
        })

        return (rating / this.totalReview).toFixed(1)
      } else {
        return rating.toFixed(1)
      }
    },
  },

  methods: {
    toggleActionsPopover() {
      this.actionsPopover = !this.actionsPopover;
    },

    updateUserStatus: debounce(async function(status) {
      this.actionsPopover = false;
      this.loading = true;
      await axios.post('api/admin/experts/' + this.expert.id, {status: status}).then(() => {
        this.$emit('reload')
        this.loading = false;
      }).catch(() => {
        this.$router.push('/admin/experts')
        this.loading = false;
      });
    }, 200),

    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do YYYY')
    },
    goTo(id) {
      window.open('/admin/expert/' + id, '_blank')
    },

    toggleLoginAsModal() {
      this.loginAs = !this.loginAs;
    }
  }
}
</script>

<template>
  <Card :padding="null" @click="goTo(expert.id)">
    <Box paddingBlock="400" paddingInline="800" class="project-card">
      <BlockStack gap="400">
        <InlineStack align="space-between" blockAlign="center">
          <InlineStack align="start" blockAlign="center" gap="200">
            <AvatarFrame rounded size="lg" :user="expert" />

            <BlockStack gap="100">
              <Text as="h2" variant="headingSm">
                {{ expert.first_name }}
                {{ expert.last_name }}

                <Badge tone="attention" v-if="expert.profile.status === 'pending'">Pending</Badge>
                <Badge tone="success" v-else-if="expert.profile.status === 'active'">Active</Badge>
                <Badge tone="warning" v-else-if="expert.profile.status === 'inactive'">Deactivated</Badge>
              </Text>
              <Text as="p" variant="bodySm" tone="subdued">
                {{ expert.profile.role }}
              </Text>

              <BlockStack gap="100" v-if="expert.profile.status === 'pending'" @click.stop>
                <Text as="p" variant="bodySm" tone="subdued" style="color: #005BD3;">
                  {{ expert.email }}
                </Text>
                <Text>
                  <Link :url="expert.url.startsWith('https://') ? expert.url : 'https://' + expert.url" target="_blank" removeUnderline style="color: #005BD3;">
                    {{ expert.url }}
                  </Link>
                </Text>
              </BlockStack>

              <InlineStack gap="100" v-if="expert.profile.status !== 'pending'">
                <div>
                  <Icon :source="StarFullIcon" />
                </div>
                <Text as="p" variant="bodyMd">
                  {{ expertRating }}
                  <Text as="span" variant="bodyMd" tone="subdued">
                    ({{ totalReview }} Completed Projects)
                  </Text>
                </Text>
              </InlineStack>
            </BlockStack>
          </InlineStack>

          <BlockStack gap="100" inlineAlign="end" v-if="expert.profile.status === 'pending'">
            <Popover
                :active="actionsPopover"
                autofocusTarget="first-node"
                @close="toggleActionsPopover"
            >
              <template #activator>
                <Button @click.stop="toggleActionsPopover" :loading="loading"
                        :disclosure="actionsPopover ? 'up' : 'down'">Actions</Button>
              </template>
              <ActionList
                  actionRole="menuitem"
                  :items="actionsList"
              ></ActionList>
            </Popover>

            <Text as="p" variant="bodyMd" tone="subdued">
              Submitted Date: {{ formatDate(expert.created_at) }}
            </Text>
          </BlockStack>

          <template v-else>
            <BlockStack gap="100">
              <Text as="p" variant="bodySm" tone="subdued">
                Matched Projects: {{ expert.active_assignments.length }}
              </Text>
              <Text as="p" variant="bodySm" tone="subdued">
                Completed Projects: {{ completedProjects }}
              </Text>
              <Text as="p" variant="bodySm" tone="subdued">
                Joined Date: {{ formatDate(expert.created_at) }}
              </Text>
            </BlockStack>

            <BlockStack gap="100">
              <Text as="p" variant="bodySm" tone="subdued" alignment="end">
                Total Earnings
              </Text>
              <Text as="p" variant="headingLg">
                <Text as="span" style="color: #303030"> ${{ expert.totalEarnings }}</Text>
              </Text>
            </BlockStack>

            <BlockStack gap="100">
              <Button variant="secondary" size="large" @click.stop="toggleLoginAsModal">Login as</Button>
            </BlockStack>
          </template>
        </InlineStack>

        <Divider />
        <InlineStack align="space-between" blockAlign="center">
          <Text as="p" variant="bodyMd" tone="subdued">Years of Experience:
            <Text as="span" variant="bodyMd" fontWeight="semibold" style="color: #303030">
              {{ expert.profile.experience }}
            </Text>
          </Text>

          <Text as="p" variant="bodyMd" tone="subdued">English Level:
            <Text as="span" variant="bodyMd" fontWeight="semibold" style="color: #303030">
              {{ expert.profile.eng_level }}
            </Text>
          </Text>

          <Text as="p" variant="bodyMd" tone="subdued">Availability:
            <Text as="span" variant="bodyMd" fontWeight="semibold" style="color: #303030">
              {{ expert.profile.availability }}
            </Text>
          </Text>

          <Text as="p" variant="bodyMd" tone="subdued">Hourly Rate:
            <Text as="span" variant="bodyMd" fontWeight="semibold" style="color: #303030">
              ${{ expert.profile.hourly_rate }}/Hour
            </Text>
          </Text>
        </InlineStack>
      </BlockStack>
    </Box>
  </Card>
  <LoginAsModal v-if="loginAs" type='expert' :user="expert" @close="toggleLoginAsModal" />
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
