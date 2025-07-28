<script>
import LongLogo from "@/components/logo/LongLogo.vue";
import NotificationIcon from "@/components/icons/NotificationIcon.vue";
import ChatIcon from "@/components/icons/ChatIcon.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import FilterIcon from "@/components/icons/FilterIcon.vue";
import AddIcon from "@/components/icons/AddIcon.vue";
import SettingsIcon from "@/components/icons/SettingsIcon.vue";
import MenuIcon from "@/components/icons/MenuIcon.vue";
import TeamIcon from "@/components/icons/TeamIcon.vue";
import PriceListIcon from "@/components/icons/PriceListIcon.vue";
import SendIcon from "@/components/icons/SendIcon.vue";
import QuestionIcon from "@/components/icons/QuestionIcon.vue";
import IdeaIcon from "@/components/icons/IdeaIcon.vue";
import ExitIcon from "@/components/icons/ExitIcon.vue";
import FilesIcon from "@/components/icons/FilesIcon.vue";
import axios from "axios";
import InputBtn from "@/components/misc/InputBtn.vue";
import CreateProjectModal from "@/components/modals/CreateProjectModal.vue";
import AvatarFrame from "@/components/misc/AvatarFrame.vue";
import IconNotifier from "@/components/misc/Notifications/IconNotifier.vue";
import MessageBox from "@/components/misc/Notifications/MessageBox.vue";
import NotificationBox from "@/components/misc/Notifications/NotificationBox.vue";
import LoadingCards from "@/components/cards/LoadingCards.vue";
import {debounce} from "@/directives/debounce";

export default {
  name: "ClientLayout",

  props: {
    modal: {
      type: Boolean,
      default: false
    },
    projects: {
      default: () => {
        return [];
      },
      type: Array
    },
    newMessagesCount: {
      type: Number,
      default: 0
    },
  },

  components: {
    AvatarFrame,
    CreateProjectModal,
    LongLogo,
    InputBtn,
    IconNotifier,
    MessageBox,
    NotificationBox,
    LoadingCards,
  },

  data() {
    return {
      NotificationIcon,
      ChatIcon,
      MenuIcon,
      CheckCircle,
      FilterIcon,
      AddIcon,
      SettingsIcon,
      TeamIcon,
      PriceListIcon,
      SendIcon,
      QuestionIcon,
      ExitIcon,
      IdeaIcon,
      FilesIcon,

      userPopover: false,
      navPopover: false,
      cardLoading: false,

      isMobile: screen.width <= 760,

      user: JSON.parse(window.localStorage.getItem('CURRENT_USER')),
      createModal: false,

      events: [],
      newEvents: 0,
      eventFilter: 'New',

      messages: [],
      newMessagesNotificationCount: this.newMessagesCount,
      messageFilter: 'New',

      availableHours: 0,

      seenActive: false,
    }
  },

  watch: {
    newMessagesCount: {
      handler(count) {
        if (count !== null && count !== this.newMessagesNotificationCount) {
          this.newMessagesNotificationCount = count;
        }
      },
      immediate: true,
      deep: true,
    },
  },

  async mounted() {
    await this.getData();
    window.Echo.private(`notifications.${this.getCurrentUser()}`)
      .listen('.NewMessageNotification', (response) => {
        if (!response.message.seen){
          this.messages.unshift(response.message);
          this.newMessagesNotificationCount++;
        }
      });
  },

  // eslint-disable-next-line
  beforeRouteUpdate(to, from) {
    this.getData();
  },

  methods: {
    async switchToNewDashboard() {
      try {
        const response = await axios.get('api/sso/switch-to-new');
        window.location.href = response.data.url;
      } catch (err) {
        console.error('SSO redirect failed:', err);
      }
    },
    getCurrentUser() {
      const user = JSON.parse(localStorage.getItem('CURRENT_USER'));
      return user.id;
    },
    async getData() {
      await axios.get('api/client').then(res => {
        this.newEvents = res.data.new_events;
        this.newMessagesNotificationCount = res.data.new_messages;
        this.availableHours = res.data.hours;
      });
    },

    getMessages() {
      this.cardLoading = true
      axios.get('api/client/messages', {params: {message: this.messageFilter}}).then(res => {
        this.messages = res.data.messages;
        this.cardLoading = false
      }).catch((err) => {
        console.log(err)
        this.cardLoading = false
      });
    },

    getEvents() {
      this.cardLoading = true
      axios.get('api/client/events', {params: {event: this.eventFilter}}).then(res => {
        this.events = res.data.events;
        this.cardLoading = false
      }).catch((err) => {
        console.log(err)
        this.cardLoading = false
      });
    },

    getHours() {
      this.cardLoading = true
      this.toggleUserPopover()
      axios.get('api/client/hours').then(res => {
        this.availableHours = res.data.hours;
        this.cardLoading = false
      }).catch((err) => {
        console.log(err)
        this.cardLoading = false
      });
    },

    markNotificationSeen: debounce(async function() {
      if (this.newEvents) {
        await axios.post('api/client/events').then(() => {
          this.getData()
        }).catch((err) => {
          console.log(err)
        });
      } else {
        return null
      }
    }, 200),

    filterNotifications(filter) {
      this.eventFilter = filter
      this.getData()
    },

    markMessagesSeen: debounce(async function() {
      if (this.newMessagesNotificationCount) {
        await axios.post('api/client/events/messages').then(() => {
          this.getData();
        }).catch((err) => {
          console.log(err)
        });
      } else {
        return null
      }
    }, 200),

    filterMessages(filter) {
      this.messageFilter = filter
      this.getData()
    },

    toggleUserPopover() {
      this.userPopover = !this.userPopover
    },

    toggleNavPopover() {
      this.navPopover = !this.navPopover
    },

    toggleCreateProjectModal() {
      this.userPopover = false;
      this.createModal = true;
    },

    goTo(page) {
      this.$router.push(page);
    },

    toProject: debounce(async function(data) {
      if (data.eventId) {
        await axios.post('api/client/events/' + data.eventId).then(() => {
          this.getData();
        }).catch((err) => {
          console.log(err)
        });
      }

      this.goTo('/client/project/' + data.projectId);
    }, 200),

    loggout() {
      window.localStorage.removeItem('CURRENT_USER')
      window.localStorage.removeItem('CURRENT_TOKEN')

      delete axios.defaults.headers.common['Authorization'];

      this.$router.push('/client/login')
    },

    refreshProjects() {
      this.createModal = false;
      this.$emit('refresh');
    },

    isRoute(routeName) {
      return this.$route.name === routeName;
    }
  }
}
</script>

