<script>
import LoginLayout from "@/layout/LoginLayout.vue";
import ViewIcon from "@/components/icons/ViewIcon.vue";
import HideIcon from "@/components/icons/HideIcon.vue";
import InputBtn from "@/components/misc/InputBtn.vue";
export default {
  name: "ApprovedPage",

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

      errors: {
        password: '',
        confirm_password: '',
      },
    }
  },

  methods: {
    showPassConf() {
      this.passConfHidden = !this.passConfHidden;
    },

    showPass() {
      this.passHidden = !this.passHidden;
    },
  }
}
</script>

<template>
  <LoginLayout>
    <BlockStack gap="100">
      <Text variant="headingXl" fontWeight="bold" as="h2">
        Great news! You are approved!
      </Text>
      <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
        You are now part of the Shopexperts talent network. Please create a password for your account and log in.
      </Text>
    </BlockStack>

    <BlockStack gap="600">
      <TextField
          :type="passHidden ? 'password' : 'text'"
          label="Password"
          v-model="form.password"
          placeholder="Enter new password"
          autoComplete="off"
          :error="errors.password"
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
          :error="errors.password"
      >
        <template v-slot:suffix>
          <Icon v-if="passHidden" :source="ViewIcon" tone="base" @click="showPassConf" style="cursor: pointer"/>
          <Icon v-else :source="HideIcon" tone="base" @click="showPassConf" style="cursor: pointer"/>
        </template>
      </TextField>
    </BlockStack>

    <BlockStack gap="600">
      <InputBtn @click="createPass" :loading="loading">Create Password & Login</InputBtn>
    </BlockStack>
  </LoginLayout>
</template>

<style scoped>

</style>