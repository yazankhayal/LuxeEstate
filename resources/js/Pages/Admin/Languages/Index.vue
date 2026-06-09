<script setup>
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  languages: Array,
})

function toggleActive(lang) {
  router.patch(route('admin.languages.update', lang.id), {
    ...lang, is_active: !lang.is_active,
  }, { preserveScroll: true })
}

function setDefault(lang) {
  router.post(`/admin/languages/${lang.id}/default`, {}, { preserveScroll: true })
}

function destroy(lang) {
  if (lang.is_default) return alert('Cannot delete the default language.')
  if (confirm(`Remove "${lang.name}" language?`)) {
    router.delete(route('admin.languages.destroy', lang.id))
  }
}
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-white">Languages</h1>
        <p class="text-gray-500 text-sm mt-1">Manage available site languages</p>
      </div>
      <Link href="/admin/languages/create"
        class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Language
      </Link>
    </div>

    <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-wider">
            <th class="text-left px-6 py-4 font-medium">Language</th>
            <th class="text-left px-6 py-4 font-medium">Code</th>
            <th class="text-left px-6 py-4 font-medium">Direction</th>
            <th class="text-left px-6 py-4 font-medium">Status</th>
            <th class="text-left px-6 py-4 font-medium">Default</th>
            <th class="text-right px-6 py-4 font-medium">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
          <tr v-for="lang in languages" :key="lang.id"
              class="hover:bg-gray-800/50 transition-colors">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <span class="text-2xl">{{ lang.flag }}</span>
                <div>
                  <p class="font-medium text-white">{{ lang.name }}</p>
                  <p class="text-xs text-gray-500">{{ lang.native_name }}</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <span class="font-mono text-xs bg-gray-800 text-amber-400 px-2.5 py-1 rounded-lg uppercase font-bold">
                {{ lang.code }}
              </span>
            </td>
            <td class="px-6 py-4">
              <span :class="[
                'text-xs px-2.5 py-1 rounded-full font-medium',
                lang.direction === 'rtl' ? 'bg-purple-500/15 text-purple-400' : 'bg-gray-700/50 text-gray-400'
              ]">
                {{ lang.direction === 'rtl' ? '← RTL' : 'LTR →' }}
              </span>
            </td>
            <td class="px-6 py-4">
              <button @click="toggleActive(lang)"
                :disabled="lang.is_default"
                :class="[
                  'relative inline-flex h-5 w-9 items-center rounded-full transition-colors',
                  lang.is_active ? 'bg-amber-500' : 'bg-gray-700',
                  lang.is_default ? 'cursor-not-allowed opacity-60' : 'cursor-pointer'
                ]">
                <span :class="[
                  'inline-block h-3 w-3 transform rounded-full bg-white transition-transform',
                  lang.is_active ? 'translate-x-5' : 'translate-x-1'
                ]" />
              </button>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <span v-if="lang.is_default"
                  class="text-xs bg-amber-500/15 text-amber-400 px-2.5 py-1 rounded-full font-semibold">
                  ★ Default
                </span>
                <button v-else @click="setDefault(lang)"
                  class="text-xs text-gray-500 hover:text-amber-400 transition-colors">
                  Set default
                </button>
              </div>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-1">
                <Link :href="route('admin.languages.edit', lang.id)"
                  class="p-2 rounded-lg text-gray-500 hover:text-amber-400 hover:bg-amber-500/10 transition-all">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </Link>
                <button @click="destroy(lang)" :disabled="lang.is_default"
                  :class="[
                    'p-2 rounded-lg transition-all',
                    lang.is_default
                      ? 'text-gray-700 cursor-not-allowed'
                      : 'text-gray-500 hover:text-red-400 hover:bg-red-500/10'
                  ]">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
