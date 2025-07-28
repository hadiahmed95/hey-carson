<script>
import ViewIcon from '../../../components/icons/ViewIcon';
import HideIcon from '../../../components/icons/HideIcon';
import CheckIcon from "@/components/icons/CheckIcon.vue";
import XIcon from "@/components/icons/XIcon.vue";
import LoginLayout from "@/layout/LoginLayout.vue";
import axios from "axios";
import InputBtn from "@/components/misc/InputBtn.vue";
import socket from "@/mixins/socket";

export default {
  name: "LoginPage",

  components: {
    InputBtn,
    LoginLayout
  },

  mixins: [socket],

  data() {
    return {
      ViewIcon,
      HideIcon,
      CheckIcon,
      XIcon,
      loading: false,

      passHidden: true,

      form: {
        email: '',
        password: '',
        confirm_password: '',
      },

      errors: {
        email: null,
        password: null,
      },

      error: '',

      mailSent: false,
    }
  },

  methods: {
    async tryLogin() {
      let tryToLogin = true;
      if (!this.form.email) {
        this.errors.email = "Email is required!"
        tryToLogin = false;
      }
      if (!this.form.password) {
        this.errors.password = "Password is required!"
        tryToLogin = false;
      }

      if (tryToLogin) {
        this.loading = true;

        await axios.post('api/login', {email: this.form.email, password: this.form.password, role: 'admin'}).then(res => {
          window.localStorage.setItem('CURRENT_USER', JSON.stringify(res.data.user))
          window.localStorage.setItem('CURRENT_TOKEN', res.data.token)

          axios.defaults.headers.common['Authorization'] = 'Bearer ' + res.data.token;
          this.initializeSocket(res.data.token);
          this.loading = false;

          this.$router.push('/admin')
        }).catch(err => {
          this.loading = false;

          this.error = err.response.data.message;
        });
      }
    },

    showPass() {
      this.passHidden = !this.passHidden;
    },

    removeNotification() {
      this.mailSent = false;
    }
  }
}
</script>

<template>
  <LoginLayout>
    <BlockStack gap="100">
      <Text variant="headingXl" fontWeight="bold" as="h2">
        Admin Login
      </Text>
      <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
        Continue to Shopexperts profile.
      </Text>
    </BlockStack>

    <Badge tone="success" size="large" v-if="mailSent" style="height: 36px">
      <InlineStack gap="200" blockAlign="center" align="start" padding="200">
        <Icon :source="CheckIcon" />
        <InlineStack align="space-between" style="width: 345px" :wrap="false">
          <Text variant="bodyLg" as="p" alignment="start" tone="success">
            A password reset link has been emailed to you.
          </Text>

          <div>
            <Icon :source="XIcon" @click="removeNotification" />
          </div>
        </InlineStack>
      </InlineStack>
    </Badge>

    <Badge tone="critical" size="large" v-if="error" style="height: 36px">
      <InlineStack gap="200" blockAlign="center" align="start" padding="200">
        <InlineStack align="space-between" style="width: 345px" :wrap="false">
          <Text variant="bodyLg" as="p" alignment="start" tone="critical">
            {{ error }}
          </Text>
        </InlineStack>
      </InlineStack>
    </Badge>

    <BlockStack gap="600">
      <TextField
          type="email"
          label="Email"
          autoComplete="email"
          v-model="form.email"
          placeholder="Enter your email"
          :error="errors.email"
          @keyup.enter="tryLogin"
      />

      <TextField
          :type="passHidden ? 'password' : 'text'"
          label="Password"
          v-model="form.password"
          placeholder="Enter your password"
          :label-action="{content: 'Forgot password?', onAction: () => {this.$router.push('forgot-password')}}"
          autoComplete="off"
          :error="errors.password"
          @keyup.enter="tryLogin"
      >
        <template v-slot:suffix>
          <Icon v-if="passHidden" :source="ViewIcon" tone="base" @click="showPass" style="cursor: pointer"/>
          <Icon v-else :source="HideIcon" tone="base" @click="showPass" style="cursor: pointer"/>
        </template>
      </TextField>
    </BlockStack>

    <BlockStack gap="600">
      <InputBtn @click="tryLogin" :loading="loading">Login</InputBtn>
    </BlockStack>
  </LoginLayout>
</template>

<style scoped>

</style>
