<template>
  <div class="fixed inset-0 z-50 flex justify-end bg-black/50">
    <div class="flex flex-col gap-8 bg-white w-[550px] h-full overflow-y-auto shadow-xl p-8 relative">

      <!-- Close Button -->
      <div>
        <div class="relative flex justify-start items-center mb-4">
          <h1 class="font-normal text-primary text-center">
            Submit your <em class="font-besley">request</em>
          </h1>
          <button
              @click="closeModal"
              class="absolute right-0 text-gray-500 hover:text-black text-2xl font-light"
          >
            &times;
          </button>
        </div>

        <p class="font-normal text-primary">
          Submit your request, and we’ll find the perfect match for your project needs so you can focus on what’s most important for your business.
        </p>
      </div>

      <!-- Form -->
      <BaseInput
          label="Your website"
          v-model="website"
          placeholder="https://myshopifystore.com"
          :error="errors.website"
      />

      <BaseInput
          label="Project title"
          v-model="title"
          placeholder="Give your project a short and descriptive title"
          :error="errors.title"
      />

      <div class="flex flex-col gap-1">
        <label class="block text-h5 font-normal font-archivo text-primary">About the project</label>
        <div class="rounded-md border border-lightGray overflow-hidden">
          <BaseInput
              v-model="description"
              textarea
              placeholder="Please try to be as detailed as possible, as this helps us provide you with best possible solutions ..."
              noStyle
              hideLabel
          />
          <div class="border-t border-lightGray">
            <button class="w-full px-4 py-2 text-paragraph text-tertiary-dark font-normal flex items-center gap-2">
              <img :src="attachFileIcon" class="w-5 h-5" />
              Attach files
            </button>
          </div>
        </div>
        <h5 v-if="errors.description" class="text-red-600 mt-2">
          {{ errors.description }}
        </h5>
      </div>

      <div class="flex flex-col gap-1">
        <p class="text-h5 text-tertiary-dark font-normal">Preferred Expert</p>
        <div class="relative w-full">
          <!-- Input -->
          <input
              type="text"
              v-model="search"
              @focus="open = true"
              @input="fetchExperts"
              placeholder="Enter the name of an expert"
              class="w-full border border-lightGray rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-primary"
          />

          <!-- Dropdown -->
          <ul
              v-if="open && experts.length"
              class="absolute z-10 mt-1 w-full bg-white border border-lightGray rounded-md shadow-lg max-h-60 overflow-auto"
          >
            <li
                v-for="expert in experts"
                :key="expert.id"
                @click="selectExpert(expert)"
                class="flex items-center gap-4 px-4 py-3 cursor-pointer hover:bg-gray-100"
            >
              <img
                  :src="getS3URL(expert.photo)"
                  class="w-12 h-12 rounded-full object-cover"
              />
              <div>
                <p class="font-archivo font-semibold">{{ expert.first_name }} {{ expert.last_name }}</p>
                <p class="text-h4 text-primary font-normal">{{ expert.profile.role }}</p>
              </div>
            </li>
          </ul>

          <!-- Selected display -->
          <div
              v-if="expert"
              class="mt-4 border border-lightGray rounded-sm px-4 py-2 flex items-center gap-4"
          >
            <img
                :src="getS3URL(expert.photo)"
                class="w-12 h-12 rounded-full object-cover"
            />
            <div>
              <p class="font-archivo font-semibold">{{ expert.first_name }} {{ expert.last_name }}</p>
              <p class="text-h4 text-primary font-normal">{{ expert.profile?.role }}</p>
            </div>
          </div>
        </div>
        <h5 v-if="errors.expert" class="text-red-600 mt-2">
          {{ errors.expert }}
        </h5>
      </div>

      <div class="flex items-start gap-4">
        <input
            type="checkbox"
            v-model="sendToMore"
            class="translate-y-[2px] rounded-[4px] border border-lightGray accent-primary hover:accent-primary cursor-pointer"
        />
        <div>
          <p class="text-h4 text-primary font-semibold">
            Send this quote request to 3 additional experts
          </p>
          <p class="text-h5 text-primary font-normal">
            We'll automatically forward your quote request to three additional experts who match your criteria, so you can compare multiple quotes.
          </p>
        </div>
      </div>

      <div class="flex items-start gap-4">
        <input
            type="checkbox"
            v-model="isUrgent"
            class="translate-y-[2px] rounded-[4px] border border-lightGray accent-primary hover:accent-primary cursor-pointer"
        />
        <div>
          <p class="text-h4 text-primary font-semibold">This project is urgent</p>
          <p class="text-h5 text-primary font-normal">
            If you need a fast turnaround, let experts know your project is urgent.
          </p>
        </div>
      </div>

      <div class="bg-primary text-white rounded-md py-2 text-paragraph font-semibold hover:bg-success hover:cursor-pointer"
           :class="[isLoading  && 'opacity-50 cursor-not-allowed']"
      >
        <Spinner v-if="isLoading" />
        <button class="flex w-full justify-center items-center gap-2"
          v-else
          @click="submit"
        >
          <span>Submit Review</span>
          <Arrow class="w-4 h-4" />
        </button>
      </div>

      <h5 class="text-tertiary-dark font-normal">
        This site is protected by reCAPTCHA and the Google Privacy Policy and Terms of Service apply.
      </h5>
    </div>
  </div>
