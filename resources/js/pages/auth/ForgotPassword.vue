<script setup lang="ts">
import PasswordResetLinkController from '@/actions/App/Http/Controllers/Auth/PasswordResetLinkController';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';
import { login } from '@/routes';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{ status?: string }>();
</script>

<template>
    <AuthLayout
        title="Lupa Password"
        description="Masukkan email Anda dan kami akan mengirimkan link reset password"
    >
        <Head title="Forgot Password" />

    <!-- Status -->
    <div
        v-if="status"
        class="p-3 mb-4 text-sm font-medium text-center text-green-600 bg-white border border-green-300 rounded-md"
    >
        {{ status }}
    </div>

    <div class="space-y-6">
        <Form
            v-bind="(PasswordResetLinkController.store as any).form()"
            v-slot="{ errors, processing }"
        >
                <div class="grid gap-3">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        placeholder="email@example.com"
                        autocomplete="off"
                        autofocus
                        class="bg-white border-gray-300 focus:border-pink-500 focus:ring-pink-500"
                    />
                    <InputError :message="errors.email" />
                </div>

                <Button
                    class="w-full mt-6 font-semibold text-white bg-pink-400 hover:bg-pink-500"
                    :disabled="processing"
                >
                    <LoaderCircle
                        v-if="processing"
                        class="w-4 h-4 mr-2 animate-spin"
                    />
                    Kirim Link Reset Password
                </Button>
            </Form>

            <div class="text-sm text-center text-gray-600">
                <span>Kembali ke</span>
                <TextLink :href="login()" class="text-pink-500">Login</TextLink>
            </div>
        </div>
    </AuthLayout>
</template>
