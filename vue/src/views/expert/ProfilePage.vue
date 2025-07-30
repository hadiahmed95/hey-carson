<script>
import ExpertLayout from "@/layout/ExpertLayout.vue";
import StarFullIcon from "@/components/icons/StarFullIcon.vue";
import LoadingPage from "@/components/cards/LoadingPage.vue";
import axios from "axios";
import emptyState from "@/assets/empty-state.png"
import moment from "moment/moment";
import AvatarFrame from "@/components/misc/AvatarFrame.vue";
import UserBox from "@/components/misc/UserBox.vue";
import StarEmptyIcon from "@/components/icons/StarEmptyIcon.vue";
import MobileCard from "@/components/MobileCard.vue";
import LoadingCards from "@/components/cards/LoadingCards.vue";
import EditIcon from "@/components/icons/EditIcon.vue";
import LoadingSpinner from '@/components/misc/LoadingSpinner.vue';
import InputBtn from "@/components/misc/InputBtn.vue";

export default {
  name: "ProfilePage",

  components: {
    InputBtn,
    StarEmptyIcon,
    StarFullIcon,
    LoadingCards,
    MobileCard,
    UserBox,
    LoadingPage,
    ExpertLayout,
    AvatarFrame,
    LoadingSpinner
  },

  async mounted() {
    await this.getExpert()
  },

  computed: {
    totalReview() {
      if (this.expert.reviews) {
        return this.expert.reviews.length
      } else {
        return 0
      }
    },

    expertRating() {
      let rating = 0;

      if (this.totalReview) {
        this.expert.reviews.forEach(rev => {
          rating += rev.rate;
        })

        return (rating / this.totalReview).toFixed(1)
      } else {
        return rating.toFixed(1)
      }
    },

    totalProjects() {
      if (this.expert.active_assignments) {
        return this.expert.active_assignments.length
      } else {
        return 0
      }
    },

    claimedProjects() {
      if (this.totalProjects) {
        return this.expert.active_assignments.filter(assignment => assignment.project?.status === 'claimed').length
      } else {
        return 0
      }
    },

    completedProjects() {
      if (this.totalProjects) {
        return this.expert.active_assignments.filter(assignment => assignment.project?.status === 'completed').length
      } else {
        return 0
      }
    },
  },

  data() {
    return {
      StarEmptyIcon,
      StarFullIcon,
      EditIcon,
      emptyState,
      isMobile: screen.width <= 760,

      pageLoading: true,

      activeTab: 0,
      tabs: [
        {
          id: 'profile',
          content: 'Expert Profile',
        },
        {
          id: 'ideas',
          content: 'Project Ideas',
        },
        {
          id: 'question',
          content: 'Answered Questions',
        },
      ],

      user: JSON.parse(window.localStorage.getItem('CURRENT_USER')),

      expert: {},
      expertAnswer: {
        content: '',
      },
      selectedQuestionId: null,
      selectedAnswerId: null,
      errors: {
        content: null,
      },
      questions: [],
      spinnerLoading: false,
      pageCount: 1,
      lastPage: 1,
    }
  },

  methods: {
    changeTab(tab) {
      this.activeTab = tab;
      if (tab === 2) {
        this.pageCount = 1;
        this.questions = [];
        this.getQuestions()
      }
    },

    async getExpert() {
      this.pageLoading = true;
      await axios.get('api/expert/profile').then(res => {
        this.expert = res.data.expert;

        this.pageLoading = false;
      }).catch(() => {
        this.$router.push('/expert')
      });
    },

    async getQuestions(search = null) {
      this.questionModal = false
      let type = 'self'

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

    setDefault() {
      this.selectedAnswerId = null
      this.selectedQuestionId = null
      this.expertAnswer.content = ''
    },

    updateSelectedQuestion(id) {
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

    refreshQuestions() {
      this.pageLoading = true
      this.getQuestions()
    },

    async createAnswer(question_id) {
      this.hasError = 0
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
      if (answer.expert_id !== expert.id)
        return true
      else if (answer.expert_id === expert.id)
        return true
      return false
    },

    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do YYYY')
    },
  }
}
</script>

<template>
  <ExpertLayout>
    <LoadingPage v-if="pageLoading" />

    <Page v-else style="padding-top: 56px; padding-bottom: 56px">
      <BlockStack gap="200">
        <Tabs style="padding: 0"
              :tabs="tabs"
              :selected="activeTab"
              @select="changeTab"
        />

        <InlineGrid v-if="activeTab === 0" :columns="['oneThird', 'twoThirds']" gap="400">
          <MobileCard label="oneThird">
            <BlockStack gap="400">
              <AvatarFrame rounded size="xl" :user="expert" />

              <BlockStack gap="400">
                <BlockStack gap="100">
                  <Text as="h2" variant="headingLg">
                    {{ expert.first_name }} {{ expert.last_name }}
                  </Text>
                  <Text as="p" variant="bodyLg" tone="subdued">
                    {{ expert.profile.role }}
                  </Text>
                  <Text as="p" variant="bodySm" style="color: #005BD3;">
                    {{ expert.email }}
                  </Text>
                </BlockStack>

                <BlockStack gap="100">
                  <InlineStack align="space-between">
                    <Text as="p" variant="bodyMd" tone="subdued">Experience:</Text>

                    <Text as="" variant="bodyMd" style="color: #303030">{{ expert.profile.experience }}</Text>
                  </InlineStack>
                  <InlineStack align="space-between">
                    <Text as="p" variant="bodyMd" tone="subdued">Availability:</Text>

                    <Text as="" variant="bodyMd" style="color: #303030">{{ expert.profile.availability }}</Text>
                  </InlineStack>
                  <InlineStack align="space-between">
                    <Text as="p" variant="bodyMd" tone="subdued">English Level:</Text>

                    <Text as="" variant="bodyMd" style="color: #303030">{{ expert.profile.eng_level }}</Text>
                  </InlineStack>
                </BlockStack>

                <Divider />

                <BlockStack gap="100">
                  <InlineStack align="start" blockAlign="center" gap="100">
                    <div>
                      <Icon :source="StarFullIcon" />
                    </div>
                    <Text as="p" variant="headingLg">{{ expertRating }}</Text>
                    <Text as="p" variant="bodyMd" tone="subdued">({{ totalReview }} Reviews)</Text>
                  </InlineStack>

                  <Text as="p" variant="bodyMd" tone="subdued">
                    {{ completedProjects }}
                    Completed Projects
                  </Text>
                </BlockStack>
              </BlockStack>
            </BlockStack>
          </MobileCard>

          <BlockStack label="twoThirds" gap="400">
            <MobileCard style="height: fit-content">
              <BlockStack gap="200">
                <Text fontWeight="semibold" tone="subdued">Short Bio</Text>

                <Divider />

                <Text>
                  {{ expert.profile.about }}
                </Text>
              </BlockStack>
            </MobileCard>

            <MobileCard style="height: fit-content" v-if="expert.reviews.length">
              <BlockStack gap="600">
                <Text fontWeight="semibold" tone="subdued">Reviews</Text>

                <Divider />

                <template v-for="review in expert.reviews" :key="review.id">
                  <BlockStack gap="400">
                    <BlockStack gap="200">
                      <Text fontWeight="semibold">{{ review.project.name }}</Text>

                      <InlineStack gap="400" align="start" blockAlign="center">
                        <InlineStack gap="200">
                          <div style="width: 14px; height: 24px">
                            <StarFullIcon style="width: 24px; height: 24px" v-if="review.rate > 0" />
                            <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                          </div>
                          <div style="width: 14px; height: 24px">
                            <StarFullIcon style="width: 24px; height: 24px" v-if="review.rate > 1" />
                            <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                          </div>
                          <div style="width: 14px; height: 24px">
                            <StarFullIcon style="width: 24px; height: 24px" v-if="review.rate > 2" />
                            <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                          </div>
                          <div style="width: 14px; height: 24px">
                            <StarFullIcon style="width: 24px; height: 24px" v-if="review.rate > 3" />
                            <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                          </div>
                          <div style="width: 14px; height: 24px">
                            <StarFullIcon style="width: 24px; height: 24px" v-if="review.rate > 4" />
                            <StarEmptyIcon style="width: 24px; height: 24px" v-else />
                          </div>
                        </InlineStack>

                        <Text variant="headingMd" as="p">
                          {{ review.rate.toFixed(2) }}
                        </Text>

                        <Text tone="subdued">{{ formatDate(review.created_at) }}</Text>
                      </InlineStack>
                    </BlockStack>

                    <BlockStack gap="200">
                      <UserBox :user="review.client" />

                      <Text>{{ review.comment }}</Text>
                    </BlockStack>
                  </BlockStack>

                </template>
              </BlockStack>
            </MobileCard>
          </BlockStack>
        </InlineGrid>

        <MobileCard v-if="activeTab === 1">
          <InlineStack align="space-between">
            <BlockStack gap="200">
              <Text variant="headingLg">Coming soon!</Text>
              <Text variant="bodyLg">
                Create Project Ideas for Clients to browse and implement into their application
              </Text>
            </BlockStack>

            <div style="margin: -15px 20px -20px">
              <svg xmlns="http://www.w3.org/2000/svg" width="200" height="104" viewBox="0 0 200 104" fill="none">
                <g filter="url(#filter0_diiii_2314_11712)">
                  <rect y="-8" width="56" height="56" rx="6" fill="white"/>
                </g>
                <g filter="url(#filter1_diiii_2314_11712)">
                  <rect y="64" width="56" height="56" rx="6" fill="white"/>
                </g>
                <g filter="url(#filter2_diiii_2314_11712)">
                  <rect x="72" y="-48" width="56" height="56" rx="6" fill="white"/>
                </g>
                <rect x="72" y="24" width="56" height="56" rx="6" fill="#46FAAA"/>
                <path d="M91.5992 52C91.5992 47.3608 95.36 43.6 99.9992 43.6C104.638 43.6 108.399 47.3608 108.399 52C108.399 52.6627 108.936 53.2 109.599 53.2C110.262 53.2 110.799 52.6627 110.799 52C110.799 46.0353 105.964 41.2 99.9992 41.2C94.0345 41.2 89.1992 46.0353 89.1992 52C89.1992 57.9647 94.0345 62.8 99.9992 62.8C100.662 62.8 101.199 62.2627 101.199 61.6C101.199 60.9373 100.662 60.4 99.9992 60.4C95.36 60.4 91.5992 56.6392 91.5992 52Z" fill="#262A46"/>
                <path d="M102.459 51.6885C102.551 52.3448 103.158 52.8021 103.814 52.7098C104.471 52.6176 104.928 52.0108 104.836 51.3545C104.712 50.4779 104.349 49.6524 103.786 48.9694C103.223 48.2863 102.482 47.7723 101.645 47.4841C100.808 47.1959 99.9076 47.1447 99.0434 47.3363C98.1791 47.5279 97.3847 47.9548 96.7479 48.5697C96.1112 49.1846 95.6568 49.9637 95.4352 50.8207C95.2135 51.6777 95.2332 52.5794 95.492 53.4259C95.7508 54.2725 96.2387 55.031 96.9017 55.6175C97.5647 56.2041 98.377 56.5959 99.2488 56.7496C99.9015 56.8647 100.524 56.4289 100.639 55.7762C100.754 55.1236 100.318 54.5012 99.6655 54.3861C99.2296 54.3092 98.8235 54.1133 98.492 53.82C98.1605 53.5268 97.9166 53.1475 97.7872 52.7242C97.6578 52.301 97.6479 51.8501 97.7587 51.4216C97.8696 50.9931 98.0967 50.6036 98.4151 50.2961C98.7335 49.9887 99.1307 49.7752 99.5628 49.6794C99.995 49.5836 100.445 49.6092 100.864 49.7533C101.282 49.8974 101.653 50.1544 101.934 50.496C102.216 50.8375 102.397 51.2502 102.459 51.6885Z" fill="#262A46"/>
                <path d="M102.577 53.557C102.286 53.4545 101.963 53.5279 101.745 53.7457C101.527 53.9635 101.454 54.2871 101.556 54.5776L104.95 64.1943C105.048 64.4723 105.291 64.6738 105.583 64.7187C105.874 64.7636 106.167 64.6446 106.344 64.4089L108.219 61.9166L109.879 63.5766C110.191 63.889 110.698 63.889 111.01 63.5766L111.576 63.0109C111.888 62.6985 111.888 62.1919 111.576 61.8795L109.916 60.2195L112.408 58.3448C112.644 58.1676 112.763 57.8751 112.718 57.5837C112.673 57.2923 112.471 57.0492 112.193 56.9511L102.577 53.557Z" fill="#262A46"/>
                <g filter="url(#filter3_diiii_2314_11712)">
                  <rect x="72" y="96" width="56" height="56" rx="6" fill="white"/>
                </g>
                <g filter="url(#filter4_diiii_2314_11712)">
                  <rect x="144" y="-8" width="56" height="56" rx="6" fill="white"/>
                </g>
                <g filter="url(#filter5_diiii_2314_11712)">
                  <rect x="144" y="64" width="56" height="56" rx="6" fill="white"/>
                </g>
                <defs>
                  <filter id="filter0_diiii_2314_11712" x="0" y="-8" width="56" height="57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dy="1"/>
                    <feComposite in2="hardAlpha" operator="out"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0.07 0"/>
                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2314_11712"/>
                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2314_11712" result="shape"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dy="1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0.5 0"/>
                    <feBlend mode="multiply" in2="shape" result="effect2_innerShadow_2314_11712"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dy="-1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.17 0"/>
                    <feBlend mode="multiply" in2="effect2_innerShadow_2314_11712" result="effect3_innerShadow_2314_11712"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dx="-1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                    <feBlend mode="multiply" in2="effect3_innerShadow_2314_11712" result="effect4_innerShadow_2314_11712"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dx="1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                    <feBlend mode="multiply" in2="effect4_innerShadow_2314_11712" result="effect5_innerShadow_2314_11712"/>
                  </filter>
                  <filter id="filter1_diiii_2314_11712" x="0" y="64" width="56" height="57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dy="1"/>
                    <feComposite in2="hardAlpha" operator="out"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0.07 0"/>
                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2314_11712"/>
                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2314_11712" result="shape"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dy="1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0.5 0"/>
                    <feBlend mode="multiply" in2="shape" result="effect2_innerShadow_2314_11712"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dy="-1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.17 0"/>
                    <feBlend mode="multiply" in2="effect2_innerShadow_2314_11712" result="effect3_innerShadow_2314_11712"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dx="-1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                    <feBlend mode="multiply" in2="effect3_innerShadow_2314_11712" result="effect4_innerShadow_2314_11712"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dx="1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                    <feBlend mode="multiply" in2="effect4_innerShadow_2314_11712" result="effect5_innerShadow_2314_11712"/>
                  </filter>
                  <filter id="filter2_diiii_2314_11712" x="72" y="-48" width="56" height="57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dy="1"/>
                    <feComposite in2="hardAlpha" operator="out"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0.07 0"/>
                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2314_11712"/>
                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2314_11712" result="shape"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dy="1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0.5 0"/>
                    <feBlend mode="multiply" in2="shape" result="effect2_innerShadow_2314_11712"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dy="-1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.17 0"/>
                    <feBlend mode="multiply" in2="effect2_innerShadow_2314_11712" result="effect3_innerShadow_2314_11712"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dx="-1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                    <feBlend mode="multiply" in2="effect3_innerShadow_2314_11712" result="effect4_innerShadow_2314_11712"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dx="1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                    <feBlend mode="multiply" in2="effect4_innerShadow_2314_11712" result="effect5_innerShadow_2314_11712"/>
                  </filter>
                  <filter id="filter3_diiii_2314_11712" x="72" y="96" width="56" height="57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dy="1"/>
                    <feComposite in2="hardAlpha" operator="out"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0.07 0"/>
                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2314_11712"/>
                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2314_11712" result="shape"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dy="1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0.5 0"/>
                    <feBlend mode="multiply" in2="shape" result="effect2_innerShadow_2314_11712"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dy="-1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.17 0"/>
                    <feBlend mode="multiply" in2="effect2_innerShadow_2314_11712" result="effect3_innerShadow_2314_11712"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dx="-1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                    <feBlend mode="multiply" in2="effect3_innerShadow_2314_11712" result="effect4_innerShadow_2314_11712"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dx="1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                    <feBlend mode="multiply" in2="effect4_innerShadow_2314_11712" result="effect5_innerShadow_2314_11712"/>
                  </filter>
                  <filter id="filter4_diiii_2314_11712" x="144" y="-8" width="56" height="57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dy="1"/>
                    <feComposite in2="hardAlpha" operator="out"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0.07 0"/>
                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2314_11712"/>
                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2314_11712" result="shape"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dy="1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0.5 0"/>
                    <feBlend mode="multiply" in2="shape" result="effect2_innerShadow_2314_11712"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dy="-1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.17 0"/>
                    <feBlend mode="multiply" in2="effect2_innerShadow_2314_11712" result="effect3_innerShadow_2314_11712"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dx="-1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                    <feBlend mode="multiply" in2="effect3_innerShadow_2314_11712" result="effect4_innerShadow_2314_11712"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dx="1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                    <feBlend mode="multiply" in2="effect4_innerShadow_2314_11712" result="effect5_innerShadow_2314_11712"/>
                  </filter>
                  <filter id="filter5_diiii_2314_11712" x="144" y="64" width="56" height="57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dy="1"/>
                    <feComposite in2="hardAlpha" operator="out"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0.07 0"/>
                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2314_11712"/>
                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2314_11712" result="shape"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dy="1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0.5 0"/>
                    <feBlend mode="multiply" in2="shape" result="effect2_innerShadow_2314_11712"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dy="-1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.17 0"/>
                    <feBlend mode="multiply" in2="effect2_innerShadow_2314_11712" result="effect3_innerShadow_2314_11712"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dx="-1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                    <feBlend mode="multiply" in2="effect3_innerShadow_2314_11712" result="effect4_innerShadow_2314_11712"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dx="1"/>
                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                    <feBlend mode="multiply" in2="effect4_innerShadow_2314_11712" result="effect5_innerShadow_2314_11712"/>
                  </filter>
                </defs>
              </svg>
            </div>
          </InlineStack>
        </MobileCard>

        <template v-if="activeTab === 2">
          <BlockStack gap="200" v-if="questions?.length">
            <MobileCard
                padding="600"
                :style="{ cursor: (activeTab === 0 && selectedQuestionId !== question.id ) ? 'pointer' : 'default' }"
                v-for="question in questions"
                :key="question.id"
                @click="updateSelectedQuestion(question.id)"
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

                          <BlockStack gap="050">
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
                <InlineStack v-if="selectedQuestionId !== question.id">
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
              gap="300"
              v-else-if="pageLoading"
          />

          <BlockStack gap="200" v-else>
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
        </template>
      </BlockStack>
    </Page>
  </ExpertLayout>
  <LoadingSpinner :isLoading="spinnerLoading" />
</template>

<style scoped>

</style>