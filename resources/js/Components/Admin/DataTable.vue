<script setup>
defineProps({
  headers: { type: Array,  default: () => [] }, // [{ label, key, class }]
  rows:    { type: Array,  default: () => [] },
  empty:   { type: String, default: 'No data found.' },
  pagination: { type: Object, default: null },
})
</script>

<template>
  <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-hidden">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-wider">
          <th v-for="col in headers" :key="col.key"
            :class="['px-6 py-4 font-medium text-left', col.class || '']">
            {{ col.label }}
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-800">
        <slot name="rows" />
        <tr v-if="!rows?.length && !$slots.rows">
          <td :colspan="headers.length"
            class="text-center py-16 text-gray-600 text-sm">
            {{ empty }}
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination slot -->
    <div v-if="pagination && pagination.last_page > 1"
      class="flex items-center justify-between px-6 py-4 border-t border-gray-800">
      <p class="text-xs text-gray-500">
        Showing {{ pagination.from }}–{{ pagination.to }} of {{ pagination.total }}
      </p>
      <div class="flex gap-2">
        <a v-if="pagination.prev_page_url" :href="pagination.prev_page_url"
          class="px-4 py-2 text-xs rounded-lg border border-gray-700 text-gray-400 hover:border-amber-500 hover:text-amber-400 transition-all">
          ← Prev
        </a>
        <a v-if="pagination.next_page_url" :href="pagination.next_page_url"
          class="px-4 py-2 text-xs rounded-lg border border-gray-700 text-gray-400 hover:border-amber-500 hover:text-amber-400 transition-all">
          Next →
        </a>
      </div>
    </div>
  </div>
</template>
