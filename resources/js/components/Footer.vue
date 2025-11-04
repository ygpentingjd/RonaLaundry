<template>
  <footer class="bg-pink-200 text-gray-900 py-10">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 sm:grid-cols-3 gap-8">

      <div class="flex items-center space-x-3">
        <div class="bg-white p-2 rounded-xl shadow-md flex items-center">
          <img src="/images/logo.png" alt="RonaLaundry Logo" class="h-10 w-auto" />
        </div>
      </div>
      <div>
        <h3 class="text-lg font-semibold mb-3">Menu</h3>
        <ul class="space-y-2">
          <li><a href="/landing" class="hover:underline">Beranda</a></li>
          <li><a href="/order" class="hover:underline">Cek Status</a></li>
          <li><a href="/reservasi" class="hover:underline">Reservasi</a></li>
        </ul>
      </div>


      <div>
        <h3 class="text-lg font-semibold mb-3">Kontak</h3>
        <ul class="space-y-2">
          <li>087844590030</li>
          <li>rona_laundry</li>
          <li>
            Jl. Kapulago Barat II No.3<br />
            RT.01 RW.10 Pajang, Laweyan,<br />
            Surakarta
          </li>
        </ul>
      </div>
    </div>
  </footer>
</template>

<script lang="ts">
export default {
  name: "Footers",
};
</script>

<style scoped>
footer {
  border-top: 1px solid #e5e7eb;
}
</style>