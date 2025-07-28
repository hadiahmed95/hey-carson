<template>
  <div class="p-8 flex flex-col bg-secondary font-light gap-14 w-full">
    <!-- Header Section -->
    <div class="flex flex-row items-center justify-between">
      <div class="flex flex-col gap-2">
        <h1 class="font-sm text-primary">Packaged Services</h1>
        <p class="text-primary font-sm">
          Browse ready-made packaged services offered by experts and <br/> agencies, with a clear and predefined scope and pricing.
        </p>
      </div>
      <!-- Search Bar -->
      <div class="flex items-center border border-grey rounded-sm bg-white py-1 px-3 w-[300px] max-w-md shadow-sm">
        <Search />
        <input
            type="text"
            placeholder="Search Packaged Services ..."
            class="w-full ml-3 outline-none placeholder-tertiary"
        />
      </div>
    </div>

    <!-- Service Cards -->
    <div class="bg-white grid grid-cols-3 gap-6">
      <PackagedServices :services="clientStore.packagedServices.packaged_services" />
    </div>
  </div>
</template>

<script setup lang="ts">
import {onMounted} from 'vue'
import Search from "../../assets/icons/search.svg";
import PackagedServices from "../../components/client/cards/PackagedServices.vue";
import {useClientStore} from "@/store/client.ts";

const clientStore = useClientStore();

onMounted(async () => {
  await clientStore.fetchPackagedServices();
  console.log(clientStore.packagedServices);
});
</script>
