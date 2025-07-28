<script>
import XIcon from "@/components/icons/XIcon.vue";
import MobileModal from "@/components/MobileModal.vue";
import axios from "axios";
import InputBtn from "@/components/misc/InputBtn.vue";
import {debounce} from "@/directives/debounce";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import common from "@/mixins/common";
import CheckCircle from "@/components/icons/CheckCircle.vue";

export default {
  name: "CreateProject",

  props: {
    projectId: {
      default: 0,
      type: Number
    },
    clientPrepaidHours: {
      default: 0,
      type: Number
    },
    scope: {
      default: false,
      type: Boolean
    },
    editQuote: {
      default: false,
      type: Boolean
    }
  },

  components: {InputBtn, MobileModal, VueDatePicker},
  mixins: [common],

  data() {
    return {
      XIcon,

      CheckCircle,
      isMobile: screen.width <= 760,

      loading: false,

      form: {
        hours: 5,
        rate: 95
      },

      deadline: new Date(),
      errors: {
        deadline: null,
        hours: null,
      }
    }
  },

  computed: {
    total() {
      return (this.form.hours * this.form.rate).toFixed(2)
    }
  },

  methods: {
    createOffer: debounce(async function() {
      this.loading = true;
      await axios.post('api/expert/projects/' + this.projectId + '/offer', {
        type: this.scope ? 'scope' : 'offer',
        hours: this.form.hours,
        deadline: this.deadline,
        rate: this.form.rate
      }).then(() => {
        this.form = {
          hours: 5,
          rate: 95.00
        }
        this.loading = false;
        this.$emit('update')
      }).catch((err) => {
        this.loading = false;
        this.errors.deadline = err.response?.data?.errors?.deadline?.[0]
        this.errors.hours = err.response?.data?.errors?.hours?.[0]

        setTimeout(() => {
          this.errors.deadline = null
          this.errors.hours = null
        }, 5000)
      });
    }, 200),

    updateQuote: debounce(async function() {
      this.loading = true;
      await axios.put('api/expert/projects/' + this.projectId + '/offer', {
        type: this.scope ? 'scope' : 'offer',
        hours: this.form.hours,
        deadline: this.deadline,
        rate: this.form.rate
      }).then(() => {
        this.form = {
          hours: 5,
          rate: 95.00
        }
        this.loading = false;
        this.$emit('update')
      }).catch((err) => {
        this.loading = false;
        this.errors.deadline = err.response?.data?.errors?.deadline?.[0]
        this.errors.hours = err.response?.data?.errors?.hours?.[0]

        setTimeout(() => {
          this.errors.deadline = null
          this.errors.hours = null
        }, 5000)
      });
    }, 200),
  }
}
</script>

