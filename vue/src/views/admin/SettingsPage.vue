<script>
import AdminLayout from "@/layout/AdminLayout.vue";
import ImageUploadModal from "@/components/modals/ImageUploadModal.vue";
import axios from "axios";
import moment from "moment";
import AvatarFrame from "@/components/misc/AvatarFrame.vue";
import ViewIcon from "@/components/icons/ViewIcon.vue";
import HideIcon from "@/components/icons/HideIcon.vue";

export default {
  name: "SettingsPage",
  components: {AvatarFrame, AdminLayout, ImageUploadModal},

  async mounted() {
    await this.getUser()
  },

  data() {
    return {
      ViewIcon,
      HideIcon,

      isMobile: screen.width <= 760,

      user: JSON.parse(window.localStorage.getItem('CURRENT_USER')),
      form: JSON.parse(window.localStorage.getItem('CURRENT_USER')),


      passHidden: true,
      passConfirmHidden: true,

      passForm: {
        password: '',
        password_confirmation: '',
      },

      errors: null,
      loading: {
        first_name: false,
        last_name: false,
        email: false,
        password: false,
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

    async updateUser(field) {
      if (this.user[field] !== this.form[field]) {
        this.loading[field] = true;
        let data = {}

        data[field] = this.form[field]

        await axios.post('api/admin/settings/user', data).then(res => {
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

    async changePassword() {
      this.loading.password = true;

      await axios.post('api/admin/settings/password', this.passForm).then(() => {
        this.success = "Password changed successfully";
        this.loading.password = false;
      }).catch(err => {
        this.errors = err.response.data.errors;
        this.loading.password = false;
      })

    },

    formatDate(date) {
      return moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").fromNow()
    },

    toggleUploadModal() {
      this.uploadModal = !this.uploadModal;

      this.getUser();
    },

    showPass() {
      this.passHidden = !this.passHidden;
    },

    showConfirmPass() {
      this.passConfirmHidden = !this.passConfirmHidden;
    },
  }
}
</script>

<template>
  <AdminLayout>
    <Page style="padding-top: 56px; padding-bottom: 48px"
            title="Settings"
            :backAction="{ content: 'Dashboard', onAction: () => {this.$router.push('/admin')} }"
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

                <TextField
                    :type="passHidden ? 'password' : 'text'"
                    label="Password *"
                    name="password"
                    v-model="passForm.password"
                    placeholder="Pick a strong password for your account."
                    autoComplete="off"
                    :error="errors?.password?.[0]"
                >
                  <template v-slot:suffix>
                    <Icon v-if="passHidden" :source="ViewIcon" tone="base" @click="showPass" style="cursor: pointer"/>
                    <Icon v-else :source="HideIcon" tone="base" @click="showPass" style="cursor: pointer"/>
                  </template>
                </TextField>

                <TextField
                    :type="passConfirmHidden ? 'password' : 'text'"
                    label="Confirm password"
                    name="password_confirmation"
                    v-model="passForm.password_confirmation"
                    placeholder="Type it again just to confirm it."
                    autoComplete="off"
                    :error="errors?.password_confirmation?.[0]"
                >
                  <template v-slot:suffix>
                    <Icon v-if="passConfirmHidden" :source="ViewIcon" tone="base" @click="showConfirmPass" style="cursor: pointer"/>
                    <Icon v-else :source="HideIcon" tone="base" @click="showConfirmPass" style="cursor: pointer"/>
                  </template>
                </TextField>

                <InlineStack>
                  <Button @click="changePassword">Change your password</Button>
                </InlineStack>
              </BlockStack>
            </Card>
          </LayoutAnnotatedSection>
        </BlockStack>
      </Page>

    <ImageUploadModal @close="toggleUploadModal" v-if="uploadModal" />
  </AdminLayout>
</template>

<style scoped>

</style>