</template>

<script lang="ts" setup>
import {ref, defineEmits, onMounted, computed} from 'vue'
import BaseInput from "@/components/common/InputFields/BaseInput.vue";
import {getS3URL, isValidUrl} from "@/utils/helpers.ts";
import type {IExpertt} from "@/types.ts";
import {useCommonStore} from "@/store/common.ts";
import Arrow from "@/assets/icons/arrow.svg";
import Spinner from "@/components/common/Spinner.vue";
import {useLoaderStore} from "@/store/loader.ts";
import { useClientStore } from "@/store/client.ts";

const loader = useLoaderStore();
const isLoading = computed(() => loader.isLoadingState);
const clientStore = useClientStore();

const commonStore = useCommonStore()
const attachFileIcon = new URL('../../../assets/icons/attachFile.svg', import.meta.url).href
const emit = defineEmits(['close'])

const search = ref( '')
const experts = ref<IExpertt[]>([])
const open = ref(false)

const website = ref('')
const title = ref('')
const description = ref('')
const expert = ref<any>(null)
const isUrgent = ref(false)
const sendToMore = ref(false)

const errors = ref({
  website: '',
  title: '',
  description: '',
  expert: '',
})

const props = withDefaults(defineProps<{
  myRequestsPage?: boolean;
}>(), {
  myRequestsPage: false,
});

onMounted(async () => {
  try {
    experts.value = await commonStore.getExperts({ all: true })
  } catch (err) {
    console.error('Error fetching experts:', err)
  }
})

const fetchExperts = async () => {
  if (!search.value.trim()) {
    experts.value = []
    return
  }

  try {
    experts.value = await commonStore.getExperts({search: search.value})
    console.log(experts.value)
  } catch (err) {
    console.error('Error fetching experts:', err)
  }
}

const selectExpert = (selectedExpert: any) => {
  expert.value = selectedExpert
  open.value = false
  console.log(expert.value)
}

const validate = () => {
  let isValid = true
  errors.value = {
    website: '',
    title: '',
    description: '',
    expert: '',
  }

  if (!website.value.trim()) {
    errors.value.website = 'Website URL is required.'
    isValid = false
  } else if (!isValidUrl(website.value.trim())) {
    errors.value.website = 'Please enter a valid URL (e.g., https://yourstore.com).'
    isValid = false
  }

  if (!title.value.trim()) {
    errors.value.title = 'Project title is required.'
    isValid = false
  }

  if (!description.value.trim()) {
    errors.value.description = 'Description is required.'
    isValid = false
  }

  if (!expert.value?.id) {
    errors.value.expert = 'Please select a preferred expert.'
    isValid = false
  }

  return isValid
}

async function submit() {
  if (!validate()) return

  const payload = {
    store_url: website.value,
    project_name: title.value,
    project_description: description.value,
    preferred_expert_id: expert.value.id,
    is_urgent: isUrgent.value,
    send_to_more_experts: sendToMore.value,
  }

  await clientStore.createRequest(payload, props.myRequestsPage)
  closeModal()
}

function closeModal() {
  emit('close')
}
</script>


<style scoped>
.input {
  @apply border border-gray-300 px-3 py-2 rounded-md text-sm focus:outline-none focus:ring focus:ring-primary;
}
</style>
