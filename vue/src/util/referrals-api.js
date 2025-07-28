import axios from 'axios'
import Cookie from 'js-cookie'
import qs from 'qs'
import { v4 as uuidv4 } from 'uuid';

const initialOptions = {
    endpoint: 'https://referral-api.heycarson.com',
    accountId: '',
    apiKey: '',
    debug: false,
    cookieName: 'hcid',
    refCookieName: 'hrc',
    cookieDomain: null
}

class ReferralsAPI {
    constructor (options) {
        this.options = {
            ...initialOptions,
            ...options,
            cookieDomain: options.cookieDomain || (window && `.${window.location.host}`) || null
        }
    }

    axios = null

    init () {
        if (this.axios) {
            return
        }

        const { apiKey, accountId } = this.options

        // new instance
        this.axios = axios.create({
            headers: {
                'X-Api-Key': apiKey,
                'X-Account-Id': accountId
            }
        })
    }

    generateClick (referralCode = '') {
        if (!this.axios) {
            return
        }

        const hcid = this.getClickId()

        if (!referralCode) {
            referralCode = this.getReferralCode()
        }

        if (!referralCode) {
            return new Promise((resolve, reject) => reject(new Error('no referral code')))
        }

        return this.axios.post(
            `${this.options.endpoint}/v1/clicks`,
            null,
            {
                params: {
                    click_id: hcid,
                    referral_code: referralCode
                },
                withCredentials: true
            }
        ).then(response => {
            const { data } = response

            if (this.options.debug) {
                console.log('gen click', data)
            }

            window && window.localStorage.setItem(this.options.cookieName, JSON.stringify(data))
            window && window.localStorage.setItem(this.options.refCookieName, referralCode)

            Cookie.set(this.options.cookieName, JSON.stringify(data), {
                path: '/',
                domain: this.options.cookieDomain,
                expires: 90,
                sameSite: 'Lax'
            })
            Cookie.set(this.options.refCookieName, referralCode, {
                path: '/',
                domain: this.options.cookieDomain,
                expires: 90,
                sameSite: 'Lax'
            })

            return data
        }).catch(error => {
            if (this.options.debug) {
                console.log('gen click error', error)
            }
        })
    }

    getClickId () {
        return (
            Cookie.get(this.options.cookieName) ? JSON.parse(Cookie.get(this.options.cookieName))?.click_id :
              (window && JSON.parse(window.localStorage.getItem(this.options.cookieName))?.click_id) || uuidv4()
        )
    }

    getPartnerId () {
        return (
            Cookie.get(this.options.cookieName)?.partner_id ||
            (window && JSON.parse(window.localStorage.getItem(this.options.cookieName))?.partner_id) || uuidv4()
        )
    }

    getPartnerName () {
        return (
            Cookie.get(this.options.cookieName)?.partner_name ||
            (window && JSON.parse(window.localStorage.getItem(this.options.cookieName))?.partner_name) || uuidv4()
        )
    }

    getProgramId () {
        return (
            Cookie.get(this.options.cookieName)?.program_id ||
            (window && JSON.parse(window.localStorage.getItem(this.options.cookieName))?.program_id) || uuidv4()
        )
    }

    getReferralCode () {
        const search = qs.parse(window && window.location.search, { ignoreQueryPrefix: true })

        if (search.hrc || search.ref) {
            return search.hrc || search.ref
        }

        return (
            Cookie.get(this.options.refCookieName) ||
            (window && window.localStorage.getItem(this.options.refCookieName))
        )
    }
}

export default ReferralsAPI
