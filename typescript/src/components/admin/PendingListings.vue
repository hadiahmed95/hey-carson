<script setup lang="ts">
import ListingCard from "./cards/ListingCard.vue";
import type { IListing } from "../../types.ts";

const pendingListings: IListing[] = [
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
]
</script>

<template>
  <div>
    <ListingCard
        v-for="listingItem in pendingListings"
        :key="listingItem.id"
        :listing="listingItem"
        :currentFilters="{}"
    />
  </div>
</template>

