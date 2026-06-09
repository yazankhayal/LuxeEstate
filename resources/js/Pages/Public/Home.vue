<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import PropertyCard from '@/Components/Public/PropertyCard.vue'

defineOptions({ layout: PublicLayout })

const props = defineProps({
  featured:    { type: Array, default: () => [] },
  recentPosts: { type: Array, default: () => [] },
  services:    { type: Array, default: () => [] },
})

const page   = usePage()
const locale = computed(() => page.props.locale)

function getTitle(item) {
  return item.translations?.find(t => t.locale === locale.value)?.title
    ?? item.translation?.title
    ?? ''
}

function getDescription(item) {
  return item.translations?.find(t => t.locale === locale.value)?.description
    ?? item.translation?.description
    ?? ''
}
</script>

<template>
  <!-- ─── Hero ────────────────────────────────────────────────────────────── -->
  <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-amber-900">
      <div class="absolute inset-0 opacity-20"
           style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.3\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')">
      </div>
    </div>

    <div class="relative max-w-5xl mx-auto px-4 text-center text-white pt-24">
      <div class="inline-flex items-center gap-2 bg-amber-500/20 border border-amber-400/30 text-amber-300 text-xs font-semibold px-4 py-2 rounded-full mb-8 uppercase tracking-widest">
        ✦ Premium Real Estate Agency
      </div>

      <h1 class="text-5xl sm:text-6xl lg:text-7xl font-serif font-bold leading-tight mb-6">
        Find Your<br/>
        <span class="text-amber-400">Dream Property</span>
      </h1>

      <p class="text-lg text-gray-300 max-w-2xl mx-auto mb-10">
        Discover exceptional properties for sale and rent across prime locations.
        Your perfect home or investment awaits.
      </p>

      <!-- Quick search bar -->
      <div class="bg-white rounded-2xl p-2 flex flex-col sm:flex-row gap-2 max-w-2xl mx-auto shadow-2xl">
        <select class="flex-1 px-4 py-3 text-gray-700 text-sm bg-transparent rounded-xl focus:outline-none">
          <option value="">All Types</option>
          <option value="sale">For Sale</option>
          <option value="rent">For Rent</option>
        </select>
        <div class="w-px bg-gray-200 hidden sm:block self-stretch my-1"></div>
        <input
          type="text"
          placeholder="Search by city, location..."
          class="flex-1 px-4 py-3 text-gray-700 text-sm bg-transparent rounded-xl focus:outline-none placeholder-gray-400"
        />
        <Link
          href="/properties"
          class="bg-amber-500 hover:bg-amber-600 text-white font-semibold px-8 py-3 rounded-xl transition-all whitespace-nowrap text-sm"
        >
          Search
        </Link>
      </div>

      <!-- Stats -->
      <div class="flex flex-wrap justify-center gap-12 mt-16 text-white">
        <div v-for="stat in [{n:'500+',l:'Properties'},{n:'1,200+',l:'Happy Clients'},{n:'12+',l:'Years Experience'},{n:'3',l:'Countries'}]"
             :key="stat.l"
             class="text-center"
        >
          <p class="text-3xl font-bold text-amber-400">{{ stat.n }}</p>
          <p class="text-sm text-gray-400 mt-1">{{ stat.l }}</p>
        </div>
      </div>
    </div>

    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
      <svg class="w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
      </svg>
    </div>
  </section>

  <!-- ─── Featured Properties ──────────────────────────────────────────────── -->
  <section v-if="featured.length" class="py-20 px-4 max-w-7xl mx-auto">
    <div class="flex items-end justify-between mb-10">
      <div>
        <p class="text-amber-500 text-sm font-semibold uppercase tracking-widest mb-2">✦ Featured</p>
        <h2 class="text-3xl font-serif font-bold text-gray-900">Premium Properties</h2>
      </div>
      <Link href="/properties" class="text-sm font-semibold text-amber-600 hover:underline">
        View All →
      </Link>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <PropertyCard
        v-for="property in featured"
        :key="property.id"
        :property="property"
      />
    </div>
  </section>

  <!-- ─── Services ─────────────────────────────────────────────────────────── -->
  <section v-if="services.length" class="py-20 bg-gray-950">
    <div class="max-w-7xl mx-auto px-4">
      <div class="text-center mb-14">
        <p class="text-amber-500 text-sm font-semibold uppercase tracking-widest mb-3">✦ What We Offer</p>
        <h2 class="text-3xl font-serif font-bold text-white">Our Services</h2>
      </div>

      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="service in services"
          :key="service.id"
          class="bg-gray-900 border border-gray-800 rounded-2xl p-7 hover:border-amber-500/50 transition-all group"
        >
          <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center mb-5 group-hover:bg-amber-500/20 transition-colors">
            <svg class="w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
          </div>
          <h3 class="font-semibold text-white text-lg mb-2">{{ getTitle(service) }}</h3>
          <p class="text-gray-400 text-sm leading-relaxed">{{ getDescription(service) }}</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ─── Recent Blog Posts ────────────────────────────────────────────────── -->
  <section v-if="recentPosts.length" class="py-20 px-4 max-w-7xl mx-auto">
    <div class="flex items-end justify-between mb-10">
      <div>
        <p class="text-amber-500 text-sm font-semibold uppercase tracking-widest mb-2">✦ Insights</p>
        <h2 class="text-3xl font-serif font-bold text-gray-900">Latest from Our Blog</h2>
      </div>
      <Link href="/blog" class="text-sm font-semibold text-amber-600 hover:underline">
        All Articles →
      </Link>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <Link
        v-for="post in recentPosts"
        :key="post.id"
        :href="route('blog.show', post.slug)"
        class="group block bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-lg transition-all"
      >
        <div class="aspect-video bg-gray-100 overflow-hidden">
          <img
            v-if="post.featured_image_url"
            :src="post.featured_image_url"
            :alt="getTitle(post)"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
          />
          <div v-else class="w-full h-full bg-gradient-to-br from-amber-50 to-amber-100"></div>
        </div>
        <div class="p-5">
          <span class="text-xs text-amber-600 font-semibold uppercase">{{ post.category?.name }}</span>
          <h3 class="font-semibold text-gray-900 mt-2 mb-2 group-hover:text-amber-600 transition-colors">
            {{ getTitle(post) }}
          </h3>
          <p class="text-xs text-gray-400">
            {{ post.published_at ? new Date(post.published_at).toLocaleDateString() : '' }}
          </p>
        </div>
      </Link>
    </div>
  </section>

  <!-- ─── CTA ──────────────────────────────────────────────────────────────── -->
  <section class="py-20 bg-amber-500">
    <div class="max-w-4xl mx-auto px-4 text-center">
      <h2 class="text-3xl sm:text-4xl font-serif font-bold text-white mb-4">
        Ready to Find Your Dream Property?
      </h2>
      <p class="text-amber-100 mb-8 text-lg">
        Our expert team is ready to guide you through every step.
      </p>
      <div class="flex flex-wrap gap-4 justify-center">
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
  </section>
</template>
