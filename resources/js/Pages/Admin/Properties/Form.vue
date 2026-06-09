<script setup>
import { ref, computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  property:  { type: Object, default: null },
  languages: { type: Array,  default: () => [] },
})

const isEdit = computed(() => !!props.property)

// Build translations initial state
const initTranslations = () => {
  const t = {}
  for (const lang of props.languages) {
    const existing = props.property?.translations?.find(tr => tr.locale === lang.code)
    t[lang.code] = {
      title:       existing?.title       || '',
      description: existing?.description || '',
      address:     existing?.address     || '',
    }
  }
  return t
}

const form = useForm({
  slug:             props.property?.slug             || '',
  type:             props.property?.type             || 'sale',
  status:           props.property?.status           || 'active',
  price:            props.property?.price            || '',
  currency:         props.property?.currency         || 'USD',
  location:         props.property?.location         || '',
  city:             props.property?.city             || '',
  country:          props.property?.country          || '',
  area:             props.property?.area             || '',
  bedrooms:         props.property?.bedrooms         || '',
  bathrooms:        props.property?.bathrooms        || '',
  is_featured:      props.property?.is_featured      || false,
  whatsapp:         props.property?.whatsapp         || '',
  phone:            props.property?.phone            || '',
  video_url:        props.property?.video_url        || '',
  meta_title:       props.property?.meta_title       || '',
  meta_description: props.property?.meta_description || '',
  meta_keywords:    props.property?.meta_keywords    || '',
  translations:     initTranslations(),
  images:           [],
})

const activeLocale = ref(props.languages[0]?.code || 'en')
const imagePreview = ref([])

function handleImages(e) {
  form.images = Array.from(e.target.files)
  imagePreview.value = form.images.map(f => URL.createObjectURL(f))
}

function submit() {
  if (isEdit.value) {
    form.put(route('admin.properties.update', props.property.id), {
      forceFormData: true,
    })
  } else {
    form.post(route('admin.properties.store'), {
      forceFormData: true,
    })
  }
}

// Auto-generate slug from EN title
function generateSlug() {
  const title = form.translations['en']?.title || ''
  form.slug = title
    .toLowerCase()
    .replace(/[^a-z0-9\s-]/g, '')
    .trim()
    .replace(/\s+/g, '-')
}

const currencies = ['USD', 'EUR', 'GBP', 'TRY', 'AED', 'SAR']
</script>

