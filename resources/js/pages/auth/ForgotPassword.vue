<script setup lang="ts">
import PasswordResetLinkController from '@/actions/App/Http/Controllers/Auth/PasswordResetLinkController';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{ status?: string }>();
</script>

<template>
    <AuthLayout
        title="Lupa Password"
        description="Masukkan email Anda untuk menerima link reset password"
    >
        <Head title="Forgot password" />

    <!-- Status -->
    <div
        v-if="status"
        class="mb-4 rounded-md border border-green-200 bg-white p-3 text-center text-sm font-medium text-green-600 shadow-sm"
    >
        {{ status }}
    </div>

    <div class="space-y-6">
        <Form
            v-bind="(PasswordResetLinkController.store as any).form()"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-3">
                <Label for="email">Email address</Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    autocomplete="off"
                    autofocus
                    placeholder="email@example.com"
                    class="border-gray-300 bg-white focus:border-pink-500 focus:ring-pink-500"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="my-6">
                <Button
                    class="w-full bg-pink-400 font-semibold text-white hover:bg-pink-500"
                    :disabled="processing"
                    data-test="email-password-reset-link-button"
                >
                    <LoaderCircle
                        v-if="processing"
                        class="mr-2 h-4 w-4 animate-spin"
                    />
                    Kirim Link Reset Password
                </Button>
            </div>
        </Form>

        <div class="space-x-1 text-center text-sm text-gray-600">
            <span>Kembali ke</span>
            <TextLink :href="login()" class="text-pink-500 ">Login</TextLink>
        </div>
    </div>
</AuthLayout>
```

</template>