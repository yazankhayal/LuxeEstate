<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineOptions({ layout: PublicLayout })

const props = defineProps({
  posts:      Object,
  categories: Array,
  filters:    Object,
})

const page   = usePage()
const locale = computed(() => page.props.locale)

function getTitle(post) {
  return post.translations?.find(t => t.locale === locale.value)?.title
    ?? post.translation?.title
    ?? post.slug
}
function getExcerpt(post) {
  return post.translations?.find(t => t.locale === locale.value)?.excerpt
    ?? post.translation?.excerpt
    ?? ''
}
</script>

<template>
  <!-- Header -->
  <div class="bg-gray-900 pt-28 pb-14">
    <div class="max-w-5xl mx-auto px-4">
      <p class="text-amber-400 text-sm font-semibold uppercase tracking-widest mb-2">✦ Insights</p>
      <h1 class="text-4xl font-serif font-bold text-white">Our Blog</h1>
      <p class="text-gray-400 mt-2">Expert insights, market trends, and real estate tips.</p>
    </div>
  </div>

  <div class="max-w-5xl mx-auto px-4 py-12">
    <div class="flex gap-10 items-start">

      <!-- Posts -->
      <div class="flex-1">
        <div v-if="posts.data.length" class="space-y-8">
          <article v-for="post in posts.data" :key="post.id"
            class="group flex gap-6 bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-lg transition-all">
            <div class="w-52 shrink-0 overflow-hidden bg-gray-100">
              <img v-if="post.featured_image_url" :src="post.featured_image_url"
                :alt="getTitle(post)"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
              <div v-else class="w-full h-full bg-gradient-to-br from-amber-50 to-amber-100 min-h-[160px]" />
            </div>
            <div class="flex-1 p-6">
              <div class="flex items-center gap-3 mb-3">
                <span v-if="post.category" class="text-xs text-amber-600 font-semibold uppercase tracking-wide">
                  {{ post.category.name }}
                </span>
                <span class="text-xs text-gray-400">
                  {{ post.published_at ? new Date(post.published_at).toLocaleDateString() : '' }}
                </span>
              </div>
              <h2 class="font-serif font-bold text-gray-900 text-xl mb-2 group-hover:text-amber-600 transition-colors">
                <Link :href="route('blog.show', post.slug)">{{ getTitle(post) }}</Link>
              </h2>
              <p v-if="getExcerpt(post)" class="text-gray-500 text-sm leading-relaxed line-clamp-2 mb-4">
                {{ getExcerpt(post) }}
              </p>
              <Link :href="route('blog.show', post.slug)"
                class="text-sm font-semibold text-amber-600 hover:underline">
                Read More →
              </Link>
            </div>
          </article>
        </div>

        <div v-else class="text-center py-20">
          <p class="text-gray-400">No posts found.</p>
        </div>

        <!-- Pagination -->
        <div v-if="posts.last_page > 1" class="flex justify-center gap-2 mt-12">
          <a v-if="posts.prev_page_url" :href="posts.prev_page_url"
            class="px-5 py-2.5 border border-gray-200 text-gray-700 rounded-xl text-sm hover:border-amber-300 hover:text-amber-600 transition-all">
            ← Prev
          </a>
          <span class="px-5 py-2.5 text-sm text-gray-500">
            {{ posts.current_page }} / {{ posts.last_page }}
          </span>
          <a v-if="posts.next_page_url" :href="posts.next_page_url"
            class="px-5 py-2.5 border border-gray-200 text-gray-700 rounded-xl text-sm hover:border-amber-300 hover:text-amber-600 transition-all">
            Next →
          </a>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="w-60 shrink-0 hidden lg:block space-y-6 sticky top-24">
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
          <h3 class="font-semibold text-gray-900 mb-4 text-sm uppercase tracking-wider">Categories</h3>
          <ul class="space-y-2">
            <li>
              <Link href="/blog"
                :class="['flex items-center justify-between text-sm py-2 px-3 rounded-xl transition-colors',
                  !filters.category ? 'bg-amber-50 text-amber-700 font-semibold' : 'text-gray-600 hover:bg-gray-50']">
                <span>All Posts</span>
                <span class="text-xs text-gray-400">{{ posts.total }}</span>
              </Link>
            </li>
            <li v-for="cat in categories" :key="cat.id">
              <Link :href="`/blog?category=${cat.slug}`"
                :class="['flex items-center justify-between text-sm py-2 px-3 rounded-xl transition-colors',
                  filters.category === cat.slug ? 'bg-amber-50 text-amber-700 font-semibold' : 'text-gray-600 hover:bg-gray-50']">
                <span>{{ cat.name }}</span>
                <span class="text-xs text-gray-400">{{ cat.posts_count }}</span>
              </Link>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
