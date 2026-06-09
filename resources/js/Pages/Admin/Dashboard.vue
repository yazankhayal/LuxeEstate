<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import ArtisanCommands from "./Dashboard/ArtisanCommands.vue";

defineOptions({ layout: AdminLayout })

const props = defineProps({
  stats:               Object,
  recentContacts:      Array,
  featuredProperties:  Array,
})

const statCards = computed(() => [
  {
    label:   'Total Properties',
    value:   props.stats?.properties || 0,
    sub:     `${props.stats?.active_properties || 0} active`,
    color:   'bg-blue-500',
    icon:    'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
    href:    '/admin/properties',
  },
  {
    label:   'Blog Posts',
    value:   props.stats?.posts || 0,
    sub:     'Published articles',
    color:   'bg-violet-500',
    icon:    'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    href:    '/admin/blog',
  },
  {
    label:   'New Inquiries',
    value:   props.stats?.contacts?.new || 0,
    sub:     `${props.stats?.contacts?.total || 0} total`,
    color:   'bg-amber-500',
    icon:    'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    href:    '/admin/contacts',
  },
  {
    label:   'Today\'s Contacts',
    value:   props.stats?.contacts?.today || 0,
    sub:     'Received today',
    color:   'bg-emerald-500',
    icon:    'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    href:    '/admin/contacts',
  },
])

const statusBadge = {
  new:      'bg-amber-500/20 text-amber-400',
  read:     'bg-blue-500/20 text-blue-400',
  replied:  'bg-emerald-500/20 text-emerald-400',
  archived: 'bg-gray-500/20 text-gray-400',
}
</script>

<template>
  <div>
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-white">Dashboard</h1>
      <p class="text-gray-500 text-sm mt-1">Welcome back! Here's what's happening.</p>
    </div>

    <!-- Stat cards -->
    <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
      <Link
        v-for="card in statCards"
        :key="card.label"
        :href="card.href"
        class="bg-gray-900 rounded-2xl p-6 border border-gray-800 hover:border-gray-700 transition-all group"
      >
        <div class="flex items-start justify-between mb-4">
          <div :class="['w-10 h-10 rounded-xl flex items-center justify-center', card.color + '/20']">
            <svg :class="['w-5 h-5', card.color.replace('bg-', 'text-')]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="card.icon"/>
            </svg>
          </div>
          <svg class="w-4 h-4 text-gray-600 group-hover:text-gray-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </div>
        <p class="text-3xl font-bold text-white mb-1">{{ card.value }}</p>
        <p class="text-sm font-medium text-gray-300">{{ card.label }}</p>
        <p class="text-xs text-gray-500 mt-0.5">{{ card.sub }}</p>
      </Link>
    </div>

    <!-- Artisan Commands -->
    <ArtisanCommands></ArtisanCommands>

    <!-- Two-column layout -->
    <div class="grid lg:grid-cols-2 gap-6">

      <!-- Recent contacts -->
      <div class="bg-gray-900 rounded-2xl border border-gray-800">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-800">
          <h2 class="font-semibold text-white">Recent Inquiries</h2>
          <Link href="/admin/contacts" class="text-xs text-amber-400 hover:underline">View All</Link>
        </div>
        <div class="divide-y divide-gray-800">
          <div
            v-for="contact in recentContacts"
            :key="contact.id"
            class="px-6 py-4 flex items-start gap-4"
          >
            <div class="w-9 h-9 rounded-full bg-gray-800 flex items-center justify-center text-sm font-bold text-amber-400 shrink-0">
              {{ contact.name.charAt(0).toUpperCase() }}
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between gap-2">
                <p class="text-sm font-medium text-white truncate">{{ contact.name }}</p>
                <span :class="['text-xs font-medium px-2.5 py-0.5 rounded-full capitalize', statusBadge[contact.status]]">
                  {{ contact.status }}
                </span>
              </div>
              <p class="text-xs text-gray-500 truncate mt-0.5">{{ contact.email }}</p>
              <p v-if="contact.property" class="text-xs text-amber-500/70 mt-1 truncate">
                Re: {{ contact.property.translation?.title }}
              </p>
            </div>
          </div>
          <div v-if="!recentContacts?.length" class="px-6 py-8 text-center text-gray-600 text-sm">
            No inquiries yet.
          </div>
        </div>
      </div>

      <!-- Quick actions -->
      <div class="bg-gray-900 rounded-2xl border border-gray-800">
        <div class="px-6 py-5 border-b border-gray-800">
          <h2 class="font-semibold text-white">Quick Actions</h2>
        </div>
        <div class="p-6 grid grid-cols-2 gap-3">
          <Link
            v-for="action in [
              { label: 'New Property', href: '/admin/properties/create', icon: 'M12 4v16m8-8H4' },
              { label: 'New Post',     href: '/admin/blog/create',       icon: 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' },
              { label: 'New User',     href: '/admin/users/create',      icon: 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z' },
              { label: 'Settings',     href: '/admin/settings',          icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z' },
            ]"
            :key="action.label"
            :href="action.href"
            class="flex flex-col items-center gap-3 bg-gray-800 hover:bg-gray-750 rounded-xl p-5 text-center transition-all hover:border-amber-500/30 border border-transparent group"
          >
            <div class="w-10 h-10 rounded-lg bg-amber-500/10 flex items-center justify-center group-hover:bg-amber-500/20 transition-colors">
              <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="action.icon"/>
              </svg>
            </div>
            <span class="text-xs font-medium text-gray-300 group-hover:text-white transition-colors">
              {{ action.label }}
            </span>
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>
