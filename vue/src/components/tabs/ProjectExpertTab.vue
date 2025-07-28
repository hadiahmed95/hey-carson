<script>
import QuotaCard from "@/components/cards/QuotaCard.vue";

import CheckCircle from "@/components/icons/CheckCircle.vue";
import StarFullIcon from "@/components/icons/StarFullIcon.vue";
import StarEmptyIcon from "@/components/icons/StarEmptyIcon.vue";
import MobileCard from "@/components/MobileCard.vue";
import MobileBanner from "@/components/MobileBanner.vue";
import UserBox from "@/components/misc/UserBox.vue";
import InputBtn from "@/components/misc/InputBtn.vue";
import common from '@/mixins/common';

export default {
  name: "ProjectExpertTab",

  props: {
    quotas: {
      type: Array
    },
    project: {
      default: () => ({
        status: '',
        expertRating: '',
        stars: [0,0,0,0,0]
      }),
      type: Object
    }
  },

  components: {
    InputBtn,
    UserBox,
    StarEmptyIcon,
    StarFullIcon,
    QuotaCard,
    MobileCard,
    MobileBanner,
  },

  computed: {
    totalReview() {
      if (this.project.active_assignment) {
        return this.project.active_assignment.expert.reviews.length
      } else {
        return 0
      }
    },
    expertRating() {
      let rating = 0;

      if (this.totalReview) {
        this.project.active_assignment.expert.reviews.forEach(rev => {
          rating += rev.rate;
        })

        return (rating / this.totalReview).toFixed(1)
      } else {
        return rating.toFixed(1)
      }
    },

    completedProjects() {
      if (this.project.active_assignment) {
        return this.project.active_assignment.expert.active_assignments.filter(a => a.project?.status === 'completed').length
      } else {
        return 0;
      }
    },

    bannerClosedInitial() {
      let key = 'client-project-banner-' + this.project.id + '-initial';
      return localStorage.getItem(key);
    },

    bannerClosedExpertCompleted() {
      let key = 'client-project-banner-' + this.project.id + '-expert_completed';
      return localStorage.getItem(key);
    },

    bannerClosedCompleted() {
      let key = 'client-project-banner-' + this.project.id + '-completed';
      return localStorage.getItem(key);
    },
  },

  data() {
    return {
      CheckCircle,
      isMobile: screen.width <= 760,

      closedBanner: false,
      timeLeft: '',
      intervalId: null
    }
  },

  mixins: [common],

  mounted() {
    if(this.project?.status === 'expert_completed') {
      this.calculateTimeLeft(this.project.updated_at)

      this.intervalId = setInterval(() => {
        this.calculateTimeLeft(this.project.updated_at);
      }, 1000);
    }
  },

  unmounted() {
    if(this.intervalId) {
      clearInterval(this.intervalId)
    }
  },

  methods: {
    add72Hours(date) {
      date.setHours(date.getHours() + 72)

      return date;
    },

    diff(startDate, endDate) {
      let diff = endDate.getTime() - startDate.getTime();
      let hours = Math.floor(diff / 1000 / 60 / 60);
      diff -= hours * 1000 * 60 * 60;
      let minutes = Math.floor(diff / 1000 / 60);
      diff -= minutes * 1000 * 60;
      let seconds = Math.floor(diff / 1000);

      if (hours < 0) {
        if(this.intervalId) {
          clearInterval(this.intervalId)
        }
        return "00:00:00"
      }

      return (
          (hours <= 9 ? "0" : "") + hours + ":" +
          (minutes <= 9 ? "0" : "") + minutes + ":" +
          (seconds <= 9 ? "0" : "") + seconds
      );
    },

    calculateTimeLeft(startTime) {
      this.timeLeft = this.diff(new Date(), this.add72Hours(new Date(startTime)))
    },

    closeBanner(value) {
      this.closedBanner = true;
      let key = 'client-project-banner-' + this.project.id + '-' + value;
      localStorage.setItem(key, '1');
      if (value === 'expert_completed') {
        localStorage.setItem('client-project-banner-' + this.project.id + '-initial', '1');
      }
      if (value === 'completed') {
        localStorage.setItem('client-project-banner-' + this.project.id + '-initial', '1');
        localStorage.setItem('client-project-banner-' + this.project.id + '-completed', '1');
      }
    }
  }
}
</script>

