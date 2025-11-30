<script setup lang="ts">
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import { MenuIcon, XIcon } from 'lucide-vue-next'

const isMenuOpen = ref(false)

const navigation = [
  { name: 'Features', href: '#' },
  { name: 'Pricing', href: '#' },
  { name: 'About us', href: '#' },
  { name: 'Contact', href: '#' },
]
</script>

<template>
  <header class="bg-white sticky top-0 z-50 border-b border-gray-100">
    <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
      <div class="flex lg:flex-1">
        <a href="#" class="-m-1.5 p-1.5 text-2xl font-bold text-gray-900">
          OpenUI
        </a>
      </div>
      <div class="flex lg:hidden">
        <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700" @click="isMenuOpen = true">
          <span class="sr-only">Open main menu</span>
          <MenuIcon class="h-6 w-6" aria-hidden="true" />
        </button>
      </div>
      <div class="hidden lg:flex lg:gap-x-12">
        <a v-for="item in navigation" :key="item.name" :href="item.href" class="text-sm font-semibold leading-6 text-gray-900 hover:text-brand-500 transition-colors">
          {{ item.name }}
        </a>
      </div>
      <div class="hidden lg:flex lg:flex-1 lg:justify-end lg:gap-x-4">
        <Button variant="ghost" class="text-sm font-semibold leading-6 text-gray-900">Log in</Button>
        <Button class="text-sm font-semibold leading-6 bg-brand-500 hover:bg-brand-600 text-white">Sign up</Button>
      </div>
    </nav>
    <div v-if="isMenuOpen" class="lg:hidden" role="dialog" aria-modal="true">
      <div class="fixed inset-0 z-50 bg-black/20 backdrop-blur-sm" @click="isMenuOpen = false"></div>
      <div class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
        <div class="flex items-center justify-between">
          <a href="#" class="-m-1.5 p-1.5 text-2xl font-bold text-gray-900">
            OpenUI
          </a>
          <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700" @click="isMenuOpen = false">
            <span class="sr-only">Close menu</span>
            <XIcon class="h-6 w-6" aria-hidden="true" />
          </button>
        </div>
        <div class="mt-6 flow-root">
          <div class="-my-6 divide-y divide-gray-500/10">
            <div class="space-y-2 py-6">
              <a v-for="item in navigation" :key="item.name" :href="item.href" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                {{ item.name }}
              </a>
            </div>
            <div class="py-6 flex flex-col gap-y-4">
              <Button variant="outline" class="w-full justify-center">Log in</Button>
              <Button class="w-full justify-center bg-brand-500 hover:bg-brand-600 text-white">Sign up</Button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>