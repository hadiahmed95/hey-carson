<script>
import ExpertLayout from "@/layout/ExpertLayout.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import emptyState from '../../../assets/empty-state.png';
import MobileCard from "@/components/MobileCard.vue";

export default {
  name: "TeamPage",

  components: {ExpertLayout, MobileCard},

  data() {
    return {
      SearchIcon,
      isMobile: screen.width <= 760,

      emptyState,

      search: ''
    }
  },
}
</script>

<template>
  <ExpertLayout>
    <template v-if="isMobile">
      <BlockStack gap="600" style="padding: 32px 16px;">
        <InlineStack align="space-between" blockAlign="center">
          <Text variant="headingLg" as="p">Available Projects</Text>
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
          <MobileCard @click="() => {this.$router.push('/expert/available/projects')}">
            <BlockStack gap="400">
              <EmptyState
                  heading="All projects are assigned at this moment"
                  :image="emptyState"
              >
                <p>You will be able to see available projects here. You can check projects, send offers, and compete with other experts.</p>
              </EmptyState>
            </BlockStack>
          </MobileCard>
        </BlockStack>
      </BlockStack>
    </template>

    <template v-else>
      <Page style="padding-top: 56px; padding-bottom: 56px"
            title="Available Projects"
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

        <Card @click="() => {this.$router.push('/expert/available/projects')}">
          <EmptyState
              heading="All projects are assigned at this moment"
              :image="emptyState"
          >
            <p>You will be able to see available projects here. You can check projects, send offers, and compete with other experts.</p>
          </EmptyState>
        </Card>
      </Page>
    </template>
  </ExpertLayout>
</template>

<style scoped>

</style>