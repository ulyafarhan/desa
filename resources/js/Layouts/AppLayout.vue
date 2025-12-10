<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { LayoutDashboard, User, LogOut } from 'lucide-vue-next';
import AiChatWidget from '@/Components/AiChatWidget.vue';

// Komponen UI (Hanya yang pasti ada)
import { Button } from '@/Components/ui/button';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';

defineProps({
    title: String,
});

// State untuk Menu Mobile
const showingNavigationDropdown = ref(false);

const getInitials = (name) => {
    return name ? name.split(' ').map((word) => word[0]).join('').toUpperCase().substring(0, 2) : 'UR';
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 font-sans text-slate-900">
        <Head :title="title" />

        <nav class="sticky top-0 z-50 w-full border-b border-slate-200 bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    
                    <div class="flex">
                        <div class="shrink-0 flex items-center gap-2">
                            <Link :href="route('dashboard')">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold shadow-sm">D</div>
                            </Link>
                            <span class="hidden md:block font-bold text-lg tracking-tight text-slate-800">DESA-Smart</span>
                        </div>

                        <div class="hidden space-x-2 sm:-my-px sm:ml-10 sm:flex sm:items-center">
                            <Button 
                                as-child 
                                variant="ghost" 
                                :class="route().current('dashboard') ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-600 hover:text-blue-600'"
                            >
                                <Link :href="route('dashboard')">
                                    <LayoutDashboard class="w-4 h-4 mr-2" />
                                    Dashboard
                                </Link>
                            </Button>
                            
                            <Button 
                                as-child 
                                variant="ghost" 
                                :class="route().current('profile.edit') ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-600 hover:text-blue-600'"
                            >
                                <Link :href="route('profile.edit')">
                                    <User class="w-4 h-4 mr-2" />
                                    Profil Warga
                                </Link>
                            </Button>
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="ghost" class="relative h-10 w-10 rounded-full hover:bg-slate-100 ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                    <Avatar class="h-9 w-9 border border-slate-200">
                                        <AvatarImage :src="`https://api.dicebear.com/7.x/initials/svg?seed=${$page.props.auth.user.name}`" alt="Avatar" />
                                        <AvatarFallback>{{ getInitials($page.props.auth.user.name) }}</AvatarFallback>
                                    </Avatar>
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent class="w-56" align="end">
                                <DropdownMenuLabel class="font-normal">
                                    <div class="flex flex-col space-y-1">
                                        <p class="text-sm font-medium leading-none truncate">{{ $page.props.auth.user.name }}</p>
                                        <p class="text-xs leading-none text-muted-foreground truncate">{{ $page.props.auth.user.email }}</p>
                                    </div>
                                </DropdownMenuLabel>
                                <DropdownMenuSeparator />
                                <DropdownMenuItem as-child>
                                    <Link :href="route('profile.edit')" class="w-full cursor-pointer flex items-center">
                                        <User class="mr-2 h-4 w-4" />
                                        Pengaturan Profil
                                    </Link>
                                </DropdownMenuItem>
                                <DropdownMenuSeparator />
                                <DropdownMenuItem @click="logout" class="text-red-600 cursor-pointer focus:text-red-600 flex items-center">
                                    <LogOut class="mr-2 h-4 w-4" />
                                    Keluar
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>

                    <div class="-mr-2 flex items-center sm:hidden">
                        <button
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center p-2 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-none focus:bg-slate-100 transition duration-150 ease-in-out"
                        >
                            <svg v-if="!showingNavigationDropdown" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg v-else class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div 
                class="sm:hidden border-b border-slate-200 bg-white" 
                :class="{ 'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown }"
            >
                <div class="pt-2 pb-3 space-y-1 px-4">
                    <Link 
                        :href="route('dashboard')" 
                        @click="showingNavigationDropdown = false"
                        class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-colors"
                        :class="route().current('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50'"
                    >
                        <LayoutDashboard class="w-5 h-5 mr-3" />
                        Dashboard
                    </Link>
                    <Link 
                        :href="route('profile.edit')" 
                        @click="showingNavigationDropdown = false"
                        class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-colors"
                        :class="route().current('profile.edit') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50'"
                    >
                        <User class="w-5 h-5 mr-3" />
                        Profil Saya
                    </Link>
                </div>

                <div class="pt-4 pb-4 border-t border-slate-100 px-6 bg-slate-50">
                    <div class="flex items-center mb-4">
                        <Avatar class="h-10 w-10 border border-slate-200 mr-3">
                            <AvatarFallback>{{ getInitials($page.props.auth.user.name) }}</AvatarFallback>
                        </Avatar>
                        <div class="overflow-hidden">
                            <div class="font-bold text-sm text-slate-800 truncate">{{ $page.props.auth.user.name }}</div>
                            <div class="text-xs text-slate-500 truncate">{{ $page.props.auth.user.email }}</div>
                        </div>
                    </div>
                    <Button variant="outline" class="w-full justify-center text-red-600 hover:text-red-700 hover:bg-red-50 border-red-200" @click="logout">
                        <LogOut class="w-4 h-4 mr-2" /> Keluar Aplikasi
                    </Button>
                </div>
            </div>
        </nav>

        <header class="bg-white border-b border-slate-200 shadow-sm" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <main>
            <slot />
        </main>
        
        <AiChatWidget />
    </div>
</template>