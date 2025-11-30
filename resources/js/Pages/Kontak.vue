<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Textarea } from '@/Components/ui/textarea';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/Components/ui/card';
import { 
    Menu, X, MapPin, Phone, Mail, Clock, Send, 
    Facebook, Instagram, Twitter 
} from 'lucide-vue-next';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const isMenuOpen = ref(false);

const form = useForm({
    nama: '',
    email: '',
    subjek: '',
    pesan: '',
});

const submit = () => {
    // Simulasi pengiriman pesan
    console.log('Pesan dikirim:', form.data());
    alert('Terima kasih! Pesan Anda telah kami terima.');
    form.reset();
};
</script>

<template>
    <Head title="Hubungi Kami" />

    <div class="min-h-screen bg-slate-50 font-sans text-slate-900 selection:bg-brand-500 selection:text-white">
        
        <header class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100 transition-all duration-300">
            <nav class="mx-auto flex max-w-7xl items-center justify-between p-4 lg:px-8">
                <div class="flex lg:flex-1 items-center gap-2">
                    <Link :href="route('welcome')" class="flex items-center gap-2 group">
                        <div class="w-8 h-8 bg-brand-500 rounded-lg flex items-center justify-center text-white font-bold group-hover:bg-brand-600 transition-colors shadow-sm">D</div>
                        <span class="text-xl font-bold tracking-tight text-slate-900">DESA-Smart</span>
                    </Link>
                </div>
                
                <div class="flex lg:hidden">
                    <button type="button" class="-m-2.5 rounded-md p-2.5 text-slate-700 hover:bg-slate-100" @click="isMenuOpen = true">
                        <Menu class="h-6 w-6" />
                    </button>
                </div>

                <div class="hidden lg:flex lg:gap-x-12">
                    <Link :href="route('welcome')" class="text-sm font-semibold leading-6 text-slate-600 hover:text-brand-500 transition-colors">Beranda</Link>
                    <Link :href="route('panduan')" class="text-sm font-semibold leading-6 text-slate-600 hover:text-brand-500 transition-colors">Panduan</Link>
                    <Link :href="route('kontak')" class="text-sm font-bold leading-6 text-brand-500 transition-colors">Kontak</Link>
                </div>

                <div class="hidden lg:flex lg:flex-1 lg:justify-end lg:gap-x-4">
                    <template v-if="$page.props.auth.user">
                        <Link :href="route('dashboard')">
                            <Button class="bg-slate-900 hover:bg-slate-800 text-white font-semibold shadow-md">Dashboard Saya</Button>
                        </Link>
                    </template>
                    <template v-else>
                        <Link :href="route('login')"><Button variant="ghost" class="font-semibold">Masuk</Button></Link>
                        <Link :href="route('register')"><Button class="bg-brand-500 hover:bg-brand-600 text-white font-semibold shadow-lg">Daftar Akun</Button></Link>
                    </template>
                </div>
            </nav>

            <div v-if="isMenuOpen" class="lg:hidden relative z-50">
                <div class="fixed inset-0 bg-slate-900/20 backdrop-blur-sm" @click="isMenuOpen = false"></div>
                <div class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm border-l shadow-xl">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-lg font-bold text-slate-900">Menu</span>
                        <button @click="isMenuOpen = false"><X class="h-6 w-6" /></button>
                    </div>
                    <div class="flex flex-col gap-4">
                        <Link :href="route('welcome')" class="text-base font-medium text-slate-600">Beranda</Link>
                        <Link :href="route('panduan')" class="text-base font-medium text-slate-600">Panduan</Link>
                        <span class="text-base font-bold text-brand-500">Kontak</span>
                        <hr class="border-slate-100 my-2">
                        <template v-if="$page.props.auth.user">
                            <Link :href="route('dashboard')"><Button class="w-full bg-slate-900">Buka Dashboard</Button></Link>
                        </template>
                        <template v-else>
                            <Link :href="route('login')"><Button variant="outline" class="w-full border-slate-300">Masuk</Button></Link>
                            <Link :href="route('register')"><Button class="w-full bg-brand-500 text-white">Daftar Akun</Button></Link>
                        </template>
                    </div>
                </div>
            </div>
        </header>

        <main class="pt-32 pb-20">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl mb-4">
                        Hubungi Kami
                    </h1>
                    <p class="text-lg text-slate-600">
                        Punya pertanyaan, saran, atau kendala layanan? Tim Pemerintah Desa Smart Digital siap membantu Anda.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-24">
                    
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-6">Informasi Kantor</h3>
                            <div class="space-y-6">
                                <div class="flex items-start gap-4">
                                    <div class="p-3 bg-brand-100 rounded-lg text-brand-600">
                                        <MapPin class="h-6 w-6" />
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-slate-900">Alamat Kantor</h4>
                                        <p class="text-slate-600 mt-1">
                                            Jl. Raya Desa No. 1, Kecamatan Maju Jaya,<br>
                                            Kabupaten Contoh, 12345
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div class="p-3 bg-blue-100 rounded-lg text-blue-600">
                                        <Mail class="h-6 w-6" />
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-slate-900">Email & Telepon</h4>
                                        <p class="text-slate-600 mt-1">admin@desasmart.id</p>
                                        <p class="text-slate-600">(021) 1234-5678</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div class="p-3 bg-green-100 rounded-lg text-green-600">
                                        <Clock class="h-6 w-6" />
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-slate-900">Jam Operasional</h4>
                                        <p class="text-slate-600 mt-1">Senin - Jumat: 08:00 - 15:00 WIB</p>
                                        <p class="text-slate-600">Sabtu - Minggu: Libur</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl overflow-hidden shadow-md border border-slate-200 h-64 bg-slate-200 relative group">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.666427009756!2d106.82496431476886!3d-6.175110395529962!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d2e764587d%3A0x649e330b69c969a7!2sMonumen%20Nasional!5e0!3m2!1sid!2sid!4v1629857945037!5m2!1sid!2sid" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy"
                                class="grayscale group-hover:grayscale-0 transition-all duration-500"
                            ></iframe>
                        </div>

                        <div class="flex gap-4">
                            <a href="#" class="p-2 bg-white border border-slate-200 rounded-full hover:bg-brand-50 hover:text-brand-600 transition-colors">
                                <Facebook class="h-5 w-5" />
                            </a>
                            <a href="#" class="p-2 bg-white border border-slate-200 rounded-full hover:bg-brand-50 hover:text-brand-600 transition-colors">
                                <Instagram class="h-5 w-5" />
                            </a>
                            <a href="#" class="p-2 bg-white border border-slate-200 rounded-full hover:bg-brand-50 hover:text-brand-600 transition-colors">
                                <Twitter class="h-5 w-5" />
                            </a>
                        </div>
                    </div>

                    <Card class="border-slate-200 shadow-xl shadow-slate-200/50">
                        <CardHeader>
                            <CardTitle>Kirim Pesan</CardTitle>
                            <CardDescription>Kami akan membalas pesan Anda melalui email sesegera mungkin.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <form @submit.prevent="submit" class="space-y-6">
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div class="space-y-2">
                                        <label for="nama" class="text-sm font-medium text-slate-700">Nama Lengkap</label>
                                        <Input id="nama" v-model="form.nama" placeholder="Nama Anda" required />
                                    </div>
                                    <div class="space-y-2">
                                        <label for="email" class="text-sm font-medium text-slate-700">Alamat Email</label>
                                        <Input id="email" type="email" v-model="form.email" placeholder="email@contoh.com" required />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="subjek" class="text-sm font-medium text-slate-700">Subjek</label>
                                    <Input id="subjek" v-model="form.subjek" placeholder="Perihal pesan..." required />
                                </div>

                                <div class="space-y-2">
                                    <label for="pesan" class="text-sm font-medium text-slate-700">Pesan</label>
                                    <Textarea id="pesan" v-model="form.pesan" placeholder="Tuliskan detail pertanyaan atau laporan Anda..." rows="5" required />
                                </div>

                                <Button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white" :disabled="form.processing">
                                    <Send class="w-4 h-4 mr-2" /> Kirim Pesan
                                </Button>
                            </form>
                        </CardContent>
                    </Card>

                </div>
            </div>
        </main>

        <footer class="bg-white border-t border-slate-100 py-12 mt-12">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
                <p class="text-slate-500 text-sm">
                    &copy; 2025 Pemerintah Desa Smart Digital. Dibuat dengan ❤️ untuk warga.
                </p>
            </div>
        </footer>
    </div>
</template>