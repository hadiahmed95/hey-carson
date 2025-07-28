<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="flex flex-col gap-6 bg-white w-full max-w-md border border-grey rounded-xl p-8 shadow-xl relative">
      <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center">
          <h2 class="font-semibold font-archivo text-primary">Accept & Pay Project Quote</h2>
          <button
              @click="$emit('close')"
              class="text-2xl text-tertiary hover:text-black"
          >
            &times;
          </button>
        </div>

        <div class="flex flex-col gap-1">
          <h5 class="font-normal font-archivo text-tertiary">Experts Offer</h5>
          <div class="border border-grey rounded-md p-6 flex flex-col gap-6">
            <div>
              <div class="flex justify-between items-center">
                <p class="font-semibold text-primary font-archivo">Project Quote</p>
                <h5 class="bg-pending-light text-pending rounded-sm font-semibold font-archivo px-2">Pending</h5>
              </div>
              <h5 class="font-normal text-tertiary">17 Dec, 2025 / 09:45am</h5>
            </div>

            <div class="flex flex-col gap-4 text-primary">
              <div class="flex flex-col gap-1">
                <div class="flex justify-between">
                  <h5>Hourly rate</h5>
                  <p>$125.00</p>
                </div>
                <div class="flex justify-between">
                  <h5>Estimated time</h5>
                  <p>10 hours</p>
                </div>
                <div class="flex justify-between">
                  <h5>Deadline</h5>
                  <p>27 Dec, 2025</p>
                </div>
              </div>
              <div class="flex flex-col gap-4">
                <hr class="border-grey" />
                <div class="flex justify-between font-semibold text-h4">
                  <h3>Total to pay</h3>
                  <h2 class="font-semibold">$1250.00</h2>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>


      <!-- Info Text -->
      <h5 class="text-primary">
        After payments go through, the project transitions to 'In Progress,' and the expert begins work.
        Payment will be released immediately, with compensation provided upon completion.
      </h5>

      <!-- Accept & Pay Button -->
      <button
          class="w-full py-2 bg-primary text-white rounded-md text-paragraph font-semibold hover:bg-success transition-colors"
          @click="submitPayment"
      >
        Accept & Pay
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
const submitPayment = () => {
  console.log('submitPayment')
}
</script>
