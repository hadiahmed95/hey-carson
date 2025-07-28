<script>
import ExpertLayout from "@/layout/ExpertLayout.vue";
import emptyState from "@/assets/empty-state.png";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import MobileCard from "@/components/MobileCard.vue";
export default {
  name: "DashboardPage",

  components: {
    MobileCard,
    ExpertLayout,
  },

  data() {
    return {
      emptyState,
      SearchIcon,
      isMobile: screen.width <= 760,

      search: ''
    }
  }
}
</script>

<template>
  <ExpertLayout>
    <template v-if="isMobile">
      <BlockStack gap="600" style="padding: 32px 16px;">
        <InlineStack align="space-between" blockAlign="center">
          <Text variant="headingLg" as="p">My Projects</Text>
        </InlineStack>

        <TextField
            style="min-width: 220px"
            :label="null"
            type="text"
            v-model="search"
            autoComplete="off"
            placeholder="Search projects ..."
        >
          <template #prefix>
            <Icon :source="SearchIcon" />
          </template>
        </TextField>
        <BlockStack gap="200">
          <MobileCard @click="() => {this.$router.push('/expert/projects')}">
            <EmptyState
                heading="Shortly, we will try to assign a project to you."
                :image="emptyState"
            >
              <p>Sit in your chair and wait for your project. We are currently trying to find the perfect project for you. Thank you for your patience.</p>
            </EmptyState>
          </MobileCard>
        </BlockStack>
      </BlockStack>
    </template>

    <template v-else>
      <Page style="padding-top: 56px; padding-bottom: 56px"
            title="My Projects"
      >
        <template #primaryAction>
          <TextField
              style="min-width: 220px"
              :label="null"
              type="text"
              v-model="search"
              autoComplete="off"
              placeholder="Search projects ..."
          >
            <template #prefix>
              <Icon :source="SearchIcon" />
            </template>
          </TextField>
        </template>

        <Card @click="() => {this.$router.push('/expert/projects')}">
          <EmptyState
              heading="Shortly, we will try to assign a project to you."
              :image="emptyState"
          >
            <p>Sit in your chair and wait for your project. We are currently trying to find the perfect project for you. Thank you for your patience.</p>
          </EmptyState>
        </Card>
      </Page>
    </template>
  </ExpertLayout>
</template>

<style scoped>

</style>