<template>
    <nav
        class="fixed top-0 left-0 z-50 w-full bg-pink-400 text-white shadow-md"
    >
        <div class="flex w-full items-center justify-between px-2 py-3">
            <!-- 🔹 Logo -->
            <div
                class="ml-2 flex items-center rounded-xl bg-white p-2 shadow-md"
            >
                <img src="/images/logo.png" alt="Logo" class="h-10 w-auto" />
            </div>

            <!-- 🔹 Menu Navigasi -->
            <ul class="flex space-x-4">
                <li>
                    <a
                        href="/"
                        class="rounded-md bg-blue-400 px-3 py-1 text-white transition hover:bg-blue-500"
                    >
                        Beranda
                    </a>
                </li>
                <li>
                    <a
                        href="/order"
                        class="rounded-md bg-blue-400 px-3 py-1 text-white transition hover:bg-blue-500"
                    >
                        Cek Status
                    </a>
                </li>
                <li>
                    <a
                        href="/reservasi"
                        class="rounded-md bg-blue-400 px-3 py-1 text-white transition hover:bg-blue-500"
                    >
                        Reservasi
                    </a>
                </li>
            </ul>

            <!-- 🔹 Bagian kanan -->
            <div class="relative mr-2 flex items-center gap-3">
                <!-- Kalau belum login -->
                <template v-if="!isAuthenticated">
                    <a
                        href="/login"
                        class="rounded-md bg-blue-500 px-4 py-1.5 text-white transition hover:bg-blue-600"
                    >
                        Login
                    </a>
                    <a
                        href="/register"
                        class="rounded-md bg-green-500 px-4 py-1.5 text-white transition hover:bg-green-600"
                    >
                        Sign Up
                    </a>
                </template>

                <!-- Kalau sudah login -->
                <template v-else>
                    <div
                        class="flex cursor-pointer items-center justify-center rounded-full border-[3px] border-pink-400 bg-white p-1.5 shadow-md transition hover:scale-105 hover:shadow-lg"
                        @click="toggleDropdown"
                    >
                        <span class="material-icons text-3xl text-black">
                            person
                        </span>
                    </div>

                    <!-- 🔹 Dropdown menu -->
                    <div
                        v-if="showDropdown"
                        class="absolute top-14 right-0 z-50 w-40 rounded-lg border border-gray-200 bg-white py-2 text-gray-800 shadow-lg"
                    >
                        <a
                            href="/user-profile"
                            class="block px-4 py-2 transition hover:bg-pink-100"
                        >
                            Profile
                        </a>
                        <button
                            @click="logout"
                            class="block w-full px-4 py-2 text-left transition hover:bg-pink-100"
                        >
                            Logout
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </nav>
</template>

<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const page = usePage();

// Gunakan computed agar selalu update saat status login berubah
const isAuthenticated = computed(() => !!(page.props as any).auth?.user);

const showDropdown = ref(false);

function toggleDropdown() {
    showDropdown.value = !showDropdown.value;
}

// Pastikan klik di luar dropdown menutup menu
function handleClickOutside(e: MouseEvent) {
    const dropdownEl = document.getElementById('navbar-dropdown');
    if (dropdownEl && !dropdownEl.contains(e.target as Node)) {
        showDropdown.value = false;
    }
}

// Logout kirim POST ke /logout, lalu kembali ke /
function logout() {
    router.post(
        '/logout',
        {},
        {
            onFinish: () => router.visit('/'),
        },
    );
}

onMounted(() => {
    window.addEventListener('click', handleClickOutside);
});
onBeforeUnmount(() => {
    window.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
nav {
    font-family: 'Poppins', sans-serif;
}
</style>
