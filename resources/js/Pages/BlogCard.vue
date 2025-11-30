<script setup lang="ts">
import { Card, CardContent, CardFooter } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import { ClockIcon, ArrowUpRightIcon } from 'lucide-vue-next'

defineProps<{
  image: string
  category: string
  title: string
  author: { name: string; avatar: string }
  date: string
  readTime: string
}>()
</script>

<template>
  <Card class="overflow-hidden border-none shadow-md hover:shadow-xl transition-shadow duration-300 cursor-pointer group">
    <div class="relative h-64 overflow-hidden">
      <img :src="image" :alt="title" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
      <Badge class="absolute top-4 left-4 bg-white/90 text-gray-900 hover:bg-white font-medium">{{ category }}</Badge>
      <div class="absolute top-4 right-4 p-2 bg-white/30 backdrop-blur-sm rounded-full text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        <ArrowUpRightIcon class="h-5 w-5" />
      </div>
    </div>
    <CardContent class="p-6">
      <h3 class="text-xl font-bold text-gray-900 line-clamp-2 group-hover:text-brand-500 transition-colors">
        {{ title }}
      </h3>
    </CardContent>
    <CardFooter class="p-6 pt-0 flex items-center justify-between text-sm text-gray-500">
      <div class="flex items-center gap-x-3">
        <Avatar class="h-8 w-8">
          <AvatarImage :src="author.avatar" :alt="author.name" />
          <AvatarFallback>{{ author.name.charAt(0) }}</AvatarFallback>
        </Avatar>
        <span class="font-medium text-gray-900">{{ author.name }}</span>
      </div>
      <div class="flex items-center gap-x-2">
        <span>{{ date }}</span>
        <span>•</span>
        <div class="flex items-center gap-x-1">
          <ClockIcon class="h-4 w-4" />
          <span>{{ readTime }}</span>
        </div>
      </div>
    </CardFooter>
  </Card>
</template>