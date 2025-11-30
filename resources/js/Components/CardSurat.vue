<script setup>
import { Link } from '@inertiajs/vue3';
import PrimaryButton from './PrimaryButton.vue'; // Asumsi PrimaryButton ada

const props = defineProps({
    // Prop ini akan menerima satu objek template dari array templates
    template: {
        type: Object,
        required: true,
        default: () => ({}),
    },
});
</script>

<template>
    <div class="border border-gray-200 bg-white rounded-xl shadow-lg hover:shadow-xl transition duration-300 ease-in-out flex flex-col h-full">
        <div class="p-6 flex-grow">
            <p class="text-xs font-semibold text-indigo-600 uppercase mb-2">
                Kode: {{ template.kode_surat }}
            </p>
            
            <h4 class="font-extrabold text-xl text-gray-800 mb-3">
                {{ template.judul_surat }}
            </h4>
            
            <p class="text-sm text-gray-600 mb-4">
                {{ template.deskripsi }}
            </p>
        </div>
        
        <div class="p-6 border-t border-gray-100 mt-auto">
            <Link :href="route('warga.form', template.id)">
                <PrimaryButton class="w-full justify-center">
                    Ajukan Surat Ini
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 ml-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </PrimaryButton>
            </Link>
        </div>
    </div>
</template>