<template>
  <section class="flex flex-col">

    <!-- 🔹 Hero Utama -->
    <div class="bg-pink-100 min-h-screen flex items-center justify-center px-6 md:px-10">
      <div class="flex flex-col md:flex-row items-center justify-center gap-4 md:gap-6 w-full max-w-5xl">
        <!-- 🔸 Teks -->
        <div class="md:w-1/2 text-right">
          <h1 class="text-3xl md:text-5xl font-serif text-gray-800 leading-snug md:leading-tight">
            No.1 <br />
            Laundry <br />
            Express di <br />
            Laweyan
          </h1>
        </div>

        <!-- 🔸 Gambar -->
        <div class="md:w-1/2 flex justify-center md:justify-start">
          <img
            src="/images/laundry.png"
            alt="Laundry Illustration"
            class="w-[50%] md:w-[20rem] drop-shadow-lg"
          />
        </div>
      </div>
    </div>

    <!-- 🔹 Grid Layanan -->
    <div class="bg-white py-24 flex justify-center items-center">
      <div class="max-w-6xl mx-auto px-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-x-18 gap-y-12">
        <div
          v-for="(item, index) in items"
          :key="index"
          class="flex flex-col items-center rounded-2xl p-8 cursor-pointer hover:scale-105 transition"
          :class="item.bg"
          @click="openModal(item)"
        >
          <img :src="item.img" :alt="item.name" class="w-24 mb-4" />
          <p class="font-serif text-xl text-gray-800">{{ item.name }}</p>
        </div>
      </div>
    </div>

    <!-- 🔹 Modal -->
    <div
      v-if="selectedItem"
      class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 backdrop-blur-sm"
      @click.self="closeModal"
    >
      <div class="bg-white rounded-2xl shadow-xl p-8 max-w-xxl w-fulll text-center relative">
        <img :src="selectedItem.img" :alt="selectedItem.name" class="w-40 mx-auto mb-7" />
        <h2 class="text-2xl font-semibold mb-2 text-gray-800">{{ selectedItem.name }}</h2>
        <p class="text-gray-700 text-lg leading-relaxed mb-4">{{ selectedItem.desc }}</p>
        <p class="text-lg font-bold text-pink-600 mb-6">💸 {{ selectedItem.price }}</p>
        <button
          @click="closeModal"
          class="bg-pink-500 hover:bg-pink-600 text-white px-6 py-2 rounded-lg shadow-md transition"
        >
          Tutup
        </button>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const items = [
  {
    name: 'Selimut',
    img: '/images/selimut1.png',
    price: 'Rp 3.000 / pcs',
    bg: 'bg-pink-200',
    desc: 'Cuci selimut jadi lebih segar dan lembut, bebas dari debu dan bau apek. Dijamin wangi tahan lama!',
  },
  {
    name: 'Boneka',
    img: '/images/boneka.png',
    price: 'Rp 5.000 / pcs',
    bg: 'bg-blue-200',
    desc: 'Boneka kesayanganmu dicuci lembut dengan cairan khusus agar tetap halus, bersih, dan wangi seperti baru.',
  },
  {
    name: 'Karpet',
    img: '/images/karpet.png',
    price: 'Rp 5.000 / meter',
    bg: 'bg-pink-200',
    desc: 'Karpet bersih bebas debu dan noda, dicuci dengan teknik khusus agar tetap awet dan segar.',
  },
  {
    name: 'Sepatu',
    img: '/images/sepatu.png',
    price: 'Rp 10.000 / pasang',
    bg: 'bg-blue-200',
    desc: 'Perawatan sepatu profesional, bersih luar dalam tanpa merusak bahan. Cocok untuk semua jenis sepatu!',
  },
  {
    name: 'Alat Ibadah',
    img: '/images/alatIbadah.png',
    price: 'Rp 3.000 / pcs',
    bg: 'bg-pink-200',
    desc: 'Bersihkan mukena, sarung, dan sajadah dengan lembut agar tetap suci, harum, dan nyaman digunakan.',
  },
  {
    name: 'Handuk',
    img: '/images/handuk.png',
    price: 'Rp 3.000 / pcs',
    bg: 'bg-blue-200',
    desc: 'Handukmu bakal bersih dan wangi segar lagi, tanpa serat kasar! Nyaman dipakai setiap kali mandi.',
  },
]

const selectedItem = ref(null)

const openModal = (item) => {
  selectedItem.value = item
}

const closeModal = () => {
  selectedItem.value = null
}
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&display=swap");

h1,
p {
  font-family: "Playfair Display", serif;
}
</style>