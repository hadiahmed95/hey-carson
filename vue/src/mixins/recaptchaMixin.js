// src/mixins/recaptchaMixin.js
export default {
    data() {
        return {
            recaptchaSiteKey: process.env.VUE_APP_RECAPTCHA_SITE_KEY,
            recaptchaToken: null,
            recaptchaWidgetId: null
        }
    },

    methods: {
        initReCapcha() {
            if (!window.grecaptcha) {
                const script = document.createElement('script');
                script.src = `https://www.google.com/recaptcha/api.js?render=explicit&onload=initVueRecaptcha`;
                script.async = true;
                script.defer = true;
                document.head.appendChild(script);

                window.initVueRecaptcha = () => {
                    this.$nextTick(() => {
                        this.renderRecaptcha();
                    });
                };
            } else {
                this.$nextTick(() => {
                    this.renderRecaptcha();
                });
            }
        },

        renderRecaptcha() {
            if (window.grecaptcha) {
                const container = document.getElementById('recaptcha-container');
                if (container) {
                    this.recaptchaWidgetId = window.grecaptcha.render(container, {
                        sitekey: this.recaptchaSiteKey,
                        callback: this.onCaptchaVerified
                    });
                }
            }
        },

        onCaptchaVerified(response) {
            this.recaptchaToken = response;
        },

        resetRecaptcha() {
            if (this.recaptchaWidgetId && window.grecaptcha) {
                window.grecaptcha.reset(this.recaptchaWidgetId);
                this.recaptchaToken = null;
            }
        }
    }
}