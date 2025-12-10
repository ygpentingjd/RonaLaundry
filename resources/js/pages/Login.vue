<template>
    <Head title="Login - RonaLaundry" />
    <div
        class="relative flex min-h-screen flex-col items-center justify-center bg-white"
    >
        <div
            class="relative w-full max-w-md rounded-xl border border-gray-300 bg-white p-8 shadow-md"
        >
            <!-- 🔹 Icon User -->
            <div
                class="absolute -top-8 left-1/2 flex h-16 w-16 -translate-x-1/2 transform items-center justify-center rounded-full border-2 border-gray-300 bg-white"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8 text-gray-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5.121 17.804A9 9 0 1118.879 17.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                </svg>
            </div>

            <!-- 🔹 Toggle -->
            <div class="mt-8 mb-6 flex justify-center">
                <button
                    class="rounded-l-md bg-blue-200 px-5 py-2 font-semibold text-gray-600"
                    @click="goToRegister"
                >
                    SIGNUP
                </button>
                <button
                    class="rounded-r-md bg-blue-500 px-5 py-2 font-semibold text-white"
                >
                    LOGIN
                </button>
            </div>

            <!-- 🔹 Form Login -->
            <form @submit.prevent="handleLogin">
                <div class="space-y-4">
                    <div>
                        <label
                            class="mb-1 block text-sm font-bold text-gray-800"
                            >Username</label
                        >
                        <input
                            v-model="form.username"
                            type="text"
                            placeholder="Masukkan username"
                            required
                            class="w-full rounded-md border border-gray-400 px-3 py-2 text-gray-800 focus:ring focus:ring-blue-300 focus:outline-none"
                            autocomplete="off"
                        />
                    </div>

                    <div>
                        <label
                            class="mb-1 block text-sm font-bold text-gray-800"
                            >Password</label
                        >
                        <input
                            v-model="form.password"
                            type="password"
                            placeholder="Masukkan password"
                            required
                            class="w-full rounded-md border border-gray-400 px-3 py-2 text-gray-800 focus:ring focus:ring-blue-300 focus:outline-none"
                            autocomplete="off"
                        />
                    </div>

                    <div class="flex items-center">
                        <input
                            id="remember"
                            type="checkbox"
                            v-model="form.remember"
                            class="mr-2"
                        />
                        <label for="remember" class="text-sm text-gray-700"
                            >Ingat saya</label
                        >
                    </div>
                    <p class="text-right">
                        <a
                            href="#"
                            @click.prevent="goToForgotPassword"
                            class="text-sm font-medium text-pink-500 hover:underline focus:ring-2 focus:ring-pink-300 focus:ring-offset-2 focus:outline-none"
                            aria-label="Lupa password, menuju halaman reset"
                        >
                            Lupa password?
                        </a>
                    </p>
                </div>

                <button
                    type="submit"
                    class="mt-6 w-full rounded-md bg-pink-400 py-2 font-bold text-white transition hover:bg-pink-500"
                >
                    LOGIN
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-600">
                Belum punya akun?
                <a
                    @click.prevent="goToRegister"
                    href="#"
                    class="font-semibold text-pink-500 hover:underline"
                >
                    Daftar sekarang
                </a>
            </p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

function handleLogin() {
    // jika ada afterLoginRedirect yang disimpan (misal user menekan reservasi sebelum login),
    // kirim sebagai query 'redirect' ke server agar server bisa redirect ke tempat itu.
    const redirectTo = localStorage.getItem('afterLoginRedirect');
    const url = redirectTo
        ? `/login?redirect=${encodeURIComponent(redirectTo)}`
        : '/login';

    form.post(url, {
        onSuccess: () => {
            // jangan panggil router.visit('/landing') — Inertia akan mengikuti redirect dari server
            localStorage.removeItem('afterLoginRedirect'); // hapus agar tidak ketempel lagi
        },
        onError: () => {
            alert('Login gagal! Periksa kembali username dan password anda.');
        },
    });
}

function goToRegister() {
    router.visit('/register');
}

function goToForgotPassword() {
    // Navigasi ke halaman ForgotPassword (Inertia page under auth/ForgotPassword.vue)
    router.visit('/forgot-password');
}
</script>
