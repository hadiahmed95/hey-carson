<script>
import MobileCard from "@/components/MobileCard.vue";
import moment from "moment";

export default {
  name: "AvailableProject",

  props: {
    availableProject: {
      type: Object,
      default: () => {
        return {}
      }
    }
  },

  components: {MobileCard},

  data() {
    return {
      isMobile: screen.width <= 760,
    }
  },

  methods: {
    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do YYYY')
    },

    projectDescription() {
      let text = this.availableProject.description.substr(0, 120)

      if (this.availableProject.description.length > 120) {
        text += '...'
      }

      return text;
    }
  }
}
</script>

<template>
  <MobileCard padding="600" v-if="isMobile" class="project-card">
    <BlockStack gap="400">
      <InlineStack align="space-between" blockAlign="start">
        <BlockStack gap="100">
          <Text as="h2" variant="headingMd">
            {{ availableProject.name }}
          </Text>
          <Text as="p" variant="bodyMd" tone="subdued" @click.stop>
            <Link :url="availableProject?.url.startsWith('https://') ? availableProject?.url : 'https://' + availableProject?.url" target="_blank" removeUnderline style="color: #6E6E73;">
              {{ availableProject?.url }}
            </Link>
          </Text>
        </BlockStack>

        <Text as="p" variant="bodySm" tone="subdued">
          Posted: {{ formatDate(availableProject.created_at) }}
        </Text>
      </InlineStack>

      <Text variant="bodySm" as="p" alignment="start" v-html="projectDescription()" />
    </BlockStack>
  </MobileCard>

  <Card v-else :padding="null">
    <Box padding="600" class="project-card">
      <BlockStack gap="400">
        <InlineStack align="space-between" blockAlign="start">
          <BlockStack gap="100">
            <Text as="h2" variant="headingMd">
              {{ availableProject.name }}
            </Text>
            <Text as="p" variant="bodyMd" tone="subdued" @click.stop>
              <Link :url="availableProject?.url.startsWith('https://') ? availableProject?.url : 'https://' + availableProject?.url" target="_blank" removeUnderline style="color: #6E6E73;">
                {{ availableProject?.url }}
              </Link>
            </Text>
          </BlockStack>

          <Text as="p" variant="bodySm" tone="subdued">
            Posted: {{ formatDate(availableProject.created_at) }}
          </Text>
        </InlineStack>

        <Text variant="bodySm" as="p" alignment="start" v-html="projectDescription()" />
      </BlockStack>
    </Box>
  </Card>
</template>

<style scoped>
.project-card:hover {
  cursor: pointer;
  background: #f9f9f9;
}
.project-card:active {
  cursor: pointer;
  background: #f2f2f2;
}
</style>