<script>
import BannerBox from "@/components/chat/BannerBox.vue";
import TextBox from "@/components/chat/TextBox.vue";
import QuoteBox from "@/components/chat/QuoteBox.vue";
import MessageAlertIcon from "@/components/icons/MessageAlertIcon.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import SendIcon from "@/components/icons/SendIcon.vue";
import UploadIcon from "@/components/icons/UploadIcon.vue";
import PlusCircleIcon from "@/components/icons/PlusCircleIcon.vue";
import XIcon from "@/components/icons/XIcon.vue";
import MobileCard from "@/components/MobileCard.vue";
import axios from 'axios';
import {QuillEditor} from '@vueup/vue-quill'
import '@/assets/vue.quill.snow.css';
import UserBox from "@/components/misc/UserBox.vue";
import AttachmentIcon from "@/components/icons/AttachmentIcon.vue";
import InputBtn from "@/components/misc/InputBtn.vue";
import {debounce} from "@/directives/debounce";
import DeleteMessageModal from "@/components/modals/DeleteMessageModal.vue";
import LoaderIcon from '../icons/LoaderIcon.vue'

export default {
  name: "ChatroomTab",

  props: {
    project: {
      default: () => {
      },
      type: Object,
    },
    userType: {
      default: 'client',
      type: String
    },
    userActive: {
      default: true,
      type: Boolean
    }
  },

  components: {
    DeleteMessageModal,
    InputBtn,
    UserBox,
    MobileCard,
    BannerBox,
    TextBox,
    QuoteBox,
    QuillEditor,
    LoaderIcon
  },

  computed: {
    messages() {
      return this.filterMessages(this.chatMessages, this.searchText);
    },

    showButton() {
      if (this.project.status === 'matched') {
        return 'offer'
      } else if (this.project.status === 'in_progress') {
        return 'scope'
      } else {
        return null
      }
    },

    expert() {
      if (this.userType === 'admin') {
        return this.project.active_assignment ? this.project.active_assignment?.expert : null
      } else {
        return null
      }
    },

    lastMessageId() {
      const [[{ id }]] = Object.values(this.messages) || 0;
      return id;
    }
  },

  data() {
    return {
      SearchIcon,
      chatMessages: [],
      CheckCircle,
      PlusCircleIcon,
      AttachmentIcon,
      SendIcon,
      UploadIcon,
      XIcon,
      MessageAlertIcon,
      isMobile: screen.width <= 760,
      searchText: '',
      chatText: '',
      replyTo: null,
      edit: null,
      loading: false,
      uploadingFile: false,
      int: null,
      error: '',
      showError: false,
      toolbar: [
        ['bold', 'italic', 'underline', 'strike'],
        [{'list': 'ordered'}, {'list': 'bullet'}],
        ['blockquote', 'code-block'],
        ['link'],
      ],
      isUserTyping: false,
      isUserTypingTimer: false,
      isReceiverOnline: false,
      isReceiverOnlineInChat: false,
      userFirstName: null,
      userLastName: null,
      userTyping: null,
      receiverId: null,
      receiversForAdmin: [],
      senderId: null,
      newMessageFound: false,
      mockMessage: null,
      failedMessageId: 1,
      failedMessageText: '',
      mockMessagesIds: [],
      showDeleteModal: false,
      deleteMessageId: null,
      enterIsSend: false,
      user: (this.userType === 'client')
          ? this.project?.client
          : (this.userType === 'expert')
              ? this.project.active_assignment?.expert
              : this.getCurrentUser(),
    };
  },

  async mounted() {
    setInterval(() => {
      window.location.reload();
    }, 3300000);

    await this.handleUnsentMessages();
    if (this.userType !== 'admin' && this.project?.id) {
      let url = `api/${this.userType}/projects/${this.project.id}/message/seen`;
      await axios.post(url, {type: this.userType}).then(() => {
        this.edit = null;
        this.replyTo = null;
        this.$emit('updateProject', this.project.id);
      }).catch((err) => {
        console.log(err);
      });
      if (this.userType === 'client') {
        await axios.get('api/client').then(res => {
          this.newMessages = res.data.new_messages;
        });
      } else if (this.userType === 'expert') {
        await axios.get('api/expert').then(res => {
          this.newMessages = res.data.new_messages;
        });
      }
      this.$emit('updateMessagesCount', {
        newMessagesCount: this.newMessages,
      });
    }
    if (this.project.messages && this.project.messages.length) {
      this.chatMessages = [...this.project.messages]
    }

    this.cleanUpExpiredFailedMessagesFromLocalStorage();
    this.loadAllFailedMessages();
    if (this.project.id) {
      this.chatText = this.getChatTextForProject(this.getCurrentUser().id, this.project.id)
      if (this.isCurrentUserAnExpert()) {
        this.receiverId = this.project.client_id;
      } else if (this.isCurrentUserAClient()) {
        this.receiverId = this.project.active_assignment?.expert.id;
      } else if (this.isCurrentUserAnAdmin()) {
        this.receiversForAdmin.push(this.project.client_id);
        this.receiversForAdmin.push(this.project.active_assignment?.expert.id);
      }

      this.joinProjectChatRoom(this.project.id);
      this.checkReceiverOnlineStatus();
    }

    this.toggleFab('none')
  },

  unmounted() {
    clearInterval(this.int)
    this.$emit('updateMessagesCount', {
      newMessagesCount: null,
    });
    if (!this.isCurrentUserAClient()) {
      this.setReceiverOffline();
    }
    this.leavingChatRoom();

    this.toggleFab('contents')
  },

  methods: {
    //Send failed messages from localstorage
    async handleUnsentMessages() {
      try {
        const failedMessages = this.getFailedMessagesFromLocalStorage();

        if (!failedMessages || failedMessages.length === 0) {
          return;
        }

        const ONE_HOUR_IN_MS = 60 * 60 * 1000;
        const currentTime = Date.now();

        for (const message of failedMessages) {
          if (currentTime - message.timestamp <= ONE_HOUR_IN_MS) {
            try {
              const url = `api/${this.userType}/projects/${this.project.id}/message`;

              if (message.content.type === 'text') {
                await axios.post(url, {
                  content: message.content.content,
                  id: message.content.id,
                  is_receiver_online: this.isReceiverOnlineInChat
                });
              } else {
                const form = new FormData();
                form.append('file', message.content.project_file);
                form.append('data', JSON.stringify({
                  id: message.content.id,
                  is_receiver_online: this.isReceiverOnlineInChat,
                }));

                await axios.post(url, form, {
                  headers: {
                    'Content-Type': 'multipart/form-data'
                  }
                });
              }

              this.removeMessageFromLocalStorage(message.content.id);
            } catch (err) {
              console.error('Failed to resend message:', err);
            }
          } else {
            this.removeMessageFromLocalStorage(message.content.id);
          }
        }

        localStorage.setItem('failedMessages' + '-' + this.getCurrentUser().id + '-' + this.project.id, JSON.stringify([]));

      } catch (error) {
        console.error('Error handling unsent messages:', error);
      }
    },

    filterMessages(chatMessages, searchText){
      let filteredMessages = chatMessages;

      if (searchText) {
        filteredMessages = filteredMessages.filter(m => {
          if (m.content) {
            return m.content.toLowerCase().includes(this.searchText.toLowerCase());
          }
          return false;
        });
      }

      const groupedMessages = () => {
        const grouped = {};
        for (const message of filteredMessages) {
          const [date] = message.created_at.split("T");
          if (!grouped[date]) {
            grouped[date] = [];
          }
          grouped[date].push(message);
        }

        const sortedKeys = Object.keys(grouped).sort((a, b) => new Date(b) - new Date(a));

        const sortedGrouped = {};
        for (const key of sortedKeys) {
          sortedGrouped[key] = grouped[key].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        }
        return sortedGrouped;
      };

      return groupedMessages();
    },
    setReceiverOffline() {
      window.Echo.leave(`presence.receiver`)
    },

    leavingChatRoom() {
      window.Echo.leave(`chat.${this.project.id}`)
    },

    isCurrentUserAnExpert() {
      const user = JSON.parse(localStorage.getItem('CURRENT_USER'));
      return user.role_id === 3
    },

    isCurrentUserAClient() {
      const user = JSON.parse(localStorage.getItem('CURRENT_USER'));
      return user.role_id === 2
    },

    isCurrentUserAnAdmin() {
      const user = JSON.parse(localStorage.getItem('CURRENT_USER'));
      return user.role_id === 1
    },

    sendTypingEvent(event) {
      this.handleKeyDown(event);
      window.Echo.join(`chat.${this.project.id}`).whisper("typing", {
        userFirstName: this.getCurrentUser().first_name,
        userLastName: this.getCurrentUser().last_name,
        userRoleId: this.getCurrentUser().role_id
      });
    },

    checkReceiverOnlineStatus() {
      this.setReceiverOffline();
      window.Echo.join(`presence.receiver`)
          .here(users => {
            this.isReceiverOnline = users.some(user => user.id === this.receiverId);
          })
          .joining(user => {
            if (this.receiverId === user.id) {
              this.isReceiverOnline = true;
            }
          })
          .leaving(user => {
            if (this.receiverId === user.id) {
              this.isReceiverOnline = false;
            }
          });
    },

    joinProjectChatRoom(projectId) {
      this.leavingChatRoom();
      window.Echo.join(`chat.${projectId}`)
          .here(users => {
            if (this.isCurrentUserAnAdmin()) {
              this.isReceiverOnlineInChat = this.receiversForAdmin.every(receiverId =>
                  users.some(user => user.id === receiverId)
              );
            } else {
              this.isReceiverOnlineInChat = users.some(user => user.id === this.receiverId);
            }
          })
          .joining(user => {
            if (this.receiverId === user.id) {
              this.isReceiverOnlineInChat = true;
            }
          })
          .leaving(user => {
            if (this.receiverId === user.id) {
              this.isReceiverOnlineInChat = false;
            }
          })
          .listen('.MessageSent', (response) => {
            this.senderId = response.message.user_id;
            this.receivedMessageId = response.unSentMessageId || response.message.id;

            if (response.status === 'created' && response.message.content !== '<p><br></p><br>') {
              this.chatMessages.push(response.message);

              if (this.mockMessagesIds.includes(this.receivedMessageId)) {
                const messageIndex = this.chatMessages.findIndex(message => message.id === this.receivedMessageId);
                if (messageIndex !== -1) {
                  this.chatMessages.splice(messageIndex, 1);
                  this.removeMessageFromLocalStorage(this.receivedMessageId);
                  const idIndex = this.mockMessagesIds.indexOf(this.receivedMessageId);
                  if (idIndex !== -1) {
                    this.mockMessagesIds.splice(idIndex, 1);
                  }
                }
              }
              this.newMessageFound = true;
              if (this.newMessageFound && this.senderId === this.getCurrentUser().id) {
                this.scrollToMessage(response.message.id);
              } else {
                this.$nextTick(() => {
                  this.checkLastMessageInView();
                });
              }

              if (response.message.reply_id) {
                this.clearReply()
              }
            }

            if (response.status === 'updated') {
              const messageIndex = this.chatMessages.findIndex(message => message.id === response.message.id);
              if (messageIndex !== -1) {
                this.chatMessages[messageIndex].content = response.message.content
                this.chatMessages[messageIndex].edited = response.message.edited
                this.clearEdit();
              }
            }

            if (response.status === 'deleted') {
              const messageIndex = this.chatMessages.findIndex(message => message.id === response.message.id);
              if (messageIndex !== -1) {
                this.chatMessages.splice(messageIndex, 1);
              }
            }

            if(response.message.project_file){
              this.$emit('updateProjectMessagesFiles', response.message.project_file);
            }
          })
          .listen('.MessageSeen', (response) => {
            const {seenMessages} = response;
            this.chatMessages.map((message) => {
              if (seenMessages.includes(message.id)) {
                message.seen = true;
              }
            });
          })
          .listen('.PaymentConfirmed', (response) => {
            this.updateOfferStatus(response.payment.offer_id, 'paid');
          })
          .listen('.PaymentDeclined', (response) => {
            this.updateOfferStatus(response.offer.id, 'decline');
          })
          .listenForWhisper("typing", (response) => {
            this.userFirstName = response.userFirstName;
            this.userLastName = response.userLastName;
            this.userTyping = response.userRoleId;
            this.isUserTyping = true;

            if (this.isUserTypingTimer) {
              clearTimeout(this.isUserTypingTimer);
            }

            this.isUserTypingTimer = setTimeout(() => {
              this.isUserTyping = false;
            }, 1000);
          })
    },

    scrollToMessage(originalMessageId) {
      this.$nextTick(() => {
        if (!Object.keys(this.messages).length) return;
        this.$nextTick(() => {
          const originalMessageElement = this.$refs.messageBox.find(messageRef => messageRef.message.id === originalMessageId);
          if (originalMessageElement) {
            originalMessageElement.$el.scrollIntoView({behavior: 'smooth'});
          }
        });
        this.newMessageFound = false;
      });
    },

    checkLastMessageInView() {
      const lastMessageIndex = this.messages.length - 1;
      const lastMessageElement = this.$refs.messageBox[lastMessageIndex];

      if (lastMessageElement) {
        const observer = new IntersectionObserver((entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              this.newMessageFound = false;
            }
          });
        });
        observer.observe(lastMessageElement.$el);
      }
    },

    getCurrentUser() {
      const user = JSON.parse(localStorage.getItem('CURRENT_USER'));
      return (user);
    },

    storeMessageInLocalStorage(content) {
      this.chatText = !content ? '<p><br></p>' : content;
      localStorage.setItem('message-content' + '-' + this.getCurrentUser().id + '-' + this.project.id, this.chatText);
    },

    storeFailedMessageInLocalStorage(content) {
      const failedMessages = this.getFailedMessagesFromLocalStorage();
      const failedMessage = {
        content,
        timestamp: new Date().getTime(),
      };
      failedMessages.push(failedMessage);

      localStorage.setItem('failedMessages' + '-' + this.getCurrentUser().id + '-' + this.project.id, JSON.stringify(failedMessages));
      this.cleanUpExpiredFailedMessagesFromLocalStorage();
    },

    cleanUpExpiredFailedMessagesFromLocalStorage() {
      const ONE_HOUR_IN_MS = 60 * 60 * 1000;
      const expiryTime = Date.now() - ONE_HOUR_IN_MS;

      const failedMessages = this.getFailedMessagesFromLocalStorage();
      const validMessages = failedMessages.filter(msg => msg.timestamp > expiryTime);

      localStorage.setItem('failedMessages' + '-' + this.getCurrentUser().id + '-' + this.project.id, JSON.stringify(validMessages));
    },

    getFailedMessagesFromLocalStorage() {
      return JSON.parse(localStorage.getItem('failedMessages' + '-' + this.getCurrentUser().id + '-' + this.project.id)) || [];
    },

    removeMessageFromLocalStorage(messageId) {

      let failedMessages = this.getFailedMessagesFromLocalStorage();
      const messageInStorage = failedMessages.find(message => message.content.id === messageId);

      if (messageInStorage) {
        failedMessages = failedMessages.filter(message => message.content.id !== messageId);

        localStorage.setItem('failedMessages' + '-' + this.getCurrentUser().id + '-' + this.project.id, JSON.stringify(failedMessages));
      }
    },

    loadAllFailedMessages() {
      const failedMessages = this.getFailedMessagesFromLocalStorage();
      if (failedMessages) {
        failedMessages.forEach(message => {
          this.mockMessage = {
            id: message.content.id,
            content: message.content.content,
            type: message.content.type,
            user: message.content.user,
            created_at: message.content.created_at,
            status: message.content.status
          };
          this.chatMessages.push(this.mockMessage);
          this.mockMessagesIds.push(this.mockMessage.id);
        });

        this.scrollToMessage(this.mockMessage?.id);
      }
    },

    getChatTextForProject(userId, projectId) {
      return localStorage.getItem('message-content' + '-' + userId + '-' + projectId);
    },

    sendMessage: debounce(async function (isFailedMessage = false) {
    if (this.enterIsSend) {
      const newEmptyLine = "<p><br></p>"
      if (this.chatText.endsWith(newEmptyLine))
        this.chatText = this.chatText.slice(0, -newEmptyLine.length)
    }

      let content = !isFailedMessage ? this.chatText : this.failedMessageText

      this.storeMessageInLocalStorage('')

      if (!isFailedMessage && !this.edit && !this.replyTo) {
        this.mockMessage = {
          id: this.messages.length ? this.messages[0].id + 1 : 1,
          content,
          type: "text",
          user: this.getCurrentUser(),
          created_at: new Date().toISOString(),
          status: 'sending'
        };

        this.mockMessagesIds.push(this.mockMessage.id)
        this.chatMessages.unshift(this.mockMessage);
        this.scrollToMessage(this.mockMessage.id);
      }

      let url = `api/${this.userType}/projects/${this.project.id}/message`;

      if (this.edit) {
        url += `/${this.edit.id}`;
      }

      let data = {
        content,
        id: isFailedMessage ? this.failedMessageId : this.mockMessage?.id,
        is_receiver_online: this.isReceiverOnlineInChat
      };

      if (this.replyTo) {
        data.reply_id = this.replyTo.id;
      }

      if (content) {
        this.loading = !isFailedMessage;
        try {
          await axios.post(url, data)

          this.loading = false;
          this.failedMessageId = 0;

        } catch (err) {
          console.log(err, "Error")
          if (err.response) {
            this.error = err.response.data.message === 'edit time expired'
                ? "You can no longer edit this message"
                : err.response.data["Error"] || "Failed to send message.";
          } else if (err.request) {
            this.error = 'Network error!';
          } else {
            this.$emit('updateProject', this.project.id);
            this.error = 'An error occurred while sending message.';
          }
          this.showError = true;
          setTimeout(() => this.showError = false, 5000);
          this.loading = false;
          this.failedMessageId = 0;
          this.mockMessage.status = 'failed';
          if (!isFailedMessage && !this.edit && !this.replyTo) {
            this.storeFailedMessageInLocalStorage(this.mockMessage);
          }
        }
      }
    }, 200),

    async setSeen() {
      if (this.userType !== 'admin') {
        let url = `api/${this.userType}/projects/${this.project.id}/message/seen`;
        await axios.post(url, {type: this.userType}).then(() => {
          this.edit = null;
          this.replyTo = null;
          this.$emit('updateProject', this.project.id);
        }).catch((err) => {
          console.log(err);
        });
      }
    },

    replyMessage(message) {
      this.replyTo = message;

      if (this.edit) {
        this.clearEdit();
      }
    },

    editMessage(message) {
      this.clearReply();
      this.edit = message;
      this.chatText = message.content;
    },

    deleteMessage: debounce(async function (messageId) {
      let url = `api/${this.userType}/projects/${this.project.id}/message/${messageId}`;
      await axios.delete(url).then(() => {
        this.$emit('updateProject', this.project.id);
      }).catch((err) => {
        console.log(err);
      });
    }, 200),

    clearReply() {
      this.loading = true;
      this.replyTo = null;
      this.storeMessageInLocalStorage('')
      setTimeout(() => {
        this.loading = false;
      }, 100);
    },

    clearEdit() {
      this.loading = true;
      this.edit = null;
      this.storeMessageInLocalStorage('')
      setTimeout(() => {
        this.loading = false;
      }, 100);
    },

    async loadFile($event, isFailedMessage = false) {
      const target = $event.target;

      const file = target.files[0];

      if (!isFailedMessage){
        const fileURL = URL.createObjectURL(file);
        this.mockMessage = {
          $event: $event,
          id: this.messages.length ? this.messages[0].id + 1 : 1,
          content: file.name,
          type: file.type.split('/')[0],
          user: this.getCurrentUser(),
          created_at: new Date().toISOString(),
          status: 'sending',
          project_file: {
            url: fileURL,
            name: file.name,
            size: file.size,
            type: file.type,
          }
        };
        this.mockMessagesIds.push(this.mockMessage.id);
        this.chatMessages.unshift(this.mockMessage);
        this.scrollToMessage(this.mockMessage.id);
      }

      const form = new FormData();

      form.append('file', file);
      form.append('data', JSON.stringify({
        id: isFailedMessage ? this.failedMessageId : this.mockMessage.id,
        is_receiver_online: this.isReceiverOnlineInChat,
      }));

      let url = `api/${this.userType}/projects/${this.project.id}/message`;

      this.uploadingFile = !isFailedMessage;
      this.loading = !isFailedMessage;

      try {
        await axios.post(url, form, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });

        if (this.userType !== 'admin') this.setSeen();

        this.loading = false;
        this.uploadingFile = false;
        this.failedMessageId = 0;

      } catch(err) {
        console.log(err);

        if (err.response) {
          this.error = err.response.data["Error"] || "File upload failed.";
        } else if (err.request) {
          this.error = 'Network error!';
        } else {
          this.error = 'An error occurred while preparing the request to upload the file.';
        }
        this.showError = true;
        this.failedMessageId = 0;
        setTimeout(() => this.showError = false, 5000);

        this.loading = false;
        this.uploadingFile = false;
        this.mockMessage.status = 'failed'
      }
    },

    updateOfferStatus(offerId, status) {
      const offerToUpdate = this.chatMessages.find(message => message.offer_id === offerId);

      if (offerToUpdate) {
        offerToUpdate.offer.status = status;
      }
    },

    resendMessage(event) {
      const {$event, type, content, id} = event;
      this.failedMessageId = id;
      if (type === "text") {
        this.failedMessageText = content;
        this.sendMessage(true);
      } else {
        this.loadFile($event, true)
      }
    },

    toggleDeleteModal(){
      this.showDeleteModal = !this.showDeleteModal;
    },

    getDeleteMessageId(messageId) {
      this.deleteMessageId = messageId;
    },

    toggleFab(display) {
      const beacon = document.getElementById('beacon-container');

      if (beacon) {
        beacon.style.display = display;
      }
    },

    isMessageEmpty(message) {
      if (!message) return false;
      const emptyMessageExpression = /^(<p><br><\/p>)+$/;
      return emptyMessageExpression.test(message.trim());
    },

    handleKeyDown(event) {
      if (this.isMessageEmpty(this.chatText) || this.isMobile) return;

      const { isEnterKey, isCtrlEnter, isShiftEnter } = this.getEnterKeyModifiers(event);

      if ((this.enterIsSend && isEnterKey && !isShiftEnter) || isCtrlEnter) {
        this.sendMessage(false);
      }
    },

    getEnterKeyModifiers(event) {
      const isEnterKey = event.key === 'Enter';
      return {
        isEnterKey,
        isCtrlEnter: event.ctrlKey && isEnterKey,
        isShiftEnter: event.shiftKey && isEnterKey
      };
    }
  }
}
</script>

