<script>
import MobileCard from "@/components/MobileCard.vue";
import moment from "moment";

export default {
  name: "TransactionCard",
  components: {MobileCard},

  props: {
    item: {
      type: Object,
      default: () => {}
    },
  },

  methods: {
    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do YYYY')
    },

    goto(item){
      const newTab = window.open(`/admin/projects/${item.project_id}`, '_blank');

      newTab.onload = () => {
        newTab.document.title = item.project.name;
      };
    }
  }
}
</script>

<template>
  <MobileCard padding="600" @click="goto(item)" style="cursor: pointer">
      <InlineStack align="space-between">
        <InlineStack align="space-between" style="flex: 1">
          <BlockStack gap="100" style="min-width: 160px">
            <InlineStack gap="400" align="space-between" blockAlign="center">
              <Text variant="headingMd">Invoice</Text>

              <Text variant="headingMd" >#{{ item.id }}</Text>
            </InlineStack>

            <InlineStack gap="400" align="space-between" blockAlign="center">
              <Text variant="bodyMd">Client</Text>

              <Text variant="bodyMd" >
                {{ item.user.first_name }}
                {{ item.user.last_name }}
              </Text>
            </InlineStack>

            <InlineStack gap="400" align="space-between" blockAlign="center" v-if="item.project">
              <Text variant="bodyMd">Project</Text>

              <Text variant="bodyMd" >
                {{ item.project.name }}
              </Text>
            </InlineStack>

            <InlineStack gap="400" align="space-between" blockAlign="center" v-else>
              <Text variant="bodyMd">Prepaid pack</Text>

              <Text variant="bodyMd" >
                {{ item.amount }} hours
              </Text>
            </InlineStack>
          </BlockStack>

          <BlockStack gap="100" style="min-width: 160px" v-if="item.project">
            <InlineStack gap="400" align="space-between" blockAlign="center">
              <Text variant="bodyMd" tone="subdued">Paid via</Text>

              <Text variant="bodyMd" >
                {{ item.status === 'prepaid' ? 'Prepaid Hours' : 'Direct Payment' }}
              </Text>
            </InlineStack>

            <InlineStack v-if="item.offer" gap="400" align="space-between" blockAlign="center">
              <Text variant="bodyMd" tone="subdued">For</Text>

              <Text variant="bodyMd" >
                {{ item.offer.type === 'offer' ? 'Project Quote' : 'Add to Scope' }}
              </Text>
            </InlineStack>
          </BlockStack>
        </InlineStack>

        <InlineStack align="end" style="flex: 1">
          <BlockStack gap="100" style="min-width: 240px">
            <InlineStack gap="400" align="space-between" blockAlign="center">
              <Text variant="bodyMd" tone="subdued">Payment amount</Text>

              <Text variant="headingMd">${{ item.total.toFixed(2) }}</Text>
            </InlineStack>

            <InlineStack gap="400" align="space-between" blockAlign="center">
              <Text variant="bodyMd" tone="subdued">Payment date</Text>

              <Text variant="headingMd">{{ formatDate(item.created_at) }}</Text>
            </InlineStack>
          </BlockStack>
        </InlineStack>
      </InlineStack>
  </MobileCard>
</template>

<style scoped>

</style>