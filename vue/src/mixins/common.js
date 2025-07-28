export default {
    methods: {
        formatDeadline(date) {
            const day = date.getUTCDate();
            const options = {year: 'numeric', month: 'short', day: 'numeric'};
            const formattedDate = date.toLocaleDateString('en-GB', options);
            return `${day} ${formattedDate.split(' ')[1]}, ${formattedDate.split(' ')[2]}`;
        },
    },
    computed: {
        privacyUrl() {
            return process.env.VUE_APP_PRIVACY_POLICY_DOMAIN + '/privacy-policy'
        },
        clientTermsUrl() {
            return process.env.VUE_APP_PRIVACY_POLICY_DOMAIN + '/client-terms-of-service'
        },
        expertTermsUrl() {
            return process.env.VUE_APP_PRIVACY_POLICY_DOMAIN + '/expert-terms-of-service'
        }
    }
};
