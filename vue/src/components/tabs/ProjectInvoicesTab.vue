<script>
import MobileCard from "@/components/MobileCard.vue";
import ExpertInvoiceCard from "@/components/cards/expert/InvoiceCard.vue";
import ClientInvoiceCard from "@/components/cards/client/InvoiceCard.vue";

export default {
  name: "ProjectInvoicesTab",

  props: {
    project: {
      type: Object,
      default: () => {}
    },
    expert: {
      type: Boolean,
      default: false,
    }
  },

  components: {MobileCard, ExpertInvoiceCard, ClientInvoiceCard},

  data() {
    return {
      isMobile: screen.width <= 760,
    }
  }
}
</script>

<template>
  <MobileCard>
    <BlockStack gap="200" v-if="project.invoices.length">
      <template v-if="expert" >
        <ExpertInvoiceCard v-for="invoice in project.invoices"
                           :key="invoice.id"
                           :item="invoice"
                           :project="project" />
      </template>
      <template v-else>
        <ClientInvoiceCard v-for="invoice in project.invoices"
                           :key="invoice.id"
                           :item="invoice"
                           :project="project" />
      </template>
    </BlockStack>
    <EmptyState v-else
        heading="You don’t have any invoice for this project yet."
        image="https://cdn.shopify.com/s/files/1/0262/4071/2726/files/emptystate-files.png"
    >
      <p>In the future, all project-related invoices will be available here.</p>
    </EmptyState>
  </MobileCard>
</template>

<style scoped>

</style>