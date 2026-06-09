<script setup>
import { ref, computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  post:       { type: Object, default: null },
  categories: { type: Array,  default: () => [] },
  tags:        { type: Array,  default: () => [] },
  languages:  { type: Array,  default: () => [] },
})

const isEdit       = computed(() => !!props.post)
const activeLocale = ref(props.languages[0]?.code || 'en')
const imagePreview = ref(props.post?.featured_image_url || null)

const initTranslations = () => {
  const t = {}
  for (const lang of props.languages) {
    const existing = props.post?.translations?.find(tr => tr.locale === lang.code)
    t[lang.code] = {
      title:   existing?.title   || '',
      excerpt: existing?.excerpt || '',
      content: existing?.content || '',
    }
  }
  return t
}

const form = useForm({
  slug:             props.post?.slug             || '',
  category_id:      props.post?.category_id      || '',
  tag_ids:          props.post?.tags?.map(t => t.id) || [],
  is_published:     props.post?.is_published     || false,
  published_at:     props.post?.published_at     || '',
  featured_image:   null,
  meta_title:       props.post?.meta_title       || '',
  meta_description: props.post?.meta_description || '',
  meta_keywords:    props.post?.meta_keywords    || '',
  translations:     initTranslations(),
})

function handleImage(e) {
  form.featured_image = e.target.files[0]
  imagePreview.value = URL.createObjectURL(e.target.files[0])
}

function generateSlug() {
  const title = form.translations['en']?.title || ''
  form.slug = title.toLowerCase()
    .replace(/[^a-z0-9\s-]/g, '')
    .trim()
    .replace(/\s+/g, '-')
}

function submit() {
  if (isEdit.value) {
    form.put(route('admin.blog.update', props.post.id), { forceFormData: true })
  } else {
    form.post(route('admin.blog.store'), { forceFormData: true })
  }
}

function toggleTag(id) {
  const idx = form.tag_ids.indexOf(id)
  if (idx === -1) form.tag_ids.push(id)
  else form.tag_ids.splice(idx, 1)
}
</script>

