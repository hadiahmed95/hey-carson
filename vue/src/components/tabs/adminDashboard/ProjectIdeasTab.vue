<script>
import ProjectIdeasCard from "@/components/cards/admin/ProjectIdeasCard.vue";
import emptyState from "@/assets/empty-state.png";

export default {
  name: "ProjectIdeasTab",

  props: {
    projectIdeas: {
      default: () => [],
      type: Array,
    },
  },

  components: {
    ProjectIdeasCard,
  },

  data() {
    return {
      emptyState,
    }
  },

  methods: {

  }
}
</script>

<template>
  <BlockStack gap="300" v-if="projectIdeas.length">
    <ProjectIdeasCard />
  </BlockStack>

  <BlockStack gap="200" v-else>
    <Card>
      <EmptyState
          heading="No project idea found"
          :image="emptyState"
      >
        <p>Currently there aren't any Project Ideas</p>
      </EmptyState>
    </Card>
  </BlockStack>
</template>

<style scoped>

</style>