<template>
    <AdminPanel>
        <!-- Header -->
        <div
            class="flex flex-col gap-4 mb-6 md:flex-row md:items-center md:justify-between"
        >
            <h1 class="text-3xl font-semibold text-pink-700">
                Daftar Pengguna
            </h1>

            <div class="flex flex-col gap-3 sm:flex-row">
                <!-- 🔍 Search Bar -->
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Cari user..."
                    class="w-full px-4 py-2 text-gray-700 transition bg-white border border-pink-300 rounded-lg shadow-sm focus:ring-2 focus:ring-pink-400 focus:outline-none sm:w-64"
                />

                <button
                    @click="openForm"
                    class="px-5 py-2 text-white transition bg-pink-600 rounded-lg shadow-md hover:bg-pink-700"
                >
                    + Tambah User
                </button>
            </div>
        </div>

        <!-- USERS TABLE -->
        <div
            class="overflow-hidden bg-white border border-gray-200 shadow-lg rounded-2xl"
        >
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-800 bg-pink-100">
                        <th class="px-5 py-3 font-semibold">ID</th>
                        <th class="px-5 py-3 font-semibold">Username</th>
                        <th class="px-5 py-3 font-semibold">Email</th>
                        <th class="px-5 py-3 font-semibold">Role</th>
                        <th class="px-5 py-3 font-semibold">Alamat</th>
                        <th class="px-5 py-3 font-semibold">Created At</th>
                        <th class="px-5 py-3 font-semibold">Updated At</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold text-center">
                            Actions
                        </th>
                    </tr>
                </thead>

                <!-- ✅ Gunakan computed filteredUsers -->
                <tbody>
                    <tr
                        v-for="user in filteredUsers"
                        :key="user.id"
                        class="text-gray-800 transition border-t even:bg-gray-50 hover:bg-pink-50"
                    >
                        <td class="px-5 py-3">{{ user.id }}</td>
                        <td class="px-5 py-3">{{ user.username }}</td>
                        <td class="px-5 py-3">{{ user.email }}</td>
                        <td class="px-5 py-3">{{ user.role }}</td>
                        <td class="px-5 py-3">{{ user.alamat_lengkap || '-' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-500">
                            {{ formatDate(user.createdAt) }}
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-500">
                            {{ formatDate(user.updatedAt) }}
                        </td>
                        <td
                            :class="
                                user.status === 'Online'
                                    ? 'text-green-600'
                                    : 'text-red-600'
                            "
                        >
                            {{ user.status }}
                        </td>
                        <td class="px-5 py-3 space-x-3 text-center">
                            <button
                                @click="editUser(user)"
                                class="text-blue-600 hover:underline"
                            >
                                Edit
                            </button>
                            <button
                                @click="deleteUser(user.id)"
                                class="text-red-600 hover:underline"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Jika tidak ada hasil -->
                    <tr v-if="filteredUsers.length === 0">
                        <td colspan="8" class="py-4 text-center text-gray-500">
                            Tidak ada pengguna ditemukan
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal Tambah/Edit -->
        <div
            v-if="showForm"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
        >
            <div
                class="animate-fadeIn relative w-[450px] rounded-2xl bg-[#ffe4ec] p-6 shadow-2xl"
            >
                <button
                    @click="closeForm"
                    class="absolute text-lg font-bold text-pink-700 top-3 right-3 hover:text-pink-900"
                >
                    ✕
                </button>

                <h2 class="mb-5 text-xl font-bold text-center text-pink-700">
                    {{ isEditing ? 'Edit User' : 'Tambah User' }}
                </h2>

                <div class="space-y-3 text-gray-700">
                    <input
                        v-model="formUser.username"
                        placeholder="Nama Lengkap"
                        class="input"
                    />
                    <input
                        v-model="formUser.email"
                        placeholder="Email"
                        class="input"
                        type="email"
                    />
                    <select v-model="formUser.role" class="input">
                        <option value="Admin">Admin</option>
                        <option value="User">User</option>
                    </select>
                    <input
                        v-model="formUser.nomor_telepon"
                        placeholder="No Telepon"
                        class="input"
                    />
                    <textarea
                        v-model="formUser.alamat_lengkap"
                        rows="2"
                        class="input"
                        placeholder="Alamat lengkap"
                    ></textarea>
                    <select v-model="formUser.status" class="input">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <div class="flex justify-center gap-4 mt-6">
                    <button
                        @click="saveUser"
                        class="px-6 py-2 text-white bg-green-500 rounded-lg shadow hover:bg-green-600"
                    >
                        Simpan
                    </button>
                    <button
                        @click="closeForm"
                        class="px-6 py-2 text-white bg-red-500 rounded-lg shadow hover:bg-red-600"
                    >
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </AdminPanel>
</template>

<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, ref } from 'vue';
import AdminPanel from '../AdminPanel.vue';

// --- tipe untuk data mentah dari server (snake_case) ---
interface UserRaw {
  id?: number;
  id_user?: number;
  username: string;
  email: string;
  alamat_lengkap?: string | null;
  status?: string;
  role?: string;
  nomor_telepon?: string;
  created_at?: string | null;
  updated_at?: string | null;
}

// --- tipe untuk view / frontend (camelCase, yang akan kita gunakan di template) ---
interface UserView {
  id: number;
  username: string;
  email: string;
  role: string;
  nomor_telepon: string;
  alamat_lengkap: string | null;
  status: string;
  createdAt?: string | null;
  updatedAt?: string | null;
}

interface AdminUsersPageProps {
  users?: UserRaw[];
  [key: string]: any;
}

// --- penting: deklarasikan page dulu
const page = usePage<AdminUsersPageProps>();
const props = page.props.value ?? {};

// --- inisialisasi users sebagai ref (bisa di-push/splice/filter) ---
const users = ref<UserView[]>(
  (props.users ?? []).map((u: UserRaw) => ({
    id: u.id_user!,
    username: u.username,
    email: u.email,
    role: u.role ?? 'User',
    nomor_telepon: u.nomor_telepon ?? '',
    alamat_lengkap: u.alamat_lengkap ?? '',
    status: u.status ?? 'Active',
    createdAt: u.created_at ?? null,
    updatedAt: u.updated_at ?? null,
  }))
);

// form & modal state
const showForm = ref(false);
const isEditing = ref(false);
const editingId = ref<number | null>(null);
const searchQuery = ref('');

const formUser = ref({
  username: '',
  email: '',
  role: 'User',
  nomor_telepon: '',
  alamat_lengkap: '',
  status: 'Active',
  password: '',
});

// computed filter
const filteredUsers = computed(() => {
  if (!searchQuery.value) return users.value;
  const q = searchQuery.value.toLowerCase();
  return users.value.filter(
    (u: any) =>
      (u.id_user + '').includes(q) ||
      u.username.toLowerCase().includes(q) ||
      u.email.toLowerCase().includes(q) ||
      (u.alamat_lengkap || '').toLowerCase().includes(q),
  );
});

function openForm() {
  isEditing.value = false;
  editingId.value = null;
  formUser.value = {
    username: '',
    email: '',
    role: 'User',
    nomor_telepon: '',
    alamat_lengkap: '',
    status: 'Active',
    password: '',
  };
  showForm.value = true;
}

function editUser(user: any) {
  isEditing.value = true;
  editingId.value = user.id_user;
  formUser.value = {
    username: user.username,
    email: user.email,
    role: user.role ?? 'User',
    nomor_telepon: user.nomor_telepon ?? '',
    alamat_lengkap: user.alamat_lengkap ?? '',
    status: user.status ?? 'Active',
    password: '',
  };
  showForm.value = true;
}

function closeForm() {
  showForm.value = false;
}

// Save (create/update)
async function saveUser() {
  try {
    const payload = { ...formUser.value };

    if (isEditing.value && editingId.value) {
      const res = await axios.put(`/admin/users/${editingId.value}`, payload);
      const updated = res.data.user;

      const idx = users.value.findIndex((u: any) => u.id_user == updated.id_user);
      if (idx !== -1) {
        users.value[idx] = {
          id: updated.id,
          username: updated.username,
          email: updated.email,
          alamat_lengkap: updated.alamat_lengkap,
          status: updated.status,
          role: updated.role ?? users.value[idx].role,
          nomor_telepon: updated.nomor_telepon ?? users.value[idx].nomor_telepon,
          createdAt: updated.created_at ?? users.value[idx].createdAt,
          updatedAt: updated.updated_at ?? new Date().toISOString(),
        };
      }
    } else {
      const res = await axios.post('/admin/users', payload);
      const created = res.data.user;
      users.value.push({
        id: created.id,
        username: created.username,
        email: created.email,
        alamat_lengkap: created.alamat_lengkap,
        status: created.status,
        role: created.role ?? 'User',
        nomor_telepon: created.nomor_telepon ?? '',
        createdAt: created.created_at,
        updatedAt: created.updated_at,
      });
    }

    showForm.value = false;
  } catch (err: any) {
    if (err.response?.status === 422) {
      console.error('Validation errors:', err.response.data.errors);
      alert('Validasi gagal. Cek input.');
    } else {
      console.error(err);
      alert('Terjadi kesalahan. Lihat console.');
    }
  }
}

async function deleteUser(id: number) {
  const ok = confirm('Yakin ingin menghapus user ini?');
  if (!ok) return;

  try {
    await axios.delete(`/admin/users/${id}`);
    users.value = users.value.filter((u: any) => u.id_user !== id);
  } catch (err) {
    console.error(err);
    alert('Gagal menghapus user.');
  }
}

function formatDate(date: string | Date | null | undefined) {
  if (!date) return '-';
  const d = new Date(date);
  return d.toLocaleString('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
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
