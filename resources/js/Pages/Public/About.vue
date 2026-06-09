<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineOptions({ layout: PublicLayout })

const page     = usePage()
const settings = computed(() => page.props.settings)
</script>

<template>
  <!-- Hero -->
  <div class="bg-gray-900 pt-28 pb-20">
    <div class="max-w-4xl mx-auto px-4 text-center">
      <p class="text-amber-400 text-sm font-semibold uppercase tracking-widest mb-4">✦ Our Story</p>
      <h1 class="text-5xl font-serif font-bold text-white mb-6 leading-tight">
        About {{ settings?.site_name || 'LuxeEstate' }}
      </h1>
      <p class="text-xl text-gray-300 max-w-2xl mx-auto leading-relaxed">
        {{ settings?.site_description || 'A premier real estate agency dedicated to helping you find your perfect property.' }}
      </p>
    </div>
  </div>

  <!-- Mission -->
  <div class="max-w-6xl mx-auto px-4 py-20">
    <div class="grid lg:grid-cols-2 gap-16 items-center">
      <div>
        <p class="text-amber-500 text-sm font-semibold uppercase tracking-widest mb-4">Our Mission</p>
        <h2 class="text-3xl font-serif font-bold text-gray-900 mb-6 leading-tight">
          Making Real Estate Simple, Transparent, and Rewarding
        </h2>
        <p class="text-gray-600 leading-relaxed mb-5">
          We believe finding the right property should be an exciting journey, not an overwhelming process.
          Our experienced team combines local market expertise with a commitment to outstanding client service.
        </p>
        <p class="text-gray-600 leading-relaxed mb-8">
          Whether you're buying your first home, investing in a rental portfolio, or relocating internationally,
          we provide end-to-end support tailored to your unique needs.
        </p>
        <Link href="/contact"
          class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-bold px-8 py-4 rounded-full transition-all text-sm">
          Work With Us →
        </Link>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-2 gap-5">
        <div v-for="stat in [
          { value: '500+', label: 'Properties Sold', icon: '🏠' },
          { value: '12+',  label: 'Years Experience', icon: '📅' },
          { value: '98%',  label: 'Client Satisfaction', icon: '⭐' },
          { value: '3',    label: 'Countries Served', icon: '🌍' },
        ]" :key="stat.label"
          class="bg-gray-50 border border-gray-100 rounded-2xl p-6 text-center hover:bg-amber-50 hover:border-amber-200 transition-all">
          <p class="text-3xl mb-1">{{ stat.icon }}</p>
          <p class="text-3xl font-bold text-gray-900 font-serif">{{ stat.value }}</p>
          <p class="text-xs text-gray-500 mt-1 font-medium">{{ stat.label }}</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Values -->
  <div class="bg-gray-950 py-20">
    <div class="max-w-6xl mx-auto px-4">
      <div class="text-center mb-14">
        <p class="text-amber-400 text-sm font-semibold uppercase tracking-widest mb-3">Our Values</p>
        <h2 class="text-3xl font-serif font-bold text-white">What We Stand For</h2>
      </div>
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div v-for="value in [
          { icon: '🤝', title: 'Integrity',    desc: 'Honest and transparent in all our dealings.' },
          { icon: '🎯', title: 'Excellence',   desc: 'We go above and beyond in everything we do.' },
          { icon: '💡', title: 'Innovation',   desc: 'Leveraging technology for a better experience.' },
          { icon: '❤️', title: 'Client First', desc: 'Your goals are always our top priority.' },
        ]" :key="value.title"
          class="bg-gray-900 border border-gray-800 rounded-2xl p-6 text-center hover:border-amber-500/30 transition-all">
          <p class="text-4xl mb-4">{{ value.icon }}</p>
          <h3 class="font-semibold text-white text-lg mb-2">{{ value.title }}</h3>
          <p class="text-gray-400 text-sm">{{ value.desc }}</p>
        </div>
      </div>
    </div>
  </div>

  <!-- CTA -->
  <div class="py-20 bg-amber-500">
    <div class="max-w-3xl mx-auto px-4 text-center">
      <h2 class="text-3xl font-serif font-bold text-white mb-4">Ready to Find Your Dream Property?</h2>
      <div class="flex gap-4 justify-center">
        <Link href="/properties"
          class="bg-white text-amber-600 hover:bg-amber-50 font-bold px-8 py-4 rounded-full transition-all text-sm">
          Browse Properties
        </Link>
        <Link href="/contact"
          class="border-2 border-white text-white hover:bg-white/10 font-bold px-8 py-4 rounded-full transition-all text-sm">
          Contact Us
        </Link>
      </div>
    </div>
  </div>
</template>
