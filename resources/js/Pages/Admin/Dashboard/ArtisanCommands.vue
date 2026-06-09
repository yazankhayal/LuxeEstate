<script setup>
import { Link } from "@inertiajs/vue3";

const lists = [
  { name: 'Clear Application Cache', command: 'cache:clear' },
  { name: 'Clear Config Cache', command: 'config:clear' },
  { name: 'Clear Route Cache', command: 'route:clear' },
  { name: 'Clear View Cache', command: 'view:clear' },
  { name: 'Clear Event Cache', command: 'event:clear' },
  { name: 'Clear Compiled Files', command: 'clear-compiled' },
  { name: 'Clear All Optimization Caches', command: 'optimize:clear' },
  { name: 'Build Config Cache', command: 'config:cache' },
  { name: 'Build Route Cache', command: 'route:cache' },
  { name: 'Build Event Cache', command: 'event:cache' },
  { name: 'Build View Cache', command: 'view:cache' },
];
</script>

<template>
  <div class="bg-gray-900 rounded-2xl border border-gray-800 mb-10">
    <div class="px-6 py-5 border-b border-gray-800">
      <h2 class="font-semibold text-white">Artisan Commands</h2>
    </div>

    <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-3">

      <div
          v-for="item in lists"
          :key="item.command"
          class="bg-gray-800 rounded-xl p-5 border border-gray-700 hover:border-amber-500/30 transition-all group"
      >

        <!-- TOP ROW: NAME + RUN ICON -->
        <div class="flex items-start justify-between gap-3">

          <h3 class="text-sm font-medium text-white">
            {{ item.name }}
          </h3>

          <!-- RUN ICON -->
          <Link
              :href="route('admin.artisan.call', { call: item.command })"
              class="w-10 h-10 rounded-lg bg-amber-500/10 flex items-center justify-center hover:bg-amber-500/20 transition-colors"
          >
            <svg
                class="w-5 h-5 text-amber-400"
                fill="none"
                stroke-width="1.5"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
            >
              <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z"
              />
            </svg>
          </Link>

        </div>

        <!-- COMMAND -->
        <code
            class="block mt-3 text-xs text-amber-400 bg-gray-900 rounded-lg px-3 py-2 font-mono break-all"
        >
          php artisan {{ item.command }}
        </code>

      </div>

    </div>
  </div>
</template>