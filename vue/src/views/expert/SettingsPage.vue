<script>
import ExpertLayout from "@/layout/ExpertLayout.vue";
import MobileCard from "@/components/MobileCard.vue";
import ImageUploadModal from "@/components/modals/ImageUploadModal.vue";
import axios from "axios";
import moment from "moment";
import AvatarFrame from "@/components/misc/AvatarFrame.vue";
import InputBtn from "@/components/misc/InputBtn.vue";
import {debounce} from "@/directives/debounce";

export default {
  name: "SettingsPage",
  components: {InputBtn, AvatarFrame, ExpertLayout, MobileCard, ImageUploadModal},

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

      selectOptions: {
        role: [
          { "label": "Designer", "value": "Designer" },
          { "label": "Frontend Developer", "value": "Frontend Developer" },
          { "label": "Backend Developer", "value": "Backend Developer" },
        ],
        experience: [
          {
            "label": "Less than a year",
            "value": "Less than a year"
          },
          {
            "label": "1-3 years",
            "value": "1-3 years"
          },
          {
            "label": "3-5 years",
            "value": "3-5 years"
          },
          {
            "label": "5-10 years",
            "value": "5-10 years"
          },
          {
            "label": "10+ years",
            "value": "10+ years"
          },
        ],
        availability: [
          {
            "label": "10-20 hours per week",
            "value": "10-20 hours per week"
          },
          {
            "label": "20-30 hours per week",
            "value": "20-30 hours per week"
          },
          {
            "label": "30-40 hours per week",
            "value": "30-40 hours per week"
          },
          {
            "label": "40+ hours per week",
            "value": "40+ hours per week"
          },
        ],
        english_level: [
          {
            "label": "Basic",
            "value": "Basic"
          },
          {
            "label": "Conversational",
            "value": "Conversational"
          },
          {
            "label": "Fluent",
            "value": "Fluent"
          },
          {
            "label": "Native",
            "value": "Native"
          },
        ]
      },

      user: JSON.parse(window.localStorage.getItem('CURRENT_USER')),
      form: JSON.parse(window.localStorage.getItem('CURRENT_USER')),

      errors: null,
      loading: {
        first_name: false,
        last_name: false,
        email: false,
        url: false,
        country: false,
        about: false,
        role: false,
        experience: false,
        availability: false,
        eng_level: false,
        hourly_rate: false,
        new_messages: false,
        project_notifications: false,
        timezone: false,
        wise_email: false,
        paypal_email: false
      },

      success: null,

      uploadModal: false,
    }
  },

  methods: {
    async getUser() {
      await axios.get('api/expert/settings').then(res => {
        window.localStorage.setItem('CURRENT_USER', JSON.stringify(res.data.user));

        this.user = JSON.parse(JSON.stringify(res.data.user));
        this.form = JSON.parse(JSON.stringify(res.data.user));
      }).catch(err => console.log(err))
    },

    changeNotifications: debounce(async function(field, value) {
      this.loading[field] = true;
      let data = {}

      data[field] = value

      await axios.post('api/expert/settings', data).then(res => {
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

    async updateProfile(field) {
      if (this.user.profile[field] !== this.form.profile[field]) {
        this.loading[field] = true;
        let profile = {}

        profile[field] = this.form.profile[field]

        await axios.post('api/expert/settings', {profile: profile}).then(res => {
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

    async updateUser(field) {
      if (this.user[field] !== this.form[field]) {
        this.loading[field] = true;
        let data = {}

        data[field] = this.form[field]

        await axios.post('api/expert/settings', data).then(res => {
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
      await axios.post('api/forgot-password', {email: this.form.email, role: 'expert'}).then(res => {
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
    }
  }
}
</script>

<template>
  <ExpertLayout>
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
                  label="Country of residence"
                  autoComplete="off"
                  @focusout="updateProfile('country')"
                  v-model="form.profile.country"
                  :loading="loading.country"
                  :error="errors?.country?.[0]"
              />

              <TextField
                  label="LinkedIn/Portfolio website URL"
                  autoComplete="off"
                  @focusout="updateUser('url')"
                  v-model="form.url"
                  :loading="loading.url"
                  :error="errors?.url?.[0]"
              />

              <Select
                  label="Role"
                  :options="selectOptions.role"
                  @change="updateProfile('role')"
                  v-model="form.profile.role"
                  :loading="loading.role"
                  :error="errors?.role?.[0]"
              />

              <Select
                  label="Years of experience"
                  :options="selectOptions.experience"
                  @change="updateProfile('experience')"
                  v-model="form.profile.experience"
                  :loading="loading.experience"
                  :error="errors?.experience?.[0]"
              />

              <Select
                  label="What is your English level?"
                  :options="selectOptions.english_level"
                  @change="updateProfile('eng_level')"
                  v-model="form.profile.eng_level"
                  :loading="loading.eng_level"
                  :error="errors?.eng_level?.[0]"
              />

              <Select
                  label="What is your availability per week?"
                  helpText="Freelancers typically need to commit to at least 10 hours per week."
                  :options="selectOptions.availability"
                  @change="updateProfile('availability')"
                  v-model="form.profile.availability"
                  :loading="loading.availability"
                  :error="errors?.availability?.[0]"
              />

              <TextField
                  label="Expected hourly rate"
                  autoComplete="off"
                  @focusout="updateProfile('hourly_rate')"
                  v-model="form.profile.hourly_rate"
                  :loading="loading.hourly_rate"
                  :error="errors?.hourly_rate?.[0]"
              />

              <TextField
                  label="Professional bio"
                  autoComplete="off"
                  :multiline="5"
                  @focusout="updateProfile('about')"
                  v-model="form.profile.about"
                  :loading="loading.about"
                  :error="errors?.about?.[0]"
              />
            </BlockStack>
          </MobileCard>
        </InlineStack>

        <Divider borderColor="border" />

        <BlockStack gap="300"
        >
          <BlockStack>
            <Text variant="headingSm" as="p">
              Payout Options
            </Text>
          </BlockStack>

          <MobileCard padding="500">
            <BlockStack gap="800">
              <BlockStack gap="400">
                <InlineStack gap="400" blockAlign="center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                    <rect width="40" height="40" rx="20" fill="#0070E0"/>
                    <path d="M17.5817 13.6493C17.4426 13.6495 17.3082 13.6973 17.2025 13.7843C17.0968 13.8713 17.0268 13.9917 17.005 14.1239L16.0469 19.9739C16.0915 19.7006 16.3361 19.4993 16.6235 19.4993H19.4312C22.257 19.4993 24.6548 17.5149 25.0929 14.8258C25.1255 14.625 25.144 14.4224 25.1482 14.2192C24.4301 13.8568 23.5865 13.6493 22.6622 13.6493H17.5817Z" fill="white" fill-opacity="0.6"/>
                    <path d="M25.1455 14.2194C25.1413 14.4226 25.1228 14.6253 25.0902 14.826C24.6521 17.5152 22.2541 19.4995 19.4286 19.4995H16.6209C16.3337 19.4995 16.0889 19.7006 16.0442 19.9741L15.1632 25.3497L14.6114 28.7221C14.6006 28.7873 14.6046 28.8539 14.6232 28.9174C14.6418 28.9809 14.6745 29.0398 14.719 29.0899C14.7635 29.1401 14.8187 29.1804 14.881 29.208C14.9432 29.2356 15.011 29.2499 15.0795 29.2499H18.127C18.2661 29.2498 18.4005 29.2019 18.5062 29.1149C18.6119 29.0279 18.6819 28.9075 18.7037 28.7753L19.5064 23.8742C19.5282 23.7419 19.5982 23.6215 19.704 23.5345C19.8098 23.4475 19.9444 23.3997 20.0835 23.3997H21.8778C24.7036 23.3997 27.1013 21.4153 27.5395 18.7262C27.8506 16.8176 26.852 15.0808 25.1455 14.2194Z" fill="white" fill-opacity="0.4"/>
                    <path d="M14.1185 9.75C13.8313 9.75 13.5866 9.95111 13.5419 10.2241L11.1504 24.8221C11.1051 25.0992 11.3275 25.3499 11.619 25.3499H15.1654L16.0459 19.9742L17.0041 14.1242C17.0258 13.992 17.0959 13.8716 17.2015 13.7846C17.3072 13.6976 17.4417 13.6498 17.5807 13.6497H22.6613C23.5858 13.6497 24.4291 13.8573 25.1473 14.2196C25.1964 11.7713 23.0978 9.75 20.2125 9.75H14.1185Z" fill="white"/>
                  </svg>

                  <BlockStack>
                    <Text as="p" fontWeight="semibold" variant="bodyMd">
                      Connect your PayPal account
                    </Text>

                    <Text as="p" tone="subdued" variant="bodyMd">
                      Allows you to request payouts on your PayPal account.
                    </Text>
                  </BlockStack>
                </InlineStack>

                <TextField
                    type="email"
                    label="Enter email associated with your PayPal account"
                    helpText="The participant is responsible for PayPal fees."
                    autoComplete="off"
                    @focusout="updateProfile('paypal_email')"
                    v-model="form.profile.paypal_email"
                    :loading="loading.paypal_email"
                    :error="errors?.paypal_email?.[0]"
                />
              </BlockStack>

              <BlockStack gap="400">
                <InlineStack gap="400" blockAlign="center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                    <rect width="40" height="40" rx="20" fill="#88EA5C"/>
                    <path d="M21.6901 16.5354H23.6157L22.6469 23.4765H20.7214L21.6901 16.5354ZM19.2625 16.5354L17.9631 20.5339L17.396 16.5354H16.0493L14.3482 20.5221L14.1356 16.5354H12.2691L12.9188 23.4765H14.4664L16.3801 19.0864L17.0535 23.4765H18.5773L21.0936 16.5354H19.2625ZM35.4289 20.5695H30.8571C30.8807 21.4713 31.4183 22.0646 32.2097 22.0646C32.8063 22.0646 33.2789 21.7442 33.6451 21.1332L35.1884 21.838C34.6581 22.8874 33.5405 23.5714 32.1625 23.5714C30.2842 23.5714 29.0379 22.3018 29.0379 20.2611C29.0379 18.0185 30.5027 16.4286 32.57 16.4286C34.3893 16.4286 35.5352 17.6626 35.5352 19.5847C35.5352 19.9051 35.4997 20.2255 35.4289 20.5695ZM33.7159 19.2406C33.7159 18.4338 33.267 17.9236 32.5464 17.9236C31.8259 17.9236 31.1879 18.4575 31.0225 19.2406H33.7159ZM6.25087 18.6272L4.28516 20.9344H7.79485L8.18941 19.8464H6.68559L7.60466 18.7791L7.60762 18.7506L7.00987 17.7177H9.69798L7.61411 23.4765H9.03997L11.5562 16.5354H5.05538L6.25028 18.6272H6.25087ZM26.7462 17.9236C27.4254 17.9236 28.0208 18.2902 28.5405 18.9191L28.8135 16.9625C28.3291 16.6333 27.6735 16.4286 26.8052 16.4286C25.0805 16.4286 24.1118 17.4431 24.1118 18.7304C24.1118 19.6233 24.608 20.1691 25.4231 20.5221L25.8129 20.7001C26.5394 21.0115 26.7344 21.1658 26.7344 21.4951C26.7344 21.8243 26.4066 22.0527 25.9074 22.0527C25.0834 22.0556 24.416 21.6315 23.914 20.9077L23.6357 22.9017C24.2075 23.3395 24.9405 23.5714 25.9074 23.5714C27.5465 23.5714 28.5536 22.6222 28.5536 21.3052C28.5536 20.4094 28.1578 19.8339 27.1596 19.383L26.7344 19.1813C26.1436 18.9173 25.9429 18.772 25.9429 18.4813C25.9429 18.1668 26.2175 17.9236 26.7462 17.9236Z" fill="#163300"/>
                  </svg>

                  <BlockStack>
                    <Text as="p" fontWeight="semibold" variant="bodyMd">
                      Connect your Wise account
                    </Text>

                    <Text as="p" tone="subdued" variant="bodyMd">
                      Allows you to request payouts on your Wise account.
                    </Text>
                  </BlockStack>
                </InlineStack>

                <TextField
                    type="email"
                    label="Enter email associated with your Wise account"
                    helpText="The participant is responsible for Wise fees."
                    autoComplete="off"
                    @focusout="updateProfile('wise_email')"
                    v-model="form.profile.wise_email"
                    :loading="loading.wise_email"
                    :error="errors?.wise_email?.[0]"
                />
              </BlockStack>
            </BlockStack>
          </MobileCard>
        </BlockStack>

<!--        <Divider borderColor="border" />

        <BlockStack gap="300">
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
        </BlockStack>-->

        <Divider borderColor="border" />

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
            :backAction="{ content: 'Dashboard', onAction: () => {this.$router.push('/expert')} }"
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
                    label="Country of residence"
                    autoComplete="off"
                    @focusout="updateProfile('country')"
                    v-model="form.profile.country"
                    :loading="loading.country"
                    :error="errors?.country?.[0]"
                />

                <TextField
                    label="LinkedIn/Portfolio website URL"
                    autoComplete="off"
                    @focusout="updateUser('url')"
                    v-model="form.url"
                    :loading="loading.url"
                    :error="errors?.url?.[0]"
                />

                <Select
                    label="Role"
                    :options="selectOptions.role"
                    @change="updateProfile('role')"
                    v-model="form.profile.role"
                    :loading="loading.role"
                    :error="errors?.role?.[0]"
                />

                <Select
                    label="Years of experience"
                    :options="selectOptions.experience"
                    @change="updateProfile('experience')"
                    v-model="form.profile.experience"
                    :loading="loading.experience"
                    :error="errors?.experience?.[0]"
                />

                <Select
                    label="What is your English level?"
                    :options="selectOptions.english_level"
                    @change="updateProfile('eng_level')"
                    v-model="form.profile.eng_level"
                    :loading="loading.eng_level"
                    :error="errors?.eng_level?.[0]"
                />

                <Select
                    label="What is your availability per week?"
                    helpText="Freelancers typically need to commit to at least 10 hours per week."
                    :options="selectOptions.availability"
                    @change="updateProfile('availability')"
                    v-model="form.profile.availability"
                    :loading="loading.availability"
                    :error="errors?.availability?.[0]"
                />

                <TextField
                    label="Expected hourly rate"
                    autoComplete="off"
                    @focusout="updateProfile('hourly_rate')"
                    v-model="form.profile.hourly_rate"
                    :loading="loading.hourly_rate"
                    :error="errors?.hourly_rate?.[0]"
                />

                <TextField
                    label="Professional bio"
                    autoComplete="off"
                    :multiline="5"
                    @focusout="updateProfile('about')"
                    v-model="form.profile.about"
                    :loading="loading.about"
                    :error="errors?.about?.[0]"
                />
              </BlockStack>
            </Card>
          </LayoutAnnotatedSection>

          <Divider borderColor="border" />

          <LayoutAnnotatedSection
              id="payout"
              title="Payout Options"
          >
            <Card padding="500">
              <BlockStack gap="800">
                <BlockStack gap="400">
                  <InlineStack gap="400" blockAlign="center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                      <rect width="40" height="40" rx="20" fill="#0070E0"/>
                      <path d="M17.5817 13.6493C17.4426 13.6495 17.3082 13.6973 17.2025 13.7843C17.0968 13.8713 17.0268 13.9917 17.005 14.1239L16.0469 19.9739C16.0915 19.7006 16.3361 19.4993 16.6235 19.4993H19.4312C22.257 19.4993 24.6548 17.5149 25.0929 14.8258C25.1255 14.625 25.144 14.4224 25.1482 14.2192C24.4301 13.8568 23.5865 13.6493 22.6622 13.6493H17.5817Z" fill="white" fill-opacity="0.6"/>
                      <path d="M25.1455 14.2194C25.1413 14.4226 25.1228 14.6253 25.0902 14.826C24.6521 17.5152 22.2541 19.4995 19.4286 19.4995H16.6209C16.3337 19.4995 16.0889 19.7006 16.0442 19.9741L15.1632 25.3497L14.6114 28.7221C14.6006 28.7873 14.6046 28.8539 14.6232 28.9174C14.6418 28.9809 14.6745 29.0398 14.719 29.0899C14.7635 29.1401 14.8187 29.1804 14.881 29.208C14.9432 29.2356 15.011 29.2499 15.0795 29.2499H18.127C18.2661 29.2498 18.4005 29.2019 18.5062 29.1149C18.6119 29.0279 18.6819 28.9075 18.7037 28.7753L19.5064 23.8742C19.5282 23.7419 19.5982 23.6215 19.704 23.5345C19.8098 23.4475 19.9444 23.3997 20.0835 23.3997H21.8778C24.7036 23.3997 27.1013 21.4153 27.5395 18.7262C27.8506 16.8176 26.852 15.0808 25.1455 14.2194Z" fill="white" fill-opacity="0.4"/>
                      <path d="M14.1185 9.75C13.8313 9.75 13.5866 9.95111 13.5419 10.2241L11.1504 24.8221C11.1051 25.0992 11.3275 25.3499 11.619 25.3499H15.1654L16.0459 19.9742L17.0041 14.1242C17.0258 13.992 17.0959 13.8716 17.2015 13.7846C17.3072 13.6976 17.4417 13.6498 17.5807 13.6497H22.6613C23.5858 13.6497 24.4291 13.8573 25.1473 14.2196C25.1964 11.7713 23.0978 9.75 20.2125 9.75H14.1185Z" fill="white"/>
                    </svg>

                    <BlockStack>
                      <Text as="p" fontWeight="semibold" variant="bodyMd">
                        Connect your PayPal account
                      </Text>

                      <Text as="p" tone="subdued" variant="bodyMd">
                        Allows you to request payouts on your PayPal account.
                      </Text>
                    </BlockStack>
                  </InlineStack>

                  <TextField
                      type="email"
                      label="Enter email associated with your PayPal account"
                      helpText="The participant is responsible for PayPal fees."
                      autoComplete="off"
                      @focusout="updateProfile('paypal_email')"
                      v-model="form.profile.paypal_email"
                      :loading="loading.paypal_email"
                      :error="errors?.paypal_email?.[0]"
                  />
                </BlockStack>

                <BlockStack gap="400">
                  <InlineStack gap="400" blockAlign="center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                      <rect width="40" height="40" rx="20" fill="#88EA5C"/>
                      <path d="M21.6901 16.5354H23.6157L22.6469 23.4765H20.7214L21.6901 16.5354ZM19.2625 16.5354L17.9631 20.5339L17.396 16.5354H16.0493L14.3482 20.5221L14.1356 16.5354H12.2691L12.9188 23.4765H14.4664L16.3801 19.0864L17.0535 23.4765H18.5773L21.0936 16.5354H19.2625ZM35.4289 20.5695H30.8571C30.8807 21.4713 31.4183 22.0646 32.2097 22.0646C32.8063 22.0646 33.2789 21.7442 33.6451 21.1332L35.1884 21.838C34.6581 22.8874 33.5405 23.5714 32.1625 23.5714C30.2842 23.5714 29.0379 22.3018 29.0379 20.2611C29.0379 18.0185 30.5027 16.4286 32.57 16.4286C34.3893 16.4286 35.5352 17.6626 35.5352 19.5847C35.5352 19.9051 35.4997 20.2255 35.4289 20.5695ZM33.7159 19.2406C33.7159 18.4338 33.267 17.9236 32.5464 17.9236C31.8259 17.9236 31.1879 18.4575 31.0225 19.2406H33.7159ZM6.25087 18.6272L4.28516 20.9344H7.79485L8.18941 19.8464H6.68559L7.60466 18.7791L7.60762 18.7506L7.00987 17.7177H9.69798L7.61411 23.4765H9.03997L11.5562 16.5354H5.05538L6.25028 18.6272H6.25087ZM26.7462 17.9236C27.4254 17.9236 28.0208 18.2902 28.5405 18.9191L28.8135 16.9625C28.3291 16.6333 27.6735 16.4286 26.8052 16.4286C25.0805 16.4286 24.1118 17.4431 24.1118 18.7304C24.1118 19.6233 24.608 20.1691 25.4231 20.5221L25.8129 20.7001C26.5394 21.0115 26.7344 21.1658 26.7344 21.4951C26.7344 21.8243 26.4066 22.0527 25.9074 22.0527C25.0834 22.0556 24.416 21.6315 23.914 20.9077L23.6357 22.9017C24.2075 23.3395 24.9405 23.5714 25.9074 23.5714C27.5465 23.5714 28.5536 22.6222 28.5536 21.3052C28.5536 20.4094 28.1578 19.8339 27.1596 19.383L26.7344 19.1813C26.1436 18.9173 25.9429 18.772 25.9429 18.4813C25.9429 18.1668 26.2175 17.9236 26.7462 17.9236Z" fill="#163300"/>
                    </svg>

                    <BlockStack>
                      <Text as="p" fontWeight="semibold" variant="bodyMd">
                        Connect your Wise account
                      </Text>

                      <Text as="p" tone="subdued" variant="bodyMd">
                        Allows you to request payouts on your Wise account.
                      </Text>
                    </BlockStack>
                  </InlineStack>

                  <TextField
                      type="email"
                      label="Enter email associated with your Wise account"
                      helpText="The participant is responsible for Wise fees."
                      autoComplete="off"
                      @focusout="updateProfile('wise_email')"
                      v-model="form.profile.wise_email"
                      :loading="loading.wise_email"
                      :error="errors?.wise_email?.[0]"
                  />
                </BlockStack>
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

    <ImageUploadModal @close="toggleUploadModal" v-if="uploadModal" />
  </ExpertLayout>
</template>

<style scoped>

</style>