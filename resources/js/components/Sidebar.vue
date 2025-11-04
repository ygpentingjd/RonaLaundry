<template>
    <aside
      class="fixed top-0 left-0 w-64 h-full bg-pink-400 text-white flex flex-col shadow-lg z-50"
    >
      <div>
        <!-- 🔹 Logo -->
        <div class="p-4 flex items-center space-x-2">
          <img src="/images/logo.png" class="h-10 rounded-lg bg-white p-1" />
          <h1 class="text-lg font-bold">Rona Admin</h1>
        </div>
  
        <!-- 🔹 Sidebar Menu -->
        <nav class="mt-6">
          <ul class="space-y-2">
            <li v-for="menu in menus" :key="menu.text">
              <a
                :href="menu.link"
                class="flex items-center px-5 py-2 rounded-md hover:bg-[#3cb4ff] transition"
              >
                <span class="material-icons text-white mr-2">{{ menu.icon }}</span>
                {{ menu.text }}
              </a>
            </li>
          </ul>
        </nav>
  
        <!-- 🔹 Garis Pemisah -->
        <div class="border-t border-white/40 mt-2"></div>
  
        <!-- 🔹 Logout Section -->
        <div class="mt-1">
          <a
            href="#"
            class="flex items-center px-5 py-3 rounded-md hover:bg-[#ff4d4d] transition"
          >
            <span class="material-icons text-white mr-2">logout</span>
            Logout
          </a>
        </div>
      </div>
    </aside>
  </template>
  
  <script setup>
  import { ref } from "vue";
  
  const menus = ref([
    { text: "Dashboard", icon: "dashboard", link: "/admin/dashboard" },
    { text: "Products", icon: "inventory_2", link: "/admin/products" },
    { text: "Users", icon: "group", link: "/admin/users" },
    { text: "Orders", icon: "shopping_cart", link: "/admin/orders" },
    { text: "Payment Verification", icon: "payments", link: "/admin/payments" },
  ]);
  </script>
  
  <style scoped>
  .material-icons {
    font-size: 20px;
  }
  </style>
  