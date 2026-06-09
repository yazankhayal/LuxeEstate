<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineOptions({ layout: PublicLayout })

const props = defineProps({
  post:    Object,
  related: Array,
})

const page   = usePage()
const locale = computed(() => page.props.locale)

const translation = computed(() =>
  props.post.translations?.find(t => t.locale === locale.value)
  ?? props.post.translations?.find(t => t.locale === 'en')
  ?? {}
)

function getTitle(item) {
  return item.translations?.find(t => t.locale === locale.value)?.title
    ?? item.translation?.title ?? ''
}
</script>

<template>
  <article class="pt-20">
    <!-- Hero -->
    <div class="relative">
      <div v-if="post.featured_image_url" class="h-80 overflow-hidden">
        <img :src="post.featured_image_url" :alt="translation.title"
          class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent" />
      </div>
      <div v-else class="h-64 bg-gradient-to-br from-gray-900 to-gray-800" />

      <div class="absolute bottom-0 left-0 right-0 px-4 pb-8">
        <div class="max-w-3xl mx-auto">
          <div class="flex items-center gap-3 mb-3">
            <Link v-if="post.category" :href="`/blog?category=${post.category.slug}`"
              class="text-xs bg-amber-500 text-white font-semibold px-3 py-1.5 rounded-full uppercase tracking-wide hover:bg-amber-600 transition-colors">
              {{ post.category.name }}
            </Link>
            <span class="text-xs text-gray-300">
              {{ post.published_at ? new Date(post.published_at).toLocaleDateString('en-US', {year:'numeric',month:'long',day:'numeric'}) : '' }}
            </span>
          </div>
          <h1 class="text-3xl sm:text-4xl font-serif font-bold text-white leading-tight">
            {{ translation.title }}
          </h1>
        </div>
      </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-12">
      <!-- Author & meta -->
      <div class="flex items-center gap-4 pb-8 mb-8 border-b border-gray-200">
        <div class="w-10 h-10 rounded-full bg-amber-500 flex items-center justify-center text-white font-bold text-sm shrink-0">
          {{ post.author?.name?.charAt(0)?.toUpperCase() }}
        </div>
        <div>
          <p class="font-semibold text-gray-900 text-sm">{{ post.author?.name }}</p>
          <p class="text-xs text-gray-400">{{ post.views_count }} views</p>
        </div>
        <!-- Tags -->
        <div v-if="post.tags?.length" class="flex gap-2 ml-auto flex-wrap justify-end">
          <Link v-for="tag in post.tags" :key="tag.id" :href="`/blog?tag=${tag.slug}`"
            class="text-xs bg-gray-100 text-gray-600 hover:bg-amber-50 hover:text-amber-600 px-3 py-1 rounded-full transition-colors">
            #{{ tag.name }}
          </Link>
        </div>
      </div>

      <!-- Excerpt -->
      <p v-if="translation.excerpt"
        class="text-lg text-gray-600 font-medium leading-relaxed mb-8 italic border-l-4 border-amber-400 pl-5">
        {{ translation.excerpt }}
      </p>

      <!-- Content -->
      <div
        class="prose prose-lg max-w-none prose-headings:font-serif prose-headings:text-gray-900 prose-a:text-amber-600 prose-img:rounded-2xl"
        v-html="translation.content"
      />

      <!-- Share -->
      <div class="border-t border-gray-200 mt-12 pt-8">
        <p class="text-sm font-semibold text-gray-700 mb-4">Share this article</p>
        <div class="flex gap-3">
          <a :href="`https://twitter.com/intent/tweet?url=${encodeURIComponent($page.props.ziggy?.location || '')}&text=${encodeURIComponent(translation.title || '')}`"
            target="_blank"
            class="flex items-center gap-2 text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-medium transition-colors">
            𝕏 Twitter
          </a>
          <a :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent($page.props.ziggy?.location || '')}`"
            target="_blank"
            class="flex items-center gap-2 text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-2.5 rounded-xl font-medium transition-colors">
            Facebook
          </a>
          <a :href="`https://www.linkedin.com/shareArticle?url=${encodeURIComponent($page.props.ziggy?.location || '')}&title=${encodeURIComponent(translation.title || '')}`"
            target="_blank"
            class="flex items-center gap-2 text-xs bg-sky-50 hover:bg-sky-100 text-sky-700 px-4 py-2.5 rounded-xl font-medium transition-colors">
            LinkedIn
          </a>
        </div>
      </div>
    </div>

    <!-- Related posts -->
    <div v-if="related?.length" class="border-t border-gray-100 py-16">
      <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-2xl font-serif font-bold text-gray-900 mb-8">Related Articles</h2>
        <div class="grid sm:grid-cols-3 gap-6">
          <Link v-for="rPost in related" :key="rPost.id"
            :href="route('blog.show', rPost.slug)"
            class="group block bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-lg transition-all">
            <div class="aspect-video overflow-hidden bg-gray-100">
              <img v-if="rPost.featured_image_url" :src="rPost.featured_image_url"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
              <div v-else class="w-full h-full bg-gradient-to-br from-amber-50 to-amber-100" />
            </div>
            <div class="p-4">
              <span class="text-xs text-amber-600 font-semibold">{{ rPost.category?.name }}</span>
              <h3 class="font-semibold text-gray-900 mt-1 text-sm leading-snug group-hover:text-amber-600 transition-colors">
                {{ getTitle(rPost) }}
              </h3>
            </div>
          </Link>
        </div>
      </div>
    </div>
  </article>
</template>
