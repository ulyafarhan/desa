<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Button } from '@/Components/ui/button';
import { Textarea } from '@/Components/ui/textarea';
import { Card, CardContent } from '@/Components/ui/card';
import { Alert, AlertDescription } from '@/Components/ui/alert';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    nik: user.nik || '',
    tempat_lahir: user.tempat_lahir || '',
    tanggal_lahir: user.tanggal_lahir || '',
    alamat_lengkap: user.alamat_lengkap || '',
});
</script>

<template>
    <Card>
        <CardContent class="pt-6">
            <form @submit.prevent="form.patch(route('profile.update'))" class="space-y-6">
                
                <div class="grid gap-2">
                    <Label for="name">Nama Lengkap (Sesuai KTP)</Label>
                    <Input
                        id="name"
                        type="text"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Contoh: Ahmad Dahlan"
                    />
                    <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
                </div>

                <div class="grid gap-2">
                    <Label for="nik">NIK (Nomor Induk Kependudukan)</Label>
                    <Input
                        id="nik"
                        type="text"
                        v-model="form.nik"
                        required
                        placeholder="16 Digit Angka"
                        :disabled="user.status_akun === 'verified'" 
                    />
                    <p class="text-[0.8rem] text-muted-foreground">
                        NIK digunakan sebagai identitas utama login.
                        <span v-if="user.status_akun === 'verified'" class="text-green-600 font-medium">(Terverifikasi - Hubungi Admin untuk ubah)</span>
                    </p>
                    <p v-if="form.errors.nik" class="text-sm text-red-500">{{ form.errors.nik }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label for="tempat_lahir">Tempat Lahir</Label>
                        <Input
                            id="tempat_lahir"
                            type="text"
                            v-model="form.tempat_lahir"
                            placeholder="Kota Kelahiran"
                        />
                        <p v-if="form.errors.tempat_lahir" class="text-sm text-red-500">{{ form.errors.tempat_lahir }}</p>
                    </div>
                    
                    <div class="grid gap-2">
                        <Label for="tanggal_lahir">Tanggal Lahir</Label>
                        <Input
                            id="tanggal_lahir"
                            type="date"
                            v-model="form.tanggal_lahir"
                        />
                        <p v-if="form.errors.tanggal_lahir" class="text-sm text-red-500">{{ form.errors.tanggal_lahir }}</p>
                    </div>
                </div>

                <div class="grid gap-2">
                    <Label for="alamat_lengkap">Alamat Lengkap</Label>
                    <Textarea
                        id="alamat_lengkap"
                        v-model="form.alamat_lengkap"
                        placeholder="Nama Jalan, RT/RW, Dusun..."
                        class="resize-none h-24"
                    />
                    <p v-if="form.errors.alamat_lengkap" class="text-sm text-red-500">{{ form.errors.alamat_lengkap }}</p>
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email (Untuk Notifikasi)</Label>
                    <Input
                        id="email"
                        type="email"
                        v-model="form.email"
                        autocomplete="username"
                    />
                    <p v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</p>

                    <div v-if="mustVerifyEmail && user.email_verified_at === null">
                        <p class="text-sm mt-2 text-slate-800">
                            Email Anda belum diverifikasi.
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="underline text-sm text-slate-600 hover:text-slate-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Klik di sini untuk kirim ulang email verifikasi.
                            </Link>
                        </p>
                        <div v-show="status === 'verification-link-sent'" class="mt-2 font-medium text-sm text-green-600">
                            Tautan verifikasi baru telah dikirim ke email Anda.
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <Button :disabled="form.processing">Simpan Perubahan</Button>

                    <transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p v-if="form.recentlySuccessful" class="text-sm text-green-600 font-medium">Berhasil disimpan.</p>
                    </transition>
                </div>
            </form>
        </CardContent>
    </Card>
</template>