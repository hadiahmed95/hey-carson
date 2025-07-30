<script>
import NoteIcon from '@shopify/polaris-icons/dist/svg/NoteIcon.svg';
import ViewIcon from '../../../components/icons/ViewIcon';
import HideIcon from '../../../components/icons/HideIcon'
import RegisterLayout from "@/layout/RegisterLayout.vue";
import RegisterPartOne from "@/components/misc/RegisterPartOne.vue";
import RegisterPartTwo from "@/components/misc/RegisterPartTwo.vue";
import axios from "axios";
import InputBtn from "@/components/misc/InputBtn.vue";
import common from "@/mixins/common";
import refApi from "@/util/referrals";
import socket from "@/mixins/socket";
import recaptchaMixin from '@/mixins/recaptchaMixin';
export default {
  name: "RegisterPage",

  components: {
    InputBtn,
    RegisterLayout,
    RegisterPartOne,
    RegisterPartTwo
  },

  data() {
    return {
      options: [
        { "label": "eCommerce brand", "value": "eCommerce brand" },
        { "label": "Agency", "value": "Agency" },
        { "label": "Shopify app", "value": "Shopify app" },
      ],

      validImageTypes: ['image/gif', 'image/jpeg', 'image/png'],

      NoteIcon,
      ViewIcon,
      HideIcon,
      isMobile: screen.width <= 760,

      passHidden: true,
      passConfirmHidden: true,

      projectForm: {
        url: '',
        company_type: '',
        name: '',
        description: '',
        files: [],
      },

      userForm: {
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        password_confirmation: '',
        subscribe: true,
      },

      expertForm: {
        click_id: refApi.getClickId(),
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        country: '',
        url: '',
        about: '',
        role: '',
        experience: '',
        availability: '',
        english_level: '',
        hourly_rate: '',
        subscribe: true,
      },

      recaptchaSiteKey: process.env.VUE_APP_RECAPTCHA_SITE_KEY,
      recaptchaToken: null,

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

      page: 1,
      loading: false,

      userType: 'expert', //expert|client
      error: null,
      errors: null,
    }
  },

  mixins: [common, socket, recaptchaMixin],

  mounted() {
    this.initReCapcha();
  },

  methods: {
    nextPage(back = false, check = false) {
      if (!back && this.page === 1) {
        if (!this.recaptchaToken) {
          this.error = 'Please complete the reCAPTCHA verification';
          return;
        } else if (this.recaptchaToken) {
          this.error = null
        }
      }
      if (check) {
        this.loading = true;

        let data = {
          first_name: this.expertForm.first_name,
          last_name: this.expertForm.last_name,
          password: this.expertForm.password,
          email: this.expertForm.email,
          country: this.expertForm.country,
          url: this.expertForm.url,
          about: this.expertForm.about
        }

        axios.post('api/register/check', data).then(() => {
          this.error = null;
          this.errors = null;

          this.page = 3
          this.loading = false;
        }).catch(err => {
          this.error = err.response.data.message ?? "You have some errors in your form"
          this.errors = err.response.data.errors

          this.loading = false;
        });
      } else {
        if (back) {
          this.page = this.page - 1
        } else {
          this.page = this.page + 1
        }
      }
    },

    showPass() {
      this.passHidden = !this.passHidden;
    },

    async register() {
      this.error = null
      this.errors = null

      if (!this.recaptchaToken) {
        this.error = 'Security verification expired. Please complete the reCAPTCHA again.';
        return;
      }

      this.loading = true;

      let data = {
        user_type: 'expert',
        recaptcha_token: this.recaptchaToken,
        expert: this.expertForm
      }

      await axios.post('api/register', data).then(res => {
        window.localStorage.setItem('CURRENT_USER', JSON.stringify(res.data.user))
        window.localStorage.setItem('CURRENT_TOKEN', res.data.token)

        axios.defaults.headers.common['Authorization'] = 'Bearer ' + res.data.token;
        this.initializeSocket(res.data.token);
        this.loading = false;

        this.page = 4

        this.error = null
        this.errors = null
      }).catch(err => {
        this.error = err.response.data.message ?? "You have some errors in your form"
        this.errors = err.response.data.errors

        this.loading = false;
      });
    }
  }
}
</script>