<template>
  <div style="min-height: 100vh; background: #fbfdff">
    <template v-if="isMobile">
      <Box class="nav-menu" role="menu" color="text-inverse">
        <InlineStack class="nav-menu-mob" align="space-between" block-align="center">
          <InlineStack >
            <Box padding="150" style="width: 54px">
              <Popover
                  :active="navPopover"
                  autofocusTarget="first-node"
                  @close="toggleNavPopover"
                  preferredAlignment="right"
              >
                <template #activator>
                  <Icon @click="toggleNavPopover" :source="MenuIcon"/>
                </template>

                <Card style="width: 300px;" padding="400" >
                  <BlockStack gap="050">
                    <Button variant="tertiary"
                            text-align="left"
                            full-width
                            :icon="FilesIcon"
                            @click="goTo('/client')" >
                      Projects
                    </Button>

                    <Button variant="tertiary"
                            text-align="left"
                            full-width
                            :icon="TeamIcon"
                            @click="goTo('/client/team')" >
                      My Team <Text variant="bodyMd" as="span" tone="subdued">(Coming Soon)</Text>
                    </Button>

                    <Button variant="tertiary"
                            text-align="left"
                            full-width
                            :icon="IdeaIcon"
                            @click="goTo('/client/project-ideas')" >
                      Project Ideas <Text variant="bodyMd" as="span" tone="subdued">(Coming Soon)</Text>
                    </Button>

