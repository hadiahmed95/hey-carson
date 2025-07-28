<script>
import ClientLayout from "@/layout/ClientLayout.vue";
import MobileCard from "@/components/MobileCard.vue";
import ImageUploadModal from "@/components/modals/ImageUploadModal.vue";
import AvatarFrame from "@/components/misc/AvatarFrame.vue";
import AddCreditCardModal from "@/components/modals/AddCreditCardModal.vue";
import SavedCardsSelector from "@/components/misc/SavedCardsSelector.vue";
import axios from "axios";
import moment from "moment";
import InputBtn from "@/components/misc/InputBtn.vue";
import {debounce} from "@/directives/debounce";

export default {
  name: "SettingsPage",
  components: {InputBtn, ClientLayout, MobileCard, ImageUploadModal, AvatarFrame, AddCreditCardModal, SavedCardsSelector},

  async mounted() {
    await this.getUser()
  },

  data() {
    return {
      isMobile: screen.width <= 760,

      availableTimezones: [
        {label: '(GMT -05:00) East Coast (US & Canada)', value: 'gmt-5'},
        {label: '(GMT -06:00) Central time (US & Canada)', value: 'gmt-6'}
      ],
      selectedTimezone: "gmt-6",

      options: [
        { "label": "Select an option", "value": null, "disabled": true },
        { "label": "Lite", "value": "Lite" },
        { "label": "Basic", "value": "Basic" },
        { "label": "Grow", "value": "Grow" },
        {"label": "Advanced", "value": "Advanced" },
        {"label":"Plus", "value": "Plus" },
        {"label":"No Shopify Plan", "value": "No Shopify Plan" },
        {"label":"Not Sure", "value": "Not Sure" },
      ],

      user: JSON.parse(window.localStorage.getItem('CURRENT_USER')),
      form: JSON.parse(window.localStorage.getItem('CURRENT_USER')),

      errors: null,
      loading: {
        first_name: false,
        last_name: false,
        email: false,
        url: false,
        company_type: false,
        shopify_plan:false,
        new_messages: false,
        project_notifications: false,
        timezone: false,
      },

      success: null,

      uploadModal: false,
      toggleAddCreditCard: false,
    }
  },

  methods: {
    async getUser() {
      await axios.get('api/client/settings').then(res => {
        window.localStorage.setItem('CURRENT_USER', JSON.stringify(res.data.user));

        this.user = JSON.parse(JSON.stringify(res.data.user));
        this.form = JSON.parse(JSON.stringify(res.data.user));
      }).catch(err => console.log(err))
    },

    changeNotifications: debounce(async function(field, value) {
      this.loading[field] = true;
      let data = {}

      data[field] = value

      await axios.post('api/client/settings', data).then(res => {
        window.localStorage.setItem('CURRENT_USER', JSON.stringify(res.data.user));

        this.user = JSON.parse(JSON.stringify(res.data.user));
        this.form = JSON.parse(JSON.stringify(res.data.user));

        this.errors = null;
        this.loading[field] = false;
      }).catch(err => {
        this.errors = err.response.data.errors;

        this.loading[field] = false;
      })
    }, 200),

    async updateUser(field) {
      if (this.user[field] !== this.form[field]) {
        this.loading[field] = true;
        let data = {}

        data[field] = this.form[field]

        await axios.post('api/client/settings', data).then(res => {
          window.localStorage.setItem('CURRENT_USER', JSON.stringify(res.data.user));

          this.user = JSON.parse(JSON.stringify(res.data.user));
          this.form = JSON.parse(JSON.stringify(res.data.user));

          this.errors = null;
          this.loading[field] = false;
        }).catch(err => {
          this.errors = err.response.data.errors;

          this.loading[field] = false;
        })
      } else {
        return null;
      }
    },

    sendChangePasswordEmail: debounce(async function() {
      this.loading = true;
      await axios.post('api/forgot-password', {email: this.form.email, role: 'client'}).then(res => {
        this.loading = false;

        this.errors = null;
        this.success = res.data.status
      }).catch(err => {
        this.loading = false;

        this.error = err.response.data.error;
      });
    }, 200),

    formatDate(date) {
      return moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").fromNow()
    },

    toggleUploadModal() {
      this.uploadModal = !this.uploadModal;

      this.getUser();
    },

    getPhoto() {
      return process.env.VUE_APP_AWS_LINK + this.user.photo
    },

    setDefaultCard: debounce(async function(card) {
      if (card.default) return
      await axios.post('api/client/settings', {default_card_id: card.id}).then(() => {
        this.getUser();
      })
    }, 200),

    deleteCard: debounce(async function(card) {
      await axios.post('api/client/settings', {remove_card: card.id}).then(() => {
        this.getUser();
      })
    }, 200),
  }
}
</script>

