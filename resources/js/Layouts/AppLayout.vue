<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ChatWidget from '@/Components/ChatWidget.vue';

// Shadcn Components
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

const showingNavigationDropdown = ref(false);

// Helper untuk inisial nama (Misal: "Ulya Farhan" -> "UF")
const getInitials = (name) => {
    return name
        .split(' ')
        .map((word) => word[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="min-h-screen bg-slate-50/50">
        <Head :title="title" />

        <nav class="sticky top-0 z-50 w-full border-b border-slate-200 bg-white/80 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('dashboard')">
                                <ApplicationLogo class="block h-9 w-auto fill-current text-slate-900" />
                            </Link>
                        </div>

                        <div class="hidden space-x-2 sm:-my-px sm:ml-10 sm:flex sm:items-center">
                            <Button 
                                as-child 
                                variant="ghost" 
                                :class="{'bg-slate-100 text-slate-900': route().current('dashboard'), 'text-slate-600': !route().current('dashboard')}"
                            >
                                <Link :href="route('dashboard')">Dashboard</Link>
                            </Button>
                            
                            <Button 
                                as-child 
                                variant="ghost" 
                                :class="{'bg-slate-100 text-slate-900': route().current('profile.edit'), 'text-slate-600': !route().current('profile.edit')}"
                            >
                                <Link :href="route('profile.edit')">Profil Saya</Link>
                            </Button>
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="ghost" class="relative h-10 w-10 rounded-full">
                                    <Avatar class="h-9 w-9 border border-slate-200">
                                        <AvatarImage :src="`https://api.dicebear.com/7.x/initials/svg?seed=${$page.props.auth.user.name}`" alt="Avatar" />
                                        <AvatarFallback>{{ getInitials($page.props.auth.user.name) }}</AvatarFallback>
                                    </Avatar>
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent class="w-56" align="end">
                                <DropdownMenuLabel class="font-normal">
                                    <div class="flex flex-col space-y-1">
                                        <p class="text-sm font-medium leading-none">{{ $page.props.auth.user.name }}</p>
                                        <p class="text-xs leading-none text-muted-foreground">{{ $page.props.auth.user.email }}</p>
                                    </div>
                                </DropdownMenuLabel>
                                <DropdownMenuSeparator />
                                <DropdownMenuItem as-child>
                                    <Link :href="route('profile.edit')" class="w-full cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        Pengaturan Profil
                                    </Link>
                                </DropdownMenuItem>
                                <DropdownMenuSeparator />
                                <DropdownMenuItem @click="logout" class="text-red-600 cursor-pointer focus:text-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                                    Keluar (Logout)
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>

                    <div class="-mr-2 flex items-center sm:hidden">
                        <Button variant="ghost" size="icon" @click="showingNavigationDropdown = !showingNavigationDropdown">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </Button>
                    </div>
                </div>
            </div>

            <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="sm:hidden border-b border-slate-200 bg-white">
                <div class="pt-2 pb-3 space-y-1 px-4">
                    <Button as-child variant="ghost" class="w-full justify-start" :class="{'bg-slate-100': route().current('dashboard')}">
                        <Link :href="route('dashboard')">Dashboard</Link>
                    </Button>
                    <Button as-child variant="ghost" class="w-full justify-start" :class="{'bg-slate-100': route().current('profile.edit')}">
                        <Link :href="route('profile.edit')">Profil</Link>
                    </Button>
                </div>

                <div class="pt-4 pb-4 border-t border-slate-200 px-4">
                    <div class="flex items-center mb-3">
                        <Avatar class="h-10 w-10 border border-slate-200 mr-3">
                            <AvatarFallback>{{ getInitials($page.props.auth.user.name) }}</AvatarFallback>
                        </Avatar>
                        <div>
                            <div class="font-medium text-base text-slate-800">{{ $page.props.auth.user.name }}</div>
                            <div class="font-medium text-sm text-slate-500">{{ $page.props.auth.user.email }}</div>
                        </div>
                    </div>
                    <Button variant="outline" class="w-full justify-start text-red-600 hover:text-red-700 hover:bg-red-50 border-red-200" @click="logout">
                        Log Out
                    </Button>
                </div>
            </div>
        </nav>

        <header class="bg-white border-b border-slate-200" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <main>
            <slot />
        </main>
        <ChatWidget />
    </div>
</template>