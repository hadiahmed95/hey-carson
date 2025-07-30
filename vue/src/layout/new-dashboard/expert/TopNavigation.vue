<script>
import LongLogo from "@/components/logo/LongLogo.vue";
import NotificationIcon from "@/components/icons/NotificationIcon.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import FilterIcon from "@/components/icons/FilterIcon.vue";
import AddIcon from "@/components/icons/AddIcon.vue";
import ProfileIcon from "@/components/icons/ProfileIcon.vue";
import SettingsIcon from "@/components/icons/SettingsIcon.vue";
import PriceListIcon from "@/components/icons/PriceListIcon.vue";
import SendIcon from "@/components/icons/SendIcon.vue";
import QuestionIcon from "@/components/icons/QuestionIcon.vue";
import ExitIcon from "@/components/icons/ExitIcon.vue";
import TeamIcon from "@/components/icons/TeamIcon.vue";
import ChatIcon from "@/components/icons/ChatIcon.vue";
import MenuIcon from "@/components/icons/MenuIcon.vue";
import NoteIcon from "@/components/icons/NoteIcon.vue";
import axios from "axios";
import WithdrawModal from "@/components/modals/WithdrawModal.vue";
import AvatarFrame from "@/components/misc/AvatarFrame.vue";
import NotificationBox from "@/components/misc/Notifications/NotificationBox.vue";
import MessageBox from "@/components/misc/Notifications/MessageBox.vue";
import IconNotifier from "@/components/misc/Notifications/IconNotifier.vue";
import {debounce} from "@/directives/debounce";
import FilesIcon from "@/components/icons/FilesIcon.vue";
import ClipboardIcon from "@/components/icons/ClipboardIcon.vue";
import LoadingCards from "@/components/cards/LoadingCards.vue";


