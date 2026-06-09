<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import PropertyCard from '@/Components/Public/PropertyCard.vue'
import PropertyFilter from '@/Components/Public/PropertyFilter.vue'

defineOptions({ layout: PublicLayout })

const props = defineProps({
  properties: Object,
  filters:    Object,
  cities:     Array,
})
</script>

<template>
  <!-- Page header -->
  <div class="bg-gray-900 pt-28 pb-14">
    <div class="max-w-7xl mx-auto px-4">
      <p class="text-amber-400 text-sm font-semibold uppercase tracking-widest mb-2">✦ Browse</p>
      <h1 class="text-4xl font-serif font-bold text-white">All Properties</h1>
      <p class="text-gray-400 mt-2">
        {{ properties.total }} properties found
        <template v-if="filters.city"> in {{ filters.city }}</template>
      </p>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex gap-8 items-start">

      <!-- Sidebar filter -->
      <div class="w-72 shrink-0 hidden lg:block sticky top-24">
        <PropertyFilter :filters="filters" :cities="cities" />
      </div>

      <!-- Grid -->
      <div class="flex-1">
        <div v-if="properties.data.length" class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
          <PropertyCard
            v-for="property in properties.data"
            :key="property.id"
            :property="property"
          />
        </div>

        <div v-else class="text-center py-20">
          <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
          </div>
          <h3 class="text-gray-600 font-semibold text-lg mb-1">No properties found</h3>
          <p class="text-gray-400 text-sm">Try adjusting your filters.</p>
        </div>

        <!-- Pagination -->
        <div v-if="properties.last_page > 1" class="flex justify-center gap-2 mt-12">
          <component
            :is="properties.prev_page_url ? 'a' : 'span'"
            :href="properties.prev_page_url"
            :class="[
              'px-4 py-2 rounded-lg text-sm font-medium transition-all border',
              properties.prev_page_url
                ? 'border-gray-200 text-gray-700 hover:border-amber-300 hover:text-amber-600 cursor-pointer'
                : 'border-gray-100 text-gray-300 cursor-not-allowed'
            ]"
          >← Prev</component>

          <span class="px-4 py-2 text-sm text-gray-500">
            {{ properties.current_page }} / {{ properties.last_page }}
          </span>

          <component
            :is="properties.next_page_url ? 'a' : 'span'"
            :href="properties.next_page_url"
            :class="[
              'px-4 py-2 rounded-lg text-sm font-medium transition-all border',
              properties.next_page_url
                ? 'border-gray-200 text-gray-700 hover:border-amber-300 hover:text-amber-600 cursor-pointer'
                : 'border-gray-100 text-gray-300 cursor-not-allowed'
            ]"
          >Next →</component>
        </div>
      </div>
    </div>
  </div>
</template>
