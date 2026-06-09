<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  users: Object,
})

const page    = usePage()
const isAdmin = page.props.auth?.user?.role === 'admin'

function destroy(user) {
  if (user.id === page.props.auth?.user?.id) {
    return alert('You cannot delete your own account.')
  }
  if (confirm(`Delete user "${user.name}"? This cannot be undone.`)) {
    router.delete(route('admin.users.destroy', user.id), { preserveScroll: true })
  }
}

const roleColors = {
  admin:  'bg-red-500/15 text-red-400 border border-red-500/20',
  editor: 'bg-blue-500/15 text-blue-400 border border-blue-500/20',
  viewer: 'bg-gray-500/15 text-gray-400 border border-gray-500/20',
}

const roleDescriptions = {
  admin:  'Full access — can manage everything including users and settings',
  editor: 'Can create and edit properties, blog posts, and services',
  viewer: 'Read-only access — can view dashboard and contacts',
}
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-white">Admin Users</h1>
        <p class="text-gray-500 text-sm mt-1">{{ users.total }} users — only admins can manage users</p>
      </div>
      <Link href="/admin/users/create"
        class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
        Add User
      </Link>
    </div>

    <!-- Role legend -->
    <div class="grid sm:grid-cols-3 gap-3 mb-6">
      <div v-for="(desc, role) in roleDescriptions" :key="role"
        class="bg-gray-900 border border-gray-800 rounded-xl p-4">
        <span :class="['text-xs font-bold px-2.5 py-1 rounded-full capitalize inline-block mb-2', roleColors[role]]">{{ role }}</span>
        <p class="text-xs text-gray-500 leading-relaxed">{{ desc }}</p>
      </div>
    </div>

    <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-800 text-gray-500 text-xs uppercase tracking-wider">
            <th class="text-left px-6 py-4 font-medium">User</th>
            <th class="text-left px-6 py-4 font-medium">Role</th>
            <th class="text-left px-6 py-4 font-medium hidden sm:table-cell">Joined</th>
            <th class="text-right px-6 py-4 font-medium">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
          <tr v-for="user in users.data" :key="user.id"
            :class="['transition-colors hover:bg-gray-800/50', user.id === page.props.auth?.user?.id ? 'bg-amber-500/5' : '']">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-amber-500 flex items-center justify-center text-sm font-bold text-white shrink-0">
                  {{ user.name?.charAt(0)?.toUpperCase() }}
                </div>
                <div>
                  <p class="font-medium text-white flex items-center gap-2">
                    {{ user.name }}
                    <span v-if="user.id === page.props.auth?.user?.id"
                      class="text-xs bg-amber-500/15 text-amber-400 px-1.5 py-0.5 rounded font-medium">You</span>
                  </p>
                  <p class="text-xs text-gray-500">{{ user.email }}</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <span :class="['text-xs font-bold px-2.5 py-1 rounded-full capitalize', roleColors[user.role]]">
                {{ user.role }}
              </span>
            </td>
            <td class="px-6 py-4 hidden sm:table-cell text-xs text-gray-500">
              {{ new Date(user.created_at).toLocaleDateString() }}
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-1">
                <!-- Edit -->
                <Link :href="route('admin.users.edit', user.id)"
                  class="p-2 rounded-lg text-gray-500 hover:text-amber-400 hover:bg-amber-500/10 transition-all"
                  title="Edit user">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </Link>
                <!-- Delete -->
                <button @click="destroy(user)"
                  :disabled="user.id === page.props.auth?.user?.id"
                  :class="[
                    'p-2 rounded-lg transition-all',
                    user.id === page.props.auth?.user?.id
                      ? 'text-gray-700 cursor-not-allowed'
                      : 'text-gray-500 hover:text-red-400 hover:bg-red-500/10'
                  ]"
                  title="Delete user">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="!users.data?.length">
            <td colspan="4" class="text-center py-16 text-gray-600 text-sm">No users found.</td>
          </tr>
        </tbody>
      </table>

      <div v-if="users.last_page > 1" class="flex items-center justify-between px-6 py-4 border-t border-gray-800">
        <p class="text-xs text-gray-500">Page {{ users.current_page }} / {{ users.last_page }}</p>
        <div class="flex gap-2">
          <a v-if="users.prev_page_url" :href="users.prev_page_url"
            class="px-4 py-2 text-xs rounded-lg border border-gray-700 text-gray-400 hover:border-amber-500 hover:text-amber-400 transition-all">← Prev</a>
          <a v-if="users.next_page_url" :href="users.next_page_url"
            class="px-4 py-2 text-xs rounded-lg border border-gray-700 text-gray-400 hover:border-amber-500 hover:text-amber-400 transition-all">Next →</a>
        </div>
      </div>
    </div>
  </div>
</template>
