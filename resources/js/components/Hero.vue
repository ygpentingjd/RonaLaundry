<template>
  <section class="flex flex-col">

    <!-- 🔹 Hero Utama -->
    <div class="flex items-center justify-center min-h-screen px-6 bg-pink-100 md:px-10">
      <div class="flex flex-col items-center justify-center w-full max-w-5xl gap-4 md:flex-row md:gap-6">
        <!-- 🔸 Teks -->
        <div class="text-right md:w-1/2">
          <h1 class="font-serif text-3xl leading-snug text-gray-800 md:text-5xl md:leading-tight">
            No.1 <br />
            Laundry <br />
            Express di <br />
            Laweyan
          </h1>
        </div>

        <!-- 🔸 Gambar -->
        <div class="flex justify-center md:w-1/2 md:justify-start">
          <img
            src="/images/laundry.png"
            alt="Laundry Illustration"
            class="w-[50%] md:w-[20rem] drop-shadow-lg"
          />
        </div>
      </div>
    </div>

    <!-- 🔹 Grid Layanan -->
    <div class="flex items-center justify-center py-24 bg-white">
      <div class="grid max-w-6xl grid-cols-2 px-6 mx-auto sm:grid-cols-3 md:grid-cols-3 gap-x-18 gap-y-12">
        <div
          v-for="(item, index) in displayItems"
          :key="index"
          class="flex flex-col items-center p-8 transition cursor-pointer rounded-2xl hover:scale-105"
          :class="getBgClass(index)"
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
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
      @click.self="closeModal"
    >
      <div class="relative p-8 text-center bg-white shadow-xl rounded-2xl max-w-xxl w-fulll">
        <img :src="selectedItem.img" :alt="selectedItem.name" class="w-40 mx-auto mb-7" />
        <h2 class="mb-2 text-2xl font-semibold text-gray-800">{{ selectedItem.name }}</h2>
        <p class="mb-4 text-lg leading-relaxed text-gray-700">{{ selectedItem.desc }}</p>
        <p class="mb-6 text-lg font-bold text-pink-600">💸 {{ selectedItem.price }}</p>
        <button
          @click="closeModal"
          class="px-6 py-2 text-white transition bg-pink-500 rounded-lg shadow-md hover:bg-pink-600"
        >
          Tutup
        </button>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'

import { computed } from 'vue'

const props = defineProps<{
    products?: any[];
}>();

interface Item {
    name: string;
    img: string;
    price: string;
    desc: string;
}

const displayItems = computed(() => {
    if (props.products && props.products.length > 0) {
        return props.products.map(p => ({
            name: p.nama_barang,
            img: p.image ? `/storage/${p.image}` : '/images/no-image.png', // Fallback image
            price: `Rp ${Number(p.harga_reguler).toLocaleString()} / ${p.satuan}`,
            desc: p.deskripsi || 'Layanan terbaik untuk Anda.',
        }));
    }
    // Fallback to static items if no products provided (optional, or return empty)
    return [
      {
        name: 'Selimut',
        img: '/images/selimut1.png',
        price: 'Rp 3.000 / pcs',
        desc: 'Cuci selimut jadi lebih segar dan lembut, bebas dari debu dan bau apek. Dijamin wangi tahan lama!',
      },
      {
        name: 'Boneka',
        img: '/images/boneka.png',
        price: 'Rp 5.000 / pcs',
        desc: 'Boneka kesayanganmu dicuci lembut dengan cairan khusus agar tetap halus, bersih, dan wangi seperti baru.',
      },
      {
        name: 'Karpet',
        img: '/images/karpet.png',
        price: 'Rp 5.000 / meter',
        desc: 'Karpet bersih bebas debu dan noda, dicuci dengan teknik khusus agar tetap awet dan segar.',
      },
      {
        name: 'Sepatu',
        img: '/images/sepatu.png',
        price: 'Rp 10.000 / pasang',
        desc: 'Perawatan sepatu profesional, bersih luar dalam tanpa merusak bahan. Cocok untuk semua jenis sepatu!',
      },
      {
        name: 'Alat Ibadah',
        img: '/images/alatIbadah.png',
        price: 'Rp 3.000 / pcs',
        desc: 'Bersihkan mukena, sarung, dan sajadah dengan lembut agar tetap suci, harum, dan nyaman digunakan.',
      },
      {
        name: 'Handuk',
        img: '/images/handuk.png',
        price: 'Rp 3.000 / pcs',
        desc: 'Handukmu bakal bersih dan wangi segar lagi, tanpa serat kasar! Nyaman dipakai setiap kali mandi.',
      },
    ];
});

const getBgClass = (index: number) => {
    // Alternating colors like in the original static list
    // Pink, Blue, Pink, Blue...
    const colors = ['bg-pink-200', 'bg-blue-200'];
    return colors[index % colors.length];
};

const selectedItem = ref<Item | null>(null)

const openModal = (item: Item) => {
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