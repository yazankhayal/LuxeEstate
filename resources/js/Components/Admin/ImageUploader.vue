<script setup>
import { ref } from 'vue'

const props = defineProps({
  multiple:    { type: Boolean, default: false },
  accept:      { type: String,  default: 'image/jpeg,image/png,image/webp' },
  maxSizeMb:   { type: Number,  default: 5 },
  existingImages: { type: Array, default: () => [] },
})

const emit    = defineEmits(['change', 'delete'])
const previews = ref([])
const error    = ref('')
const dragging = ref(false)

function onFiles(files) {
  error.value = ''
  const fileArray = Array.from(files)

  for (const file of fileArray) {
    if (file.size > props.maxSizeMb * 1024 * 1024) {
      error.value = `"${file.name}" exceeds ${props.maxSizeMb}MB limit.`
      return
    }
  }

  previews.value = fileArray.map(f => ({
    file: f,
    url:  URL.createObjectURL(f),
    name: f.name,
  }))

  emit('change', props.multiple ? fileArray : fileArray[0] || null)
}

function onDrop(e) {
  dragging.value = false
  onFiles(e.dataTransfer.files)
}
</script>

<template>
  <div class="space-y-3">
    <!-- Existing images -->
    <div v-if="existingImages.length" class="grid grid-cols-4 gap-3">
      <div v-for="img in existingImages" :key="img.id"
        class="relative group aspect-square rounded-xl overflow-hidden bg-gray-800">
        <img :src="img.url" class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity
          flex items-center justify-center">
          <button type="button" @click="$emit('delete', img.id)"
            class="bg-red-500 text-white text-xs px-3 py-1.5 rounded-lg font-medium hover:bg-red-600 transition-colors">
            Delete
          </button>
        </div>
        <span v-if="img.is_cover"
          class="absolute top-1 left-1 bg-amber-500 text-white text-[10px] px-1.5 py-0.5 rounded font-bold uppercase">
          Cover
        </span>
      </div>
    </div>

    <!-- Drop zone -->
    <label
      @dragover.prevent="dragging = true"
      @dragleave="dragging = false"
      @drop.prevent="onDrop"
      :class="[
        'flex flex-col items-center justify-center border-2 border-dashed rounded-xl p-8 cursor-pointer transition-all',
        dragging
          ? 'border-amber-500 bg-amber-500/5'
          : 'border-gray-700 hover:border-amber-500/50 hover:bg-gray-800/50'
      ]"
    >
      <svg class="w-10 h-10 text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
      </svg>
      <p class="text-sm text-gray-400 font-medium">
        {{ dragging ? 'Drop files here' : 'Click or drag images to upload' }}
      </p>
      <p class="text-xs text-gray-600 mt-1">
        {{ accept.replace(/image\//g, '').toUpperCase() }} — max {{ maxSizeMb }}MB each
      </p>
      <input
        type="file"
        :multiple="multiple"
        :accept="accept"
        class="hidden"
        @change="e => onFiles(e.target.files)"
      />
    </label>

    <!-- Error -->
    <p v-if="error" class="text-red-400 text-xs">{{ error }}</p>

    <!-- New previews -->
    <div v-if="previews.length" class="grid grid-cols-4 gap-3">
      <div v-for="(preview, i) in previews" :key="i"
        class="relative aspect-square rounded-xl overflow-hidden bg-gray-800 ring-2 ring-amber-500/40">
        <img :src="preview.url" class="w-full h-full object-cover" />
        <span class="absolute top-1 right-1 bg-black/60 text-white text-[10px] px-1.5 py-0.5 rounded font-medium">
          New
        </span>
      </div>
    </div>
  </div>
</template>
