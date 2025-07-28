import { defineStore } from 'pinia'

export const useAlertStore = defineStore('alert', {
    state: () => ({
        message: '',
        type: 'error', // could be 'error', 'success', 'info'
        visible: false,
    }),
    actions: {
        show(message: string, type = 'error') {
            this.message = message
            this.type = type
            this.visible = true

            setTimeout(() => {
                this.visible = false
                this.message = ''
            }, 5000)
        },
    },
})
