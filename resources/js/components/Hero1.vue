<script setup lang="ts">
// Tidak butuh props untuk sekarang
</script>

<template>
  <section
    class="relative bg-gradient-to-r from-pink-400 via-pink-300 to-pink-400 text-white text-center shadow-md mt-20 overflow-hidden rounded-full mx-auto px-8"
    style="max-width: 90%; height: 180px;"
  >
    <div class="relative z-10 flex flex-col justify-center items-center h-full px-4">
      <!-- Judul 3D -->
      <h1
        class="text-3xl md:text-4xl font-extrabold mb-2"
        style="
          text-shadow:
            1px 1px 0 #d63384,
            2px 2px 0 #c2185b,
            3px 3px 5px rgba(0, 0, 0, 0.3);
        "
      >
        Formulir Reservasi
      </h1>

      <!-- Deskripsi 3D -->
      <p
        class="text-base md:text-lg opacity-90 max-w-2xl mx-auto text-pink-50 font-medium transition-all duration-300 hover:scale-105 leading-relaxed cursor-pointer"
        style="
          text-shadow:
            1px 1px 0 #ffb6c1,
            2px 2px 0 #ff9ab5,
            3px 3px 6px rgba(0, 0, 0, 0.25);
        "
      >
        Isi data berikut untuk memesan layanan laundry kami dengan cepat dan mudah!
      </p>
    </div>
  </section>
</template>

<style scoped>
section {
  font-family: "Poppins", sans-serif;
  transition: all 0.4s ease;
}

/* Responsif agar proporsi tetap bagus di mobile */
@media (max-width: 768px) {
  section {
    height: 150px;
    max-width: 95%;
    border-radius: 9999px; /* tetap melengkung penuh di ujung */
  }
}
</style>
