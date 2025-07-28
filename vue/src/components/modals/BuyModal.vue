<script>
import CheckCircle from "@/components/icons/CheckCircle.vue";
import XIcon from "@/components/icons/XIcon.vue";
import axios from "axios";
import MobileModal from "@/components/MobileModal.vue";
import SavedCardsSelector from "@/components/misc/SavedCardsSelector.vue";
import InputBtn from "@/components/misc/InputBtn.vue";
import {debounce} from "@/directives/debounce";

export default {
  name: "BuyModal",
  components: {InputBtn, SavedCardsSelector, MobileModal},

  props: {
    quota: {
      default: () => {},
      type: Object
    }
  },

  data() {
    return {
      XIcon,
      CheckCircle,

      isMobile: screen.width <= 760,

      loading: false,

      userCards: [],

      selectedCard: null,
      isTester: false,

      availableHours: 0,
      step: 1,

      stripe: null,
      elements: null,
      cardElement: null,
      user: JSON.parse(window.localStorage.getItem('CURRENT_USER')),
      resultContainer: '',
      TEST_CLIENT_ID: 4181
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
    await this.cardElement.mount('#card-element');
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
        this.availableHours = res.data.hours;
      });
    },

    openSettings() {
      this.$emit('close')
      this.$router.push('/client/settings')
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
            this.step = 2;
            this.loading = false;
          }).catch(() => {
            this.loading = false;
          });
        }).catch(err => {
          this.loading = false;
          this.resultContainer = err.response.data.message;
        })
      }
    }, 200),

    handleSubmit: debounce(async function(e) {
      if (this.loading || this.step === 'succeeded')
        return

      this.loading = true;
      e.preventDefault();

      let total = this.quota.hours * this.quota.rate;
      if (this.isTester) total = 1;

      let selectedPack = {
        amount: this.quota.hours,
        price: this.quota.rate,
        total: total,
      }

      this.loading = true;

      await axios.post("api/payment/buy-hours", {
        selected_pack: selectedPack,
        selected_card_id: this.selectedCard.id,
      }).then(() => {
        this.step = 'succeeded'
        this.loading = false;
      }).catch(() => {
        this.step = 'failed'
        this.loading = false;
      })
    }, 200),
  }
}
</script>

<template>
  <MobileModal :mobile="isMobile">
    <template #heading>
      <BlockStack gap="100">
        <InlineStack align="space-between">
          <Text variant="bodyLg" fontWeight="bold" as="p" v-if="step === 'failed'">
            Payment Failed to Process
          </Text>
          <Text variant="bodyLg" fontWeight="bold" as="p" v-else-if="step === 'succeeded'">
            Payment Successfully Processed
          </Text>
          <Text variant="bodyLg" fontWeight="bold" as="p" v-else>
            Buy Prepaid Hours
          </Text>

          <div>
            <Icon :source="XIcon" @click="() => this.$emit('close')"/>
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

    <Box padding="400" v-else>
      <BlockStack gap="400" v-if="quota">
        <Text>
          Prepaid hours benefit from Express Turnaround and can be used across multiple projects with any freelancer from our network.
        </Text>

        <Box borderWidth="025" borderRadius="300" borderColor="border" padding="400">
          <BlockStack gap="200">
            <InlineStack gap="100" align="space-between" blockAlign="center">
              <Text variant="bodyLg" as="p" tone="subdued">
                Amount of prepaid hours:
              </Text>

              <Text as="p" variant="headingLg">
                {{ quota.hours }}
              </Text>
            </InlineStack>

            <InlineStack gap="100" align="space-between" blockAlign="center">
              <Text variant="bodyLg" as="p" tone="subdued">
                Price per hour:
              </Text>

              <Text as="p" variant="headingLg">
                {{ user?.id === this.TEST_CLIENT_ID ? '$0.04' : `$${quota.rate.toFixed(0)}` }}
              </Text>
            </InlineStack>

            <Divider />

            <InlineStack gap="100" align="space-between" blockAlign="center">
              <Text variant="bodyLg" as="p" tone="subdued">
                Total to pay:
              </Text>

              <Text as="p" variant="headingLg">
                ${{ (this.isTester ? 1 : quota.hours * quota.rate).toLocaleString('US') }}
              </Text>
            </InlineStack>
          </BlockStack>
        </Box>
      </BlockStack>
    </Box>

    <Box padding="400"
         v-show="step === 1">
      <template v-if="userCards.length">
        <SavedCardsSelector :savedCards="userCards" :cardPerColumn="2" selectCard
                            @selectCard="selectCard" />
      </template>
      <BlockStack gap="400" v-show="!userCards.length">
        <Text variant="bodyMd" as="p">
          You don't have any Credit Card saved to your account. Please fill your credit card details below to proceed with your payment
        </Text>

        <!-- placeholder for Elements -->
        <div id="card-element"></div>

        <Text v-if="resultContainer">{{ resultContainer }}</Text>
      </BlockStack>
    </Box>

    <Box borderBlockStartWidth="0"
         borderBlockEndWidth="025"
         borderInlineStartWidth="0"
         borderInlineEndWidth="0"
         borderColor="border"
         padding="400"
         v-show="step === 2">
      <BlockStack gap="200">
        <InlineStack align="space-between">
          <Text variant="subdued">Current Balance of Prepaid Hours</Text>
          <Text>{{ availableHours }} hours</Text>
        </InlineStack>

        <InlineStack align="space-between">
          <Text variant="subdued">Balance After Purchase</Text>
          <Text>{{ availableHours + quota.hours }} hours</Text>
        </InlineStack>

        <InlineStack align="space-between" v-if="selectedCard">
          <Text variant="subdued">Selected Card</Text>
          <Text>**** **** **** {{ selectedCard.last_digits }}</Text>
        </InlineStack>
      </BlockStack>
    </Box>

    <template #footer>
      <InlineStack align="end" gap="200"  v-if="step === 1">
        <Button @click="() => this.$emit('close')">Cancel</Button>

        <InputBtn v-if="userCards.length" :icon="CheckCircle" @click="step = 2">Continue</InputBtn>
        <InputBtn v-else :icon="CheckCircle" :loading="loading" :disabled="loading" @click="saveCardAndContinue">Add Card & Pay</InputBtn>
      </InlineStack>

      <InlineStack align="end" gap="200" v-if="step === 2">
        <Button @click="() => this.$emit('close')">Cancel</Button>
        <Button @click="step = 1">Back</Button>

        <InputBtn :icon="CheckCircle" :loading="loading" :disabled="loading" @click="handleSubmit">Confirm & Pay</InputBtn>

      </InlineStack>
    </template>
  </MobileModal>
</template>

<style scoped>

</style>