<!--                    <Button variant="tertiary"-->
<!--                            text-align="left"-->
<!--                            full-width-->
<!--                            :icon="ChatIcon"-->
<!--                            @click="goTo('/client/questions')" >-->
<!--                      Questions-->
<!--                    </Button>-->

                    <Button variant="tertiary"
                            text-align="left"
                            :icon="PriceListIcon"
                            full-width
                            @click="goTo('/client/transactions')" >
                      Transactions
                    </Button>

                    <Button variant="tertiary"
                            text-align="left"
                            :icon="SettingsIcon"
                            full-width
                            @click="goTo('/client/settings')" >
                      Settings
                    </Button>

                    <Divider />

                    <Button variant="tertiary"
                            text-align="left"
                            :icon="ExitIcon"
                            full-width
                            @click="loggout">
                      Logout
                    </Button>
                  </BlockStack>
                </Card>
              </Popover>

            </Box>
          </InlineStack>

          <InlineStack gap="200" style="padding: 0 16px">
            <IconNotifier :icon="ChatIcon"
                          :newCount="newMessagesNotificationCount"
                          :totalCount="messages.length"
                          :isLoading="cardLoading"
                          @click="getMessages"
                          windowText="Latest Messages"
                          emptyText="Your conversations with experts will show here."
                          @markSeen="markMessagesSeen"
                          @filterItems="filterMessages" >
              <MessageBox v-for="message in messages" :key="message.id" :item="message" @goToProject="toProject" />
            </IconNotifier>

            <IconNotifier :icon="NotificationIcon"
                          :newCount="newEvents"
                          :totalCount="events.length"
                          :isLoading="cardLoading"
                          @click="getEvents"
                          windowText="Notifications"
                          emptyText="Alerts about your store and account will show here."
                          @markSeen="markNotificationSeen"
                          @filterItems="filterNotifications" >
              <NotificationBox v-for="event in events" :key="event.id" :item="event" @goToProject="toProject" />
            </IconNotifier>

            <Box class="nav-btn" minWidth="32" minHeight="32"  borderRadius="200">
              <Popover
                  :active="userPopover"
                  autofocusTarget="first-node"
                  @close="toggleUserPopover"
                  preferredAlignment="right"
              >
                <template #activator>
                  <Box padding="050">
                    <AvatarFrame @click="getHours" :user="user" />
                  </Box>
                </template>

                <Card style="width: 300px;" padding="400" >
                  <LoadingCards v-if="cardLoading" />
                  <BlockStack gap="400" v-else>
                    <BlockStack gap="100">
                      <Text variant="bodyLg" as="p" fontWeight="semibold" alignment="start">
                        Welcome back, {{ user.first_name }} {{ user.last_name }}!
                      </Text>

                      <Text as="p" tone="subdued">
                        The solution to your Shopify problems is just a few clicks away. The best Shopify experts are just around the corner.
                      </Text>
                    </BlockStack>

                    <BlockStack gap="100">
                      <InlineStack align="space-between">
                        <Text as="p">
                          Prepaid Hours
                        </Text>

                        <Button  variant="plain" @click="() => this.$router.push('/client/pricing')">
                          See Our Pricing
                        </Button>
                      </InlineStack>
                      <Box background="bg-fill-secondary" padding="300">
                        <BlockStack gap="200">
                          <Text as="p" fontWeight="semibold">
                            {{ availableHours ?? 0 }} Prepaid Hours
                          </Text>

                          <Divider />

                          <Text as="p" variant="bodyXs" tone="subdued">
                            Prepaid hours are valid for multiple projects.
                          </Text>
                        </BlockStack>
                      </Box>
                    </BlockStack>

                    <BlockStack gap="200">
                      <Button full-width @click="() => this.$router.push('/client/pricing')">Buy Prepaid Hours</Button>

                      <InputBtn :icon="AddIcon" @click="() => this.createModal = true">New Project</InputBtn>
                    </BlockStack>
                  </BlockStack>
                </Card>
              </Popover>
            </Box>
          </InlineStack>
        </InlineStack>
      </Box>
    </template>
    <template v-else>
      <Box id="access-old-site" style="padding: 10px 32px; background: #E2F1FB; color: #212c41; font-weight: 600">
        Need to access your old HeyCarson account? <a target="_blank" href="https://dashboard.heycarson.com">Login here.</a>
      </Box>
      <Box class="nav-menu" role="menu" color="text-inverse">
        <InlineStack :wrap="false" align="start">
          <BlockStack class="nav-menu-logo"><LongLogo inverse /></BlockStack>
          <BlockStack class="nav-menu-grad" inlineAlign="stretch" align="center">
            <InlineStack align="space-between">
              <InlineStack gap="200">
                <Box class="nav-btn-left" :class="isRoute('client-dashboard') ? 'active-btn' : ''" borderRadius="200" @click="goTo('/client')">
                  <Text variant="bodyLg" as="p" alignment="start">
                    My Projects
                  </Text>
                </Box>
