<script setup lang="ts">
/**
 * Komponen MapSection
 * -------------------
 * Menampilkan peta lokasi menggunakan Google Maps embed link.
 * Kamu bisa:
 * 1️⃣ Langsung isi embed link di defaultSrc (untuk penggunaan mandiri)
 * 2️⃣ Atau kirim lewat prop :src di LandingPage.vue
 */

const props = defineProps({
  src: {
    type: String,
    default: ''
  }
})

const defaultSrc =
  "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.113315640054!2d110.7876657765866!3d-7.562622674689436!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a158216a4eea7%3A0xebd13e3c3c654d74!2sRona%20Laundry!5e0!3m2!1sen!2sid!4v1762484136688!5m2!1sen!2sid";
</script>

<template>
  <section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-6">
      <!-- Judul + Logo -->
      <h2 class="text-center text-2xl md:text-3xl font-semibold mb-6 text-black leading-snug">
        Temukan Kami di
        <br />
        <img 
          src="/images/logo.png" 
          alt="Logo Rona Laundry" 
          class="mx-auto mt-3 w-40 md:w-48"
        />
      </h2>

      <!-- Google Maps -->
      <div class="w-full rounded-lg overflow-hidden shadow-lg border">
        <div class="relative" style="padding-top: 28%;">
          <iframe
            :src="props.src || defaultSrc"
            class="absolute inset-0 w-full h-full border-0"
            :allowfullscreen="true"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Lokasi Rona Laundry"
          ></iframe>
        </div>
      </div>
    </div>
  </section>
</template>