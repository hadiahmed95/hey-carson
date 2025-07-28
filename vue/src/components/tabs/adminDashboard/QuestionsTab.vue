<script>
import QuestionsCard from "@/components/cards/admin/QuestionsCard.vue";
import emptyState from "@/assets/empty-state.png";
export default {
  name: "QuestionsTab",

  props: {
    questions: {
      default: () => [],
      type: Array,
    },
  },

  components: {
    QuestionsCard
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
  <BlockStack gap="300" v-if="questions.length">
    <QuestionsCard />
  </BlockStack>

  <BlockStack gap="200" v-else>
    <Card>
      <EmptyState
          heading="No questions found"
          :image="emptyState"
      >
        <p>Currently there aren't any questions</p>
      </EmptyState>
    </Card>
  </BlockStack>
</template>

<style scoped>

</style>