<template>
  <div>
    <div class="flex items-center gap-4 mb-8">
      <Link href="/admin/blog"
        class="p-2 rounded-xl bg-gray-900 border border-gray-800 text-gray-400 hover:text-white transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </Link>
      <div>
        <h1 class="text-2xl font-bold text-white">{{ isEdit ? 'Edit Post' : 'New Blog Post' }}</h1>
        <p class="text-gray-500 text-sm mt-0.5">{{ isEdit ? post?.slug : 'Fill in content below' }}</p>
      </div>
    </div>

    <form @submit.prevent="submit" class="grid xl:grid-cols-3 gap-6">

      <!-- Main content -->
      <div class="xl:col-span-2 space-y-6">

        <!-- Language tabs -->
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
              <label class="form-label">
                Title <span v-if="activeLocale === 'en'" class="text-red-400">*</span>
              </label>
              <input v-model="form.translations[activeLocale].title" type="text" class="form-input"
                :placeholder="`Post title in ${activeLocale.toUpperCase()}`"
                @blur="activeLocale === 'en' && !form.slug && generateSlug()" />
              <p v-if="form.errors[`translations.${activeLocale}.title`]" class="form-error">
                {{ form.errors[`translations.${activeLocale}.title`] }}
              </p>
            </div>

            <div>
              <label class="form-label">Excerpt (short summary)</label>
              <textarea v-model="form.translations[activeLocale].excerpt" rows="2" class="form-input"
                :placeholder="`Brief excerpt in ${activeLocale.toUpperCase()}`" />
            </div>

            <div>
              <label class="form-label">
                Content <span v-if="activeLocale === 'en'" class="text-red-400">*</span>
              </label>
              <textarea v-model="form.translations[activeLocale].content" rows="14" class="form-input font-mono text-xs leading-relaxed"
                :placeholder="`Full article content in ${activeLocale.toUpperCase()}. You can use HTML or Markdown.`" />
              <p v-if="form.errors[`translations.${activeLocale}.content`]" class="form-error">
                {{ form.errors[`translations.${activeLocale}.content`] }}
              </p>
            </div>
          </div>
        </div>

        <!-- SEO -->
        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6 space-y-4">
          <h3 class="font-semibold text-white">SEO</h3>
          <div>
            <label class="form-label">Meta Title</label>
            <input v-model="form.meta_title" type="text" class="form-input" maxlength="160" placeholder="SEO title" />
          </div>
          <div>
            <label class="form-label">Meta Description</label>
            <textarea v-model="form.meta_description" rows="2" class="form-input" maxlength="320" />
          </div>
          <div>
            <label class="form-label">Keywords</label>
            <input v-model="form.meta_keywords" type="text" class="form-input" placeholder="comma, separated" />
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">

        <!-- Publish settings -->
        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6 space-y-4">
          <h3 class="font-semibold text-white">Publish</h3>

          <div>
            <label class="form-label">Slug <span class="text-red-400">*</span></label>
            <div class="flex gap-2">
              <input v-model="form.slug" type="text" class="form-input flex-1" placeholder="post-url-slug" />
              <button type="button" @click="generateSlug"
                class="px-3 py-2 bg-gray-800 text-gray-400 rounded-xl text-xs hover:text-white transition-colors">
                Auto
              </button>
            </div>
            <p v-if="form.errors.slug" class="form-error">{{ form.errors.slug }}</p>
          </div>

          <div>
            <label class="flex items-center gap-3 cursor-pointer">
              <input type="checkbox" v-model="form.is_published" class="w-4 h-4 rounded accent-amber-500" />
              <span class="text-sm text-gray-300 font-medium">Published</span>
            </label>
          </div>

          <div v-if="form.is_published">
            <label class="form-label">Publish Date</label>
            <input v-model="form.published_at" type="datetime-local" class="form-input" />
          </div>
        </div>

        <!-- Featured Image -->
        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6 space-y-3">
          <h3 class="font-semibold text-white">Featured Image</h3>
          <div v-if="imagePreview" class="rounded-xl overflow-hidden aspect-video bg-gray-800">
            <img :src="imagePreview" class="w-full h-full object-cover" />
          </div>
          <label class="flex flex-col items-center justify-center border-2 border-dashed border-gray-700 rounded-xl p-6 cursor-pointer hover:border-amber-500/50 transition-colors">
            <svg class="w-8 h-8 text-gray-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01"/>
            </svg>
            <p class="text-xs text-gray-500">Click to upload image</p>
            <input type="file" accept="image/*" class="hidden" @change="handleImage" />
          </label>
        </div>

        <!-- Category -->
        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6 space-y-3">
          <h3 class="font-semibold text-white">Category <span class="text-red-400">*</span></h3>
          <select v-model="form.category_id" class="form-input">
            <option value="" disabled>Select a category</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
          <p v-if="form.errors.category_id" class="form-error">{{ form.errors.category_id }}</p>
        </div>

        <!-- Tags -->
        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6 space-y-3">
          <h3 class="font-semibold text-white">Tags</h3>
          <div class="flex flex-wrap gap-2">
            <button v-for="tag in tags" :key="tag.id" type="button" @click="toggleTag(tag.id)"
              :class="[
                'text-xs px-3 py-1.5 rounded-full border transition-all font-medium',
                form.tag_ids.includes(tag.id)
                  ? 'bg-amber-500/20 border-amber-500/40 text-amber-400'
                  : 'border-gray-700 text-gray-500 hover:border-gray-600 hover:text-gray-400'
              ]">
              {{ tag.name }}
            </button>
          </div>
        </div>

        <!-- Submit -->
        <button type="submit" :disabled="form.processing"
          class="w-full bg-amber-500 hover:bg-amber-600 disabled:opacity-60 text-white font-bold py-3.5 rounded-xl transition-all text-sm">
          {{ form.processing ? 'Saving...' : (isEdit ? 'Update Post' : 'Create Post') }}
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
