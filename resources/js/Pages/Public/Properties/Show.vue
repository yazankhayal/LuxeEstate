<script setup>
import { ref, computed } from 'vue'
import { usePage, useForm, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import PropertyCard from '@/Components/Public/PropertyCard.vue'

defineOptions({ layout: PublicLayout })

const props = defineProps({
  property: Object,
  similar:  Array,
})

const page   = usePage()
const locale = computed(() => page.props.locale)

const translation = computed(() =>
  props.property.translations?.find(t => t.locale === locale.value)
  ?? props.property.translations?.find(t => t.locale === 'en')
  ?? {}
)

const activeImage = ref(props.property.images?.[0] ?? null)

const price = computed(() => {
  const p = props.property.price
  const c = props.property.currency || 'USD'
  return new Intl.NumberFormat('en-US', { style: 'currency', currency: c, maximumFractionDigits: 0 }).format(p)
})

const form = useForm({
  name:        '',
  email:       '',
  phone:       '',
  message:     '',
  property_id: props.property.id,
})

function sendInquiry() {
  form.post(route('contact.store'), { preserveScroll: true, onSuccess: () => form.reset() })
}
</script>

<template>
  <div class="pt-20">
    <!-- Image gallery -->
    <div class="bg-gray-900">
      <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-4 gap-3 h-[480px]">
          <!-- Main image -->
          <div class="col-span-3 rounded-2xl overflow-hidden bg-gray-800">
            <img
              v-if="activeImage"
              :src="activeImage.url"
              :alt="translation.title"
              class="w-full h-full object-cover"
            />
          </div>
          <!-- Thumbnails -->
          <div class="flex flex-col gap-3 overflow-y-auto">
            <button
              v-for="img in property.images"
              :key="img.id"
              @click="activeImage = img"
              :class="['rounded-xl overflow-hidden aspect-video shrink-0', activeImage?.id === img.id ? 'ring-2 ring-amber-500' : 'opacity-70 hover:opacity-100']"
            >
              <img :src="img.url" class="w-full h-full object-cover" />
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-12">
      <div class="grid lg:grid-cols-3 gap-10">

        <!-- Main content -->
        <div class="lg:col-span-2">

          <!-- Breadcrumb -->
          <div class="flex items-center gap-2 text-xs text-gray-400 mb-6">
            <Link href="/" class="hover:text-amber-600">Home</Link>
            <span>/</span>
            <Link href="/properties" class="hover:text-amber-600">Properties</Link>
            <span>/</span>
            <span class="text-gray-600">{{ translation.title }}</span>
          </div>

          <!-- Title & badges -->
          <div class="flex flex-wrap items-start gap-3 mb-2">
            <span :class="['text-xs font-bold px-3 py-1 rounded-full uppercase', property.type === 'sale' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700']">
              For {{ property.type === 'sale' ? 'Sale' : 'Rent' }}
            </span>
            <span v-if="property.is_featured" class="text-xs font-bold px-3 py-1 rounded-full bg-amber-100 text-amber-700 uppercase">
              ★ Featured
            </span>
          </div>

          <h1 class="text-3xl font-serif font-bold text-gray-900 mb-2">{{ translation.title }}</h1>

          <p class="flex items-center gap-2 text-gray-500 mb-6">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            </svg>
            {{ property.location }}, {{ property.city }}, {{ property.country }}
          </p>

          <!-- Price -->
          <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-8">
            <p class="text-4xl font-bold text-amber-600">{{ price }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ property.type === 'rent' ? 'per month' : 'asking price' }}</p>
          </div>

          <!-- Specs grid -->
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            <div v-if="property.area" class="bg-gray-50 rounded-2xl p-4 text-center">
              <p class="text-xl font-bold text-gray-900">{{ property.area }}</p>
              <p class="text-xs text-gray-500 mt-1">m² Area</p>
            </div>
            <div v-if="property.bedrooms" class="bg-gray-50 rounded-2xl p-4 text-center">
              <p class="text-xl font-bold text-gray-900">{{ property.bedrooms }}</p>
              <p class="text-xs text-gray-500 mt-1">Bedrooms</p>
            </div>
            <div v-if="property.bathrooms" class="bg-gray-50 rounded-2xl p-4 text-center">
              <p class="text-xl font-bold text-gray-900">{{ property.bathrooms }}</p>
              <p class="text-xs text-gray-500 mt-1">Bathrooms</p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-4 text-center">
              <p class="text-xl font-bold text-gray-900">{{ property.views_count }}</p>
              <p class="text-xs text-gray-500 mt-1">Views</p>
            </div>
          </div>

          <!-- Description -->
          <div class="prose max-w-none mb-10">
            <h2 class="text-xl font-serif font-bold text-gray-900 mb-4">About This Property</h2>
            <div class="text-gray-600 leading-relaxed whitespace-pre-line">{{ translation.description }}</div>
          </div>

          <!-- Features -->
          <div v-if="translation.features?.length" class="mb-10">
            <h3 class="text-xl font-serif font-bold text-gray-900 mb-4">Features & Amenities</h3>
            <ul class="grid grid-cols-2 gap-3">
              <li v-for="feature in translation.features" :key="feature"
                  class="flex items-center gap-2 text-sm text-gray-700">
                <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ feature }}
              </li>
            </ul>
          </div>

          <!-- Video -->
          <div v-if="property.video_url" class="mb-10">
            <h3 class="text-xl font-serif font-bold text-gray-900 mb-4">Property Video</h3>
            <div class="aspect-video rounded-2xl overflow-hidden bg-gray-900">
              <iframe
                :src="property.video_url.replace('watch?v=', 'embed/')"
                class="w-full h-full"
                allowfullscreen
              />
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">

          <!-- Contact form -->
          <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm sticky top-24">
            <h3 class="font-serif font-bold text-gray-900 text-xl mb-5">Inquire About This Property</h3>

            <div v-if="$page.props.flash?.success" class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl p-3 mb-4">
              {{ $page.props.flash.success }}
            </div>

            <form @submit.prevent="sendInquiry" class="space-y-3">
              <input v-model="form.name" type="text" placeholder="Your Name *" required
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300" />
              <input v-model="form.email" type="email" placeholder="Email Address *" required
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300" />
              <input v-model="form.phone" type="text" placeholder="Phone Number"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300" />
              <textarea v-model="form.message" rows="4"
                :placeholder="`I'm interested in ${translation.title || 'this property'}...`"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300"
              />
              <button type="submit" :disabled="form.processing"
                class="w-full bg-amber-500 hover:bg-amber-600 disabled:opacity-60 text-white font-bold py-3.5 rounded-xl transition-all text-sm">
                {{ form.processing ? 'Sending...' : '📩 Send Inquiry' }}
              </button>
            </form>

            <!-- Quick contact -->
            <div class="border-t border-gray-100 mt-5 pt-5 flex flex-col gap-3">
              <a v-if="property.phone" :href="`tel:${property.phone}`"
                class="flex items-center gap-3 text-sm font-medium text-gray-700 hover:text-amber-600 transition-colors">
                <span class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center">📞</span>
                {{ property.phone }}
              </a>
              <a v-if="property.whatsapp" :href="`https://wa.me/${property.whatsapp}`" target="_blank"
                class="flex items-center gap-3 text-sm font-medium text-gray-700 hover:text-emerald-600 transition-colors">
                <span class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center">💬</span>
                WhatsApp
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Similar properties -->
      <div v-if="similar?.length" class="mt-16">
        <h2 class="text-2xl font-serif font-bold text-gray-900 mb-8">Similar Properties</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
          <PropertyCard v-for="p in similar" :key="p.id" :property="p" />
        </div>
      </div>
    </div>
  </div>
</template>
