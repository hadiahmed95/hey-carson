<template>
  <div class="pb-6 mt-14">
    <h3 class="font-semibold pb-2">Invited List</h3>
    <h4>Invite and manage your invitations from your current and past leads.</h4>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full border-separate border-spacing-y-2 text-h4 text-left">
      <thead class="text-h5 text-gray-500 tracking-wide">
      <tr class="text-center">
        <th class="px-4 py-2 font-medium text-left">Invited Lead</th>
        <th class="px-4 py-2 font-medium">Project Value</th>
        <th class="px-4 py-2 font-medium">Hired on shopexperts</th>
        <th class="px-4 py-2 font-medium">Repeated Client</th>
        <th class="px-4 py-2 font-medium">Invited Date</th>
        <th class="px-4 py-2 font-medium">Status</th>
      </tr>
      </thead>

      <tbody>
      <tr
          v-for="(lead, index) in invitedLeads"
          :key="index"
          class="bg-white hover:bg-gray-100 border border-gray text-center text-paragraph"
      >
        <td class="border-y border-l px-4 py-4 text-left">
          <p class="font-normal">{{ 'Petter Owens' }}</p>
          <h4 class="font-extraLight text-gray-400">{{ 'petterowens@unitedbyblue.com' }}</h4>
        </td>
        <td class="border-y px-4 py-4">$1000-$2000</td>
        <td class="border-y px-4 py-4">{{ lead.hired }}</td>
        <td class="border-y px-4 py-4">{{ lead.repeated }}</td>
        <td class="border-y px-4 py-4">{{ lead.date }}</td>
        <td class="border-y border-r px-4 py-4 content-center">
            <h6 class="bg-blue-100 text-blue-500 font-semibold px-2 py-1 w-fit rounded-sm mx-auto">
              Invited
            </h6>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">

const invitedLeads = [
  { hired: 'Yes', repeated: 'Yes', date: '17 Dec, 2025' },
  { hired: 'No', repeated: 'No', date: '15 Dec, 2025' },
  { hired: 'Yes', repeated: 'No', date: '11 Dec, 2025' },
  { hired: 'No', repeated: 'Yes', date: '08 Dec, 2025' },
  { hired: 'Yes', repeated: 'Yes', date: '03 Dec, 2025' },
]
</script>
