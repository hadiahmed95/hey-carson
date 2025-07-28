<script>
import NoteIcon from "@/components/icons/NoteIcon.vue";
import PlusCircleIcon from "@/components/icons/PlusCircleIcon.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import XIcon from "@/components/icons/XIcon.vue";
import MobileModal from "@/components/MobileModal.vue";
import moment from "moment/moment";
import axios from "axios";
import InputBtn from "@/components/misc/InputBtn.vue";
import {debounce} from "@/directives/debounce";

export default {
  name: "QuoteModal",
  components: {InputBtn, MobileModal},

  props: {
    currentBalance: {
      type: Number,
      default: 0,
    }
  },

  data() {
    return {
      NoteIcon,
      PlusCircleIcon,
      CheckCircle,
      XIcon,
      isMobile: screen.width <= 760,

      user: JSON.parse(window.localStorage.getItem('CURRENT_USER')),

      selectedBalance: 0,
      paymentActive: null,

      loading: false,
    }
  },

  mounted() {
    this.selectedBalance = this.currentBalance.toFixed(2);
  },

  computed: {
    canWithdraw() {
      return this.paymentActive && this.selectedBalance > 0;
    }
  },

  methods: {
    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do YYYY')
    },

    submitRequest: debounce(async function() {
      this.loading = true;
      await axios.post('api/expert/payouts', {
        amount: this.selectedBalance,
        type: this.paymentActive,
      }).then(() => {
        this.loading = false;
        this.$emit('update');
      }).catch(err => {
        this.loading = false;
        console.log(err);
      });
    }, 200),

    openSettings() {
      this.$emit('close')
      this.$router.push('/expert/settings')
    }
  }
}
</script>

