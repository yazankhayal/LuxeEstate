<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page     = usePage()
const settings = computed(() => page.props.settings)
const year     = new Date().getFullYear()

const quickLinks = [
  { label: 'Properties', href: '/properties' },
  { label: 'Services',   href: '/services' },
  { label: 'Blog',       href: '/blog' },
  { label: 'About Us',   href: '/about' },
  { label: 'Contact',    href: '/contact' },
]

const legal = [
  { label: 'Privacy Policy', href: '/privacy-policy' },
  { label: 'Terms & Conditions', href: '/terms' },
]

const socials = [
  { key: 'facebook',  icon: 'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z' },
  { key: 'instagram', icon: 'M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z M4 6a2 2 0 100-4 2 2 0 000 4z' },
  { key: 'twitter',   icon: 'M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z' },
  { key: 'linkedin',  icon: 'M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z M4 6a2 2 0 100-4 2 2 0 000 4z' },
]
</script>

<template>
  <footer class="bg-gray-950 text-gray-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-12">

        <!-- Brand -->
        <div class="md:col-span-2">
          <h2 class="text-white font-serif text-2xl font-bold mb-4">
            {{ settings?.site_name || 'LuxeEstate' }}
          </h2>
          <p class="text-sm leading-relaxed mb-6 max-w-xs">
            {{ settings?.site_description || 'Your trusted partner in premium real estate.' }}
          </p>

          <!-- Contact info -->
          <div class="space-y-2 text-sm">
            <p v-if="settings?.phone" class="flex items-center gap-2">
              <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
              {{ settings.phone }}
            </p>
            <p v-if="settings?.email" class="flex items-center gap-2">
              <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              {{ settings.email }}
            </p>
            <p v-if="settings?.address" class="flex items-start gap-2">
              <svg class="w-4 h-4 text-amber-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              {{ settings.address }}
            </p>
          </div>

          <!-- Social links -->
          <div class="flex gap-3 mt-6">
            <template v-for="social in socials" :key="social.key">
              <a
                v-if="settings?.[social.key]"
                :href="settings[social.key]"
                target="_blank"
                class="w-9 h-9 rounded-full bg-gray-800 hover:bg-amber-500 flex items-center justify-center transition-colors"
              >
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="social.icon"/>
                </svg>
              </a>
            </template>
          </div>
        </div>

        <!-- Quick links -->
        <div>
          <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Quick Links</h4>
          <ul class="space-y-3">
            <li v-for="link in quickLinks" :key="link.href">
              <Link :href="link.href" class="text-sm hover:text-amber-400 transition-colors">
                {{ link.label }}
              </Link>
            </li>
          </ul>
        </div>

        <!-- Legal -->
        <div>
          <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Legal</h4>
          <ul class="space-y-3">
            <li v-for="link in legal" :key="link.href">
              <Link :href="link.href" class="text-sm hover:text-amber-400 transition-colors">
                {{ link.label }}
              </Link>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Bottom bar -->
    <div class="border-t border-gray-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
        <p class="text-xs">
          © {{ year }} {{ settings?.site_name || 'LuxeEstate' }}. All rights reserved.
        </p>
        <Link href="/admin" class="text-xs hover:text-amber-400 transition-colors">Admin Dashboard</Link>
      </div>
    </div>
  </footer>
</template>
