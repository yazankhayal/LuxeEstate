<script setup>
import { useForm, Link } from '@inertiajs/vue3'

defineProps({ status: String })

const form = useForm({ email: '' })

function submit() {
  form.post(route('password.email'))
}
</script>

<template>
  <div class="min-h-screen bg-gray-950 flex items-center justify-center px-4">
    <div class="w-full max-w-md">

      <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-amber-500 rounded-2xl mb-5 shadow-lg shadow-amber-500/20">
          <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
          </svg>
        </div>
        <h1 class="text-2xl font-bold text-white font-serif">Reset Password</h1>
        <p class="text-gray-500 text-sm mt-1">Enter your email to receive a reset link</p>
      </div>

      <div class="bg-gray-900 border border-gray-800 rounded-2xl p-8 shadow-2xl">

        <div v-if="status" class="mb-5 text-sm text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 rounded-xl px-4 py-3">
          {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">
          <div>
            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Email Address</label>
            <input
              v-model="form.email"
              type="email"
              required
              autofocus
              :class="[
                'w-full bg-gray-800 border rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 transition',
                form.errors.email ? 'border-red-500' : 'border-gray-700 focus:border-amber-500/50'
              ]"
              placeholder="admin@example.com"
            />
            <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-400">{{ form.errors.email }}</p>
          </div>

          <button type="submit" :disabled="form.processing"
            class="w-full bg-amber-500 hover:bg-amber-600 disabled:opacity-60 text-white font-bold py-3.5 rounded-xl transition-all text-sm">
            {{ form.processing ? 'Sending...' : 'Send Reset Link' }}
          </button>
        </form>
      </div>

      <div class="text-center mt-6">
        <Link :href="route('login')" class="text-xs text-gray-600 hover:text-gray-400 transition-colors">
          ← Back to login
        </Link>
      </div>
    </div>
  </div>
</template>
