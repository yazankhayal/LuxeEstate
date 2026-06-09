<script setup>
import { computed } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineOptions({ layout: PublicLayout })

const page     = usePage()
const settings = computed(() => page.props.settings)

const form = useForm({
  name:    '',
  email:   '',
  phone:   '',
  subject: '',
  message: '',
})

function submit() {
  form.post(route('contact.store'), { onSuccess: () => form.reset() })
}
</script>

<template>
  <!-- Hero -->
  <div class="bg-gray-900 pt-28 pb-16">
    <div class="max-w-3xl mx-auto px-4 text-center">
      <p class="text-amber-400 text-sm font-semibold uppercase tracking-widest mb-3">✦ Get in Touch</p>
      <h1 class="text-4xl font-serif font-bold text-white mb-4">Contact Us</h1>
      <p class="text-gray-400">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
    </div>
  </div>

  <div class="max-w-6xl mx-auto px-4 py-16">
    <div class="grid lg:grid-cols-5 gap-12">

      <!-- Contact info -->
      <div class="lg:col-span-2 space-y-8">
        <div>
          <h2 class="text-xl font-serif font-bold text-gray-900 mb-6">Our Office</h2>
          <div class="space-y-5">
            <div v-if="settings?.phone" class="flex gap-4">
              <div class="w-11 h-11 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
              </div>
              <div>
                <p class="font-semibold text-gray-900 text-sm">Phone</p>
                <a :href="`tel:${settings.phone}`" class="text-gray-600 text-sm hover:text-amber-600 transition-colors">{{ settings.phone }}</a>
              </div>
            </div>

            <div v-if="settings?.email" class="flex gap-4">
              <div class="w-11 h-11 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
              </div>
              <div>
                <p class="font-semibold text-gray-900 text-sm">Email</p>
                <a :href="`mailto:${settings.email}`" class="text-gray-600 text-sm hover:text-amber-600 transition-colors">{{ settings.email }}</a>
              </div>
            </div>

            <div v-if="settings?.address" class="flex gap-4">
              <div class="w-11 h-11 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                </svg>
              </div>
              <div>
                <p class="font-semibold text-gray-900 text-sm">Address</p>
                <p class="text-gray-600 text-sm">{{ settings.address }}</p>
              </div>
            </div>

            <div v-if="settings?.whatsapp" class="flex gap-4">
              <div class="w-11 h-11 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                <span class="text-lg">💬</span>
              </div>
              <div>
                <p class="font-semibold text-gray-900 text-sm">WhatsApp</p>
                <a :href="`https://wa.me/${settings.whatsapp}`" target="_blank"
                  class="text-gray-600 text-sm hover:text-emerald-600 transition-colors">
                  {{ settings.whatsapp }}
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Form -->
      <div class="lg:col-span-3">
        <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">

          <div v-if="$page.props.flash?.success" class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl p-4 mb-6 text-sm font-medium">
            ✓ {{ $page.props.flash.success }}
          </div>

          <h2 class="font-serif font-bold text-gray-900 text-2xl mb-6">Send a Message</h2>

          <form @submit.prevent="submit" class="space-y-4">
            <div class="grid sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5">Name *</label>
                <input v-model="form.name" type="text" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300" />
                <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5">Email *</label>
                <input v-model="form.email" type="email" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300" />
                <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
              </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5">Phone</label>
                <input v-model="form.phone" type="text" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300" />
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5">Subject</label>
                <input v-model="form.subject" type="text" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300" />
              </div>
            </div>

            <div>
              <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5">Message *</label>
              <textarea v-model="form.message" rows="5" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300" />
              <p v-if="form.errors.message" class="text-red-500 text-xs mt-1">{{ form.errors.message }}</p>
            </div>

            <button type="submit" :disabled="form.processing"
              class="w-full bg-amber-500 hover:bg-amber-600 disabled:opacity-60 text-white font-bold py-4 rounded-xl transition-all">
              {{ form.processing ? 'Sending...' : '📩 Send Message' }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