export default {
  name: "TopNavigation",

  props:{
    newMessagesCount: {
      type: Number,
      default: 0
    },
  },

  components: {
    IconNotifier, MessageBox, NotificationBox,
    AvatarFrame,
    LongLogo,
    WithdrawModal,
    LoadingCards
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
      ProfileIcon,
      NoteIcon,
      FilesIcon,
      ClipboardIcon,

      isMobile: screen.width <= 760,

      notifyPopover: false,
      userPopover: false,
      navPopover: false,
      cardLoading: false,

      withdrawModal: false,

      user: JSON.parse(window.localStorage.getItem('CURRENT_USER')),

      profile: null,

      events: [],
      newEvents: 0,
      eventFilter: 'New',

      messages: [],
      newMessagesNotificationCount: this.newMessagesCount,
      messageFilter: 'New',

      totalEarnings: 0,
      totalBalance: 0,
      currentBalance: 0,
      currentLevel: 1,
      withdrawRequest: 0,

      seenActive: false,
    }
  },

  watch: {
    newMessagesCount: {
      handler(count) {
        if ( count !== null && count !== this.newMessagesNotificationCount) {
          this.newMessagesNotificationCount = count;
        }
      },
      immediate: true,
      deep: true,
    },
  },

  async mounted() {
    // await this.getData()
    // window.Echo.private(`notifications.${this.getCurrentUser()}`)
    //   .listen('.NewMessageNotification', (response) => {
    //     if (!response.message.seen){
    //       this.messages.unshift(response.message);
    //       this.newMessagesNotificationCount++;
    //     }
    //   });
  },

  // eslint-disable-next-line
  beforeRouteUpdate(to, from) {
    this.getData();
  },

  methods: {
    getCurrentUser() {
      const user = JSON.parse(localStorage.getItem('CURRENT_USER'));
      return user.id;
    },
    async getData() {
      await axios.get('api/expert').then(res => {
        this.newEvents = res.data.new_events
        this.newMessagesNotificationCount = res.data.new_messages
        this.currentBalance = parseInt(res.data.current_balance) ?? 0
      }).catch(err => {
        console.log(err)
      })
    },

    getStats() {
      this.cardLoading = true
      this.toggleUserPopover()
      axios.get('api/expert/stats').then(res => {
        this.totalEarnings = parseInt(res.data.total_earnings) ?? 0;
        this.totalBalance = parseInt(res.data.total_balance) ?? 0;
        this.currentBalance = parseInt(res.data.current_balance) ?? 0;
        this.currentLevel = parseInt(res.data.current_level) ?? 1;
        localStorage.setItem('expertLevel', this.currentLevel);
        this.withdrawRequest = parseInt(res.data.withdraw_requested) ?? 0;
        this.profile = res.data.profile;

        this.cardLoading = false
      }).catch((err) => {
        console.log(err)
        this.cardLoading = false
      });
    },

    getMessages() {
      this.cardLoading = true
      axios.get('api/expert/messages', {params: {message: this.messageFilter}}).then(res => {
        this.messages = res.data.messages;
        this.cardLoading = false
      }).catch((err) => {
        console.log(err)
        this.cardLoading = false
      });
    },

    getEvents() {
      this.cardLoading = true
      axios.get('api/expert/events', {params: {event: this.eventFilter}}).then(res => {
        this.events = res.data.events;
        this.cardLoading = false
      }).catch((err) => {
        console.log(err)
        this.cardLoading = false
      });
    },

    markNotificationSeen: debounce(async function() {
      if (this.newEvents) {
        await axios.post('api/expert/events').then(() => {
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
        await axios.post('api/expert/events/messages').then(() => {
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

    updateStatus() {
      this.withdrawModal = false;
      this.getData();
    },

    toggleNotifyPopover() {
      this.notifyPopover = !this.notifyPopover
    },

    toggleUserPopover() {
      this.userPopover = !this.userPopover
    },

    toggleCreateProjectModal() {
      this.userPopover = false;
    },

    toggleNavPopover() {
      this.navPopover = !this.navPopover
    },

    goTo(page) {
      this.$router.push(page);
    },

    logout() {
      window.localStorage.removeItem('CURRENT_USER')
      window.localStorage.removeItem('CURRENT_TOKEN')

      delete axios.defaults.headers.common['Authorization'];

      this.$router.push('/expert/login')
    },

    toProject: debounce(async function(data) {
      if (data.eventId) {
        await axios.post('api/expert/events/' + data.eventId).then(() => {
          this.getData();
        }).catch((err) => {
          console.log(err)
        });
      }

      if (data.isAvailable) {
        window.open('/expert/available', '_blank');
      } else {
        window.open('/expert/project/' + data.projectId, '_blank');
      }
    }, 200),

    toggleWithdrawModal() {
      this.userPopover = false
      this.withdrawModal = true
    },

    isRoute(routeName) {
      return this.$route.name === routeName;
    },

  },

  computed: {
    completedProjects() {
      if (this.profile.projects && this.profile.projects.length) {
        return this.profile.projects.filter(a => a.project?.status === 'completed').length
      } else {
        return 0
      }
    },

    matchedProjects() {
      if (this.profile.projects && this.profile.projects.length) {
        return this.profile.projects.length
      } else {
        return 0
      }
    },

    totalReview() {
      if (this.profile.reviews && this.profile.reviews.length) {
        return this.profile.reviews.length
      } else {
        return 0
      }
    },

    expertRating() {
      let rating = 0;

      if (this.profile.reviews && this.profile.reviews.length) {
        this.profile.reviews.forEach(rev => {
          rating += rev.rate;
        })

        return (rating / this.totalReview).toFixed(1)
      } else {
        return rating.toFixed(1)
      }
    },

    earningsToNextLevel() {
      let toNextLvl = "$";

      toNextLvl += this.totalEarnings.toFixed() + ' / $'

      if (this.currentLevel === 1) {
        toNextLvl += '75000.00'
      } else if (this.currentLevel === 2) {
        toNextLvl += '150000.00'
      } else if (this.currentLevel === 3) {
        toNextLvl += '250000.00'
      } else if (this.currentLevel === 4) {
        toNextLvl += '1000000.00'
      }

      return toNextLvl;
    },

    earningProgress() {
      let progress = 0;

      if (this.currentLevel === 1) {
        progress = (this.totalEarnings / 75000) * 100;
      } else if (this.currentLevel === 2) {
        progress = ((this.totalEarnings - 75000) / 75000) * 100;
      } else if (this.currentLevel === 3) {
        progress = ((this.totalEarnings - 150000) / 100000) * 100;
      } else if (this.currentLevel === 4) {
        progress = ((this.totalEarnings - 250000) / 750000) * 100;

        if (progress > 100) {
          progress = 100;
        }
      }

      return progress;
    },
  }
}
</script>

<template>
  <div style="min-height: 100vh; background: #fbfdff">

    <template v-if="isMobile">
      <Box role="menu" color="text-inverse">
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
                  <BlockStack gap="400">
                    <BlockStack gap="050">
                      <Button variant="tertiary"
                              text-align="left"
                              :icon="FilesIcon"
                              full-width
                              @click="goTo('/expert')">
                        My Projects
                      </Button>
                      <Button variant="tertiary"
                              text-align="left"
                              :icon="ClipboardIcon"
                              full-width
                              @click="goTo('/expert/available')">
                        Available Projects
                      </Button>

<!--                      <Button variant="tertiary"-->
<!--                              text-align="left"-->
<!--                              :icon="ChatIcon"-->
<!--                              full-width-->
<!--                              @click="goTo('/expert/questions')">-->
<!--                        Question-->
<!--                      </Button>-->

                      <Button variant="tertiary"
                              text-align="left"
                              :icon="PriceListIcon"
                              full-width
                              @click="goTo('/expert/payouts')">
                        Payouts
                      </Button>

                      <Button variant="tertiary"
                              text-align="left"
                              :icon="ProfileIcon"
                              full-width
                              @click="goTo('/expert/profile')">
                        Profile
                      </Button>

                      <Button variant="tertiary"
                              text-align="left"
                              :icon="SettingsIcon"
                              full-width
                              @click="goTo('/expert/settings')">
                        Settings
                      </Button>
                      <Divider />

                      <Button variant="tertiary"
                              text-align="left"
                              :icon="ExitIcon"
                              full-width
                              @click="logout">
                        Logout
                      </Button>
                    </BlockStack>
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
              <NotificationBox v-for="event in events" :key="event.id" :item="event" userType="Expert" @goToProject="toProject" />
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
                    <AvatarFrame @click="getStats" :user="user" />
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
                        Here’s the status of your expert account.
                      </Text>
                    </BlockStack>

                    <Box background="bg-fill-secondary" padding="300">
                      <BlockStack gap="200">
                        <InlineStack align="space-between">
                          <Text as="p">
                            Balance:
                          </Text>

                          <Text as="p" fontWeight="semibold">
                            ${{ currentBalance.toFixed(2) }}
                          </Text>
                        </InlineStack>

                        <Button full-width @click="openWithdrawModal">Request to Withdraw</Button>
                      </BlockStack>
                    </Box>

                    <Divider />

                    <BlockStack gap="300">
                      <InlineStack align="space-between">
                        <Badge>Level {{ currentLevel }}</Badge>

                        <Text as="p">
                          {{ earningsToNextLevel }}
                        </Text>
                      </InlineStack>

                      <ProgressBar color="primary" size="small" :progress="earningProgress" />
                    </BlockStack>

                    <BlockStack gap="100">
                      <InlineStack align="space-between">
                        <Text as="p">
                          Completed Projects
                        </Text>

                        <Text as="p" alignment="start">
                          {{ completedProjects }}
                        </Text>
                      </InlineStack>

                      <InlineStack align="space-between">
                        <Text as="p">
                          Matched
                        </Text>

                        <Text as="p" alignment="start">
                          {{ matchedProjects }}
                        </Text>
                      </InlineStack>

                      <InlineStack align="space-between">
                        <Text as="p">
                          Total Earnings
                        </Text>

                        <Text as="p" alignment="start">
                          ${{ totalBalance.toFixed(2) }}
                        </Text>
                      </InlineStack>

                      <InlineStack align="space-between">
                        <Text as="p">
                          Withdraw Requested
                        </Text>

                        <Text as="p" alignment="start">
                          ${{ withdrawRequest.toFixed(2) }}
                        </Text>
                      </InlineStack>

                      <InlineStack align="space-between">
                        <Text as="p">
                          Reviews
                        </Text>

                        <Text as="p" alignment="start">
                          {{ totalReview }}
                        </Text>
                      </InlineStack>

                      <InlineStack align="space-between">
                        <Text as="p">
                          Average Rating
                        </Text>

                        <Text as="p" alignment="start">
                          {{ expertRating }}
                        </Text>
                      </InlineStack>
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
      <Box role="menu" color="text-inverse">
        <InlineStack :wrap="false" align="start">
          <BlockStack class="nav-menu-logo"><LongLogo inverse /></BlockStack>
          <BlockStack class="nav-menu-grad" inlineAlign="stretch" align="center" >
          </BlockStack>
          <InlineStack class="nav-menu-user" gap="100" :wrap="false">
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
              <NotificationBox v-for="event in events" :key="event.id" :item="event" userType="Expert" @goToProject="toProject" />
            </IconNotifier>

            <Box class="nav-btn" padding="050" minWidth="32" minHeight="32"  borderRadius="200">
              <Popover
                  :active="userPopover"
                  autofocusTarget="first-node"
                  @close="toggleUserPopover"
                  preferredAlignment="right"
              >
                <template #activator>
                  <InlineStack @click="getStats" blockAlign="center" align="center" gap="200">
                    <Text variant="bodyLg" as="p" alignment="start" style="padding-left: 10px">
                      My profile
                    </Text>

                    <AvatarFrame :user="user" />
                  </InlineStack>
                </template>

                <Card style="width: 300px;" padding="400" >
                  <LoadingCards v-if="cardLoading" />
                  <BlockStack gap="200" v-else>
                    <BlockStack gap="100">
                      <Text variant="bodyLg" as="p" fontWeight="semibold" alignment="start">
                        Welcome back, {{ user.first_name }} {{ user.last_name }}!
                      </Text>

                      <Text as="p" tone="subdued">
                        Here’s the status of your expert account.
                      </Text>
                    </BlockStack>

                    <Box background="bg-fill-secondary" padding="300">
                      <BlockStack gap="200">
                        <InlineStack align="space-between">
                          <Text as="p">
                            Balance:
                          </Text>

                          <Text as="p" fontWeight="semibold">
                            ${{ currentBalance.toFixed(2) }}
                          </Text>
                        </InlineStack>

                        <Button full-width @click="toggleWithdrawModal">Request to Withdraw</Button>
                      </BlockStack>
                    </Box>

                    <Divider />

                    <BlockStack gap="300">
                      <InlineStack align="space-between">
                        <Badge>Level {{ currentLevel }}</Badge>

                        <Text as="p">
                          {{ earningsToNextLevel }}
                        </Text>
                      </InlineStack>

                      <ProgressBar color="primary" size="small" :progress="earningProgress" />
                      </BlockStack>

                    <BlockStack gap="100">
                      <InlineStack align="space-between">
                        <Text as="p">
                          Completed Projects
                        </Text>

                        <Text as="p" alignment="start">
                          {{ completedProjects }}
                        </Text>
                      </InlineStack>

                      <InlineStack align="space-between">
                        <Text as="p">
                          Matched
                        </Text>

                        <Text as="p" alignment="start">
                          {{ matchedProjects }}
                        </Text>
                      </InlineStack>

                      <InlineStack align="space-between">
                        <Text as="p">
                          Total Earnings
                        </Text>

                        <Text as="p" alignment="start">
                          ${{ totalBalance.toFixed(2) }}
                        </Text>
                      </InlineStack>

                      <InlineStack align="space-between">
                        <Text as="p">
                          Withdraw Requested
                        </Text>

                        <Text as="p" alignment="start">
                          ${{ withdrawRequest.toFixed(2) }}
                        </Text>
                      </InlineStack>

                      <InlineStack align="space-between">
                        <Text as="p">
                          Reviews
                        </Text>

                        <Text as="p" alignment="start">
                          {{ totalReview }}
                        </Text>
                      </InlineStack>

                      <InlineStack align="space-between">
                        <Text as="p">
                          Average Rating
                        </Text>

                        <Text as="p" alignment="start">
                          {{ expertRating }}
                        </Text>
                      </InlineStack>
                    </BlockStack>

                    <Divider />

                    <BlockStack gap="050">
                      <Button variant="tertiary" text-align="left" :icon="ProfileIcon" full-width
                              @click="goTo('/expert/profile')">Profile</Button>

                      <Button variant="tertiary" text-align="left" :icon="SettingsIcon" full-width
                              @click="goTo('/expert/settings')">Settings</Button>

                      <Button variant="tertiary" text-align="left" :icon="PriceListIcon" full-width
                              @click="goTo('/expert/payouts')">Payouts</Button>

                      <Button variant="tertiary" text-align="left" :icon="ExitIcon" full-width
                              @click="logout">Logout</Button>
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

    <WithdrawModal v-if="withdrawModal" :currentBalance="currentBalance"
                   @close="() => this.withdrawModal = false" @update="updateStatus"/>
  </div>
</template>

<style scoped>
.nav-menu-logo {
  background: #FFFFFF;
  padding: 14px 32px;
  max-width: fit-content;
  flex-grow: 1;
  border-bottom: rgba(0, 0, 0, 0.13) 1px solid
}
.nav-menu-grad {
  background: #FFFFFF;
  padding: 13px 0 13px 107px;
  width: auto;
  flex-grow: 20;
  border-bottom: rgba(0, 0, 0, 0.13) 1px solid
}

.nav-menu-user {
  background: #FFFFFF;
  padding: 13px 32px;
  min-width: 200px;
  max-width: fit-content;
  flex-grow: 1;
  border-bottom: rgba(0, 0, 0, 0.13) 1px solid;
}

.nav-menu-mob {
  background: #FFFFFF;
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
  background: rgba(0, 0, 0, 0.10);
}

.nav-btn-left:active {
  background: rgba(0, 0, 0, 0.05);
}
</style>
