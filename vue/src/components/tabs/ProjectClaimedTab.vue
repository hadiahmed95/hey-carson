<script>
import MobileBanner from "@/components/MobileBanner.vue";
import MobileCard from "@/components/MobileCard.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import moment from "moment";
import UserBox from "@/components/misc/UserBox.vue";
import AttachmentIcon from "@/components/icons/AttachmentIcon.vue";
import InputBtn from "@/components/misc/InputBtn.vue";

export default {
  name: "ProjectClaimedTab",

  props: {
    project: {
      type: Object,
      default: () => {}
    }
  },

  components: {
    InputBtn,
    UserBox,
    MobileCard,
    MobileBanner,
  },

  data() {
    return {
      CheckCircle,
      AttachmentIcon,

      isMobile: screen.width <= 760,

      duration: moment.duration(300000, 'milliseconds')
    }
  },

  created() {
    let claimTime = moment(this.project.active_assignment.created_at, "YYYY-MM-DDTHH:mm:ss.SSSSZ").add(5, 'minutes');
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
        this.$emit('releaseProject')
        return '00:00'
      }
    },

    projectFiles() {
      let files = [];
      if (this.project.project_files && this.project.project_files.length) {
        files.push(...this.project.project_files.filter(file => file.message_id === null));
      }
      return files
    },
  },

  methods: {
    toggleClaimModal() {
      this.$emit('claimedProject')
    },

    projectDescription() {
      return this.project.description.replaceAll("\n", "<br/>")
    },

    encodeS3URI(filename) {
      const encoding = {
        '+': "%2B",
        '!': "%21",
        '"': "%22",
        '#': "%23",
        '$': "%24",
        '&': "%26",
        "'": "%27",
        '(': "%28",
        ')': "%29",
        '*': "%2A",
        ',': "%2C",
        ':': "%3A",
        ';': "%3B",
        '=': "%3D",
        '?': "%3F",
        '@': "%40",
      };

      return encodeURI(filename) // Do the standard url encoding
          .replace(
              /(\+|!|"|#|\$|&|'|\(|\)|\*|\+|,|:|;|=|\?|@)/img,
              function (match) {
                return encoding[match];
              }
          );

    },

    openFile(file) {
      const href = process.env.VUE_APP_AWS_LINK
          + (file.url.includes(file.name) ? this.encodeS3URI(file.url) : file.url)

      return window.open(href, '_blank')
    },
  }
}
</script>

<template>
  <BlockStack gap="400">

    <template v-if="isMobile">
      <MobileBanner warning>
        <template #title>You're reading this project.</template>

        <Text as="p">
          You've got 5 minutes to go through the project description carefully and let us know if you're up for it. If you think you've got the skills for the job, go ahead and claim the project. If not, no worries! Just release it, and we'll find someone else who can help out.
        </Text>
      </MobileBanner>

      <MobileCard padding="600">
        <BlockStack gap="400">
          <InlineStack align="space-between" blockAlign="center">
            <UserBox client isShowClientUrl :user="project.client" />

            <!--            <Badge size="large">Local Time: 09:36am</Badge>-->
          </InlineStack>

          <Divider />

          <BlockStack gap="400">
            <Text variant="bodyMd" as="p" fontWeight="semibold">
              Project Description
            </Text>

            <Text variant="bodyMd" as="p" v-html="projectDescription()" />
          </BlockStack>

          <Divider />

          <BlockStack gap="200">
            <InlineStack align="space-between">
              <InputBtn :icon="CheckCircle" @click="toggleClaimModal">Claim Project</InputBtn>
              <Button @click="() => this.$emit('releaseProject')">Release Project</Button>
            </InlineStack>

            <InlineStack align="space-between">
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
    </template>
    <template v-else>

      <Banner
          title="You're reading this project."
          tone="warning"
          @dismiss="() => {}" >
        <Text as="p">
          You've got 5 minutes to go through the project description carefully and let us know if you're up for it. If you think you've got the skills for the job, go ahead and claim the project. If not, no worries! Just release it, and we'll find someone else who can help out.
        </Text>
      </Banner>

      <Card padding="600">
        <BlockStack gap="400">
          <InlineStack align="space-between" blockAlign="center">
            <UserBox client isShowClientUrl :user="project.client" />

<!--            <Badge size="large">Local Time: 09:36am</Badge>-->
          </InlineStack>

          <Divider />

          <BlockStack gap="400">
            <Text variant="bodyMd" as="p" fontWeight="semibold">
              Project Description
            </Text>

            <Text variant="bodyMd" as="p" v-html="projectDescription()" />
          </BlockStack>

          <template v-if="projectFiles.length">
            <Divider />

            <BlockStack gap="200">
              <Text fontWeight="semibold">Attached Files</Text>

              <InlineStack gap="200">
                <Button :icon="AttachmentIcon" v-for="file in projectFiles" :key="file.id" @click="openFile(file)">{{ file.name }}</Button>
              </InlineStack>
            </BlockStack>
          </template>

          <Divider />

          <InlineStack align="space-between" blockAlign="center">
            <InlineStack gap="200">
              <InputBtn :icon="CheckCircle" @click="toggleClaimModal">Claim Project</InputBtn>

              <Button @click="() => this.$emit('releaseProject')">Release Project</Button>
            </InlineStack>

            <InlineStack gap="200" blockAlign="center">
              <Text as="p" variant="bodySm" tone="subdued">
                Time Left to Respond
              </Text>
              <Text as="p" variant="headingLg">
                {{ timeLeft }}
              </Text>
            </InlineStack>
          </InlineStack>
        </BlockStack>
      </Card>
    </template>
  </BlockStack>
</template>

<style scoped>

</style>
