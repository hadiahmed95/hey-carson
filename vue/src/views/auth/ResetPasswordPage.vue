<script>
import LoginLayout from "@/layout/LoginLayout.vue";
import ViewIcon from "@/components/icons/ViewIcon.vue";
import HideIcon from "@/components/icons/HideIcon.vue";
import axios from "axios";
import InputBtn from "@/components/misc/InputBtn.vue";

export default {
  name: "ResetPasswordPage",

  components: {
    InputBtn,
    LoginLayout
  },

  data() {
    return {
      ViewIcon,
      HideIcon,

      passHidden: true,
      passConfHidden: true,

      form: {
        password: '',
        confirm_password: '',
      },

      errors: {},
      error: null,
      loading: false
    }
  },

  methods: {
    showPassConf() {
      this.passConfHidden = !this.passConfHidden;
    },

    showPass() {
      this.passHidden = !this.passHidden;
    },

    async  resetPassword() {
      this.loading = true;

      await axios.post('api/reset-password', {
        token: this.$route.query.token,
        password: this.form.password,
        password_confirmation: this.form.confirm_password
      }).then((response) => {
        this.loading = false;
        this.$router.push('/' + response.data.user_role + '/login')
      }).catch(err => {
        this.loading = false;
        this.errors = err.response?.data?.errors || {};
        this.error = err.response?.data?.error || null;
      });
    }
  }
}
</script>

<template>
  <LoginLayout>
    <BlockStack gap="100">
      <Text variant="headingXl" fontWeight="bold" as="h2">
        Reset account password
      </Text>
      <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
        For the account {{ this.$route.query['email'] }}
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

    <BlockStack gap="600">
      <TextField
          :type="passHidden ? 'password' : 'text'"
          label="New Password"
          v-model="form.password"
          placeholder="Enter new password"
          autoComplete="off"
          :error="errors.password && errors.password[0] ? errors.password[0] : null"
      >
        <template v-slot:suffix>
          <Icon v-if="passHidden" :source="ViewIcon" tone="base" @click="showPass" style="cursor: pointer"/>
          <Icon v-else :source="HideIcon" tone="base" @click="showPass" style="cursor: pointer"/>
        </template>
      </TextField>

      <TextField
          :type="passConfHidden ? 'password' : 'text'"
          label="Confirm Password"
          v-model="form.confirm_password"
          placeholder="Re-type password"
          autoComplete="off"
          :error="errors.confirm_password && errors.confirm_password[0] ? errors.confirm_password[0] : null"
      >
        <template v-slot:suffix>
          <Icon v-if="passHidden" :source="ViewIcon" tone="base" @click="showPassConf" style="cursor: pointer"/>
          <Icon v-else :source="HideIcon" tone="base" @click="showPassConf" style="cursor: pointer"/>
        </template>
      </TextField>
    </BlockStack>

    <BlockStack gap="600">
      <InputBtn @click="resetPassword" :loading="loading">Reset Password</InputBtn>
    </BlockStack>
  </LoginLayout>
</template>

<style scoped>

</style>