<script>
import MobileCard from "@/components/MobileCard.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import DeleteIcon from "@/components/icons/DeleteIcon.vue";
import moment from "moment";

export default {
  name: "CreditCardCard",

  components: {MobileCard},

  props: {
    card: {
      default: () => {},
      type: Object
    },

    selectCard: {
      default: false,
      type: Boolean
    },
  },

  data() {
    return  {
      CheckCircle,
      DeleteIcon,

      setPrimary: false,
      deleteCard: false,
    }
  },

  methods: {
    getDate(date) {
      if (date) {
        return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do, YYYY')
      } else {
        return 'Never'
      }
    },

    getExpired() {
      let date = moment(this.card.exp_date, "YYYY/MM")
      let currentDate = moment();

      return currentDate.isAfter(date);
    }
  }
}
</script>

<template>
  <MobileCard padding="300"
              @click="() => this.$emit('selectCard')"
              canHover
              :background="getExpired() ? 'bg-surface-secondary' : 'bg-surface'">
    <InlineStack align="space-between">
      <InlineStack align="start" blockAlign="center" gap="400">
        <Box style="margin-top: 13px" v-if="card.card_type === 'visa'">
          <svg width="44" height="32" viewBox="0 0 44 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g filter="url(#filter0_d_2863_28980)">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M4 3C4 1.34315 5.34315 0 7 0H37C38.6569 0 40 1.34315 40 3V21C40 22.6569 38.6569 24 37 24H7C5.34315 24 4 22.6569 4 21V3Z" fill="white"/>
            </g>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M20.3761 15.7468H18.4453L19.6529 8.47473H21.5836L20.3761 15.7468Z" fill="#0742A6"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M27.3737 8.6525C26.9929 8.50536 26.3889 8.3429 25.642 8.3429C23.7353 8.3429 22.3927 9.33308 22.3844 10.7488C22.3686 11.7932 23.3457 12.3734 24.0766 12.7216C24.8235 13.0775 25.0775 13.3098 25.0775 13.627C25.0699 14.1142 24.4739 14.3387 23.918 14.3387C23.1472 14.3387 22.7341 14.2229 22.1064 13.9519L21.8522 13.8357L21.582 15.4681C22.0349 15.669 22.8692 15.8473 23.7353 15.8552C25.7612 15.8552 27.0801 14.8803 27.0957 13.3717C27.1034 12.5438 26.5875 11.9095 25.4751 11.3912C24.7998 11.0585 24.3862 10.8341 24.3862 10.4936C24.3942 10.1841 24.736 9.86715 25.4983 9.86715C26.126 9.85162 26.5872 9.99855 26.9365 10.1456L27.1112 10.2228L27.3737 8.6525Z" fill="#0742A6"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M30.8301 8.47473H32.3236L33.8812 15.7467H32.0935C32.0935 15.7467 31.9186 14.9112 31.8631 14.6558H29.3842C29.3125 14.8491 28.979 15.7467 28.979 15.7467H26.9531L29.821 9.0781C30.0197 8.60614 30.3696 8.47473 30.8301 8.47473ZM30.7108 11.1359C30.7108 11.1359 30.0989 12.7528 29.9399 13.1706H31.5448C31.4654 12.807 31.0997 11.0663 31.0997 11.0663L30.9648 10.4397C30.908 10.6011 30.8258 10.8229 30.7704 10.9725C30.7328 11.0739 30.7075 11.1422 30.7108 11.1359Z" fill="#0742A6"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.8332 8.47473L14.9424 13.4336L14.7358 12.4279C14.3862 11.2674 13.2899 10.0066 12.0664 9.37968L13.7983 15.7391H15.84L18.8748 8.47473H16.8332V8.47473Z" fill="#0742A6"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.185 8.47473H10.0787L10.0469 8.62167C12.47 9.22513 14.0749 10.6798 14.7342 12.4282L14.0589 9.08601C13.9477 8.62156 13.6061 8.49005 13.185 8.47473Z" fill="#0742A6"/>
            <defs>
              <filter id="filter0_d_2863_28980" x="0" y="0" width="44" height="32" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                <feOffset dy="4"/>
                <feGaussianBlur stdDeviation="2"/>
                <feColorMatrix type="matrix" values="0 0 0 0 0.0449034 0 0 0 0 0.0617619 0 0 0 0 0.198315 0 0 0 0.1 0"/>
                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2863_28980"/>
                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2863_28980" result="shape"/>
              </filter>
            </defs>
          </svg>

        </Box>
        <Box style="margin-top: 13px" v-if="card.card_type === 'mastercard'">
          <svg width="44" height="32" viewBox="0 0 44 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g filter="url(#filter0_d_2863_28960)">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M4 3C4 1.34315 5.34315 0 7 0H37C38.6569 0 40 1.34315 40 3V21C40 22.6569 38.6569 24 37 24H7C5.34315 24 4 22.6569 4 21V3Z" fill="white"/>
            </g>
            <g opacity="0.01">
              <rect x="11.5" y="5.25" width="20.8125" height="13.5496" fill="white"/>
            </g>
            <rect x="19.1602" y="7.6106" width="5.49022" height="8.82675" fill="#FF5F00"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.7277 12.0249C19.7264 10.3027 20.5295 8.67555 21.9058 7.61226C19.5677 5.80357 16.2102 6.06703 14.1928 8.21747C12.1753 10.3679 12.1753 13.6834 14.1928 15.8338C16.2102 17.9842 19.5677 18.2477 21.9058 16.439C20.5291 15.3754 19.7259 13.7476 19.7277 12.0249Z" fill="#EB001B"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M31.2277 15.2851V15.9215H31.165V15.4396L31.0673 15.8533H30.9997L30.902 15.4396V15.9215H30.8369V15.2851H30.9271L31.0323 15.7396L31.1375 15.2851H31.2277ZM30.6494 15.3942V15.9215H30.5943V15.3942H30.4766V15.2852H30.7746V15.3942H30.6494Z" fill="#F79E1B"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M31.1332 12.0248C31.1331 14.174 29.8865 16.1344 27.9226 17.0736C25.9588 18.0127 23.6226 17.7657 21.9062 16.4374C23.2819 15.3732 24.0851 13.7463 24.0851 12.024C24.0851 10.3018 23.2819 8.67487 21.9062 7.61067C23.6226 6.28239 25.9588 6.03537 27.9226 6.97453C29.8865 7.91368 31.1331 9.87413 31.1332 12.0233V12.0248Z" fill="#F79E1B"/>
            <defs>
              <filter id="filter0_d_2863_28960" x="0" y="0" width="44" height="32" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                <feOffset dy="4"/>
                <feGaussianBlur stdDeviation="2"/>
                <feColorMatrix type="matrix" values="0 0 0 0 0.0449034 0 0 0 0 0.0617619 0 0 0 0 0.198315 0 0 0 0.1 0"/>
                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2863_28960"/>
                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2863_28960" result="shape"/>
              </filter>
            </defs>
          </svg>

        </Box>

        <div style="height: 62px; border: #6E7F9240 1px solid" />

        <BlockStack gap="100">
          <InlineStack gap="200">
            <Text variant="bodyMd" fontWeight="semibold" v-if="card.card_type === 'visa'">
              Visa ({{ card.last_digits }})
            </Text>
            <Text variant="bodyMd" fontWeight="semibold" v-if="card.card_type === 'mastercard'">
              MasterCard ({{ card.last_digits }})
            </Text>

            <Badge v-if="card.selected" tone="info">Selected</Badge>
            <Badge v-if="card.default" tone="success">Primary</Badge>
            <Badge v-if="getExpired()" tone="critical">Expired</Badge>
          </InlineStack>

          <BlockStack>
            <Text variant="bodyMd" >
              Exp. Date: {{ card.exp_date }}
            </Text>

            <Text variant="bodyMd" tone="subdued">
              Last time used: {{ getDate(card.last_used) }}
            </Text>
          </BlockStack>
        </BlockStack>
      </InlineStack>
      <BlockStack >
        <div v-if="!selectCard"
             @click.stop="() => this.$emit('deleteCard')"
             @mouseenter="deleteCard = true"
             @mouseleave="deleteCard = false">
          <Tooltip content="Delete Card">
            <Icon :tone="deleteCard ? 'primary' : 'subdued'" :source="DeleteIcon"/>
          </Tooltip>
        </div>
      </BlockStack>
    </InlineStack>
  </MobileCard>
</template>

<style scoped>

</style>