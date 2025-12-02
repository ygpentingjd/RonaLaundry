<script setup lang="ts">
import Navbar from '../components/Navbar.vue'
import Footer from '../components/Footer.vue'
import Hero from '../components/Hero1.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps<{
    user?: {
        nama_lengkap?: string;
        nomor_telepon?: string;
        alamat_lengkap?: string;
    }
}>();

const nama = ref(props.user?.nama_lengkap || '')
const whatsapp = ref(props.user?.nomor_telepon || '')
const layanan = ref('')
const barang = ref<string[]>([])
const alamat = ref(props.user?.alamat_lengkap || '')
const pembayaran = ref('')
const metode_pengantaran = ref('')
const tanggal = ref(new Date().toISOString().split('T')[0])
const tanggal_kembali = ref('')
const pesan = ref('')

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
    metode_pengantaran: metode_pengantaran.value,
    tanggal: tanggal.value,
    tanggal_kembali: tanggal_kembali.value,
    pesan: pesan.value
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
      metode_pengantaran.value = ''
      tanggal.value = ''
      tanggal_kembali.value = ''
      pesan.value = ''
    },
    onError: (errors) => {
      console.error(errors)
      alert('Gagal menyimpan pesanan. Periksa kembali data Anda.')
    }
  })
}
</script>

<template>
  <div class="flex flex-col min-h-screen bg-blue-50 pt-15">
    <!-- Navbar -->
    <Navbar />

    <!-- Hero Section -->
    <Hero />

    <!-- Main Content -->
    <main class="flex items-start justify-center flex-grow px-6 py-16">
      <div
        class="w-full max-w-3xl p-10 bg-white border border-blue-200 shadow-lg rounded-2xl"
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
              class="w-full p-3 border border-gray-300 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-blue-400"
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
              class="w-full p-3 border border-gray-300 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-blue-400"
              @input="whatsapp = whatsapp.replace(/[^0-9]/g, '')"
              required
            />
          </div>

          <!-- Jenis Layanan -->
          <div>
            <label class="block mb-2 font-medium">Pilih Jenis Layanan</label>
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
            <label class="block mb-1 font-medium">Jenis Barang</label>
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
            <label class="block mb-1 font-medium">Alamat</label>
            <textarea
              v-model="alamat"
              rows="2"
              placeholder="Masukkan alamat lengkap Anda"
              class="w-full p-3 border border-gray-300 rounded-md shadow-sm outline-none resize-none focus:ring-2 focus:ring-blue-400"
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

          <!-- Metode Pengantaran -->
          <div>
            <label class="block mb-2 font-medium">Metode Pengantaran</label>
              <div class="grid grid-cols-2 gap-3">
                <button
                  v-for="option in ['Ambil di Tempat', 'Diantar']"
                  :key="option"
                  type="button"
                  @click="metode_pengantaran = option"
                  :class="[
                    'p-3 rounded-lg font-medium shadow-sm transition-all border',
                    metode_pengantaran === option
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
            <label class="block mb-1 font-medium">Tanggal Masuk</label>
            <input
              v-model="tanggal"
              type="date"
              class="w-full p-3 border border-gray-300 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-blue-400"
              readonly
            />
          </div>

          <div>
            <label class="block mb-1 font-medium">Tanggal Pengambilan</label>
            <input
              v-model="tanggal_kembali"
              type="date"
              class="w-full p-3 border border-gray-300 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-blue-400"
              required
            />
          </div>

          <div>
            <label class="block mb-1 font-medium text-black-800">
              Pesan Khusus (Optional)
            </label>
            <textarea v-model="pesan" rows="3"
              placeholder="Contoh: Cuciannya sudah saya taruh di depan pintu, nanti tolong diambil ya. Terima kasih 😊"
              class="w-full p-3 transition bg-white border border-pink-200 rounded-lg shadow-sm outline-none resize-none focus:ring-2 focus:ring-pink-400 focus:border-pink-400"></textarea>
          </div>

          <!-- Tombol Submit -->
          <div class="flex justify-center mt-8">
            <button
              type="submit"
              class="px-10 py-3 font-semibold text-white transition-all transform rounded-full shadow-md bg-gradient-to-r from-blue-500 to-blue-600 hover:scale-105"
            >
              Selesai Pesanan
            </button>
          </div>
        </form>

        <!-- Ringkasan Pesanan -->
        <div
          v-if="nama || layanan"
          class="p-5 mt-10 border border-blue-200 rounded-lg shadow-inner bg-blue-50"
        >
          <h3 class="mb-2 font-semibold text-gray-800">Ringkasan Pesanan:</h3>
          <ul class="space-y-1 text-sm text-gray-700">
            <li><strong>Nama:</strong> {{ nama }}</li>
            <li><strong>WhatsApp:</strong> {{ whatsapp }}</li>
            <li><strong>Layanan:</strong> {{ layanan }}</li>
            <li><strong>Barang:</strong> {{ barang.join(', ') }}</li>
            <li><strong>Alamat:</strong> {{ alamat }}</li>
            <li><strong>Pembayaran:</strong> {{ pembayaran }}</li>
            <li><strong>Pengantaran:</strong> {{ metode_pengantaran }}</li>
            <li><strong>Tanggal Masuk:</strong> {{ tanggal }}</li>
            <li><strong>Tanggal Pengambilan:</strong> {{ tanggal_kembali }}</li>
            <li><strong>Pesan Tambahan:</strong> {{ pesan }}</li>
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