<template>
  <template v-if="isMobile">
    <BlockStack gap="400">
      <template v-if="project?.status === 'pending_match' || project?.status === 'available' || project?.status === 'claimed'">
        <MobileCard>
          <EmptyState
              heading="We are working on matching you with the best expert"
              image="https://cdn.shopify.com/s/files/1/0262/4071/2726/files/emptystate-files.png"
          >
            <p>You can expect a follow-up from us within 24-48 hours.</p>
          </EmptyState>
        </MobileCard>
      </template>

      <template v-else>
        <MobileBanner v-if="project?.status === 'expert_completed'" info>
          <template #title>The expert has marked this project as 'Awaiting Approval'.</template>

          <Text as="p">
            The expert on this project has submitted a request to mark it as completed. If you're confident that the expert has successfully addressed all requirements, feel free to mark the project as completed. Otherwise, select "Not There Yet".
          </Text>
        </MobileBanner>
        <MobileBanner v-else-if="project?.status === 'completed'">
          <template #title>You marked this project as 'Completed'.</template>

          <Text as="p">
            We are delighted to see that the project has reached completion. We hope this project will improve your e-commerce business. Take a moment to share your experience with the expert who worked on this project. Your feedback helps us improve our services to better meet your needs.
          </Text>
        </MobileBanner>
        <MobileBanner v-else>
          <template #title>Great news!</template>

          <Text as="p">
            We've matched you with the best expert for this project type. The expert will be in touch with you shortly to start the conversation.
          </Text>
        </MobileBanner>

        <MobileCard padding="600">
          <BlockStack gap="400">
            <BlockStack gap="300" v-if="project.active_assignment">
              <UserBox role isMobile :user="project.active_assignment.expert" />

              <InlineStack gap="050" :wrap="false" blockAlign="center">
                <Text as="p" variant="bodyLg" fontWeight="semibold">
                  {{ expertRating }}
                  <Text as="span" variant="bodyMd" fontWight="default">({{ totalReview }} Reviews)</Text>
                </Text>
                <Text as="p" variant="bodySm" tone="subdued">
                  {{ completedProjects }} Completed Projects
                </Text>
              </InlineStack>
            </BlockStack>

            <BlockStack gap="300" v-if="project.active_assignment">
              <QuotaCard @showModal="() => this.$emit('showQuotaModal', offer)" @decline="() => this.$emit('declineOffer', offer.id)"
                         v-for="offer in project.active_assignment.offers" :key="offer.id" :quota="offer" :user="project.active_assignment.expert" />
            </BlockStack>

            <template v-if="project?.status === 'completed' || project?.status === 'expert_completed'">
              <Divider />

                <template v-if="project?.status === 'completed'">
                  <InlineStack align="space-between">
                    <BlockStack gap="100">
                      <Text as="p" variant="bodyMd" tone="subdued">
                        Mark as Completed
                      </Text>
                      <Text as="p" variant="headingMd">
                        {{ formatDeadline(new Date(project.updated_at)) }}
                      </Text>
                    </BlockStack>

                    <BlockStack gap="100">
                      <Text as="p" variant="bodyMd" tone="subdued" v-if="project?.review">
                        Expert's Rating
                      </Text>

                      <InlineStack gap="200" align="start" blockAlign="center" v-if="project?.review">
                        <InlineStack>
                          <StarFullIcon style="width: 20px; height: 20px" />
                        </InlineStack>

                        <Text variant="headingMd" as="p">
                          {{ project?.review?.rate }}
                        </Text>
                      </InlineStack>
                    </BlockStack>
                  </InlineStack>
                </template>

                <template v-else>
                  <BlockStack gap="200">
                    <InputBtn @click="() => this.$emit('showFinishModal')">Mark as Completed</InputBtn>

                    <Button @click="() => this.$emit('notYet')">Not There Yet</Button>
                  </BlockStack>
                </template>
            </template>
          </BlockStack>
        </MobileCard>
      </template>
    </BlockStack>
  </template>
  <template v-else>
    <BlockStack gap="400">
      <template v-if="project?.status === 'pending_match' || project?.status === 'available' || project?.status === 'claimed'">
        <Card>
          <EmptyState
              heading="We are working on matching you with the best expert"
              image="https://cdn.shopify.com/s/files/1/0262/4071/2726/files/emptystate-files.png"
          >
            <p>You can expect a follow-up from us within 24-48 hours.</p>
          </EmptyState>
        </Card>
      </template>
      <template v-else>
        <template v-if="!closedBanner">
          <Banner v-if="project?.status === 'expert_completed' && !bannerClosedExpertCompleted"
                @dismiss="closeBanner('expert_completed')"
                title="The expert has marked this project as 'Awaiting Approval'. "
                tone="info">
          <Text as="p">
            The expert on this project has submitted a request to mark it as completed. If you're confident that the expert has successfully addressed all requirements, feel free to mark the project as completed. Otherwise, select "Not There Yet".</Text>
        </Banner>
        <Banner v-else-if="project?.status === 'completed' && !bannerClosedCompleted"
                @dismiss="closeBanner('completed')"
                title="You marked this project as 'Completed'."
                tone="success">
          <Text as="p">
            We are delighted to see that the project has reached completion. We hope this project will improve your e-commerce business. Take a moment to share your experience with the expert who worked on this project. Your feedback helps us improve our services to better meet your needs.
          </Text>
        </Banner>
        <Banner v-else-if="!bannerClosedInitial"
                @dismiss="closeBanner('initial')"
                title="Great news!"
                tone="success">
          <Text as="p">
            We've matched you with the best expert for this project type. The expert will be in touch with you shortly to start the conversation.
          </Text>
        </Banner>
        </template>

        <Card padding="600">
          <BlockStack gap="800" v-if="project.active_assignment">
            <InlineStack align="space-between" blockAlign="center">
              <UserBox role :user="project.active_assignment.expert" />

              <BlockStack gap="050">
                <Text as="p" variant="bodyLg" fontWeight="semibold">
                  {{ expertRating }}
                  <Text as="span" variant="bodyMd" fontWight="default">({{ totalReview }} Reviews)</Text>
                </Text>
                <Text as="p" variant="bodySm" tone="subdued">
                  {{ completedProjects }} Completed Projects
                </Text>
              </BlockStack>