<template>
  <ClientLayout>
    <template v-if="isMobile">
      <BlockStack gap="400" style="padding: 32px 16px">
        <InlineStack align="space-between" blockAlign="center">
          <Text variant="headingLg" as="p">Settings</Text>
        </InlineStack>

        <InlineStack gap="300">
          <BlockStack>
            <Text variant="headingSm" as="p">
              Personal Details
            </Text>

            <Text variant="bodySm" as="p">
              add your profile image and update/manage your company details.
            </Text>
          </BlockStack>

          <MobileCard padding="500">
            <BlockStack gap="500">
              <InlineStack gap="400" blockAlign="center">
                <AvatarFrame rounded size="lg" :user="user" />

                <div>
                  <Button @click="toggleUploadModal">Upload Photo</Button>
                </div>
              </InlineStack>

              <Divider />

              <FormLayoutGroup>
                <TextField
                    label="First Name"
                    autoComplete="off"
                    @focusout="updateUser('first_name')"
                    v-model="form.first_name"
                    :loading="loading.first_name"
                    :error="errors?.first_name?.[0]"
                />

                <TextField
                    label="Last Name"
                    autoComplete="off"
                    @focusout="updateUser('last_name')"
                    v-model="form.last_name"
                    :loading="loading.last_name"
                    :error="errors?.last_name?.[0]"
                />
              </FormLayoutGroup>

              <TextField
                  type="email"
                  label="Email"
                  helpText="Your email is not visible to the experts."
                  autoComplete="off"
                  @focusout="updateUser('email')"
                  v-model="form.email"
                  :loading="loading.email"
                  :error="errors?.email?.[0]"
              />

              <TextField
                  label="Your website"
                  autoComplete="off"
                  @focusout="updateUser('url')"
                  v-model="form.url"
                  :loading="loading.url"
                  :error="errors?.url?.[0]"
              />

              <Select
                  label="Shopify Plan"
                  :options="options"
                  @change="updateUser('shopify_plan')"
                  v-model="form.shopify_plan"
                  :loading="loading.shopify_plan"
                  :error="errors?.shopify_plan?.[0]"
              />
            </BlockStack>
          </MobileCard>
        </InlineStack>

        <Divider borderColor="border" />

        <BlockStack gap="300">
          <BlockStack>
            <Text variant="headingSm" as="p">
              Billing Details
            </Text>

            <Text variant="bodySm" as="p">
              To save time and streamline payments on your projects, add a payment method for faster transactions.
            </Text>
          </BlockStack>

          <MobileCard padding="500">
            <BlockStack gap="800">
              <BlockStack gap="400">
                <Text as="p" variant="bodyMd">
                  At this moment, we support credit cards.
                </Text>

                <InlineStack>
                  <InputBtn @click="() => toggleAddCreditCard = true">Add Payment Method</InputBtn>
                </InlineStack>
              </BlockStack>
            </BlockStack>
          </MobileCard>

          <BlockStack gap="200" v-if="user.saved_cards && user.saved_cards.length">
            <Text as="p" variant="bodyMd">
              Saved credit cards:
            </Text>

            <SavedCardsSelector :cardPerColumn="2" :savedCards="user.saved_cards" @selectCard="setDefaultCard" @deleteCard="deleteCard" />
          </BlockStack>
        </BlockStack>

        <Divider borderColor="border" />

