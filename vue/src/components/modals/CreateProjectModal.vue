<script>
import XIcon from "@/components/icons/XIcon.vue";
import MobileModal from "@/components/MobileModal.vue";
import axios from "axios";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import UploadIcon from "@/components/icons/UploadIcon.vue";
import AvatarFrame from "@/components/misc/AvatarFrame.vue";
import QuestionIcon from "@/components/icons/QuestionIcon.vue";
import InputBtn from "@/components/misc/InputBtn.vue";
import {debounce} from "@/directives/debounce";
import refApi from "@/util/referrals";

export default {
  name: "CreateProjectModal",

  props:{
    user: {
      type: Object,
      default: () => ({
      }),
    },
  },

  components: {InputBtn, MobileModal, AvatarFrame},

  data() {
    return {
      XIcon,
      SearchIcon,
      UploadIcon,
      QuestionIcon,

      isMobile: screen.width <= 760,

      loading: false,

      expertName: '',
      clientName: '',
      client_email: '',
      loadedExperts: [],
      loadedClients: [],
      selectedExpert: null,
      selectedClient: null,
      loadingExperts: false,
      loadingClients: false,
      EXPERT_ROLE_ID: 3,
      CLIENT_ROLE_ID: 2,


      projectForm: {
        url: window.localStorage.getItem('CURRENT_USER') ? JSON.parse(window.localStorage.getItem('CURRENT_USER')).url : '',
        title: '',
        description: '',
        files: [],
        expert_id: null,
        client_id: null,
        client_email: null,
        client_name: null,
        urgent: false
      },

      hasError: 0,

      errors: {
        url: null,
        title: null,
        description: null,
        client_id: null,
        client_email: null,
      },

      validImageTypes: ['image/gif', 'image/jpeg', 'image/png'],
      existingClient: true,
      newClient: false,
    }
  },

  methods: {
    loadClients: debounce(async function(search = null) {
      await axios.get('api/client-list', {params: {'search': search}}).then(res => {
        this.loadedClients = res.data.clients;
        this.loadingClients = false;
      }).catch(err => {
        console.log(err);
        this.loadedClients = [];
        this.loadingClients = false;
      })
    }, 400),

    loadExperts: debounce(async function(search = null) {
      await axios.get('api/expert-list', {params: {'search': search}}).then(res => {
        this.loadedExperts = res.data.experts;
        this.loadingExperts = false;
      }).catch(err => {
        console.log(err);
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

    updateClient(input) {
      this.clientName = input;
      if (!this.loadingClients) {
        this.loadingClients = true;
      }

      this.loadClients(input);
      this.projectForm.client_id = null;
    },

    updateExpertSelection(selected) {
      const matchedOption = this.loadedExperts.find((expert) => {
        return expert.id === selected.id;
      });

      this.selectedExpert = selected;
      this.projectForm.expert_id = selected.id;
      this.expertName = matchedOption ? matchedOption.first_name + ' ' + matchedOption.last_name.substring(0, 1) + '.' : '';
    },

    updateClientSelection(selected) {
      if (this.selectedClient && this.selectedClient.id === selected.id) {
        this.selectedClient = null;
        this.clientName = "";
        this.projectForm.client_id = null;
        this.projectForm.url = null;
        this.updateClient("")
        return;
      }

      const matchedOption = this.loadedClients.find((client) => {
        return client.id === selected.id;
      });

      this.selectedClient = selected;
      this.projectForm.client_id = selected.id;
      this.projectForm.url = selected.url;
      this.projectForm.client_email = null;
      this.clientName = matchedOption ? matchedOption.first_name + ' ' + matchedOption.last_name.substring(0, 1) + '.' : '';
    },

    loadFile($event) {
      const target = $event.target;

      this.projectForm.files.push(...Array.from(target.files));
    },

    removeFile(fileIndex) {
      this.projectForm.files.splice(fileIndex, 1);
    },

    createProject: debounce(async function() {
      this.hasError = 0;

      if (!this.projectForm.url && !this.newClient) {
        this.hasError++;
        this.errors.url = 'Website is required'
      } else {
        this.errors.url = null;
      }

      if (!this.projectForm.title) {
        this.hasError++;
        this.errors.title = 'title is required'
      } else {
        this.errors.title = null;
      }

      if (!this.projectForm.description) {
        this.hasError++;
        this.errors.description = 'Description is required'
      } else {
        this.errors.description = null;
      }

      if (this.existingClient && this.user?.role_id === this.EXPERT_ROLE_ID) {
        if (!this.projectForm.client_id) {
          this.hasError++;
          this.errors.client_id = 'Select Client'
        } else {
          this.errors.client_id = null;
        }
      } else if (this.newClient && this.user?.role_id === this.EXPERT_ROLE_ID) {
        this.projectForm.client_id = null;
        if (!this.projectForm.client_name) {
          this.hasError++;
          this.errors.client_id = 'Client is required'
        } else {
          this.errors.client_id = null;
        }
        if (!this.projectForm.client_email) {
          this.hasError++;
          this.errors.client_email = 'Client Email is required'
        } else {
          this.errors.client_email = null;
        }
      }

      const form = new FormData();

      form.append('click_id', refApi.getClickId());
      if (!this.newClient) {
        form.append('url', this.projectForm.url);
      }
      form.append('title', this.projectForm.title);
      form.append('description', this.projectForm.description);
      form.append('urgent', this.projectForm.urgent ? 1 : 0);
      if (this.projectForm.client_id) form.append('client_id', this.projectForm.client_id);
      if (this.projectForm.client_name) form.append('client_name', this.projectForm.client_name);
      if (this.projectForm.client_email) form.append('client_email', this.projectForm.client_email);
      if (this.projectForm.expert_id) form.append('preferred_expert_id', this.projectForm.expert_id);

      if (this.projectForm.files.length) {
        this.projectForm.files.forEach(file => {
          form.append('files[]', file);
        })
      }


      if (!this.hasError) {
        this.loading = true;

        const apiEndpoint = this.user?.role_id === this.EXPERT_ROLE_ID ? 'api/expert/projects' : 'api/client/projects';

        await axios.post(apiEndpoint, form, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then(() => {
          this.loading = false;

          this.projectForm = {
            url: window.localStorage.getItem('CURRENT_USER') ? JSON.parse(window.localStorage.getItem('CURRENT_USER')).url : '',
            title: '',
            description: '',
            files: [],
            expert_id: null,
            client_id: null,
            urgent: false
          }

          this.selectedExpert = null;
          this.expertName = "";
          this.selectedClient = null;
          this.clientName = "";

          this.$emit('createdProject')
        }).catch(err => {
          console.log(err);
          this.$emit('createdProject')
          this.$emit('createdProject')
        });
      }
    }, 200),

    toggleClientSelection () {
      this.existingClient = !this.existingClient;
      this.newClient = !this.newClient;
      this.errors.client_id = null;
      this.errors.client_email = null;
      this.errors.title = null;
      this.errors.description = null;
    },
  }
}
</script>

<template>
  <template v-if="isMobile">
    <MobileModal>
      <template #heading>
        <BlockStack gap="100">
          <InlineStack align="space-between">
            <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
              New Project
            </Text>

            <div>
              <Icon :source="XIcon" @click="() => this.$emit('close')"/>
            </div>
          </InlineStack>
        </BlockStack>
      </template>

      <Box style="padding: 16px; max-height: 50vh; overflow: scroll">
        <BlockStack gap="200">
          <BlockStack gap="100">
            <Text variant="headingXl" fontWeight="bold" as="h2">
              Submit your project
            </Text>
            <Text v-if="user?.role_id === this.CLIENT_ROLE_ID" variant="bodyLg" as="p" alignment="start" tone="subdued">
              Connect with the world best Shopify developers.
            </Text>
            <Text v-else-if="user?.role_id === this.EXPERT_ROLE_ID" variant="bodyLg" as="p" alignment="start" tone="subdued">
              Create a Project for any merchant, registered or not.<br>
              Select their account by name if they're registered, or enter<br>
              their email if not. <strong>The project starts once they register.</strong>
            </Text>
          </BlockStack>

          <div v-if="user?.role_id === this.EXPERT_ROLE_ID" style="display: flex; flex-direction: row;">
            <InputBtn :class="existingClient ? 'active-hover-left' : 'select-tab-left'" @click="toggleClientSelection">Existing Client</InputBtn>
            <InputBtn :class="newClient ? 'active-hover-right' : 'select-tab-right'" @click="toggleClientSelection">New Client</InputBtn>
          </div>

          <BlockStack gap="400">
            <FormLayout>

              <BlockStack v-if="user?.role_id === this.EXPERT_ROLE_ID">
                <BlockStack v-if="existingClient">
                  <Combobox preferredPosition="cover">
                    <template #activator>
                      <ComboboxTextField
                          v-model="clientName"
                          placeholder="Enter Client full Name ..."
                          autoComplete="off"
                          @input="(_e, input) => updateClient(input)"
                          :error="errors.client_id"
                      >
                        <template #label>
                          <InlineStack>
                            Client Name
                          </InlineStack>
                        </template>
                        <template #prefix>
                          <Icon :source="SearchIcon" />
                        </template>
                      </ComboboxTextField>
                    </template>

                    <Listbox
                        v-if="loadingClients || loadedClients.length > 0"
                        @select="updateClientSelection"
                    >
                      <template v-if="!loadingClients">
                        <Scrollable :style="{ maxHeight: '320px' }">
                          <ListboxOption
                              style="padding: 0!important;"
                              v-for="client in loadedClients"
                              :key="client.id"
                              :value="client">
                            <Box paddingInline="200" paddingBlock="050" width="100%">
                              <Box :background="selectedClient && selectedClient.id === client.id ? 'bg-surface-secondary' : null"
                                   borderWidth="025" borderColor="border" borderRadius="300"
                                   paddingInline="400" paddingBlock="200" style="width: 100%">
                                <InlineStack gap="300" blockAlign="center">
                                  <AvatarFrame rounded size="lg" :user="client" />

                                  <BlockStack>
                                    <Text>{{ client.first_name + ' ' + client.last_name + '.' }}</Text>
                                    <Text variant="subdued">{{ client.first_name }}</Text>
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
                </BlockStack>

                <BlockStack gap="200" v-if="newClient">
                  <TextField
                      label="Client Name"
                      autoComplete="off"
                      v-model="projectForm.client_name"
                      placeholder="Enter Client full Name ..."
                      :error="errors.client_id" />

                  <TextField
                      label="Client Email"
                      autoComplete="off"
                      v-model="projectForm.client_email"
                      placeholder="client@example.com"
                      :error="errors.client_email" />

                </BlockStack>
              </BlockStack>

              <BlockStack gap="100">
                <InlineStack>
                  Your website
                  <Tooltip content="This is your default store, you can change the URL if you want.">
                    <Icon :source="QuestionIcon" />
                  </Tooltip>
                </InlineStack>
                <TextField
                    autoComplete="off"
                    v-model="projectForm.url"
                    :error="errors.url" />
              </BlockStack>

              <TextField
                  label="Project title"
                  autoComplete="off"
                  v-model="projectForm.title"
                  placeholder="Give your project a short and descriptive title"
                  :error="errors.title" />

              <TextField
                  label="Describe your project"
                  autoComplete="off"
                  v-model="projectForm.description"
                  :multiline="5"
                  placeholder="Please try to be as detailed as possible, as this helps us provide you with the best possible solutions ..."
                  :error="errors.description" />

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
                    <InlineStack>
                      Preferred expert (optional)
                      <Tooltip content="Use the input below if you worked with one of our experts before, or have received a recommendation.">
                        <Icon :source="QuestionIcon" />
                      </Tooltip>
                    </InlineStack>

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
                    v-if="loadedExperts.length > 0"
                    @select="updateExpertSelection"
                >
                  <template v-if="!loadingExperts && loadedExperts.length">
                    <ListboxOption
                        style="padding: 0!important;"
                        v-for="expert in loadedExperts"
                        :key="expert.id"
                        :value="expert"
                        :disabled="expert.availability_status === 'unavailable'">
                      <Box paddingInline="200" paddingBlock="050" width="100%">
                        <Box :background="selectedExpert && selectedExpert.id === expert.id ? 'bg-surface-secondary' : null"
                             borderWidth="025" borderColor="border" borderRadius="300"
                             paddingInline="400" paddingBlock="200" style="width: 100%" :opacity="expert.availability_status === 'unavailable' ? '0.5' : '1'">
                          <InlineStack gap="300" blockAlign="center">
                            <AvatarFrame rounded size="lg" :user="expert" />

                            <BlockStack>
                              {{expert.first_name + ' ' + expert.last_name.substring(0, 1) + '.' + (expert.availability_status === 'unavailable' ? ' [Not Available]' : '')}}
                              <Text variant="subdued">{{ expert.profile.role }}</Text>
                            </BlockStack>
                          </InlineStack>
                        </Box>
                      </Box>
                    </ListboxOption>
                  </template>
                  <ListboxLoading v-else />
                </Listbox>
              </Combobox>

              <InlineStack :wrap="false" blockStack="center">
                <Checkbox v-model="projectForm.urgent">
                  <template #label>
                    <InlineStack>
                      This project is urgent
                    </InlineStack>
                  </template>
                </Checkbox>

                <Tooltip content="We offer express quotes for priority projects to shorten the assessment time and expedite service delivery.">
                  <Icon :source="QuestionIcon" />
                </Tooltip>
              </InlineStack>
            </FormLayout>
          </BlockStack>
        </BlockStack>
      </Box>

      <template #footer>
        <InlineStack align="end" gap="200">
          <InputBtn :loading="loading" @click="createProject">Submit Project</InputBtn>

        </InlineStack>
      </template>
    </MobileModal>
  </template>

  <template v-else>
    <div style="position: fixed; overflow-y: hidden; top: 0; left: 0; width: 100%; z-index: 5; background: #00000033;">
      <BlockStack inlineAlign="end">
        <Box background="bg-surface" border-radius="300"  @click.stop="null">
          <BlockStack gap="800" align="start" style="min-height: 100vh; width: 550px; padding: 40px">
            <InlineStack align="space-between">

              <Text variant="bodyLg" as="p" alignment="start" tone="subdued">

              </Text>

              <div>
                <Icon :source="XIcon" @click="() => this.$emit('close')"/>
              </div>
            </InlineStack>

            <BlockStack gap="300">
              <BlockStack gap="100">
                <Text variant="headingXl" fontWeight="bold" as="h2">
                  Submit your project
                </Text>
                <Text v-if="user?.role_id === this.CLIENT_ROLE_ID" variant="bodyLg" as="p" alignment="start" tone="subdued">
                  Connect with the world best Shopify developers.
                </Text>
                <Text v-else-if="user?.role_id === this.EXPERT_ROLE_ID" variant="bodyLg" as="p" alignment="start" tone="subdued">
                  Create a Project for any merchant, registered or not.<br>
                  Select their account by name if they're registered, or enter<br>
                  their email if not. <strong>The project starts once they register.</strong>
                </Text>
              </BlockStack>
            </BlockStack>

            <div v-if="user?.role_id === this.EXPERT_ROLE_ID" style="display: flex; flex-direction: row;">
              <InputBtn :class="existingClient ? 'active-hover-left' : 'select-tab-left'" @click="toggleClientSelection">Existing Client</InputBtn>
              <InputBtn :class="newClient ? 'active-hover-right' : 'select-tab-right'" @click="toggleClientSelection">New Client</InputBtn>
            </div>

            <BlockStack gap="800">
              <FormLayout>

                <BlockStack v-if="user?.role_id === this.EXPERT_ROLE_ID">
                  <BlockStack v-if="existingClient">
                    <Combobox preferredPosition="cover">
                      <template #activator>
                        <ComboboxTextField
                            v-model="clientName"
                            placeholder="Enter Client full Name ..."
                            autoComplete="off"
                            @input="(_e, input) => updateClient(input)"
                            :error="errors.client_id"
                        >
                          <template #label>
                            <InlineStack>
                              Client Name
                            </InlineStack>
                          </template>
                          <template #prefix>
                            <Icon :source="SearchIcon" />
                          </template>
                        </ComboboxTextField>
                      </template>

                      <Listbox
                          v-if="loadingClients || loadedClients.length > 0"
                          @select="updateClientSelection"
                      >
                        <template v-if="!loadingClients">
                          <Scrollable :style="{ maxHeight: '320px' }">
                            <ListboxOption
                                style="padding: 0!important;"
                                v-for="client in loadedClients"
                                :key="client.id"
                                :value="client">
                              <Box paddingInline="200" paddingBlock="050" width="100%">
                                <Box :background="selectedClient && selectedClient.id === client.id ? 'bg-surface-secondary' : null"
                                     borderWidth="025" borderColor="border" borderRadius="300"
                                     paddingInline="400" paddingBlock="200" style="width: 100%; cursor: pointer">
                                  <InlineStack gap="300" blockAlign="center">
                                    <AvatarFrame rounded size="lg" :user="client" />

                                    <BlockStack>
                                      <Text>{{ client.first_name + ' ' + client.last_name + '.' }}</Text>
                                      <Text variant="subdued">{{ client.first_name }}</Text>
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
                  </BlockStack>

                  <BlockStack gap="200" v-if="newClient">
                    <TextField
                        label="Client Name"
                        autoComplete="off"
                        v-model="projectForm.client_name"
                        placeholder="Enter Client full Name ..."
                        :error="errors.client_id" />

                    <TextField
                        label="Client Email"
                        autoComplete="off"
                        v-model="projectForm.client_email"
                        placeholder="client@example.com"
                        :error="errors.client_email" />

                  </BlockStack>
                </BlockStack>
                <div v-else>
                  <TextField
                      autoComplete="off"
                      v-model="projectForm.url"
                      :error="errors.url" >
                    <template #label>
                      <InlineStack>
                        Your website
                        <Tooltip content="This is your default store, you can change the URL if you want.">
                          <Icon :source="QuestionIcon" />
                        </Tooltip>
                      </InlineStack>
                    </template>
                  </TextField>
                </div>

                <TextField
                    label="Project title"
                    autoComplete="off"
                    v-model="projectForm.title"
                    placeholder="Give your project a short and descriptive title"
                    :error="errors.title" />

                <TextField
                    label="About the project"
                    autoComplete="off"
                    v-model="projectForm.description"
                    :multiline="5"
                    placeholder="Please try to be as detailed as possible, as this helps us provide you with the best possible solutions ..."
                    :error="errors.description" />

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

                    <input style="display: none" type="file" ref="file"
                           @change="loadFile($event)" accept="image/*,.pdf,.doc,.docx" multiple/>
                  </InlineStack>

                  <InlineStack v-if="projectForm.files" gap="100">
                    <Button :icon="XIcon" v-for="(file, index) in projectForm.files" :key="file" @click="removeFile(index)">{{ file.name }}</Button>
                  </InlineStack>
                </BlockStack>

                <Combobox preferredPosition="cover">
                  <template #activator>
                    <ComboboxTextField
                        v-model="expertName"
                        placeholder="Enter the name of an expert"
                        autoComplete="off"
                        @input="(_e, input) => updateText(input)"
                    >
                      <template #label>
                        <InlineStack>
                          Preferred expert (optional)
                          <Tooltip content="Use the input below if you worked with one of our experts before, or have received a recommendation.">
                            <Icon :source="QuestionIcon" />
                          </Tooltip>
                        </InlineStack>
                      </template>
                      <template #prefix>
                        <Icon :source="SearchIcon" />
                      </template>
                    </ComboboxTextField>
                  </template>

                  <Listbox
                      v-if="loadingExperts || loadedExperts.length > 0"
                      @select="updateExpertSelection"
                  >
                    <template v-if="!loadingExperts">
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

                <Checkbox v-model="projectForm.urgent">
                  <template #label>
                    <InlineStack>
                      This project is urgent
                      <Tooltip content="We offer express quotes for priority projects to shorten the assessment time and expedite service delivery.">
                        <Icon :source="QuestionIcon" />
                      </Tooltip>
                    </InlineStack>
                  </template>
                </Checkbox>
              </FormLayout>
            </BlockStack>

            <InputBtn :loading="loading" @click="createProject">Submit Project</InputBtn>
          </BlockStack>
        </Box>
      </BlockStack>
    </div>
  </template>
</template>

<style scoped>
.select-tab-right {
  width: 50%;
  border-top-left-radius: 0px;
  border-bottom-left-radius: 0px;
  border: gray !important;
  background-color: initial !important; /* Ensure background doesn't change */
  color: initial !important; /* Override hover text color */
  border: none !important;
}

/* Prevent hover effect */
.select-tab-right:hover {
  background-color: black !important; /* Ensure background doesn't change */
  color: white !important; /* Override hover text color */
  border: none !important; /* Remove border changes */
}

.active-hover-right:hover {
  background-color: black !important; /* Ensure background doesn't change */
  color: white !important; /* Override hover text color */
  border: none !important; /* Remove border changes */
}

.select-tab-left {
  width: 50%;
  border-top-right-radius: 0px;
  border-bottom-right-radius: 0px;
  background-color: initial !important; /* Ensure background doesn't change */
  color: initial !important; /* Override hover text color */
  border: none !important;
}

/* Prevent hover effect */
.select-tab-left:hover {
  background-color: black !important; /* Ensure background doesn't change */
  color: white !important; /* Override hover text color */
  border: none !important; /* Remove border changes */
}

.active-hover-left:hover {
  background-color: black !important; /* Ensure background doesn't change */
  color: white !important; /* Override hover text color */
  border: none !important; /* Remove border changes */
}

.active-hover-left {
  width: 50%;
  border-top-right-radius: 0px;
  border-bottom-right-radius: 0px;
  background-color: black !important; /* Ensure background doesn't change */
  color: white !important; /* Override hover text color */
  border: none !important; /* Remove border changes */
}

.active-hover-right {
  width: 50%;
  border-top-left-radius: 0px;
  border-bottom-left-radius: 0px;
  background-color: black !important; /* Ensure background doesn't change */
  color: white !important; /* Override hover text color */
  border: none !important; /* Remove border changes */
}
</style>