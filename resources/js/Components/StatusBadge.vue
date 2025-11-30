<script setup>
import { computed } from 'vue';

const props = defineProps({
    // Prop ini menerima string status dari database (pending, completed, in_queue, dll)
    status: {
        type: String,
        required: true,
    },
});

// Logika untuk menentukan warna berdasarkan status
const badgeClass = computed(() => {
    switch (props.status.toLowerCase()) {
        case 'completed':
            return 'bg-emerald-100 text-emerald-800'; // Hijau (Selesai)
        case 'in_queue':
        case 'processing':
            return 'bg-sky-100 text-sky-800'; // Biru (Dalam Antrian/Proses)
        case 'pending':
            return 'bg-amber-100 text-amber-800'; // Kuning/Amber (Menunggu Persetujuan)
        case 'rejected':
            return 'bg-red-100 text-red-800'; // Merah (Ditolak)
        case 'failed':
            return 'bg-gray-100 text-gray-800'; // Abu-abu (Gagal Proses)
        default:
            return 'bg-gray-100 text-gray-800';
    }
});
</script>

<template>
    <span 
        :class="badgeClass" 
        class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium uppercase tracking-wider"
    >
        {{ status.replace('_', ' ') }}
    </span>
</template>