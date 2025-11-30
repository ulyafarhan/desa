<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Button } from '@/Components/ui/button';
import { Card, CardContent } from '@/Components/ui/card';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <Card>
        <CardContent class="pt-6">
            <form @submit.prevent="updatePassword" class="space-y-6">
                
                <div class="grid gap-2">
                    <Label for="current_password">Kata Sandi Saat Ini</Label>
                    <Input
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        type="password"
                        autocomplete="current-password"
                    />
                    <p v-if="form.errors.current_password" class="text-sm text-red-500">{{ form.errors.current_password }}</p>
                </div>

                <div class="grid gap-2">
                    <Label for="password">Kata Sandi Baru</Label>
                    <Input
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        autocomplete="new-password"
                    />
                    <p v-if="form.errors.password" class="text-sm text-red-500">{{ form.errors.password }}</p>
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Konfirmasi Kata Sandi Baru</Label>
                    <Input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        autocomplete="new-password"
                    />
                    <p v-if="form.errors.password_confirmation" class="text-sm text-red-500">{{ form.errors.password_confirmation }}</p>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <Button :disabled="form.processing">Perbarui Password</Button>

                    <transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p v-if="form.recentlySuccessful" class="text-sm text-green-600 font-medium">Password berhasil diubah.</p>
                    </transition>
                </div>
            </form>
        </CardContent>
    </Card>
</template>