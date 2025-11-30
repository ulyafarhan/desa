<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import { Separator } from '@/Components/ui/separator';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profil Saya" />

    <AppLayout title="Profil">
        <template #header>
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                Pengaturan Profil
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
                
                <div class="space-y-0.5 px-4 sm:px-0">
                    <h2 class="text-2xl font-bold tracking-tight">Profil & Akun</h2>
                    <p class="text-muted-foreground">
                        Kelola data kependudukan dan keamanan akun Anda.
                    </p>
                </div>
                
                <Separator class="my-6" />

                <div class="flex flex-col gap-8 md:flex-row">
                    <aside class="md:w-1/3">
                        <h3 class="text-lg font-medium text-slate-900">Data Kependudukan</h3>
                        <p class="text-sm text-slate-500 mt-1">
                            Pastikan data ini sesuai dengan KTP/KK Anda agar pengajuan surat tidak ditolak.
                        </p>
                    </aside>
                    <div class="md:w-2/3">
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                            class="max-w-xl"
                        />
                    </div>
                </div>

                <Separator class="my-6" />

                <div class="flex flex-col gap-8 md:flex-row">
                    <aside class="md:w-1/3">
                        <h3 class="text-lg font-medium text-slate-900">Keamanan</h3>
                        <p class="text-sm text-slate-500 mt-1">
                            Perbarui kata sandi Anda secara berkala untuk menjaga keamanan akun.
                        </p>
                    </aside>
                    <div class="md:w-2/3">
                        <UpdatePasswordForm class="max-w-xl" />
                    </div>
                </div>

                <Separator class="my-6" />

                <div class="flex flex-col gap-8 md:flex-row">
                    <aside class="md:w-1/3">
                        <h3 class="text-lg font-medium text-red-600">Zona Bahaya</h3>
                        <p class="text-sm text-slate-500 mt-1">
                            Tindakan ini tidak dapat dibatalkan. Data Anda akan dihapus permanen.
                        </p>
                    </aside>
                    <div class="md:w-2/3">
                        <DeleteUserForm class="max-w-xl" />
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>