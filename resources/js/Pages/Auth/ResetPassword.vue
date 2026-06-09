<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  email: String,
  token: String,
})

const form = useForm({
  token:                 props.token,
  email:                 props.email,
  password:              '',
  password_confirmation: '',
})

function submit() {
  form.post(route('password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <div class="min-h-screen bg-gray-950 flex items-center justify-center px-4">
    <div class="w-full max-w-md">

      <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-amber-500 rounded-2xl mb-5 shadow-lg shadow-amber-500/20">
          <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
        </div>
        <h1 class="text-2xl font-bold text-white font-serif">Set New Password</h1>
        <p class="text-gray-500 text-sm mt-1">Choose a strong password for your account</p>
      </div>

      <div class="bg-gray-900 border border-gray-800 rounded-2xl p-8 shadow-2xl">
        <form @submit.prevent="submit" class="space-y-5">

          <div>
            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Email</label>
            <input v-model="form.email" type="email" required
              class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500/40 transition" />
            <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-400">{{ form.errors.email }}</p>
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">New Password</label>
            <input v-model="form.password" type="password" required
              :class="[
                'w-full bg-gray-800 border rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500/40 transition',
                form.errors.password ? 'border-red-500' : 'border-gray-700 focus:border-amber-500/50'
              ]"
              placeholder="Minimum 8 characters" />
            <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-400">{{ form.errors.password }}</p>
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Confirm Password</label>
            <input v-model="form.password_confirmation" type="password" required
              class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500/40 transition"
              placeholder="Repeat password" />
          </div>

          <button type="submit" :disabled="form.processing"
            class="w-full bg-amber-500 hover:bg-amber-600 disabled:opacity-60 text-white font-bold py-3.5 rounded-xl transition-all text-sm mt-2">
            {{ form.processing ? 'Saving...' : 'Reset Password' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>