<!--                <Box class="nav-btn-left" :class="isRoute('client-project-ideas') ? 'active-btn' : ''" borderRadius="200" @click="goTo('/client/project-ideas')">-->
<!--                  <Text variant="bodyLg" as="p" alignment="start">-->
<!--                    Project Ideas-->
<!--                  </Text>-->
<!--                </Box>-->
                <Box class="nav-btn-left" :class="isRoute('client-project-ideas') ? 'active-btn' : ''" borderRadius="200" @click="goTo('/client/pricing')">
                  <Text variant="bodyLg" as="p" alignment="start">
                    Buy Prepaid Hours
                  </Text>
                </Box>
<!--                <Box class="nav-btn-left" :class="isRoute('client-questions') ? 'active-btn' : ''" borderRadius="200" @click="goTo('/client/questions')">-->
<!--                  <Text variant="bodyLg" as="p" alignment="start">-->
<!--                    Questions-->
<!--                  </Text>-->
<!--                </Box>-->
              </InlineStack>
              <Box style="padding: 6px 0;">
                <InlineStack align="center" gap="100">
                  <Tooltip borderRadius="300" tone="success" preferredPosition = "below" content="Buy discounted pre-paid packs of 25 hours, 50 hours, and 75 hours from $80/hour. These are treated as express hours and can be used on multiple projects, with any freelancer in our network.">
                    <Text variant="bodyLg" as="p" alignment="start" style="color: #1F2125">
                      <Text as="span" tone="subdued">Balance:</Text>
                      {{ availableHours ?? 0 }} Prepaid Hours
                    </Text>
                  </Tooltip>
                  <Box style="cursor: pointer; display: flex; align-items: center;" @click="goTo('/client/pricing')">
                    <Icon :source="AddIcon" style="color: black;" />
                  </Box>
                </InlineStack>
              </Box>
            </InlineStack>
          </BlockStack>
          <InlineStack class="nav-menu-user" gap="100" :wrap="false">
            <InputBtn :icon="AddIcon" size="large" @click="() => this.createModal = true">New Project</InputBtn>

            <IconNotifier :icon="ChatIcon"
                          :newCount="newMessagesNotificationCount"
                          :totalCount="messages.length"
                          :isLoading="cardLoading"
                          @click="getMessages"
                          windowText="Latest Messages"
                          emptyText="Your conversations with experts will show here."
                          @markSeen="markMessagesSeen"
                          @filterItems="filterMessages" >
              <MessageBox v-for="message in messages" :key="message.id" :item="message" @goToProject="toProject" />
            </IconNotifier>

            <IconNotifier :icon="NotificationIcon"
                          :newCount="newEvents"
                          :totalCount="events.length"
                          :isLoading="cardLoading"
                          @click="getEvents"
                          windowText="Notifications"
                          emptyText="Alerts about your store and account will show here."
                          @markSeen="markNotificationSeen"
                          @filterItems="filterNotifications" >
              <NotificationBox v-for="event in events" :key="event.id" :item="event" @goToProject="toProject" />
            </IconNotifier>

            <Box class="nav-btn" padding="050" minWidth="32" minHeight="32"  borderRadius="200">
              <Popover
                  :active="userPopover"
                  autofocusTarget="first-node"
                  @close="toggleUserPopover"
                  preferredAlignment="right"
              >
                <template #activator>
                  <InlineStack @click="getHours" blockAlign="center" align="center" gap="200">
                    <Text variant="bodyLg" as="p" alignment="start" style="padding-left: 10px">
                      My profile
                    </Text>
                    <AvatarFrame :user="user" />
                  </InlineStack>
                </template>

                <Card style="width: 300px;" padding="400" >
                  <LoadingCards v-if="cardLoading" />
                  <BlockStack gap="400" v-else>
                    <BlockStack gap="100">
                      <Text variant="bodyLg" as="p" fontWeight="semibold" alignment="start">
                        Welcome back, {{ user.first_name }} {{ user.last_name }}!
                      </Text>
                    </BlockStack>

                    <BlockStack gap="100">
                      <InlineStack align="space-between">
                        <Text as="p">
                          Prepaid Hours
                        </Text>

                        <Button  variant="plain" @click="goTo('/client/pricing')">
                          See Our Pricing
                        </Button>
                      </InlineStack>
                      <Box background="bg-fill-secondary" padding="300">
                        <BlockStack gap="200">
                          <Text as="p" fontWeight="semibold">
                            {{ availableHours ?? 0 }} Hours
                          </Text>

                          <Divider />

                          <Text as="p" variant="bodyXs" tone="subdued">
                            Prepaid hours are valid for multiple projects.
                          </Text>
                        </BlockStack>
                      </Box>
                    </BlockStack>

                    <BlockStack gap="200">
                      <Button class="prepaid-hours-button" full-width @click="() => this.$router.push('/client/pricing')">Buy Prepaid Hours</Button>

                      <InputBtn :icon="AddIcon" @click="toggleCreateProjectModal">New Project</InputBtn>
                    </BlockStack>

                    <Divider />

                    <BlockStack gap="050">
                      <Button variant="tertiary" text-align="left" full-width
                              @click="() => switchToNewDashboard()">Switch to New Dashboard</Button>

                      <Button variant="tertiary" text-align="left" :icon="SettingsIcon" full-width
                              @click="() => this.$router.push('/client/settings')">Settings</Button>

                      <Button variant="tertiary" text-align="left" :icon="PriceListIcon" full-width
                              @click="() => this.$router.push('/client/transactions')">Transactions</Button>

                      <Button variant="tertiary" text-align="left" :icon="TeamIcon" full-width
                              @click="() => this.$router.push('/client/team')">My Team</Button>

                      <Button variant="tertiary" text-align="left" :icon="ExitIcon" full-width
                              @click="loggout">Logout</Button>
                    </BlockStack>
                  </BlockStack>
                </Card>
              </Popover>

            </Box>
          </InlineStack>
        </InlineStack>
      </Box>
    </template>

    <slot></slot>

    <CreateProjectModal v-show="createModal" @createdProject="refreshProjects" @close="() => this.createModal = false"/>
  </div>
