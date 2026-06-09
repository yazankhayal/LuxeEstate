<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

defineProps({ open: Boolean })
defineEmits(['toggle'])

const page = usePage()
const user = computed(() => page.props.auth?.user)
const role = computed(() => user.value?.role || 'viewer')

// Role capabilities
const canEdit    = computed(() => ['admin', 'editor'].includes(role.value))
const isAdmin    = computed(() => role.value === 'admin')

// Build nav items based on role
const navItems = computed(() => {
  const items = [
    { label: 'Dashboard',   href: '/admin',             icon: 'grid',      exact: true, show: true },
    { label: 'Properties',  href: '/admin/properties',  icon: 'home',      show: true },
    { label: 'Blog',        href: '/admin/blog',         icon: 'file-text', show: true },
    { label: 'Services',    href: '/admin/services',     icon: 'briefcase', show: canEdit.value },
    { label: 'Contacts',    href: '/admin/contacts',     icon: 'mail',      show: true },
    { label: 'Users',       href: '/admin/users',        icon: 'users',     show: isAdmin.value },
    { label: 'Languages',   href: '/admin/languages',    icon: 'globe',     show: isAdmin.value },
    { label: 'Settings',    href: '/admin/settings',     icon: 'settings',  show: isAdmin.value },
  ]
  return items.filter(i => i.show)
})

function isActive(href, exact = false) {
  const current = page.url
  if (exact) return current === href || current === href + '/'
  return current.startsWith(href)
}

// Role badge color
const roleBadge = computed(() => ({
  admin:  'bg-red-500/20 text-red-400',
  editor: 'bg-blue-500/20 text-blue-400',
  viewer: 'bg-gray-500/20 text-gray-400',
}[role.value] || 'bg-gray-500/20 text-gray-400'))
</script>

<template>
  <aside :class="[
    'bg-gray-900 border-r border-gray-800 flex flex-col transition-all duration-300 shrink-0',
    open ? 'w-60' : 'w-16'
  ]">
    <!-- Logo -->
    <div class="h-16 flex items-center px-4 border-b border-gray-800 gap-3 overflow-hidden">
      <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
      </div>
      <transition name="fade">
        <span v-if="open" class="font-bold text-white text-sm tracking-wide truncate">LuxeEstate</span>
      </transition>
    </div>

    <!-- Nav -->
    <nav class="flex-1 py-4 space-y-0.5 px-2 overflow-y-auto">
      <Link
        v-for="item in navItems" :key="item.href"
        :href="item.href"
        :class="[
          'flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all text-sm font-medium',
          isActive(item.href, item.exact)
            ? 'bg-amber-500/20 text-amber-400'
            : 'text-gray-400 hover:bg-gray-800 hover:text-white'
        ]"
      >
        <NavIcon :name="item.icon" class="w-5 h-5 shrink-0" />
        <transition name="fade">
          <span v-if="open">{{ item.label }}</span>
        </transition>
      </Link>
    </nav>

    <!-- Role badge + view site -->
    <div class="p-3 border-t border-gray-800 space-y-1">
      <transition name="fade">
        <div v-if="open" class="flex items-center gap-2 px-3 py-2">
          <span :class="['text-xs font-bold px-2 py-0.5 rounded-full capitalize', roleBadge]">
            {{ role }}
          </span>
          <span class="text-xs text-gray-600 truncate">{{ user?.name }}</span>
        </div>
      </transition>
      <a href="/" target="_blank"
        :class="[
          'flex items-center gap-3 px-3 py-2 rounded-lg text-xs text-gray-500 hover:text-white hover:bg-gray-800 transition-all',
        ]">
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
        <span v-if="open">View Website</span>
      </a>
    </div>
  </aside>
</template>

<script>
import { defineComponent, h } from 'vue'

const icons = {
  grid:        'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z',
  home:        'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
  'file-text': 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
  briefcase:   'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
  mail:        'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
  users:       'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
  globe:       'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9',
  settings:    'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
}

export const NavIcon = defineComponent({
  name: 'NavIcon',
  props: { name: String, class: String },
  render() {
    return h('svg', { class: this.class, fill: 'none', viewBox: '0 0 24 24', stroke: 'currentColor' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: icons[this.name] || icons.home })
    ])
  }
})
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity .15s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
