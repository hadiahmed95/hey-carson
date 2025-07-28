<script>
import DoubleCheckIcon from "@/components/icons/DoubleCheckIcon.vue";
import ResendMessageIcon from "@/components/icons/ResendMessageIcon.vue";
import DoubleCheckGrayIcon from "@/components/icons/DoubleCheckGrayIcon.vue";
import moment from "moment";
import EditIcon from "@/components/icons/EditIcon.vue";
import DeleteIcon from "@/components/icons/DeleteIcon.vue";
import ReplyIcon from "@/components/icons/ReplyIcon.vue";
import DownloadIcon from "@/components/icons/AttachmentIcon.vue";
import { QuillEditor } from '@vueup/vue-quill'
import '@/assets/vue-quill.bubble.css';
import AvatarFrame from "@/components/misc/AvatarFrame.vue";
import EmailIcon from "@/components/icons/EmailIcon.vue";

export default {
  name: "TextBox",

  props: {
    userType: {
      default: 'client',
      type: String,
    },
    isReceiverOnline: {
      default: false,
      type: Boolean,
    },
    isReceiverOnlineInChat:{
      default: false,
      type: Boolean,
    },
    message: {
      default: () => ({
        type: 'text',
        content: '',
        time: '',
        userType: 'client',
        userName: '',
        seen: true,
        id: 0,
      }),
      type: Object
    },
    replyTo: {
      default: null,
    },
    edit: {
      default: null,
    },
    failedMessageId:{
      default: 0,
      type: Number
    }
  },
  components: {
    AvatarFrame,
    QuillEditor,
  },
  data() {
    return {
      DoubleCheckIcon,
      ResendMessageIcon,
      DoubleCheckGrayIcon,
      EmailIcon,
      EditIcon,
      DeleteIcon,
      ReplyIcon,
      DownloadIcon,

      replyActive: false,
      editActive: false,
      deleteActive: false,

      isHover: false,
      currentUser: JSON.parse(window.localStorage.getItem('CURRENT_USER')),
      showDeleteModal: false,
      showEditIcon: false,
    }
  },
  computed: {
    isOwnMessage() {
      return this.message.user && this.message.user.id === this.currentUser.id && this.message.type === 'text'
    },
    getBorderClass() {
      let classes = ''
      if (this.edit && this.edit.id === this.message.id) {
        classes += 'message-box-edit '
      }

      if (this.replyTo && this.replyTo.id === this.message.id) {
        classes += 'message-box-reply '
      }

      return classes;
    },
  },

  mounted() {
    this.startMessageEditTimer(this.message.created_at);
  },

  methods: {
    dateFormat(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").fromNow()
    },

    getUrl(file) {
      if (file && file.url) {
        return process.env.VUE_APP_AWS_LINK + file.url;
      } else {
        return "";
      }
    },

    openFile(file) {
      const href = this.getUrl(file);

      return window.open(href, '_blank')
    },

    retry({$event, type, content, id}) {
      this.$emit('retry-clicked', { $event, type, content, id });
    },

    toggleDeleteModal(){
      this.showDeleteModal = !this.showDeleteModal;
      this.$emit('showDeleteModal', this.showDeleteModal);
      this.$emit('deleteMessageId', this.message.id);
    },


    startMessageEditTimer(messageCreatedAt) {
      const editLimit = 20 * 60 * 1000;
      const messageCreationTime = new Date(messageCreatedAt).getTime();
      const timeElapsed = Date.now() - messageCreationTime;

      if (timeElapsed >= editLimit) {
        this.showEditIcon = false;
        return;
      }

      this.showEditIcon = true;
      const hideAfter = editLimit - timeElapsed;

      setTimeout(() => {
        this.showEditIcon = false;
      }, hideAfter);
    },

    isVideo(messageType) {
      return ['webm', 'mpg', 'mp2', 'mpeg', 'mpe', 'mpv', 'ogg', 'mp4', 'm4p', 'm4v', 'avi', 'wmv', 'mov', 'qt', 'flv', 'swf'].includes(messageType)
    },

    isImage(messageType) {
      return ['jpg', 'png', 'gif', 'webp', 'tiff', 'psd', 'raw', 'bmp', 'heif', 'indd', 'jpeg 2000', 'svg', 'ai', 'eps'].includes(messageType)
    },

  }
}
</script>

