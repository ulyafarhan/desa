<script setup lang="ts">
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import { TrendingUpIcon, ArrowUpRightIcon, MoreHorizontalIcon } from 'lucide-vue-next'
</script>

<template>
  <div class="relative isolate pt-14 lg:pt-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
        <div>
          <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl lg:leading-[1.1]">
            Stay Ahead<br />of the Curve
          </h1>
          <p class="mt-6 text-lg leading-8 text-gray-600">
            Discover the latest trends, proven strategies, and actionable insights to grow your business and stay ahead of the competition.
          </p>
          <div class="mt-10 flex flex-col sm:flex-row gap-4 sm:max-w-md">
            <Input type="email" placeholder="Enter your email" class="h-12 flex-grow" />
            <Button class="h-12 px-8 bg-brand-500 hover:bg-brand-600 text-white text-base">Subscribe</Button>
          </div>
          <p class="mt-3 text-sm text-gray-500">No spam, unsubscribe anytime.</p>

          <div class="mt-10 flex items-center gap-x-4">
            <div class="flex -space-x-2 overflow-hidden">
              <Avatar class="inline-block h-10 w-10 ring-2 ring-white"><AvatarImage src="https://i.pravatar.cc/150?img=1" /><AvatarFallback>U1</AvatarFallback></Avatar>
              <Avatar class="inline-block h-10 w-10 ring-2 ring-white"><AvatarImage src="https://i.pravatar.cc/150?img=2" /><AvatarFallback>U2</AvatarFallback></Avatar>
              <Avatar class="inline-block h-10 w-10 ring-2 ring-white"><AvatarImage src="https://i.pravatar.cc/150?img=3" /><AvatarFallback>U3</AvatarFallback></Avatar>
              <Avatar class="inline-block h-10 w-10 ring-2 ring-white"><AvatarImage src="https://i.pravatar.cc/150?img=4" /><AvatarFallback>U4</AvatarFallback></Avatar>
            </div>
            <div class="text-sm leading-6 text-gray-600">
              Join <strong class="font-semibold text-gray-900">1,200+</strong> other subscribers
            </div>
          </div>
        </div>

        <div class="relative h-[400px] lg:h-[500px] w-full">
          <Card class="absolute top-0 right-0 w-[90%] h-[85%] bg-white shadow-xl rounded-2xl overflow-hidden z-0 transform translate-x-4 -translate-y-4 border-none">
             <CardHeader class="pb-2">
              <div class="flex justify-between items-center">
                <CardTitle class="text-lg font-medium text-gray-500">Weekly Visits</CardTitle>
                <Button variant="ghost" size="icon" class="h-8 w-8 text-gray-400"><MoreHorizontalIcon class="h-5 w-5" /></Button>
              </div>
              <div class="text-3xl font-bold">124,500</div>
             </CardHeader>
            <CardContent class="p-0 mt-4">
              <div class="flex items-end justify-between h-48 px-6 pb-6 gap-2">
                <div class="w-full bg-brand-100 rounded-t-md h-[40%]"></div>
                <div class="w-full bg-brand-500 rounded-t-md h-[70%]"></div>
                <div class="w-full bg-brand-100 rounded-t-md h-[50%]"></div>
                <div class="w-full bg-brand-100 rounded-t-md h-[30%]"></div>
                <div class="w-full bg-brand-100 rounded-t-md h-[60%]"></div>
                <div class="w-full bg-brand-100 rounded-t-md h-[80%]"></div>
                <div class="w-full bg-brand-500 rounded-t-md h-[90%]"></div>
              </div>
            </CardContent>
          </Card>

          <Card class="absolute bottom-0 left-0 w-[85%] h-[65%] bg-brand-500 shadow-2xl rounded-2xl z-10 text-white border-none overflow-hidden">
            <CardHeader>
              <CardTitle class="text-lg font-medium text-brand-100">Total Balance</CardTitle>
              <div class="flex items-baseline gap-2 mt-2">
                <span class="text-4xl font-bold">$78,200</span>
                <span class="text-sm font-medium text-brand-200 bg-white/20 px-2 py-1 rounded-full flex items-center gap-1">
                  <TrendingUpIcon class="h-3 w-3" /> +12%
                </span>
              </div>
            </CardHeader>
            <CardContent class="relative p-0 mt-6 h-full">
              <svg class="w-full h-full absolute bottom-0" viewBox="0 0 100 50" preserveAspectRatio="none">
                <defs>
                  <linearGradient id="grad" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" style="stop-color:white;stop-opacity:0.5" />
                    <stop offset="100%" style="stop-color:white;stop-opacity:0" />
                  </linearGradient>
                </defs>
                <path d="M0,40 Q25,20 50,30 T100,10 V50 H0 Z" fill="url(#grad)" />
                <path d="M0,40 Q25,20 50,30 T100,10" fill="none" stroke="white" stroke-width="2" />
              </svg>
              <div class="absolute bottom-6 right-6">
                 <Button size="icon" class="rounded-full h-12 w-12 bg-white text-brand-500 hover:bg-gray-100 shadow-lg">
                   <ArrowUpRightIcon class="h-6 w-6" />
                 </Button>
               </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  </div>
</template>