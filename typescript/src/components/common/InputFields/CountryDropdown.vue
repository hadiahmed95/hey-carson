<script setup lang="ts">
import { useExpertStore } from '@/store/expert';
import { ref, onMounted, computed } from 'vue'

defineProps<{
  error?: string
}>()

interface CountryName {
  common: string
}

interface Country {
  name: CountryName
  cca2: string
}

const model = defineModel<string>()
const countries = ref<Country[]>([])
const searchTerm = ref('')
const showDropdown = ref(false)
const expertStore = useExpertStore();

const filteredCountries = computed(() => {
  if (!searchTerm.value) return countries.value
  return countries.value.filter(country =>
      country.name.common.toLowerCase().includes(searchTerm.value.toLowerCase())
  )
})

const selectCountry = (countryName: string) => {
  model.value = countryName
  searchTerm.value = countryName
  showDropdown.value = false
}

const filterCountries = () => {
  model.value = searchTerm.value
}

const hideDropdown = () => {
  setTimeout(() => showDropdown.value = false, 100)
}

onMounted(async () => {
  try {
    const response = await expertStore.fetchCountries();
    const data = response
    countries.value = data.sort((a: Country, b: Country) => a.name.common.localeCompare(b.name.common))
  } catch (err) {
    console.error('Failed to fetch countries:', err)
  }
})
</script>

<template>
  <div class="flex flex-col gap-1 relative">
    <label class="block text-h5 font-sm font-archivo text-primary">Country</label>
    <input
        v-model="searchTerm"
        @input="filterCountries"
        @focus="showDropdown = true"
        @blur="hideDropdown"
        placeholder="Select or type country"
        class="w-full border px-4 py-2 rounded-md text-primary font-archivo text-paragraph focus:ring-primary focus:border-primary focus:outline-none focus:ring-0"
        :class="showDropdown && filteredCountries.length? 'rounded-bl-none rounded-br-none':''"
    >
    <div v-if="showDropdown && filteredCountries.length" class="absolute top-[62px] z-10 w-full bg-card border border-primary border-t-0 rounded-md mt-1 max-h-48 overflow-y-auto rounded-tl-none rounded-tr-none">
      <div
          v-for="country in filteredCountries"
          :key="country.cca2"
          @mousedown="selectCountry(country.name.common)"
          class="px-4 py-2 hover:bg-primary hover:text-card cursor-pointer"
      >
        {{ country.name.common }}
      </div>
    </div>
    <h5 v-if="error" class="text-danger mt-2">
      {{ error }}
    </h5>
  </div>
</template>