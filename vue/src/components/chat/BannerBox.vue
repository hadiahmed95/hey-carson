<script>
import AlertCircleIcon from "@/components/icons/AlertCircleIcon.vue";
import CheckIcon from "@/components/icons/CheckIcon.vue";
import AlertDiamondIcon from "@/components/icons/AlertDiamondIcon.vue";
import moment from "moment/moment";
import InputBtn from "@/components/misc/InputBtn.vue";

export default {
  name: "BannerBox",
  components: {InputBtn},

  props: {
    userType: {
      default: 'client',
      type: String,
    },
    message: {
      default: () => {},
      type: Object
    },
    expert: {
      default: () => {},
      type: Object
    }
  },

  data() {
    return {
      AlertCircleIcon,
      CheckIcon,
      AlertDiamondIcon,

    }
  },

  computed: {
    bannerColor() {
      return 'bg-surface-' + this.message.banner.type
    }
  },

  methods: {
    dateFormat(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do, YYYY / HH:mma')
    }
  }
}
</script>

<template>
  <Box :background="bannerColor" border-radius="200" padding="200">
    <InlineStack gap="200" :wrap="false">
      <div>
        <Icon style="color: #00527C" :source="AlertCircleIcon" v-if="message.banner.type === 'info'" />

        <Icon style="color: #0C5132" :source="CheckIcon" v-if="message.banner.type === 'success'" />

        <Icon style="color: #5E4200" :source="AlertCircleIcon" v-if="message.banner.type === 'caution'" />

        <Icon style="color: #EF4D2F" :source="AlertDiamondIcon" v-if="message.banner.type === 'critical'" />
      </div>

      <BlockStack gap="200" style="flex-grow: 1">
        <InlineStack align="space-between">
          <Text as="p" varian="bodySm" style="color: #00527C" v-if="message.banner.type === 'info'">
            <template v-if="message.banner.content_type === 'expertMatched'">
              <template v-if="userType === 'admin'">
                This project is matched with
                <Text as="span" fontWeight="semibold">{{ expert?.first_name }} {{ expert?.last_name}}</Text>
              </template>
              <template v-if="userType === 'expert'">
                We've matched you with a project request that aligns with your skills. Take a moment to carefully read through the project description, and feel free to start a friendly conversation with the client. Once you're ready, go ahead and send over a suitable project quote. Good luck!
              </template>
              <template v-else>
                We've matched you with the best expert for this project type. The expert will be in touch with you shortly to start the conversation.
              </template>
            </template>

            <template v-if="message.banner.content_type === 'teamAdded'">
              {{ userType === message.user_type ?
                'You added' :
                message.user_type === 'client' ?
                    'Client has added ' :
                    'Expert has added ' }}
              <Text as="span" fontWeight="semibold">{{ message.content.fullName }}</Text>
              {{ message.content.role === 'expert' ? ' as an expert' : '' }} to this project
            </template>

            <template v-if="message.banner.content_type === 'expertCompleted'">
              {{ userType === message.user_type ?
                'You have marked this project as ' :
                'The expert has marked this project as ' }}
              <Text as="span" fontWeight="semibold">"Awaiting Approval"</Text>.
              {{ userType === message.user_type ?
                'Wait for the client\'s confirmation.' : userType === 'admin' ? '' :
                'Please confirm if you agree.' }}
            </template>
          </Text>

          <Text as="p" varian="bodySm" style="color: #0C5132" v-if="message.banner.type === 'success'">
            <template v-if="message.banner.content_type === 'clientOffer'">
              {{ userType === message.user_type && userType === 'client' ?
                'You accepted and paid for the project quote. The project is now ' :
                'The client accepted and paid for the project quote. The project is now ' }}
              <Text as="span" fontWeight="semibold">"In Progress"</Text>.
            </template>
            <template v-if="message.banner.content_type === 'clientScope'">
              {{ userType === message.user_type && userType === 'client' ?
                'You approved and paid for the additional project payment.' :
                'The client has approved you scope, and it has been added to the payments.' }}
            </template>
            <template v-if="message.banner.content_type === 'clientCompleted'">
              {{ userType === message.user_type && userType === 'client' ?
                'You marked this project as ' :
                'Great news! The client has marked this project as ' }}
              <Text as="span" fontWeight="semibold">"Completed"</Text>.
            </template>
          </Text>

          <Text as="p" varian="bodySm" style="color: #5E4200" v-if="message.banner.type === 'caution'">
            <template v-if="message.banner.content_type === 'expertClaimed'">
              You've successfully claimed this project. The client will be notified of your assignment. Begin communication with them and send an offer that meets their needs. Good luck!
            </template>
          </Text>

          <Text as="p" varian="bodySm" style="color: #8E1F0B" v-if="message.banner.type === 'critical'">
            <template v-if="message.banner.content_type === 'clientCompleted'">
              {{ userType === message.user_type && userType === 'client' ?
                'You marked project as ' :
                'The client marked project as ' }}
              <Text as="span" fontWeight="semibold">"Not There Yet"</Text>.
              {{  userType === 'admin' ? '' : userType === message.user_type && userType === 'client' ?
                'Keep working and communicating with the expert.' :
                'Keep working and communicating with the client.' }}
            </template>
          </Text>

          <Text as="p" vairan="bodySm" tone="subdued" style="padding-right: 12px" v-show="message.banner.content_type !== 'expertMatched'">
            {{ dateFormat(message.created_at) }}
          </Text>
        </InlineStack>

        <InlineStack gap="200" v-if="userType === 'client' && message.banner.content_type === 'expertCompleted' && !message.content">
          <InputBtn @click="() => this.$emit('showModal')">Mark as Completed</InputBtn>

          <Button @click="() => this.$emit('decline')">Not There Yet</Button>
        </InlineStack>
      </BlockStack>
    </InlineStack>
  </Box>
</template>

<style scoped>

</style>