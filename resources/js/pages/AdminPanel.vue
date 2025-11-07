<template>
  <div class="flex min-h-screen bg-pink-50 font-poppins">
    <!-- 🔹 Sidebar -->
    <Sidebar />

    <!-- 🔹 Main Content (Slot dinamis dari halaman seperti Users.vue, Orders.vue, dsb) -->
    <main class="flex-1 p-8 overflow-y-auto ml-64">
      <slot />
    </main>
  </div>
</template>

<script setup lang="ts">
import Sidebar from '@/components/Sidebar.vue'
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

/* Tambahan opsional agar scroll lebih halus di area main */
main {
  scroll-behavior: smooth;
}
</style>