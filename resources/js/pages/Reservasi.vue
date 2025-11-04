<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import Navbar from '../components/Navbar.vue'
import Footer from '../components/Footer.vue'
import Hero from '../components/Hero1.vue'
import { ref } from 'vue'

const nama = ref('')
const whatsapp = ref('')
const layanan = ref('')
const barang = ref('')
const alamat = ref('')
const pembayaran = ref('')
const tanggal = ref('')

const submitForm = () => {
  alert('Pesanan berhasil disimpan! 😊')
}
</script>

<template>
  <Head title="Reservasi - RonaLaundry" />
  <div class="flex flex-col min-h-screen bg-pink-100 pt-15">
    <!-- Navbar -->
    <Navbar />

    <!-- Hero Section -->
    <Hero />

    <!-- Main Content -->
    <main class="flex items-start justify-center flex-grow px-6 py-16">
      <div
        class="w-full max-w-3xl p-10 bg-white border border-pink-200 shadow-lg rounded-2xl"
      >
        <h2 class="mb-8 text-3xl font-semibold text-center text-gray-800">
          Reservasi Layanan
        </h2>

        <!-- Form -->
        <form
          @submit.prevent="submitForm"
          class="grid grid-cols-1 text-gray-700 gap-y-5"
        >
          <!-- Nama -->
          <div>
            <label class="block mb-1 font-medium">Nama Lengkap</label>
            <input
              v-model="nama"
              type="text"
              placeholder="Masukkan nama Anda"
              class="w-full p-3 border border-gray-300 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-pink-400"
              required
            />
          </div>

          <!-- WhatsApp -->
          <div>
            <label class="block mb-1 font-medium">Nomor WhatsApp</label>
            <input
              v-model="whatsapp"
              type="tel"
              placeholder="08123456789"
              inputmode="numeric"
              pattern="[0-9]*"
              maxlength="15"
              class="w-full p-3 border border-gray-300 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-pink-400"
              @input="whatsapp = whatsapp.replace(/[^0-9]/g, '')"
              required
            />
          </div>

          <!-- Layanan -->
          <div>
            <label class="block mb-2 font-medium">Pilih Jenis Layanan</label>
            <div class="grid grid-cols-3 gap-3">
              <button
                v-for="option in ['Kiloan', 'Boneka', 'Karpet', 'Sepatu', 'Setrika', 'Handuk']"
                :key="option"
                type="button"
                @click="layanan = option"
                :class="[
                  'p-3 rounded-lg font-medium shadow-sm transition-all',
                  layanan === option
                    ? 'bg-pink-400 text-white'
                    : 'bg-gray-100 hover:bg-gray-200 text-gray-700'
                ]"
              >
                {{ option }}
              </button>
            </div>
          </div>

          <!-- Barang -->
          <div>
            <label class="block mb-1 font-medium">Jenis Barang</label>
            <input
              v-model="barang"
              type="text"
              placeholder="Contoh: Sprei, Jaket, Bantal"
              class="w-full p-3 border border-gray-300 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-pink-400"
              required
            />
          </div>

          <!-- Alamat -->
          <div>
            <label class="block mb-1 font-medium">Alamat</label>
            <textarea
              v-model="alamat"
              rows="2"
              placeholder="Masukkan alamat lengkap Anda"
              class="w-full p-3 border border-gray-300 rounded-md shadow-sm outline-none resize-none focus:ring-2 focus:ring-pink-400"
              required
            ></textarea>
          </div>

          <!-- Pembayaran -->
          <div>
            <label class="block mb-2 font-medium">Metode Pembayaran</label>
            <div class="grid grid-cols-3 gap-3">
              <button
                v-for="option in ['QRIS', 'Transfer', 'Cash']"
                :key="option"
                type="button"
                @click="pembayaran = option"
                :class="[
                  'p-3 rounded-lg font-medium shadow-sm transition-all',
                  pembayaran === option
                    ? 'bg-blue-400 text-white'
                    : 'bg-gray-100 hover:bg-gray-200 text-gray-700'
                ]"
              >
                {{ option }}
              </button>
            </div>
          </div>

          <!-- Tanggal -->
          <div>
            <label class="block mb-1 font-medium">Tanggal Pengambilan</label>
            <input
              v-model="tanggal"
              type="date"
              class="w-full p-3 border border-gray-300 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-pink-400"
              required
            />
          </div>

          <!-- Tombol Submit -->
          <div class="flex justify-center mt-8">
            <button
              type="submit"
              class="bg-blue-400 hover:bg-blue-500 focus:ring-4 focus:ring-blue-200 text-white font-semibold px-10 py-3 rounded-md shadow-md transition-all transform hover:scale-[1.03]"
            >
              Selesai Pesanan
            </button>
          </div>
        </form>

        <!-- Ringkasan Pesanan -->
        <div
          v-if="nama || layanan"
          class="p-5 mt-10 border border-gray-200 rounded-lg shadow-inner bg-gray-50"
        >
          <h3 class="mb-2 font-semibold text-gray-800">Ringkasan Pesanan:</h3>
          <ul class="space-y-1 text-sm text-gray-700">
            <li><strong>Nama:</strong> {{ nama }}</li>
            <li><strong>WhatsApp:</strong> {{ whatsapp }}</li>
            <li><strong>Layanan:</strong> {{ layanan }}</li>
            <li><strong>Barang:</strong> {{ barang }}</li>
            <li><strong>Alamat:</strong> {{ alamat }}</li>
            <li><strong>Pembayaran:</strong> {{ pembayaran }}</li>
            <li><strong>Tanggal:</strong> {{ tanggal }}</li>
          </ul>
        </div>
      </div>
    </main>

    <Footer />
  </div>
</template>

<style scoped>
main {
  font-family: "Poppins", sans-serif;
}
</style>
