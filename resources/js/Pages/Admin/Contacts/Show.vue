<script setup>
import { ref } from 'vue'
import { Link, useForm, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  contact: Object,
})

const replyForm = useForm({ notes: props.contact.admin_notes || '' })

function markReplied() {
  replyForm.post(route('admin.contacts.replied', props.contact.id), {
    preserveScroll: true,
  })
}

const statusColors = {
  new:      'bg-amber-500/15 text-amber-400',
  read:     'bg-blue-500/15 text-blue-400',
  replied:  'bg-emerald-500/15 text-emerald-400',
  archived: 'bg-gray-500/15 text-gray-400',
}
</script>

<template>
  <div class="max-w-3xl">
    <div class="flex items-center gap-4 mb-8">
      <Link href="/admin/contacts"
        class="p-2 rounded-xl bg-gray-900 border border-gray-800 text-gray-400 hover:text-white transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </Link>
      <div>
        <h1 class="text-2xl font-bold text-white">Inquiry from {{ contact.name }}</h1>
        <p class="text-gray-500 text-sm mt-0.5">
          Received {{ new Date(contact.created_at).toLocaleString() }}
        </p>
      </div>
    </div>

    <div class="space-y-5">
      <!-- Status banner -->
      <div :class="['rounded-2xl border px-6 py-4 flex items-center justify-between',
        contact.status === 'new' ? 'bg-amber-500/10 border-amber-500/30' : 'bg-gray-900 border-gray-800']">
        <div class="flex items-center gap-3">
          <span :class="['text-xs font-bold px-3 py-1.5 rounded-full uppercase', statusColors[contact.status]]">
            {{ contact.status }}
          </span>
          <span v-if="contact.replied_at" class="text-xs text-gray-500">
            Replied {{ new Date(contact.replied_at).toLocaleDateString() }}
          </span>
        </div>
        <button v-if="contact.status !== 'replied'"
          @click="markReplied"
          class="text-xs bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-4 py-2 rounded-lg transition-all">
          ✓ Mark as Replied
        </button>
      </div>

      <!-- Contact info -->
      <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6">
        <h3 class="font-semibold text-white mb-5 text-sm uppercase tracking-wider">Contact Details</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
          <div>
            <p class="text-gray-500 text-xs mb-1">Name</p>
            <p class="text-white font-medium">{{ contact.name }}</p>
          </div>
          <div>
            <p class="text-gray-500 text-xs mb-1">Email</p>
            <a :href="`mailto:${contact.email}`" class="text-amber-400 hover:underline">{{ contact.email }}</a>
          </div>
          <div v-if="contact.phone">
            <p class="text-gray-500 text-xs mb-1">Phone</p>
            <a :href="`tel:${contact.phone}`" class="text-white">{{ contact.phone }}</a>
          </div>
          <div v-if="contact.subject">
            <p class="text-gray-500 text-xs mb-1">Subject</p>
            <p class="text-white">{{ contact.subject }}</p>
          </div>
        </div>
      </div>

      <!-- Property reference -->
      <div v-if="contact.property" class="bg-gray-900 rounded-2xl border border-amber-500/20 p-6">
        <h3 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">Regarding Property</h3>
        <Link :href="route('properties.show', contact.property.slug)"
          target="_blank"
          class="flex items-center gap-3 text-amber-400 hover:text-amber-300 transition-colors">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
          {{ contact.property.translation?.title }}
          <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
          </svg>
        </Link>
      </div>

      <!-- Message -->
      <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6">
        <h3 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">Message</h3>
        <div class="bg-gray-800/50 rounded-xl p-5 border-l-4 border-amber-500/40">
          <p class="text-gray-200 leading-relaxed whitespace-pre-line">{{ contact.message }}</p>
        </div>
      </div>

      <!-- Admin notes -->
      <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6">
        <h3 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">Admin Notes</h3>
        <textarea
          v-model="replyForm.notes"
          rows="4"
          placeholder="Internal notes about this inquiry (not visible to the contact)"
          class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 transition"
        />
        <div class="flex justify-end mt-3">
          <button @click="markReplied" :disabled="replyForm.processing"
            class="bg-amber-500 hover:bg-amber-600 disabled:opacity-60 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all">
            {{ replyForm.processing ? 'Saving...' : 'Save Notes & Mark Replied' }}
          </button>
        </div>
      </div>

      <!-- Quick reply links -->
      <div class="flex gap-3">
        <a :href="`mailto:${contact.email}?subject=Re: ${contact.subject || 'Your Inquiry'}`"
          class="flex items-center gap-2 bg-gray-900 border border-gray-800 hover:border-amber-500/40 text-gray-300 hover:text-white px-5 py-2.5 rounded-xl text-sm transition-all">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
          Reply via Email
        </a>
        <a v-if="contact.phone" :href="`tel:${contact.phone}`"
          class="flex items-center gap-2 bg-gray-900 border border-gray-800 hover:border-amber-500/40 text-gray-300 hover:text-white px-5 py-2.5 rounded-xl text-sm transition-all">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
          </svg>
          Call
        </a>
      </div>
    </div>
  </div>
</template>
