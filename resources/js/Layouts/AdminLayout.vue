<script setup>
import { ref, computed } from 'vue'
import { usePage, Link, router } from '@inertiajs/vue3'
import AdminSidebar from '@/Components/Admin/Sidebar.vue'

const page       = usePage()
const sidebarOpen = ref(true)
const user        = computed(() => page.props.auth?.user)

function logout() {
  router.post(route('logout'))
}
</script>

<template>
  <div class="flex h-screen bg-gray-950 text-gray-100 overflow-hidden font-sans">

    <!-- Sidebar -->
    <AdminSidebar :open="sidebarOpen" @toggle="sidebarOpen = !sidebarOpen" />

    <!-- Main content -->
    <div class="flex-1 flex flex-col overflow-hidden">

      <!-- Top bar -->
      <header class="h-16 bg-gray-900 border-b border-gray-800 flex items-center justify-between px-6 shrink-0">
        <button
          @click="sidebarOpen = !sidebarOpen"
          class="text-gray-400 hover:text-white transition-colors"
        >
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>

        <div class="flex items-center gap-4">
          <!-- Flash -->
          <transition name="slide">
            <span
              v-if="$page.props.flash?.success"
              class="text-sm text-emerald-400 font-medium"
            >
              ✓ {{ $page.props.flash.success }}
            </span>
            <span
              v-else-if="$page.props.flash?.error"
              class="text-sm text-red-500 font-medium"
            >
              X {{ $page.props.flash.error }}
            </span>
          </transition>

          <!-- User menu -->
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-amber-500 flex items-center justify-center text-sm font-bold text-white">
              {{ user?.name?.charAt(0)?.toUpperCase() }}
            </div>
            <div class="hidden sm:block">
              <p class="text-sm font-medium text-white">{{ user?.name }}</p>
              <p class="text-xs text-gray-500 capitalize">{{ user?.role }}</p>
            </div>
            <button
              @click="logout"
              class="text-xs text-gray-500 hover:text-red-400 transition-colors ml-2"
            >
              Logout
            </button>
          </div>
        </div>
      </header>

      <!-- Page content -->
      <main class="flex-1 overflow-y-auto p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<style scoped>
.slide-enter-active, .slide-leave-active { transition: all .3s; }
.slide-enter-from { opacity: 0; transform: translateY(-8px); }
.slide-leave-to   { opacity: 0; }
</style>
