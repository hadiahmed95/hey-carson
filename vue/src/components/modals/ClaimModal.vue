<script>
import CheckCircle from "@/components/icons/CheckCircle.vue";
import XIcon from "@/components/icons/XIcon.vue";
import MobileModal from "@/components/MobileModal.vue";
import axios from "axios";
import InputBtn from "@/components/misc/InputBtn.vue";
import {debounce} from "@/directives/debounce";

export default {
  name: "ClaimModal",
  components: {InputBtn, MobileModal},

  props: {
    id: {
      type: Number,
      default: 0
    }
  },

  data() {
    return {
      CheckCircle,
      XIcon,
      isMobile: screen.width <= 760,

      loading: false,
    }
  },

  methods: {
    takeProject: debounce(async function() {
      this.loading = true
      await axios.get('api/expert/projects/' + this.id + '/take').then(() => {
        this.loading = false
        this.$emit('close')
      }).catch(() => {
        this.loading = false
        this.$router.push('/expert')
      })
    }, 200),
  }
}
</script>

<template>

  <template v-if="isMobile">
    <MobileModal>
      <template #heading>
        <InlineStack align="space-between" blockAlign="start" :wrap="false">
          <Text variant="bodyLg" fontWeight="bold" as="p">
            Are you sure you want to claim this project?
          </Text>

          <div>
            <Icon :source="XIcon"  @click="() => this.$emit('close')"/>
          </div>
        </InlineStack>
      </template>

      <Box style="padding: 16px">
        <Text variant="bodyMd" as="p">
          Please only claim this project if you are confident in your ability to meet the required skills and fulfill the client's needs. If you feel that you are not equipped to assist the client effectively, kindly refrain from claiming the project.
        </Text>
      </Box>

      <template #footer>
        <InlineStack align="end" gap="200">
          <Button @click="() => this.$emit('close')">Cancel</Button>

          <InputBtn :icon="CheckCircle" :loading="loading" @click="takeProject">Claim Project</InputBtn>
        </InlineStack>
      </template>
    </MobileModal>
  </template>

  <template v-else>
    <div style="position: fixed; overflow-y: hidden; top: 0; left: 0; width: 100%; height: 100%; z-index: 1000; background: #00000033">
    <BlockStack inlineAlign="center" align="center" style="height: 100%">
      <Card style="width: 620px;" :padding="null">
        <Box background="bg-surface-secondary"
             borderBlockStartWidth="0"
             borderBlockEndWidth="025"
             borderInlineStartWidth="0"
             borderInlineEndWidth="0"
             borderColor="border"
             paddingBlock="300"
             paddingInline="400">
          <InlineStack align="space-between">
            <Text variant="bodyLg" fontWeight="bold" as="p">
              Are you sure you want to claim this project?
            </Text>

            <div>
              <Icon :source="XIcon"  @click="() => this.$emit('close')"/>
            </div>
          </InlineStack>
        </Box>
        <Box
             borderBlockStartWidth="0"
             borderBlockEndWidth="025"
             borderInlineStartWidth="0"
             borderInlineEndWidth="0"
             borderColor="border"
             padding="400">
          <Text variant="bodyMd" as="p">
            Please only claim this project if you are confident in your ability to meet the required skills and fulfill the client's needs. If you feel that you are not equipped to assist the client effectively, kindly refrain from claiming the project.
          </Text>
        </Box>

        <Box padding="400">
          <InlineStack align="end" gap="200">
            <Button @click="() => this.$emit('close')">Cancel</Button>

            <InputBtn :icon="CheckCircle" :loading="loading" @click="takeProject">Claim Project</InputBtn>
          </InlineStack>
        </Box>
      </Card>
    </BlockStack>
  </div>
  </template>
</template>

<style scoped>

</style>