<script>
import emptyState from "@/assets/empty-state.png"
import LoadingCards from "@/components/cards/LoadingCards.vue"
import EditIcon from "@/components/icons/EditIcon.vue"
import AdminLayout from "@/layout/AdminLayout.vue"
import MobileCard from "@/components/MobileCard.vue"
import axios from "axios"
import QuestionsCard from "@/components/cards/admin/QuestionsCard.vue";

export default {
  name: "QuestionPage",
  components: {
    QuestionsCard,
    AdminLayout,
    LoadingCards,
    MobileCard
  },

  data() {
    return {
      EditIcon,
      emptyState,
      isMobile: screen.width <= 760,
      pageLoading: false,
      actionsLoading: false,
      question: null,
      answers: null,
      moderateQuestions: 0
    }
  },

  async mounted() {
    this.pageLoading = true
    await this.getQuestionBy(this.$route.params.id)
  },

  computed: {
    mobileHorizontalPadding() {
      return {
        padding: this.isMobile ? '0 16px' : '',
      }
    },
  },

  methods: {
    async getQuestionBy(id) {
      this.questionModal = false
      await axios.get(`api/admin/questions/${id}`).then(res => {
        this.question = res.data.data
        this.answers = this.question.answers
        this.pageLoading = false
      }).catch(err => {
        console.log(err)
        this.pageLoading = false
      })
    },

    refresh() {
      this.pageLoading = true
      this.getQuestionBy(this.question?.id)
    },


  }
}
</script>

<template>
  <AdminLayout>
    <Page class="answers-on-questions" title="Answers on Question">
      <BlockStack gap="600" :style="mobileHorizontalPadding">
        <BlockStack v-if="question">
          <QuestionsCard
              padding="600"
              :key="question.id"
              :question="question"
              :answers="question.answers"
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
</template>

<style scoped>
.answers-on-questions {
  padding-top: 56px;
  padding-bottom: 56px;
}
</style>
