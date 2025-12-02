<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Footer from '../components/Footer.vue';
import Navbar from '../components/Navbar.vue';

// 🔹 Data dummy bertema laundry
const orders = ref([
    {
        id: 1,
        tanggal: '2025-10-20',
        barang: 'Cuci Kering Pakaian',
        jumlah: 5,
        status: 'Selesai',
        harga: 'Rp 25.000',
    },
    {
        id: 2,
        tanggal: '2025-10-22',
        barang: 'Setrika Baju',
        jumlah: 10,
        status: 'Diproses',
        harga: 'Rp 15.000',
    },
    {
        id: 3,
        tanggal: '2025-10-24',
        barang: 'Cuci Sepatu',
        jumlah: 2,
        status: 'Dikirim',
        harga: 'Rp 30.000',
    },
    {
        id: 4,
        tanggal: '2025-10-25',
        barang: 'Cuci Karpet Besar',
        jumlah: 1,
        status: 'Selesai',
        harga: 'Rp 50.000',
    },
    {
        id: 5,
        tanggal: '2025-10-27',
        barang: 'Laundry Bed Cover',
        jumlah: 2,
        status: 'Diproses',
        harga: 'Rp 40.000',
    },
]);

function goBack() {
    router.visit('/landing'); // arahkan sesuai halaman utama kamu
}
</script>

<template>
    <Head title="Pesanan Saya - RonaLaundry" />
    <div class="flex min-h-screen flex-col bg-pink-100">
        <!-- 🔹 Navbar -->
        <Navbar />

        <!-- 🔹 Konten Utama -->
        <main
            class="flex flex-grow flex-col items-center justify-start px-6 py-16 pt-25"
        >
            <div class="w-full max-w-4xl rounded-xl bg-pink-200 p-10 shadow-md">
                <h2 class="mb-6 text-xl font-semibold text-gray-800">
                    Pesanan Saya
                </h2>

                <!-- 🔹 Tabel -->
                <div class="overflow-x-auto">
                    <table
                        class="w-full border border-gray-400 bg-pink-100 text-center text-gray-800"
                    >
                        <thead class="bg-pink-300 text-gray-900">
                            <tr>
                                <th class="border border-gray-400 px-4 py-2">
                                    Tgl
                                </th>
                                <th class="border border-gray-400 px-4 py-2">
                                    Layanan
                                </th>
                                <th class="border border-gray-400 px-4 py-2">
                                    Jumlah
                                </th>
                                <th class="border border-gray-400 px-4 py-2">
                                    Status
                                </th>
                                <th class="border border-gray-400 px-4 py-2">
                                    Harga
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="order in orders"
                                :key="order.id"
                                class="transition hover:bg-pink-200"
                            >
                                <td class="border border-gray-400 px-4 py-2">
                                    {{ order.tanggal }}
                                </td>
                                <td class="border border-gray-400 px-4 py-2">
                                    {{ order.barang }}
                                </td>
                                <td class="border border-gray-400 px-4 py-2">
                                    {{ order.jumlah }}
                                </td>
                                <td
                                    class="border border-gray-400 px-4 py-2 font-medium"
                                >
                                    <span
                                        :class="{
                                            'text-green-600':
                                                order.status === 'Selesai',
                                            'text-yellow-600':
                                                order.status === 'Diproses',
                                            'text-blue-600':
                                                order.status === 'Dikirim',
                                        }"
                                    >
                                        {{ order.status }}
                                    </span>
                                </td>
                                <td class="border border-gray-400 px-4 py-2">
                                    {{ order.harga }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- 🔹 Bagian bawah -->
                <div class="mt-10 flex items-center justify-between">
                    <button
                        @click="goBack"
                        class="flex items-center gap-2 rounded-md border border-gray-600 px-4 py-2 text-gray-700 transition hover:bg-gray-100"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                        Kembali
                    </button>

                    <p
                        class="cursor-pointer font-medium text-gray-800 hover:underline"
                    >
                        Lanjut Lainnya
                    </p>
                </div>
            </div>
        </main>

        <!-- 🔹 Footer -->
        <Footer />
    </div>
</template>

<style scoped>
table {
    border-collapse: collapse;
}

th,
td {
    border: 1px solid #b0b0b0;
}

th {
    font-weight: 600;
}
</style>
