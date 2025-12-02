<template>
  <aside
    class="fixed top-0 left-0 z-50 flex flex-col justify-between w-64 h-full text-white bg-pink-400 shadow-lg"
  >
    <!-- 🔹 Bagian Atas -->
    <div>
      <!-- 🔹 Logo -->
      <div class="flex items-center p-4 space-x-2">
        <img src="/images/logo.png" class="h-10 p-1 bg-white rounded-lg" />
        <h1 class="text-lg font-bold">Rona Admin</h1>
      </div>

      <!-- 🔹 Sidebar Menu -->
      <nav class="mt-6">
        <ul class="space-y-2">
          <li v-for="menu in menus" :key="menu.text" class="px-3">
            <a
              :href="menu.link"
              class="flex items-center gap-2 px-4 py-2 transition-all duration-200 ease-in-out rounded-xl"
              :class="
                isActive(menu.link)
                  ? 'bg-[#3cb4ff] shadow-md'
                  : 'hover:bg-[#5ec7ff]/80 hover:shadow-sm'
              "
            >
              <span class="text-white material-icons">{{ menu.icon }}</span>
              <span>{{ menu.text }}</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>

    <!-- 🔹 Bagian Logout -->
    <div class="pt-3 mx-3 mb-4 border-t border-white/40">
      <button
        @click="logout"
        class="flex items-center gap-2 w-full text-left py-2 px-4 rounded-xl transition-all duration-200 ease-in-out hover:bg-[#ff4d4d]/90 hover:shadow-sm cursor-pointer"
      >
        <span class="text-white material-icons">logout</span>
        Logout
      </button>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { usePage, router } from "@inertiajs/vue3";

const page = usePage();

const menus = ref([
  { text: "Beranda", icon: "home", link: "/" },
  { text: "Dashboard", icon: "dashboard", link: "/admin" },
  { text: "Products Management", icon: "inventory_2", link: "/admin/products" },
  { text: "Users Management", icon: "group", link: "/admin/users" },
  { text: "Orders Management", icon: "shopping_cart", link: "/admin/orders" },
  { text: "Payment Verification", icon: "payments", link: "/admin/payments" },
]);

const isActive = (link: string) => {
  const current = "/" + page.url.replace(/^\//, "");

  if (link === "/") {
    return current === "/";
  }

  if (link === "/admin") {
    return current === "/admin";
  }

  return current === link || current.startsWith(link + "/");
};

const logout = () => {
  router.post("/logout", {}, {
    onSuccess: () => {
      router.visit("/");
    },
  });
};
</script>


<style scoped>
.material-icons {
  font-size: 20px;
}
</style>