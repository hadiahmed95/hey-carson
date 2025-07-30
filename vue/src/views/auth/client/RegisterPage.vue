<script>
import ViewIcon from '@/components/icons/ViewIcon';
import HideIcon from '@/components/icons/HideIcon';
import SearchIcon from "@/components/icons/SearchIcon.vue";
import RegisterLayout from "@/layout/RegisterLayout.vue";
import axios from "axios";
import QuestionIcon from "@/components/icons/QuestionIcon.vue";
import UploadIcon from "@/components/icons/UploadIcon.vue";
import InputBtn from "@/components/misc/InputBtn.vue";
import common from '@/mixins/common';
import {debounce} from "@/directives/debounce";
import refApi from "@/util/referrals";
import socket from "@/mixins/socket";
import recaptchaMixin from '@/mixins/recaptchaMixin';

export default {
  name: "RegisterPage",

  components: {
    InputBtn,
    RegisterLayout,
  },

  data() {
    return {
      options: [
        { "label": "eCommerce brand", "value": "eCommerce brand" },
        { "label": "Agency", "value": "Agency" },
        { "label": "Shopify app", "value": "Shopify app" },
      ],

      validImageTypes: ['image/gif', 'image/jpeg', 'image/png'],

      ViewIcon,
      HideIcon,
      SearchIcon,
      QuestionIcon,
      UploadIcon,

      passHidden: true,
      passConfirmHidden: true,

      expertName: '',
      loadedExperts: [],
      selectedExpert: null,
      loadingExperts: false,

      errors: null,
      error: null,
      recaptchaSiteKey: process.env.VUE_APP_RECAPTCHA_SITE_KEY,
      recaptchaToken: null,

      form: {
        first_name: '',
        last_name: '',
        email: '',
        url: '',
        password: '',
        password_confirmation: '',
        subscribe: true,
      },

      loading: false,
    }
  },

  mounted() {
    const urlParams = new URLSearchParams(window.location.search);
    const emailParam = urlParams.get('email');

    if (emailParam) {
      this.form.email = emailParam;
    }
    this.initReCapcha();
  },

  mixins: [common, socket, recaptchaMixin],

  methods: {
    showPass() {
      this.passHidden = !this.passHidden;
    },

    register: debounce(async function() {
      if (!this.recaptchaToken) {
        this.error = 'Please complete the reCAPTCHA verification.';
        return;
      }
      this.loading = true;

      const form = new FormData();

      form.append('recaptcha_token', this.recaptchaToken);
      form.append('user_type', 'client');
      form.append('click_id', refApi.getClickId());
      form.append('client[first_name]', this.form.first_name);
      form.append('client[last_name]', this.form.last_name);
      form.append('client[email]', this.form.email);
      form.append('client[url]', this.form.url);
      form.append('client[password]', this.form.password);

      await axios.post('api/register', form, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(res => {
        window.localStorage.setItem('CURRENT_USER', JSON.stringify(res.data.user))
        window.localStorage.setItem('CURRENT_TOKEN', res.data.token)

        axios.defaults.headers.common['Authorization'] = 'Bearer ' + res.data.token;
        this.initializeSocket(res.data.token);
        this.loading = false;

        this.$router.push('/client')
      }).catch(err => {
        this.error = err.response.data.message ?? "You have some errors in your form"
        this.errors = err.response.data.errors

        this.loading = false;
      });
    }, 200)
  }
}
</script>

<template>
  <RegisterLayout>
    <BlockStack gap="300">
      <BlockStack gap="100">
        <Text variant="headingXl" fontWeight="bold" as="h2">
          Open a free account
        </Text>
      </BlockStack>
      <Text variant="bodyMd" as="p" alignment="start" tone="subdued">
        Already have an account?
        <Link :removeUnderline="true" external target="_blank" to="login" >Login</Link>
      </Text>
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
      <FormLayout name="register_client" :autoComplete="false">
        <FormLayoutGroup>
          <TextField
              label="First Name *"
              autoComplete="off"
              :requiredIndicator="true"
              v-model="form.first_name"
              placeholder="Enter your first name"
              :error="errors?.first_name?.[0]"
          />

          <TextField
              label="Last Name *"
              :requiredIndicator="true"
              v-model="form.last_name"
              placeholder="Enter your last name"
              autoComplete="off"
              :error="errors?.last_name?.[0]"
          />
        </FormLayoutGroup>

        <TextField
            label="Store URL*"
            autoComplete="off"
            name="url"
            v-model="form.url"
            placeholder="yourstore.com"
            :error="errors?.url?.[0]"
        />

        <TextField
            label="Email Address*"
            autoComplete="off"
            v-model="form.email"
            name="email"
            placeholder="your@email.com"
            :error="errors?.email?.[0]"
        />

        <TextField
            :type="passHidden ? 'password' : 'text'"
            label="Password *"
            name="password"
            v-model="form.password"
            placeholder="Pick a strong password for your account."
            autoComplete="off"
            :error="errors?.password?.[0]"
        >
          <template v-slot:suffix>
            <Icon v-if="passHidden" :source="ViewIcon" tone="base" @click="showPass" style="cursor: pointer"/>
            <Icon v-else :source="HideIcon" tone="base" @click="showPass" style="cursor: pointer"/>
          </template>
        </TextField>
      </FormLayout>

      <div class="recaptcha-wrapper">
        <div id="recaptcha-container"
             ref="recaptchaContainer"
             :data-sitekey="recaptchaSiteKey"></div>
        <div v-if="errors?.recaptcha_token" class="error-message">
          {{ errors.recaptcha_token[0] }}
        </div>
      </div>

      <BlockStack gap="600">
        <InputBtn @click="register" :loading="loading">Sign up</InputBtn>

        <Text variant="bodyMd" as="p" alignment="center" tone="subdued">
          By proceeding, you agree to the
          <Link :removeUnderline="true" external target="_blank" :url="clientTermsUrl">Terms & Conditions</Link> and
          <Link :removeUnderline="true" external target="_blank" :url="privacyUrl" >Privacy Policy</Link>.
        </Text>
      </BlockStack>
      </BlockStack>
  </RegisterLayout>
</template>

<style scoped>
</style>
