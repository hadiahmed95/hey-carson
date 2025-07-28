<script>
import LoginLayout from "@/layout/LoginLayout.vue";
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
    }
  },

  methods: {
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

    <BlockStack gap="600">
      <TextField
          type="email"
          label="Email"
          autoComplete="email"
          v-model="form.email"
          placeholder="Enter your email"
          :error="errors.email"
      />
    </BlockStack>

    <BlockStack gap="600">
      <InputBtn @click="() => {this.$router.push('reset-password')}">Reset Password</InputBtn>

      <Text variant="bodyMd" as="p" alignment="center" tone="subdued">
        <Link :removeUnderline="true" external target="_blank" to="login" >Return to Login</Link>
      </Text>
    </BlockStack>
  </LoginLayout>
</template>

<style scoped>

</style>