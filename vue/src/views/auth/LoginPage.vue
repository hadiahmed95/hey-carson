<script>
import ViewIcon from '../../components/icons/ViewIcon';
import HideIcon from '../../components/icons/HideIcon';
import CheckIcon from "@/components/icons/CheckIcon.vue";
import XIcon from "@/components/icons/XIcon.vue";
import LoginLayout from "@/layout/LoginLayout.vue";
import InputBtn from "@/components/misc/InputBtn.vue";

export default {
  name: "LoginPage",

  components: {
    InputBtn,
    LoginLayout
  },

  data() {
    return {
      ViewIcon,
      HideIcon,
      CheckIcon,
      XIcon,

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

      mailSent: false,
    }
  },

  methods: {
    tryLogin() {
      if (!this.form.email) {
        this.errors.email = "Email is required!"
      }
      if (!this.form.password) {
        this.errors.password = "Password is required!"
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
        Log in
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

    <BlockStack gap="600">
      <TextField
          type="email"
          label="Email"
          autoComplete="email"
          v-model="form.email"
          placeholder="Enter your email"
          :error="errors.email"
      />

      <TextField
          :type="passHidden ? 'password' : 'text'"
          label="Password"
          v-model="form.password"
          placeholder="Enter your password"
          :label-action="{content: 'Forgot password?', onAction: () => {this.$router.push('forgot-password')}}"
          autoComplete="off"
          :error="errors.password"
      >
        <template v-slot:suffix>
          <Icon v-if="passHidden" :source="ViewIcon" tone="base" @click="showPass" style="cursor: pointer"/>
          <Icon v-else :source="HideIcon" tone="base" @click="showPass" style="cursor: pointer"/>
        </template>
      </TextField>
    </BlockStack>

    <BlockStack gap="600">
      <InputBtn @click="tryLogin">Login</InputBtn>

      <Text variant="bodyMd" as="p" alignment="center" tone="subdued">
        Don't have account?
        <Link :removeUnderline="true" external target="_blank" to="register" >Sign up</Link>
      </Text>
    </BlockStack>
  </LoginLayout>
</template>

<style scoped>

</style>