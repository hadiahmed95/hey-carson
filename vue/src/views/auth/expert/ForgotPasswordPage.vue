<script>
import LoginLayout from "@/layout/LoginLayout.vue";
import axios from "axios";
import InputBtn from "@/components/misc/InputBtn.vue";

export default {
  name: "ForgotPasswordPage",

  components: {
    InputBtn,
    LoginLayout
  },

  data() {
    return {
      form: {
        email: '',
      },

      errors: {
        email: null,
      },

      error: null,
      success: null,
      loading: false,
    }
  },

  methods: {
    async resetPassword() {
      this.loading = true;

      await axios.post('api/forgot-password', {email: this.form.email, role: 'expert'}).then(res => {
        this.loading = false;

        this.success = res.data.status
      }).catch(err => {
        this.loading = false;

        this.errors = err.response.data.errors && {
          email: null,
        };
        this.error = err.response.data.error;
      });
    }
  }
}
</script>

<template>
  <LoginLayout>
    <BlockStack gap="100">
      <Text variant="headingXl" fontWeight="bold" as="h2">
        Forgot your password?
      </Text>
      <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
        We’ll email instructions on how to reset it.
      </Text>
    </BlockStack>

    <Badge tone="success" size="large" v-if="success" style="height: 36px">
      <InlineStack gap="200" blockAlign="center" align="start" padding="200">
        <InlineStack align="space-between" style="width: 345px" :wrap="false">
          <Text variant="bodyLg" as="p" alignment="start" tone="success">
            {{ success }}
          </Text>
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
          :error="errors && errors.email ? errors.email[0] : null"
      />
    </BlockStack>

    <BlockStack gap="600">
      <InputBtn @click="resetPassword" :loading="loading">Reset Password</InputBtn>

      <Text variant="bodyMd" as="p" alignment="center" tone="subdued">
        <Link :removeUnderline="true" external target="_blank" to="login" >Return to Login</Link>
      </Text>
    </BlockStack>
  </LoginLayout>
</template>

<style scoped>

</style>