<script setup>
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import Modal from '@/Components/Modal.vue'; // Kita gunakan Modal bawaan Breeze tapi di-styling ulang isinya
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Card, CardContent } from '@/Components/ui/card';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.reset();
};
</script>

<template>
    <Card class="border-red-100 bg-red-50/50">
        <CardContent class="pt-6">
            <header>
                <h2 class="text-lg font-medium text-slate-900">Hapus Akun</h2>
                <p class="mt-1 text-sm text-slate-600">
                    Setelah akun Anda dihapus, semua data dan riwayat surat akan dihapus secara permanen.
                </p>
            </header>

            <Button variant="destructive" class="mt-6" @click="confirmUserDeletion">
                Hapus Akun Saya
            </Button>

            <Modal :show="confirmingUserDeletion" @close="closeModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-slate-900">
                        Apakah Anda yakin ingin menghapus akun?
                    </h2>

                    <p class="mt-1 text-sm text-slate-600">
                        Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.
                    </p>

                    <div class="mt-6">
                        <Label for="password" class="sr-only">Password</Label>
                        <Input
                            id="password"
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            placeholder="Password"
                            @keyup.enter="deleteUser"
                        />
                        <p v-if="form.errors.password" class="text-sm text-red-500 mt-2">{{ form.errors.password }}</p>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <Button variant="outline" @click="closeModal"> Batal </Button>
                        <Button variant="destructive" :disabled="form.processing" @click="deleteUser">
                            Hapus Akun
                        </Button>
                    </div>
                </div>
            </Modal>
        </CardContent>
    </Card>
</template>