<script>
import emptyState from "@/assets/empty-state.png"
import SearchIcon from "@/components/icons/SearchIcon.vue"
import LoadingCards from "@/components/cards/LoadingCards.vue"
import EditIcon from "@/components/icons/EditIcon.vue"
import ExpertLayout from "@/layout/ExpertLayout.vue"
import AvatarFrame from "@/components/misc/AvatarFrame.vue"
import MobileCard from "@/components/MobileCard.vue"
import LoadingSpinner from '@/components/misc/LoadingSpinner.vue';
import axios from "axios"
import moment from "moment"
import InputBtn from "@/components/misc/InputBtn.vue";
import {debounce} from "@/directives/debounce";

export default {
  name: "QuestionsPage",
  components: {
    LoadingSpinner,
    InputBtn,
    AvatarFrame,
    ExpertLayout,
    LoadingCards,
    MobileCard
  },

  watch: {
    search: debounce(function () {
      this.defaultPageSettings()
      this.getQuestions(this.activeTab, this.search)
    }, 400),
  },

  data() {
    return {
      EditIcon,
      emptyState,
      SearchIcon,
      isMobile: screen.width <= 760,
      search: '',
      activeTab: 0,
      spinnerLoading: false,
      pageCount: 1,
      lastPage: 1,
      tabs: [
        {
          id: 'read-questions',
          content: 'Read Questions',
        },
        {
          id: 'my-answers',
          content: 'My Answers',
        },
      ],
      expertAnswer: {
        content: '',
      },
      selectedQuestionId: null,
      selectedAnswerId: null,
      errors: {
        content: null,
      },
      pageLoading: false,
      questions: [],
    }
  },

  async mounted() {
    await this.getQuestions()
  },

  computed: {
    mobileHorizontalPadding() {
      return {
        padding: this.isMobile ? '0 16px' : '',
      }
    },
  },

  methods: {
    async getQuestions(activeTab = 0, search = null) {
      this.pageLoading = true
      this.spinnerLoading = true
      this.questionModal = false
      let type = 'others'
      if (activeTab === 1) {
        type = 'self'
      }
      await axios.get(`api/expert/answers?page=${this.pageCount}`, {params: {'type': type, 'search': search}}).then(res => {
        this.questions.push(...res.data.data)
        this.pageCount += 1
        this.lastPage = res.data.meta.last_page
      }).catch(err => {
        console.log(err)
      }).finally(() => {
        this.pageLoading = false
        this.spinnerLoading = false
        this.setDefault()
      })
    },

    updateSelectedQuestion(id) {
      if (this.selectedQuestionId === id) {
        return
      }
      this.setDefault()
      this.selectedQuestionId = id
      this.updateSelectedAnswer(this.getSelectedAnswer(id))
    },

    updateSelectedAnswer(selectedAnswer = null) {
      if (selectedAnswer) {
        this.selectedAnswerId = selectedAnswer.id
        this.expertAnswer.content = selectedAnswer.content
      }
    },

    setDefault() {
      this.selectedAnswerId = null
      this.selectedQuestionId = null
      this.expertAnswer.content = ''
    },

    getSelectedAnswer(selectedQuestionId) {
      const expert = JSON.parse(window.localStorage.getItem('CURRENT_USER'))
      const selectedAnswers = this.questions
          .filter((question) => selectedQuestionId === question.id)[0]['answers']
      if (selectedAnswers) {
        let selectedAnswer = selectedAnswers.filter((answer) => answer.expert_id === expert.id)
        if (selectedAnswers) {
          selectedAnswer = selectedAnswer[0]
          return selectedAnswer
        }
      }
      return null
    },

    toggleEditForm(selectedQuestionId) {
      if (this.selectedQuestionId && selectedQuestionId === this.selectedQuestionId) {
        this.setDefault()
      }
      else {
        this.selectedQuestionId = selectedQuestionId
        this.updateSelectedAnswer(this.getSelectedAnswer(selectedQuestionId))
      }
    },

    changeTab(tab) {
      this.activeTab = tab
      this.defaultPageSettings()
      this.getQuestions(this.activeTab)
    },

    refreshQuestions() {
      this.activeTab = 1
      this.defaultPageSettings()
      this.getQuestions(this.activeTab)
    },

    async createAnswer(question_id) {
      this.hasError = 0;

      if (!this.expertAnswer.content) {
        this.hasError++
        this.errors.content = 'Answer is required'
      } else {
        this.errors.content = null
      }
      const form = new FormData()
      form.append('content', this.expertAnswer.content)
      form.append('question_id', question_id)
      if (!this.hasError) {
        this.loading = true
        await axios.post('api/expert/answers', form, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then(() => {
          this.loading = false
          this.expertAnswer = {
            content: '',
          }
          this.refreshQuestions()
        }).catch(err => {
          console.log(err)
          this.refreshQuestions()
        })
      }
    },

    async editAnswer(answer_id) {
      this.hasError = 0
      if (!this.expertAnswer.content) {
        this.hasError++
        this.errors.content = 'Answer is required'
      } else {
        this.errors.content = null
      }

      const payload = { 'content': this.expertAnswer.content }

      if (!this.hasError) {
        this.loading = true
        await axios.put(`api/expert/answers/${answer_id}`, payload, {
          headers: {
            'Content-Type': 'application/json'
          }
        }).then(() => {
          this.loading = false
          this.expertAnswer = {
            content: '',
          }
          this.refreshQuestions()
        }).catch(err => {
          console.log(err)
          this.refreshQuestions()
        })
      }
    },

    isShowAnswer(answer) {
      const expert = JSON.parse(window.localStorage.getItem('CURRENT_USER'))
      if (this.activeTab === 0 && answer.expert_id !== expert.id)
        return true
      else if (this.activeTab === 1 && answer.expert_id === expert.id)
        return true
      return false
    },

    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('Do MMMM, YYYY')
    },

    defaultPageSettings() {
      this.pageCount = 1
      this.questions = []
    },

    loadMore() {
      if (this.pageCount > this.lastPage)
        return

      if (this.questions.length === 0)
        return

      this.getQuestions(this.activeTab, this.search)

    }
  }
}
</script>

