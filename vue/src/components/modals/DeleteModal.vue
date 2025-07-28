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
    userType: {
      type: String,
      default: 'client'
    },

    projectId: {
      type: Number,
      default: 0,
    },

    toAvailable: {
      type: Boolean,
      default: false,
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
    deleteProject: debounce(async function() {
      this.loading = true;

      await axios.delete('api/client/projects/' + this.projectId).then(() => {
        this.loading = false
        this.$emit('close')
        this.$router.push('/client')
      }).catch(() => {
        this.loading = false
        this.$emit('close')
        this.$router.push('/client')
      })
    }, 200),

    releaseProject: debounce(async function() {
      this.loading = true;

      await axios.delete('api/expert/projects/' + this.projectId).then(() => {
        this.loading = false
        this.$emit('close')
        this.toAvailable ? this.$router.push('/expert/available') : this.$router.push('/expert')

      }).catch(() => {
        this.loading = false
        this.$emit('close')
        this.toAvailable ? this.$router.push('/expert/available') : this.$router.push('/expert')
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
            {{ userType === 'client' ? "Delete Request" : "Release Project" }}
          </Text>

          <div>
            <Icon :source="XIcon"  @click="() => this.$emit('close')"/>
          </div>
        </InlineStack>
      </template>

      <Box style="padding: 16px">
        <Text variant="bodyMd" as="p" v-if="userType === 'client'">
          Are you sure you want to delete this request? If you delete the request, it will be permanently removed and you will not be matched with an expert.
        </Text>
        <Text variant="bodyMd" as="p" v-else>
          Are you sure you want to release this project? If you release the project, it will be permanently removed and you will not be able to pick it again.
        </Text>
      </Box>

      <template #footer>
        <InlineStack align="end" gap="200">
          <Button @click="() => this.$emit('close')">Cancel</Button>

          <InputBtn v-if="userType === 'client'" :icon="CheckCircle"
                    :loading="loading" @click="deleteProject">Delete Request</InputBtn>

          <InputBtn v-else :icon="CheckCircle"
                    :loading="loading" @click="releaseProject">Release Project</InputBtn>
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
              Delete Request
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
          <Text variant="bodyMd" as="p" v-if="userType === 'client'">
            Are you sure you want to delete this request? If you delete the request, it will be permanently removed and you will not be matched with an expert.
          </Text>
          <Text variant="bodyMd" as="p" v-else>
            Are you sure you want to release this project? If you release the project, it will be permanently removed and you will not be able to pick it again.
          </Text>
        </Box>

        <Box padding="400">
          <InlineStack align="end" gap="200">
            <Button @click="() => this.$emit('close')">Cancel</Button>

            <InputBtn v-if="userType === 'client'" :icon="CheckCircle"
                      :loading="loading" @click="deleteProject">Delete Request</InputBtn>

            <InputBtn v-else :icon="CheckCircle"
                      :loading="loading" @click="releaseProject">Release Project</InputBtn>
          </InlineStack>
        </Box>
      </Card>
    </BlockStack>
  </div>
  </template>
</template>

<style scoped>

</style>