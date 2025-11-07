<template>
  <AdminPanel>
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
      <h1 class="text-3xl font-semibold text-pink-700">Daftar Pengguna</h1>

      <div class="flex flex-col sm:flex-row gap-3">
        <!-- 🔍 Search Bar -->
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Cari user..."
          class="w-full sm:w-64 bg-white border border-pink-300 rounded-lg px-4 py-2 text-gray-700
                 focus:outline-none focus:ring-2 focus:ring-pink-400 shadow-sm transition"
        />

        <button
          @click="openForm"
          class="bg-pink-600 text-white px-5 py-2 rounded-lg hover:bg-pink-700 transition shadow-md"
        >
          + Tambah User
        </button>
      </div>
    </div>

    <!-- USERS TABLE -->
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-200">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-pink-100 text-gray-800">
            <th class="py-3 px-5 font-semibold">Name</th>
            <th class="py-3 px-5 font-semibold">Email</th>
            <th class="py-3 px-5 font-semibold">Role</th>
            <th class="py-3 px-5 font-semibold">Alamat</th>
            <th class="py-3 px-5 font-semibold">Created At</th>
            <th class="py-3 px-5 font-semibold">Updated At</th>
            <th class="py-3 px-5 font-semibold">Status</th>
            <th class="py-3 px-5 font-semibold text-center">Actions</th>
          </tr>
        </thead>

        <!-- ✅ Gunakan computed filteredUsers -->
        <tbody>
          <tr
            v-for="user in filteredUsers"
            :key="user.id"
            class="border-t even:bg-gray-50 hover:bg-pink-50 transition text-gray-800"
          >
            <td class="py-3 px-5">{{ user.name }}</td>
            <td class="py-3 px-5">{{ user.email }}</td>
            <td class="py-3 px-5">{{ user.role }}</td>
            <td class="py-3 px-5">{{ user.address || '-' }}</td>
            <td class="py-3 px-5 text-sm text-gray-500">{{ formatDate(user.createdAt) }}</td>
            <td class="py-3 px-5 text-sm text-gray-500">{{ formatDate(user.updatedAt) }}</td>
            <td
              class="py-3 px-5 font-medium"
              :class="user.status === 'Active' ? 'text-green-600' : 'text-red-600'"
            >
              {{ user.status }}
            </td>
            <td class="py-3 px-5 text-center space-x-3">
              <button @click="editUser(user)" class="text-blue-600 hover:underline">Edit</button>
              <button @click="deleteUser(user.id)" class="text-red-600 hover:underline">Delete</button>
            </td>
          </tr>

          <!-- Jika tidak ada hasil -->
          <tr v-if="filteredUsers.length === 0">
            <td colspan="8" class="py-4 text-center text-gray-500">Tidak ada pengguna ditemukan</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Tambah/Edit -->
    <div
      v-if="showForm"
      class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50"
    >
      <div class="relative w-[450px] bg-[#ffe4ec] rounded-2xl shadow-2xl p-6 animate-fadeIn">
        <button
          @click="closeForm"
          class="absolute top-3 right-3 text-pink-700 hover:text-pink-900 text-lg font-bold"
        >
          ✕
        </button>

        <h2 class="text-xl font-bold text-center text-pink-700 mb-5">
          {{ isEditing ? 'Edit User' : 'Tambah User' }}
        </h2>

        <div class="space-y-3 text-gray-700">
          <input v-model="formUser.name" placeholder="Nama Lengkap" class="input" />
          <input v-model="formUser.email" placeholder="Email" class="input" type="email" />
          <select v-model="formUser.role" class="input">
            <option value="Admin">Admin</option>
            <option value="User">User</option>
          </select>
          <input v-model="formUser.phone" placeholder="No Telepon" class="input" />
          <textarea v-model="formUser.address" rows="2" class="input" placeholder="Alamat lengkap"></textarea>
          <select v-model="formUser.status" class="input">
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>

        <div class="flex justify-center gap-4 mt-6">
          <button
            @click="saveUser"
            class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg shadow"
          >
            Simpan
          </button>
          <button
            @click="closeForm"
            class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg shadow"
          >
            Batal
          </button>
        </div>
      </div>
    </div>
  </AdminPanel>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import AdminPanel from "../AdminPanel.vue";

const showForm = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);
const searchQuery = ref(""); // 🔍 untuk pencarian

const users = ref([
  {
    id: 1,
    name: "Admin",
    email: "admin@rona.com",
    role: "Admin",
    address: "Jl. Mawar No. 1",
    status: "Active",
    createdAt: new Date(),
    updatedAt: new Date(),
  },
  {
    id: 2,
    name: "Siti",
    email: "siti@example.com",
    role: "User",
    address: "Jl. Kenanga No. 12",
    status: "Active",
    createdAt: new Date(),
    updatedAt: new Date(),
  },
  {
    id: 3,
    name: "Budi",
    email: "budi@example.com",
    role: "User",
    address: "Jl. Melati No. 7",
    status: "Inactive",
    createdAt: new Date(),
    updatedAt: new Date(),
  },
]);

const formUser = ref({
  name: "",
  email: "",
  role: "User",
  phone: "",
  address: "",
  status: "Active",
});

// 🔍 Computed untuk filter berdasarkan searchQuery
const filteredUsers = computed(() => {
  if (!searchQuery.value) return users.value;
  const q = searchQuery.value.toLowerCase();
  return users.value.filter(
    (u) =>
      u.name.toLowerCase().includes(q) ||
      u.email.toLowerCase().includes(q) ||
      u.address?.toLowerCase().includes(q)
  );
});

function openForm() {
  isEditing.value = false;
  formUser.value = {
    name: "",
    email: "",
    role: "User",
    phone: "",
    address: "",
    status: "Active",
  };
  showForm.value = true;
}

function editUser(user: any) {
  isEditing.value = true;
  editingId.value = user.id;
  formUser.value = { ...user };
  showForm.value = true;
}

function closeForm() {
  showForm.value = false;
}

function saveUser() {
  const now = new Date();
  if (isEditing.value) {
    const index = users.value.findIndex((u) => u.id === editingId.value);
    if (index !== -1) {
      users.value[index] = {
        ...users.value[index],   // tetap jaga properti lama
        ...formUser.value,
        updatedAt: now,
    };
    }
  } else {
    users.value.push({
      id: Date.now(),
      ...formUser.value,
      createdAt: now,
      updatedAt: now,
    });
  }
  showForm.value = false;
}

function deleteUser(id: number) {
  users.value = users.value.filter((u) => u.id !== id);
}

function formatDate(date: string | Date) {
  if (!date) return "-";
  const d = new Date(date);
  return d.toLocaleString("id-ID", {
    day: "2-digit",
    month: "short",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
}
</script>

<style scoped>
.animate-fadeIn {
  animation: fadeIn 0.3s ease;
}
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-5px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>