<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  settings:  { type: Object, default: () => ({}) },
  languages: { type: Array,  default: () => [] },
})

// FIX: use ref() not $ref() — $ref is Vue Reactivity Transform (experimental, not available here)
const activeSection = ref('general')

const form = useForm({
  site_name:        props.settings?.site_name        || '',
  site_description: props.settings?.site_description || '',
  phone:            props.settings?.phone            || '',
  email:            props.settings?.email            || '',
  address:          props.settings?.address          || '',
  whatsapp:         props.settings?.whatsapp         || '',
  contact_whatsapp:         props.settings?.contact_whatsapp         || '',
  facebook:         props.settings?.facebook         || '',
  instagram:        props.settings?.instagram        || '',
  twitter:          props.settings?.twitter          || '',
  linkedin:         props.settings?.linkedin         || '',
  youtube:          props.settings?.youtube          || '',
  default_locale:   props.settings?.default_locale   || 'en',
  meta_title:       props.settings?.meta_title       || '',
  meta_description: props.settings?.meta_description || '',
  meta_keywords:    props.settings?.meta_keywords    || '',
  logo:             null,
  favicon:          null,
})

function submit() {
  form.put(route('admin.settings.update'), { forceFormData: true })
}

const sections = [
  { key: 'general', label: 'General' },
  { key: 'contact', label: 'Contact' },
  { key: 'social',  label: 'Social Media' },
  { key: 'seo',     label: 'SEO' },
  { key: 'locale',  label: 'Language' },
]
</script>

