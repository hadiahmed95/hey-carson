<script>
import ClientLayout from "@/layout/ClientLayout.vue";
import CreateProjectModal from "@/components/modals/CreateProjectModal.vue";
import AddIcon from "@/components/icons/AddIcon.vue";
import MobileDashboardPage from "@/views/client/moble/MobileDashboardPage.vue";
import WebDashboardPage from "@/views/client/web/WebDashboardPage.vue";
import LoadingSpinner from '@/components/misc/LoadingSpinner.vue';
import axios from "axios";

export default {
  name: "DashboardPage",

  components: {
    ClientLayout,
    CreateProjectModal,
    MobileDashboardPage,
    WebDashboardPage,
    LoadingSpinner,
  },

  data() {
    return {
      AddIcon,

      isMobile: screen.width <= 760,

      statusPopover: false,

      createModal: false,

      projects: [],

      pageLoading: false,
      spinnerLoading: false,
      pageCount: 1,
      lastPage: 1,
    }
  },

  async mounted() {
    await this.getProjects();
  },

  methods: {
    async getProjects() {
      this.createModal = false;
      this.pageLoading = true;
      await axios.get(`api/client/projects?page=${this.pageCount}`, {params: {'status': this.activeStatus}}).then(res => {
        this.projects = [];
        this.projects.push(...res.data.projects.data);
        this.lastPage = res.data.projects.last_page;
        this.pageCount += 1;
      }).catch(err => {
        console.log(err);
      }).finally(()=> {
        this.pageLoading = false
        this.spinnerLoading = false
      });
    },

    defaultPageSettings() {
      this.pageCount = 1
      this.projects = []
    },

    updateStatus() {
      this.defaultPageSettings()
      this.getProjects();
    },

    refresh() {
      this.defaultPageSettings()
      this.getProjects()
    },

    loadMore() {
      if (this.pageCount > this.lastPage)
        return

      if (this.projects.length === 0)
        return

      this.spinnerLoading = true
      this.getProjects(this.activeStatus)
    },
  },
  watch: {
    '$store.state.clientProjectSortStatus': {
      handler(newValue) {
        this.activeStatus = newValue
        this.updateStatus()
      },
      immediate: true
    }
  }
}
</script>

<template>
  <ClientLayout @refresh="refresh" v-infinite-scroll="{ threshold: 1, callback: loadMore }" class="scrollable-container">
    <MobileDashboardPage v-if="isMobile"
                         :pageLoading="pageLoading"
                         :projects="projects"
                         @createModal="() => this.createModal = true"
    />

    <WebDashboardPage  v-else
                       :projects="projects"
                       :pageLoading="pageLoading"
                       @createModal="() => this.createModal = true"
    />

    <CreateProjectModal v-show="createModal" @close="() => this.createModal = false"  @createdProject="refresh"/>
  </ClientLayout>
  <LoadingSpinner :isLoading="spinnerLoading" />
</template>

<style scoped>
.scrollable-container {
  height: 100vh;
  overflow-y: scroll;

}
</style>