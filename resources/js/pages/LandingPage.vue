<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Navbar from '../components/Navbar.vue'
import Hero from '../components/Hero.vue'
import Footer from '../components/Footer.vue'
import MapSection from '../components/MapSection.vue'

const navbarHeight = ref('0px')

onMounted(() => {
  const navbar = document.querySelector('nav')
  if (navbar) {
    navbarHeight.value = navbar.offsetHeight + 'px'
  }
})

const page = usePage() as unknown as { props: { auth: any } }
const isAuthenticated = !!page.props.auth.user

const handlePesanSekarang = () => {
  if (isAuthenticated) {
    // Sudah login langsung ke reservasi
    router.visit('/reservasi')
  } else {
    // Simpan niat user ke localStorage
    localStorage.setItem('afterLoginRedirect', '/reservasi')
    router.visit('/login')
  }
}
</script>

<template>
  <Head title="Landing Page - RonaLaundry" />
  <div class="flex flex-col min-h-screen bg-gray-50 scroll-smooth">
    <Navbar :is-authenticated="isAuthenticated" />

    <main :style="{ marginTop: navbarHeight }" class="flex-grow">
      <!-- Section 1: Hero -->
      <Hero />

      <!-- Section 2: About -->
      <section
        id="about"
        class="relative flex flex-col items-center justify-center w-full min-h-screen px-8 py-24 text-center bg-center bg-no-repeat bg-cover"
        style="background-image: url('/images/pink.jpg');"
      >
        <div class="flex flex-col items-center max-w-6xl gap-10 px-6 mx-auto md:flex-row">
          <!-- Gambar kiri -->
          <div class="flex justify-center flex-1">
            <img
              src="/images/street.jpg"
              alt="Rona Laundry"
              class="object-cover rounded-lg shadow-lg w-80 md:w-96"
            />
          </div>

          <!-- Teks kanan -->
          <div class="flex-1 text-gray-800 md:text-left md:pl-10">
            <h2 class="mb-4 text-3xl font-semibold text-center md:text-left">
              About <span class="text-pink-600">RonaLaundry</span>
            </h2>
            <p class="mb-3 leading-relaxed">
              Di <span class="font-semibold">Rona Laundry</span>, kami percaya bahwa pakaian bersih
              bukan sekadar kebutuhan, tapi juga cerminan gaya hidup sehat dan nyaman. Karena itu, kami menghadirkan layanan laundry kiloan dan satuan yang cepat, higienis, dan wangi tahan lama.
            </p>
            <p class="mb-5 leading-relaxed">
              <span class="font-semibold">Rona Laundry</span> juga melayani pelanggan dari berbagai kalangan,
              mulai dari keluarga, mahasiswa, hingga komunitas. Kami siap membantu siapa pun yang ingin menghemat waktu tanpa khawatir soal cucian.
            </p>
            <div class="flex items-center gap-3 mt-4">
              <span class="text-2xl">⭐</span>
              <span class="italic font-medium text-gray-700">Jangan Ragu Jangan Bimbang</span>
            </div>
          </div>
        </div>

        <!-- Section Metode Pembayaran -->
        <div class="px-4 py-12 mt-20 text-center shadow-lg bg-white/80 rounded-xl backdrop-blur-sm">
          <h3 class="mb-8 text-xl font-semibold text-gray-800">
            Mau QRIS, transfer, atau cash? <br />
            Semua gampang tinggal gas!
          </h3>

          <!-- 🔹 Grid responsif -->
          <div class="grid grid-cols-2 gap-10 mt-6 sm:grid-cols-2 md:grid-cols-4 justify-items-center mr-25 ml-25">
            <div class="flex flex-col items-center">
              <img
                src="/images/qris.webp"
                alt="QRIS"
                class="object-contain w-32 h-32 p-3 transition-transform duration-300 shadow-lg md:w-40 md:h-40 rounded-xl bg-gray-50 hover:scale-105"
              />
              <p class="mt-3 font-medium text-gray-700">QRIS</p>
            </div>

            <div class="flex flex-col items-center">
              <img
                src="/images/bsi.png"
                alt="BSI"
                class="object-contain w-32 h-32 p-3 transition-transform duration-300 shadow-lg md:w-40 md:h-40 rounded-xl bg-gray-50 hover:scale-105"
              />
              <p class="mt-3 font-medium text-gray-700">BSI</p>
            </div>

            <div class="flex flex-col items-center">
              <img
                src="/images/bni.jpg"
                alt="BNI"
                class="object-contain w-32 h-32 p-3 transition-transform duration-300 shadow-lg md:w-40 md:h-40 rounded-xl bg-gray-50 hover:scale-105"
              />
              <p class="mt-3 font-medium text-gray-700">BNI</p>
            </div>

            <div class="flex flex-col items-center">
              <img
                src="/images/cash.png"
                alt="Cash"
                class="object-contain w-32 h-32 p-3 transition-transform duration-300 shadow-lg md:w-40 md:h-40 rounded-xl bg-gray-50 hover:scale-105"
              />
              <p class="mt-3 font-medium text-gray-700">Cash</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Section 3: CTA + Testimoni -->
      <section class="py-20 text-center bg-gradient-to-b from-pink-100 to-pink-200">
        <button
          class="px-12 py-4 mb-16 text-lg font-semibold text-white transition bg-blue-400 rounded-lg shadow-md hover:bg-blue-500"
          @click="handlePesanSekarang"
        >
          PESAN SEKARANG
        </button>

        <h3 class="mb-10 text-2xl font-semibold text-gray-800">APA KATA MEREKA?</h3>

        <div class="grid max-w-5xl grid-cols-1 gap-8 px-6 mx-auto md:grid-cols-3">
          <div class="p-6 text-gray-700 bg-white rounded-lg shadow">
            <img
              src="/images/cat.jpg"
              alt="User"
              class="object-cover w-16 h-16 mx-auto mb-4 rounded-full"
            />
            <div class="flex justify-center mb-3 text-yellow-400">
              <span v-for="i in 5" :key="i">★</span>
            </div>
            <p>Cepat, tepat, wangi, rapi. Jadi ga perlu mikirin cucian lagi deh.</p>
          </div>

          <div class="p-6 text-gray-700 bg-white rounded-lg shadow">
            <img
              src="/images/chris.png"
              alt="User"
              class="object-cover w-16 h-16 mx-auto mb-4 rounded-full"
            />
            <div class="flex justify-center mb-3 text-yellow-400">
              <span v-for="i in 5" :key="i">★</span>
            </div>
            <p>Pelayanan sangat mantap dan tepat, karena waktu yang saya tentukan.</p>
          </div>

          <div class="p-6 text-gray-700 bg-white rounded-lg shadow">
            <img
              src="/images/asuna.jpg"
              alt="User"
              class="object-cover w-16 h-16 mx-auto mb-4 rounded-full"
            />
            <div class="flex justify-center mb-3 text-yellow-400">
              <span v-for="i in 5" :key="i">★</span>
            </div>
            <p>Pelayanan sangat baik!</p>
          </div>
        </div>
      </section>
    </main>

    <MapSection />
    <Footer />
  </div>
</template>