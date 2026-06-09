<script setup>
import { reactive, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  filters: { type: Object, default: () => ({}) },
  cities:  { type: Array,  default: () => [] },
})

const form = reactive({
  type:      props.filters.type      || '',
  city:      props.filters.city      || '',
  min_price: props.filters.min_price || '',
  max_price: props.filters.max_price || '',
  bedrooms:  props.filters.bedrooms  || '',
  search:    props.filters.search    || '',
})

function apply() {
  router.get(route('properties.index'), form, { preserveState: true, replace: true })
}

function reset() {
  Object.keys(form).forEach(k => (form[k] = ''))
  apply()
}
</script>

<template>
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h3 class="font-semibold text-gray-900 mb-5">Filter Properties</h3>

    <div class="space-y-4">
      <!-- Search -->
      <div>
        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5 block">Search</label>
        <input
          v-model="form.search"
          type="text"
          placeholder="Property name or keyword..."
          class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 transition"
        />
      </div>

      <!-- Type -->
      <div>
        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5 block">Type</label>
        <div class="grid grid-cols-3 gap-2">
          <button
            v-for="opt in [{value:'', label:'All'},{value:'sale', label:'Sale'},{value:'rent', label:'Rent'}]"
            :key="opt.value"
            @click="form.type = opt.value"
            :class="[
              'py-2 rounded-xl text-sm font-medium transition-all border',
              form.type === opt.value
                ? 'bg-amber-500 text-white border-amber-500'
                : 'border-gray-200 text-gray-600 hover:border-amber-300'
            ]"
          >
            {{ opt.label }}
          </button>
        </div>
      </div>

      <!-- City -->
      <div v-if="cities.length">
        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5 block">City</label>
        <select v-model="form.city" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300">
          <option value="">All Cities</option>
          <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
        </select>
      </div>

      <!-- Price range -->
      <div>
        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5 block">Price Range</label>
        <div class="grid grid-cols-2 gap-2">
          <input v-model="form.min_price" type="number" placeholder="Min" class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300" />
          <input v-model="form.max_price" type="number" placeholder="Max" class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300" />
        </div>
      </div>

      <!-- Bedrooms -->
      <div>
        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5 block">Min Bedrooms</label>
        <div class="flex gap-2">
          <button
            v-for="n in [1,2,3,4,5]"
            :key="n"
            @click="form.bedrooms = form.bedrooms == n ? '' : n"
            :class="[
              'flex-1 py-2 rounded-xl text-sm font-medium transition-all border',
              form.bedrooms == n
                ? 'bg-amber-500 text-white border-amber-500'
                : 'border-gray-200 text-gray-600 hover:border-amber-300'
            ]"
          >{{ n }}+</button>
        </div>
      </div>

      <!-- Actions -->
      <div class="flex gap-2 pt-2">
        <button @click="apply" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white font-semibold py-2.5 rounded-xl text-sm transition-all">
          Apply Filters
        </button>
        <button @click="reset" class="px-4 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50 transition-all">
          Reset
        </button>
      </div>
    </div>
  </div>
</template>
