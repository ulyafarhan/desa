<script setup lang="ts">
import NavBar from './NavBar.vue'
import HeroSection from './HeroSection.vue'
import BlogSection from './BlogSection.vue'
</script>

<template>
  <div class="min-h-screen bg-white">
    <NavBar />
    <main>
      <HeroSection class="mb-24" />
      <BlogSection />
    </main>
    <footer class="bg-white py-12 text-center text-gray-500 text-sm border-t border-gray-100">
      © 2024 OpenUI. All rights reserved.
    </footer>
  </div>
</template>