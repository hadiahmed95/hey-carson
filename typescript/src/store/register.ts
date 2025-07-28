import { defineStore } from 'pinia'
import AuthService from '@/services/auth.service'
import {withLoader} from "@/utils/helpers.ts";
import { useAuthStore } from '@/store/auth.ts'

export const useRegisterStore = defineStore('register', {
    actions: {
        async signupClient(payload: any) {
            await withLoader( async () => {
                const res = await AuthService.signupClient(payload)

                if (res.data.status) {
                    const authStore = useAuthStore()
                    authStore.setCurrentUser( res.data.user, res.data.token );
                }
            })
        },
        async signupExpert(payload: any) {
            return await withLoader( async () => {
                return await AuthService.signupExpert(payload)
            })
        },
    }
})
