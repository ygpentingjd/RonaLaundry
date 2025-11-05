<template>
  <div class="flex">
    <!-- Sidebar -->
    <SidebarUser />

    <!-- Konten Utama -->
    <main class="ml-64 w-full min-h-screen bg-gray-50 p-10">
      <slot />
    </main>
  </div>
</template>

<script setup lang="ts">
import SidebarUser from '@/components/SidebarUser.vue'
</script>

<style scoped>
/* optional styling biar halus */
main {
  font-family: 'Poppins', sans-serif;
  transition: all 0.3s ease;
}
</style>