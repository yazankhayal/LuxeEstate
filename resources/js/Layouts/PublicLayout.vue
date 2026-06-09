<script setup>
import { computed } from 'vue'
import { usePage, Link, router } from '@inertiajs/vue3'
import Navbar from '@/Components/Public/Navbar.vue'
import Footer from '@/Components/Public/Footer.vue'

const page = usePage()
const settings = computed(() => page.props.settings)
const locale   = computed(() => page.props.locale)

// Set HTML dir for RTL support
const direction = computed(() => {
  const langs = page.props.languages || []
  const current = langs.find(l => l.code === locale.value)
  return current?.direction || 'ltr'
})
</script>

<template>
  <div :dir="direction" :lang="locale" class="min-h-screen flex flex-col bg-stone-50">
    <!-- Flash messages -->
    <transition name="fade">
      <div
        v-if="$page.props.flash?.success"
        class="fixed top-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg text-sm font-medium"
      >
        {{ $page.props.flash.success }}
      </div>
    </transition>

    <Navbar />

    <main class="flex-1">
      <slot />
    </main>

    <Footer />
  </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity .4s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