<template>
  <div>
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-white">Website Settings</h1>
      <p class="text-gray-500 text-sm mt-1">Control all global site settings from here.</p>
    </div>

    <div v-if="$page.props.flash?.success"
      class="mb-6 bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 rounded-xl px-5 py-3 text-sm font-medium">
      ✓ {{ $page.props.flash.success }}
    </div>

    <form @submit.prevent="submit">
      <div class="grid xl:grid-cols-4 gap-6">

        <!-- Section nav -->
        <div class="xl:col-span-1">
          <nav class="bg-gray-900 rounded-2xl border border-gray-800 p-2 space-y-1">
            <button
              v-for="sec in sections" :key="sec.key"
              type="button"
              @click="activeSection = sec.key"
              :class="[
                'w-full text-left px-4 py-3 rounded-xl text-sm font-medium transition-all',
                activeSection === sec.key
                  ? 'bg-amber-500/10 text-amber-400'
                  : 'text-gray-500 hover:text-gray-300 hover:bg-gray-800'
              ]"
            >
              {{ sec.label }}
            </button>
          </nav>
        </div>

        <!-- Panels -->
        <div class="xl:col-span-3 space-y-6">

          <!-- General -->
          <div v-show="activeSection === 'general'" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 space-y-5">
            <h3 class="font-semibold text-white text-lg">General Settings</h3>
            <div>
              <label class="form-label">Site Name *</label>
              <input v-model="form.site_name" type="text" class="form-input" placeholder="LuxeEstate" />
              <p v-if="form.errors.site_name" class="form-error">{{ form.errors.site_name }}</p>
            </div>
            <div>
              <label class="form-label">Site Description</label>
              <textarea v-model="form.site_description" rows="3" class="form-input" />
            </div>
            <div class="grid grid-cols-2 gap-5">
              <div>
                <label class="form-label">Logo (PNG / SVG / WebP)</label>
                <div v-if="settings?.logo" class="mb-2">
                  <img :src="$page.props.appUrl + '/storage/' + settings.logo" class="h-10 object-contain" alt="Current logo" />
                </div>
                <input type="file" accept="image/png,image/svg+xml,image/webp"
                  class="block w-full text-xs text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-amber-500/20 file:text-amber-400 hover:file:bg-amber-500/30 cursor-pointer"
                  @change="e => form.logo = e.target.files[0]" />
              </div>
              <div>
                <label class="form-label">Favicon (PNG / ICO)</label>
                <div v-if="settings?.favicon" class="mb-2">
                  <img :src="$page.props.appUrl + '/storage/' + settings.favicon" class="h-10 object-contain" alt="Current favicon" />
                </div>
                <input type="file" accept="image/png,image/x-icon,image/svg+xml"
                  class="block w-full text-xs text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-amber-500/20 file:text-amber-400 hover:file:bg-amber-500/30 cursor-pointer"
                  @change="e => form.favicon = e.target.files[0]" />
              </div>
            </div>
          </div>

          <!-- Contact -->
          <div v-show="activeSection === 'contact'" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 space-y-5">
            <h3 class="font-semibold text-white text-lg">Contact Information</h3>
            <div>
              <label class="form-label">Phone Number</label>
              <input v-model="form.phone" type="text" class="form-input" placeholder="+90 212 000 0000" />
            </div>
            <div>
              <label class="form-label">Email Address</label>
              <input v-model="form.email" type="email" class="form-input" placeholder="info@agency.com" />
              <p v-if="form.errors.email" class="form-error">{{ form.errors.email }}</p>
            </div>
            <div>
              <label class="form-label">Office Address</label>
              <textarea v-model="form.address" rows="2" class="form-input" />
            </div>
            <div>
              <label class="form-label">WhatsApp Number</label>
              <input v-model="form.whatsapp" type="text" class="form-input" placeholder=""" />
            </div>
            <div>
              <label class="form-label">Twilio WhatsApp Number</label>
              <input v-model="form.contact_whatsapp" type="text" class="form-input" placeholder="" />
            </div>
          </div>

          <!-- Social -->
          <div v-show="activeSection === 'social'" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 space-y-5">
            <h3 class="font-semibold text-white text-lg">Social Media Links</h3>
            <div v-for="key in ['facebook','instagram','twitter','linkedin','youtube']" :key="key">
              <label class="form-label capitalize">{{ key }}</label>
              <input v-model="form[key]" type="url" class="form-input" :placeholder="`https://${key}.com/yourpage`" />
            </div>
          </div>

          <!-- SEO -->
          <div v-show="activeSection === 'seo'" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 space-y-5">
            <h3 class="font-semibold text-white text-lg">SEO Settings</h3>
            <div>
              <label class="form-label">Default Meta Title</label>
              <input v-model="form.meta_title" type="text" class="form-input" maxlength="160" />
              <p class="text-xs text-gray-600 mt-1">{{ (form.meta_title || '').length }}/160</p>
            </div>
            <div>
              <label class="form-label">Default Meta Description</label>
              <textarea v-model="form.meta_description" rows="3" class="form-input" maxlength="320" />
              <p class="text-xs text-gray-600 mt-1">{{ (form.meta_description || '').length }}/320</p>
            </div>
            <div>
              <label class="form-label">Meta Keywords</label>
              <input v-model="form.meta_keywords" type="text" class="form-input" placeholder="comma, separated, keywords" />
            </div>
          </div>

          <!-- Locale -->
          <div v-show="activeSection === 'locale'" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 space-y-5">
            <h3 class="font-semibold text-white text-lg">Language Settings</h3>
            <div>
              <label class="form-label">Default Language</label>
              <select v-model="form.default_locale" class="form-input">
                <option v-for="lang in languages" :key="lang.code" :value="lang.code">
                  {{ lang.flag }} {{ lang.name }} ({{ lang.code }})
                </option>
              </select>
            </div>
            <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 text-sm text-blue-400">
              To enable/disable languages, go to
              <a href="/admin/languages" class="underline font-semibold">Language Management →</a>
            </div>
          </div>

          <!-- Save -->
          <div class="flex justify-end">
            <button type="submit" :disabled="form.processing"
              class="bg-amber-500 hover:bg-amber-600 disabled:opacity-60 text-white font-bold px-8 py-3 rounded-xl transition-all text-sm">
              {{ form.processing ? 'Saving...' : '💾 Save Settings' }}
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<style scoped>
.form-label { @apply block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5; }
.form-input { @apply w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500/50 transition; }
.form-error { @apply text-red-400 text-xs mt-1; }
</style>
