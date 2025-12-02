<template>
    <nav class="fixed top-0 left-0 z-50 w-full text-white bg-pink-400 shadow-md">
        <div class="flex items-center justify-between w-full px-2 py-3">
            
            <!-- 🔹 Logo -->
            <div class="flex items-center p-2 ml-2 bg-white shadow-md rounded-xl">
                <img src="/images/logo.png" alt="Logo" class="w-auto h-10" />
            </div>

            <!-- 🔹 Menu Navigasi -->
            <ul class="flex space-x-4">
                <li><a href="/" class="px-3 py-1 text-white bg-blue-400 rounded-md hover:bg-blue-500">Beranda</a></li>
                <li><a href="/order" class="px-3 py-1 text-white bg-blue-400 rounded-md hover:bg-blue-500">Cek Status</a></li>
                <li><a href="/reservasi" class="px-3 py-1 text-white bg-blue-400 rounded-md hover:bg-blue-500">Reservasi</a></li>
            </ul>

            <!-- 🔹 Bagian kanan -->
            <div class="relative flex items-center gap-3 mr-2">

                <!-- Kalau belum login -->
                <template v-if="!isAuthenticated">
                    <a href="/login" class="rounded-md bg-blue-500 px-4 py-1.5 text-white hover:bg-blue-600">Login</a>
                    <a href="/register" class="rounded-md bg-green-500 px-4 py-1.5 text-white hover:bg-green-600">Sign Up</a>
                </template>

                <!-- Kalau sudah login -->
                <template v-else>
                    <div
                        class="flex cursor-pointer items-center justify-center rounded-full border-[3px] border-pink-400 bg-white p-1.5 shadow-md hover:scale-105 hover:shadow-lg"
                        @click.stop="toggleDropdown"
                    >
                        <span class="text-3xl text-black material-icons">person</span>
                    </div>

                    <!-- 🔹 Dropdown menu -->
                    <div
                        id="navbar-dropdown"
                        v-if="showDropdown"
                        class="absolute right-0 z-50 w-40 py-2 text-gray-800 bg-white border border-gray-200 rounded-lg shadow-lg top-14"
                        @click.stop
                    >
                        <!-- Jika role = user -->
                        <template v-if="userRole === 'user'">
                            <a href="/user-profile" class="block px-4 py-2 hover:bg-pink-100">Profile</a>
                        </template>

                        <!-- Jika role = admin -->
                        <template v-else-if="userRole === 'admin'">
                            <a href="/admin" class="block px-4 py-2 hover:bg-pink-100">Dashboard Admin</a>
                        </template>

                        <button 
                            @click="logout" 
                            class="block w-full px-4 py-2 text-left hover:bg-pink-100"
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
import { computed, ref, onMounted, onBeforeUnmount } from 'vue';

const page = usePage();

// Cek login user
const isAuthenticated = computed(() => !!(page.props as any).auth?.user);
const userRole = computed(() => (page.props as any).auth?.user?.role || 'user');

// Dropdown state
const showDropdown = ref(false);

function toggleDropdown() {
    showDropdown.value = !showDropdown.value;
}

function handleClickOutside(e: MouseEvent) {
    const dropdownEl = document.getElementById('navbar-dropdown');

    // Jika dropdown terbuka & klik di luar dropdown
    if (showDropdown.value && dropdownEl && !dropdownEl.contains(e.target as Node)) {
        showDropdown.value = false;
    }
}

function logout() {
    router.post('/logout', {}, { 
        onSuccess: () => router.visit('/'),
        onFinish: () => showDropdown.value = false
    });
}

onMounted(() => {
    window.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    window.removeEventListener('click', handleClickOutside);
});
</script>