<template>
  <div>
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
      <Link href="/admin/properties" class="p-2 rounded-xl bg-gray-900 border border-gray-800 text-gray-400 hover:text-white transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </Link>
      <div>
        <h1 class="text-2xl font-bold text-white">{{ isEdit ? 'Edit Property' : 'Add New Property' }}</h1>
        <p class="text-gray-500 text-sm mt-0.5">{{ isEdit ? `Editing: ${property?.slug}` : 'Fill in the details below' }}</p>
      </div>
    </div>

    <form @submit.prevent="submit" class="grid xl:grid-cols-3 gap-6">

      <!-- Main content -->
      <div class="xl:col-span-2 space-y-6">

        <!-- Translations -->
        <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-x-auto">
          <div class="flex border-b border-gray-800">
            <button
              v-for="lang in languages"
              :key="lang.code"
              type="button"
              @click="activeLocale = lang.code"
              :class="[
                'px-5 py-3.5 text-sm font-medium transition-all',
                activeLocale === lang.code
                  ? 'bg-amber-500/10 text-amber-400 border-b-2 border-amber-500'
                  : 'text-gray-500 hover:text-gray-300'
              ]"
            >
              {{ lang.flag }} {{ lang.name }}
              <span v-if="lang.code === 'en'" class="text-red-400 ml-1">*</span>
            </button>
          </div>

          <div class="p-6 space-y-4">
            <div>
              <label class="form-label">Title <span v-if="activeLocale === 'en'" class="text-red-400">*</span></label>
              <input
                v-model="form.translations[activeLocale].title"
                type="text"
                class="form-input"
                :placeholder="`Property title in ${activeLocale.toUpperCase()}`"
                @blur="activeLocale === 'en' && !form.slug && generateSlug()"
              />
              <p v-if="form.errors[`translations.${activeLocale}.title`]" class="form-error">
                {{ form.errors[`translations.${activeLocale}.title`] }}
              </p>
            </div>

            <div>
              <label class="form-label">Description <span v-if="activeLocale === 'en'" class="text-red-400">*</span></label>
              <textarea
                v-model="form.translations[activeLocale].description"
                rows="5"
                class="form-input"
                :placeholder="`Full description in ${activeLocale.toUpperCase()}`"
              />
            </div>

            <div>
              <label class="form-label">Address</label>
              <input
                v-model="form.translations[activeLocale].address"
                type="text"
                class="form-input"
                :placeholder="`Full address in ${activeLocale.toUpperCase()}`"
              />
            </div>
          </div>
        </div>

        <!-- Images -->
        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6">
          <h3 class="font-semibold text-white mb-4">Property Images</h3>

          <!-- Existing images -->
          <div v-if="property?.images?.length" class="grid grid-cols-4 gap-3 mb-4">
            <div
              v-for="img in property.images"
              :key="img.id"
              class="relative group aspect-square rounded-xl overflow-hidden bg-gray-800"
            >
              <img :src="img.url" class="w-full h-full object-cover" />
              <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <Link
                  :href="route('admin.properties.images.destroy', img.id)"
                  method="delete"
                  as="button"
                  class="text-white text-xs bg-red-500 px-2 py-1 rounded-lg"
                >
                  Delete
                </Link>
              </div>
              <span v-if="img.is_cover" class="absolute top-1 left-1 bg-amber-500 text-white text-xs px-1.5 py-0.5 rounded font-medium">
                Cover
              </span>
            </div>
          </div>

          <!-- Upload input -->
          <label class="flex flex-col items-center justify-center border-2 border-dashed border-gray-700 rounded-xl p-8 cursor-pointer hover:border-amber-500/50 transition-colors">
            <svg class="w-10 h-10 text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-sm text-gray-400 font-medium">Click to upload images</p>
            <p class="text-xs text-gray-600 mt-1">JPG, PNG, WebP — max 5MB each</p>
            <input type="file" multiple accept="image/*" class="hidden" @change="handleImages" />
          </label>

          <!-- Previews -->
          <div v-if="imagePreview.length" class="grid grid-cols-4 gap-3 mt-4">
            <div v-for="(src, i) in imagePreview" :key="i" class="aspect-square rounded-xl overflow-hidden bg-gray-800">
              <img :src="src" class="w-full h-full object-cover" />
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">

        <!-- Basic info -->
        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6 space-y-4">
          <h3 class="font-semibold text-white">Property Details</h3>

          <div>
            <label class="form-label">Slug <span class="text-red-400">*</span></label>
            <div class="flex gap-2">
              <input v-model="form.slug" type="text" class="form-input flex-1" placeholder="my-property-name" />
              <button type="button" @click="generateSlug" class="px-3 py-2 bg-gray-800 text-gray-400 rounded-xl text-xs hover:text-white transition-colors">
                Auto
              </button>
            </div>
            <p v-if="form.errors.slug" class="form-error">{{ form.errors.slug }}</p>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="form-label">Type</label>
              <select v-model="form.type" class="form-input">
                <option value="sale">For Sale</option>
                <option value="rent">For Rent</option>
              </select>
            </div>
            <div>
              <label class="form-label">Status</label>
              <select v-model="form.status" class="form-input">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="sold">Sold</option>
                <option value="rented">Rented</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="form-label">Price <span class="text-red-400">*</span></label>
              <input v-model="form.price" type="number" class="form-input" placeholder="0" />
            </div>
            <div>
              <label class="form-label">Currency</label>
              <select v-model="form.currency" class="form-input">
                <option v-for="c in currencies" :key="c" :value="c">{{ c }}</option>
              </select>
            </div>
          </div>

          <div>
            <label class="form-label">Area (m²) <span class="text-red-400">*</span></label>
            <input v-model="form.area" type="number" class="form-input" placeholder="0" />
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="form-label">Bedrooms</label>
              <input v-model="form.bedrooms" type="number" min="0" class="form-input" />
            </div>
            <div>
              <label class="form-label">Bathrooms</label>
              <input v-model="form.bathrooms" type="number" min="0" class="form-input" />
            </div>
          </div>

          <div>
            <label class="flex items-center gap-3 cursor-pointer">
              <input type="checkbox" v-model="form.is_featured" class="w-4 h-4 rounded accent-amber-500" />
              <span class="text-sm text-gray-300 font-medium">Mark as Featured</span>
            </label>
          </div>
        </div>

        <!-- Location -->
        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6 space-y-4">
          <h3 class="font-semibold text-white">Location</h3>
          <div>
            <label class="form-label">City <span class="text-red-400">*</span></label>
            <input v-model="form.city" type="text" class="form-input" placeholder="Istanbul" />
          </div>
          <div>
            <label class="form-label">Country <span class="text-red-400">*</span></label>
            <input v-model="form.country" type="text" class="form-input" placeholder="Turkey" />
          </div>
          <div>
            <label class="form-label">Location / Neighborhood</label>
            <input v-model="form.location" type="text" class="form-input" placeholder="Beşiktaş" />
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="form-label">Latitude</label>
              <input v-model="form.latitude" type="number" step="any" class="form-input" placeholder="41.0082" />
            </div>
            <div>
              <label class="form-label">Longitude</label>
              <input v-model="form.longitude" type="number" step="any" class="form-input" placeholder="28.9784" />
            </div>
          </div>
        </div>

        <!-- Contact -->
        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6 space-y-4">
          <h3 class="font-semibold text-white">Contact</h3>
          <div>
            <label class="form-label">Phone</label>
            <input v-model="form.phone" type="text" class="form-input" placeholder="+90 212 000 0000" />
          </div>
          <div>
            <label class="form-label">WhatsApp</label>
            <input v-model="form.whatsapp" type="text" class="form-input" placeholder="+90 532 000 0000" />
          </div>
          <div>
            <label class="form-label">Video URL</label>
            <input v-model="form.video_url" type="url" class="form-input" placeholder="https://youtube.com/..." />
          </div>
        </div>

        <!-- SEO -->
        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6 space-y-4">
          <h3 class="font-semibold text-white">SEO</h3>
          <div>
            <label class="form-label">Meta Title</label>
            <input v-model="form.meta_title" type="text" class="form-input" placeholder="SEO title (max 160 chars)" maxlength="160" />
          </div>
          <div>
            <label class="form-label">Meta Description</label>
            <textarea v-model="form.meta_description" rows="3" class="form-input" placeholder="Meta description (max 320 chars)" maxlength="320" />
          </div>
          <div>
            <label class="form-label">Keywords</label>
            <input v-model="form.meta_keywords" type="text" class="form-input" placeholder="comma, separated, keywords" />
          </div>
        </div>

        <!-- Submit -->
        <button
          type="submit"
          :disabled="form.processing"
          class="w-full bg-amber-500 hover:bg-amber-600 disabled:opacity-60 text-white font-bold py-3.5 rounded-xl transition-all text-sm"
        >
          {{ form.processing ? 'Saving...' : (isEdit ? 'Update Property' : 'Create Property') }}
        </button>

        <p v-if="Object.keys(form.errors).length" class="text-red-400 text-xs text-center">
          Please fix the errors above before submitting.
        </p>
      </div>
    </form>
  </div>
</template>

<style>
.form-label  { @apply block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5; }
.form-input  { @apply w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500/50 transition; }
.form-error  { @apply text-red-400 text-xs mt-1; }
</style>
