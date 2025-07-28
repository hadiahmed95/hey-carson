<script>
import XIcon from "@/components/icons/XIcon.vue"
import MobileModal from "@/components/MobileModal.vue"
import axios from "axios"
import InputBtn from "@/components/misc/InputBtn.vue";
import {debounce} from "@/directives/debounce";

export default {
  name: "CreateQuestionModal",
  components: {InputBtn, MobileModal},
  data() {
    return {
      XIcon,
      isMobile: screen.width <= 760,
      loading: false,
      questionForm: {
        content: '',
      },
      hasError: 0,
      errors: {
        content: null,
      },
    }
  },
  methods: {
    createQuestion: debounce(async function() {
      this.hasError = 0
      if (!this.questionForm.content) {
        this.hasError++
        this.errors.content = 'Question content is required'
      } else {
        this.errors.content = null
      }
      const form = new FormData()
      form.append('content', this.questionForm.content)
      if (!this.hasError) {
        this.loading = true
        await axios.post('api/client/questions', form, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then(() => {
          this.loading = false
          this.questionForm = {
            content: '',
          }
          this.$emit('createdQuestion')
        }).catch(err => {
          console.log(err)
          this.$emit('createdQuestion')
        })
      }
    }, 200),
  }
}
</script>

<template>
  <template v-if="isMobile">
    <MobileModal>
      <template #heading>
        <BlockStack gap="100">
          <InlineStack align="space-between">
            <Text variant="headingXl" fontWeight="bold" as="h2">
              Submit your question
            </Text>
            <div>
              <Icon :source="XIcon" @click="() => this.$emit('close')" />
            </div>
          </InlineStack>
          <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
            If you have any technical question about Shopify, you are definitely
            in the right place to ask! Our experts are here to help and have
            answers to all your queries. Please note this is not a project.
          </Text>
        </BlockStack>
      </template>
      <Box class="question-content">
        <BlockStack gap="400">
          <FormLayout>
            <TextField
                label="Your question"
                autoComplete="off"
                v-model="questionForm.content"
                :multiline="5"
                placeholder="Please write your question here, being as detailed as possible."
                :error="errors.content"
            />
          </FormLayout>
        </BlockStack>
      </Box>
      <template #footer>
        <InlineStack align="end" gap="200">
          <InputBtn :loading="loading" @click="createQuestion">Submit Question</InputBtn>
        </InlineStack>
      </template>
    </MobileModal>
  </template>

  <template v-else>
    <div
        class="desktop"
        @click="() => this.$emit('close')"
    >
      <BlockStack inlineAlign="end">
        <Box background="bg-surface" border-radius="300" @click.stop="null">
          <BlockStack
              gap="800"
              align="start"
              class="submit-questions"
          >
            <BlockStack gap="300">
              <InlineStack align="space-between">
                <Text variant="headingXl" fontWeight="bold" as="h2">
                  Submit your question
                </Text>
                <div>
                  <Icon :source="XIcon" @click="() => this.$emit('close')" />
                </div>
              </InlineStack>
              <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
                If you have any technical question about Shopify, you are
                definitely in the right place to ask! Our experts are here to
                help and have answers to all your queries. Note this is not
                project.
              </Text>
            </BlockStack>
            <FormLayout>
              <TextField
                  label="Your question"
                  autoComplete="off"
                  v-model="questionForm.content"
                  :multiline="5"
                  placeholder="Ex. I have encountered security issues with my Shopify store and I'm worried about the safety of my customer data. Could you provide the comprehensive overview of the specific security measures you implement, such as encryption protocol, firewall configurations, and regular security audits?"
                  :error="errors.content"
              />
            </FormLayout>

            <InputBtn :loading="loading" @click="createQuestion">Submit Question</InputBtn>
          </BlockStack>
        </Box>
      </BlockStack>
    </div>
  </template>
</template>

<style scoped>
.desktop {
  position: fixed;
  overflow-y: hidden;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 40;
  background: #00000033;
}

.submit-questions {
  min-height: 100vh;
  width: 550px;
  padding: 40px
}

.question-content{
  padding: 16px;
  max-height: 50vh;
  overflow: scroll
}
</style>