<template>
  <template v-if="isMobile">
    <MobileModal>
      <template #heading>
        <BlockStack gap="100">
          <InlineStack align="space-between">
            <Text variant="bodyLg" fontWeight="bold" as="p">
              Request to Withdraw
            </Text>

            <div>
              <Icon :source="XIcon"  @click="() => this.$emit('close')"/>
            </div>
          </InlineStack>
        </BlockStack>
      </template>

      <Box v-if="selectedBalance > 0"
          borderBlockStartWidth="0"
          borderBlockEndWidth="025"
          borderInlineStartWidth="0"
          borderInlineEndWidth="0"
          borderColor="border"
          padding="400">
        <BlockStack gap="400">
          <Box borderWidth="025" borderRadius="300" borderColor="border" padding="400">
            <BlockStack gap="100">
              <Text variant="bodyMd" as="p" tone="subdued">
                Your Balance
              </Text>

              <InlineStack blockAlign="center" :wrap="false">
                <Text as="p" variant="heading3xl">
                  $
                </Text>

                <input class="hiddenInput" type="number" autofocus step="0.01" v-model="selectedBalance">
              </InlineStack>
            </BlockStack>
          </Box>

          <BlockStack v-if="!user.profile.paypal_email && !user.profile.wise_email" gap="100">
            <Text v-if="!user.profile.paypal_email && !user.profile.wise_email" variant="bodyMd" as="p">
              Before you can withdraw from your balance, you need to set up a payout method in your account <Button @click="openSettings" variant="plain">Settings</Button>.
            </Text>
          </BlockStack>

          <BlockStack v-else gap="100">
            <Text variant="bodyMd" as="p">
              Select Withdraw Method
            </Text>

            <Box v-if="user.profile.paypal_email" :background="paymentActive === 'paypal' ? 'bg-surface-secondary' : null"
                 class="payment-option"
                 borderWidth="025" borderRadius="300" borderColor="border"
                 paddingInline="400" paddingBlock="200"
                 @click="() => paymentActive = 'paypal'">
              <InlineStack gap="400" blockAlign="center">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                  <rect width="40" height="40" rx="20" fill="#0070E0"/>
                  <path d="M17.5817 13.6493C17.4426 13.6495 17.3082 13.6973 17.2025 13.7843C17.0968 13.8713 17.0268 13.9917 17.005 14.1239L16.0469 19.9739C16.0915 19.7006 16.3361 19.4993 16.6235 19.4993H19.4312C22.257 19.4993 24.6548 17.5149 25.0929 14.8258C25.1255 14.625 25.144 14.4224 25.1482 14.2192C24.4301 13.8568 23.5865 13.6493 22.6622 13.6493H17.5817Z" fill="white" fill-opacity="0.6"/>
                  <path d="M25.1455 14.2194C25.1413 14.4226 25.1228 14.6253 25.0902 14.826C24.6521 17.5152 22.2541 19.4995 19.4286 19.4995H16.6209C16.3337 19.4995 16.0889 19.7006 16.0442 19.9741L15.1632 25.3497L14.6114 28.7221C14.6006 28.7873 14.6046 28.8539 14.6232 28.9174C14.6418 28.9809 14.6745 29.0398 14.719 29.0899C14.7635 29.1401 14.8187 29.1804 14.881 29.208C14.9432 29.2356 15.011 29.2499 15.0795 29.2499H18.127C18.2661 29.2498 18.4005 29.2019 18.5062 29.1149C18.6119 29.0279 18.6819 28.9075 18.7037 28.7753L19.5064 23.8742C19.5282 23.7419 19.5982 23.6215 19.704 23.5345C19.8098 23.4475 19.9444 23.3997 20.0835 23.3997H21.8778C24.7036 23.3997 27.1013 21.4153 27.5395 18.7262C27.8506 16.8176 26.852 15.0808 25.1455 14.2194Z" fill="white" fill-opacity="0.4"/>
                  <path d="M14.1185 9.75C13.8313 9.75 13.5866 9.95111 13.5419 10.2241L11.1504 24.8221C11.1051 25.0992 11.3275 25.3499 11.619 25.3499H15.1654L16.0459 19.9742L17.0041 14.1242C17.0258 13.992 17.0959 13.8716 17.2015 13.7846C17.3072 13.6976 17.4417 13.6498 17.5807 13.6497H22.6613C23.5858 13.6497 24.4291 13.8573 25.1473 14.2196C25.1964 11.7713 23.0978 9.75 20.2125 9.75H14.1185Z" fill="white"/>
                </svg>

                <Text as="p" variant="bodyMd">
                  Withdraw to my PayPal account.
                </Text>
              </InlineStack>
            </Box>

            <Box v-if="user.profile.wise_email" :background="paymentActive === 'wise' ? 'bg-surface-secondary' : null"
                 class="payment-option"
                 borderWidth="025" borderRadius="300" borderColor="border"
                 paddingInline="400" paddingBlock="200"
                 @click="() => paymentActive = 'wise'">
              <InlineStack gap="400" blockAlign="center">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                  <rect width="40" height="40" rx="20" fill="#88EA5C"/>
                  <path d="M21.6901 16.5354H23.6157L22.6469 23.4765H20.7214L21.6901 16.5354ZM19.2625 16.5354L17.9631 20.5339L17.396 16.5354H16.0493L14.3482 20.5221L14.1356 16.5354H12.2691L12.9188 23.4765H14.4664L16.3801 19.0864L17.0535 23.4765H18.5773L21.0936 16.5354H19.2625ZM35.4289 20.5695H30.8571C30.8807 21.4713 31.4183 22.0646 32.2097 22.0646C32.8063 22.0646 33.2789 21.7442 33.6451 21.1332L35.1884 21.838C34.6581 22.8874 33.5405 23.5714 32.1625 23.5714C30.2842 23.5714 29.0379 22.3018 29.0379 20.2611C29.0379 18.0185 30.5027 16.4286 32.57 16.4286C34.3893 16.4286 35.5352 17.6626 35.5352 19.5847C35.5352 19.9051 35.4997 20.2255 35.4289 20.5695ZM33.7159 19.2406C33.7159 18.4338 33.267 17.9236 32.5464 17.9236C31.8259 17.9236 31.1879 18.4575 31.0225 19.2406H33.7159ZM6.25087 18.6272L4.28516 20.9344H7.79485L8.18941 19.8464H6.68559L7.60466 18.7791L7.60762 18.7506L7.00987 17.7177H9.69798L7.61411 23.4765H9.03997L11.5562 16.5354H5.05538L6.25028 18.6272H6.25087ZM26.7462 17.9236C27.4254 17.9236 28.0208 18.2902 28.5405 18.9191L28.8135 16.9625C28.3291 16.6333 27.6735 16.4286 26.8052 16.4286C25.0805 16.4286 24.1118 17.4431 24.1118 18.7304C24.1118 19.6233 24.608 20.1691 25.4231 20.5221L25.8129 20.7001C26.5394 21.0115 26.7344 21.1658 26.7344 21.4951C26.7344 21.8243 26.4066 22.0527 25.9074 22.0527C25.0834 22.0556 24.416 21.6315 23.914 20.9077L23.6357 22.9017C24.2075 23.3395 24.9405 23.5714 25.9074 23.5714C27.5465 23.5714 28.5536 22.6222 28.5536 21.3052C28.5536 20.4094 28.1578 19.8339 27.1596 19.383L26.7344 19.1813C26.1436 18.9173 25.9429 18.772 25.9429 18.4813C25.9429 18.1668 26.2175 17.9236 26.7462 17.9236Z" fill="#163300"/>
                </svg>

                <Text as="p" variant="bodyMd">
                  Withdraw to my Wise account.
                </Text>
              </InlineStack>
            </Box>

            <Text variant="bodyMd" as="p" tone="subdued">
              Usually we will process your request within 24-48 hours.
            </Text>
          </BlockStack>
        </BlockStack>
      </Box>

      <Box v-else
           borderBlockStartWidth="0"
           borderBlockEndWidth="025"
           borderInlineStartWidth="0"
           borderInlineEndWidth="0"
           borderColor="border"
           padding="400">
        <Text as="p" variant="headingMd" alignment="center">
          Your current available balance is $0. <br /> Please continue working on projects to earn funds, so that you can make your next withdraw.
        </Text>
      </Box>

      <template #footer>
        <InlineStack align="end" gap="200">
          <Button @click="() => this.$emit('close')">Cancel</Button>

          <InputBtn :icon="CheckCircle" :disabled="!canWithdraw"
                    @click="submitRequest" :loading="loading">Submit Request</InputBtn>
        </InlineStack>
      </template>
    </MobileModal>
  </template>

  <template v-else>
    <div style="position: fixed; overflow-y: hidden; top: 0; left: 0; width: 100%; height: 100%; z-index: 10000; background: #00000033"
       @click="() => this.$emit('close')">
    <BlockStack inlineAlign="center" align="center" style="height: 100%">
      <Card style="width: 450px;" :padding="null" @click.stop="null">
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
              Request to Withdraw
            </Text>

            <div>
              <Icon :source="XIcon"  @click="() => this.$emit('close')"/>
            </div>
          </InlineStack>
        </Box>

        <Box v-if="selectedBalance > 0"
            borderBlockStartWidth="0"
            borderBlockEndWidth="025"
            borderInlineStartWidth="0"
            borderInlineEndWidth="0"
            borderColor="border"
            padding="400">
          <BlockStack gap="400">
            <Box borderWidth="025" borderRadius="300" borderColor="border" padding="400">
              <BlockStack gap="100">
                <Text variant="bodyMd" as="p" tone="subdued">
                  Your Balance
                </Text>

                <InlineStack blockAlign="center">
                  <Text as="p" variant="heading3xl">
                    $
                  </Text>

                  <input class="hiddenInput" type="number" autofocus step="0.01" v-model="selectedBalance">
                </InlineStack>
              </BlockStack>
            </Box>

            <BlockStack v-if="!user.profile.paypal_email && !user.profile.wise_email" gap="100">
              <Text v-if="!user.profile.paypal_email && !user.profile.wise_email" variant="bodyMd" as="p">
                Before you can withdraw from your balance, you need to set up a payout method in your account <Button @click="openSettings" variant="plain">Settings</Button>.
              </Text>
            </BlockStack>

            <BlockStack v-else gap="100">
              <Text variant="bodyMd" as="p">
                Select Withdraw Method
              </Text>

              <Box v-if="user.profile.paypal_email" :background="paymentActive === 'paypal' ? 'bg-surface-secondary' : null"
                   class="payment-option"
                   borderWidth="025" borderRadius="300" borderColor="border"
                   paddingInline="400" paddingBlock="200"
                   @click="() => paymentActive = 'paypal'">
                <InlineStack gap="400" blockAlign="center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                    <rect width="40" height="40" rx="20" fill="#0070E0"/>
                    <path d="M17.5817 13.6493C17.4426 13.6495 17.3082 13.6973 17.2025 13.7843C17.0968 13.8713 17.0268 13.9917 17.005 14.1239L16.0469 19.9739C16.0915 19.7006 16.3361 19.4993 16.6235 19.4993H19.4312C22.257 19.4993 24.6548 17.5149 25.0929 14.8258C25.1255 14.625 25.144 14.4224 25.1482 14.2192C24.4301 13.8568 23.5865 13.6493 22.6622 13.6493H17.5817Z" fill="white" fill-opacity="0.6"/>
                    <path d="M25.1455 14.2194C25.1413 14.4226 25.1228 14.6253 25.0902 14.826C24.6521 17.5152 22.2541 19.4995 19.4286 19.4995H16.6209C16.3337 19.4995 16.0889 19.7006 16.0442 19.9741L15.1632 25.3497L14.6114 28.7221C14.6006 28.7873 14.6046 28.8539 14.6232 28.9174C14.6418 28.9809 14.6745 29.0398 14.719 29.0899C14.7635 29.1401 14.8187 29.1804 14.881 29.208C14.9432 29.2356 15.011 29.2499 15.0795 29.2499H18.127C18.2661 29.2498 18.4005 29.2019 18.5062 29.1149C18.6119 29.0279 18.6819 28.9075 18.7037 28.7753L19.5064 23.8742C19.5282 23.7419 19.5982 23.6215 19.704 23.5345C19.8098 23.4475 19.9444 23.3997 20.0835 23.3997H21.8778C24.7036 23.3997 27.1013 21.4153 27.5395 18.7262C27.8506 16.8176 26.852 15.0808 25.1455 14.2194Z" fill="white" fill-opacity="0.4"/>
                    <path d="M14.1185 9.75C13.8313 9.75 13.5866 9.95111 13.5419 10.2241L11.1504 24.8221C11.1051 25.0992 11.3275 25.3499 11.619 25.3499H15.1654L16.0459 19.9742L17.0041 14.1242C17.0258 13.992 17.0959 13.8716 17.2015 13.7846C17.3072 13.6976 17.4417 13.6498 17.5807 13.6497H22.6613C23.5858 13.6497 24.4291 13.8573 25.1473 14.2196C25.1964 11.7713 23.0978 9.75 20.2125 9.75H14.1185Z" fill="white"/>
                  </svg>

                  <Text as="p" variant="bodyMd">
                    Withdraw to my PayPal account.
                  </Text>
                </InlineStack>
              </Box>

              <Box v-if="user.profile.wise_email" :background="paymentActive === 'wise' ? 'bg-surface-secondary' : null"
                   class="payment-option"
                   borderWidth="025" borderRadius="300" borderColor="border"
                   paddingInline="400" paddingBlock="200"
                   @click="() => paymentActive = 'wise'">
                <InlineStack gap="400" blockAlign="center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                    <rect width="40" height="40" rx="20" fill="#88EA5C"/>
                    <path d="M21.6901 16.5354H23.6157L22.6469 23.4765H20.7214L21.6901 16.5354ZM19.2625 16.5354L17.9631 20.5339L17.396 16.5354H16.0493L14.3482 20.5221L14.1356 16.5354H12.2691L12.9188 23.4765H14.4664L16.3801 19.0864L17.0535 23.4765H18.5773L21.0936 16.5354H19.2625ZM35.4289 20.5695H30.8571C30.8807 21.4713 31.4183 22.0646 32.2097 22.0646C32.8063 22.0646 33.2789 21.7442 33.6451 21.1332L35.1884 21.838C34.6581 22.8874 33.5405 23.5714 32.1625 23.5714C30.2842 23.5714 29.0379 22.3018 29.0379 20.2611C29.0379 18.0185 30.5027 16.4286 32.57 16.4286C34.3893 16.4286 35.5352 17.6626 35.5352 19.5847C35.5352 19.9051 35.4997 20.2255 35.4289 20.5695ZM33.7159 19.2406C33.7159 18.4338 33.267 17.9236 32.5464 17.9236C31.8259 17.9236 31.1879 18.4575 31.0225 19.2406H33.7159ZM6.25087 18.6272L4.28516 20.9344H7.79485L8.18941 19.8464H6.68559L7.60466 18.7791L7.60762 18.7506L7.00987 17.7177H9.69798L7.61411 23.4765H9.03997L11.5562 16.5354H5.05538L6.25028 18.6272H6.25087ZM26.7462 17.9236C27.4254 17.9236 28.0208 18.2902 28.5405 18.9191L28.8135 16.9625C28.3291 16.6333 27.6735 16.4286 26.8052 16.4286C25.0805 16.4286 24.1118 17.4431 24.1118 18.7304C24.1118 19.6233 24.608 20.1691 25.4231 20.5221L25.8129 20.7001C26.5394 21.0115 26.7344 21.1658 26.7344 21.4951C26.7344 21.8243 26.4066 22.0527 25.9074 22.0527C25.0834 22.0556 24.416 21.6315 23.914 20.9077L23.6357 22.9017C24.2075 23.3395 24.9405 23.5714 25.9074 23.5714C27.5465 23.5714 28.5536 22.6222 28.5536 21.3052C28.5536 20.4094 28.1578 19.8339 27.1596 19.383L26.7344 19.1813C26.1436 18.9173 25.9429 18.772 25.9429 18.4813C25.9429 18.1668 26.2175 17.9236 26.7462 17.9236Z" fill="#163300"/>
                  </svg>

                  <Text as="p" variant="bodyMd">
                    Withdraw to my Wise account.
                  </Text>
                </InlineStack>
              </Box>

              <Text variant="bodyMd" as="p" tone="subdued">
                Usually we will process your request within 24-48 hours.
              </Text>
            </BlockStack>
          </BlockStack>
        </Box>

        <Box v-else
             borderBlockStartWidth="0"
             borderBlockEndWidth="025"
             borderInlineStartWidth="0"
             borderInlineEndWidth="0"
             borderColor="border"
             padding="400">
          <Text as="p" variant="headingMd" alignment="center">
            Your current available balance is $0. <br /> Please continue working on projects to earn funds, so that you can make your next withdraw.
          </Text>
        </Box>

        <Box padding="400">
          <InlineStack align="end" gap="200">
            <Button @click="() => this.$emit('close')">Cancel</Button>

            <InputBtn :icon="CheckCircle" :disabled="!canWithdraw"
                      @click="submitRequest" :loading="loading">Submit Request</InputBtn>
          </InlineStack>
        </Box>
      </Card>
    </BlockStack>
  </div>
  </template>
</template>

<style scoped>
.hiddenInput {
  color: rgb(48, 48, 48);
  color-scheme: light;
  display: block;
  font-family: Inter, -apple-system, "system-ui", "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
  font-feature-settings: "calt" 0;
  font-size: 34px;
  font-weight: 700;
  height: 48px;
  letter-spacing: -0.54px;
  line-height: 48px;
  max-width: 300px;
  border: none;
}
.hiddenInput:focus {
  outline: none;
}
.payment-option:hover {
  cursor: pointer;
  background: rgb(252, 252, 252) !important;
}
.payment-option:active {
  cursor: pointer;
  background: rgb(247, 247, 247) !important;
}
</style>