<script setup>
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

defineProps({ scrolled: Boolean })

const page      = usePage()
const open      = ref(false)
const locale    = computed(() => page.props.locale)
const languages = computed(() => page.props.languages || [])
const current   = computed(() => languages.value.find(l => l.code === locale.value))

function switchLang(code) {
  open.value = false
  router.get(route('lang.switch', code), {}, { preserveState: true, preserveScroll: true })
}
</script>

<template>
  <div class="relative">
    <button
      @click="open = !open"
      :class="[
        'flex items-center gap-2 px-3 py-2 rounded-full text-sm font-medium transition-all border',
        scrolled
          ? 'border-gray-200 text-gray-700 hover:border-amber-300 hover:text-amber-600'
          : 'border-white/20 text-white hover:border-white/50'
      ]"
    >
      <span>{{ current?.flag }}</span>
      <span class="uppercase font-semibold text-xs">{{ locale }}</span>
      <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
      </svg>
    </button>

    <transition name="dropdown">
      <div
        v-if="open"
        class="absolute right-0 top-full mt-2 w-40 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50"
      >
        <button
          v-for="lang in languages"
          :key="lang.code"
          @click="switchLang(lang.code)"
          :class="[
            'w-full flex items-center gap-3 px-4 py-3 text-sm text-left transition-colors',
            lang.code === locale
              ? 'bg-amber-50 text-amber-700 font-semibold'
              : 'text-gray-700 hover:bg-gray-50'
          ]"
        >
          <span>{{ lang.flag }}</span>
          <span>{{ lang.native_name }}</span>
        </button>
      </div>
    </transition>
  </div>
</template>

<style scoped>
.dropdown-enter-active, .dropdown-leave-active { transition: all .2s ease; }
.dropdown-enter-from, .dropdown-leave-to { opacity: 0; transform: translateY(-6px); }
</style>
