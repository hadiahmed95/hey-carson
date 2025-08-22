<script setup lang="ts">
import { computed, defineProps} from "vue";
import InvoiceCard from "../expert/cards/InvoiceCard.vue";
import type {ITranscationn} from "@/types.ts";

// Props
const props = defineProps<{
  invoices?: ITranscationn[];
}>();

const invoices = computed(() => {
  return props.invoices || []
});

</script>

<template>
  <div class="space-y-4">
    <InvoiceCard
        v-for="(invoice, index) in invoices"
        :key="index"
        :invoice="invoice"
    />

    <p v-if="invoices.length === 0" class="text-gray-500 italic">
      No invoices found for this request.
    </p>
  </div>
</template>