<template>
  <template v-if="isMobile">
    <MobileModal>
      <template #heading>
        <InlineStack align="space-between">
          <BlockStack gap="100">
            <Text variant="headingMd" as="p" alignment="start" v-if="editQuote">
              Edit Quote Details
            </Text>
            <Text variant="headingMd" as="p" alignment="start" v-else-if="scope">
              Add to project scope
            </Text>
            <Text variant="headingMd" as="p" alignment="start" v-else>
              Send your offer to the client
            </Text>
          </BlockStack>

          <div>
            <Icon :source="XIcon" @click="() => this.$emit('close')"/>
          </div>
        </InlineStack>
      </template>

      <BlockStack gap="400" align="start" style="padding: 16px;">
        <BlockStack gap="100">
          <Text>Choose an option</Text>

          <InlineStack align="space-between" gap="200">
            <Box style="flex: 1"
                 :background="form.rate === 95 ? 'bg-surface-secondary' : null"
                 class="payment-option"
                 borderWidth="025"
                 borderColor="border"
                 borderRadius="300"
                 paddingBlock="200"
                 paddingInline="400"
                 @click="() => {form.rate = 95}">
              <BlockStack blockAlign="center" gap="200">
                <Text as="p">
                  Standard rate
                </Text>
                <Text as="p" variant="headingLg">
                  $95/hour
                </Text>
              </BlockStack>
            </Box>

            <Box style="flex: 1"
                 :background="form.rate === 125 ? 'bg-surface-secondary' : null"
                 class="payment-option"
                 borderWidth="025"
                 borderColor="border"
                 borderRadius="300"
                 paddingBlock="200"
                 paddingInline="400"
                 @click="() => {form.rate = 125}">
              <BlockStack blockAlign="center" gap="200">
                <Text as="p">
                  Express rate
                </Text>
                <Text as="p" variant="headingLg">
                  $125/hour
                </Text>
              </BlockStack>
            </Box>
          </InlineStack>
        </BlockStack>

        <TextField
            label="Estimated time needed for this project (hours)"
            type="number"
            autoComplete="off"
            min="1"
            step="1"
            v-model="form.hours"
            :helpText="scope && !errors.hours ? 'These are additional hours for the current project.' : ''"
            :error="errors.hours" />

        <div>
          Deadline date
          <VueDatePicker
              v-model="deadline"
              class="dp__theme_light date-picker"
              :enable-time-picker="false"
              :format="formatDeadline"
              :min-date="new Date()"
          />
          <Text variant="bodyLg"
                as="p"
                class="text-error"
                alignment="start"
                tone="subdued"
                v-if="errors?.deadline"
          >
            {{ errors?.deadline }}
          </Text>
          <Text variant="bodySm" as="p" alignment="start" tone="subdued" v-else>
            Set a deadline that's achievable, considering the project scope.
          </Text>
        </div>

        <BlockStack>
          <Text variant="bodyMd" tone="subdued" as="p">
            Project quote
          </Text>

          <Text variant="headingLg" as="p">
            ${{ total }}
          </Text>
        </BlockStack>
      </BlockStack>

      <template #footer>
        <InlineStack align="end">
          <InputBtn v-if="editQuote" :loading="loading" :icon="CheckCircle" @click="updateQuote">Update Quote</InputBtn>
          <InputBtn v-else-if="scope" :loading="loading" @click="createOffer">Send project scope</InputBtn>
          <InputBtn v-else :loading="loading" @click="createOffer">Send project quote</InputBtn>
        </InlineStack>
      </template>
    </MobileModal>

  </template>
  <template v-else>
    <div style="position: fixed; overflow-y: hidden; top: 0; left: 0; width: 100%; z-index: 1000; background: #00000033;">
      <BlockStack inlineAlign="end">
        <Box background="bg-surface" border-radius="300" @click.stop="null">
          <BlockStack gap="800" align="start" style="min-height: 100vh; width: 550px; padding: 40px">
            <InlineStack align="space-between">
              <BlockStack gap="300">
                <Text variant="headingXl" as="p" alignment="start" v-if="editQuote">
                  Edit Quote Details
                </Text>
                <Text variant="headingXl" as="p" alignment="start" v-else-if="scope">
                  Add to project scope
                </Text>
                <Text variant="headingXl" as="p" alignment="start" v-else>
                  Send your offer to the client
                </Text>
                <Text tone="subdued">Client's Balance: <Text as="span" variant="bodySm" fontWeight="semibold">{{ clientPrepaidHours }}</Text> Prepaid Hours</Text>
              </BlockStack>

              <div style="cursor: pointer;">
                <Icon :source="XIcon" @click="() => this.$emit('close')"/>
              </div>
            </InlineStack>

            <BlockStack gap="100">
              <Text>Choose an option</Text>

              <InlineStack align="space-between" gap="200">
                <Box style="flex: 1; cursor: default" :style="form.rate === 95 ? null : 'cursor: pointer'"
                     :background="form.rate === 95 ? 'bg-surface-secondary' : null"
                     class="payment-option"
                     borderWidth="025"
                     borderColor="border"
                     borderRadius="300"
                     paddingBlock="200"
                     paddingInline="400"
                     @click="() => {form.rate = 95}">
                  <BlockStack blockAlign="center" gap="200">
                    <Text as="p">
                      Standard rate
                    </Text>
                    <Text as="p" variant="headingLg">
                      $95/hour
                    </Text>
                  </BlockStack>
                </Box>

                <Box style="flex: 1; cursor: default" :style="form.rate === 125 ? null : 'cursor: pointer'"
                     :background="form.rate === 125 ? 'bg-surface-secondary' : null"
                     class="payment-option"
                     borderWidth="025"
                     borderColor="border"
                     borderRadius="300"
                     paddingBlock="200"
                     paddingInline="400"
                     @click="() => {form.rate = 125}">
                  <BlockStack blockAlign="center" gap="200">
                    <Text as="p">
                      Express rate
                    </Text>
                    <Text as="p" variant="headingLg">
                      $125/hour
                    </Text>
                  </BlockStack>
                </Box>
              </InlineStack>
            </BlockStack>

            <TextField
                label="Estimated time needed for this project (hours)"
                type="number"
                autoComplete="off"
                min="1"
                step="1"
                v-model="form.hours"
                :helpText="scope && !errors.hours ? 'These are additional hours for the current project.' : ''"
                :error="errors.hours" />

            <div>
              Deadline date
              <VueDatePicker
                  v-model="deadline"
                  class="dp__theme_light date-picker"
                  :enable-time-picker="false"
                  :format="formatDeadline"
                  :min-date="new Date()"
              />
              <Text variant="bodyLg"
                  as="p"
                  class="text-error"
                  alignment="start"
                  tone="subdued"
                  v-if="errors?.deadline"
              >
                {{ errors?.deadline }}
              </Text>
              <Text variant="bodySm" as="p" alignment="start" tone="subdued" v-else>
                Set a deadline that's achievable, considering the project scope.
              </Text>
            </div>

            <BlockStack>
              <Text variant="bodyMd" tone="subdued" as="p">
                Project quote
              </Text>

              <Text variant="headingLg" as="p">
                ${{ total }}
              </Text>
            </BlockStack>

            <InputBtn v-if="editQuote" :loading="loading" :icon="CheckCircle" @click="updateQuote">Update Quote</InputBtn>
            <InputBtn v-else-if="scope" :loading="loading" @click="createOffer">Send project scope</InputBtn>
            <InputBtn v-else :loading="loading" @click="createOffer">Send project quote</InputBtn>
          </BlockStack>
        </Box>
      </BlockStack>
    </div>
  </template>
</template>

<style scoped>
  .date-picker {
    margin-top: 5px;
    margin-bottom: 5px;
  }

  .dp__theme_light {
    --dp-border-color: rgb(200, 200, 200);
    --dp-border-radius: 10px;
    --dp-border-color-hover: rgb(160, 160, 160);
    --dp-border-color-focus: rgb(160, 160, 160);
  }

  .text-error {
    color: darkred;
  }
</style>