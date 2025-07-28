<script>
import ExpertLayout from "@/layout/ExpertLayout.vue";
import MobileDashboardPage from "@/views/expert/mobile/MobileDashboardPage.vue";
import WebDashboardPage from "@/views/expert/web/WebDashboardPage.vue";
import LoadingSpinner from '@/components/misc/LoadingSpinner.vue';
import axios from "axios";
import CreateProjectModal from "@/components/modals/CreateProjectModal.vue";

export default {
  name: "DashboardPage",

  components: {
    CreateProjectModal,
    LoadingSpinner,
    ExpertLayout,
    MobileDashboardPage,
    WebDashboardPage
  },

  data() {
    return {
      isMobile: screen.width <= 760,
      projects: [],
      status: 'all',
      pageLoading: false,
      spinnerLoading: false,
      pageCount: 1,
      lastPage: 1,
      search: '',
      createModal: false,
      user: JSON.parse(window.localStorage.getItem('CURRENT_USER')),
    }
  },

  async mounted() {
    await this.getProjects();
  },

  methods: {
    async getProjects(search = null) {
      this.pageLoading = true
      this.spinnerLoading = true
      this.createModal = false;

      await axios.get(`api/expert/projects?page=${this.pageCount}`, {params: {'status': this.status, 'search': search}}).then(res => {
        this.projects.push(...res.data.projects.data)
        this.pageCount += 1
        this.lastPage = res.data.projects.last_page
      }).catch(err => {
        console.log(err)
      }).finally(() => {
        this.pageLoading = false
        this.spinnerLoading = false
      })
    },

    updateStatus(status) {
      this.status = status
      this.defaultPageSettings()
      this.getProjects()
    },

    refreshPage() {
      this.defaultPageSettings()
      this.getProjects()
    },

    defaultPageSettings() {
      this.pageCount = 1
      this.projects = []
    },

    loadMore() {
      if (this.pageCount > this.lastPage)
        return

      if (this.projects.length === 0)
        return

      this.getProjects(this.search)
    },

    searchFilter(search) {
      this.defaultPageSettings()
      this.search = search
      this.getProjects(search)
    }
  }
}
</script>

<template>
  <ExpertLayout v-infinite-scroll="{ threshold: 1, callback: loadMore }" @refresh="refreshPage" class="scrollable-container">
    <MobileDashboardPage v-if="isMobile"
                         :pageLoading="pageLoading"
                         :projects="projects"
                         @search="searchFilter"
                         @createModal="() => this.createModal = true"
                         @status="updateStatus"/>
    <WebDashboardPage v-else
                      :pageLoading="pageLoading"
                      :projects="projects"
                      @search="searchFilter"
                      @status="updateStatus" @refresh="refreshPage" />
    <CreateProjectModal v-show="createModal" :user="user" @close="() => this.createModal = false"  @createdProject="refreshPage"/>
  </ExpertLayout>
  <LoadingSpinner :isLoading="spinnerLoading" />
</template>

<style scoped>
.scrollable-container {
  height: 100vh;
  overflow-y: auto;
}
</style>