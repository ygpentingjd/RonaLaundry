<script setup lang="ts">
import NewPasswordController from '@/actions/App/Http/Controllers/Auth/NewPasswordController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{ token: string; email: string }>();

// Email readonly
const inputEmail = ref(props.email);
</script>

<template>
    <AuthLayout
        title="Reset Password"
        description="Masukkan password baru untuk akun Anda"
    >
        <Head title="Reset Password" />

        <Form
            v-bind="NewPasswordController.store.form()"
            :transform="(data) => ({
                ...data,
                token: props.token,
                email: props.email,
            })"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-6">

                <!-- Email -->
                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        readonly
                        v-model="inputEmail"
                        class="font-medium text-gray-700 border-gray-300 bg-gray-50"
                    />
                    <InputError :message="errors.email" />
                </div>

                <!-- Password -->
                <div class="grid gap-2">
                    <Label for="password">Password Baru</Label>
                    <Input
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        placeholder="Masukkan password baru"
                        class="border-gray-300 focus:border-pink-500 focus:ring-pink-500"
                    />
                    <InputError :message="errors.password" />
                </div>

                <!-- Password Confirmation -->
                <div class="grid gap-2">
                    <Label for="password_confirmation">Konfirmasi Password</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        autocomplete="new-password"
                        placeholder="Ulangi password baru"
                        class="border-gray-300 focus:border-pink-500 focus:ring-pink-500"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <!-- Submit -->
                <Button
                    type="submit"
                    class="w-full mt-4 font-semibold text-white bg-pink-400 hover:bg-pink-500"
                    :disabled="processing"
                >
                    <LoaderCircle
                        v-if="processing"
                        class="w-4 h-4 mr-2 animate-spin"
                    />
                    Simpan Password Baru
                </Button>
            </div>
        </Form>
    </AuthLayout>
</template>
