<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Textarea } from '@/Components/ui/textarea';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/Components/ui/card';
import { 
    MapPin, Phone, Mail, Clock, Send, 
    Facebook, Instagram, Twitter 
} from 'lucide-vue-next';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

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
    <PublicLayout title="Hubungi Kami">
        
        <div class="py-16 sm:py-24">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-5xl mb-4">
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
                                    <div class="p-3 bg-blue-100 rounded-lg text-blue-600 shadow-sm">
                                        <MapPin class="h-6 w-6" />
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-slate-900">Alamat Kantor</h4>
                                        <p class="text-slate-600 mt-1 leading-relaxed">
                                            Jl. Raya Desa No. 1, Kecamatan Maju Jaya,<br>
                                            Kabupaten Contoh, 12345
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div class="p-3 bg-blue-100 rounded-lg text-blue-600 shadow-sm">
                                        <Mail class="h-6 w-6" />
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-slate-900">Email & Telepon</h4>
                                        <p class="text-slate-600 mt-1">admin@desasmart.id</p>
                                        <p class="text-slate-600">(021) 1234-5678</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div class="p-3 bg-green-100 rounded-lg text-green-600 shadow-sm">
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

                        <div class="rounded-2xl overflow-hidden shadow-md border border-slate-200 h-64 bg-slate-100 relative group">
                            <iframe 
                                src="https://maps.google.com/maps?q=-6.200000,106.816666&z=15&output=embed" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy"
                                class="grayscale group-hover:grayscale-0 transition-all duration-500 opacity-90 group-hover:opacity-100"
                            ></iframe>
                        </div>

                        <div class="flex gap-4">
                            <a href="#" class="p-3 bg-white border border-slate-200 rounded-full hover:bg-blue-50 hover:text-blue-600 transition-colors shadow-sm">
                                <Facebook class="h-5 w-5" />
                            </a>
                            <a href="#" class="p-3 bg-white border border-slate-200 rounded-full hover:bg-pink-50 hover:text-pink-600 transition-colors shadow-sm">
                                <Instagram class="h-5 w-5" />
                            </a>
                            <a href="#" class="p-3 bg-white border border-slate-200 rounded-full hover:bg-sky-50 hover:text-sky-500 transition-colors shadow-sm">
                                <Twitter class="h-5 w-5" />
                            </a>
                        </div>
                    </div>

                    <Card class="border-slate-200 shadow-xl shadow-slate-200/50 h-fit">
                        <CardHeader>
                            <CardTitle class="text-xl">Kirim Pesan</CardTitle>
                            <CardDescription>Kami akan membalas pesan Anda melalui email sesegera mungkin.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <form @submit.prevent="submit" class="space-y-5">
                                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                    <div class="space-y-2">
                                        <label for="nama" class="text-sm font-medium text-slate-700">Nama Lengkap</label>
                                        <Input id="nama" v-model="form.nama" placeholder="Nama Anda" required class="focus:ring-blue-500" />
                                    </div>
                                    <div class="space-y-2">
                                        <label for="email" class="text-sm font-medium text-slate-700">Alamat Email</label>
                                        <Input id="email" type="email" v-model="form.email" placeholder="email@contoh.com" required class="focus:ring-blue-500" />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="subjek" class="text-sm font-medium text-slate-700">Subjek</label>
                                    <Input id="subjek" v-model="form.subjek" placeholder="Perihal pesan..." required class="focus:ring-blue-500" />
                                </div>

                                <div class="space-y-2">
                                    <label for="pesan" class="text-sm font-medium text-slate-700">Pesan</label>
                                    <Textarea id="pesan" v-model="form.pesan" placeholder="Tuliskan detail pertanyaan atau laporan Anda..." rows="5" required class="focus:ring-blue-500" />
                                </div>

                                <Button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white shadow-md transition-all" :disabled="form.processing">
                                    <Send class="w-4 h-4 mr-2" /> Kirim Pesan
                                </Button>
                            </form>
                        </CardContent>
                    </Card>

                </div>
            </div>
        </div>
    </PublicLayout>
</template>