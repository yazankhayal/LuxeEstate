<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div class="min-h-screen bg-gray-950 flex items-center justify-center px-4">
    <div class="text-center">
      <p class="text-8xl font-serif font-bold text-amber-500 mb-4">404</p>
      <h1 class="text-2xl font-bold text-white mb-3">Page Not Found</h1>
      <p class="text-gray-400 mb-8">The page you're looking for doesn't exist or has been moved.</p>
      <Link href="/" class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-bold px-8 py-3 rounded-full transition-all text-sm">
        Back to Home
      </Link>
    </div>
  </div>
</template>
