<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

// --- IMPORTS KOMPONEN SHADCN UI ---
// Pastikan komponen ini sudah diinstal via CLI
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table';
import { Alert, AlertDescription, AlertTitle } from '@/Components/ui/alert';
import { Separator } from '@/Components/ui/separator';

// --- PROPS & LOGIC ---
const props = defineProps({
    user: Object,
    templates: Array,
    riwayat: Array,
});

// 1. Pencarian Realtime
const searchQuery = ref('');

// 2. Filter Riwayat
const filteredRiwayat = computed(() => {
    if (!searchQuery.value) return props.riwayat;
    const lowerQuery = searchQuery.value.toLowerCase();
    return props.riwayat.filter(item => 
        item.template.judul_surat.toLowerCase().includes(lowerQuery) ||
        (item.nomor_surat && item.nomor_surat.toLowerCase().includes(lowerQuery))
    );
});

// 3. Statistik
const stats = computed(() => {
    const total = props.riwayat.length;
    const completed = props.riwayat.filter(r => r.status === 'completed').length;
    const pending = props.riwayat.filter(r => ['pending', 'in_queue', 'processing'].includes(r.status)).length;
    return { total, completed, pending };
});

// 4. Greeting Time
const greeting = computed(() => {
    const hour = new Date().getHours();
    if (hour < 12) return 'Selamat Pagi';
    if (hour < 18) return 'Selamat Siang';
    return 'Selamat Malam';
});

// 5. Helper Warna Badge Status (Custom Class pada Shadcn Badge)
const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'completed': return 'bg-emerald-500 hover:bg-emerald-600 border-transparent';
        case 'processing': 
        case 'in_queue': return 'bg-blue-500 hover:bg-blue-600 border-transparent';
        case 'pending': return 'bg-amber-500 hover:bg-amber-600 border-transparent';
        case 'rejected': return 'bg-destructive hover:bg-destructive/80 border-transparent';
        default: return 'bg-secondary hover:bg-secondary/80 text-secondary-foreground';
    }
};
</script>

<template>
    <Head title="Dashboard Layanan" />

    <AppLayout title="Dashboard">
        
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-foreground">
                        {{ greeting }}, {{ user.name.split(' ')[0] }}
                    </h2>
                    <p class="text-muted-foreground mt-1">
                        Selamat datang di pusat layanan administrasi digital desa.
                    </p>
                </div>
                
                <Alert v-if="user.status_akun !== 'verified'" variant="destructive" class="md:w-auto max-w-lg bg-amber-50 text-amber-900 border-amber-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 stroke-amber-600"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    <AlertTitle>Akun Belum Terverifikasi</AlertTitle>
                    <AlertDescription>
                        Fitur pengajuan dibatasi. Silakan lengkapi profil Anda atau hubungi Admin Desa.
                    </AlertDescription>
                </Alert>
            </div>
        </template>

        <div class="py-8 space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid gap-4 md:grid-cols-3">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Pengajuan</CardTitle>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="h-4 w-4 text-muted-foreground"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" /></svg>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.total }}</div>
                        <p class="text-xs text-muted-foreground">Dokumen diajukan</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Dalam Proses</CardTitle>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="h-4 w-4 text-muted-foreground"><path d="M22 12h-4l-3 9L9 3l-3 9H2" /></svg>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.pending }}</div>
                        <p class="text-xs text-muted-foreground">Menunggu persetujuan/proses</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Selesai</CardTitle>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="h-4 w-4 text-muted-foreground"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.completed }}</div>
                        <p class="text-xs text-muted-foreground">Dokumen siap diunduh</p>
                    </CardContent>
                </Card>
            </div>

            <Separator />

            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold tracking-tight">Layanan Surat Tersedia</h3>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <Card 
                        v-for="template in templates" 
                        :key="template.id" 
                        class="flex flex-col hover:shadow-md transition-shadow duration-200"
                    >
                        <CardHeader>
                            <CardTitle class="text-base line-clamp-1" :title="template.judul_surat">
                                {{ template.judul_surat }}
                            </CardTitle>
                            <CardDescription class="line-clamp-2 min-h-[2.5rem]">
                                {{ template.deskripsi }}
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="flex-grow">
                            </CardContent>
                        <CardFooter>
                            <Button as-child class="w-full">
                                <Link :href="route('warga.form', template.id)">
                                    Ajukan Surat
                                </Link>
                            </Button>
                        </CardFooter>
                    </Card>
                </div>
            </div>

            <Separator />

            <Card>
                <CardHeader>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <CardTitle>Riwayat Pengajuan</CardTitle>
                            <CardDescription>Daftar surat yang pernah Anda ajukan.</CardDescription>
                        </div>
                        <div class="flex w-full max-w-sm items-center space-x-2">
                            <Input 
                                v-model="searchQuery" 
                                type="search" 
                                placeholder="Cari surat atau nomor..." 
                            />
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-[300px]">Jenis Surat</TableHead>
                                <TableHead class="hidden md:table-cell">Nomor Surat</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="hidden sm:table-cell">Tanggal</TableHead>
                                <TableHead class="text-right">Aksi</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="item in filteredRiwayat" :key="item.id">
                                <TableCell class="font-medium">
                                    {{ item.template.judul_surat }}
                                    <div class="md:hidden text-xs text-muted-foreground mt-1">
                                        {{ item.nomor_surat || 'Belum ada nomor' }}
                                    </div>
                                </TableCell>
                                <TableCell class="hidden md:table-cell">
                                    <code class="relative rounded bg-muted px-[0.3rem] py-[0.2rem] font-mono text-sm font-semibold">
                                        {{ item.nomor_surat || 'Menunggu' }}
                                    </code>
                                </TableCell>
                                <TableCell>
                                    <Badge :class="getStatusBadgeClass(item.status)">
                                        {{ item.status.replace('_', ' ') }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="hidden sm:table-cell text-muted-foreground">
                                    {{ new Date(item.created_at).toLocaleDateString('id-ID') }}
                                </TableCell>
                                <TableCell class="text-right">
                                    <Button 
                                        v-if="item.status === 'completed'" 
                                        variant="outline" 
                                        size="sm" 
                                        as-child
                                    >
                                        <a :href="route('dokumen.show', item.id)" target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-3 w-3"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                            Unduh
                                        </a>
                                    </Button>
                                    <span v-else class="text-xs text-muted-foreground italic">Proses</span>
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="filteredRiwayat.length === 0">
                                <TableCell colspan="5" class="h-24 text-center">
                                    Tidak ada data surat yang ditemukan.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>

        </div>
    </AppLayout>
</template>