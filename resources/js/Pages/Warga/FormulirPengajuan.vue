<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    template: Object,
    form_schema: Object,
    user_data: Object,
    errors: Object,
});

// Inisialisasi form Inertia
const form = useForm({
    template_id: props.template.id,
    form_data: {},
});

// Lakukan inisialisasi form_data dari schema
// Ini penting agar Vue reaktif pada data yang akan diisi
for (const key in props.form_schema) {
    form.form_data[key] = '';
}

const submit = () => {
    form.post(route('warga.store'), {
        onSuccess: () => {
            alert('Pengajuan berhasil dikirim!');
        }
    });
};

const getComponentType = (typeValue) => {
    if (typeValue.startsWith('select:')) {
        return 'select';
    }
    return typeValue; // text, number, date, textarea
};

const getSelectOptions = (typeValue) => {
    if (typeValue.startsWith('select:')) {
        return typeValue.substring(7).split(',');
    }
    return [];
};
</script>

<template>
    <Head :title="'Formulir ' + template.judul_surat" />

    <AppLayout :title="template.judul_surat">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Formulir Pengajuan: {{ template.judul_surat }}
            </h2>
            <p class="text-sm text-gray-600 mt-1">{{ template.deskripsi }}</p>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                    
                    <form @submit.prevent="submit">
                        
                        <h3 class="text-lg font-bold mb-4 border-b pb-2 text-indigo-700">1. Data Pemohon</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div><InputLabel value="Nama" /> <p class="text-sm font-medium">{{ user_data.name }}</p></div>
                            <div><InputLabel value="NIK" /> <p class="text-sm font-medium">{{ user_data.nik }}</p></div>
                            <div><InputLabel value="Alamat" /> <p class="text-sm font-medium">{{ user_data.alamat_lengkap ?? 'Lengkapi di Profil Anda' }}</p></div>
                            <div v-if="user_data.tanggal_lahir"><InputLabel value="TTL" /> <p class="text-sm font-medium">{{ user_data.tempat_lahir }}, {{ new Date(user_data.tanggal_lahir).toLocaleDateString() }}</p></div>
                        </div>

                        <h3 class="text-lg font-bold mb-4 border-b pb-2 text-indigo-700">2. Data Keperluan Surat</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            
                            <div v-for="(typeValue, label) in form_schema" :key="label">
                                <InputLabel :for="label" :value="label" />
                                
                                <template v-if="getComponentType(typeValue) !== 'select'">
                                    <textarea
                                        v-if="getComponentType(typeValue) === 'textarea'"
                                        :id="label"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        rows="3"
                                        v-model="form.form_data[label]"
                                        required
                                    ></textarea>
                                    <TextInput
                                        v-else
                                        :id="label"
                                        :type="getComponentType(typeValue)"
                                        class="mt-1 block w-full"
                                        v-model="form.form_data[label]"
                                        required
                                    />
                                </template>

                                <select
                                    v-else
                                    :id="label"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.form_data[label]"
                                    required
                                >
                                    <option value="" disabled selected>Pilih salah satu</option>
                                    <option v-for="option in getSelectOptions(typeValue)" :key="option" :value="option.trim()">
                                        {{ option.trim() }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors['form_data.' + label]" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton :disabled="form.processing">
                                Ajukan Surat (Kirim ke Admin)
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>