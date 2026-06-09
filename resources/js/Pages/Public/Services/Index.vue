<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineOptions({ layout: PublicLayout })

const props = defineProps({
  services: Array,
})

const page   = usePage()
const locale = computed(() => page.props.locale)

function trans(service, field) {
  return service.translations?.find(t => t.locale === locale.value)?.[field]
    ?? service.translations?.find(t => t.locale === 'en')?.[field]
    ?? ''
}
</script>

<template>
  <!-- Header -->
  <div class="bg-gray-900 pt-28 pb-16">
    <div class="max-w-4xl mx-auto px-4 text-center">
      <p class="text-amber-400 text-sm font-semibold uppercase tracking-widest mb-3">✦ What We Do</p>
      <h1 class="text-4xl font-serif font-bold text-white mb-4">Our Services</h1>
      <p class="text-gray-400 max-w-xl mx-auto">
        Comprehensive real estate services tailored to buyers, sellers, renters, and investors.
      </p>
    </div>
  </div>

  <!-- Services grid -->
  <div class="max-w-7xl mx-auto px-4 py-16">
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div v-for="service in services" :key="service.id"
        class="group bg-white rounded-2xl border border-gray-100 p-8 hover:shadow-xl hover:border-amber-200 transition-all duration-300">

        <!-- Image or Icon -->
        <div class="mb-6">
          <img v-if="service.image_url" :src="service.image_url"
            class="w-full h-40 object-cover rounded-xl mb-4" />
          <div v-else
            class="w-14 h-14 bg-amber-50 group-hover:bg-amber-100 rounded-2xl flex items-center justify-center transition-colors">
            <svg class="w-7 h-7 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
          </div>
        </div>

        <h3 class="font-serif font-bold text-gray-900 text-xl mb-3 group-hover:text-amber-600 transition-colors">
          {{ trans(service, 'title') }}
        </h3>

        <p class="text-gray-500 text-sm leading-relaxed">
          {{ trans(service, 'description') }}
        </p>

        <div v-if="trans(service, 'content')" class="mt-5">
          <div class="text-sm text-gray-600 leading-relaxed prose-sm prose"
            v-html="trans(service, 'content')" />
        </div>

        <div class="mt-6 pt-6 border-t border-gray-100">
          <Link href="/contact"
            class="inline-flex items-center gap-2 text-sm font-semibold text-amber-600 hover:text-amber-700 transition-colors">
            Get started
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </Link>
        </div>
      </div>
    </div>
  </div>

  <!-- CTA -->
  <div class="bg-gray-950 py-20">
    <div class="max-w-3xl mx-auto px-4 text-center">
      <h2 class="text-3xl font-serif font-bold text-white mb-4">Need a custom solution?</h2>
      <p class="text-gray-400 mb-8">Our team is here to help you with any real estate need.</p>
      <Link href="/contact"
        class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-bold px-8 py-4 rounded-full transition-all text-sm">
        Contact Our Team →
      </Link>
    </div>
  </div>
</template>
