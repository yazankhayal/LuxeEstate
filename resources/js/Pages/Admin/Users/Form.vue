<script setup>
import { computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  user: { type: Object, default: null },
})

const isEdit = computed(() => !!props.user)

const form = useForm({
  name:                  props.user?.name     || '',
  email:                 props.user?.email    || '',
  password:              '',
  password_confirmation: '',
  role:                  props.user?.role     || 'editor',
})

function submit() {
  if (isEdit.value) {
    form.put(route('admin.users.update', props.user.id))
  } else {
    form.post(route('admin.users.store'))
  }
}
</script>

<template>
  <div class="max-w-lg">
    <div class="flex items-center gap-4 mb-8">
      <Link href="/admin/users"
        class="p-2 rounded-xl bg-gray-900 border border-gray-800 text-gray-400 hover:text-white transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </Link>
      <div>
        <h1 class="text-2xl font-bold text-white">{{ isEdit ? 'Edit User' : 'Create Admin User' }}</h1>
        <p v-if="isEdit" class="text-gray-500 text-sm mt-0.5">{{ user.email }}</p>
      </div>
    </div>

    <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6">
      <form @submit.prevent="submit" class="space-y-4">

        <div>
          <label class="form-label">Full Name *</label>
          <input v-model="form.name" type="text" class="form-input" placeholder="John Smith" required />
          <p v-if="form.errors.name" class="form-error">{{ form.errors.name }}</p>
        </div>

        <div>
          <label class="form-label">Email Address *</label>
          <input v-model="form.email" type="email" class="form-input" placeholder="user@example.com" required />
          <p v-if="form.errors.email" class="form-error">{{ form.errors.email }}</p>
        </div>

        <div>
          <label class="form-label">Role *</label>
          <select v-model="form.role" class="form-input">
            <option value="admin">Admin — Full access</option>
            <option value="editor">Editor — Can manage content</option>
            <option value="viewer">Viewer — Read-only</option>
          </select>
          <p v-if="form.errors.role" class="form-error">{{ form.errors.role }}</p>
        </div>

        <div>
          <label class="form-label">
            Password <span v-if="!isEdit" class="text-red-400">*</span>
            <span v-if="isEdit" class="normal-case font-normal text-gray-500 ml-1">(leave blank to keep current)</span>
          </label>
          <input v-model="form.password" type="password" class="form-input"
            placeholder="Minimum 8 characters"
            :required="!isEdit" />
          <p v-if="form.errors.password" class="form-error">{{ form.errors.password }}</p>
        </div>

        <div v-if="!isEdit || form.password">
          <label class="form-label">Confirm Password <span class="text-red-400">*</span></label>
          <input v-model="form.password_confirmation" type="password" class="form-input"
            placeholder="Repeat password" />
        </div>

        <div class="pt-2 flex gap-3">
          <button type="submit" :disabled="form.processing"
            class="flex-1 bg-amber-500 hover:bg-amber-600 disabled:opacity-60 text-white font-bold py-3 rounded-xl transition-all text-sm">
            {{ form.processing ? 'Saving...' : (isEdit ? 'Update User' : 'Create User') }}
          </button>
          <Link href="/admin/users"
            class="px-5 py-3 border border-gray-700 text-gray-400 hover:text-white rounded-xl text-sm transition-all">
            Cancel
          </Link>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
.form-label { @apply block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5; }
.form-input { @apply w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500/50 transition; }
.form-error { @apply text-red-400 text-xs mt-1; }
</style>
