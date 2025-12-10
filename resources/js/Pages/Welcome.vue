<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

// Import Komponen Shadcn
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Badge } from '@/Components/ui/badge';

// Import Ikon Lucide (Menu & X sudah dihapus karena ada di Layout)
import { 
    ArrowRight, CheckCircle2, 
    TrendingUp, Activity, FileText, Users 
} from 'lucide-vue-next';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <PublicLayout title="Pelayanan Desa Digital">
        
        <div class="relative isolate pt-14 lg:pt-20 pb-16 overflow-hidden">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                    
                    <div class="max-w-2xl">
                        <Badge variant="outline" class="mb-6 px-4 py-1 text-sm border-blue-200 bg-blue-50 text-blue-700 rounded-full">
                            Pelayanan Desa Digital Terpadu
                        </Badge>
                        
                        <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-6xl lg:leading-[1.1] mb-6">
                            Urus Administrasi <br />
                            <span class="text-blue-600">Lebih Cepat & Mudah</span>
                        </h1>
                        
                        <p class="text-lg leading-8 text-slate-600 mb-8">
                            Tidak perlu lagi antri di kantor desa. Ajukan surat keterangan, SKCK, dan administrasi lainnya langsung dari rumah Anda melalui DESA-Smart.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            <template v-if="$page.props.auth.user">
                                <Link :href="route('dashboard')">
                                    <Button size="lg" class="h-12 px-8 text-base bg-blue-600 hover:bg-blue-700 shadow-xl shadow-blue-500/20 w-full sm:w-auto">
                                        Buka Dashboard Saya <ArrowRight class="ml-2 h-4 w-4" />
                                    </Button>
                                </Link>
                            </template>
                            <template v-else>
                                <Link :href="route('register')">
                                    <Button size="lg" class="h-12 px-8 text-base bg-blue-600 hover:bg-blue-700 shadow-xl shadow-blue-500/20 w-full sm:w-auto">
                                        Mulai Sekarang <ArrowRight class="ml-2 h-4 w-4" />
                                    </Button>
                                </Link>
                                <Link :href="route('login')">
                                    <Button size="lg" variant="outline" class="h-12 px-8 text-base border-slate-300 w-full sm:w-auto">
                                        Masuk Aplikasi
                                    </Button>
                                </Link>
                            </template>
                        </div>
                    </div>

                    <div class="relative h-[450px] w-full hidden lg:block">
                        <div class="absolute top-10 right-10 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
                        
                        <Card class="absolute top-0 right-0 w-[80%] h-[280px] bg-white shadow-2xl border-slate-100 rounded-2xl z-10 transform translate-x-8 -translate-y-8">
                            <CardHeader class="pb-2">
                                <div class="flex justify-between items-center">
                                    <CardTitle class="text-base font-medium text-slate-500">Pengajuan Minggu Ini</CardTitle>
                                    <div class="p-2 bg-slate-50 rounded-full"><Activity class="h-4 w-4 text-slate-400"/></div>
                                </div>
                                <div class="text-4xl font-bold text-slate-900">128+ Pengajuan</div>
                            </CardHeader>
                            <CardContent>
                                <div class="flex items-end justify-between h-32 gap-2 mt-4">
                                    <div class="w-full bg-blue-100 rounded-t-sm h-[40%]"></div>
                                    <div class="w-full bg-blue-500 rounded-t-sm h-[70%]"></div>
                                    <div class="w-full bg-blue-200 rounded-t-sm h-[50%]"></div>
                                    <div class="w-full bg-blue-100 rounded-t-sm h-[30%]"></div>
                                    <div class="w-full bg-blue-300 rounded-t-sm h-[60%]"></div>
                                    <div class="w-full bg-blue-400 rounded-t-sm h-[80%]"></div>
                                    <div class="w-full bg-blue-500 rounded-t-sm h-[90%]"></div>
                                </div>
                            </CardContent>
                        </Card>

                        <Card class="absolute bottom-10 left-0 w-[85%] h-[220px] bg-slate-900 text-white shadow-2xl border-none rounded-2xl z-20 overflow-hidden">
                            <CardHeader>
                                <div class="flex justify-between items-start">
                                    <div>
                                        <CardTitle class="text-lg font-medium text-slate-300">Status Permohonan</CardTitle>
                                        <div class="mt-2 text-3xl font-bold">Siap Diunduh</div>
                                    </div>
                                    <div class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                                        <TrendingUp class="h-3 w-3" /> Selesai
                                    </div>
                                </div>
                            </CardHeader>
                            <CardContent class="mt-4 space-y-4">
                                <div class="flex items-center gap-4 bg-white/5 p-3 rounded-lg border border-white/10">
                                    <div class="p-2 bg-blue-500 rounded-full"><FileText class="h-5 w-5 text-white" /></div>
                                    <div>
                                        <p class="text-sm font-semibold">Surat Keterangan Domisili</p>
                                        <p class="text-xs text-slate-400">Diajukan: 24 Nov 2025</p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </div>

        <section id="fitur" class="py-24 bg-slate-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Layanan Serba Digital</h2>
                    <p class="mt-4 text-lg text-slate-600">Kami menghadirkan fitur-fitur terbaik untuk memudahkan kebutuhan administrasi Anda.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <Card class="border-none shadow-sm hover:shadow-xl transition-all duration-300">
                        <CardHeader>
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                                <FileText class="h-6 w-6 text-blue-600" />
                            </div>
                            <CardTitle>Pengajuan Mandiri</CardTitle>
                        </CardHeader>
                        <CardContent class="text-slate-600">
                            Buat surat pengantar KTP, KK, SKCK, dan surat keterangan lainnya langsung dari dashboard pribadi Anda.
                        </CardContent>
                    </Card>

                    <Card class="border-none shadow-sm hover:shadow-xl transition-all duration-300">
                        <CardHeader>
                            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                                <Users class="h-6 w-6 text-indigo-600" />
                            </div>
                            <CardTitle>Data Terintegrasi</CardTitle>
                        </CardHeader>
                        <CardContent class="text-slate-600">
                            Data kependudukan Anda aman dan terpusat, memudahkan proses verifikasi tanpa perlu input ulang berkali-kali.
                        </CardContent>
                    </Card>

                    <Card class="border-none shadow-sm hover:shadow-xl transition-all duration-300">
                        <CardHeader>
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                                <CheckCircle2 class="h-6 w-6 text-green-600" />
                            </div>
                            <CardTitle>Verifikasi Cepat</CardTitle>
                        </CardHeader>
                        <CardContent class="text-slate-600">
                            Proses persetujuan oleh staf desa dilakukan secara realtime. Dapatkan notifikasi saat dokumen selesai.
                        </CardContent>
                    </Card>
                </div>
            </div>
        </section>

        <footer class="bg-white border-t border-slate-100 py-12">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
                <p class="text-slate-500 text-sm">
                    &copy; 2025 Pemerintah Desa Smart Digital. Dibuat dengan ❤️ untuk warga.
                </p>
            </div>
        </footer>

    </PublicLayout>
</template>

<style scoped>
@keyframes blob {
  0% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
  100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
  animation: blob 7s infinite;
}
</style>