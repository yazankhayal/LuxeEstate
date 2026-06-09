<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  services: Array,
})

function destroy(service) {
  if (confirm(`Delete "${service.translations?.[0]?.title || service.slug}"?`)) {
    router.delete(route('admin.services.destroy', service.id))
  }
}

function toggleActive(service) {
  router.patch(route('admin.services.update', service.id),
    { ...service, is_active: !service.is_active },
    { preserveScroll: true }
  )
}

function getTitle(service) {
  return service.translations?.find(t => t.locale === 'en')?.title
    || service.translations?.[0]?.title
    || service.slug
}
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-white">Services</h1>
        <p class="text-gray-500 text-sm mt-1">{{ services.length }} services</p>
      </div>
      <Link href="/admin/services/create"
        class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Service
      </Link>
    </div>

    <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-wider">
            <th class="text-left px-6 py-4 font-medium">Order</th>
            <th class="text-left px-6 py-4 font-medium">Service</th>
            <th class="text-left px-6 py-4 font-medium hidden md:table-cell">Icon</th>
            <th class="text-left px-6 py-4 font-medium">Active</th>
            <th class="text-right px-6 py-4 font-medium">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
          <tr v-for="service in services" :key="service.id"
              class="hover:bg-gray-800/50 transition-colors">
            <td class="px-6 py-4">
              <span class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center text-xs font-bold text-gray-400">
                {{ service.order }}
              </span>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div v-if="service.image_url" class="w-10 h-10 rounded-xl overflow-hidden bg-gray-800 shrink-0">
                  <img :src="service.image_url" class="w-full h-full object-cover" />
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center shrink-0" v-else>
                  <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                </div>
                <div>
                  <p class="font-medium text-white">{{ getTitle(service) }}</p>
                  <p class="text-xs text-gray-500">{{ service.slug }}</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 hidden md:table-cell">
              <span class="text-xs bg-gray-800 text-gray-400 px-2.5 py-1 rounded-full font-mono">
                {{ service.icon }}
              </span>
            </td>
            <td class="px-6 py-4">
              <button @click="toggleActive(service)"
                :class="[
                  'relative inline-flex h-5 w-9 items-center rounded-full transition-colors',
                  service.is_active ? 'bg-amber-500' : 'bg-gray-700'
                ]">
                <span :class="[
                  'inline-block h-3 w-3 transform rounded-full bg-white transition-transform',
                  service.is_active ? 'translate-x-5' : 'translate-x-1'
                ]" />
              </button>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-1">
                <Link :href="route('admin.services.edit', service.id)"
                  class="p-2 rounded-lg text-gray-500 hover:text-amber-400 hover:bg-amber-500/10 transition-all">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </Link>
                <button @click="destroy(service)"
                  class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-all">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="!services.length">
            <td colspan="5" class="text-center py-16 text-gray-600">
              No services yet. <Link href="/admin/services/create" class="text-amber-400 hover:underline">Add one →</Link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
