<template>
  <div
      class="min-h-screen w-screen"
      :style="{ backgroundImage: `url(${backgroundImage}), linear-gradient(180deg, #FFC2B1, #FFFFFF)`, backgroundSize: 'cover', backgroundPosition: 'center' }"
  >
    <div class="flex flex-col md:flex-row w-full h-screen">
      <!-- Left Side: Signup Form -->
      <div class="border rounded-md md:w-[34.5rem] flex flex-col bg-white h-full overflow-y-scroll">
        <div class="flex flex-col h-full px-8 py-6 gap-14">

          <!-- Group 1: Logo -->
          <Logo />

          <!-- Group 2: Main Content -->
          <div class="flex flex-col gap-8">

            <!-- Group 4: Top Texts -->
            <div class="flex flex-col gap-4">
              <h4 class="font-normal text-primary">
                Already have an account?
                <span class="text-blue-600 cursor-pointer hover:underline" @click="goToLogin">Login</span>
              </h4>

              <h1 class="font-normal text-primary">
                Open Free <em class="font-italic">Client Account</em>
              </h1>

              <p class="text-primary">
                Dive into our Shopify experts directory and connect with top global and local experts in just minutes – no fuss, just results!
              </p>
            </div>

            <!-- Group 5: Inputs -->
            <div class="flex flex-col gap-8">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <BaseInput
                    label="First Name"
                    placeholder="First Name"
                    v-model="firstName"
                    :error="errors.firstName"
                />
                <BaseInput
                    label="Last Name"
                    placeholder="Last Name"
                    v-model="lastName"
                    :error="errors.lastName"
                />
              </div>

              <BaseInput
                  label="Your Email Address"
                  type="email"
                  placeholder="Enter your email"
                  v-model="email"
                  :error="errors.email"
              />

              <BaseInput
                  label="Shopify Store/Website URL"
                  type="text"
                  placeholder="https://your-store-name.myshopify.com"
                  v-model="storeUrl"
                  :error="errors.storeUrl"
              />

              <div class="flex flex-col gap-2">
                <label for="password" class="text-h5 font-sm text-primary">Password</label>
                <PasswordInput
                  v-model="password"
                  label="Password"
                  :error="errors.password"
                />
              </div>
            </div>
          </div>

          <!-- Group 3: Sign up and Terms -->
          <div class="flex flex-col gap-4">
            <BaseButton :loading-button="true" class="w-full text-white font-semibold" :isPrimary="true" @click="signUp">
              Sign up
            </BaseButton>

            <h6 class="text-center text-primary">
              By proceeding, you agree to the
              <a href="#" class="underline">Terms & Conditions</a> and
              <a href="#" class="underline">Privacy Policy</a>.
            </h6>
          </div>
        </div>
      </div>

      <!-- Right Side: Visual Section -->
      <div class="w-full md:w-2/3 h-full px-6">
        <div class="grid grid-rows-3 h-full">
          <div></div>
          <div></div>

          <div class="flex flex-col justify-end max-w-3xl mx-auto gap-8">
            <div class="flex flex-col gap-2">
              <h2 class="text-primary text-h1 font-sm font-archivo">Finding your next Shopify expert</h2>
              <p class="italic text-3xl text-primary">just got way easier.</p>
            </div>

            <img
                :src="rightSideImage"
                alt="Experts Preview"
                class="w-full rounded-xl shadow-lg object-cover"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import {onMounted, ref} from 'vue'
import { useRouter } from 'vue-router'
import Logo from '../../assets/icons/logo.svg'
import BaseInput from '../../components/common/InputFields/BaseInput.vue'
import PasswordInput from '../../components/common/InputFields/PasswordInput.vue'
import BaseButton from '../../components/common/InputFields/BaseButton.vue'
import {useRegisterStore} from "@/store/register.ts";
import {validateEmail} from "@/utils/helpers.ts";
import {useAuthStore} from "@/store/auth.ts";

const router = useRouter()
const registerStore = useRegisterStore()
const authStore = useAuthStore()

// Form data
const firstName = ref('')
const lastName = ref('')
const email = ref('')
const storeUrl = ref('')
const password = ref('')

// Images
const rightSideImage = new URL('../../assets/icons/signup-side.png', import.meta.url).href
const backgroundImage = new URL('../../assets/icons/background.svg', import.meta.url).href


const errors = ref({
  firstName: '',
  lastName: '',
  email: '',
  storeUrl: '',
  password: '',
})

onMounted(async () => {
  if (authStore.token && authStore.user) {
    await router.push('/client/dashboard')
  }
})


const validURL = (url: string) => {
  try {
    new URL(url);
    return true;
  } catch (_) {
    return false;
  }
}

const validateForm = () => {
  let isValid = true
  errors.value = {
    firstName: '',
    lastName: '',
    email: '',
    storeUrl: '',
    password: '',
  }

  if (!firstName.value.trim()) {
    errors.value.firstName = 'First name is required.'
    isValid = false
  }
  if (!lastName.value.trim()) {
    errors.value.lastName = 'Last name is required.'
    isValid = false
  }
  if (!email.value.trim()) {
    errors.value.email = 'Email is required.'
    isValid = false
  } else if (!validateEmail(email.value)) {
    errors.value.email = 'Please enter a valid email.'
    isValid = false
  }
  if (!storeUrl.value.trim()) {
    errors.value.storeUrl = 'Store URL is required.'
    isValid = false
  } else if (!validURL(storeUrl.value)) {
    errors.value.storeUrl = 'Please enter a valid URL (e.g., https://yourstore.com).'
    isValid = false
  }
  if (!password.value) {
    errors.value.password = 'Password is required.'
    isValid = false
  } else if (password.value.length < 8) {
    errors.value.password = 'Password must be at least 8 characters.'
    isValid = false
  }

  return isValid
}

// Navigation
const signUp = async () => {
  if (validateForm()) {
    const payload = {
      client: {
        first_name: firstName.value,
        last_name:  lastName.value,
        url:        storeUrl.value,
        email:      email.value,
        password:   password.value,
      },
      user_type: 'client',
      new_dashboard: true,
    }

    await registerStore.signupClient(payload)

    if (authStore.token && authStore.user) {
      await router.push('/client/dashboard')
    }
  }
}

const goToLogin = () => {
  router.push('/client/login')
}
</script>
