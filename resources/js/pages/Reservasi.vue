<script setup lang="ts">
import Navbar from '../components/Navbar.vue'
import Footer from '../components/Footer.vue'
import Hero from '../components/Hero1.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const nama = ref('')
const whatsapp = ref('')
const layanan = ref('')
const barang = ref<string[]>([])
const alamat = ref('')
const pembayaran = ref('')
const tanggal = ref('')

const barangOptions = ['Selimut', 'Boneka', 'Karpet', 'Sepatu', 'Alat Ibadah', 'Handuk']

const toggleBarang = (item: string) => {
  if (barang.value.includes(item)) {
    barang.value = barang.value.filter((b) => b !== item)
  } else {
    barang.value.push(item)
  }
}

const submitForm = () => {
  router.post('/reservasi/store', {
    nama: nama.value,
    whatsapp: whatsapp.value,
    layanan: layanan.value,
    barang: barang.value,
    alamat: alamat.value,
    pembayaran: pembayaran.value,
    tanggal: tanggal.value
  }, {
    onSuccess: () => {
      alert('Pesanan berhasil disimpan! 😊')
      // Reset form setelah submit
      nama.value = ''
      whatsapp.value = ''
      layanan.value = ''
      barang.value = []
      alamat.value = ''
      pembayaran.value = ''
      tanggal.value = ''
    },
    onError: (errors) => {
      console.error(errors)
      alert('Gagal menyimpan pesanan. Periksa kembali data Anda.')
    }
  })
}
</script>

<template>
  <div class="bg-blue-50 min-h-screen flex flex-col pt-15">
    <!-- Navbar -->
    <Navbar />

    <!-- Hero Section -->
    <Hero />

    <!-- Main Content -->
    <main class="flex-grow py-16 px-6 flex justify-center items-start">
      <div
        class="bg-white shadow-lg rounded-2xl p-10 w-full max-w-3xl border border-blue-200"
      >
        <h2 class="text-3xl font-semibold mb-8 text-gray-800 text-center">
          Reservasi Layanan
        </h2>

        <!-- Form -->
        <form
          @submit.prevent="submitForm"
          class="grid grid-cols-1 gap-y-5 text-gray-700"
        >
          <!-- Nama -->
          <div>
            <label class="block font-medium mb-1">Nama Lengkap</label>
            <input
              v-model="nama"
              type="text"
              placeholder="Masukkan nama Anda"
              class="w-full p-3 rounded-md border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-400 outline-none"
              required
            />
          </div>

          <!-- WhatsApp -->
          <div>
            <label class="block font-medium mb-1">Nomor WhatsApp</label>
            <input
              v-model="whatsapp"
              type="tel"
              placeholder="08123456789"
              inputmode="numeric"
              pattern="[0-9]*"
              maxlength="15"
              class="w-full p-3 rounded-md border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-400 outline-none"
              @input="whatsapp = whatsapp.replace(/[^0-9]/g, '')"
              required
            />
          </div>

          <!-- Jenis Layanan -->
          <div>
            <label class="block font-medium mb-2">Pilih Jenis Layanan</label>
            <div class="grid grid-cols-3 gap-3">
              <button
                v-for="option in ['Biasa', 'Kilat']"
                :key="option"
                type="button"
                @click="layanan = option"
                :class="[ 
                  'p-3 rounded-lg font-medium shadow-sm transition-all border',
                  layanan === option
                    ? 'bg-blue-500 text-white border-blue-500 shadow-md'
                    : 'bg-gray-100 text-gray-700 border-gray-200 hover:bg-blue-100 hover:border-blue-300'
                ]"
              >
                {{ option }}
              </button>
            </div>
          </div>

          <!-- Jenis Barang -->
          <div>
            <label class="block font-medium mb-1">Jenis Barang</label>
            <div class="grid grid-cols-3 gap-3">
              <button
                v-for="item in barangOptions"
                :key="item"
                type="button"
                @click="toggleBarang(item)"
                :class="[
                  'p-3 rounded-lg font-medium shadow-sm transition-all border',
                  barang.includes(item)
                    ? 'bg-blue-500 text-white border-blue-500 shadow-md'
                    : 'bg-gray-100 text-gray-700 border-gray-200 hover:bg-blue-100 hover:border-blue-300'
                ]"
              >
                {{ item }}
              </button>
            </div>
          </div>

          <!-- Alamat -->
          <div>
            <label class="block font-medium mb-1">Alamat</label>
            <textarea
              v-model="alamat"
              rows="2"
              placeholder="Masukkan alamat lengkap Anda"
              class="w-full p-3 rounded-md border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-400 outline-none resize-none"
              required
            ></textarea>
          </div>

          <!-- Pembayaran -->
          <div>
            <label class="block font-medium mb-2">Metode Pembayaran</label>
            <div class="grid grid-cols-3 gap-3">
              <button
                v-for="option in ['QRIS', 'Transfer', 'Cash']"
                :key="option"
                type="button"
                @click="pembayaran = option"
                :class="[
                  'p-3 rounded-lg font-medium shadow-sm transition-all border',
                  pembayaran === option
                    ? 'bg-blue-500 text-white border-blue-500 shadow-md'
                    : 'bg-gray-100 text-gray-700 border-gray-200 hover:bg-blue-100 hover:border-blue-300'
                ]"
              >
                {{ option }}
              </button>
            </div>
          </div>

          <!-- Tanggal -->
          <div>
            <label class="block font-medium mb-1">Tanggal Pengambilan</label>
            <input
              v-model="tanggal"
              type="date"
              class="w-full p-3 rounded-md border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-400 outline-none"
              required
            />
          </div>

          <!-- Tombol Submit -->
          <div class="flex justify-center mt-8">
            <button
              type="submit"
              class="bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold px-10 py-3 rounded-full shadow-md hover:scale-105 transition-all transform"
            >
              Selesai Pesanan
            </button>
          </div>
        </form>

        <!-- Ringkasan Pesanan -->
        <div
          v-if="nama || layanan"
          class="bg-blue-50 p-5 rounded-lg mt-10 shadow-inner border border-blue-200"
        >
          <h3 class="font-semibold mb-2 text-gray-800">Ringkasan Pesanan:</h3>
          <ul class="space-y-1 text-gray-700 text-sm">
            <li><strong>Nama:</strong> {{ nama }}</li>
            <li><strong>WhatsApp:</strong> {{ whatsapp }}</li>
            <li><strong>Layanan:</strong> {{ layanan }}</li>
            <li><strong>Barang:</strong> {{ barang.join(', ') }}</li>
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