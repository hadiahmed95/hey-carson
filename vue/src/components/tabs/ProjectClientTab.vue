<script>
import CheckCircle from "@/components/icons/CheckCircle.vue";
import QuotaCard from "@/components/cards/QuotaCard.vue";
import MobileBanner from "@/components/MobileBanner.vue";
import MobileCard from "@/components/MobileCard.vue";
import UserBox from "@/components/misc/UserBox.vue";
import moment from "moment/moment";
import InputBtn from "@/components/misc/InputBtn.vue";

export default {
  name: "ProjectClientTab",

  props: {
    project: {
      default: () => ({
        status: ''
      }),
      type: Object
    },
    quotas: {
      default: () => [],
      type: Array
    },
    isQuotePaid: {
      default: false,
      type: Boolean
    }
  },

  components: {
    InputBtn,
    UserBox,
    MobileCard,
    MobileBanner,
    QuotaCard,
  },

  data() {
    return {
      CheckCircle,
      isMobile: screen.width <= 760,

      closedBanner: false,
      loading: false,
    }
  },

  computed: {
    bannerClosedInitial() {
      let key = 'expert-project-banner-' + this.project.id + '-initial';
      return localStorage.getItem(key);
    },

    bannerClosedInProgress() {
      let key = 'expert-project-banner-' + this.project.id + '-in_progress';
      return localStorage.getItem(key);
    },

    bannerClosedExpertCompleted() {
      let key = 'expert-project-banner-' + this.project.id + '-expert_completed';
      return localStorage.getItem(key);
    },

    bannerClosedCompleted() {
      let key = 'expert-project-banner-' + this.project.id + '-completed';
      return localStorage.getItem(key);
    },
  },

  methods: {
    projectDescription() {
      return this.project.description.replaceAll("\n", "<br/>")
    },

    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do YYYY')
    },

    closeBanner(value) {
      this.closedBanner = true;
      let key = 'expert-project-banner-' + this.project.id + '-' + value;
      localStorage.setItem(key, '1');
      if (value === 'in_progress') {
        localStorage.setItem('expert-project-banner-' + this.project.id + '-initial', '1');
      }
      if (value === 'expert_completed') {
        localStorage.setItem('expert-project-banner-' + this.project.id + '-initial', '1');
        localStorage.setItem('expert-project-banner-' + this.project.id + '-in_progress', '1');
      }
      if (value === 'completed') {
        localStorage.setItem('expert-project-banner-' + this.project.id + '-initial', '1');
        localStorage.setItem('expert-project-banner-' + this.project.id + '-in_progress', '1');
        localStorage.setItem('expert-project-banner-' + this.project.id + '-completed', '1');
      }
    }
  }
}
</script>

<template>
  <template v-if="isMobile">
    <BlockStack gap="400">

      <MobileBanner v-if="project.status === 'in_progress'" info>
        <template #title>Client accepted your offer!</template>

        <Text as="p">
          Congratulations! The client accepted your offer, and this project is now "In Progress". Be professional, proactive, and open-minded with the client during the project. Good communication and dedication will help you complete this project in the short term.
        </Text>
      </MobileBanner>
      <MobileBanner v-else-if="project.status === 'completed'">
        <template #title>Great news! The client has marked this project as “Completed”.
        </template>

        <Text as="p">
          Congratulations! We're thrilled to see the project completed. We hope your hard work is acknowledged by the client, and that you receive a positive review on your expert profile. Client reviews help build your reputation on the platform, leading to more matches and quality projects.
        </Text>
      </MobileBanner>
      <MobileBanner v-else-if="project.status === 'expert_completed'">
        <template #title>Great news! You marked this project as “Awaiting Approval”..
        </template>

        <Text as="p">
          Congratulations! We're thrilled to see the project completed. We hope your hard work is acknowledged by the client, and that you receive a positive review on your expert profile. Client reviews help build your reputation on the platform, leading to more matches and quality projects.
        </Text>
      </MobileBanner>
      <MobileBanner v-else>
        <template #title>Great news!</template>

        <Text as="p">
          We've matched you with a project request that aligns with your skills. Take a moment to carefully read through the project description, and feel free to start a friendly conversation with the client. Once you're ready, go ahead and send over a suitable project quote. Good luck!
        </Text>
      </MobileBanner>

      <MobileCard padding="600" v-if="project.client">
        <BlockStack gap="400">
          <BlockStack gap="400">
            <UserBox client isShowShopifyPlan isShowBalance :isShowEmail="!['claimed', 'matched', 'pending_match', 'pending_payment'].includes(project.status)" :user="project.client"/>

