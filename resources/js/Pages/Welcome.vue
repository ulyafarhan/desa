<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
// Import Komponen Shadcn
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Badge } from '@/Components/ui/badge';
import ChatWidget from '@/Components/ChatWidget.vue';
// Import Ikon
import { 
    Menu, X, ArrowRight, CheckCircle2, 
    TrendingUp, Activity, FileText, Users 
} from 'lucide-vue-next';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const isMenuOpen = ref(false);
</script>

<template>
    <Head title="Pelayanan Desa Digital" />

    <div class="min-h-screen bg-white font-sans text-slate-900">
        
        <header class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
            <nav class="mx-auto flex max-w-7xl items-center justify-between p-4 lg:px-8" aria-label="Global">
                <div class="flex lg:flex-1 items-center gap-2">
                    <div class="w-8 h-8 bg-brand-500 rounded-lg flex items-center justify-center text-white font-bold">D</div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">DESA-Smart</span>
                </div>
                
                <div class="flex lg:hidden">
                    <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-slate-700" @click="isMenuOpen = true">
                        <span class="sr-only">Buka menu</span>
                        <Menu class="h-6 w-6" aria-hidden="true" />
                    </button>
                </div>

                <div class="hidden lg:flex lg:gap-x-12">
                    <a href="#fitur" class="text-sm font-semibold leading-6 text-slate-600 hover:text-brand-500 transition-colors">Layanan</a>
                    <a href="/panduan" class="text-sm font-semibold leading-6 text-slate-600 hover:text-brand-500 transition-colors">Panduan</a>
                    <a href="/kontak" class="text-sm font-semibold leading-6 text-slate-600 hover:text-brand-500 transition-colors">Kontak</a>
                </div>

                <div class="hidden lg:flex lg:flex-1 lg:justify-end lg:gap-x-4">
                    <template v-if="$page.props.auth.user">
                        <Link :href="route('dashboard')">
                            <Button class="bg-slate-900 hover:bg-slate-800 text-white font-semibold shadow-md">
                                Dashboard Saya
                            </Button>
                        </Link>
                    </template>
                    <template v-else>
                        <Link :href="route('login')">
                            <Button variant="ghost" class="font-semibold">Masuk</Button>
                        </Link>
                        <Link :href="route('register')">
                            <Button class="bg-brand-500 hover:bg-brand-600 text-white font-semibold shadow-lg shadow-brand-500/30">
                                Daftar Akun
                            </Button>
                        </Link>
                    </template>
                </div>
            </nav>

            <div v-if="isMenuOpen" class="lg:hidden" role="dialog" aria-modal="true">
                <div class="fixed inset-0 z-50 bg-black/20 backdrop-blur-sm" @click="isMenuOpen = false"></div>
                <div class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm border-l">
                    <div class="flex items-center justify-between">
                        <span class="text-xl font-bold">Menu</span>
                        <button type="button" class="-m-2.5 rounded-md p-2.5 text-slate-700" @click="isMenuOpen = false">
                            <span class="sr-only">Tutup menu</span>
                            <X class="h-6 w-6" aria-hidden="true" />
                        </button>
                    </div>
                    <div class="mt-6 flow-root">
                        <div class="-my-6 divide-y divide-slate-100">
                            <div class="space-y-2 py-6">
                                <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-slate-900 hover:bg-slate-50">Layanan</a>
                                <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-slate-900 hover:bg-slate-50">Panduan</a>
                            </div>
                            <div class="py-6 flex flex-col gap-2">
                                <template v-if="$page.props.auth.user">
                                    <Link :href="route('dashboard')">
                                        <Button class="w-full bg-slate-900 hover:bg-slate-800 text-white">Buka Dashboard</Button>
                                    </Link>
                                </template>
                                <template v-else>
                                    <Link :href="route('login')"><Button variant="outline" class="w-full">Masuk</Button></Link>
                                    <Link :href="route('register')"><Button class="w-full bg-brand-500 hover:bg-brand-600">Daftar Akun</Button></Link>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <div class="relative isolate pt-24 lg:pt-32 pb-16 overflow-hidden lg:mt-8 xs:mt-0">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                        
                        <div class="max-w-2xl">
                            <Badge variant="outline" class="mb-6 px-4 py-1 text-sm border-brand-200 bg-brand-50 text-brand-700 rounded-full">
                                Pelayanan Desa Digital Terpadu
                            </Badge>
                            
                            <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-6xl lg:leading-[1.1] mb-6">
                                Urus Administrasi <br />
                                <span class="text-brand-500">Lebih Cepat & Mudah</span>
                            </h1>
                            
                            <p class="text-lg leading-8 text-slate-600 mb-8">
                                Tidak perlu lagi antri di kantor desa. Ajukan surat keterangan, SKCK, dan administrasi lainnya langsung dari rumah Anda melalui DESA-Smart.
                            </p>
                            
                            <div class="flex flex-col sm:flex-row gap-4">
                                <template v-if="$page.props.auth.user">
                                    <Link :href="route('dashboard')">
                                        <Button size="lg" class="h-12 px-8 text-base bg-brand-500 hover:bg-brand-600 shadow-xl shadow-brand-500/20 w-full sm:w-auto">
                                            Buka Dashboard Saya <ArrowRight class="ml-2 h-4 w-4" />
                                        </Button>
                                    </Link>
                                </template>
                                <template v-else>
                                    <Link :href="route('register')">
                                        <Button size="lg" class="h-12 px-8 text-base bg-brand-500 hover:bg-brand-600 shadow-xl shadow-brand-500/20 w-full sm:w-auto">
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

                            <div class="mt-10 flex items-center gap-x-4">
                                <div class="flex -space-x-3">
                                    <Avatar class="border-2 border-white w-10 h-10"><AvatarImage src="https://i.pravatar.cc/100?img=1" /><AvatarFallback>W1</AvatarFallback></Avatar>
                                    <Avatar class="border-2 border-white w-10 h-10"><AvatarImage src="https://i.pravatar.cc/100?img=2" /><AvatarFallback>W2</AvatarFallback></Avatar>
                                    <Avatar class="border-2 border-white w-10 h-10"><AvatarImage src="https://i.pravatar.cc/100?img=3" /><AvatarFallback>W3</AvatarFallback></Avatar>
                                    <Avatar class="border-2 border-white w-10 h-10 bg-slate-100 text-xs font-medium text-slate-600 flex items-center justify-center">+500</Avatar>
                                </div>
                                <div class="text-sm leading-6 text-slate-600">
                                    Warga telah bergabung
                                </div>
                            </div>
                        </div>

                        <div class="relative h-[450px] w-full hidden lg:block">
                            <div class="absolute top-10 right-10 w-96 h-96 bg-brand-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
                            
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
                                        <div class="w-full bg-brand-100 rounded-t-sm h-[40%]"></div>
                                        <div class="w-full bg-brand-500 rounded-t-sm h-[70%]"></div>
                                        <div class="w-full bg-brand-200 rounded-t-sm h-[50%]"></div>
                                        <div class="w-full bg-brand-100 rounded-t-sm h-[30%]"></div>
                                        <div class="w-full bg-brand-300 rounded-t-sm h-[60%]"></div>
                                        <div class="w-full bg-brand-400 rounded-t-sm h-[80%]"></div>
                                        <div class="w-full bg-brand-500 rounded-t-sm h-[90%]"></div>
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
                                        <div class="p-2 bg-brand-500 rounded-full"><FileText class="h-5 w-5 text-white" /></div>
                                        <div>
                                            <p class="text-sm font-semibold">Surat Keterangan Domisili</p>
                                            <p class="text-xs text-slate-400">Diajukan: 24 Nov 2025</p>
                                        </div>
                                    </div>
                                    <div class="absolute bottom-6 right-6">
                                        <Button size="icon" class="rounded-full bg-brand-500 hover:bg-brand-400 text-white shadow-lg h-12 w-12">
                                            <ArrowRight class="h-5 w-5" />
                                        </Button>
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
                                <div class="w-12 h-12 bg-brand-100 rounded-xl flex items-center justify-center mb-4">
                                    <FileText class="h-6 w-6 text-brand-600" />
                                </div>
                                <CardTitle>Pengajuan Mandiri</CardTitle>
                            </CardHeader>
                            <CardContent class="text-slate-600">
                                Buat surat pengantar KTP, KK, SKCK, dan surat keterangan lainnya langsung dari dashboard pribadi Anda.
                            </CardContent>
                        </Card>

                        <Card class="border-none shadow-sm hover:shadow-xl transition-all duration-300">
                            <CardHeader>
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                                    <Users class="h-6 w-6 text-blue-600" />
                                </div>
                                <CardTitle>Asisten Cerdas (SiDesa)</CardTitle>
                            </CardHeader>
                            <CardContent class="text-slate-600">
                                Bingung syarat surat? Tanya langsung ke Chatbot AI kami yang siap membantu 24 jam non-stop.
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
        </main>
    </div>
    <ChatWidget />
</template>

<style scoped>
/* Animasi blob background */
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