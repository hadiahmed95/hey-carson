<script>
import emptyState from "@/assets/empty-state.png"
import SearchIcon from "@/components/icons/SearchIcon.vue"
import LoadingCards from "@/components/cards/LoadingCards.vue"
import EditIcon from "@/components/icons/EditIcon.vue"
import AdminLayout from "@/layout/AdminLayout.vue"
import MobileCard from "@/components/MobileCard.vue"
import axios from "axios"
import QuestionsCard from "@/components/cards/admin/QuestionsCard.vue";
import LoadingSpinner from '@/components/misc/LoadingSpinner.vue';
import InputBtn from "@/components/misc/InputBtn.vue";
import {debounce} from "@/directives/debounce";

export default {
  name: "QuestionsPage",
  components: {
    LoadingSpinner,
    InputBtn,
    QuestionsCard,
    AdminLayout,
    LoadingCards,
    MobileCard
  },

  watch: {
    search: debounce(function () {
      this.defaultPageSettings()
      this.getQuestions(this.search)
    }, 400),
  },

  data() {
    return {
      spinnerLoading: false,
      pageCount: 1,
      lastPage: 1,
      EditIcon,
      emptyState,
      SearchIcon,
      isMobile: screen.width <= 760,
      search: '',
      questionsCount: 0,
      statusList: [
        {
          content: 'All',
          role: 'all',
          onAction: () => this.selectAction('all')
        },
        {
          content: 'Pending',
          role: 'created',
          onAction: () => this.selectAction('created')
        },
        {
          content: 'Approved',
          role: 'completed',
          onAction: () => this.selectAction('completed')
        },
        {
          content: 'Declined',
          role: 'declined',
          onAction: () => this.selectAction('declined')
        },
      ],
      actionsPopover: false,
      statusPopover: false,
      activeStatus: 'all',
      pageLoading: false,
      actionsLoading: false,
      questions: [],
      moderateQuestions: 0
    }
  },

  async mounted() {
    await this.getSettings()
    await this.getQuestions()
  },

  computed: {
    mobileHorizontalPadding() {
      return {
        padding: this.isMobile ? '0 16px' : '',
      }
    },
    selectedStatus() {
      return this.statusList.find(status => status.role === this.activeStatus);
    }
  },

  methods: {
    async getQuestions(search = null) {
      this.pageLoading = true
      this.spinnerLoading = true
      this.questionModal = false

      await axios.get(`api/admin/questions?page=${this.pageCount}`, {params: {'status': this.activeStatus, 'search': search}}).then(res => {
        this.questions.push(...res.data.data)
        this.questionsCount = this.questions.length
        this.pageCount += 1
        this.lastPage = res.data.meta.last_page
      }).catch(err => {
        console.log(err)
      }).finally(() => {
        this.pageLoading = false
        this.spinnerLoading = false
      })
    },

    defaultPageSettings() {
      this.pageCount = 1
      this.questions = []
    },

    toggleStatusPopover() {
      this.statusPopover = !this.statusPopover;
    },

    selectAction(status) {
      this.statusPopover = false;
      this.activeStatus = status;

      this.getQuestions();
    },

    refresh() {
      this.getQuestions()
    },

    async getSettings() {
      await axios.get('api/admin/settings', {params: {'type': 'moderate_questions'}}).then(res => {
        this.moderateQuestions = res.data.moderate_questions
      })
    },

    updateSetting: debounce(async function() {
      await axios.post('api/admin/settings', {'type': 'moderate_questions', 'value': !this.moderateQuestions}).then(() => {
        this.moderateQuestions = !this.moderateQuestions
      })
    }, 200),

    loadMore() {
      if (this.pageCount > this.lastPage)
        return

      if (this.questions.length === 0)
        return

      this.getQuestions(this.search)
    }
  }
}
</script>

<template>
  <AdminLayout v-infinite-scroll="{ threshold: 1, callback: loadMore }" class="scrollable-container">
    <Page class="question"
          :title="'Questions ('+questionsCount+')'">
      <template #primaryAction>
        <InlineStack gap="200" :class="{ 'pb-16':isMobile }">
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

        </InlineStack>
      </template>

      <template #pageTitle>
        <Popover
            :active="statusPopover"
            autofocusTarget="first-node"
            @close="toggleStatusPopover"
        >
          <template #activator>
            <Button @click="toggleStatusPopover" size="large"
                    :disclosure="statusPopover ? 'up' : 'down'">Status: {{ selectedStatus.content }}</Button>
          </template>
          <ActionList
              actionRole="menuitem"
              :items="statusList"
          ></ActionList>
        </Popover>
      </template>

      <BlockStack gap="600" :style="mobileHorizontalPadding">
        <InlineStack align="space-between" block-align="center">
          <BlockStack gap="100" :class="{ 'pb-16':isMobile }">
            <Text as="p" variant="headingMd">
              Do you want to moderate questions?
            </Text>

            <Text as="p" variant="bodyMd" tone="subdued" v-if="moderateQuestions">
              At this moment, you need to moderate and approve questions.
            </Text>

            <Text as="p" variant="bodyMd" tone="subdued" v-else>
              At this moment, you don't need to review questions, and they are automatically made public.
            </Text>
          </BlockStack>

          <InlineStack gap="200" v-if="moderateQuestions">
            <InputBtn>Yes</InputBtn>
            <Button @click="updateSetting">No</Button>
          </InlineStack>

          <InlineStack gap="200" v-else>
            <Button @click="updateSetting">Yes</Button>
            <InputBtn>No</InputBtn>
          </InlineStack>
        </InlineStack>

        <BlockStack gap="200" v-if="questions.length">
          <QuestionsCard
              padding="600"
              v-for="question in questions"
              :key="question.id"
              :question="question"
              @refresh="refresh()"
          />
        </BlockStack>

        <LoadingCards
            :style="mobileHorizontalPadding"
            gap="300"
            v-else-if="pageLoading"
        />

        <BlockStack gap="200" :style="mobileHorizontalPadding" v-else>
          <MobileCard>
            <EmptyState
                heading="No question found"
                :image="emptyState"
            >
              <p>
                Currently there are no questions with selected status
              </p>
            </EmptyState>
          </MobileCard>
        </BlockStack>
      </BlockStack>
    </Page>
  </AdminLayout>
  <LoadingSpinner :isLoading="spinnerLoading" />
</template>

<style scoped>
.question {
  padding-top: 56px;
  padding-bottom: 56px;
}
.pb-16 {
  padding-bottom: 16px;
}
.scrollable-container {
  height: 100vh;
  overflow-y: auto;
}
</style>
