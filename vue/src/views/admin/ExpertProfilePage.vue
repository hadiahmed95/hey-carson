<script>
import AdminLayout from "@/layout/AdminLayout.vue";
import ProjectsCard from "@/components/cards/admin/ProjectsCard.vue";
import StarFullIcon from "@/components/icons/StarFullIcon.vue";
import LoadingPage from "@/components/cards/LoadingPage.vue";
import axios from "axios";
import moment from "moment/moment";
import AvatarFrame from "@/components/misc/AvatarFrame.vue";
import UserBox from "@/components/misc/UserBox.vue";
import StarEmptyIcon from "@/components/icons/StarEmptyIcon.vue";
import LoginAsModal from "@/components/modals/LoginAsModal.vue";
import {debounce} from "@/directives/debounce";

export default {
  name: "ExpertProfilePage",

  components: {
    StarEmptyIcon,
    StarFullIcon,
    UserBox,
    LoadingPage,
    ProjectsCard,
    AdminLayout,
    AvatarFrame,
    LoginAsModal
  },

  async mounted() {
    await this.getExpert(this.$route.params.id)
  },

  // eslint-disable-next-line
  beforeRouteUpdate(to, from) {
    this.getExpert(to.param.id);
  },

  computed: {
    totalReview() {
      if (this.expert.reviews) {
        return this.expert.reviews.length
      } else {
        return 0
      }
    },
    expertRating() {
      let rating = 0;

      if (this.totalReview) {
        this.expert.reviews.forEach(rev => {
          rating += rev.rate;
        })

        return (rating / this.totalReview).toFixed(1)
      } else {
        return rating.toFixed(1)
      }
    },

    totalProjects() {
      if (this.expert.active_assignments) {
        return this.expert.active_assignments.length
      } else {
        return 0
      }
    },

    claimedProjects() {
      if (this.totalProjects) {
        return this.expert.active_assignments.filter(assignment => assignment.project?.status === 'claimed').length
      } else {
        return 0
      }
    },

    completedProjects() {
      if (this.totalProjects) {
        return this.expert.active_assignments.filter(assignment => assignment.project?.status === 'completed').length
      } else {
        return 0
      }
    },

    projects() {
      if (this.totalProjects) {
        let projects = [...this.expert.active_assignments];
        return projects.sort(function(a, b) {
          let aT = moment(a.project?.updated_at, "YYYY-MM-DDTHH:mm:ss.SSSSZ")
          let bT = moment(b.project?.updated_at, "YYYY-MM-DDTHH:mm:ss.SSSSZ")
          if (bT.isBefore(aT)) {
            return 1
          } else if (aT.isBefore(bT)) {
            return -1
          } else {
            0
          }
        });
      } else {
        return [];
      }
    },

    actionsList() {
      if (this.expert.deleted_at && this.expert.profile.status === 'inactive') {
        return [
          {
            content: 'Activate',
            role: 'assign',
            onAction: () => this.updateUserStatus('active')
          }
        ]
      } else if (!this.expert.deleted_at && this.expert.profile.status === 'active') {
        return [
          {
            content: 'Deactivated',
            role: 'assign',
            onAction: () => this.updateUserStatus('inactive')
          },
          {
            content: 'Login As',
            onAction: () => this.toggleLoginAsModal()
          }
        ]
      } else {
        return [
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
    }
  },

  data() {
    return {
      StarFullIcon,

      pageLoading: true,

      activeTab: 0,
      tabs: [
        {
          id: 'projects',
          content: 'Projects',
        },
        {
          id: 'reviews',
          content: 'Reviews',
        },
        {
          id: 'payouts',
          content: 'Payouts',
        },
      ],

      expert: {},

      actionsPopover: false,
      loginAs: false,
    }
  },

  methods: {
    changeTab(tab) {
      this.activeTab = tab;
    },

    toggleActionsPopover() {
      this.actionsPopover = !this.actionsPopover
    },

    toggleLoginAsModal() {
      this.loginAs = !this.loginAs;
    },

    async getExpert(id) {
      this.pageLoading = true;
      await axios.get('api/admin/experts/' + id).then(res => {
        this.expert = res.data.expert;
        this.totalEarning = res.data.totalEarning;
        this.currentBalance = res.data.currentBalance;

        this.pageLoading = false;
      }).catch(() => {
        this.$router.push('/admin/experts')
      });
    },

    updateUserStatus: debounce(async function(status) {
      this.statusPopover = false
      await axios.post('api/admin/experts/' + this.expert.id, {status: status}).then(() => {
        this.getExpert(this.expert.id)
      }).catch(() => {
        this.$router.push('/admin/experts')
      });
    }, 200),

    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do YYYY')
    },
  }
}
</script>

<template>
  <AdminLayout>
    <LoadingPage v-if="pageLoading" />

    <Page v-else style="padding-top: 56px; padding-bottom: 56px"
          title="Expert Profile"
    >
      <BlockStack gap="800">
        <Card padding="800">
          <BlockStack gap="400">
            <InlineStack align="space-between">
              <InlineStack gap="400">
                <AvatarFrame rounded size="xl" :user="expert" />

                <BlockStack gap="300">
                  <BlockStack gap="100">
                    <Text as="h2" variant="headingLg">
                      {{ expert.first_name }} {{ expert.last_name }}

<!--                      <Badge size="large">Time: 08:25am</Badge>-->
                      <Badge tone="attention" v-if="expert.profile.status === 'pending'">Pending</Badge>
                      <Badge tone="success" v-else-if="expert.profile.status === 'active'">Active</Badge>
                      <Badge tone="warning" v-else-if="expert.profile.status === 'inactive'">Deactivated</Badge>
                    </Text>
                    <Text as="p" variant="bodyLg" tone="subdued">
                      {{ expert.profile.role }}
                    </Text>
                    <Text as="p" variant="bodySm" style="color: #005BD3;">
                      {{ expert.email }}
                    </Text>
                    <Text>
                      <Link :url="expert.url.startsWith('https://') ? expert.url : 'https://' + expert.url" target="_blank" removeUnderline style="color: #005BD3;">
                        {{ expert.url }}
                      </Link>
                    </Text>
                  </BlockStack>

                  <InlineStack align="start" blockAlign="center" gap="100">
                    <div>
                      <Icon :source="StarFullIcon" />
                    </div>
                    <Text as="p" variant="headingLg">{{ expertRating }}</Text>
                    <Text as="p" variant="bodyMd" tone="subdued">({{ totalReview }} Reviews)</Text>
                  </InlineStack>

                  <InlineStack align="start" gap="200">
<!--                    <Button>Login As</Button>-->
                    <Popover :active="actionsPopover"
                             autofocusTarget="first-node"
                             @close="toggleActionsPopover"
                    >
                      <template #activator>
                        <Button @click.stop="toggleActionsPopover"
                                :disclosure="actionsPopover ? 'up' : 'down'">Actions</Button>
                      </template>
                      <ActionList
                          actionRole="menuitem"
                          :items="actionsList"
                      ></ActionList>
                    </Popover>
                  </InlineStack>
                </BlockStack>
              </InlineStack>

              <BlockStack gap="200">
                <Text as="p" variant="bodyMd" tone="subdued">
                  Total Earnings:
                  <Text as="span" style="color: #303030">${{ this.totalEarning }}</Text>

                </Text>

                <Text as="p" variant="bodyMd" tone="subdued">
                  Current Balance:
                  <Text as="span" style="color: #303030">${{ this.currentBalance }}</Text>

                </Text>

                <Text as="p" variant="bodyMd" tone="subdued">
                  Matched Projects:

                  <Text as="span" style="color: #303030">{{ totalProjects }}</Text>
                </Text>

                <Text as="p" variant="bodyMd" tone="subdued">
                  Claimed Projects:

                  <Text as="span" style="color: #303030">{{ claimedProjects }}</Text>
                </Text>

                <Text as="p" variant="bodyMd" tone="subdued">
                  Completed Projects:

                  <Text as="span" style="color: #303030">{{ completedProjects }}</Text>
                </Text>

                <Text as="p" variant="bodyMd" tone="subdued">
                  Location:

                  <Text as="span" style="color: #303030">{{ expert.profile.country }}</Text>
                </Text>

                <Text as="p" variant="bodyMd" tone="subdued">
                  Joined Date:

                  <Text as="span" style="color: #303030">{{ formatDate(expert.created_at) }}</Text>
                </Text>
              </BlockStack>
            </InlineStack>

            <Divider />

            <InlineStack align="space-between" blockAlign="center">
              <Text as="p" variant="bodyMd" tone="subdued">Experience:
                <Text as="p" variant="bodyMd" fontWeight="semibold" style="color: #303030">
                  {{ expert.profile.experience }}
                </Text>
              </Text>

              <Text as="p" variant="bodyMd" tone="subdued">English Level:
                <Text as="p" variant="bodyMd" fontWeight="semibold" style="color: #303030">
                  {{ expert.profile.eng_level }}
                </Text>
              </Text>

              <Text as="p" variant="bodyMd" tone="subdued">Availability:
                <Text as="p" variant="bodyMd" fontWeight="semibold" style="color: #303030">
                  {{ expert.profile.availability }}
                </Text>
              </Text>

              <Text as="p" variant="bodyMd" tone="subdued">Hourly Rate:
                <Text as="p" variant="bodyMd" fontWeight="semibold" style="color: #303030">
                  ${{ expert.profile.hourly_rate }}/Hour
                </Text>
              </Text>
            </InlineStack>
          </BlockStack>
        </Card>

        <BlockStack gap="200" v-if="expert.profile.status !== 'pending'">
          <Text as="p" variant="headingMd">Last Updates</Text>

          <Tabs style="padding: 0"
                :tabs="tabs"
                :selected="activeTab"
                @select="changeTab"
          />
        </BlockStack>

        <BlockStack gap="300" v-if="activeTab === 0">
          <ProjectsCard v-for="active_assignment in projects"
                        :key="active_assignment.id"
                        :project="active_assignment.project"/>
        </BlockStack>

        <BlcokStack gap="300" v-if="activeTab === 1">
          <template v-for="review in expert.reviews" :key="review.id">
            <Card padding="600">
              <BlockStack gap="400">
                <BlockStack gap="200">
                  <Text fontWeight="semibold">{{ review.project.name }}</Text>

                  <InlineStack gap="400" align="start" blockAlign="center">
                    <InlineStack gap="200">
                      <div style="width: 14px; height: 24px">
                        <StarFullIcon style="width: 24px; height: 24px" v-if="review.rate > 0" />
                        <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                      </div>
                      <div style="width: 14px; height: 24px">
                        <StarFullIcon style="width: 24px; height: 24px" v-if="review.rate > 1" />
                        <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                      </div>
                      <div style="width: 14px; height: 24px">
                        <StarFullIcon style="width: 24px; height: 24px" v-if="review.rate > 2" />
                        <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                      </div>
                      <div style="width: 14px; height: 24px">
                        <StarFullIcon style="width: 24px; height: 24px" v-if="review.rate > 3" />
                        <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                      </div>
                      <div style="width: 14px; height: 24px">
                        <StarFullIcon style="width: 24px; height: 24px" v-if="review.rate > 4" />
                        <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                      </div>
                    </InlineStack>

                    <Text variant="headingMd" as="p">
                      {{ review.rate.toFixed(2) }}
                    </Text>

                    <Text tone="subdued">{{ formatDate(review.created_at) }}</Text>
                  </InlineStack>
                </BlockStack>

                <BlockStack gap="200">
                  <UserBox :user="review.client" />

                  <Text>{{ review.comment }}</Text>
                </BlockStack>
              </BlockStack>
            </Card>
          </template>
        </BlcokStack>
      </BlockStack>
    </Page>
  </AdminLayout>
  <LoginAsModal v-if="loginAs" type='expert' :user="expert" @close="toggleLoginAsModal" />
</template>

<style scoped>

</style>