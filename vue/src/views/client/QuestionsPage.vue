<script>
import emptyState from "@/assets/empty-state.png"
import SearchIcon from "@/components/icons/SearchIcon.vue"
import LoadingCards from "@/components/cards/LoadingCards.vue"
import AddIcon from "@/components/icons/AddIcon.vue"
import ClientLayout from "@/layout/ClientLayout.vue"
import AvatarFrame from "@/components/misc/AvatarFrame.vue"
import CreateQuestionModal from "@/components/modals/CreateQuestionModal.vue"
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
    ClientLayout,
    LoadingCards,
    CreateQuestionModal,
    MobileCard
  },

  watch: {
    search: debounce(function() {
      this.defaultPageSettings()
      this.getQuestions('others', this.search)
    }, 400),
  },

  data() {
    return {
      isMobile: screen.width <= 760,
      spinnerLoading: false,
      pageCount: 1,
      lastPage: 1,
      emptyState,
      SearchIcon,
      AddIcon,
      search: '',
      questionModal: false,
      activeTab: 0,
      tabs: [
        {
          id: 'my-questions',
          content: 'My Questions',
        },
        {
          id: 'read-questions',
          content: 'Read Questions',
        },
      ],
      pageLoading: false,
      questions: [],
    }
  },

  computed: {
    mobileHorizontalPadding() {
      return {
        padding: this.isMobile ? '0 16px' : ''
      }
    },
  },

  async mounted() {
    await this.getQuestions()
  },

  methods: {
    async getQuestions(type = 'self', search = null) {
      this.pageLoading = true
      this.spinnerLoading = true
      this.questionModal = false
      await axios.get(`api/client/questions?page=${this.pageCount}`, {params: {'type': type, 'search': search}}).then(res => {
        this.questions.push(...res.data.data)
        this.lastPage = res.data.meta.last_page
        this.pageCount += 1
      }).catch(err => {
        console.log(err)
      }).finally(() => {
        this.pageLoading = false
        this.spinnerLoading = false
      })
    },

    changeTab(tab) {
      this.defaultPageSettings()
      this.activeTab = tab
      if (this.activeTab === 0) {
        this.getQuestions('self')
      }
      else {
        this.getQuestions('others')
      }
    },

    defaultPageSettings() {
      this.pageCount = 1
      this.questions = []
    },

    refreshQuestions() {
      this.questionModal = false
      this.activeTab = 0
      this.defaultPageSettings()
      this.getQuestions()
    },

    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('Do MMMM, YYYY')
    },

    loadMore() {
      if (this.pageCount > this.lastPage)
        return

      if (this.questions.length === 0)
        return

      if (this.activeTab === 0) {
        this.getQuestions('self')
      }
      else {
        this.getQuestions('others', this.search)
      }
    }
  }
}
</script>

<template>
  <ClientLayout v-infinite-scroll="{ threshold: 1, callback: loadMore }" class="scrollable-container">
    <Page class="questions" title="Questions">
      <template #secondaryActions>
        <InputBtn :icon="AddIcon"  @click="() => this.questionModal = true">Submit Question</InputBtn>
      </template>

      <BlockStack gap="200">
        <InlineStack
            class="container"
            :style="mobileHorizontalPadding"
            :class="{ 'inline-block':isMobile }"
        >
          <Tabs
              class="tabs"
              :tabs="tabs"
              :selected="activeTab"
              @select="changeTab"
          />
          <TextField
              v-if="activeTab === 1"
              class="z-index"
              :class="{ 'pt-16 pb-16 pl-16':isMobile }"
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
        </InlineStack>

        <BlockStack
            gap="200"
            :style="mobileHorizontalPadding"
            v-if="questions.length"
        >
          <MobileCard
              padding="600"
              v-for="question in questions"
              :key="question.id"
              class="z-index"
          >
            <BlockStack gap="400">
              <BlockStack gap="400">
                <Text
                    as="p"
                    variant="bodyMd"
                    tone="subdued"
                    v-if="this.activeTab === 0"
                >
                  Answers ({{ question.answers.length }})
                </Text>
                <Text as="p" variant="bodyMd" tone="subdued" v-else>
                  Question asked by:
                  {{ question.client.full_name }}
                </Text>
                <Text variant="headingLg" fontWeight="bold" as="h2">
                  {{ question.content }}
                </Text>

                <Text variant="bodyMd" as="p" />
              </BlockStack>
              <Divider v-if="question.answers.length" />
              <BlockStack gap="200">
                <MobileCard
                    padding="600"
                    v-for="answer in question.answers"
                    :key="answer.id"
                    class="z-index"
                >
                  <BlockStack gap="800">
                    <InlineStack
                        align="space-between"
                        blockAlign="start"
                    >
                      <InlineStack gap="200">
                        <AvatarFrame rounded size="lg" :user="answer.expert" />

                        <BlockStack gap="050">
                          <Text as="p" variant="bodyLg" fontWeight="semibold">
                            {{ answer.expert.full_name }}
                          </Text>
                          <Text as="p" variant="bodySm" tone="subdued">
                            {{ answer.expert.profile.role }}
                          </Text>
                        </BlockStack>
                      </InlineStack>
                      <BlockStack gap="50" :class="{ 'pt-16 inline-block':isMobile }">
                        <Text as="p" variant="bodySm" tone="subdued">
                          <Text as="span">
                            Posted {{ formatDate(answer.created_at) }}
                          </Text>
                        </Text>
                      </BlockStack>
                    </InlineStack>
                    <InlineStack class="container" gap="200">
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
            </BlockStack>
          </MobileCard>
        </BlockStack>

        <LoadingCards
            :style="mobileHorizontalPadding"
            gap="300"
            v-else-if="pageLoading"
        />

        <BlockStack :style="mobileHorizontalPadding" gap="200" v-else>
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
    <CreateQuestionModal
        v-show="questionModal"
        @createdQuestion="refreshQuestions"
        @close="() => this.questionModal = false"
    />
  </ClientLayout>
  <LoadingSpinner :isLoading="spinnerLoading" />
</template>

<style scoped>
.container {
  display: flex;
  align-items: center;
}

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

.pb-16 {
  padding-bottom: 16px;
}

.pl-16 {
  padding-left: 16px;
}

.tabs {
  flex: 1;
  padding: 0;
}

.z-index {
  z-index: 0;
}

.scrollable-container {
  height: 100vh;
  overflow-y: auto;
}

</style>
