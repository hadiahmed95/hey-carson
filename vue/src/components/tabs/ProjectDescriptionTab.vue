<script>
import AttachmentIcon from "@/components/icons/AttachmentIcon.vue";
import MobileCard from "@/components/MobileCard.vue";
import UserBox from "@/components/misc/UserBox.vue";
import InputBtn from "@/components/misc/InputBtn.vue";

export default {
  name: "ProjectDescriptionTab",

  props: {
    expert: {
      default: false,
      type: Boolean
    },
    project: {
      type: Object,
      default: () => {
      },
    }
  },

  components: {UserBox, MobileCard, InputBtn},

  data() {
    return {
      AttachmentIcon,

      isMobile: screen.width <= 760,
      showAllProjectFiles: false,
      showAllMessageFiles: false,
      FILES_COUNT: 3,
    }
  },

  computed: {
    projectFiles() {
      let files = [];
      if (this.project.project_files && this.project.project_files.length) {
        files.push(...this.project.project_files.filter(file => file.message_id === null));
      }
      return files
    },

    messageFiles() {
      let files = [];
      if (this.project.project_files && this.project.project_files.length) {
        files.push(...this.project.project_files.filter(file => file.message_id !== null));
      }
      return files
    }
  },

  methods: {
    projectDescription() {
      return this.project.description ? this.project.description.replaceAll("\n", "<br/>") : "";
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

    toggleFiles(filesType) {
      if (filesType === 'project') {
        this.showAllProjectFiles = !this.showAllProjectFiles;
      } else if (filesType === 'message') {
        this.showAllMessageFiles = !this.showAllMessageFiles;
      }
    },

    setFilesDisplayHeight(filesType) {
      const totalFiles = filesType.length;

      const mobileConfig = {
        maxHeight: 300,
        fileHeight: 35
      };

      const desktopConfig = {
        filesPerRow: 3,
        fileHeight: 35,
        maxHeight: 140
      };

      const { fileHeight, maxHeight, filesPerRow } = this.isMobile ? mobileConfig : desktopConfig;

      const height = this.isMobile
          ? totalFiles * fileHeight
          : Math.ceil(totalFiles / filesPerRow) * fileHeight;

      return Math.min(height, maxHeight);
    }
  }
}
</script>

<template>
  <MobileCard v-if="isMobile" >
    <BlockStack gap="400">
      <template v-if="expert">
        <InlineStack align="space-between" blockAlign="center">
          <UserBox client :user="project.client" />

          <!--          <Badge size="large">Local Time: 09:36am</Badge>-->
        </InlineStack>

        <Divider />
      </template>

      <Text variant="bodyMd" as="p" fontWeight="semibold">
        Project Description
      </Text>

      <Text variant="bodyMd" as="p" v-html="projectDescription()" />

      <template v-if="projectFiles.length">
        <Divider />

        <BlockStack gap="200">
          <Text fontWeight="semibold">Attached Files</Text>
          <BlockStack v-if="showAllProjectFiles" :style="{ height: setFilesDisplayHeight(projectFiles) + 'px', overflow: 'auto' }">
          <InlineStack gap="200">
              <Button :icon="AttachmentIcon" v-for="file in projectFiles" :key="file.id" @click="openFile(file)">{{ file.name }}</Button>
            </InlineStack>
          </BlockStack>
          <InlineStack v-else-if="!showAllProjectFiles" gap="200">
            <Button :icon="AttachmentIcon" v-for="file in projectFiles.slice(0, FILES_COUNT)" :key="file.id" @click="openFile(file)">{{ file.name }}</Button>
          </InlineStack>
        </BlockStack>
        <InlineStack style="display: flex; justify-content: center;">
          <InputBtn v-if="projectFiles.length > FILES_COUNT" @click="toggleFiles('project')">{{ showAllProjectFiles ? 'See Less' : 'See More' }}</InputBtn>
        </InlineStack>
      </template>

      <template v-if="messageFiles.length">
        <Divider />

        <BlockStack gap="200">
          <Text fontWeight="semibold">Attached Files (Messages)</Text>
          <BlockStack v-if="showAllMessageFiles" :style="{ height: setFilesDisplayHeight(messageFiles) + 'px', overflow: 'auto' }">
            <InlineStack gap="200">
              <Button :icon="AttachmentIcon" v-for="file in messageFiles" :key="file.id" @click="openFile(file)">{{ file.name }}</Button>
            </InlineStack>
          </BlockStack>
          <InlineStack v-else-if="!showAllMessageFiles" gap="200">
            <Button :icon="AttachmentIcon" v-for="file in messageFiles.slice(0, FILES_COUNT)" :key="file.id" @click="openFile(file)">{{ file.name }}</Button>
          </InlineStack>
        </BlockStack>
      </template>
      <InlineStack style="display: flex; justify-content: center;">
        <InputBtn v-if="messageFiles.length > FILES_COUNT" @click="toggleFiles('message')">{{ showAllMessageFiles ? 'See Less' : 'See More' }}</InputBtn>
      </InlineStack>
    </BlockStack>
  </MobileCard>
  <Card v-else padding="800">
    <BlockStack gap="400">
      <template v-if="expert">
        <InlineStack align="space-between" blockAlign="center">
          <UserBox client :user="project.client" />

          <Badge size="large">Local Time: 09:36am</Badge>
        </InlineStack>

        <Divider />
      </template>

      <Text variant="bodyMd" as="p" fontWeight="semibold">
        Project Description
      </Text>
      <Text variant="bodyMd" as="p" v-html="projectDescription()" />

      <template v-if="projectFiles.length">
        <Divider />

        <BlockStack gap="200">
          <Text fontWeight="semibold">Attached Files</Text>
          <BlockStack v-if="showAllProjectFiles" :style="{ height: setFilesDisplayHeight(projectFiles) + 'px', overflow: 'auto' }">
            <InlineStack gap="200">
              <Button :icon="AttachmentIcon" v-for="file in projectFiles" :key="file.id" @click="openFile(file)">{{ file.name }}</Button>
            </InlineStack>
          </BlockStack>
          <InlineStack v-else-if="!showAllProjectFiles" gap="200">
            <Button :icon="AttachmentIcon" v-for="file in projectFiles.slice(0, FILES_COUNT)" :key="file.id" @click="openFile(file)">{{ file.name }}</Button>
          </InlineStack>
        </BlockStack>
        <InlineStack style="display: flex; justify-content: center;">
          <InputBtn v-if="projectFiles.length > FILES_COUNT" @click="toggleFiles('project')">{{ showAllProjectFiles ? 'See Less' : 'See More' }}</InputBtn>
        </InlineStack>
      </template>

      <template v-if="messageFiles.length">
        <Divider />

        <BlockStack gap="200">
          <Text fontWeight="semibold">Attached Files (Messages)</Text>
          <BlockStack v-if="showAllMessageFiles" :style="{ height: setFilesDisplayHeight(messageFiles) + 'px', overflow: 'auto' }">
            <InlineStack gap="200">
              <Button :icon="AttachmentIcon" v-for="file in messageFiles" :key="file.id" @click="openFile(file)">{{ file.name }}</Button>
            </InlineStack>
          </BlockStack>
          <InlineStack v-else-if="!showAllMessageFiles" gap="200">
            <Button :icon="AttachmentIcon" v-for="file in messageFiles.slice(0, FILES_COUNT)" :key="file.id" @click="openFile(file)">{{ file.name }}</Button>
          </InlineStack>
        </BlockStack>
      </template>
      <InlineStack style="display: flex; justify-content: center;">
        <InputBtn v-if="messageFiles.length > FILES_COUNT" @click="toggleFiles('message')">{{ showAllMessageFiles ? 'See Less' : 'See More' }}</InputBtn>
      </InlineStack>
    </BlockStack>
  </Card>
</template>

<style scoped>

</style>
