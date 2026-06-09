<script setup>
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  posts:   Object,
  filters: Object,
})

function destroy(post) {
  if (confirm(`Delete "${post.translation?.title || post.slug}"?`)) {
    router.delete(route('admin.blog.destroy', post.id), { preserveScroll: true })
  }
}

function togglePublished(post) {
  router.patch(route('admin.blog.toggle-publish', post.id), {}, { preserveScroll: true })
}
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-white">Blog Posts</h1>
        <p class="text-gray-500 text-sm mt-1">{{ posts.total }} total posts</p>
      </div>
      <Link href="/admin/blog/create"
        class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Post
      </Link>
    </div>

    <!-- Search -->
    <div class="mb-5">
      <input
        :value="filters?.search"
        @input="e => router.get('/admin/blog', { search: e.target.value }, { preserveState: true, replace: true })"
        type="text"
        placeholder="Search posts..."
        class="bg-gray-900 border border-gray-800 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-amber-500/30 w-72"
      />
    </div>

    <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-wider">
            <th class="text-left px-6 py-4 font-medium">Title</th>
            <th class="text-left px-6 py-4 font-medium hidden md:table-cell">Category</th>
            <th class="text-left px-6 py-4 font-medium hidden lg:table-cell">Author</th>
            <th class="text-left px-6 py-4 font-medium">Status</th>
            <th class="text-left px-6 py-4 font-medium hidden sm:table-cell">Date</th>
            <th class="text-right px-6 py-4 font-medium">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
          <tr v-for="post in posts.data" :key="post.id"
            class="hover:bg-gray-800/50 transition-colors">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-12 h-10 rounded-lg overflow-hidden bg-gray-800 shrink-0">
                  <img v-if="post.featured_image_url" :src="post.featured_image_url"
                    class="w-full h-full object-cover" alt="" />
                  <div v-else class="w-full h-full bg-gradient-to-br from-violet-900/40 to-violet-700/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                  </div>
                </div>
                <div>
                  <p class="font-medium text-white truncate max-w-[220px]">
                    {{ post.translation?.title || post.slug }}
                  </p>
                  <p class="text-xs text-gray-500">{{ post.slug }}</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 hidden md:table-cell">
              <span class="text-xs bg-gray-800 text-gray-300 px-2.5 py-1 rounded-full">
                {{ post.category?.name || '—' }}
              </span>
            </td>
            <td class="px-6 py-4 hidden lg:table-cell text-xs text-gray-400">
              {{ post.author?.name || '—' }}
            </td>
            <td class="px-6 py-4">
              <button @click="togglePublished(post)"
                :class="[
                  'text-xs font-medium px-2.5 py-1 rounded-full transition-all',
                  post.is_published
                    ? 'bg-emerald-500/15 text-emerald-400 hover:bg-emerald-500/25'
                    : 'bg-gray-700/50 text-gray-500 hover:bg-gray-700'
                ]">
                {{ post.is_published ? '● Published' : '○ Draft' }}
              </button>
            </td>
            <td class="px-6 py-4 hidden sm:table-cell text-xs text-gray-500">
              {{ post.published_at ? new Date(post.published_at).toLocaleDateString() : '—' }}
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-1">
                <a v-if="post.is_published"
                  :href="route('blog.show', post.slug)"
                  target="_blank"
                  class="p-2 rounded-lg text-gray-500 hover:text-blue-400 hover:bg-blue-500/10 transition-all"
                  title="View live">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                  </svg>
                </a>
                <Link :href="route('admin.blog.edit', post.id)"
                  class="p-2 rounded-lg text-gray-500 hover:text-amber-400 hover:bg-amber-500/10 transition-all"
                  title="Edit">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </Link>
                <button @click="destroy(post)"
                  class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-all"
                  title="Delete">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="!posts.data?.length">
            <td colspan="6" class="text-center py-16 text-gray-600 text-sm">
              No posts yet.
              <Link href="/admin/blog/create" class="text-amber-400 hover:underline">Create one →</Link>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="posts.last_page > 1" class="flex items-center justify-between px-6 py-4 border-t border-gray-800">
        <p class="text-xs text-gray-500">Page {{ posts.current_page }} / {{ posts.last_page }}</p>
        <div class="flex gap-2">
          <a v-if="posts.prev_page_url" :href="posts.prev_page_url"
            class="px-4 py-2 text-xs rounded-lg border border-gray-700 text-gray-400 hover:border-amber-500 hover:text-amber-400 transition-all">← Prev</a>
          <a v-if="posts.next_page_url" :href="posts.next_page_url"
            class="px-4 py-2 text-xs rounded-lg border border-gray-700 text-gray-400 hover:border-amber-500 hover:text-amber-400 transition-all">Next →</a>
        </div>
      </div>
    </div>
  </div>
</template>
