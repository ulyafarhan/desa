<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import CardSurat from '@/Components/CardSurat.vue';
import StatusBadge from '@/Components/StatusBadge.vue'; // Komponen ini perlu dibuat

const props = defineProps({
    user: Object,
    templates: Array,
    riwayat: Array,
});
</script>

<template>
    <Head title="Dashboard Warga" />

    <AppLayout title="Dashboard Warga">
        <!-- Header -->
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Selamat Datang, {{ user.name }} (NIK: {{ user.nik }})
            </h2>
            <p
                v-if="user.status_akun !== 'verified'"
                class="text-sm text-red-600 mt-1"
            >
                ⚠️ Akun Anda belum terverifikasi oleh Admin Desa. Harap lengkapi data kependudukan.
            </p>
        </template>

        <!-- Konten Utama -->
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Ajukan Surat Baru -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-8 p-6">
                    <h3 class="text-xl font-bold mb-4 border-b pb-2">Ajukan Surat Baru</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <CardSurat
                            v-for="template in templates"
                            :key="template.id"
                            :template="template"
                        />
                    </div>
                </div>

                <!-- Riwayat Pengajuan -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-xl font-bold mb-4 border-b pb-2">
                        Riwayat Pengajuan Saya ({{ riwayat.length }} dokumen)
                    </h3>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jenis Surat
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nomor Surat
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Pengajuan
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="item in riwayat" :key="item.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ item.template.judul_surat }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ item.nomor_surat ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <StatusBadge :status="item.status" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ new Date(item.created_at).toLocaleDateString() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a
                                        v-if="item.status === 'completed'"
                                        :href="route('dokumen.show', item.id)"
                                        target="_blank"
                                        class="text-green-600 hover:text-green-900 ml-2"
                                    >
                                        Unduh/Lihat
                                    </a>
                                    <span v-else class="text-gray-400">Menunggu...</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>