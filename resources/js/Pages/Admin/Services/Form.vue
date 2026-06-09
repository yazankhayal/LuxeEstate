<script setup>
import { ref, computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  service:   { type: Object, default: null },
  languages: { type: Array,  default: () => [] },
})

const isEdit       = computed(() => !!props.service)
const activeLocale = ref(props.languages[0]?.code || 'en')
const imagePreview = ref(props.service?.image_url || null)

const initTranslations = () => {
  const t = {}
  for (const lang of props.languages) {
    const ex = props.service?.translations?.find(tr => tr.locale === lang.code)
    t[lang.code] = {
      title:       ex?.title       || '',
      description: ex?.description || '',
      content:     ex?.content     || '',
    }
  }
  return t
}

const form = useForm({
  slug:         props.service?.slug      || '',
  icon:         props.service?.icon      || 'briefcase',
  order:        props.service?.order     || 0,
  is_active:    props.service?.is_active ?? true,
  image:        null,
  translations: initTranslations(),
})

function handleImage(e) {
  form.image = e.target.files[0]
  imagePreview.value = URL.createObjectURL(e.target.files[0])
}

function generateSlug() {
  const title = form.translations['en']?.title || ''
  form.slug = title.toLowerCase().replace(/[^a-z0-9\s-]/g, '').trim().replace(/\s+/g, '-')
}

function submit() {
  if (isEdit.value) {
    form.put(route('admin.services.update', props.service.id), { forceFormData: true })
  } else {
    form.post(route('admin.services.store'), { forceFormData: true })
  }
}

const iconOptions = [
  'home', 'key', 'briefcase', 'chart-bar', 'cog', 'trending-up',
  'star', 'shield', 'globe', 'map', 'building', 'calculator',
]
</script>

<template>
  <div>
    <div class="flex items-center gap-4 mb-8">
      <Link href="/admin/services"
        class="p-2 rounded-xl bg-gray-900 border border-gray-800 text-gray-400 hover:text-white transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </Link>
      <h1 class="text-2xl font-bold text-white">{{ isEdit ? 'Edit Service' : 'New Service' }}</h1>
    </div>

    <form @submit.prevent="submit" class="grid xl:grid-cols-3 gap-6">

      <!-- Content -->
      <div class="xl:col-span-2 space-y-6">
        <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-x-auto">
          <div class="flex border-b border-gray-800">
            <button v-for="lang in languages" :key="lang.code" type="button"
              @click="activeLocale = lang.code"
              :class="[
                'px-5 py-3.5 text-sm font-medium transition-all',
                activeLocale === lang.code
                  ? 'bg-amber-500/10 text-amber-400 border-b-2 border-amber-500'
                  : 'text-gray-500 hover:text-gray-300'
              ]">
              {{ lang.flag }} {{ lang.name }}
              <span v-if="lang.code === 'en'" class="text-red-400 ml-0.5">*</span>
            </button>
          </div>
          <div class="p-6 space-y-4">
            <div>
              <label class="form-label">Title <span v-if="activeLocale === 'en'" class="text-red-400">*</span></label>
              <input v-model="form.translations[activeLocale].title" type="text" class="form-input"
                @blur="activeLocale === 'en' && !form.slug && generateSlug()" />
            </div>
            <div>
              <label class="form-label">Short Description</label>
              <textarea v-model="form.translations[activeLocale].description" rows="3" class="form-input"
                placeholder="Shown on service cards" />
            </div>
            <div>
              <label class="form-label">Full Content (optional)</label>
              <textarea v-model="form.translations[activeLocale].content" rows="8" class="form-input"
                placeholder="Detailed service page content (HTML supported)" />
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6 space-y-4">
          <h3 class="font-semibold text-white">Settings</h3>

          <div>
            <label class="form-label">Slug *</label>
            <div class="flex gap-2">
              <input v-model="form.slug" type="text" class="form-input flex-1" placeholder="service-slug" />
              <button type="button" @click="generateSlug"
                class="px-3 bg-gray-800 text-gray-400 rounded-xl text-xs hover:text-white transition-colors">Auto</button>
            </div>
            <p v-if="form.errors.slug" class="form-error">{{ form.errors.slug }}</p>
          </div>

          <div>
            <label class="form-label">Icon Name</label>
            <select v-model="form.icon" class="form-input">
              <option v-for="ic in iconOptions" :key="ic" :value="ic">{{ ic }}</option>
            </select>
          </div>

          <div>
            <label class="form-label">Display Order</label>
            <input v-model.number="form.order" type="number" min="0" class="form-input" />
          </div>

          <div>
            <label class="flex items-center gap-3 cursor-pointer">
              <input type="checkbox" v-model="form.is_active" class="w-4 h-4 accent-amber-500 rounded" />
              <span class="text-sm text-gray-300 font-medium">Active (visible on site)</span>
            </label>
          </div>
        </div>

        <!-- Image -->
        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6 space-y-3">
          <h3 class="font-semibold text-white">Service Image</h3>
          <div v-if="imagePreview" class="rounded-xl overflow-hidden aspect-video bg-gray-800">
            <img :src="imagePreview" class="w-full h-full object-cover" />
          </div>
          <label class="flex flex-col items-center border-2 border-dashed border-gray-700 rounded-xl p-5 cursor-pointer hover:border-amber-500/40 transition-colors">
            <svg class="w-7 h-7 text-gray-600 mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/>
            </svg>
            <p class="text-xs text-gray-500">Upload image</p>
            <input type="file" accept="image/*" class="hidden" @change="handleImage" />
          </label>
        </div>

        <button type="submit" :disabled="form.processing"
          class="w-full bg-amber-500 hover:bg-amber-600 disabled:opacity-60 text-white font-bold py-3.5 rounded-xl transition-all text-sm">
          {{ form.processing ? 'Saving...' : (isEdit ? 'Update Service' : 'Create Service') }}
        </button>
      </div>
    </form>
  </div>
</template>

<style>
.form-label { @apply block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5; }
.form-input { @apply w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500/50 transition; }
.form-error { @apply text-red-400 text-xs mt-1; }
</style>
