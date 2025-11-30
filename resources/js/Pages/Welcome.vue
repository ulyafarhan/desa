<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Button } from '@/Components/ui/button';
import { Card, CardHeader, CardTitle, CardContent } from '@/Components/ui/card';
import { Badge } from '@/Components/ui/badge';
import { Separator } from '@/Components/ui/separator';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

const features = [
    {
        title: 'Pengajuan Online',
        desc: 'Ajukan surat keterangan domisili, usaha, dan lainnya langsung dari rumah tanpa antri.',
        icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
    },
    {
        title: 'SiDesa AI Assistant',
        desc: 'Tanya jawab seputar syarat dan prosedur layanan desa 24 jam dengan kecerdasan buatan.',
        icon: 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'
    },
    {
        title: 'Tanda Tangan Digital',
        desc: 'Dokumen sah dengan Tanda Tangan Elektronik (TTE) dan QR Code yang dapat diverifikasi.',
        icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
    },
    {
        title: 'Tracking Realtime',
        desc: 'Pantau status permohonan Anda mulai dari verifikasi hingga dokumen siap unduh.',
        icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
    }
];
</script>

<template>
    <Head title="Pelayanan Desa Digital" />

    <div class="min-h-screen bg-slate-50 text-slate-900 selection:bg-slate-900 selection:text-white">
        
        <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center gap-2">
                        <ApplicationLogo class="h-8 w-8 text-slate-900" />
                        <span class="font-bold text-xl tracking-tight text-slate-900">DESA-Smart</span>
                    </div>

                    <div v-if="canLogin" class="flex items-center gap-4">
                        <template v-if="$page.props.auth.user">
                            <Link :href="route('dashboard')">
                                <Button>Dashboard</Button>
                            </Link>
                        </template>
                        <template v-else>
                            <Link :href="route('login')">
                                <Button variant="ghost" class="font-semibold text-slate-600 hover:text-slate-900">Masuk</Button>
                            </Link>
                            <Link v-if="canRegister" :href="route('register')">
                                <Button>Daftar Sekarang</Button>
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <header class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <Badge variant="outline" class="mb-6 py-1.5 px-4 text-sm bg-white/50 backdrop-blur border-slate-300 text-slate-600">
                    ✨ Transformasi Digital Pelayanan Publik
                </Badge>
                
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 mb-6">
                    Urus Surat Desa <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-slate-700 to-slate-500">
                        Cukup dari Rumah
                    </span>
                </h1>
                
                <p class="mt-4 text-xl text-slate-600 max-w-2xl mx-auto mb-10 leading-relaxed">
                    Sistem pelayanan administrasi mandiri terpadu. 
                    Lebih cepat, transparan, dan efisien dengan dukungan teknologi cerdas.
                </p>

                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <Link :href="route('register')">
                        <Button size="lg" class="w-full sm:w-auto h-12 px-8 text-base">
                            Buat Akun Warga
                        </Button>
                    </Link>
                    <Link :href="route('login')">
                        <Button size="lg" variant="outline" class="w-full sm:w-auto h-12 px-8 text-base bg-white">
                            Masuk Aplikasi
                        </Button>
                    </Link>
                </div>
            </div>

            <div class="absolute top-0 left-0 w-full h-full -z-10 opacity-30">
                <div class="absolute right-0 top-0 bg-gradient-to-b from-slate-200 to-transparent w-1/2 h-full blur-3xl"></div>
                <div class="absolute left-0 bottom-0 bg-gradient-to-t from-slate-200 to-transparent w-1/2 h-full blur-3xl"></div>
            </div>
        </header>

        <section class="py-20 bg-white border-y border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-slate-900">Kenapa Menggunakan DESA-Smart?</h2>
                    <p class="mt-4 text-lg text-slate-600">Solusi modern untuk kebutuhan administrasi warga.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <Card v-for="(feature, index) in features" :key="index" class="border-slate-200 hover:shadow-lg transition-all duration-300 group">
                        <CardHeader>
                            <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-slate-900 transition-colors duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-700 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feature.icon" />
                                </svg>
                            </div>
                            <CardTitle class="text-xl">{{ feature.title }}</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p class="text-slate-600 leading-relaxed">
                                {{ feature.desc }}
                            </p>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </section>

        <section class="py-20 bg-slate-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-slate-800">
                    <div class="p-4">
                        <div class="text-4xl font-bold mb-2">24/7</div>
                        <div class="text-slate-400">Layanan Online</div>
                    </div>
                    <div class="p-4">
                        <div class="text-4xl font-bold mb-2">< 5 Menit</div>
                        <div class="text-slate-400">Waktu Pengajuan</div>
                    </div>
                    <div class="p-4">
                        <div class="text-4xl font-bold mb-2">100%</div>
                        <div class="text-slate-400">Transparansi Proses</div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="bg-white border-t border-slate-200 pt-16 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center gap-2 mb-4">
                            <ApplicationLogo class="h-8 w-8 text-slate-900" />
                            <span class="font-bold text-xl">DESA-Smart</span>
                        </div>
                        <p class="text-slate-600 max-w-sm">
                            Platform digital untuk mempermudah administrasi dan pelayanan publik di tingkat desa. Mewujudkan desa yang transparan, akuntabel, dan modern.
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-slate-900 mb-4">Layanan</h4>
                        <ul class="space-y-2 text-sm text-slate-600">
                            <li><a href="#" class="hover:text-slate-900">Surat Keterangan</a></li>
                            <li><a href="#" class="hover:text-slate-900">Pengantar SKCK</a></li>
                            <li><a href="#" class="hover:text-slate-900">Izin Usaha</a></li>
                            <li><a href="#" class="hover:text-slate-900">Konsultasi AI</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-bold text-slate-900 mb-4">Kontak</h4>
                        <ul class="space-y-2 text-sm text-slate-600">
                            <li>Kantor Kepala Desa</li>
                            <li>Jl. Raya Desa No. 1</li>
                            <li>(021) 1234-5678</li>
                            <li>admin@desasmart.id</li>
                        </ul>
                    </div>
                </div>
                
                <Separator class="mb-8" />
                
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-500">
                    <p>&copy; {{ new Date().getFullYear() }} Pemerintah Desa Smart Digital. All rights reserved.</p>
                    <p>Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})</p>
                </div>
            </div>
        </footer>
    </div>
</template>