</template>

<style scoped>
.nav-menu-logo {
  //background: #FFFFFF;
  background: #ffffff;
  border-bottom: solid 1px #0000001A;
  padding: 14px 32px;
  max-width: fit-content;
  flex-grow: 1;
  border-bottom: rgba(0, 0, 0, 0.13) 1px solid
}
.nav-menu-grad {
  //background: #FFFFFF;
  background: #ffffff;
  border-bottom: solid 1px #0000001A;
  padding: 13px 0 13px 107px;
  width: auto;
  flex-grow: 20;
  border-bottom: rgba(0, 0, 0, 0.13) 1px solid
}

.nav-menu-user {
  //background: #FFFFFF;
  background: #ffffff;
  border-bottom: solid 1px #0000001A;
  padding: 13px 32px;
  min-width: 200px;
  max-width: fit-content;
  flex-grow: 1;
  border-bottom: rgba(0, 0, 0, 0.13) 1px solid;
}

.nav-menu-mob {
  //background: #FFFFFF;
  background: #ffffff;
  border-bottom: solid 1px #0000001A;
  height: 54px;
  border-bottom: rgba(0, 0, 0, 0.13) 1px solid;
}

.nav-btn {
  background: #FFFFFF;
  color: #1F2125 !important;
  box-shadow: rgba(0, 0, 0, 0.13) 1px 0 0 0 inset, rgba(0, 0, 0, 0.13) -1px 0 0 0 inset, rgba(0, 0, 0, 0.17) 0 -1px 0 0 inset, rgba(204, 204, 204, 0.5) 0 1px 0 0 inset;
}

.nav-btn:hover {
  cursor: pointer;
  background: #0000000A;
}

.nav-btn:active {
  background: #00000005;
}

.active-btn {
  background: rgba(0, 0, 0, 0.05);
}

.nav-btn-left {
  color: #1F2125 !important;
  cursor: pointer;
  padding: 6px 8px;
}

.nav-btn-left:hover {
  cursor: pointer;
  background: #F2F2F2;
}

.nav-btn-left:active {
  background: #F2F2F2AA;
}

.prepaid-hours-button {
  background: #ACE46F !important;
  &:hover {
    background: #8CC84B !important;
  }
}
</style>
