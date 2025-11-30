<script setup lang="ts">
import BlogCard from './BlogCard.vue'

const posts = [
  {
    id: 1,
    image: 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&auto=format&fit=crop&w=2072&q=80',
    category: 'Technology',
    title: 'The Future of Web Development: 2025 and Beyond',
    author: { name: 'Sarah Chen', avatar: 'https://i.pravatar.cc/150?img=10' },
    date: 'May 15, 2024',
    readTime: '5 min read',
  },
  {
    id: 2,
    image: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
    category: 'Data Science',
    title: 'Unlocking Insights: A Guide to Data Visualization',
    author: { name: 'Michael Foster', avatar: 'https://i.pravatar.cc/150?img=12' },
    date: 'May 12, 2024',
    readTime: '7 min read',
  },
  {
    id: 3,
    image: 'https://images.unsplash.com/photo-1553484771-371a605b060b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
    category: 'Design',
    title: 'Mastering User Experience: Principles of Good UI/UX',
    author: { name: 'Emma Davis', avatar: 'https://i.pravatar.cc/150?img=5' },
    date: 'May 10, 2024',
    readTime: '4 min read',
  },
]
</script>

<template>
  <section class="py-24 sm:py-32 bg-gray-50">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="mx-auto max-w-2xl text-center">
        <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Latest Articles</h2>
        <p class="mt-4 text-lg leading-8 text-gray-600">
          Explore our latest thoughts on technology, design, and business.
        </p>
      </div>
      <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">
        <BlogCard v-for="post in posts" :key="post.id" v-bind="post" />
      </div>
    </div>
  </section>
</template>