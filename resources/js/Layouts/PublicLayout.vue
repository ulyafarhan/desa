<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import ChatWidget from '@/Components/ChatWidget.vue'; 
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gray-50 text-gray-900 font-sans selection:bg-blue-500 selection:text-white">
        <Head :title="title" />

        <nav class="sticky top-0 z-50 w-full border-b border-gray-200 bg-white/90 backdrop-blur-md shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    
                    <div class="flex items-center">
                        <div class="shrink-0 flex items-center gap-2">
                            <Link :href="route('welcome')">
                                <ApplicationLogo class="block h-9 w-auto fill-current text-blue-600" />
                            </Link>
                            <span class="hidden md:block font-bold text-xl tracking-tight text-gray-800">DESA-Smart</span>
                        </div>

                        <div class="hidden sm:ml-10 sm:flex sm:space-x-8">
                            <Link :href="route('welcome')" :class="route().current('welcome') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out">
                                Beranda
                            </Link>
                            <Link :href="route('panduan')" :class="route().current('panduan') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out">
                                Panduan
                            </Link>
                            <Link :href="route('kontak')" :class="route().current('kontak') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out">
                                Kontak
                            </Link>
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <template v-if="$page.props.auth.user">
                            <Link :href="route('dashboard')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Dashboard
                            </Link>
                        </template>
                        <template v-else>
                            <div class="space-x-4">
                                <Link :href="route('login')" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition">Masuk</Link>
                                <Link :href="route('register')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Daftar
                                </Link>
                            </div>
                        </template>
                    </div>

                    <div class="-mr-2 flex items-center sm:hidden">
                        <button
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                        >
                            <svg v-show="!showingNavigationDropdown" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg v-show="showingNavigationDropdown" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="sm:hidden bg-white border-b border-gray-200 shadow-lg">
                <div class="pt-2 pb-3 space-y-1">
                    <Link :href="route('welcome')" class="block w-full pl-3 pr-4 py-2 border-l-4 text-left text-base font-medium transition duration-150 ease-in-out" :class="route().current('welcome') ? 'border-blue-400 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'">
                        Beranda
                    </Link>
                    <Link :href="route('panduan')" class="block w-full pl-3 pr-4 py-2 border-l-4 text-left text-base font-medium transition duration-150 ease-in-out" :class="route().current('panduan') ? 'border-blue-400 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'">
                        Panduan
                    </Link>
                    <Link :href="route('kontak')" class="block w-full pl-3 pr-4 py-2 border-l-4 text-left text-base font-medium transition duration-150 ease-in-out" :class="route().current('kontak') ? 'border-blue-400 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'">
                        Kontak
                    </Link>
                </div>

                <div class="pt-4 pb-4 border-t border-gray-200">
                    <template v-if="$page.props.auth.user">
                        <div class="flex items-center px-4 mb-3">
                            <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
                        </div>
                        <div class="space-y-1">
                            <Link :href="route('dashboard')" class="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition duration-150 ease-in-out">
                                Ke Dashboard
                            </Link>
                        </div>
                    </template>
                    <template v-else>
                        <div class="space-y-1 px-4 pb-2">
                            <Link :href="route('login')" class="block w-full text-center py-2 bg-gray-100 rounded-lg text-gray-700 mb-2">Masuk</Link>
                            <Link :href="route('register')" class="block w-full text-center py-2 bg-blue-600 rounded-lg text-white">Daftar Akun</Link>
                        </div>
                    </template>
                </div>
            </div>
        </nav>

        <main>
            <slot />
        </main>
        
        <ChatWidget />
    </div>
</template>