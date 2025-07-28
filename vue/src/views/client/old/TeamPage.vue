<script>
import ClientLayout from "@/layout/ClientLayout.vue";
import AddTeamModal from "@/components/modals/AddTeamModal.vue";

import AddIcon from "@/components/icons/AddIcon.vue";

import emptyState from '../../../assets/empty-state.png';
import MobileCard from "@/components/MobileCard.vue";

export default {
  name: "TeamPage",

  components: {
    MobileCard,
    ClientLayout,
    AddTeamModal,
  },

  data() {
    return {
      AddIcon,
      emptyState,
      isMobile: screen.width <= 760,
      addTeamModal: false,
    }
  },

  methods: {
    toggleAddTeamModal() {
      this.addTeamModal = !this.addTeamModal;
    }
  }
}
</script>

<template>
  <ClientLayout>
    <template v-if="isMobile">
      <BlockStack gap="600" style="padding: 32px 16px;">
        <InlineStack align="space-between" blockAlign="center">
          <Text variant="headingLg" as="p">My Team</Text>

          <Button variant="primary" size="large" :icon="AddIcon" @click="toggleAddTeamModal">Add Member</Button>
        </InlineStack>

        <BlockStack gap="200">
          <MobileCard>
            <EmptyState @click.stop="null"
                        heading="Your team has empty seats waiting to be filled"
                        :action="{ content: 'Add Member', icon: AddIcon, onAction: toggleAddTeamModal }"
                        :image="emptyState"
            >
              <p @click="() => this.$router.push('/client/team/full')">Add members to help manage projects together, easier and faster.</p>
            </EmptyState>
          </MobileCard>

        </BlockStack>
      </BlockStack>
    </template>

    <template v-else>
      <Page style="padding-top: 56px; padding-bottom: 56px"
            title="My Team"
      >
        <template #primaryAction>
          <Button variant="primary" size="large" :icon="AddIcon" @click="toggleAddTeamModal">Add Member</Button>
        </template>

        <Card>
          <EmptyState @click.stop="null"
                      heading="Your team has empty seats waiting to be filled"
                      :action="{ content: 'Add Member', icon: AddIcon, onAction: toggleAddTeamModal }"
                      :image="emptyState"
          >
            <p @click="() => this.$router.push('/client/team/full')">Add members to help manage projects together, easier and faster.</p>
          </EmptyState>
        </Card>
      </Page>
    </template>

    <AddTeamModal v-show="addTeamModal" @close="toggleAddTeamModal"/>
  </ClientLayout>
</template>

<style scoped>

</style>