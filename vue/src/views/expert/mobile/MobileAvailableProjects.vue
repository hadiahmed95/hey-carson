<script>
import MobileCard from "@/components/MobileCard.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import emptyState from "@/assets/empty-state.png";
import AvailableProject from "@/components/cards/expert/AvailableProject.vue";
import axios from "axios";
import LoadingCards from "@/components/cards/LoadingCards.vue";
import {debounce} from "@/directives/debounce";

export default {
  name: "MobileAvailableProjects",

  components: {
    LoadingCards,
    MobileCard,
    AvailableProject
  },

  props: {
    pageLoading: {
      default: false,
      type: Boolean
    },
    availableProjects: {
      type: Array,
      default: () => {
        return [];
      }
    }
  },

  data() {
    return {
      SearchIcon,
      emptyState,

      search: '',

      loading: false,
    }
  },

  methods: {
    claimProject: debounce(async function(id) {
      if (this.loading === true) return;

      this.loading = true;

      await axios.post('api/expert/projects/' + id + '/claim', ).then(() => {
        this.$router.push('/expert/project/' + id);

        this.loading = false;
      }).catch(err => {
        console.log(err);
        this.loading = false;
      });
    }, 200)
  }
}
</script>

<template>
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

    <BlockStack gap="200" v-if="availableProjects.length">
      <AvailableProject v-for="project in availableProjects"
                        :key="project.id"
                        expert
                        :availableProject="project" @click="claimProject(project.id)" />
    </BlockStack>

    <LoadingCards gap="300" v-else-if="pageLoading" />

    <BlockStack gap="200" v-else>
      <MobileCard>
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

<style scoped>

</style>