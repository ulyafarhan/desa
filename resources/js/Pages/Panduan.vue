<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/Components/ui/button';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/Components/ui/accordion';
import { 
    Menu, X, ArrowLeft, BookOpen, 
    FileText, CreditCard, User, Home, Briefcase, ArrowRight 
} from 'lucide-vue-next';

// Terima props dari Laravel
defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    dynamicGuides: Array, // Data dari database
});

const isMenuOpen = ref(false);

// Helper untuk mapping string icon dari DB ke Komponen Lucide
const getIcon = (name) => {
    const icons = {
        'FileText': FileText,
        'CreditCard': CreditCard,
        'User': User,
        'Home': Home,
        'Briefcase': Briefcase
    };
    return icons[name] || FileText;
};
</script>

<template>
    <Head title="Panduan Layanan" />

    <div class="min-h-screen bg-slate-50 font-sans text-slate-900">
        
        <header class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
            <nav class="mx-auto flex max-w-7xl items-center justify-between p-4 lg:px-8">
                <div class="flex lg:flex-1 items-center gap-2">
                    <Link :href="route('welcome')" class="flex items-center gap-2 group">
                        <div class="w-8 h-8 bg-brand-500 rounded-lg flex items-center justify-center text-white font-bold group-hover:bg-brand-600 transition-colors">D</div>
                        <span class="text-xl font-bold tracking-tight text-slate-900">DESA-Smart</span>
                    </Link>
                </div>
                
                <div class="hidden lg:flex lg:gap-x-12">
                    <Link :href="route('welcome')" class="text-sm font-semibold leading-6 text-slate-600 hover:text-brand-500">Beranda</Link>
                    <span class="text-sm font-bold leading-6 text-brand-500">Panduan</span>
                    <Link :href="'/kontak'" title="Kontak" class="text-sm font-semibold leading-6 text-slate-600 hover:text-brand-500">Kontak</Link>
                </div>

                <div class="hidden lg:flex lg:flex-1 lg:justify-end gap-4">
                    <template v-if="$page.props.auth.user">
                        <Link :href="route('dashboard')"><Button class="bg-slate-900 text-white">Dashboard</Button></Link>
                    </template>
                    <template v-else>
                        <Link :href="route('login')"><Button variant="ghost">Masuk</Button></Link>
                        <Link :href="route('register')"><Button class="bg-brand-500 hover:bg-brand-600 shadow-md">Daftar</Button></Link>
                    </template>
                </div>

                <div class="flex lg:hidden">
                    <button type="button" class="-m-2.5 rounded-md p-2.5 text-slate-700" @click="isMenuOpen = true">
                        <Menu class="h-6 w-6" />
                    </button>
                </div>
            </nav>

            <div v-if="isMenuOpen" class="lg:hidden" role="dialog" aria-modal="true">
                <div class="fixed inset-0 z-50 bg-black/20 backdrop-blur-sm" @click="isMenuOpen = false"></div>
                <div class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm border-l">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-xl font-bold">Menu</span>
                        <button @click="isMenuOpen = false"><X class="h-6 w-6" /></button>
                    </div>
                    <div class="flex flex-col gap-4">
                        <Link :href="route('welcome')" class="text-lg font-medium">Beranda</Link>
                        <span class="text-lg font-bold text-brand-500">Panduan</span>
                        <hr class="border-slate-100 my-2">
                        <template v-if="$page.props.auth.user">
                            <Link :href="route('dashboard')"><Button class="w-full">Dashboard</Button></Link>
                        </template>
                        <template v-else>
                            <Link :href="route('login')"><Button variant="outline" class="w-full">Masuk</Button></Link>
                        </template>
                    </div>
                </div>
            </div>
        </header>

        <main class="pt-32 pb-20">
            <div class="max-w-3xl mx-auto px-6 lg:px-8">
                
                <div class="text-center mb-12">
                    <div class="inline-flex items-center justify-center p-3 bg-brand-100 rounded-xl mb-4 shadow-sm">
                        <BookOpen class="w-8 h-8 text-brand-600" />
                    </div>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl mb-4">
                        Panduan Layanan & Administrasi
                    </h1>
                    <p class="text-lg text-slate-600">
                        Informasi lengkap mengenai syarat, alur, dan prosedur pengurusan dokumen kependudukan.
                    </p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
                    
                    <div v-if="dynamicGuides.length === 0" class="text-center py-10 text-slate-500">
                        Belum ada panduan yang ditambahkan oleh admin.
                    </div>

                    <Accordion type="single" collapsible class="w-full">
                        <AccordionItem v-for="guide in dynamicGuides" :key="guide.id" :value="'item-' + guide.id">
                            <AccordionTrigger class="text-left font-semibold text-slate-800 hover:text-brand-600 hover:no-underline py-4 text-base group">
                                <div class="flex items-center gap-3">
                                    <component :is="getIcon(guide.icon)" class="w-5 h-5 text-slate-400 group-hover:text-brand-500 transition-colors" />
                                    {{ guide.judul }}
                                </div>
                            </AccordionTrigger>
                            <AccordionContent class="text-slate-600 leading-relaxed pb-4 pl-8">
                                <ol class="list-decimal space-y-2 ml-4 marker:text-slate-400 marker:font-medium">
                                    <li v-for="(step, index) in guide.langkah_langkah" :key="index">
                                        {{ step.isi }}
                                    </li>
                                </ol>
                                <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end">
                                    <Link :href="$page.props.auth.user ? route('dashboard') : route('login')">
                                        <Button size="sm" variant="outline" class="text-brand-600 border-brand-200 hover:bg-brand-50">
                                            Ajukan sekarang <ArrowRight class="w-3 h-3 ml-2" />
                                        </Button>
                                    </Link>
                                </div>
                            </AccordionContent>
                        </AccordionItem>
                    </Accordion>
                </div>

                <div class="mt-12 text-center">
                    <p class="text-slate-600 mb-4">Masih bingung dengan prosedurnya?</p>
                    <Link :href="route('dashboard')">
                        <Button variant="secondary" class="gap-2 shadow-sm bg-white border border-slate-200 hover:bg-slate-50">
                            Tanya SiDesa (AI Chatbot) <ArrowRight class="w-4 h-4" />
                        </Button>
                    </Link>
                </div>

            </div>
        </main>

        <footer class="bg-white border-t border-slate-100 py-8 text-center text-slate-500 text-sm">
            &copy; 2025 Pemerintah Desa Smart Digital.
        </footer>
    </div>
</template>