<script>
import ExpertLayout from "@/layout/ExpertLayout.vue";
import MobileAvailableProjects from "@/views/expert/mobile/MobileAvailableProjects.vue";
import WebAvailableProjects from "@/views/expert/web/WebAvailableProjects.vue";
import LoadingSpinner from '@/components/misc/LoadingSpinner.vue';
import axios from "axios";

export default {
  name: "TeamPage",

  components: {
    LoadingSpinner,
    ExpertLayout,
    MobileAvailableProjects,
    WebAvailableProjects
  },

  async mounted() {
    this.pageLoading = true;
    await this.getAvailableProjects()
  },

  data() {
    return {
      isMobile: screen.width <= 760,

      availableProjects: [],

      pageLoading: false,
      spinnerLoading: false,
      pageCount: 1,
      lastPage: 1,
    }
  },

  methods: {
    async getAvailableProjects() {
      this.spinnerLoading = true;
      await axios.get(`api/expert/projects/available?page=${this.pageCount}`).then(res => {
        this.availableProjects.push(...res.data.available_projects.data)
        this.lastPage = res.data.available_projects.last_page
        this.pageCount += 1
      }).catch(err => {
        console.log(err)
      }).finally(() => {
        this.pageLoading = false;
        this.spinnerLoading = false;
      })
    },

    loadMore() {
      if (this.pageCount > this.lastPage)
        return

      if (this.availableProjects.length === 0)
        return

      this.getAvailableProjects()
    }
  },
}
</script>

<template>
  <ExpertLayout v-infinite-scroll="{ threshold: 1, callback: loadMore }" class="scrollable-container">
    <MobileAvailableProjects v-if="isMobile" :pageLoading="pageLoading" :availableProjects="availableProjects" />

    <WebAvailableProjects v-else :pageLoading="pageLoading" :availableProjects="availableProjects" />
  </ExpertLayout>

  <LoadingSpinner :isLoading="spinnerLoading" />
</template>

<style scoped>
.scrollable-container {
  height: 100vh;
  overflow-y: auto;
}
</style>