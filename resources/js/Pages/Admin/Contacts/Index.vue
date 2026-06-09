<script setup>
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  contacts: Object,
  stats:    Object,
  filters:  Object,
})

function markReplied(contact) {
  router.post(route('admin.contacts.replied', contact.id))
}

function destroy(contact) {
  if (confirm('Delete this inquiry?')) {
    router.delete(route('admin.contacts.destroy', contact.id))
  }
}

const statusColors = {
  new:      'bg-amber-500/15 text-amber-400',
  read:     'bg-blue-500/15 text-blue-400',
  replied:  'bg-emerald-500/15 text-emerald-400',
  archived: 'bg-gray-500/15 text-gray-400',
}
</script>

<template>
  <div>
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-white">Contact Inquiries</h1>
      <p class="text-gray-500 text-sm mt-1">{{ stats.total }} total inquiries</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-4 gap-4 mb-8">
      <div v-for="(val, key) in { 'Total': stats.total, 'New': stats.new, 'Today': stats.today, 'Replied': stats.replied }"
           :key="key"
           class="bg-gray-900 rounded-2xl border border-gray-800 p-5 text-center"
      >
        <p class="text-2xl font-bold text-white">{{ val }}</p>
        <p class="text-xs text-gray-500 mt-1 uppercase tracking-wide">{{ key }}</p>
      </div>
    </div>

    <!-- Filters -->
    <div class="flex gap-3 mb-6">
      <select
        :value="filters.status"
        @change="e => router.get('/admin/contacts', { ...filters, status: e.target.value }, { preserveState: true })"
        class="bg-gray-900 border border-gray-800 rounded-xl px-4 py-2 text-sm text-white"
      >
        <option value="">All Statuses</option>
        <option value="new">New</option>
        <option value="read">Read</option>
        <option value="replied">Replied</option>
        <option value="archived">Archived</option>
      </select>
      <input
        :value="filters.search"
        @input="e => router.get('/admin/contacts', { ...filters, search: e.target.value }, { preserveState: true })"
        type="text"
        placeholder="Search by name or email..."
        class="bg-gray-900 border border-gray-800 rounded-xl px-4 py-2 text-sm text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-amber-500/30 flex-1 max-w-xs"
      />
    </div>

    <!-- Table -->
    <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-wider">
            <th class="text-left px-6 py-4 font-medium">From</th>
            <th class="text-left px-6 py-4 font-medium hidden md:table-cell">Property</th>
            <th class="text-left px-6 py-4 font-medium hidden lg:table-cell">Message</th>
            <th class="text-left px-6 py-4 font-medium">Status</th>
            <th class="text-left px-6 py-4 font-medium hidden sm:table-cell">Date</th>
            <th class="text-right px-6 py-4 font-medium">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
          <tr v-for="contact in contacts.data" :key="contact.id"
              :class="['hover:bg-gray-800/50 transition-colors', contact.status === 'new' ? 'bg-amber-500/5' : '']">
            <td class="px-6 py-4">
              <p class="font-medium text-white">{{ contact.name }}</p>
              <p class="text-xs text-gray-500">{{ contact.email }}</p>
            </td>
            <td class="px-6 py-4 hidden md:table-cell">
              <span v-if="contact.property" class="text-xs text-amber-400 truncate max-w-[150px] block">
                {{ contact.property.translation?.title }}
              </span>
              <span v-else class="text-xs text-gray-600">General</span>
            </td>
            <td class="px-6 py-4 hidden lg:table-cell">
              <p class="text-xs text-gray-400 truncate max-w-[200px]">{{ contact.message }}</p>
            </td>
            <td class="px-6 py-4">
              <span :class="['text-xs font-medium px-2.5 py-1 rounded-full capitalize', statusColors[contact.status]]">
                {{ contact.status }}
              </span>
            </td>
            <td class="px-6 py-4 hidden sm:table-cell text-xs text-gray-500">
              {{ new Date(contact.created_at).toLocaleDateString() }}
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-1">
                <Link :href="route('admin.contacts.show', contact.id)"
                  class="p-2 rounded-lg text-gray-500 hover:text-blue-400 hover:bg-blue-500/10 transition-all" title="View">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                </Link>
                <button v-if="contact.status !== 'replied'"
                  @click="markReplied(contact)"
                  class="p-2 rounded-lg text-gray-500 hover:text-emerald-400 hover:bg-emerald-500/10 transition-all" title="Mark Replied">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                  </svg>
                </button>
                <button @click="destroy(contact)"
                  class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-all" title="Delete">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="!contacts.data.length">
            <td colspan="6" class="text-center py-16 text-gray-600 text-sm">No inquiries yet.</td>
          </tr>
        </tbody>
      </table>

      <div v-if="contacts.last_page > 1" class="flex items-center justify-between px-6 py-4 border-t border-gray-800">
        <p class="text-xs text-gray-500">Page {{ contacts.current_page }} of {{ contacts.last_page }}</p>
        <div class="flex gap-2">
          <a v-if="contacts.prev_page_url" :href="contacts.prev_page_url"
            class="px-4 py-2 text-xs rounded-lg border border-gray-700 text-gray-400 hover:border-amber-500 hover:text-amber-400 transition-all">← Prev</a>
          <a v-if="contacts.next_page_url" :href="contacts.next_page_url"
            class="px-4 py-2 text-xs rounded-lg border border-gray-700 text-gray-400 hover:border-amber-500 hover:text-amber-400 transition-all">Next →</a>
        </div>
      </div>
    </div>
  </div>
</template>
