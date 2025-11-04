<template>
    <Head title="Register - RonaLaundry" />
    <div
        class="relative flex flex-col items-center justify-center min-h-screen bg-white"
    >
        <div
            class="relative w-full max-w-md p-8 mt-20 mb-20 border border-gray-300 shadow-md mt-20g-white m rounded-xl"
        >
            <!-- 🔹 Icon User -->
            <div
                class="absolute flex items-center justify-center w-16 h-16 transform -translate-x-1/2 bg-white border-2 border-gray-300 rounded-full -top-8 left-1/2"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-8 h-8 text-gray-600"
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
            <div class="flex justify-center mt-8 mb-6">
                <button
                    class="px-5 py-2 font-semibold text-white bg-blue-500 rounded-l-md"
                >
                    SIGNUP
                </button>
                <button
                    class="px-5 py-2 font-semibold text-gray-600 bg-blue-200 rounded-r-md"
                    @click="goToLogin"
                >
                    LOGIN
                </button>
            </div>

            <!-- 🔹 Form -->
            <form @submit.prevent="handleSubmit">
                <div class="space-y-4">
                    <div>
                        <label
                            class="block mb-1 text-sm font-bold text-gray-800"
                            >Username</label
                        >
                        <input
                            v-model="form.username"
                            type="text"
                            placeholder="Masukkan username"
                            required
                            class="w-full px-3 py-2 text-gray-800 border border-gray-400 rounded-md focus:ring focus:ring-blue-300 focus:outline-none"
                            autocomplete="off"
                        />
                    </div>

                    <div>
                        <label
                            class="block mb-1 text-sm font-bold text-gray-800"
                            >Email Address</label
                        >
                        <input
                            v-model="form.email"
                            type="email"
                            placeholder="Masukkan email"
                            required
                            class="w-full px-3 py-2 text-gray-800 border border-gray-400 rounded-md focus:ring focus:ring-blue-300 focus:outline-none"
                            autocomplete="off"
                        />
                    </div>

                    <div>
                        <label
                            class="block mb-1 text-sm font-bold text-gray-800"
                            >Password</label
                        >
                        <input
                            v-model="form.password"
                            type="password"
                            placeholder="Masukkan password"
                            required
                            class="w-full px-3 py-2 text-gray-800 border border-gray-400 rounded-md focus:ring focus:ring-blue-300 focus:outline-none"
                            autocomplete="off"
                        />
                    </div>

                    <div>
                        <label
                            class="block mb-1 text-sm font-bold text-gray-800"
                            >Konfirmasi Password</label
                        >
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            placeholder="Ulangi password"
                            required
                            class="w-full px-3 py-2 text-gray-800 border border-gray-400 rounded-md focus:ring focus:ring-blue-300 focus:outline-none"
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
                        <label
                            for="remember"
                            class="block mb-1 text-sm font-bold text-gray-800"
                            >Ingat saya</label
                        >
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full py-2 mt-6 font-bold text-white transition bg-pink-400 rounded-md hover:bg-pink-500"
                >
                    SIGNUP
                </button>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = ref({
    name: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
    remember: false,
});

function handleSubmit() {
    router.post('/register', form.value, {
        onSuccess: () => {
            alert('Akun berhasil dibuat! Silakan login dulu ya 😊');
            router.visit('/login');
        },
        onError: (errors) => {
            console.error(errors);
            alert(
                'Terjadi kesalahan saat mendaftar, periksa kembali data Anda.',
            );
        },
    });
}

function goToLogin() {
    router.visit('/login');
}
</script>
