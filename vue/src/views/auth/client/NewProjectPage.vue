<script>
import ViewIcon from '@/components/icons/ViewIcon';
import HideIcon from '@/components/icons/HideIcon';
import SearchIcon from "@/components/icons/SearchIcon.vue";
import InfoIcon from '@/components/icons/infoIcon.vue'
import RegisterLayout from "@/layout/RegisterLayout.vue";
import axios from "axios";
import AvatarFrame from "@/components/misc/AvatarFrame.vue";
import QuestionIcon from "@/components/icons/QuestionIcon.vue";
import UploadIcon from "@/components/icons/UploadIcon.vue";
import {debounce} from "@/directives/debounce";
import common from "@/mixins/common";
import refApi from '@/util/referrals';
import socket from "@/mixins/socket";
import recaptchaMixin from '@/mixins/recaptchaMixin';

export default {
  name: "RegisterPage",

  components: {
    RegisterLayout,
    AvatarFrame
  },

  mixins: [common, socket, recaptchaMixin],

  data() {
    return {
      options: [
        { "label": "Lite", "value": "Lite" },
        { "label": "Basic", "value": "Basic" },
        { "label": "Grow", "value": "Grow" },
        {"label": "Advanced", "value": "Advanced" },
        {"label":"Plus", "value": "Plus" },
        {"label":"No Shopify Plan", "value": "No Shopify Plan" },
        {"label":"Not Sure", "value": "Not Sure" },
      ],

      validImageTypes: ['image/gif', 'image/jpeg', 'image/png'],

      ViewIcon,
      HideIcon,
      SearchIcon,
      InfoIcon,
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

      projectForm: {
        url: '',
        company_type: '',
        shopify_plan:'',
        name: '',
        description: '',
        files: [],
        expert_id: null,
        urgent: false,
      },

      userForm: {
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        confirm_password: '',
        subscribe: true,
      },

      page: 1,

      loading: false,

      userType: 'expert', //expert|client
      refId: '',
      recaptchaSiteKey: process.env.VUE_APP_RECAPTCHA_SITE_KEY,
      recaptchaToken: null,
    }
  },

  computed: {
    projectFormFill() {
      let isFilled = false;

      isFilled = this.projectForm.url && this.projectForm.shopify_plan && this.projectForm.name && this.projectForm.description;

      return !isFilled;
    },

    userFormFill() {
      let matching, isFilled = false;

      matching = this.userForm.password && this.userForm.confirm_password && this.userForm.password === this.userForm.confirm_password;

      isFilled = this.userForm.first_name && this.userForm.email

      return !matching && !isFilled;
    }
  },

  mounted() {
    if (this.$route.query.ref) {
      this.postClickData();
    }
    this.initReCapcha();
  },

  methods: {
    async postClickData() {
      refApi.init()

      refApi
          .generateClick()
    },
    openChat() {
      if (window.Beacon) {
        window.Beacon('open');
      }
    },
    nextPage(back = false, check = false) {
      if (check) {
        if (!this.recaptchaToken) {
          this.error = 'Please complete the reCAPTCHA verification';
          return;
        }
        this.loading = true;

        let data = {
          url: this.projectForm.url,
          description: this.projectForm.description,
          name: this.projectForm.name,
        }

        axios.post('api/register/check', data).then(() => {
          this.error = null;
          this.errors = null;

          this.page = 2
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

    showConfirmPass() {
      this.passConfirmHidden = !this.passConfirmHidden;
    },

    loadExperts: debounce(async function(search = null) {
      await axios.get('api/expert-list', {params: {'search': search}}).then(res => {
        this.loadedExperts = res.data.experts;
        this.loadingExperts = false;
      }).catch(() => {
        this.loadedExperts = [];
        this.loadingExperts = false;
      })
    }, 400),

    updateText(input) {
      this.expertName = input;

      if (!this.loadingExperts) {
        this.loadingExperts = true;
      }

      this.loadExperts(input);
      this.selectedExpert = null;
      this.projectForm.expert_id = null;
    },

    updateSelection(selected) {
      const matchedOption = this.loadedExperts.find((expert) => {
        return expert.id === selected.id;
      });

      this.selectedExpert = selected;
      this.projectForm.expert_id = selected.id;
      this.expertName = matchedOption ? matchedOption.first_name + ' ' + matchedOption.last_name.substring(0, 1) + '.' : '';
    },

    loadFile($event) {
      const target = $event.target;

      this.projectForm.files.push(...Array.from(target.files));
    },

    removeFile(fileIndex) {
      this.projectForm.files.splice(fileIndex, 1);
    },

    async register() {
      if (!this.recaptchaToken) {
        this.error = 'Please complete the reCAPTCHA verification';
        return;
      }

      const form = new FormData();
      form.append('recaptcha_token', this.recaptchaToken);
      this.loading = true;

      form.append('user_type', 'client');
      if (this.$route.query.ref) {
        form.append('click_id', refApi.getClickId());
        form.append('partner_id', refApi.getPartnerId());
        form.append('partner_name', refApi.getPartnerName());
        form.append('program_id', refApi.getProgramId());
      }
      form.append('new_project', true);
      form.append('client[url]', this.projectForm.url);
      form.append('client[company_type]', this.projectForm.company_type);
      form.append('client[shopify_plan]', this.projectForm.shopify_plan);
      form.append('client[title]', this.projectForm.name);
      form.append('client[description]', this.projectForm.description);
      form.append('client[urgent]', this.projectForm.urgent ? 1 : 0);
      if (this.projectForm.expert_id) form.append('client[preferred_expert_id]', this.projectForm.expert_id);
      form.append('client[first_name]', this.userForm.first_name);
      form.append('client[last_name]', this.userForm.last_name);
      form.append('client[email]', this.userForm.email);
      form.append('client[password]', this.userForm.password);
      form.append('client[confirm_password]', this.userForm.confirm_password);
      if(this.refId) {
        form.append('ref_id', this.refId)
      }

      if (this.projectForm.files.length) {
        this.projectForm.files.forEach(file => {
          form.append('files[]', file);
        })
      }

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
    }
  }
}
</script>

<template>
  <RegisterLayout withProject>
    <template v-if="page === 1">
      <BlockStack gap="300">
        <BlockStack gap="100"  >
          <div style='margin-top: 16px; color: #8D8D8D; margin-bottom: 16px; font-size: 12px'>
            Already have an account?
            <Link :removeUnderline="true" external target="_blank" url="https://app.shopexperts.com/client/login" style='text-decoration: underline'>Login here.</Link>
          </div>
          <Text variant="headingXl" fontWeight="bold" as="h2" style='font-family: Archivo' >
            Let’s start here!
          </Text>
          <Text variant="bodyLg" as="p" alignment="start" tone="subdued" class='reg-desc' style='font-family: Archivo'>
              In your own words, describe your Shopify design or development project, or the challenge you’re facing.
          </Text>
        </BlockStack>
      </BlockStack>

      <Badge tone="critical" size="large" v-if="error" style='padding: 8px!important;'>
        <InlineStack gap="200" blockAlign="center" align="start" padding="200">
          <InlineStack align="space-between" style="width: 345px" :wrap="false">
            <Text variant="bodyLg" as="p" alignment="start" tone="critical">
               {{ error }}
            </Text>
          </InlineStack>
        </InlineStack>
      </Badge>

      <BlockStack gap="800">
        <FormLayout name="register_project_client_1" :autoComplete="false">
            <TextField
                label="Shopify store URL or agency website *"
                autoComplete="off"
                name="url"
                v-model="projectForm.url"
                placeholder="yourstore.com"
                :error="errors?.url?.[0]"
            />

          <TextField
              label="Project title *"
              autoComplete="off"
              name="name"
              v-model="projectForm.name"
              placeholder="Pick a catchy title to summarize your project"
              :error="errors?.name?.[0]"
          />

          <TextField
              label="Project brief *"
              autoComplete="off"
              name="description"
              v-model="projectForm.description"
              :multiline="5"
              placeholder="Please try to be as detailed as possible. Include examples of other websites you like or that already do what you're trying to achieve. This helps us provide you with a more accurate project quote."
              :error="errors?.description?.[0]"
              class='input'
          />

          <BlockStack gap="200">
            <InlineStack gap="300" align="start" blockAlign="center">
              <Box @click.stop="$refs.file.click()"
                   borderColor="border"
                   borderStyle="dashed"
                   borderWidth="025"
                   borderRadius="300"
                   padding="600">
                <Icon :source="UploadIcon" />
              </Box>

              <BlockStack>
                <Text fontWeight="semibold">Attach files</Text>
                <Text tone="subdued">Maximum upload file size is 20MB.</Text>
              </BlockStack>

              <input style="display: none" type="file" ref="file" @change="loadFile($event)" accept="image/*,.pdf,.doc,.docx" multiple/>
            </InlineStack>

            <InlineStack v-if="projectForm.files" gap="100">
              <Button :icon="XIcon" v-for="(file, index) in projectForm.files" :key="file" @click="removeFile(index)">{{ file.name }}</Button>
            </InlineStack>
          </BlockStack>

          <Combobox preferredPosition="cover">
            <template #activator>
              <BlockStack gap="100">
                <div style='display: flex; gap: 8px'>
                  Preferred expert (optional)
                  <Tooltip content="Use the input below if you worked with one of our experts before, or have received a recommendation.">
                    <Icon :source="QuestionIcon" />
                  </Tooltip>
                </div>
                <ComboboxTextField
                    v-model="expertName"
                    placeholder="Enter the name of an expert"
                    autoComplete="off"
                    @input="(_e, input) => updateText(input)"
                >
                  <template #prefix>
                    <Icon :source="SearchIcon" />
                  </template>
                </ComboboxTextField>
              </BlockStack>
            </template>

            <Listbox
                v-if="loadingExperts || loadedExperts.length > 0"
                @select="updateSelection"
            >
              <template v-if="!loadingExperts && loadedExperts.length > 0">
                <Scrollable :style="{ maxHeight: '320px' }">
                  <ListboxOption
                      style="padding: 0!important;"
                      v-for="expert in loadedExperts"
                      :key="expert.id"
                      :value="expert"
                      :disabled="expert.availability_status === 'unavailable'">
                    <Box paddingInline="200" paddingBlock="050" width="100%">
                      <Box :background="selectedExpert && selectedExpert.id === expert.id ? 'bg-surface-secondary' : null"
                           borderWidth="025" borderColor="border" borderRadius="300"
                           paddingInline="400" paddingBlock="200" style="width: 100%; cursor: pointer" :opacity="expert.availability_status === 'unavailable' ? '0.5' : '1'">
                        <InlineStack gap="300" blockAlign="center">
                          <AvatarFrame rounded size="lg" :user="expert" />

                          <BlockStack>
                            <Text>
                              {{expert.first_name + ' ' + expert.last_name.substring(0, 1) + '.' + (expert.availability_status === 'unavailable' ? ' [Not Available]' : '')}}
                            </Text>
                            <Text variant="subdued">{{ expert.profile.role }}</Text>
                          </BlockStack>
                        </InlineStack>
                      </Box>
                    </Box>
                  </ListboxOption>
                </Scrollable>
              </template>
              <ListboxLoading v-else />
            </Listbox>
          </Combobox>

          <div style='display: flex; gap: 8px; align-items: center'>
            <Checkbox
                v-model="projectForm.urgent">
              <template #label>
                <InlineStack>
                  This project is urgent
                </InlineStack>
              </template>
            </Checkbox>

            <Tooltip content="We offer express quotes for priority projects to shorten the assessment time and expedite service delivery.">
              <Icon :source="QuestionIcon" />
            </Tooltip>
          </div>

        </FormLayout>
        <div>
          <div class="recaptcha-wrapper">
            <div id="recaptcha-container"
                 ref="recaptchaContainer"
                 :data-sitekey="recaptchaSiteKey"></div>
            <div v-if="errors?.recaptcha_token" class="error-message">
              {{ errors.recaptcha_token[0] }}
            </div>
          </div>
          <div class='pro-cta'>
             <div style='font-size: 14px; color:#1F2125; font-weight: 600'>
               Need help?
               <a href="#" @click='openChat()' style='color: #0094FF;text-decoration: none'>Chat with us. </a>
             </div>
            <div @click="nextPage(false, true)" :class="loading ? 'btn-plain-loading main-button' : 'btn-plain main-button'" borderRadius="200">
               <div class='main-button__item'>
                  <div>
                   Next Step
                  </div>
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M25.1429 0H6.85714C3.07005 0 0 3.07005 0 6.85714V25.1429C0 28.93 3.07005 32 6.85714 32H25.1429C28.93 32 32 28.93 32 25.1429V6.85714C32 3.07005 28.93 0 25.1429 0Z" fill="#ACE46F"/>
                  <path d="M12.1905 19.8096L19.8096 12.1905M19.8096 12.1905H12.1905M19.8096 12.1905V19.8096" stroke="#1F2125" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
            </div>
          </div>
          <div style='margin-top: 16px; color: #8D8D8D; font-size: 12px'>
            By proceeding, you agree to the
            <Link :removeUnderline="true" external target="_blank" :url="clientTermsUrl" style='color: #8D8D8D; text-decoration: underline'>Terms & Conditions</Link> and
            <Link :removeUnderline="true" external target="_blank" :url="privacyUrl" style='color: #8D8D8D; text-decoration: underline'>Privacy Policy</Link>.
          </div>
        </div>
      </BlockStack>
    </template>

    <template v-else-if="page === 2">
      <BlockStack gap="300">
        <BlockStack gap="100">
          <Text variant="headingXl" fontWeight="bold" as="h2">
            One last step ...
          </Text>
          <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
            We just need a couple more details and you're all set!
          </Text>
        </BlockStack>
      </BlockStack>

      <Badge tone="critical" size="large" v-if="error">
        <InlineStack gap="200" blockAlign="center" align="start" padding="200">
          <InlineStack align="space-between" style="width: 345px; padding: 8px" :wrap="false">
            <Text variant="bodyLg" as="p" alignment="start" tone="critical">
                {{ error }}
            </Text>
          </InlineStack>
        </InlineStack>
      </Badge>

      <BlockStack gap="800">
        <FormLayout name="register_project_client_2" :autoComplete="false">
           <TextField
               label="Your e-mail *"
               autoComplete="off"
               v-model="userForm.email"
               name="email"
               placeholder="your@email.com"
               :error="errors?.email?.[0]"
               :style="{fontSize: '13px'}"
           />

          <FormLayoutGroup>
            <TextField
                label="First name *"
                autoComplete="off"
                name="first_name"
                v-model="userForm.first_name"
                placeholder="Enter your first name"
                :error="errors?.first_name?.[0]"
            />

            <TextField
                label="Last name *"
                name="last_name"
                v-model="userForm.last_name"
                placeholder="Enter your last name"
                autoComplete="off"
                :error="errors?.last_name?.[0]"
            />
          </FormLayoutGroup>

          <Select
              label="What is the Shopify plan of the store you own or work for?"
              name="shopify_plan"
              :options="options"
              placeholder="Select an option ..."
              v-model="projectForm.shopify_plan"
              :error="errors?.shopify_plan?.[0]"
          />


          <TextField
              :type="passHidden ? 'password' : 'text'"
              label="Password *"
              name="password"
              v-model="userForm.password"
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
              name="confirm_password"
              v-model="userForm.confirm_password"
              placeholder="Type it again just to confirm it."
              autoComplete="off"
              :error="errors?.confirm_password?.[0]"
              class='input'
          >
            <template v-slot:suffix>
              <Icon v-if="passConfirmHidden" :source="ViewIcon" tone="base" @click="showConfirmPass" style="cursor: pointer"/>
              <Icon v-else :source="HideIcon" tone="base" @click="showConfirmPass" style="cursor: pointer"/>
            </template>
          </TextField>

          <BlockStack gap="200">
            <Text variant="bodyMd" as="p" alignment="start">
              We'd love to send you our weekly newsletter with Shopify resources and occasional offers for Shopexperts services.
            </Text>

            <Checkbox
                label="Yes please, send me tips and offers via email."
                v-model="userForm.subscribe"
            />
          </BlockStack>
        </FormLayout>
      </BlockStack>

      <div>
        <div class='pro-cta'>
          <div @click="nextPage(true)" class='main-button-back-container'>
            <div class='main-button-back'>
              <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_32_6)">
                <path d="M6.85714 0H25.1429C28.93 0 32 3.07005 32 6.85714V25.1429C32 28.93 28.93 32 25.1429 32H6.85714C3.07005 32 0 28.93 0 25.1429V6.85714C0 3.07005 3.07005 0 6.85714 0Z" fill="#ACE46F"/>
                <path d="M19.8096 19.8096L12.1905 12.1905M12.1905 12.1905H19.8096M12.1905 12.1905V19.8096" stroke="#1F2125" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </g>
                <defs>
                <clipPath id="clip0_32_6">
                <rect width="32" height="32" fill="white"/>
                </clipPath>
                </defs>
              </svg>
               <div>
                Go Back
              </div>
            </div>

          </div>
          <div @click="register" :class="loading ? 'btn-plain-loading main-button' : 'btn-plain main-button'" borderRadius="200">
             <div class='main-button__item'>
                <div>
                 Submit Project
                </div>
              <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M25.1429 0H6.85714C3.07005 0 0 3.07005 0 6.85714V25.1429C0 28.93 3.07005 32 6.85714 32H25.1429C28.93 32 32 28.93 32 25.1429V6.85714C32 3.07005 28.93 0 25.1429 0Z" fill="#ACE46F"/>
                <path d="M12.1905 19.8096L19.8096 12.1905M19.8096 12.1905H12.1905M19.8096 12.1905V19.8096" stroke="#1F2125" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
          </div>
        </div>
        <div style='margin-top: 16px; color: #8D8D8D; font-size: 12px'>
          By proceeding, you agree to the
          <Link :removeUnderline="true" external target="_blank" :url="clientTermsUrl" style='color: #8D8D8D; text-decoration: underline'>Terms & Conditions</Link> and
          <Link :removeUnderline="true" external target="_blank" :url="privacyUrl" style='color: #8D8D8D; text-decoration: underline' >Privacy Policy</Link>.
        </div>
      </div>
    </template>
  </RegisterLayout>
</template>

<style scoped>
.btn-plain {
  cursor: pointer;
  border: 1px solid #E3E3E3;
  color: #1F2125;
}
.btn-plain:hover {
  background: #FAFAFA;
}
.btn-plain:active {
  background: #FAFAFAAA;
}
.btn-plain-disable {
  cursor: pointer;
  background: #f7f7f7;
  border: 1px solid #f1f1f1;
  color: #a4a4a4 !important;
}

.btn-plain-loading {
  cursor: pointer;
  background: #fafafa;
  border: 1px solid #e3e3e3;
  color: #fafafa !important;
}

.reg-desc {
  font-family: Archivo;
}
.pro-cta {
  display: flex;
  align-items: center;
  justify-content: space-between
}
.main-button {
  display: flex;
  flex-wrap: nowrap;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  gap: 8px;
  flex-shrink: 0;
  padding: 7px 8px 7px 16px;
  background: #1F2125;
  color: #ffffff;
  border-radius: 8px;
  font-size: 17px;
  font-weight: 600;
  line-height: 0px;
}
.main-button:hover {
  color: #303030;
  background: #ACE46F;
}
.main-button-back-container{
  border-radius: 8px;
  padding: 8px;
  border: 1px solid #1F2125
}

.main-button-back-container:hover{
  color: #303030;
  background: #ACE46F;
  border: 1px solid #ACE46F;

}

.main-button-back {
  display: flex;
  flex-wrap: nowrap;
  flex: 1;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  gap: 8px;
  flex-shrink: 0;
  font-size: 17px;
  font-weight: 600;
  transition: 0.3s ease;
}

.main-button-back:hover >  svg {
  transform: rotate(-45deg);
  transition: 0.3s ease;
}
.main-button-back >  svg {
  transition: 0.3s ease;
}

.main-button__item {
  display: flex;
  flex-wrap: nowrap;
  flex: 1;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  gap: 8px;
  flex-shrink: 0;
}
.main-button:hover > .main-button__item > svg {
  transform: rotate(45deg);
  transition: 0.3s ease;
}

.main-button > .main-button__item > svg {
  transition: 0.3s ease;
}

@media screen and (max-width: 600px) {
  .reg-desc {
    margin-bottom: 24px;
  }

  .pro-cta {
    margin-top: 24px;
    flex-direction: column-reverse;
    gap: 16px;
  }
  .main-button {
    width: 100%;
    justify-content: flex-end;
  }
  .main-button__item {
    justify-content: center;
    gap: 8px;
  }

  .main-button-back-container {
    width: 100%;
    justify-content: center;
  }

  .error-message {
    color: #d92d20;
    font-size: 14px;
    margin-top: 8px;
  }

}
</style>
