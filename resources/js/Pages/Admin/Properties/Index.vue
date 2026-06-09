<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  properties: Object,
  filters:    Object,
})

function destroy(property) {
  if (confirm(`Delete "${property.translation?.title || property.slug}"? This cannot be undone.`)) {
    router.delete(route('admin.properties.destroy', property.id))
  }
}

const statusColors = {
  active:   'bg-emerald-500/15 text-emerald-400',
  inactive: 'bg-gray-500/15 text-gray-400',
  sold:     'bg-blue-500/15 text-blue-400',
  rented:   'bg-orange-500/15 text-orange-400',
}

const typeColors = {
  sale: 'bg-blue-500/15 text-blue-400',
  rent: 'bg-emerald-500/15 text-emerald-400',
}

function formatPrice(price, currency = 'USD') {
  return new Intl.NumberFormat('en-US', { style: 'currency', currency, maximumFractionDigits: 0 }).format(price)
}
</script>

<template>
  <div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-white">Properties</h1>
        <p class="text-gray-500 text-sm mt-1">{{ properties.total }} total properties</p>
      </div>
      <Link
        href="/admin/properties/create"
        class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Property
      </Link>
    </div>

    <!-- Filters bar -->
    <div class="bg-gray-900 rounded-2xl border border-gray-800 p-4 mb-6 flex flex-wrap gap-3">
      <input
        :value="filters.search"
        @input="e => router.get('/admin/properties', { ...filters, search: e.target.value }, { preserveState: true, replace: true })"
        type="text"
        placeholder="Search properties..."
        class="bg-gray-800 border border-gray-700 rounded-xl px-4 py-2 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40"
      />
      <select
        :value="filters.type"
        @change="e => router.get('/admin/properties', { ...filters, type: e.target.value }, { preserveState: true, replace: true })"
        class="bg-gray-800 border border-gray-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none"
      >
        <option value="">All Types</option>
        <option value="sale">For Sale</option>
        <option value="rent">For Rent</option>
      </select>
      <select
        :value="filters.status"
        @change="e => router.get('/admin/properties', { ...filters, status: e.target.value }, { preserveState: true, replace: true })"
        class="bg-gray-800 border border-gray-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none"
      >
        <option value="">All Statuses</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
        <option value="sold">Sold</option>
        <option value="rented">Rented</option>
      </select>
    </div>

    <!-- Table -->
    <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-wider">
            <th class="text-left px-6 py-4 font-medium">Property</th>
            <th class="text-left px-6 py-4 font-medium hidden md:table-cell">Type</th>
            <th class="text-left px-6 py-4 font-medium hidden lg:table-cell">Status</th>
            <th class="text-left px-6 py-4 font-medium hidden lg:table-cell">Price</th>
            <th class="text-left px-6 py-4 font-medium hidden xl:table-cell">City</th>
            <th class="text-right px-6 py-4 font-medium">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
          <tr
            v-for="property in properties.data"
            :key="property.id"
            class="hover:bg-gray-800/50 transition-colors"
          >
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gray-800 overflow-hidden shrink-0">
                  <img
                    v-if="property.cover_image?.url"
                    :src="property.cover_image.url"
                    class="w-full h-full object-cover"
                    alt=""
                  />
                  <div v-else class="w-full h-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                  </div>
                </div>
                <div>
                  <p class="font-medium text-white truncate max-w-[200px]">
                    {{ property.translation?.title || property.slug }}
                  </p>
                  <p class="text-xs text-gray-500">{{ property.slug }}</p>
                </div>
                <span v-if="property.is_featured" class="hidden sm:inline-flex text-xs bg-amber-500/20 text-amber-400 px-2 py-0.5 rounded-full font-medium">
                  ★ Featured
                </span>
              </div>
            </td>
            <td class="px-6 py-4 hidden md:table-cell">
              <span :class="['text-xs font-medium px-2.5 py-1 rounded-full capitalize', typeColors[property.type]]">
                {{ property.type === 'sale' ? 'For Sale' : 'For Rent' }}
              </span>
            </td>
            <td class="px-6 py-4 hidden lg:table-cell">
              <span :class="['text-xs font-medium px-2.5 py-1 rounded-full capitalize', statusColors[property.status]]">
                {{ property.status }}
              </span>
            </td>
            <td class="px-6 py-4 hidden lg:table-cell">
              <span class="font-semibold text-amber-400">
                {{ formatPrice(property.price, property.currency) }}
              </span>
            </td>
            <td class="px-6 py-4 hidden xl:table-cell text-gray-400">
              {{ property.city }}
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <a
                  :href="route('properties.show', property.slug)"
                  target="_blank"
                  class="p-2 rounded-lg text-gray-500 hover:text-blue-400 hover:bg-blue-500/10 transition-all"
                  title="View"
                >
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                </a>
                <Link
                  :href="route('admin.properties.edit', property.id)"
                  class="p-2 rounded-lg text-gray-500 hover:text-amber-400 hover:bg-amber-500/10 transition-all"
                  title="Edit"
                >
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </Link>
                <button
                  @click="destroy(property)"
                  class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-all"
                  title="Delete"
                >
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="!properties.data.length">
            <td colspan="6" class="text-center py-16 text-gray-600">
              No properties found. <Link href="/admin/properties/create" class="text-amber-400 hover:underline">Create one →</Link>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div v-if="properties.last_page > 1" class="flex items-center justify-between px-6 py-4 border-t border-gray-800">
        <p class="text-xs text-gray-500">
          Showing {{ properties.from }}–{{ properties.to }} of {{ properties.total }}
        </p>
        <div class="flex gap-2">
          <a
            v-if="properties.prev_page_url"
            :href="properties.prev_page_url"
            class="px-4 py-2 text-xs rounded-lg border border-gray-700 text-gray-400 hover:border-amber-500 hover:text-amber-400 transition-all"
          >← Prev</a>
          <a
            v-if="properties.next_page_url"
            :href="properties.next_page_url"
            class="px-4 py-2 text-xs rounded-lg border border-gray-700 text-gray-400 hover:border-amber-500 hover:text-amber-400 transition-all"
          >Next →</a>
        </div>
      </div>
    </div>
  </div>
</template>
