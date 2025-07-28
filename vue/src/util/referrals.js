import ReferralsAPI from '@/util/referrals-api'

const refApi = new ReferralsAPI({
  endpoint: process.env.VUE_APP_REF_API_URL,
  cookieDomain: process.env.VUE_APP_REF_COOKIE_DOMAIN,
  accountId: process.env.VUE_APP_REF_ACCOUNT_ID,
  apiKey: process.env.VUE_APP_REF_PUBLIC_KEY
})

export default refApi
