<template>
  <footer class="bg-gradient-to-r from-pink-200 via-pink-100 to-pink-200 text-gray-900 py-10 border-t border-gray-300">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 sm:grid-cols-4 gap-8">
      
      <!-- 🔹 Logo Section -->
      <div class="flex flex-col items-center sm:items-start">
        <div class="bg-white p-2 rounded-xl shadow-md flex items-center ml-2">
          <img src="/images/logo.png" alt="RonaLaundry Logo" class="h-10 w-auto" />
        </div>
        <p class="text-sm text-gray-700 text-center sm:text-left mt-3 ml-2">
          Laundry cepat, bersih, dan wangi untuk keseharian Anda.
        </p>
      </div>

      <!-- 🔹 Menu -->
      <div>
        <h3 class="text-lg font-semibold mb-3">Menu</h3>
        <ul class="space-y-2 text-sm">
          <li><a href="/" class="hover:text-pink-700 transition">Beranda</a></li>
          <li><a href="#" class="hover:text-pink-700 transition">Cek Status</a></li>
          <li><a href="#" class="hover:text-pink-700 transition">Reservasi</a></li>
        </ul>
      </div>

      <!-- 🔹 Kontak -->
      <div>
        <h3 class="text-lg font-semibold mb-3">Kontak</h3>
        <ul class="space-y-2 text-sm">
          <li class="flex items-center space-x-2">
            <span class="material-icons text-pink-700 text-base">call</span>
            <span>0878-4459-0030</span>
          </li>
          <li class="flex items-center space-x-2">
            <span class="material-icons text-pink-700 text-base">alternate_email</span>
            <span>@rona_laundry</span>
          </li>
          <li class="flex items-start space-x-2">
            <span class="material-icons text-pink-700 text-base mt-1">location_on</span>
            <span>
              Jl. Kapulago Barat II No.3<br />
              Pajang, Laweyan, Surakarta
            </span>
          </li>
        </ul>

        <!-- 🔹 Sosial Media -->
        <div class="flex space-x-4 mt-4 ml-1">
          <a
            href="https://wa.me/6287844590030"
            target="_blank"
            class="hover:text-pink-700 transition flex items-center space-x-1"
            aria-label="WhatsApp"
          >
            <span class="material-icons text-2xl">chat</span>
          </a>
          <a
            href="https://instagram.com/rona_laundry"
            target="_blank"
            class="hover:text-pink-700 transition flex items-center space-x-1"
            aria-label="Instagram"
          >
            <span class="material-icons text-2xl">photo_camera</span>
          </a>
        </div>
      </div>

      <!-- 🔹 CTA -->
      <div class="flex flex-col justify-center items-center sm:items-start">
        <a
          href="https://wa.me/6287844590030"
          class="bg-pink-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-pink-700 transition flex items-center space-x-2"
        >
          <span class="material-icons text-white text-base">phone_in_talk</span>
          <span>Hubungi Kami</span>
        </a>
      </div>
    </div>

    <!-- 🔹 Copyright -->
    <div class="mt-10 text-center text-sm text-gray-600 border-t border-gray-300 pt-4">
      © {{ new Date().getFullYear() }} Rona Laundry. Semua hak dilindungi.
    </div>
  </footer>
</template>

<script setup lang="ts">
defineOptions({
  name: 'FooterPage'
})
</script>