<template>
  <RegisterLayout expert>
    <template #top>
      <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
        Step {{page}}/4
      </Text>
    </template>
    <template v-if="page === 1">
      <BlockStack gap="100" style="margin-top: 16px">
        <Text variant="headingXl" fontWeight="bold" as="h2">
          You're interested in joining the Shopexperts talent network?
        </Text>
        <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
          We pledge to link freelancers to lucrative contracts with top eCommerce companies and support them in a growing global community of freelancers.
        </Text>
      </BlockStack>

      <BlockStack gap="400" style="margin-top: 16px">
        <RegisterPartOne :style="isMobile ? 'margin: 0 calc((100vw - 522px)/2)' : ''" />

        <Text variant="bodyLg" as="p" alignment="center">
          Before proceeding, ensure your technical and communication skills are proficient. We only accept freelancers with a <strong>minimum of 3 years of professional experience</strong> in development or design.
        </Text>
      </BlockStack>

      <div class="recaptcha-wrapper">
        <div id="recaptcha-container"></div>
        <div v-if="error?.includes('reCAPTCHA')" class="error-message">
          {{ error }}
        </div>
      </div>

      <InputBtn @click="nextPage()" style="margin-top: 16px">Next</InputBtn>
    </template>

    <template v-else-if="page === 2">
      <BlockStack gap="300">
        <BlockStack gap="100">
          <Text variant="headingXl" fontWeight="bold" as="h2">
            Join shopexperts talent network
          </Text>
          <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
            This will likely take no more than 4 minutes.
          </Text>
        </BlockStack>
      </BlockStack>

      <Badge tone="critical" size="large" v-if="error" style="height: 36px">
        <InlineStack gap="200" blockAlign="center" align="start" padding="200">
          <InlineStack align="space-between" style="width: 345px" :wrap="false">
            <Text variant="bodyLg" as="p" alignment="start" tone="critical">
              {{ error }}
            </Text>
          </InlineStack>
        </InlineStack>
      </Badge>

      <BlockStack gap="800">
        <FormLayout>
          <FormLayoutGroup>
            <TextField
                label="First Name *"
                autoComplete="off"
                :requiredIndicator="true"
                v-model="expertForm.first_name"
                placeholder="Enter your first name"
                :error="errors?.first_name?.[0]"
            />

            <TextField
                label="Last Name *"
                :requiredIndicator="true"
                v-model="expertForm.last_name"
                placeholder="Enter your last name"
                autoComplete="off"
                :error="errors?.last_name?.[0]"
            />
          </FormLayoutGroup>

          <TextField
              type="email"
              label="Email *"
              :requiredIndicator="true"
              autoComplete="email"
              v-model="expertForm.email"
              :error="errors?.email?.[0]"
              placeholder="Enter your email"
          />

          <TextField
              :type="passHidden ? 'password' : 'text'"
              label="Password *"
              v-model="expertForm.password"
              placeholder="Enter your password"
              :error="errors?.password?.[0]"
              autoComplete="off"
          >
            <template v-slot:suffix>
              <Icon v-if="passHidden" :source="ViewIcon" tone="base" @click="showPass" style="cursor: pointer"/>
              <Icon v-else :source="HideIcon" tone="base" @click="showPass" style="cursor: pointer"/>
            </template>
          </TextField>

          <TextField
              type="text"
              label="Country of residence *"
              :requiredIndicator="true"
              v-model="expertForm.country"
              placeholder="Enter country"
              :error="errors?.country?.[0]"
          />

          <TextField
              type="text"
              label="LinkedIn/Portfolio website URL *"
              :requiredIndicator="true"
              v-model="expertForm.url"
              placeholder="www.myportfolio.com "
              :error="errors?.url?.[0]"
          />

          <TextField
              label="Tell us about your previous experience and skills as a Shopify expert especially as it relates to Shopify or eCommerce. *"
              autoComplete="off"
              :requiredIndicator="true"
              v-model="expertForm.about"
              :multiline="5"
              placeholder="Please give as much detail as you can about previous duties and roles. Include programming languages, frameworks and unique skills or projects you've worked on."
              :error="errors?.about?.[0]"
          />
        </FormLayout>
      </BlockStack>

      <InputBtn @click="nextPage(false, true)" :loading="loading" style="margin-top: 16px">Next</InputBtn>
    </template>

    <template v-else-if="page === 3">
      <BlockStack gap="300">
        <BlockStack gap="100">
          <Text variant="headingXl" fontWeight="bold" as="h2">
            Almost done!
          </Text>
          <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
            We just need couple more details and you're all set!
          </Text>
        </BlockStack>
      </BlockStack>

      <Badge tone="critical" size="large" v-if="error" style="height: 36px">
        <InlineStack gap="200" blockAlign="center" align="start" padding="200">
          <InlineStack align="space-between" style="width: 345px" :wrap="false">
            <Text variant="bodyLg" as="p" alignment="start" tone="critical">
              {{ error }}
            </Text>
          </InlineStack>
        </InlineStack>
      </Badge>

      <BlockStack gap="800">
        <FormLayout>
          <Select
              label="Primary Role *"
              :requiredIndicator="true"
              :options="selectOptions.role"
              placeholder="Your primary focus as Shopify expert..."
              v-model="expertForm.role"
              :error="errors?.role?.[0]"
          />

          <Select
              label="Years of experience *"
              :requiredIndicator="true"
              :options="selectOptions.experience"
              placeholder="Select an option..."
              v-model="expertForm.experience"
              :error="errors?.experience?.[0]"
          />

          <Select
              label="What is your availability per week? *"
              :requiredIndicator="true"
              :options="selectOptions.availability"
              placeholder="Select an option..."
              v-model="expertForm.availability"
              helpText="Freelancers typically need to commit to at least 10 hours per week."
              :error="errors?.availability?.[0]"
          />

          <Select
              label="What is your English level? *"
              :requiredIndicator="true"
              :options="selectOptions.english_level"
              placeholder="Select an option..."
              v-model="expertForm.english_level"
              :error="errors?.english_level?.[0]"
          />

          <TextField
              type="text"
              :requiredIndicator="true"
              label="Expected hourly rate *"
              v-model="expertForm.hourly_rate"
              placeholder="Enter your hourly rate (USD)"
              :error="errors?.hourly_rate?.[0]"
          />
        </FormLayout>
      </BlockStack>

      <BlockStack gap="600">
        <InputBtn @click="register" :loading="loading">Submit Application</InputBtn>

        <Button fullWidth @click="nextPage(true)">Back</Button>

        <Text variant="bodyMd" as="p" alignment="center" tone="subdued">
          By proceeding, you agree to the
          <Link :removeUnderline="true" external target="_blank" :url="expertTermsUrl">Terms & Conditions</Link> and
          <Link :removeUnderline="true" external target="_blank" :url="privacyUrl" >Privacy Policy</Link>.
        </Text>
      </BlockStack>
    </template>

    <template v-else>
        <BlockStack gap="100" style="margin-top: 16px">
          <Text variant="headingXl" fontWeight="bold" as="h2">
            Application successfully submitted!
          </Text>
          <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
            You can expect a follow up from us within 7-10 business days.
          </Text>
        </BlockStack>

        <BlockStack gap="400" style="margin-top: 16px">
          <RegisterPartTwo :style="isMobile ? 'margin: 0 calc((100vw - 522px)/2)' : ''" />

          <Text variant="bodyLg" as="p" alignment="center">
            Our team will thoroughly review your application details. We're excited about the possibility of welcoming you into our vetted network soon. Thanks for your patience!          </Text>
        </BlockStack>

      <InputBtn style="margin-top: 16px" @click="() => this.$router.push('/expert/login')" :loading="loading">Back to Website</InputBtn>
      </template>
  </RegisterLayout>
</template>

<style scoped>
.error-message {
  color: #d92d20;
  font-size: 14px;
  margin-top: 8px;
}
</style>
