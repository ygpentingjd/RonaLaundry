<template>
    <AdminPanel>
        <Head title="Users Management - RonaLaundry" />
        <!-- Header -->
        <div
            class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
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
                    class="w-full rounded-lg border border-pink-300 bg-white px-4 py-2 text-gray-700 shadow-sm transition focus:ring-2 focus:ring-pink-400 focus:outline-none sm:w-64"
                />

                <button
                    @click="openForm"
                    class="rounded-lg bg-pink-600 px-5 py-2 text-white shadow-md transition hover:bg-pink-700"
                >
                    + Tambah User
                </button>
            </div>
        </div>

        <!-- USERS TABLE -->
        <div
            class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg"
        >
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-pink-100 text-gray-800">
                        <th class="px-5 py-3 font-semibold">ID</th>
                        <th class="px-5 py-3 font-semibold">Username</th>
                        <th class="px-5 py-3 font-semibold">Email</th>
                        <th class="px-5 py-3 font-semibold">Role</th>
                        <th class="px-5 py-3 font-semibold">Alamat</th>
                        <th class="px-5 py-3 font-semibold">Created At</th>
                        <th class="px-5 py-3 font-semibold">Updated At</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 text-center font-semibold">
                            Actions
                        </th>
                    </tr>
                </thead>

                <!-- ✅ Gunakan computed filteredUsers -->
                <tbody>
                    <tr
                        v-for="user in filteredUsers"
                        :key="user.id"
                        class="border-t text-gray-800 transition even:bg-gray-50 hover:bg-pink-50"
                    >
                        <td class="px-5 py-3">{{ user.id }}</td>
                        <td class="px-5 py-3">{{ user.username }}</td>
                        <td class="px-5 py-3">{{ user.email }}</td>
                        <td class="px-5 py-3">{{ user.role }}</td>
                        <td class="px-5 py-3">
                            {{ user.alamat_lengkap || '-' }}
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-500">
                            {{ formatDate(user.createdAt) }}
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-500">
                            {{ formatDate(user.updatedAt) }}
                        </td>
                        <td class="px-5 py-3">
                            <span
                                :class="{
                                    'bg-green-100 text-green-800':
                                        user.status === 'Active',
                                    'bg-red-100 text-red-800':
                                        user.status === 'Inactive',
                                }"
                                class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                            >
                                {{ user.status }}
                            </span>
                        </td>
                        <td class="space-x-3 px-5 py-3 text-center">
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
                    class="absolute top-3 right-3 text-lg font-bold text-pink-700 hover:text-pink-900"
                >
                    ✕
                </button>

                <h2 class="mb-5 text-center text-xl font-bold text-pink-700">
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

                <div class="mt-6 flex justify-center gap-4">
                    <button
                        @click="saveUser"
                        class="rounded-lg bg-green-500 px-6 py-2 text-white shadow hover:bg-green-600"
                    >
                        Simpan
                    </button>
                    <button
                        @click="closeForm"
                        class="rounded-lg bg-red-500 px-6 py-2 text-white shadow hover:bg-red-600"
                    >
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </AdminPanel>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';
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

const props = defineProps<{ users: UserRaw[] }>();

// --- inisialisasi users sebagai ref (bisa di-push/splice/filter) ---
const userList = ref<UserView[]>([]);

onMounted(() => {
    if (props.users) {
        userList.value = props.users.map((u: UserRaw) => ({
            id: u.id_user!,
            username: u.username,
            email: u.email,
            role: u.role ?? 'User',
            nomor_telepon: u.nomor_telepon ?? '',
            alamat_lengkap: u.alamat_lengkap ?? '',
            status: u.status ?? 'Active',
            createdAt: u.created_at ?? null,
            updatedAt: u.updated_at ?? null,
        }));
    }
});

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
    if (!searchQuery.value) return userList.value;
    const q = searchQuery.value.toLowerCase();
    return userList.value.filter(
        (u: UserView) =>
            (u.id + '').includes(q) ||
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
    editingId.value = user.id;
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
            await axios.put(`/admin/users/${editingId.value}`, payload);
            alert('User berhasil diperbarui');
        } else {
            await axios.post('/admin/users', payload);
            alert('User berhasil ditambahkan');
        }

        showForm.value = false;
        router.reload({ only: ['users'] });
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
        alert('User berhasil dihapus');
        router.reload({ only: ['users'] });
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
