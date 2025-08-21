<script setup lang="ts">
import ListingCard from "./cards/ListingCard.vue";
import type { IListing } from "../../types.ts";

const pendingListings: IListing[] = [
  {
    id: 1,
    display_name: 'Michael Oswald',
    photo: 'https://randomuser.me/api/portraits/men/32.jpg',
    expert_type_formatted: 'Freelancer',
    email: 'michael@shopifyhero.com',
    store_title: 'Check Website',
    url: 'https://www.trustpilot.com/',
    country_formatted: 'United States',
    job_title_formatted: 'Senior Shopify Developer',
    language_formatted: 'English, Spanish',
    hourly_rate_formatted: '$1500.00',
    status_updated_at_formatted: '17 Dec, 2025',
    status_formatted: 'Pending',
    services_offered_safe: [
        'Store Setup & Management',
        'Development and Troubleshooting',
        'Training & Consultation',
    ],
  },
  {
    id: 2,
    display_name: 'Axome',
    photo: 'https://randomuser.me/api/portraits/men/44.jpg',
    expert_type_formatted: 'Agency',
    email: 'marketing@axome.com',
    store_title: 'Check Website',
    url: 'https://www.trustpilot.com/',
    country_formatted: 'Saint-Etienne/France',
    job_title_formatted: 'Senior Shopify Developer',
    language_formatted: 'French',
    hourly_rate_formatted: '$5000.00',
    status_updated_at_formatted: '17 Dec, 2025',
    status_formatted: 'Pending',
    services_offered_safe: [
        'Store Setup & Management',
        'Development and Troubleshooting',
        'Training & Consultation',
    ],
  },
  {
    id: 3,
    display_name: 'Mihail Kuznetsov',
    photo: 'https://randomuser.me/api/portraits/men/55.jpg',
    expert_type_formatted: 'Freelancer',
    email: 'mihailkuznetsovdev@gmail.com',
    store_title: 'Check Website',
    url: 'https://www.trustpilot.com/',
    country_formatted: 'United States/New York',
    job_title_formatted: 'Senior Shopify Developer',
    language_formatted: 'English, Spanish',
    hourly_rate_formatted: '$1500.00',
    status_updated_at_formatted: '17 Dec, 2025',
    status_formatted: 'Pending',
    services_offered_safe: [
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