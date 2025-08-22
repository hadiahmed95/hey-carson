<script setup lang="ts">
const emit = defineEmits<{
  (e: 'close'): void
}>()
</script>

<template>
  <div class="fixed inset-0 z-50 bg-black/50 flex justify-end">
    <!-- Side Modal Panel -->
    <div class="w-full max-w-lg h-full bg-card shadow-xl p-8 overflow-y-auto relative text-primary font-archivo rounded-tl-md rounded-bl-md">
      <div class="flex items-center justify-between mb-8">
        <!-- Header -->
        <h1 class="font-normal">
          Send your <span class="italic font-besley">project quote</span>
        </h1>
        <button
            class="text-greyDark font-normal text-h1"
            @click="emit('close')"
        >&times;</button>
      </div>

      <!-- Form -->
      <form class="space-y-8">
        <!-- Hourly Rate -->
        <div>
          <label class="block text-h5 font-normal mb-1" for="hourlyRate">Enter your hourly rate (USD)</label>
          <input
              id="hourlyRate"
              type="text"
              placeholder="$125.00"
              class="w-full border border-grey text-h4 text-primary rounded-md px-4 py-2"
          />
          <h5 class="text-tertiary mt-1">
            This is your default hourly rate from your profile. You can adjust it up or down for this specific project.
          </h5>
        </div>

        <!-- Estimated Time -->
        <div>
          <label class="block text-h5 font-normal mb-1" for="estimatedTime">Estimated time needed to complete this project (hours)</label>
          <input
              id="estimatedTime"
              type="text"
              placeholder="10"
              class="w-full border border-grey text-h4 text-primary rounded-md px-4 py-2"
          />
          <h5 class="text-tertiary mt-1">
            Enter the estimated number of hours required to complete the project.
          </h5>
        </div>

        <!-- Deadline -->
        <div>
          <label class="block text-h5 font-normal mb-1" for="deadline">Deadline date</label>
          <input
              id="deadline"
              type="text"
              placeholder="17 Dec, 2025"
              class="w-full border border-grey text-h4 text-primary rounded-md px-4 py-2"
          />
          <h5 class="text-tertiary mt-1">
            Set a deadline that's achievable, considering the project scope.
          </h5>
        </div>

        <!-- Project Quote -->
        <div>
          <h5 class="text-primary">Project quote</h5>
          <h2 class="font-semibold mt-1">$1250.00</h2>
        </div>

        <!-- Submit Button -->
        <button
            type="submit"
            class="w-full bg-primary text-white text-paragraph font-semibold py-2 rounded-md hover:opacity-90 transition"
        >
          Send quote to client
        </button>
      </form>
    </div>
  </div>


</template>
