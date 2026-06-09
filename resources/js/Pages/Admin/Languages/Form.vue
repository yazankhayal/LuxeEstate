<script setup>
import { computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  language: { type: Object, default: null },
})

const isEdit = computed(() => !!props.language)

const form = useForm({
  code:        props.language?.code        || '',
  name:        props.language?.name        || '',
  native_name: props.language?.native_name || '',
  direction:   props.language?.direction   || 'ltr',
  flag:        props.language?.flag        || '',
  is_active:   props.language?.is_active   ?? true,
  is_default:  props.language?.is_default  ?? false,
})

function submit() {
  if (isEdit.value) {
    form.put(route('admin.languages.update', props.language.id))
  } else {
    form.post(route('admin.languages.store'))
  }
}

const presetLanguages = [
  { code: 'en', name: 'English',  native_name: 'English',    direction: 'ltr', flag: '🇬🇧' },
  { code: 'ar', name: 'Arabic',   native_name: 'العربية',    direction: 'rtl', flag: '🇸🇦' },
  { code: 'tr', name: 'Turkish',  native_name: 'Türkçe',     direction: 'ltr', flag: '🇹🇷' },
  { code: 'fr', name: 'French',   native_name: 'Français',   direction: 'ltr', flag: '🇫🇷' },
  { code: 'de', name: 'German',   native_name: 'Deutsch',    direction: 'ltr', flag: '🇩🇪' },
  { code: 'ru', name: 'Russian',  native_name: 'Русский',    direction: 'ltr', flag: '🇷🇺' },
  { code: 'zh', name: 'Chinese',  native_name: '中文',        direction: 'ltr', flag: '🇨🇳' },
  { code: 'fa', name: 'Persian',  native_name: 'فارسی',      direction: 'rtl', flag: '🇮🇷' },
]

function fillPreset(preset) {
  if (!isEdit.value) form.code = preset.code
  form.name        = preset.name
  form.native_name = preset.native_name
  form.direction   = preset.direction
  form.flag        = preset.flag
}
</script>

<template>
  <div class="max-w-xl">
    <div class="flex items-center gap-4 mb-8">
      <Link href="/admin/languages"
        class="p-2 rounded-xl bg-gray-900 border border-gray-800 text-gray-400 hover:text-white transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </Link>
      <h1 class="text-2xl font-bold text-white">{{ isEdit ? 'Edit Language' : 'Add Language' }}</h1>
    </div>

    <!-- Presets (create only) -->
    <div v-if="!isEdit" class="bg-gray-900 rounded-2xl border border-gray-800 p-5 mb-5">
      <p class="text-xs text-gray-500 uppercase tracking-wide font-medium mb-3">Quick Fill from Preset</p>
      <div class="flex flex-wrap gap-2">
        <button v-for="preset in presetLanguages" :key="preset.code"
          type="button"
          @click="fillPreset(preset)"
          class="flex items-center gap-2 px-3 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white rounded-xl text-sm transition-all">
          <span>{{ preset.flag }}</span>
          <span>{{ preset.name }}</span>
        </button>
      </div>
    </div>

    <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6">
      <form @submit.prevent="submit" class="space-y-4">
        <div v-if="!isEdit">
          <label class="form-label">Language Code *</label>
          <input v-model="form.code" type="text" class="form-input" placeholder="en" maxlength="5"
            pattern="[a-z]{2,5}" title="2-5 lowercase letters" />
          <p class="text-xs text-gray-600 mt-1">ISO 639-1 code (e.g. en, ar, tr, fr)</p>
          <p v-if="form.errors.code" class="form-error">{{ form.errors.code }}</p>
        </div>

        <div>
          <label class="form-label">Language Name (English) *</label>
          <input v-model="form.name" type="text" class="form-input" placeholder="Arabic" />
          <p v-if="form.errors.name" class="form-error">{{ form.errors.name }}</p>
        </div>

        <div>
          <label class="form-label">Native Name *</label>
          <input v-model="form.native_name" type="text" class="form-input" placeholder="العربية" />
          <p v-if="form.errors.native_name" class="form-error">{{ form.errors.native_name }}</p>
        </div>

        <div>
          <label class="form-label">Flag Emoji</label>
          <input v-model="form.flag" type="text" class="form-input" placeholder="🇸🇦" maxlength="10" />
        </div>

        <div>
          <label class="form-label">Text Direction *</label>
          <div class="flex gap-3">
            <label :class="['flex-1 flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all',
              form.direction === 'ltr' ? 'bg-amber-500/10 border-amber-500/40 text-amber-400' : 'border-gray-700 text-gray-500 hover:border-gray-600']">
              <input type="radio" v-model="form.direction" value="ltr" class="hidden" />
              <span class="text-lg">LTR →</span>
              <span class="text-sm">Left to Right</span>
            </label>
            <label :class="['flex-1 flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all',
              form.direction === 'rtl' ? 'bg-amber-500/10 border-amber-500/40 text-amber-400' : 'border-gray-700 text-gray-500 hover:border-gray-600']">
              <input type="radio" v-model="form.direction" value="rtl" class="hidden" />
              <span class="text-lg">← RTL</span>
              <span class="text-sm">Right to Left</span>
            </label>
          </div>
        </div>

        <div class="flex gap-4">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" v-model="form.is_active" class="w-4 h-4 accent-amber-500 rounded" />
            <span class="text-sm text-gray-300 font-medium">Active</span>
          </label>
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" v-model="form.is_default" class="w-4 h-4 accent-amber-500 rounded" />
            <span class="text-sm text-gray-300 font-medium">Set as Default</span>
          </label>
        </div>

        <div class="pt-2 flex gap-3">
          <button type="submit" :disabled="form.processing"
            class="flex-1 bg-amber-500 hover:bg-amber-600 disabled:opacity-60 text-white font-bold py-3 rounded-xl transition-all text-sm">
            {{ form.processing ? 'Saving...' : (isEdit ? 'Update Language' : 'Add Language') }}
          </button>
          <Link href="/admin/languages"
            class="px-5 py-3 border border-gray-700 text-gray-400 hover:text-white rounded-xl text-sm transition-all">
            Cancel
          </Link>
        </div>
      </form>
    </div>
  </div>
</template>

<style>
.form-label { @apply block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5; }
.form-input { @apply w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500/50 transition; }
.form-error { @apply text-red-400 text-xs mt-1; }
</style>
