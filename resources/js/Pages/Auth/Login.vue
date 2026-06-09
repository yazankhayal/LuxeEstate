<script setup>
import { useForm } from '@inertiajs/vue3'

defineProps({
  canResetPassword: Boolean,
  status:           String,
})

const form = useForm({
  email:    '',
  password: '',
  remember: false,
})

function submit() {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <div class="min-h-screen bg-gray-950 flex items-center justify-center px-4">

    <!-- Background grid -->
    <div class="absolute inset-0 opacity-5"
      style="background-image: linear-gradient(#c9a96e 1px, transparent 1px), linear-gradient(to right, #c9a96e 1px, transparent 1px); background-size: 40px 40px;">
    </div>

    <div class="relative w-full max-w-md">

      <!-- Logo / Brand -->
      <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-amber-500 rounded-2xl mb-5 shadow-lg shadow-amber-500/20">
          <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
        </div>
        <h1 class="text-2xl font-bold text-white font-serif">Admin Dashboard</h1>
        <p class="text-gray-500 text-sm mt-1">Sign in to manage your real estate platform</p>
      </div>

      <!-- Card -->
      <div class="bg-gray-900 border border-gray-800 rounded-2xl p-8 shadow-2xl">

        <!-- Status message (e.g. password reset success) -->
        <div v-if="status" class="mb-5 text-sm text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 rounded-xl px-4 py-3">
          {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">

          <!-- Email -->
          <div>
            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">
              Email Address
            </label>
            <input
              v-model="form.email"
              id="email"
              type="email"
              autocomplete="username"
              required
              autofocus
              :class="[
                'w-full bg-gray-800 border rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 transition',
                form.errors.email ? 'border-red-500' : 'border-gray-700 focus:border-amber-500/50'
              ]"
              placeholder="admin@example.com"
            />
            <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-400">
              {{ form.errors.email }}
            </p>
          </div>

          <!-- Password -->
          <div>
            <div class="flex items-center justify-between mb-2">
              <label class="block text-xs font-medium text-gray-400 uppercase tracking-wide">
                Password
              </label>
              <a v-if="canResetPassword"
                :href="route('password.request')"
                class="text-xs text-amber-400 hover:text-amber-300 transition-colors">
                Forgot password?
              </a>
            </div>
            <input
              v-model="form.password"
              id="password"
              type="password"
              autocomplete="current-password"
              required
              :class="[
                'w-full bg-gray-800 border rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 transition',
                form.errors.password ? 'border-red-500' : 'border-gray-700 focus:border-amber-500/50'
              ]"
              placeholder="••••••••"
            />
            <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-400">
              {{ form.errors.password }}
            </p>
          </div>

          <!-- Remember me -->
          <div class="flex items-center gap-3">
            <input
              id="remember"
              v-model="form.remember"
              type="checkbox"
              class="w-4 h-4 rounded accent-amber-500 bg-gray-800 border-gray-600"
            />
            <label for="remember" class="text-sm text-gray-400 cursor-pointer select-none">
              Keep me signed in
            </label>
          </div>

          <!-- Submit -->
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full bg-amber-500 hover:bg-amber-600 active:bg-amber-700 disabled:opacity-60 disabled:cursor-not-allowed text-white font-bold py-3.5 rounded-xl transition-all text-sm shadow-lg shadow-amber-500/20 mt-2"
          >
            <span v-if="form.processing" class="flex items-center justify-center gap-2">
              <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
              </svg>
              Signing in...
            </span>
            <span v-else>Sign In →</span>
          </button>
        </form>
      </div>

      <!-- Back to site -->
      <div class="text-center mt-6">
        <a href="/" class="text-xs text-gray-600 hover:text-gray-400 transition-colors">
          ← Back to website
        </a>
      </div>
    </div>
  </div>
</template>
