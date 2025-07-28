<script>
import { Spinner } from '@ownego/polaris-vue';

export default {
  components: {
    Spinner
  },
  props: {
    isLoading: {
      type: Boolean,
      required: true
    }
  }
};
</script>

<template>
  <div v-if="isLoading" class="loading-spinner">
    <Spinner size="large" />
  </div>
</template>



<style scoped>
.loading-spinner {
  background: #fbfdff;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 1em;
}
</style>
