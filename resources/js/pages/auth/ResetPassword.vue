<script setup lang="ts">
import NewPasswordController from '@/actions/App/Http/Controllers/Auth/NewPasswordController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{ token: string; email: string }>();
const inputEmail = ref(props.email);
</script>

<template>
    <AuthLayout
        title="Reset Password"
        description="Silakan masukkan password baru Anda"
    >
        <Head title="Reset password" />

```
    <Form
        v-bind="NewPasswordController.store.form()"
        :transform="(data) => ({ ...data, token, email })"
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
                    class="border-gray-300 bg-gray-50 font-medium text-gray-700"
                />
                <InputError :message="errors.email" />
            </div>

            <!-- Password Baru -->
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

            <!-- Konfirmasi Password -->
            <div class="grid gap-2">
                <Label for="password_confirmation">
                    Konfirmasi Password
                </Label>
                <Input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    autocomplete="new-password"
                    placeholder="Ulangi password"
                    class="border-gray-300 focus:border-pink-500 focus:ring-pink-500"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <!-- Submit Button -->
            <Button
                type="submit"
                class="mt-4 w-full bg-pink-400 font-semibold text-white hover:bg-pink-500"
                :disabled="processing"
                data-test="reset-password-button"
            >
                <LoaderCircle
                    v-if="processing"
                    class="mr-2 h-4 w-4 animate-spin"
                />
                Simpan Password Baru
            </Button>
        </div>
    </Form>
</AuthLayout>
```

</template>