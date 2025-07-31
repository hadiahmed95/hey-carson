<script setup lang="ts">

import { ref } from "vue";
import ListingCard from "../../components/admin/cards/ListingCard.vue";
import Search from "../../assets/icons/search.svg";


const status = ref('')
const typeOfAccount = ref('')
const plan = ref('')
const role = ref('')
const country = ref('')
const city = ref('')
const language = ref('')
const servicesOffered = ref('')
const searchQuery = ref('')

const listing = [
  {
    id: 1,
    name: 'Michael Oswald',
    displayUrl: 'https://randomuser.me/api/portraits/men/32.jpg',
    type: 'Freelancer',
    email: 'michael@shopifyhero.com',
    storeTitle: 'Check Website',
    storeUrl: 'https://www.trustpilot.com/',
    country: 'Michael Oswald',
    jobTitle: 'Senior Shopify Developer',
    language: 'English, Spanish',
    minimumProjectBudget: '$1500.00',
    statusUpdatedAt: '17 Dec, 2025',
    status: 'Pending',
    servicesOffered: [
      'Store Setup & Management',
      'Development and Troubleshooting',
      'Training & Consultation',
    ],
  },
  {
    id: 2,
    name: 'Axome',
    displayUrl: 'https://randomuser.me/api/portraits/men/44.jpg',
    type: 'Agency',
    email: 'marketing@axome.com',
    storeTitle: 'Check Website',
    storeUrl: 'https://www.trustpilot.com/',
    country: 'Saint-Etienne/France',
    jobTitle: 'Senior Shopify Developer',
    language: 'Saint-Etienne/France',
    minimumProjectBudget: '$5000.00',
    statusUpdatedAt: '17 Dec, 2025',
    status: 'Pending',
    servicesOffered: [
      'Store Setup & Management',
      'Development and Troubleshooting',
      'Training & Consultation',
    ],
  },
  {
    id: 3,
    name: 'Mihail Kuznetsov',
    displayUrl: 'https://randomuser.me/api/portraits/men/55.jpg',
    type: 'Freelancer',
    email: 'mihailkuznetsovdev@gmail.com',
    storeTitle: 'Check Website',
    storeUrl: 'https://www.trustpilot.com/',
    country: 'United States/New York',
    jobTitle: 'Senior Shopify Developer',
    language: 'English, Spanish',
    minimumProjectBudget: '$1500.00',
    statusUpdatedAt: '17 Dec, 2025',
    status: 'Pending',
    servicesOffered: [
      'Store Setup & Management',
      'Development and Troubleshooting',
      'Training & Consultation',
    ],
  },
  {
    id: 4,
    name: 'Markus Rashford',
    displayUrl: 'https://randomuser.me/api/portraits/men/66.jpg',
    type: 'Freelancer',
    email: 'markusrasdev@yahoo.com',
    storeTitle: 'Check Website',
    storeUrl: 'https://www.trustpilot.com/',
    country: 'United Kingdom, Manchester',
    jobTitle: 'Senior Shopify Developer',
    language: 'English, Spanish',
    minimumProjectBudget: '$999.00',
    status: 'Active',
    statusUpdatedAt: '17 Dec, 2025',
    servicesOffered: [
      'Store Setup & Management',
      'Development and Troubleshooting',
      'Training & Consultation',
    ],
  },
  {
    id: 5,
    name: 'KLoc Technologies Pvt Ltd',
    displayUrl: 'https://randomuser.me/api/portraits/men/13.jpg',
    type: 'Agency',
    email: 'ecomexpert@kloctechnologies.com',
    storeTitle: 'Check Website',
    storeUrl: 'https://www.trustpilot.com/',
    country: 'India, Bangalore',
    jobTitle: 'Senior Shopify Developer',
    language: 'English, Spanish',
    minimumProjectBudget: '$2500.00',
    status: 'Claimed',
    statusUpdatedAt: '17 Dec, 2025',
    servicesOffered: [
      'Store Setup & Management',
      'Development and Troubleshooting',
      'Training & Consultation',
    ],
  },
]
</script>

<template>
  <main class="flex-1 p-8 overflow-y-auto bg-secondary font-light space-y-6">
    <div class="flex flex-row justify-between">
      <div>
        <h1>
          Listings <span class="text-gray-500">({{ listing.length }})</span>
        </h1>
      </div>
      <div class="flex items-center border border-grey rounded-sm bg-white py-1 px-3 w-[300px] max-w-md shadow-sm">
        <Search />
        <input
            type="text"
            placeholder="Search Experts ..."
            class="w-full ml-3 text-h4 outline-none placeholder-tertiary"
            v-model="searchQuery"
        />
      </div>
    </div>

    <div class="mt-1 text-paragraph space-x-3">
      <select v-model="status" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Status: All</option>
        <option value="In Progress">In Progress</option>
        <option value="Completed">Completed</option>
        <option value="Closed">Closed</option>
      </select>

      <select v-model="typeOfAccount" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Type of Account: All</option>
        <option value="freelance">Freelance</option>
        <option value="agency">Agency</option>
      </select>

      <select v-model="plan" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Plan: All</option>
        <option value="free">free</option>
        <option value="paid">Paid</option>
      </select>

      <select v-model="role" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Role: All</option>
        <option value="developer">Developer</option>
        <option value="designer">Designer</option>
      </select>

      <select v-model="country" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Country: All</option>
        <option value="united_states">United Sates</option>
        <option value="canada">Canada</option>
      </select>

      <select v-model="city" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">City: All</option>
        <option value="new_york">New York</option>
        <option value="canada">Canada</option>
      </select>

      <select v-model="language" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Language: All</option>
        <option value="en_us">English US</option>
        <option value="en_ca">English CA</option>
      </select>

      <select v-model="servicesOffered" class="border rounded-sm px-1 w-auto py-2 text-h4 hover:bg-gray-100">
        <option value="">Services Offered: All</option>
        <option value="store_management">Store Management</option>
        <option value="store_marketing">Store Marketing</option>
      </select>
    </div>

    <div>
      <ListingCard
          v-for="listingItem in listing"
          :key="listingItem.id"
          :listing="listingItem"
      />
    </div>
    
  </main>
</template>
