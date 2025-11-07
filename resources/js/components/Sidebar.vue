<template>
  <aside
    class="fixed top-0 left-0 w-64 h-full bg-pink-400 text-white flex flex-col justify-between shadow-lg z-50"
  >
    <!-- 🔹 Bagian Atas -->
    <div>
      <!-- 🔹 Logo -->
      <div class="p-4 flex items-center space-x-2">
        <img src="/images/logo.png" class="h-10 rounded-lg bg-white p-1" />
        <h1 class="text-lg font-bold">Rona Admin</h1>
      </div>

      <!-- 🔹 Sidebar Menu -->
      <nav class="mt-6">
        <ul class="space-y-2">
          <li v-for="menu in menus" :key="menu.text" class="px-3">
            <a
              :href="menu.link"
              class="flex items-center gap-2 py-2 px-4 rounded-xl transition-all duration-200 ease-in-out"
              :class="
                isActive(menu.link)
                  ? 'bg-[#3cb4ff] shadow-md'
                  : 'hover:bg-[#5ec7ff]/80 hover:shadow-sm'
              "
            >
              <span class="material-icons text-white">{{ menu.icon }}</span>
              <span>{{ menu.text }}</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>

    <!-- 🔹 Bagian Logout -->
    <div class="border-t border-white/40 mx-3 mb-4 pt-3">
      <a
        href="/login"
        class="flex items-center gap-2 py-2 px-4 rounded-xl transition-all duration-200 ease-in-out hover:bg-[#ff4d4d]/90 hover:shadow-sm"
      >
        <span class="material-icons text-white">logout</span>
        Logout
      </a>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();

const menus = ref([
  { text: "Dashboard", icon: "dashboard", link: "/admin" },
  { text: "Products", icon: "inventory_2", link: "/admin/products" },
  { text: "Users", icon: "group", link: "/admin/users" },
  { text: "Orders", icon: "shopping_cart", link: "/admin/orders" },
  { text: "Payment Verification", icon: "payments", link: "/admin/payments" },
]);

const isActive = (link) => {
  // Kalau link = '/admin', aktif hanya kalau benar-benar di /admin
  if (link === "/admin") {
    return page.url === "/admin";
  }
  // Untuk menu lainnya, aktif kalau url-nya dimulai dengan link tsb
  return page.url.startsWith(link);
};
</script>

<style scoped>
.material-icons {
  font-size: 20px;
}
</style>