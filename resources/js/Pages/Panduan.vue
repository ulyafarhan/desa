<script setup>
import { Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Button } from '@/Components/ui/button';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/Components/ui/accordion';
import { 
    FileText, CreditCard, User, Home, Briefcase, 
    BookOpen, ArrowRight, MessageCircle 
} from 'lucide-vue-next';

// Terima props dari Laravel
defineProps({
    dynamicGuides: Array, // Data dari database
});

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
    <PublicLayout title="Panduan Layanan">
        
        <div class="py-16 sm:py-24">
            <div class="max-w-3xl mx-auto px-6 lg:px-8">
                
                <div class="text-center mb-12">
                    <div class="inline-flex items-center justify-center p-3 bg-blue-100 rounded-xl mb-4 shadow-sm ring-1 ring-blue-50">
                        <BookOpen class="w-8 h-8 text-blue-600" />
                    </div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl mb-4">
                        Panduan Layanan & Administrasi
                    </h1>
                    <p class="text-lg text-slate-600 leading-relaxed">
                        Informasi lengkap mengenai syarat, alur, dan prosedur pengurusan dokumen kependudukan di Desa Smart Digital.
                    </p>
                </div>

                <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-100 p-6 md:p-8">
                    
                    <div v-if="!dynamicGuides || dynamicGuides.length === 0" class="text-center py-12">
                        <div class="bg-slate-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <FileText class="w-8 h-8 text-slate-300" />
                        </div>
                        <h3 class="text-lg font-medium text-slate-900">Belum ada panduan</h3>
                        <p class="text-slate-500 mt-1">Panduan layanan belum ditambahkan oleh admin.</p>
                    </div>

                    <Accordion v-else type="single" collapsible class="w-full space-y-2">
                        <AccordionItem 
                            v-for="guide in dynamicGuides" 
                            :key="guide.id" 
                            :value="'item-' + guide.id"
                            class="border border-slate-100 rounded-lg px-2 data-[state=open]:bg-blue-50/50 data-[state=open]:border-blue-100 transition-all duration-200"
                        >
                            <AccordionTrigger class="text-left font-semibold text-slate-800 hover:text-blue-600 hover:no-underline py-4 px-2 text-base group transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="p-2 bg-slate-100 rounded-lg group-hover:bg-blue-100 transition-colors">
                                        <component :is="getIcon(guide.icon)" class="w-5 h-5 text-slate-500 group-hover:text-blue-600" />
                                    </div>
                                    <span>{{ guide.judul }}</span>
                                </div>
                            </AccordionTrigger>
                            <AccordionContent class="text-slate-600 leading-relaxed pb-4 px-4 pl-[4.5rem]">
                                <ol class="relative border-l border-slate-200 space-y-4 ml-1 mt-2">
                                    <li v-for="(step, index) in guide.langkah_langkah" :key="index" class="ml-4">
                                        <div class="absolute w-2 h-2 bg-blue-400 rounded-full mt-2 -left-1"></div>
                                        <span class="text-slate-700">{{ step.isi || step }}</span>
                                    </li>
                                </ol>

                                <div class="mt-6 pt-4 border-t border-slate-100/50 flex justify-end">
                                    <Link :href="$page.props.auth.user ? route('dashboard') : route('login')">
                                        <Button size="sm" class="bg-blue-600 hover:bg-blue-700 text-white shadow-sm hover:shadow">
                                            Ajukan Surat Ini <ArrowRight class="w-3 h-3 ml-2" />
                                        </Button>
                                    </Link>
                                </div>
                            </AccordionContent>
                        </AccordionItem>
                    </Accordion>
                </div>

                <div class="mt-12 text-center bg-white border border-slate-200 rounded-2xl p-8 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Masih bingung dengan prosedurnya?</h3>
                    <p class="text-slate-600 mb-6">Tanyakan langsung kepada asisten virtual kami yang siap membantu Anda 24/7.</p>
                    
                    <button 
                        type="button" 
                        class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-full text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors duration-200 gap-2"
                        onclick="document.querySelector('.fixed.bottom-5.right-5 button').click()"
                    >
                        <MessageCircle class="w-5 h-5" />
                        Tanya SiDesa Sekarang
                    </button>
                </div>

            </div>
        </div>
    </PublicLayout>
</template>