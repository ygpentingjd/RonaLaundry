<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import Navbar from '../components/Navbar.vue'
import Footer from '../components/Footer.vue'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

// 🔹 Data dummy bertema laundry
const orders = ref([
  { id: 1, tanggal: '2025-10-20', barang: 'Cuci Kering Pakaian', jumlah: 5, status: 'Selesai', harga: 'Rp 25.000' },
  { id: 2, tanggal: '2025-10-22', barang: 'Setrika Baju', jumlah: 10, status: 'Diproses', harga: 'Rp 15.000' },
  { id: 3, tanggal: '2025-10-24', barang: 'Cuci Sepatu', jumlah: 2, status: 'Dikirim', harga: 'Rp 30.000' },
  { id: 4, tanggal: '2025-10-25', barang: 'Cuci Karpet Besar', jumlah: 1, status: 'Selesai', harga: 'Rp 50.000' },
  { id: 5, tanggal: '2025-10-27', barang: 'Laundry Bed Cover', jumlah: 2, status: 'Diproses', harga: 'Rp 40.000' },
])

function goBack() {
  router.visit('/landing') // arahkan sesuai halaman utama kamu
}
</script>

<template>
  <Head title="Pesanan Saya - RonaLaundry" />
  <div class="flex flex-col min-h-screen bg-pink-100">
    <!-- 🔹 Navbar -->
    <Navbar />

    <!-- 🔹 Konten Utama -->
    <main class="flex flex-col items-center justify-start flex-grow px-6 py-16 pt-25">
      <div class="w-full max-w-4xl p-10 bg-pink-200 shadow-md rounded-xl">
        <h2 class="mb-6 text-xl font-semibold text-gray-800">Pesanan Saya</h2>

        <!-- 🔹 Tabel -->
        <div class="overflow-x-auto">
          <table class="w-full text-center text-gray-800 bg-pink-100 border border-gray-400">
            <thead class="text-gray-900 bg-pink-300">
              <tr>
                <th class="px-4 py-2 border border-gray-400">Tgl</th>
                <th class="px-4 py-2 border border-gray-400">Layanan</th>
                <th class="px-4 py-2 border border-gray-400">Jumlah</th>
                <th class="px-4 py-2 border border-gray-400">Status</th>
                <th class="px-4 py-2 border border-gray-400">Harga</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="order in orders"
                :key="order.id"
                class="transition hover:bg-pink-200"
              >
                <td class="px-4 py-2 border border-gray-400">{{ order.tanggal }}</td>
                <td class="px-4 py-2 border border-gray-400">{{ order.barang }}</td>
                <td class="px-4 py-2 border border-gray-400">{{ order.jumlah }}</td>
                <td class="px-4 py-2 font-medium border border-gray-400">
                  <span
                    :class="{
                      'text-green-600': order.status === 'Selesai',
                      'text-yellow-600': order.status === 'Diproses',
                      'text-blue-600': order.status === 'Dikirim'
                    }"
                  >
                    {{ order.status }}
                  </span>
                </td>
                <td class="px-4 py-2 border border-gray-400">{{ order.harga }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- 🔹 Bagian bawah -->
        <div class="flex items-center justify-between mt-10">
          <button
            @click="goBack"
            class="flex items-center gap-2 px-4 py-2 text-gray-700 transition border border-gray-600 rounded-md hover:bg-gray-100"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
          </button>

          <p class="font-medium text-gray-800 cursor-pointer hover:underline">Lanjut Lainnya</p>
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

th, td {
  border: 1px solid #b0b0b0;
}

th {
  font-weight: 600;
}
</style>
