<script>
import ProjectsCard from "@/components/cards/admin/ProjectsCard.vue";
import LoadingCards from "@/components/cards/LoadingCards.vue";
import emptyState from "@/assets/empty-state.png";
export default {
  name: "ProjectsTab",

  props: {
    projects: {
      default: () => [],
      type: Array,
    },
    pageLoading: {
      default: true,
      type: Boolean
    }
  },

  components: {
    ProjectsCard,
    LoadingCards
  },

  data() {
    return {
      emptyState,
    }
  },

  methods: {
    goTo(id) {
      this.$router.push('/admin/projects/' + id)
    }
  }
}
</script>

<template>
  <BlockStack gap="200" v-if="projects.length">
    <ProjectsCard @click="goTo(project.id)" v-for="project in projects" :key="project.id" :project="project"/>
  </BlockStack>

  <LoadingCards gap="300" v-else-if="pageLoading" />

  <BlockStack gap="200" v-else>
    <Card>
      <EmptyState
          heading="No project found"
          :image="emptyState"
      >
        <p>Currently there are no projects with selected status</p>
      </EmptyState>
    </Card>
  </BlockStack>
</template>

<style scoped>

</style>