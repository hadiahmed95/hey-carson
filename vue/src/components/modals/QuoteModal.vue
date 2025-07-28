<script>
import NoteIcon from "@/components/icons/NoteIcon.vue";
import PlusCircleIcon from "@/components/icons/PlusCircleIcon.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import XIcon from "@/components/icons/XIcon.vue";
import MobileModal from "@/components/MobileModal.vue";
import moment from "moment/moment";
import axios from "axios";
import SavedCardsSelector from "@/components/misc/SavedCardsSelector.vue";
import InputBtn from "@/components/misc/InputBtn.vue";
import {debounce} from "@/directives/debounce";

export default {
  name: "QuoteModal",
  components: {InputBtn, SavedCardsSelector, MobileModal},

  props: {
    quota: {
      default: () => {},
      type: Object
    },
    project: {
      default: () => {},
      type: Object
    }
  },

  data() {
    return {
      NoteIcon,
      PlusCircleIcon,
      CheckCircle,
      XIcon,
      isMobile: screen.width <= 760,
      paymentActive: null,

      paymentElement: null,

      userCards: [],
      selectedCard: null,
      isTester: false,
      availableHours: 0,
      step: 'select',

      loading: false,
      paymentLoads: false,

      stripe: null,
      elements: null,
      cardElement: null,
      user: JSON.parse(window.localStorage.getItem('CURRENT_USER')),
      resultContainer: '',
    }
  },

  async mounted() {
    await this.getUserCards()
    await this.getHours()

    let key = process.env.VUE_APP_STRIPE_PUBLISHABLE_KEY;
    // eslint-disable-next-line no-undef
    this.stripe = Stripe(key);
    this.elements = this.stripe.elements();
    this.cardElement = this.elements.create('card', {
      disableLink: true,
      style: {
        empty: {
          '::placeholder': {color: '#616161'},
          iconColor: '#000'
        }
      },
    });
    this.cardElement.mount('#card-element');
  },

  methods: {
    async getUserCards() {
      await axios.get('api/client/settings').then(res => {
        this.userCards = res.data.user.saved_cards;
        this.isTester = res.data.user.is_tester;

        this.userCards.forEach(card => {
          card.selected = card.default

          if (card.default) {
            this.selectedCard = card
          }
        })
      }).catch(err => console.log(err))
    },

    selectCard(selectedCard) {
      this.userCards.forEach(card => {
        card.selected = card.id === selectedCard.id

        if (card.selected) {
          this.selectedCard = card
        }
      })
    },

    async getHours() {
      await axios.get('api/client/hours').then(res => {
        this.availableHours = parseInt(res.data.hours);
      });
    },

    openSettings() {
      this.$emit('close')
      this.$router.push('/client/settings')
    },

    close() {
      this.paymentActive = null;
      this.step = "select";
      this.$emit('close')
    },

    back(step) {
      this.paymentActive = null;
      this.step = step
    },

    goTo(page) {
      this.$router.push(page);
    },

    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do YYYY')
    },

    selectPayment(payment) {
      this.paymentActive = payment;
      this.step = payment;
    },

    saveCardAndContinue: debounce(async function() {
      this.loading = true;
      const {paymentMethod, error} = await this.stripe.createPaymentMethod({
            type: 'card',
            card: this.cardElement,
            billing_details: {
              name: this.user.first_name + ' ' + this.user.last_name,
            },
          }
      );

      if (error) {
        this.resultContainer = error.message;
      } else {
        let expDate = ''

        if (paymentMethod.card.exp_month < 10) {
          expDate = paymentMethod.card.exp_year + '/0' + paymentMethod.card.exp_month
        } else {
          expDate = paymentMethod.card.exp_year + '/' + paymentMethod.card.exp_month
        }

        await axios.post("api/payment/save-card", {
          payment_id: paymentMethod.id,
          card_type: paymentMethod.card.brand,
          exp_date: expDate,
          last_digits: paymentMethod.card.last4
        }).then(async () => {
          await this.getUserCards().then(() => {
            this.step = 'payment';
          });

        }).catch(err => {
          this.resultContainer = err.response.data.message;
        })
      }

      this.loading = false;
    }, 200),

    deductHours() {
      if (this.loading || this.step === 'select')
        return

      this.loading = true;
      axios.post("api/payment/prepaid", {
        amount: this.quota.hours,
        total: this.quota.hours * this.quota.rate,
        price: this.quota.rate,
        offer_id: this.quota.id,
        project_id: this.project.id,
      }).then(() => {
        this.step = "select";
        this.loading = false;
        this.$emit('confirm', this.quota.id)
      }).catch(() => {
        this.step = "select";
        this.loading = false;
      })
    },

    payWithCard() {
      if (this.loading || this.step === 'succeeded')
        return

      let total = this.quota.hours * this.quota.rate;
      if (this.isTester) total = 1;

      let selectedPack = {
        amount: this.quota.hours,
        price: this.quota.rate,
        total: total,
        offer_id: this.quota.id,
      }

      this.loading = true;

      axios.post("api/payment/card-payment", {
        project_id: this.project.id,
        selected_pack: selectedPack,
        selected_card_id: this.selectedCard.id,
      }).then(() => {
        this.step = 'succeeded'
        this.$emit('confirmNoClose', this.quota.id)
        this.loading = false;
      }).catch(() => {
        this.step = 'failed'
        this.loading = false;
      })
    },
  }
}
</script>

