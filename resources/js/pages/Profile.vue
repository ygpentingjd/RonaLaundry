<template>
  <Head title="Profile - RonaLaundry" />
    <div class="min-h-screen bg-pink-50 flex flex-col items-center py-12 px-4">
      <!-- 🔹 Card Profil -->
      <div
        class="bg-white shadow-lg rounded-2xl w-full max-w-md p-8 border border-pink-200"
      >
        <!-- Foto Profil -->
        <div class="flex flex-col items-center mb-6">
          <span
            class="material-icons text-pink-400 text-8xl border-4 border-pink-400 rounded-full p-4 bg-pink-100 shadow-md"
          >
            account_circle
          </span>
          <h2 class="mt-3 text-2xl font-bold text-gray-800">Admin Rona</h2>
          <p class="text-gray-500 text-sm">admin@ronalaundry.com</p>
        </div>
  
        <!-- Detail Informasi -->
        <div class="space-y-4 text-gray-700">
          <div>
            <p class="text-sm text-gray-500">Nama Lengkap</p>
            <p class="font-semibold">Admin Rona</p>
          </div>
  
          <div>
            <p class="text-sm text-gray-500">Nomor Telepon</p>
            <p class="font-semibold">0878-4459-0030</p>
          </div>
  
          <div>
            <p class="text-sm text-gray-500">Alamat</p>
            <p class="font-semibold">
              Jl. Kapulago Barat II No.3, RT.01 RW.10 Pajang, Laweyan, Surakarta
            </p>
          </div>
        </div>
  
        <!-- Tombol Edit (nonaktif sementara) -->
        <div class="mt-8 flex justify-center">
          <button
            disabled
            class="bg-blue-400 text-white px-6 py-2 rounded-lg opacity-70 cursor-not-allowed"
          >
            Edit Profil
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { Head } from '@inertiajs/vue3'
  // Tidak ada logic dulu (masih statis)
  </script>
  
  <style scoped>
  .material-icons {
    font-size: 90px;
  }
  </style>
  