<template>
  <Box v-if="project">
    <MobileCard v-if="isMobile" :padding="null">
      <Box paddingInline="100" paddingBlockStart="200">
        <BlockStack gap="300">
          <BlockStack gap="200">
            <InlineStack align="space-between" :wrap="false">
              <UserBox v-if="project.active_assignment && userType === 'client'" role
                       :user="project.active_assignment?.expert"/>
              <UserBox v-else client isShowBalance :isClientUserOnline="isCurrentUserAnExpert && isReceiverOnline"
                       :user="project.client" />

              <div v-if="false">
                <Badge>Local Time: 09:36am</Badge>
              </div>
            </InlineStack>

            <BlockStack gap="200" blockAlign="center">
              <TextField
                  style="min-width: 220px"
                  :label="null"
                  type="text"
                  v-model="searchText"
                  autoComplete="off"
                  placeholder="Search chatroom ..."
              >
                <template #prefix>
                  <Icon :source="SearchIcon" />
                </template>
              </TextField>
            </BlockStack>
          </BlockStack>

          <Divider />
        </BlockStack>
      </Box>

      <Box paddingInline="100" paddingBlockStart="300">
        <BlockStack gap="300" :reverseOrder="true" style="height: calc(100vh - 530px); min-height: 300px; overflow: auto">
          <template v-if="Object.keys(messages).length">
            <template v-for="(messageGroup, date) in messages" :key="date">

              <template v-for="message in messageGroup" :key="message.id">
                <BannerBox
                    @showModal="() => this.$emit('showFinishModal')"
                    @decline="() => this.$emit('notYet')"
                    v-if="message.type === 'banner'"
                    :message="message"
                    :userType="userType"
                    :expert="expert"
                    ref="messageBox" :ref_key="'messageBox' + message.id"
                />

                <QuoteBox
                    @showModal="() => this.$emit('showQuotaModal', message.offer)"
                    @decline="() => this.$emit('declineOffer', message.offer.id)"
                    v-else-if="message.type === 'offer'"
                    :message="message"
                    :user="user"
                    :userType="userType"
                    ref="messageBox" :ref_key="'messageBox' + message.id"
                />

                <TextBox
                    v-else
                    @replyMessage="replyMessage"
                    @editMessage="editMessage"
                    @showDeleteModal="toggleDeleteModal"
                    @deleteMessageId="getDeleteMessageId"
                    @scrollToMessage="scrollToMessage"
                    @retry-clicked="resendMessage"
                    :message="message"
                    :isReceiverOnline="isReceiverOnline"
                    :isReceiverOnlineInChat="isReceiverOnlineInChat"
                    :replyTo="replyTo"
                    :edit="edit"
                    :userType="userType"
                    :failedMessageId="failedMessageId"
                    ref="messageBox" :ref_key="'messageBox' + message.id"
                />
              </template>
              <div class="group flex justify-center w-[100%]">
                <div class="bg-gray-100 text-center rounded-full py-1 px-2 text-sm w-[7rem]">
                  {{ date }}
                </div>
              </div>
            </template>
          </template>
          <div v-else-if="!Object.keys(messages).length && project.status !== 'available' && !this.searchText" class="btn-spinner">
            <LoaderIcon/>
          </div>

          <DeleteMessageModal
              v-if="showDeleteModal"
              :messageId="deleteMessageId"
              @close="toggleDeleteModal"
              @deleteMessage="deleteMessage"
          />
          <Icon :source="MessageAlertIcon" @click="scrollToMessage(lastMessageId)" v-if="newMessageFound && senderId !== this.getCurrentUser().id" />
        </BlockStack>
      </Box>

      <Box paddingInline="100" paddingBlockEnd="200">
        <BlockStack gap="400" style="margin-top: 12px">
          <Divider />

          <InlineStack gap="200" blockAlign="center" :wrap="false" v-if="replyTo">
            <Text as="p" variant="bodyXs" tone="subdued" style="flex: 1; display: flex; gap: inherit;">
              Reply to
              <Text as="span" fontWeight="semibold">
                <span class="bold" v-html="replyTo.content" />
              </Text>
            </Text>

            <div>
              <Icon tone="subdued" :source="XIcon" @click="clearReply"/>
            </div>
          </InlineStack>
          <InlineStack gap="200" blockAlign="center" :wrap="false" v-if="edit">
            <Text as="p" variant="bodyXs" tone="subdued" style="flex: 1">
              Editing:
            </Text>

            <div>
              <Icon tone="subdued" :source="XIcon" @click="clearEdit"/>
            </div>
          </InlineStack>

          <input style="display: none" type="file" ref="file" @change="loadFile($event, false)" accept="file_extension|audio/*|video/*|image/*|media_type" />

          <BlockStack gap="200">
            <Button :icon="UploadIcon" @click.stop="$refs.file.click()" :loading="uploadingFile" >
              Add Files
            </Button>
            <small v-if="isUserTyping && userTyping !== 1" class="text-gray-600">
              {{ userFirstName + ' ' + userLastName }} Typing...
            </small>
            <div v-if="showError">
              <Text style="color: red" as="p" variant="bodyMd" tone="error">{{ error }}</Text>
            </div>

            <div style="flex-grow: 1" class="fix-border">
              <QuillEditor v-if="!loading" theme="snow"
                           :toolbar="toolbar"
                           :content="chatText"
                           @update:content="storeMessageInLocalStorage"
                           contentType="html"
                           @keydown="sendTypingEvent"/>
              <QuillEditor v-else theme="snow"
                           :readOnly="true"
                           :enabled="false"
                           :toolbar="toolbar"
                           contentType="html"/>

              <Button class="upload-btn-mob" size="large" :icon="AttachmentIcon" variant="plain" @click.stop="$refs.file.click()" :loading="uploadingFile" />
              <Button class="send-btn-mob" size="large" :icon="SendIcon" variant="plain" @click="sendMessage(false)"  :disabled="isMessageEmpty(chatText) || isMessageEmpty(failedMessageText)" :loading="loading" />
            </div>
          </BlockStack>

        </BlockStack>
      </Box>

    </MobileCard>
    <Card v-else :padding="null">
      <Box paddingInline="600" paddingBlockStart="300">
        <BlockStack gap="300">
          <InlineStack align="space-between">
            <UserBox v-if="project.active_assignment && userType === 'client'" role
                     :user="project.active_assignment?.expert"/>
            <UserBox v-else client isShowBalance :isClientUserOnline="isCurrentUserAnExpert && isReceiverOnline"
                     :user="project.client" />

            <InlineStack gap="200" blockAlign="center">
              <TextField
                  style="min-width: 220px"
                  :label="null"
                  type="text"
                  v-model="searchText"
                  autoComplete="off"
                  placeholder="Search chatroom ..."
              >
                <template #prefix>
                  <Icon :source="SearchIcon" />
                </template>
              </TextField>



              <InputBtn v-if="userType === 'expert' && showButton === 'offer'" :icon="CheckCircle"
                        @click="() => this.$emit('showOffer')">Send Offer</InputBtn>
              <InputBtn v-if="userType === 'expert' && showButton === 'scope'" :icon="PlusCircleIcon"
                        @click="() => this.$emit('showScope')">Add to Scope</InputBtn>
            </InlineStack>
          </InlineStack>

          <Divider />
        </BlockStack>
      </Box>

      <Box padding="600" >
        <BlockStack gap="200" :reverseOrder="true" style="height: calc(100vh - 530px); min-height: 300px; overflow: auto" >
          <template v-if="Object.keys(messages).length">
            <template v-for="(messageGroup, date) in messages" :key="date">
              <template v-for="message in messageGroup" :key="message.id">
                <BannerBox
                    @showModal="() => this.$emit('showFinishModal')"
                    @decline="() => this.$emit('notYet')"
                    v-if="message.type === 'banner'"
                    :message="message" :userType="userType" :expert="expert"
                    ref="messageBox" :ref_key="'messageBox' + message.id"/>

                <QuoteBox
                    @showModal="() => this.$emit('showQuotaModal', message.offer)"
                    @decline="() => this.$emit('declineOffer', message.offer.id)"
                    v-else-if="message.type === 'offer'"
                    :message="message" :userType="userType"
                    :user="user"
                    ref="messageBox" :ref_key="'messageBox' + message.id"/>

                <TextBox
                    @replyMessage="replyMessage"
                    @editMessage="editMessage"
                    @showDeleteModal="toggleDeleteModal"
                    @deleteMessageId="getDeleteMessageId"
                    @scrollToMessage="scrollToMessage"
                    @retry-clicked="resendMessage"
                    v-else
                    :message="message"
                    :isReceiverOnline="isReceiverOnline"
                    :isReceiverOnlineInChat="isReceiverOnlineInChat"
                    :replyTo="replyTo"
                    :edit="edit"
                    :userType="userType"
                    :failedMessageId="failedMessageId"
                    ref="messageBox" :ref_key="'messageBox' + message.id"
                />

              </template>
              <div class="group flex justify-center w-[100%]">
                <div class="bg-gray-100 text-center rounded-full py-1 px-2 text-sm w-[7rem]">
                  {{ date }}
                </div>
              </div>
            </template>
          </template>
          <div v-else-if="!Object.keys(messages).length && project.status !== 'available' && !this.searchText" class="btn-spinner">
            <LoaderIcon/>
          </div>
          <DeleteMessageModal
              v-if="showDeleteModal"
              :messageId="deleteMessageId"
              @close="toggleDeleteModal"
              @deleteMessage="deleteMessage"
          />
          <Icon :source="MessageAlertIcon" @click="scrollToMessage(lastMessageId)" v-if="newMessageFound && senderId !== this.getCurrentUser().id" />
        </BlockStack>
      </Box>

      <Box paddingInline="600" :paddingBlockEnd="isUserTyping && userTyping !== 1 ? 0 : 500">
        <BlockStack gap="400">
          <Divider />

          <BlockStack gap="100">
            <div style="display: flex; justify-content: space-between;">
              <div>
                <Text v-if="showError" style="color: red" as="p" variant="bodyMd" tone="error">{{ error }}</Text>
              </div>
              <div>
                <label style="color: gray; display: flex; align-items: center;">
                  <!-- Aligning checkbox and label text on the same line -->
                  <input type="checkbox" v-model="enterIsSend" />
                  Enter is Send
                </label>
              </div>
            </div>
            <InlineStack gap="200" blockAlign="center" :wrap="false" v-if="replyTo">
              <Text as="p" variant="bodyXs" tone="subdued" style="flex: 1; display: flex; gap: inherit;">
                Reply to
                <Text as="span" fontWeight="semibold">
                  <span class="bold" v-html="replyTo.content" />
                </Text>
              </Text>

              <div>
                <Icon tone="subdued" :source="XIcon" @click="clearReply"/>
              </div>
            </InlineStack>
            <InlineStack gap="200" blockAlign="center" :wrap="false" v-if="edit">
              <Text as="p" variant="bodyXs" tone="subdued" style="flex: 1">
                Editing:
              </Text>

              <div>
                <Icon tone="subdued" :source="XIcon" @click="clearEdit"/>
              </div>
            </InlineStack>

            <input style="display: none" type="file" ref="file" @change="loadFile($event, false)" accept="file_extension|audio/*|video/*|image/*|media_type" />

            <InlineStack gap="200" blockAlign="end" :wrap="false">

              <div style="flex-grow: 1" class="fix-border">
                <QuillEditor theme="snow"
                             :toolbar="toolbar"
                             :content="chatText"
                             @update:content="storeMessageInLocalStorage"
                             contentType="html"
                             @keydown="sendTypingEvent"/>

                  <Button class="upload-btn" size="large" :icon="AttachmentIcon" variant="plain" @click.stop="$refs.file.click()" :loading="uploadingFile" />
                  <Button class="send-btn" size="large" :icon="SendIcon" variant="plain" @click="sendMessage(false)" :disabled="isMessageEmpty(chatText) || isMessageEmpty(failedMessageText)" />
              </div>

            </InlineStack>
          </BlockStack>
        </BlockStack>
        <BlockStack>
          <InlineStack blockAlign="end" :wrap="false">
            <div style="flex-grow: 1" class="fix-border">
              <small v-if="isUserTyping && userTyping !== 1" class="text-gray-600">
                {{ userFirstName + ' ' + userLastName }} Typing...
              </small>
            </div>
          </InlineStack>
        </BlockStack>
      </Box>
    </Card>
  </Box>
</template>

<style scoped>
.fix-border.ql-toolbar {
  border-radius: 8px 8px 0 0 !important;
  position: relative;
}
.upload-btn {
  position: absolute;
  bottom: 30px;
  left: 34px;
}
.send-btn {
  position: absolute;
  bottom: 30px;
  right: 34px;
}

.upload-btn-mob {
  position: absolute;
  bottom: 18px;
  left: 14px;
}
.send-btn-mob {
  position: absolute;
  bottom: 18px;
  right: 14px;
}
.btn-spinner {
  position: absolute;
  z-index: 99999;
  display:flex;
  justify-content: center;
  align-items: center;
  height: inherit;
  width: 100%;
}
.btn-spinner svg {
  fill: rgb(179 175 175) !important;
  animation: var(--p-motion-keyframes-spin) var(--p-motion-duration-500) linear infinite;
  width: 30px;
  height: 30px;
}
.fix-border.ql-container {
  border-radius: 0 0 8px 8px !important;
  position: relative;
}
</style>