<!--              <Badge size="large">Local Time: 09:36am</Badge>-->
            </InlineStack>

            <template v-if="project.active_assignment && project.active_assignment.offers && project.active_assignment.offers.length">
              <BlockStack gap="300">
                <QuotaCard @showModal="() => this.$emit('showQuotaModal', offer)" @decline="() => this.$emit('declineOffer', offer.id)"
                           v-for="offer in project.active_assignment.offers" :key="offer.id" :quota="offer" :user="project.active_assignment.expert" />
              </BlockStack>
            </template>

            <template v-if="project?.status === 'completed' || project?.status === 'expert_completed'">
              <Divider />

              <InlineStack align="space-between" blockAlign="center">
                <template v-if="project?.status === 'completed'">
                  <BlockStack gap="100">
                    <Text as="p" variant="bodyMd" tone="subdued">
                      Mark as Completed
                    </Text>
                    <Text as="p" variant="headingMd">
                      {{ formatDeadline(new Date(project.updated_at)) }}
                    </Text>
                  </BlockStack>

                  <BlockStack gap="100">
                    <Text as="p" variant="bodyMd" tone="subdued" v-if="project?.review">
                      Expert's Rating
                    </Text>

                    <InlineStack gap="200" align="start" blockAlign="center" v-if="project?.review">
                      <InlineStack>
                        <div style="width: 24px; height: 24px">
                          <StarFullIcon style="width: 24px; height: 24px" v-if="project?.review?.rate > 0" />
                          <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                        </div>
                        <div style="width: 24px; height: 24px">
                          <StarFullIcon style="width: 24px; height: 24px" v-if="project?.review?.rate > 1" />
                          <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                        </div>
                        <div style="width: 24px; height: 24px">
                          <StarFullIcon style="width: 24px; height: 24px" v-if="project?.review?.rate > 2" />
                          <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                        </div>
                        <div style="width: 24px; height: 24px">
                          <StarFullIcon style="width: 24px; height: 24px" v-if="project?.review?.rate > 3" />
                          <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                        </div>
                        <div style="width: 24px; height: 24px">
                          <StarFullIcon style="width: 24px; height: 24px" v-if="project?.review?.rate > 4" />
                          <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                        </div>
                      </InlineStack>

                      <Text variant="headingMd" as="p">
                        {{ project?.review?.rate.toFixed(2) }}
                      </Text>
                    </InlineStack>
                  </BlockStack>
                </template>

                <template v-else>
                  <InlineStack gap="200">
                    <InputBtn @click="() => this.$emit('showFinishModal')">Mark as Completed</InputBtn>

                    <Button @click="() => this.$emit('notYet')">Not There Yet</Button>
                  </InlineStack>
                  <InlineStack gap="200" blockAlign="center">
                    <Text as="p" variant="bodySm" tone="subdued">
                      Time Left to Respond
                    </Text>
                    <Text as="p" variant="headingLg">
                      {{ timeLeft }}
                    </Text>
                  </InlineStack>
                </template>
              </InlineStack>
            </template>
          </BlockStack>
        </Card>
      </template>
    </BlockStack>
  </template>
</template>

<style scoped>
.mobile > div > div {
  border-radius: 16px;
}
</style>