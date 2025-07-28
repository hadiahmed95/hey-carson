<script>
import CheckCircle from "@/components/icons/CheckCircle.vue";
import XIcon from "@/components/icons/XIcon.vue";
import axios from "axios";
import MobileModal from "@/components/MobileModal.vue";
import InputBtn from "@/components/misc/InputBtn.vue";
import {debounce} from "@/directives/debounce";

export default {
  name: "AddCreditCardModal",
  components: {InputBtn, MobileModal},

  data() {
    return {
      XIcon,
      CheckCircle,

      isMobile: screen.width <= 760,

      loading: false,

      stripe: null,
      elements: null,
      cardElement: null,
      user: JSON.parse(window.localStorage.getItem('CURRENT_USER')),
      resultContainer: '',

      availableHours: 0,
      step: 1
    }
  },

  mounted() {
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
    saveCard: debounce(async function() {
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
        }).then(() => {
          this.$emit('saved');
        }).catch(err => {
          this.resultContainer = err.response.data.message;
        })
      }

      this.loading = false;
    }, 200),
  }
}
</script>

<template>

  <template v-if="isMobile">
    <MobileModal>
      <template #heading>
        <BlockStack gap="100">
          <InlineStack align="space-between">
            <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
              Add Credit Card
            </Text>

            <div>
              <Icon :source="XIcon" @click="() => this.$emit('close')"/>
            </div>
          </InlineStack>
        </BlockStack>
      </template>

      <Box
          borderBlockStartWidth="0"
          borderBlockEndWidth="025"
          borderInlineStartWidth="0"
          borderInlineEndWidth="0"
          borderColor="border"
          padding="400">
        <BlockStack gap="400">
          <!-- placeholder for Elements -->
          <div id="card-element"></div>

          <Text v-if="resultContainer">{{ resultContainer }}</Text>
        </BlockStack>
      </Box>

      <template #footer>
        <InlineStack align="end" gap="200">
          <Button @click="() => this.$emit('close')">Cancel</Button>

          <InputBtn :icon="CheckCircle" :loading="loading" @click="saveCard">Save</InputBtn>
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
                Add Credit Card
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
            <BlockStack gap="400">
              <!-- placeholder for Elements -->
              <div id="card-element"></div>

              <Text v-if="resultContainer">{{ resultContainer }}</Text>
            </BlockStack>
          </Box>

          <Box padding="400">
            <InlineStack align="end" gap="200">
              <Button @click="() => this.$emit('close')">Cancel</Button>

              <InputBtn :icon="CheckCircle" :loading="loading" @click="saveCard">Save</InputBtn>
            </InlineStack>
          </Box>
        </Card>
      </BlockStack>
    </div>
  </template>
</template>

<style scoped>

</style>