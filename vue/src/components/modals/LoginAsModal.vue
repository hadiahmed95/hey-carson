<script>
import CheckCircle from "@/components/icons/CheckCircle.vue";
import XIcon from "@/components/icons/XIcon.vue";
import MobileModal from "@/components/MobileModal.vue";
import axios from "axios";
import InputBtn from "@/components/misc/InputBtn.vue";
import UserBox from "@/components/misc/UserBox.vue";
import socket from "@/mixins/socket";

export default {
  name: "LoginAsModal",
  components: {InputBtn, MobileModal, UserBox},

  props: {
    userId: {
      type: Number,
      default: 0
    },
    type: {
      type: String,
      default: ''
    },
    user: {
      type: Object,
      default: () => {}
    }
  },

  mixins: [socket],

  data() {
    return {
      CheckCircle,
      XIcon,
      isMobile: screen.width <= 760,

      loading: false,
    }
  },

  methods: {
    async loginAs() {
      await axios.post(`api/admin/login-as/${this.user?.id}`).then(res => {

        window.localStorage.setItem('CURRENT_USER', JSON.stringify(res.data.user))
        window.localStorage.setItem('CURRENT_TOKEN', res.data.token)

        axios.defaults.headers.common['Authorization'] = 'Bearer ' + res.data.token;
        this.initializeSocket(res.data.token);
        this.loading = false;

        const route = this.type === 'client' ? '/client' : '/expert';
        this.$router.push(route);
      }).catch(() => {
        const route = this.type === 'client' ? '/admin/clients' : '/admin/experts';
        this.$router.push(route);
      });
    },
  }
}
</script>

<template>
  <MobileModal :mobile="isMobile">
  <template #heading>
    <InlineStack align="space-between" blockAlign="start" :wrap="false">
      <Text variant="bodyLg" fontWeight="bold" as="p">
        Login as {{ type === 'client' ? 'Client' : 'Expert' }}
      </Text>

      <div>
        <Icon :source="XIcon"  @click="() => this.$emit('close')"/>
      </div>
    </InlineStack>
  </template>

  <Box style="padding: 16px">
    <BlockStack gap="200">
      <Text variant="bodyMd" as="p">
        Are you sure you want to log in as this {{ type === 'client' ? 'client' : 'expert' }}?
      </Text>

      <Divider/>
      <UserBox :user="user" :isShowEmail="type ===  'client'" :role="type === 'expert'" />
    </BlockStack>
  </Box>

  <template #footer>
    <InlineStack align="end" gap="200">
      <Button @click="() => this.$emit('close')">Cancel</Button>

      <InputBtn :icon="CheckCircle" :loading="loading" @click="loginAs">Login as</InputBtn>
    </InlineStack>
  </template>
  </MobileModal>
</template>

<style scoped>

</style>