<template>
  <MobileModal :mobile="isMobile">
    <template #heading>
      <BlockStack gap="100">
        <InlineStack align="space-between" v-if="quota">
          <Text variant="bodyLg" fontWeight="bold" as="p" v-if="step === 'failed'">
            Payment Failed to Process
          </Text>
          <Text variant="bodyLg" fontWeight="bold" as="p" v-else-if="step === 'succeeded'">
            Payment Successfully Processed
          </Text>
          <Text variant="bodyLg" fontWeight="bold" as="p" v-else-if="quota.type === 'offer'">
            Accept Project Quote
          </Text>
          <Text variant="bodyLg" fontWeight="bold" as="p" v-if="quota.type === 'scope'">
            Approve Additional Time
          </Text>

          <div>
            <Icon :source="XIcon"  @click="close"/>
          </div>
        </InlineStack>
      </BlockStack>
    </template>

    <Box padding="400" v-if="step === 'failed'">
      <BlockStack align="center" inlineAlign="center">
        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56" fill="none">
          <path d="M28.0004 16.7999C29.1602 16.7999 30.1004 17.7401 30.1004 18.8999L30.1002 28.6999C30.1002 29.8597 29.16 30.7999 28.0002 30.7999C26.8404 30.7999 25.9002 29.8597 25.9002 28.6999L25.9004 18.8999C25.9004 17.7401 26.8406 16.7999 28.0004 16.7999Z" fill="#FF7474"/>
          <path d="M30.8004 36.3999C30.8004 37.9463 29.5468 39.1999 28.0004 39.1999C26.454 39.1999 25.2004 37.9463 25.2004 36.3999C25.2004 34.8535 26.454 33.5999 28.0004 33.5999C29.5468 33.5999 30.8004 34.8535 30.8004 36.3999Z" fill="#FF7474"/>
          <path fill-rule="evenodd" clip-rule="evenodd" d="M47.6004 27.9999C47.6004 38.8247 38.8252 47.5999 28.0004 47.5999C17.1756 47.5999 8.40039 38.8247 8.40039 27.9999C8.40039 17.1751 17.1756 8.3999 28.0004 8.3999C38.8252 8.3999 47.6004 17.1751 47.6004 27.9999ZM43.4004 27.9999C43.4004 36.5051 36.5056 43.3999 28.0004 43.3999C19.4952 43.3999 12.6004 36.5051 12.6004 27.9999C12.6004 19.4947 19.4952 12.5999 28.0004 12.5999C36.5056 12.5999 43.4004 19.4947 43.4004 27.9999Z" fill="#FF7474"/>
        </svg>

        <Text alignment="center">
          Unfortunately, your payment was not processed successfully, and no amount has been charged to your credit card. Please check your card details or contact your bank for more information.
        </Text>
      </BlockStack>
    </Box>

    <Box padding="400" v-else-if="step === 'succeeded'">
      <BlockStack align="center" inlineAlign="center">
        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56" fill="none">
          <path d="M12.9638 24.6682C14.8046 16.3647 23.0283 11.1256 31.3319 12.9664C32.4642 13.2175 33.5856 12.5031 33.8366 11.3707C34.0877 10.2384 33.3732 9.11703 32.2409 8.866C21.6727 6.52309 11.2062 13.191 8.86332 23.7592C6.52041 34.3274 13.1883 44.7939 23.7565 47.1368C34.3247 49.4797 44.7912 42.8118 47.1341 32.2436C47.3851 31.1113 46.6707 29.9899 45.5384 29.7389C44.4061 29.4879 43.2847 30.2023 43.0337 31.3346C41.1928 39.6382 32.9691 44.8772 24.6656 43.0364C16.362 41.1955 11.1229 32.9718 12.9638 24.6682Z" fill="#38FAA3"/>
          <path d="M44.8837 18.9849C45.7038 18.1648 45.7038 16.8351 44.8837 16.015C44.0636 15.1949 42.7339 15.1949 41.9138 16.015L27.9987 29.9301L21.7837 23.715C20.9636 22.8949 19.6339 22.8949 18.8138 23.715C17.9937 24.5351 17.9937 25.8648 18.8138 26.6849L26.5138 34.3849C27.3339 35.205 28.6636 35.205 29.4837 34.3849L44.8837 18.9849Z" fill="#38FAA3"/>
        </svg>

        <Text alignment="center">
          We confirm that your payment has been processed successfully, and the amount related to the offer will be charged to your credit card.
        </Text>
      </BlockStack>
    </Box>

    <Box borderBlockStartWidth="0"
         borderBlockEndWidth="025"
         borderInlineStartWidth="0"
         borderInlineEndWidth="0"
         borderColor="border"
         padding="400"
         v-else>
      <BlockStack gap="100" v-if="quota">
        <Card>
          <BlockStack gap="400">
            <InlineStack gap="200" v-if="quota.type === 'offer'">
              <div style="min-width:40px; min-height:40px; padding: 10px; border-radius: 100%; background: #EAF4FF">
                <Icon :source="NoteIcon" />
              </div>

              <BlockStack gap="100">
                <Text as="p" variant="bodyMd" fontWeight="semibold">
                  Submitted project quote
                  <Badge tone="success" v-if="quota.paid">Paid</Badge>
                </Text>
                <Text as="p" variant="bodySm" tone="subdued">
                  {{ formatDate(quota.created_at) }}
                </Text>
              </BlockStack>
            </InlineStack>

            <InlineStack gap="200" v-if="quota.type === 'scope'">
              <div style="min-width:40px; min-height:40px; padding: 10px; border-radius: 100%; background: #FFD6A4">
                <Icon :source="PlusCircleIcon" />
              </div>

              <BlockStack gap="100">
                <Text as="p" variant="bodyMd" fontWeight="semibold">
                  Additional project scope
                  <Badge tone="success" v-if="quota.paid">Paid</Badge>
                </Text>
                <Text as="p" variant="bodySm" tone="subdued">
                  {{ formatDate(quota.created_at) }}
                </Text>
              </BlockStack>
            </InlineStack>

            <Divider />

            <InlineStack align="space-between">
              <Text as="p" variant="bodyMd" tone="subdued">
                Hourly rate
              </Text>

              <Text as="p" variant="headingMd">
                ${{ quota.rate.toFixed(2) }}
              </Text>
            </InlineStack>

            <InlineStack align="space-between">
              <Text as="p" variant="bodyMd" tone="subdued">
                {{ quota.type === 'scope' ? 'Additional time' : 'Estimated time' }}
              </Text>

              <Text as="p" variant="headingMd">
                {{ quota.hours }} hours
              </Text>
            </InlineStack>

            <Divider />

            <InlineStack align="space-between" blockAlign="center">
              <Text as="p" variant="bodyMd" tone="subdued">
                Total to pay
              </Text>

              <Text as="p" variant="headingXl">
                ${{ (isTester? 1 : quota.rate * quota.hours).toFixed(2) }}
              </Text>
            </InlineStack>

            <InlineStack align="space-between" blockAlign="center" v-if="isTester">
              <Text as="p" variant="bodySm" tone="subdued">
                Real Price
              </Text>

              <Text as="p" variant="bodyXl">
                ${{ (quota.rate * quota.hours).toFixed(2) }}
              </Text>
            </InlineStack>
          </BlockStack>
        </Card>
      </BlockStack>
    </Box>

    <Box v-show="step === 'select'"
         padding="400">
      <BlockStack gap="400">
        <Box v-if="quota && availableHours >= quota.hours"
             :background="paymentActive === 'prepaid' ? 'bg-surface-secondary' : null"
             class="payment-option-new"
             borderWidth="025"
             borderColor="border"
             borderRadius="300"
             paddingBlock="200"
             paddingInline="400"
             @click="selectPayment('prepaid')">
          <InlineStack blockAlign="center" gap="200">
            <Box style="border-radius: 100%; width: 40px; height: 40px; padding: 12px; background-color: #ACE46F" borderColor="border" borderWidth="025">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="15" viewBox="0 0 14 15" fill="none">
                <path d="M7.74999 3.5C7.74999 3.08579 7.41421 2.75 6.99999 2.75C6.58578 2.75 6.24999 3.08579 6.24999 3.5L6.24999 7.5C6.24999 7.69891 6.32901 7.88968 6.46966 8.03033L8.46966 10.0303C8.76255 10.3232 9.23743 10.3232 9.53032 10.0303C9.82321 9.73744 9.82321 9.26256 9.53032 8.96967L7.74999 7.18934L7.74999 3.5Z" fill="#303030"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M14 7.5C14 11.366 10.866 14.5 7 14.5C3.13401 14.5 0 11.366 0 7.5C0 3.63401 3.13401 0.5 7 0.5C10.866 0.5 14 3.63401 14 7.5ZM12.5 7.5C12.5 10.5376 10.0376 13 7 13C3.96243 13 1.5 10.5376 1.5 7.5C1.5 4.46243 3.96243 2 7 2C10.0376 2 12.5 4.46243 12.5 7.5Z" fill="#303030"/>
              </svg>
            </Box>

            <Text as="p">
              Pay with <Text as="span" fontWeight="semibold">Prepaid Hours</Text>
            </Text>
          </InlineStack>
        </Box>

        <Box v-if="quota && availableHours === 0"
             :background="paymentActive === 'prepaid' ? 'bg-surface-secondary' : null"
             class="payment-option-new"
             borderWidth="025"
             borderColor="border"
             borderRadius="300"
             paddingBlock="200"
             paddingInline="400"
             @click="goTo('/client/pricing')">
          <InlineStack blockAlign="center" gap="200">
            <Box style="border-radius: 100%; width: 40px; height: 40px; padding: 12px; background-color: #ACE46F" borderColor="border" borderWidth="025">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="15" viewBox="0 0 14 15" fill="none">
                <path d="M7.74999 3.5C7.74999 3.08579 7.41421 2.75 6.99999 2.75C6.58578 2.75 6.24999 3.08579 6.24999 3.5L6.24999 7.5C6.24999 7.69891 6.32901 7.88968 6.46966 8.03033L8.46966 10.0303C8.76255 10.3232 9.23743 10.3232 9.53032 10.0303C9.82321 9.73744 9.82321 9.26256 9.53032 8.96967L7.74999 7.18934L7.74999 3.5Z" fill="#303030"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M14 7.5C14 11.366 10.866 14.5 7 14.5C3.13401 14.5 0 11.366 0 7.5C0 3.63401 3.13401 0.5 7 0.5C10.866 0.5 14 3.63401 14 7.5ZM12.5 7.5C12.5 10.5376 10.0376 13 7 13C3.96243 13 1.5 10.5376 1.5 7.5C1.5 4.46243 3.96243 2 7 2C10.0376 2 12.5 4.46243 12.5 7.5Z" fill="#303030"/>
              </svg>
            </Box>

            <Text as="p">
              Save up with <Text as="span" fontWeight="semibold">Pre-Paid Hours</Text>
            </Text>
          </InlineStack>
        </Box>

        <Box v-if="userCards.length" :background="paymentActive === 'card' ? 'bg-surface-secondary' : null"
             class="payment-option"
             borderWidth="025"
             borderColor="border"
             borderRadius="300"
             paddingBlock="200"
             paddingInline="400"
             @click="selectPayment('card')">
          <InlineStack blockAlign="center" gap="200">
            <Box style="border-radius: 100%; width: 40px; height: 40px; padding: 10px"
                 borderColor="border" borderWidth="025">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M6.25 11.25C5.83579 11.25 5.5 11.5858 5.5 12C5.5 12.4142 5.83579 12.75 6.25 12.75H9C9.41421 12.75 9.75 12.4142 9.75 12C9.75 11.5858 9.41421 11.25 9 11.25H6.25Z" fill="#303030"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M2.5 7.25C2.5 5.73122 3.73122 4.5 5.25 4.5H14.75C16.2688 4.5 17.5 5.73122 17.5 7.25V12.75C17.5 14.2688 16.2688 15.5 14.75 15.5H5.25C3.73122 15.5 2.5 14.2688 2.5 12.75V7.25ZM14.75 6C15.4404 6 16 6.55964 16 7.25L4 7.25C4 6.55964 4.55964 6 5.25 6H14.75ZM16 9.25H4V12.75C4 13.4404 4.55964 14 5.25 14H14.75C15.4404 14 16 13.4404 16 12.75V9.25Z" fill="#303030"/>
              </svg>
            </Box>

            <Text as="p">
              Pay with <Text as="span" fontWeight="semibold">Credit Card</Text>
            </Text>
          </InlineStack>
        </Box>

        <Box v-show="!userCards.length && availableHours === 0">
          <BlockStack gap="400">
            <Text variant="bodyMd" as="p">
              You don't have any Cards saved, add one now and proceed with payment
            </Text>

            <!-- placeholder for Elements -->
            <div id="card-element"></div>

            <Text v-if="resultContainer">{{ resultContainer }}</Text>
          </BlockStack>
        </Box>

        <template v-if="quota">
          <Text variant="bodyMd" as="p" v-if="quota.type === 'offer'">
            After your confirmation, the project transitions to 'In Progress,' and the expert begins work. Payment will be released immediately, with compensation provided upon completion.
          </Text>

          <Text variant="bodyMd" as="p" v-if="quota.type === 'scope'">
            The expert has added these hours to the project scope. If you agree, please confirm and make payment. Once confirmed, the hours will be officially added to the project scope.
          </Text>
        </template>
      </BlockStack>
    </Box>

    <Box v-if="step === 'prepaid'"
         padding="400">
      <BlockStack gap="400">
        <Text variant="bodyMd" as="p">
          The number of hours from this expert's offer will be deducted from your prepaid balance of hours.
        </Text>

        <Divider />

        <BlockStack gap="200">
          <InlineStack align="space-between">
            <Text variant="subdued">Current Balance of Prepaid Hours</Text>
            <Text>{{ availableHours }} hours</Text>
          </InlineStack>

          <InlineStack align="space-between">
            <Text variant="subdued">Balance After Payment</Text>
            <Text>{{ availableHours - quota.hours }} hours</Text>
          </InlineStack>
        </BlockStack>
      </BlockStack>
    </Box>

    <Box v-if="step === 'card'"
         padding="400">
      <BlockStack gap="400">
        <template v-if="userCards">
          <SavedCardsSelector :savedCards="userCards" :cardPerColumn="2" selectCard
                              @selectCard="selectCard" />
        </template>
      </BlockStack>
    </Box>

    <Box v-if="step === 'payment'"
         padding="400">
      <Text variant="bodyMd" as="p">
        Are you sure you want to proceed with current payment.
        Your card ending with <b>{{ selectedCard.last_digits }}</b> will get charged <b>${{ (isTester ? 1 : quota.rate * quota.hours).toFixed(2) }}</b> for this {{ quota.type }}.
      </Text>
    </Box>

    <template #footer v-if="step === 'prepaid' || step === 'card' || step === 'payment' || (step === 'select' && !userCards.length && availableHours === 0)">
      <InlineStack align="end" gap="200" v-if="!userCards.length && availableHours === 0">
        <InputBtn :icon="CheckCircle" :loading="loading"
                  @click="saveCardAndContinue">Save Card & Pay</InputBtn>
      </InlineStack>

      <InlineStack align="end" gap="200" v-if="step === 'prepaid'">
        <Button @click="back('select')">Back</Button>

        <InputBtn :icon="CheckCircle"
                  @click="deductHours">Confirm</InputBtn>
      </InlineStack>

      <InlineStack align="end" gap="200" v-if="step === 'card'">
        <Button @click="back('select')">Back</Button>

        <InputBtn :icon="CheckCircle" :loading="loading"
                  @click="step = 'payment'">Continue</InputBtn>
      </InlineStack>

      <InlineStack align="end" gap="200" v-if="step === 'payment'">
        <Button @click="back('select')">Back</Button>

        <InputBtn :icon="CheckCircle" :loading="loading" :disabled="loading"
                  @click="payWithCard">Confirm & Pay</InputBtn>
      </InlineStack>
    </template>
  </MobileModal>
</template>

<style scoped>
.payment-option:hover {
  cursor: pointer;
  background: rgb(252, 252, 252) !important;
}
.payment-option:active {
  cursor: pointer;
  background: rgb(247, 247, 247) !important;
}
.payment-option-new {
  cursor: pointer;
  background-color: black !important;
  color: white !important;

  &:hover {
    background-color: #ACE46F !important;
    color: black !important;
  }
}

::v-deep .mobile-modal-content {
  max-height: 80vh;          /* Limit the modal body height */
  overflow-y: auto;          /* Enable vertical scrolling */
  padding-right: 8px;        /* Optional: room for scrollbar */
}

::v-deep .Polaris-Box {  /* Assuming your Box maps to this class, adjust as needed */
  max-height: 80vh;
  overflow-y: auto;
}
</style>