<template>
  <BlockStack gap="100">
    <Box :padding="null"
         :style="{
           background: !isOwnMessage ? '#f5f5f5' : '',
           opacity: ['sending', 'failed'].includes(message.status) ? 0.6 : 1
         }"
         class="message-box"
         :class="getBorderClass"
         @mouseover="() => isHover = true"
         @mouseleave="() => {isHover = false; showDelete = false}">
      <BlockStack gap="0">
        <Box padding="400"
             v-if="message.reply"
             :style="!isOwnMessage ? 'background: #f5f5f5' : ''" class="message-reply-box">
          <InlineStack align="space-between" blockAlign="center" :wrap="false" gap="200">
            <div>
              <Icon tone="subdued" :source="ReplyIcon" @click="this.$emit('scrollToMessage', this.message.reply_id)"/>
            </div>

            <div>
              <AvatarFrame rounded size="lg" :user="message.reply.user" />
            </div>

            <BlockStack align="start" blockAlign="start" gap="200" style="flex: 1">
              <Text as="p" variant="bodyMd" v-if="message.reply.type === 'text'">
                <QuillEditor theme="bubble" :content="message.reply.content" :readOnly="true" contentType="html"/>
              </Text>

              <div v-else-if="isImage(message.reply.type)">
                <img
                    v-for="(file, index) in message.reply.project_file"
                    :key="index"
                    @click="openFile(file)"
                    :src="getUrl(file)"
                    class="message-box-imgs"
                    :title="file.name"
                    :alt="file.name"
                >
              </div>

              <div v-else-if="isVideo(message.reply.type)">
                <video
                    v-for="(file, index) in message.reply?.project_file"
                    :key="index"
                    class="message-box-video"
                    controls="controls"
                    preload="metadata"
                >
                  {{ file ? file?.name : '' }}
                  <source :src="getUrl(file)" type="video/mp4">
                </video>
              </div>

              <div
                  v-else-if="message.status === 'failed' && message.reply.project_file"
              >
                <Button
                    v-for="(file, index) in message.reply.project_file"
                    :key="index"
                    :icon="DownloadIcon"
                    @click="openFile(file)"
                >
                  {{ file ? file?.name : '' }}
                </Button>
              </div>

              <div v-else>
                <Button
                    v-for="(file, index) in message.reply.project_file"
                    :key="index"
                    :icon="DownloadIcon"
                    @click="openFile(file)"
                >
                  {{ file ? file?.name : '' }}
                </Button>
              </div>
            </BlockStack>

          </InlineStack>
        </Box>

        <Box padding="300">
          <InlineStack align="space-between" :wrap="false" gap="200">
            <div>
              <AvatarFrame rounded size="lg" :user="message.user" />
            </div>

            <BlockStack align="start" blockAlign="start" gap="200" style="flex: 1">
              <InlineStack align="start" gap="100">
                <BlockStack gap="200" style="flex: 1">
                  <Text v-if="message.user" as="h2" variant="headingSm" style="padding: 0 4px; display: flex;">
                    <InlineStack gap="150">
                        <div>
                          {{ message.user?.first_name }} {{ message.user?.last_name }}
                        </div>
                        <Icon v-if="message.sent_from_email"
                            :source="EmailIcon"
                            tone="base"
                        />
                    </InlineStack>

                    <Button v-if="message.status === 'failed'" :icon="ResendMessageIcon" variant="plain" @click="retry(message)" :loading="failedMessageId === message.id" />

                    <Text as="span" variant="bodyXs" tone="subdued" v-if="message.edited">(edited)</Text>
                  </Text>
                  <Text as="p" variant="bodyMd" v-if="message.type === 'text'">
                    <QuillEditor theme="bubble" :content="message.content" :readOnly="true" contentType="html"/>
                    <div style="display: flex;">
                      <img
                          v-for="(file, index) in message.project_file"
                          :key="index"
                          @click="openFile(file)"
                          :src="getUrl(file)"
                          :alt="file.name"
                          :title="file.name"
                          class="message-box-imgs"
                      >
                    </div>
                  </Text>

                  <div v-else-if="isImage(message.type)">
                    <img
                        v-for="(file, index) in message.project_file"
                        :key="index"
                        @click="openFile(file)"
                        :src="getUrl(file)"
                        class="message-box-img"
                        :title="file.name"
                        :alt="file.name"
                    >
                  </div>

                  <div v-else-if="isVideo(message.type)">
                    <video
                        v-for="(file, index) in message.project_file"
                        :key="index"
                        class="message-box-video"
                        controls="controls"
                        preload="metadata"
                    >
                      {{ file ? file?.name : '' }}
                      <source :src="getUrl(file)" type="video/mp4">
                    </video>
                  </div>

                  <div
                      v-else-if="message.status === 'failed' && message.project_file"
                  >
                    <Button
                        v-for="(file, index) in message.project_file"
                        :key="index"
                        :icon="DownloadIcon"
                        @click="openFile(file)"
                    >
                      {{ file ? file?.name : '' }}
                    </Button>
                  </div>

                  <div v-else>
                    <Button
                        v-for="(file, index) in message.project_file"
                        :key="index"
                        :icon="DownloadIcon"
                        @click="openFile(file)"
                    >
                      {{ file?.name }}
                    </Button>
                  </div>

                </BlockStack>
                <BlockStack gap="200" style="min-width: 100px">
                  <InlineStack gap="100" align="end">
                    <div class="relative group">
                      <Text as="p" variant="bodySm" tone="subdued">
                        {{ new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}
                      </Text>
                      <div class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-2 hidden group-hover:block bg-gray-600 text-white text-xs py-1 px-2 rounded w-auto inline-block !w-[8rem]">
                        {{ new Date(message.created_at).toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year:'numeric' }) }}
                      </div>
                    </div>
                    <div v-if="userType === message.user_type && message.seen">
                      <Icon :source="DoubleCheckIcon" tone="info"></Icon> <!-- Blue icon -->
                    </div>
                    <div v-else-if="userType === message.user_type && !isReceiverOnlineInChat && !message.seen">
                      <Icon :source="DoubleCheckGrayIcon"  tone="info"></Icon>
                    </div>
                  </InlineStack>

                  <InlineStack gap="100" align="end" v-if="isHover && message.status !== 'sending'">
                    <div @click="() => this.$emit('replyMessage', message)"
                         @mouseenter="replyActive = true"
                         @mouseleave="replyActive = false">
                      <Tooltip content="Reply">
                        <Icon :tone="replyActive ? 'primary' : 'subdued'" :source="ReplyIcon"/>
                      </Tooltip>
                    </div>

                    <div v-if="isOwnMessage && showEditIcon"
                         @click="() => this.$emit('editMessage', message)"
                         @mouseenter="editActive = true"
                         @mouseleave="editActive = false">
                      <Tooltip content="Edit">
                        <Icon :tone="editActive ? 'primary' : 'subdued'"  :source="EditIcon"/>
                      </Tooltip>
                    </div>

                    <div v-if="isOwnMessage"
                         @click="toggleDeleteModal"
                         @mouseenter="deleteActive = true"
                         @mouseleave="deleteActive = false">
                      <Tooltip content="Delete">
                        <Icon :tone="deleteActive ? 'primary' : 'subdued'" :source="DeleteIcon"/>
                      </Tooltip>
                    </div>
                  </InlineStack>
                </BlockStack>
              </InlineStack>
            </BlockStack>
          </InlineStack>
        </Box>
      </BlockStack>
    </Box>
  </BlockStack>
</template>
<style scoped >
.message-box {
  border: #e5e5e5 1px solid;
  border-radius: 8px;
  word-break: break-word;
}
.message-reply-box {
  border-radius: 8px 8px 0 0;
  border-bottom: #e5e5e5 1px solid;
  background: #ffffff !important;
  cursor: pointer;
}
.message-box-reply {
  border: #303030 1px solid;
}
.message-box-edit {
  border: #909090 1px solid;
}
.message-box:hover {
  background: #f9f9f9 !important;
}
.message-box-img {
  max-width: 500px;
  height: auto;
  object-fit: scale-down;
  cursor: pointer
}
.message-box-imgs {
  margin-right: 10px;
  max-width: 20%;
  border-radius: 5px;
  text-overflow: ellipsis;
  overflow: hidden;
  white-space: nowrap;
}
.message-box-video {
  width: 400px;
}
@media (max-width: 470px) {
  .message-box-img {
    max-width: 250px;
    height: auto;
  }

  .message-box-video {
    width: 250px;
  }
}
</style>
