<script>
import axios from "axios";
import {debounce} from "@/directives/debounce";

export default {
  name: "StipePayment",

  props: {
    quota: {
      default: () => {},
      type: Object
    }
  },

  data() {
    return {
      token: null,
      payment: null,
      stripe: null,
      elements: null
    }
  },

  async mounted() {
    await axios.post('api/payment/initiate', {
      amount: this.quota.hours,
      total: this.quota.hours * this.quota.rate,
      price: this.quota.rate,
      currency: 'USD'
    }).then(response => {
      this.token = response.data.token // Use to identify the payment
      // eslint-disable-next-line no-undef
      this.stripe = Stripe("pk_test_51ISjAdJldSyU9QO2Xwk3jhAwtYCTzsOSJXl1cyrx4KtnDNqgrgtRADSO58SzOdQUJEIysOxAIF9CMgwGB7sbE8Af00btMMapgF");
      this.payment = response.data.stripe_id
      const options = {
        clientSecret: response.data.client_secret,
      }

      this.elements = this.stripe.elements(options);
      const paymentElement = this.elements.create('payment');
      paymentElement.mount('#payment-element');
    }).catch(error => {
      console.log(error)
    })
  },

  methods: {
    handleSubmit: debounce(async function(e) {
      e.preventDefault();

      const { error } = await this.stripe.confirmPayment({
        elements: this.elements,
        redirect: "if_required"
      });

      if (error === undefined) {
        await axios.post("api/payment/complete", {
          token: this.token,
          stripe_id: this.payment,
          hours: this.quota.hours,
        })
      } else {
        await axios.post("api/payment/failure", {
          token: this.token,
          code: error.code,
          description: error.message,
        })
      }
    }, 200),
  }
}
</script>

<template>
  <form id="payment-form">
    <div id="payment-element">
      <!-- Stripe will create form elements here -->
    </div>
    <Button type="submit" @click="handleSubmit" >Pay via Stripe</Button>
  </form>
</template>

<style scoped>

</style>