<!--        <BlockStack gap="300">
          <BlockStack>
            <Text variant="headingSm" as="p">
              Timezone
            </Text>

            <Text variant="bodySm" as="p">
              Set your timezone to facilitate smoother communication with experts on projects.
            </Text>
          </BlockStack>

          <MobileCard padding="500">
            <BlockStack gap="500">
              <Select
                  label="Your timezone"
                  :options="availableTimezones"
                  @change="updateUser('timezone')"
                  v-model="form.timezone"
                  :loading="loading.timezone"
                  :error="errors?.timezone?.[0]"
              />
            </BlockStack>
          </MobileCard>
        </BlockStack>

        <Divider borderColor="border" />-->

        <BlockStack gap="300">
          <BlockStack>
            <Text variant="headingSm" as="p">
              Email Preferences
            </Text>

            <Text variant="bodySm" as="p">
              Customize notifications to fit your workflow. Get instant updates or daily summaries for crucial project information.
            </Text>
          </BlockStack>

          <MobileCard padding="500">
            <BlockStack gap="500">
              <InlineStack align="space-between">
                <BlockStack gap="100">
                  <Text variant="bodyMd">Project Notifications</Text>
                  <Text v-if="form.project_notifications === 'instant'" variant="bodyXs" tone="subdued">Instant Notifications</Text>
                  <Text v-else variant="bodyXs" tone="subdued">Daily Summary</Text>
                </BlockStack>

                <ButtonGroup v-if="form.project_notifications === 'instant'">
                  <InputBtn>Instant</InputBtn>
                  <Button @click="changeNotifications('project_notifications', 'daily')" :loading="loading.project_notifications">Daily Summary</Button>
                </ButtonGroup>

                <ButtonGroup v-else>
                  <Button @click="changeNotifications('project_notifications', 'instant')" :loading="loading.project_notifications">Instant</Button>
                  <InputBtn>Daily Summary</InputBtn>
                </ButtonGroup>
              </InlineStack>

              <InlineStack align="space-between">
                <BlockStack gap="100">
                  <Text variant="bodyMd">New Messages</Text>
                  <Text v-if="form.new_messages === 'instant'" variant="bodyXs" tone="subdued">Instant Notifications</Text>
                  <Text v-else variant="bodyXs" tone="subdued">Daily Summary</Text>
                </BlockStack>


                <ButtonGroup v-if="form.new_messages === 'instant'">
                  <InputBtn>Instant</InputBtn>
                  <Button @click="changeNotifications('new_messages', 'daily')" :loading="loading.new_messages">Daily Summary</Button>
                </ButtonGroup>

                <ButtonGroup v-else>
                  <Button @click="changeNotifications('new_messages', 'instant')" :loading="loading.new_messages">Instant</Button>
                  <InputBtn>Daily Summary</InputBtn>
                </ButtonGroup>
              </InlineStack>
            </BlockStack>
          </MobileCard>
        </BlockStack>

        <Divider borderColor="border" />

        <BlockStack gap="300">
          <BlockStack>
            <Text variant="headingSm" as="p">
              Password
            </Text>
          </BlockStack>

          <MobileCard padding="500">
            <BlockStack gap="500">
              <Badge @click="() => success = null" tone="success" size="large" v-if="success" style="height: 36px">
                <InlineStack gap="200" blockAlign="center" align="start" padding="200">
                  <InlineStack align="space-between" style="width: 345px" :wrap="false">
                    <Text variant="bodyLg" as="p" alignment="start" tone="success">
                      {{ success }}
                    </Text>
                  </InlineStack>
                </InlineStack>
              </Badge>

              <Text v-if="!user.password_changed">You have never changed your password.</Text>
              <Text v-else>You last changed password {{ formatDate(user.password_changed)}}.</Text>

              <InlineStack>
                <Button @click="sendChangePasswordEmail">Change your password</Button>
              </InlineStack>
            </BlockStack>
          </MobileCard>
        </BlockStack>

        <Divider borderColor="border" />

        <BlockStack gap="300">
          <BlockStack>
            <Text variant="headingSm" as="p">
              Close Account
            </Text>

            <Text variant="bodySm" as="p">
              By closing your account, you will be logged off all your devices, and all the information associated with your account will be deleted.
            </Text>
          </BlockStack>

          <MobileCard padding="500">
            <BlockStack gap="500">
              <Text>We're sorry to see you go. Our team will receive your request and proceed to close your account.</Text>

              <InlineStack>
                <Button tone="critical">Send Close Request</Button>
              </InlineStack>
            </BlockStack>
          </MobileCard>
        </BlockStack>
      </BlockStack>
    </template>

    <template v-else>
      <Page style="padding-top: 56px; padding-bottom: 48px"
            title="Settings"
            :backAction="{ content: 'Dashboard', onAction: () => {this.$router.push('/client')} }"
      >
        <BlockStack gap="600">
          <LayoutAnnotatedSection
              id="personalDetails"
              title="Personal Details"
              description="add your profile image and update/manage your company details."
          >
            <Card padding="500">
              <BlockStack gap="500">
                <InlineStack gap="400" blockAlign="center">
                  <AvatarFrame rounded size="lg" :user="user" />

                  <div>
                    <Button @click="toggleUploadModal">Upload Photo</Button>
                  </div>
                </InlineStack>

                <Divider />

                <FormLayoutGroup>
                  <TextField
                      label="First Name"
                      autoComplete="off"
                      @focusout="updateUser('first_name')"
                      v-model="form.first_name"
                      :loading="loading.first_name"
                      :error="errors?.first_name?.[0]"
                  />

                  <TextField
                      label="Last Name"
                      autoComplete="off"
                      @focusout="updateUser('last_name')"
                      v-model="form.last_name"
                      :loading="loading.last_name"
                      :error="errors?.last_name?.[0]"
                  />
                </FormLayoutGroup>

                <TextField
                    type="email"
                    label="Email"
                    helpText="Your email is not visible to the experts."
                    autoComplete="off"
                    @focusout="updateUser('email')"
                    v-model="form.email"
                    :loading="loading.email"
                    :error="errors?.email?.[0]"
                />

                <TextField
                    label="Your website"
                    autoComplete="off"
                    @focusout="updateUser('url')"
                    v-model="form.url"
                    :loading="loading.url"
                    :error="errors?.url?.[0]"
                />

                <Select
                    label="Shopify Plan"
                    :options="options"
                    @change="updateUser('shopify_plan')"
                    v-model="form.shopify_plan"
                    :loading="loading.shopify_plan"
                    :error="errors?.shopify_plan?.[0]"
                />
              </BlockStack>
            </Card>
          </LayoutAnnotatedSection>

          <Divider borderColor="border" v-if="false" />

          <LayoutAnnotatedSection v-if="false"
              id="timezone"
              title="Timezone"
              description="Set your timezone to facilitate smoother communication with experts on projects."
          >
            <Card padding="500">
              <BlockStack gap="500">
                <Select
                    label="Your timezone"
                    :options="availableTimezones"
                    @change="updateUser('timezone')"
                    v-model="form.timezone"
                    :loading="loading.timezone"
                    :error="errors?.timezone?.[0]"
                />
              </BlockStack>
            </Card>
          </LayoutAnnotatedSection>

          <Divider borderColor="border" />

          <LayoutAnnotatedSection
              id="payout"
              title="Billing Details  "
              description="To save time and streamline payments on your projects, add a payment method for faster transactions."
          >
            <Card padding="500">
              <BlockStack gap="600">
                <BlockStack gap="400">
                  <Text as="p" variant="bodyMd">
                    <!--                    At this moment, we support credit cards and PayPal.-->
                    At this moment, we support credit cards.
                  </Text>

                  <InlineStack>
                    <InputBtn @click="() => toggleAddCreditCard = true">Add Payment Method</InputBtn>
                  </InlineStack>
                </BlockStack>

                <BlockStack gap="200" v-if="user.saved_cards && user.saved_cards.length">
                  <Text as="p" variant="bodyMd">
                    Saved credit cards:
                  </Text>

                  <SavedCardsSelector :savedCards="user.saved_cards" @selectCard="setDefaultCard" @deleteCard="deleteCard" />
                </BlockStack>
              </BlockStack>
            </Card>
          </LayoutAnnotatedSection>

          <Divider borderColor="border" />

          <LayoutAnnotatedSection
              id="email"
              title="Email Preferences"
              description="Customize notifications to fit your workflow. Get instant updates or daily summaries for crucial project information."
          >
            <Card padding="500">
              <BlockStack gap="500">
                <InlineStack align="space-between">
                  <BlockStack gap="100">
                    <Text variant="bodyMd">Project Notifications</Text>
                    <Text v-if="form.project_notifications === 'instant'" variant="bodyXs" tone="subdued">Instant Notifications</Text>
                    <Text v-else variant="bodyXs" tone="subdued">Daily Summary</Text>
                  </BlockStack>

                  <ButtonGroup v-if="form.project_notifications === 'instant'">
                    <InputBtn>Instant</InputBtn>
                    <Button @click="changeNotifications('project_notifications', 'daily')" :loading="loading.project_notifications">Daily Summary</Button>
                  </ButtonGroup>

                  <ButtonGroup v-else>
                    <Button @click="changeNotifications('project_notifications', 'instant')" :loading="loading.project_notifications">Instant</Button>
                    <InputBtn>Daily Summary</InputBtn>
                  </ButtonGroup>
                </InlineStack>

                <InlineStack align="space-between">
                  <BlockStack gap="100">
                    <Text variant="bodyMd">New Messages</Text>
                    <Text v-if="form.new_messages === 'instant'" variant="bodyXs" tone="subdued">Instant Notifications</Text>
                    <Text v-else variant="bodyXs" tone="subdued">Daily Summary</Text>
                  </BlockStack>


                  <ButtonGroup v-if="form.new_messages === 'instant'">
                    <InputBtn>Instant</InputBtn>
                    <Button @click="changeNotifications('new_messages', 'daily')" :loading="loading.new_messages">Daily Summary</Button>
                  </ButtonGroup>

                  <ButtonGroup v-else>
                    <Button @click="changeNotifications('new_messages', 'instant')" :loading="loading.new_messages">Instant</Button>
                    <InputBtn>Daily Summary</InputBtn>
                  </ButtonGroup>
                </InlineStack>
              </BlockStack>
            </Card>
          </LayoutAnnotatedSection>

          <Divider borderColor="border" />

          <LayoutAnnotatedSection
              id="password"
              title="Password"
          >
            <Card padding="500">
              <BlockStack gap="500">
                <Badge @click="() => success = null" tone="success" size="large" v-if="success" style="height: 36px">
                  <InlineStack gap="200" blockAlign="center" align="start" padding="200">
                    <InlineStack align="space-between" style="width: 345px" :wrap="false">
                      <Text variant="bodyLg" as="p" alignment="start" tone="success">
                        {{ success }}
                      </Text>
                    </InlineStack>
                  </InlineStack>
                </Badge>

                <Text v-if="!user.password_changed">You have never changed your password.</Text>
                <Text v-else>You last changed password {{ formatDate(user.password_changed)}}.</Text>

                <InlineStack>
                  <Button @click="sendChangePasswordEmail">Change your password</Button>
                </InlineStack>
              </BlockStack>
            </Card>
          </LayoutAnnotatedSection>

          <Divider borderColor="border" />

          <LayoutAnnotatedSection
              id="closeAccount"
              title="Close Account "
              description="By closing your account, you will be logged off all your devices, and all the information associated with your account will be deleted."
          >
            <Card padding="500">
              <BlockStack gap="500">
                <Text>We're sorry to see you go. Our team will receive your request and proceed to close your account.</Text>

                <InlineStack>
                  <Button tone="critical">Send Close Request</Button>
                </InlineStack>
              </BlockStack>
            </Card>
          </LayoutAnnotatedSection>
        </BlockStack>
      </Page>
    </template>

    <AddCreditCardModal @close="() => toggleAddCreditCard = false" @saved="() => {toggleAddCreditCard = false; getUser()}" v-if="toggleAddCreditCard" />
    <ImageUploadModal @close="toggleUploadModal" v-if="uploadModal" />
  </ClientLayout>
</template>
