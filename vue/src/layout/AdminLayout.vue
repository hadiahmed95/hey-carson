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
import axios from "axios";

export default {
  name: "AdminLayout",

  components: {
    LongLogo,
  },

  data() {
    return {
      NotificationIcon,
      CheckCircle,
      FilterIcon,
      AddIcon,
      ProfileIcon,
      SettingsIcon,
      TeamIcon,
      PriceListIcon,
      SendIcon,
      QuestionIcon,
      ExitIcon,

      notifyPopover: false,
      userPopover: false,

      user: JSON.parse(window.localStorage.getItem('CURRENT_USER')),
    }
  },

  methods: {
    toggleNotifyPopover() {
      this.notifyPopover = !this.notifyPopover
    },

    toggleUserPopover() {
      this.userPopover = !this.userPopover
    },

    goTo(page) {
      this.$router.push(page);
    },


    loggout() {
      window.localStorage.removeItem('CURRENT_USER')
      window.localStorage.removeItem('CURRENT_TOKEN')

      delete axios.defaults.headers.common['Authorization'];

      this.$router.push('/admin/login')
    },

    isRoute(routeName) {
      return this.$route.name === routeName;
    }
  },

}
</script>

<template>
  <div style="min-height: 100vh; background: #fbfdff">
    <Box class="nav-menu" role="menu" color="text-inverse">
      <InlineStack :wrap="false" align="start">
        <BlockStack class="nav-menu-logo"><LongLogo inverse /></BlockStack>
        <BlockStack class="nav-menu-grad" inlineAlign="stretch" align="center">
          <InlineStack align="space-between">

            <InlineStack gap="200">
              <Box class="nav-btn-left"
                   :class="isRoute('admin') ? 'active-btn' : ''"
                   borderRadius="200" @click="goTo('/admin')">
                <Text variant="bodyLg" as="p" alignment="start">
                  Dashboard
                </Text>
              </Box>

              <Box class="nav-btn-left"
                   :class="isRoute('admin-projects') ? 'active-btn' : ''"
                   borderRadius="200" @click="goTo('/admin/projects')">
                <Text variant="bodyLg" as="p" alignment="start">
                  Projects
                </Text>
              </Box>

              <Box class="nav-btn-left"
                   :class="isRoute('admin-clients') ? 'active-btn' : ''"
                   borderRadius="200" @click="goTo('/admin/clients')">
                <Text variant="bodyLg" as="p" alignment="start">
                  Clients
                </Text>
              </Box>

              <Box class="nav-btn-left"
                   :class="isRoute('admin-experts') ? 'active-btn' : ''"
                   borderRadius="200" @click="goTo('/admin/experts')">
                <Text variant="bodyLg" as="p" alignment="start">
                  Experts
                </Text>
              </Box>

              <Box class="nav-btn-left"
                   :class="isRoute('admin-transactions') ? 'active-btn' : ''"
                   borderRadius="200" @click="goTo('/admin/transactions')">
                <Text variant="bodyLg" as="p" alignment="start">
                  Transactions
                </Text>
              </Box>

              <Box class="nav-btn-left"
                   :class="isRoute('admin-payouts') ? 'active-btn' : ''"
                   borderRadius="200" @click="goTo('/admin/payouts')">
                <Text variant="bodyLg" as="p" alignment="start">
                  Payouts
                </Text>
              </Box>

              <Box class="nav-btn-left" borderRadius="200" @click="goTo('/admin/questions')">
                <Text variant="bodyLg" as="p" alignment="start">
                  Questions
                </Text>
              </Box>
            </InlineStack>

          </InlineStack>
        </BlockStack>
        <InlineStack class="nav-menu-user" gap="100" :wrap="false">
          <Box class="nav-btn" minWidth="32" minHeight="32"  borderRadius="200">
            <Popover
                :active="notifyPopover"
                autofocusTarget="first-node"
                @close="toggleNotifyPopover"
                preferredAlignment="right"
            >
              <template #activator>
                <Box padding="150">
                  <Icon @click="toggleNotifyPopover" :source="NotificationIcon"/>
                </Box>
              </template>

              <Card style="width: 380px;" padding="400" >
                <BlockStack gap="400">
                  <InlineStack align="space-between">
                    <Text variant="bodyLg" as="p" fontWeight="semibold" alignment="start">
                      Notifications
                    </Text>

                    <InlineStack gap="400">
                      <Icon :source="FilterIcon" tone="subdued"/>

                      <Icon :source="CheckCircle" tone="subdued"/>
                    </InlineStack>
                  </InlineStack>

                  <Box background="bg-fill-secondary" padding="400">
                    <Text as="p" tone="subdued">
                      Alerts about your store and account will show here.
                    </Text>
                  </Box>
                </BlockStack>
              </Card>
            </Popover>
          </Box>
          <Box class="nav-btn" padding="050" minWidth="32" minHeight="32"  borderRadius="200">
            <Popover
                :active="userPopover"
                autofocusTarget="first-node"
                @close="toggleUserPopover"
                preferredAlignment="right"
            >
              <template #activator>
                <InlineStack @click="toggleUserPopover" blockAlign="center" align="center" gap="200">
                  <Text variant="bodyLg" as="p" alignment="start" style="padding-left: 10px">
                    My profile
                  </Text>
                  <Avatar customer name="Farrah"/>
                </InlineStack>
              </template>

              <Card style="width: 300px;" padding="400" >
                <BlockStack gap="400">
                  <BlockStack gap="100">
                    <Text variant="bodyLg" as="p" fontWeight="semibold" alignment="start">
                      Welcome back, {{ user.first_name }} {{ user.last_name }}!
                    </Text>
                  </BlockStack>

                  <Divider />

                  <BlockStack gap="050">
                    <Button variant="tertiary" text-align="left" :icon="SettingsIcon" full-width
                            @click="() => {this.$router.push('/admin/settings')}">Settings</Button>

                    <Button variant="tertiary" text-align="left" :icon="ExitIcon" full-width @click="loggout">Logout</Button>
                  </BlockStack>
                </BlockStack>
              </Card>
            </Popover>

          </Box>
        </InlineStack>
      </InlineStack>
    </Box>

    <slot></slot>
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