<!--            <Badge size="large">Local Time: 09:36am</Badge>-->

            <BlockStack gap="100" inlineAlign="end">
              <Text variant="bodySm" as="p" alignment="start" tone="subdued" v-if="project.status === 'claimed'">
                Open for read: {{ formatDate(project.created_at) }}
              </Text>
              <Text variant="bodySm" as="p" alignment="start" tone="subdued" v-else>
                Matched: {{ formatDate(project.created_at) }}
              </Text>

              <Text variant="bodySm" as="p" alignment="start" tone="subdued"
                    v-if="project.status !== 'matched' && project.status !== 'claimed'">
                Last updated on: {{ formatDate(project.created_at) }}
              </Text>
            </BlockStack>
          </BlockStack>

          <template v-if="project.status === 'matched'">
            <Divider />

            <BlockStack gap="400">
              <Text variant="bodyMd" as="p" fontWeight="semibold">
                Project Description
              </Text>

              <Text variant="bodyMd" as="p" v-html="projectDescription()" />
            </BlockStack>

            <Divider />

            <BlockStack gap="200">
              <InputBtn :icon="CheckCircle" @click="() => this.$emit('createOffer')">Create a Project Quote</InputBtn>

              <Button @click="() => this.$emit('releaseProject')" :loading="loading">Release Project</Button>
            </BlockStack>
          </template>

          <template v-if="!isQuotePaid && project.status === 'pending_payment'">
            <Divider />

            <InlineStack gap="200">
              <InputBtn :icon="CheckCircle" @click="() => this.$emit('updateOffer')">Edit Quote Details</InputBtn>
            </InlineStack>
            <Text variant="bodyMd" tone="subdued">You can update the quote details while it's still in pending status, before the client makes a decision.</Text>
          </template>

          <template v-else>
            <template v-if="project.active_assignment">
              <QuotaCard expert v-for="offer in project.active_assignment.offers" :key="offer.id" :quota="offer" />
            </template>
          </template>

          <template v-if="project.status === 'in_progress'">
            <Divider />

            <BlockStack gap="200">
              <InputBtn :icon="CheckCircle" @click="() => this.$emit('markComplete')">Submit for Review</InputBtn>

              <Button @click="() => this.$emit('addScope')">Add to Scope</Button>
            </BlockStack>
          </template>
        </BlockStack>
      </MobileCard>
    </BlockStack>
  </template>
  <template v-else>
  <BlockStack gap="400">
    <template v-if="!closedBanner">
      <Banner v-if="project.status === 'in_progress' && !bannerClosedInProgress"
              @dismiss="closeBanner('in_progress')"
              title="Client accepted your offer!"
              tone="info">
        <Text as="p">
          Congratulations! The client accepted your offer, and this project is now "In Progress". Be professional, proactive, and open-minded with the client during the project. Good communication and dedication will help you complete this project in the short term.
        </Text>
      </Banner>
      <Banner v-else-if="project.status === 'completed' && !bannerClosedCompleted"
              @dismiss="closeBanner('completed')"
              title="Great news! The client has marked this project as “Completed”."
              tone="success">
        <Text as="p">
          Congratulations! We're thrilled to see the project completed. We hope your hard work is acknowledged by the client, and that you receive a positive review on your expert profile. Client reviews help build your reputation on the platform, leading to more matches and quality projects.
        </Text>
      </Banner>
      <Banner v-else-if="project.status === 'expert_completed' && !bannerClosedExpertCompleted"
              @dismiss="closeBanner('expert_completed')"
              title="Great news! You marked this project as “Awaiting Approval”."
              tone="success">
        <Text as="p">
          Congratulations! We're thrilled to see the project completed. We hope your hard work is acknowledged by the client, and that you receive a positive review on your expert profile. Client reviews help build your reputation on the platform, leading to more matches and quality projects.
        </Text>
      </Banner>
      <Banner v-else-if="!bannerClosedInitial"
              @dismiss="closeBanner('initial')"
              title="Great News!"
              tone="success">
        <Text as="p">
          We've matched you with a project request that aligns with your skills. Take a moment to carefully read through the project description, and feel free to start a friendly conversation with the client. Once you're ready, go ahead and send over a suitable project quote. Good luck!
        </Text>
      </Banner>
    </template>

    <Card padding="600">
      <BlockStack gap="400" v-if="project.client">
        <InlineStack align="space-between" blockAlign="center">
          <UserBox client isShowShopifyPlan isShowBalance :isShowEmail="!['claimed', 'matched', 'pending_match', 'pending_payment'].includes(project.status)" :user="project.client" />

          <BlockStack gap="200" inlineAlign="end">
            <Text variant="bodySm" as="p" alignment="start" tone="subdued">
              Matched: {{ formatDate(project.active_assignment.created_at) }}
            </Text>

            <Text variant="bodySm" as="p" alignment="start" tone="subdued"
                  v-if="project.status !== 'matched' && project.status !== 'claimed'">
              Last updated on: {{ formatDate(project.created_at) }}
            </Text>
          </BlockStack>

<!--          <Badge size="large">Local Time: 09:36am</Badge>-->
        </InlineStack>

        <template v-if="project.active_assignment">

          <Divider />

          <template v-if="project.active_assignment.offers.length">
            <QuotaCard expert v-for="offer in project.active_assignment.offers" :key="offer.id" :quota="offer" />
          </template>

          <BlockStack gap="400" v-else>
            <Text variant="bodyMd" as="p" fontWeight="semibold">
              Project Description
            </Text>

            <Text variant="bodyMd" as="p" v-html="projectDescription()" />
          </BlockStack>
        </template>

        <template v-if="project.status === 'matched'">
          <Divider />

          <InlineStack gap="200">
            <InputBtn :icon="CheckCircle" @click="() => this.$emit('createOffer')">Create a Project Quote</InputBtn>

            <Button @click="() => this.$emit('releaseProject')" :loading="loading">Release Project</Button>
          </InlineStack>
        </template>

        <template v-if="!isQuotePaid && project.status === 'pending_payment'">
          <Divider />

          <InlineStack gap="200">
            <InputBtn :icon="CheckCircle" @click="() => this.$emit('updateOffer')">Edit Quote Details</InputBtn>
          </InlineStack>
          <Text variant="bodyMd" tone="subdued">You can update the quote details while it's still in pending status, before the client makes a decision.</Text>
        </template>

        <template v-if="project.status === 'in_progress'">
          <Divider />

          <InlineStack gap="200">
            <InputBtn :icon="CheckCircle" @click="() => this.$emit('markComplete')">Submit for Review</InputBtn>

            <Button @click="() => this.$emit('addScope')">Add to Scope</Button>
          </InlineStack>
        </template>
      </BlockStack>
    </Card>
  </BlockStack>
  </template>
</template>

<style scoped>

</style>