<template>
  <ExpertLayout v-infinite-scroll="{ threshold: 1, callback: loadMore }" class="scrollable-container">
    <Page class="questions" title="Questions">
      <template #secondaryActions>
        <TextField
            style="min-width: 220px"
            :label="null"
            type="text"
            v-model="search"
            autoComplete="off"
            placeholder="Search questions ..."
        >
          <template #prefix>
            <Icon :source="SearchIcon" />
          </template>
        </TextField>
      </template>
      <BlockStack gap="200" :style="mobileHorizontalPadding">
        <Tabs
            style="padding: 0"
            :tabs="tabs"
            :selected="activeTab"
            @select="changeTab"
        />
        <BlockStack gap="200" v-if="questions?.length">
          <MobileCard
              padding="600"
              :style="{ cursor: (activeTab === 0 && selectedQuestionId !== question.id ) ? 'pointer' : 'default' }"
              v-for="question in questions"
              :key="question.id"
              @click="activeTab === 0 ? updateSelectedQuestion(question.id) : null"
          >
            <BlockStack gap="400">
              <BlockStack gap="400">
                <Text as="p" variant="bodyMd" tone="subdued">
                  Question asked by:
                  {{ question.client.full_name }}
                </Text>
                <Text variant="headingLg" fontWeight="bold" as="h2">
                  {{ question.content }}
                </Text>

                <Text variant="bodyMd" as="p" />
              </BlockStack>
              <Divider
                  v-if="question.answers.length || question.id === selectedQuestionId"
              />
              <BlockStack gap="300">
                <BlockStack v-for="answer in question.answers" :key="answer.id">
                  <MobileCard padding="600" v-if="isShowAnswer(answer)">
                    <BlockStack gap="800">
                      <InlineStack
                          align="space-between"
                          blockAlign="start"
                      >
                        <InlineStack gap="200">
                          <AvatarFrame
                              rounded
                              size="lg"
                              :user="answer.expert"
                          />

                          <BlockStack gap="050">
                            <Text as="p" variant="bodyLg" fontWeight="semibold">
                              {{ answer.expert.full_name }}
                            </Text>
                            <Text as="p" variant="bodySm" tone="subdued">
                              {{ answer.expert.profile.role }}
                            </Text>
                          </BlockStack>
                        </InlineStack>

                        <BlockStack gap="050" :class="{ 'pt-16 inline-block':isMobile }">
                          <Text as="p" variant="bodySm" tone="subdued">
                            <Text as="span">
                              Posted {{ formatDate(answer.created_at) }}
                            </Text>
                          </Text>
                        </BlockStack>
                      </InlineStack>

                      <InlineStack blockAlign="center" gap="200">
                        <Text variant="bodyMd" as="span">
                          {{ answer.content }}
                        </Text>
                        <Text variant="bodySm" tone="subdued" as="p">
                          {{ answer.edited ? '(edited)' : '' }}
                        </Text>
                      </InlineStack>
                    </BlockStack>
                  </MobileCard>
                </BlockStack>

                <FormLayout v-if="selectedQuestionId === question.id">
                  <TextField
                      :label="selectedAnswerId ? 'Edit your answer': 'Write your answer to this question'"
                      autoComplete="off"
                      v-model="expertAnswer.content"
                      :multiline="5"
                      placeholder="Share your answer with kindness and respect. Everyone's perspective is valued here ..."
                      :error="errors.content"
                  />
                  <InlineStack align="space-between" blockAlign="center">
                    <Text as="p" variant="bodySm" tone="subdued">
                      Your email address will not be published.
                    </Text>
                    <InputBtn  @click="selectedAnswerId ? editAnswer(selectedAnswerId) : createAnswer(question.id)"
                               :class="{ 'mt-16':isMobile }">Submit Question</InputBtn>

                  </InlineStack>
                </FormLayout>
              </BlockStack>
              <InlineStack v-if="activeTab === 1">
                <Button
                    :icon="EditIcon"
                    variant="plain"
                    @click="toggleEditForm(question.id)"
                >
                  Edit Answer
                </Button>
              </InlineStack>
            </BlockStack>
          </MobileCard>
        </BlockStack>

        <LoadingCards
            :style="mobileHorizontalPadding"
            gap="300"
            v-else-if="pageLoading"
        />

        <BlockStack gap="200" :style="mobileHorizontalPadding" v-else>
          <MobileCard>
            <EmptyState
                heading="Do you have any technical questions for our experts?"
                :image="emptyState"
            >
              <p>
                Submit a question, and our experts will have the chance to
                answer and resolve your technical doubts related to ecommerce
                solutions.
              </p>
            </EmptyState>
          </MobileCard>
        </BlockStack>
      </BlockStack>
    </Page>
  </ExpertLayout>
  <LoadingSpinner :isLoading="spinnerLoading" />
</template>

<style scoped>
.questions {
  padding-top: 56px;
  padding-bottom: 56px;
}

.inline-block {
  display: inline-block;
}

.pt-16 {
  padding-top: 16px;
}

.mt-16 {
  margin-top: 16px;
}

.scrollable-container {
  height: 100vh;
  overflow-y: auto;
}
</style>
