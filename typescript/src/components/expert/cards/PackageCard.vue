<template>
  <div class="flex border rounded-md shadow-sm bg-white mb-card-padding p-card-padding-md">
    <div class="w-one-third">
      <img
          :src="service.image"
          :alt="service.title"
          class="w-full h-full object-cover rounded-md"
      />
    </div>

    <div class="flex-1 ml-card-padding-lg">
      <div class="flex flex-col justify-between h-full">
        <div>
          <h5 class="text-grey-black-combo text-sm mb-2">Packaged Service Name #{{ service.id }}</h5>
          <h3 class="font-semibold text-lg mb-4 w-320">{{ service.title }}</h3>
        </div>

        <div>
          <h5 class="text-grey-black-combo text-sm">Starting Price</h5>
          <h2 class="font-bold text-xl text-primary">${{ service.price.toFixed(2) }}</h2>
        </div>

        <div class="flex gap-card-padding mt-4">
          <button @click="handleEdit" class="border border-lightGray p-2 rounded-sm">
            <Pencil/>
          </button>
          <button class="border border-lightGray p-2 rounded-sm">
            <Trash/>
          </button>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import Trash from '../../../assets/icons/trash.svg'
import Pencil from '../../../assets/icons/pencil.svg'

const props = defineProps<{
  service: {
    id: number
    title: string
    price: number
    image: string
  }
}>()

const emit = defineEmits<{
  (e: 'edit', service: typeof props.service): void
}>()

// Handle edit button click
const handleEdit = () => {
  emit('edit', props.service)
}
</script>