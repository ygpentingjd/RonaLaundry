<template>
    <AdminPanel>
        <!-- Header -->
        <!-- HEADER -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-semibold text-pink-700">
                Orders Management
            </h1>

            <div class="flex items-center gap-3">
                <!-- Search Bar -->
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Cari order..."
                    class="w-64 px-4 py-2 text-gray-700 transition bg-white border border-pink-300 rounded-lg shadow-sm focus:ring-2 focus:ring-pink-400 focus:outline-none"
                />

                <!-- Tombol Clear -->
                <button
                    @click="clearSearch"
                    v-if="searchQuery"
                    class="text-sm text-gray-500 hover:text-gray-700"
                >
                    Clear
                </button>

                <!-- Tombol Refresh -->
                <button
                    @click="refreshOrders"
                    :disabled="isRefreshing"
                    class="px-4 py-2 text-white transition bg-pink-500 rounded-lg shadow hover:bg-pink-600 disabled:opacity-50"
                >
                    <span
                        class="mr-1 text-sm align-middle material-icons"
                        :class="{ 'animate-spin': isRefreshing }"
                    >
                        refresh
                    </span>
                    {{ isRefreshing ? 'Refreshing...' : 'Refresh' }}
                </button>
            </div>
        </div>

        <!-- Orders List -->
        <div class="space-y-6">
            <!-- iterate filteredOrders instead of orders -->
            <div
                v-for="order in filteredOrders"
                :key="order.id"
                class="p-6 transition duration-200 shadow-md rounded-2xl bg-pink-50"
            >
                <!-- Header Card -->
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <div
                                class="flex items-center justify-center w-8 h-8 font-semibold text-white bg-pink-400 rounded-full"
                            >
                                {{ String(order.id).padStart(2, '0') }}
                            </div>
                            <!-- Hanya tampilkan Order ID -->
                            <h3 class="text-lg font-semibold text-gray-800">
                                Order #{{ order.id }}
                            </h3>
                        </div>
                        <div
                            class="flex items-center gap-1 mt-1 text-sm text-gray-500"
                        >
                            <span class="text-sm material-icons">schedule</span>
                            {{ order.date }}
                        </div>
                    </div>

                    <button
                        @click="toggleDetail(order.id)"
                        class="px-4 py-1 text-sm text-white transition bg-pink-400 rounded-lg shadow hover:bg-pink-500"
                    >
                        {{
                            expandedOrder === order.id
                                ? 'Tutup ▲'
                                : 'Lihat Detail ▼'
                        }}
                    </button>
                </div>

                <!-- Detail (Dropdown) -->
                <transition name="fade">
                    <div
                        v-if="expandedOrder === order.id"
                        class="mt-5 space-y-4 text-sm text-gray-700"
                    >
                        <!-- 🔹 Identitas Pemesan -->
                        <div>
                            <p class="mb-2 font-semibold">Identitas Pemesan</p>
                            <table class="w-full mb-2">
                                <tbody>
                                    <tr>
                                        <td class="py-1">Order ID</td>
                                        <td
                                            class="py-1 text-right text-gray-600"
                                        >
                                            #{{ order.id }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">Nama Pemesan</td>
                                        <td
                                            class="py-1 text-right text-gray-600"
                                        >
                                            {{ order.customer }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">Alamat</td>
                                        <td
                                            class="py-1 text-right text-gray-600"
                                        >
                                            {{ order.address }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- 🔹 Status Pembayaran -->
                        <div>
                            <p class="mb-2 font-semibold">Status Pembayaran</p>
                            <span
                                class="px-3 py-1 text-sm font-medium rounded-full"
                                :class="{
                                    'bg-yellow-100 text-yellow-700':
                                        order.paymentStatus === 'Pending',
                                    'bg-green-100 text-green-700':
                                        order.paymentStatus === 'Verified',
                                    'bg-red-100 text-red-700':
                                        order.paymentStatus === 'Rejected',
                                }"
                            >
                                {{ order.paymentStatus }}
                            </span>
                        </div>

                        <!-- 🔹 Status Pesanan -->
                        <div>
                            <p class="mt-4 mb-2 font-semibold">
                                Status Pesanan
                            </p>
                            <div class="relative inline-block text-left">
                                <button
                                    @click="toggleDropdown(order.id)"
                                    class="flex items-center justify-between px-3 py-1 text-sm font-medium rounded-full w-52"
                                    :class="statusClass(order.orderStatus)"
                                >
                                    {{ order.orderStatus }}
                                    <span class="ml-1 text-xs material-icons"
                                        >arrow_drop_down</span
                                    >
                                </button>

                                <transition name="fade">
                                    <div
                                        v-if="openDropdown === order.id"
                                        class="absolute z-50 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg w-52"
                                    >
                                        <ul class="py-1 text-sm text-gray-700">
                                            <li
                                                v-for="status in statusOptions"
                                                :key="status"
                                                @click="
                                                    selectStatus(order, status)
                                                "
                                                class="flex items-center px-3 py-2 rounded-lg cursor-pointer hover:bg-gray-100"
                                            >
                                                <span
                                                    class="w-3 h-3 mr-2 rounded-full"
                                                    :class="
                                                        statusDotClass(status)
                                                    "
                                                ></span>
                                                <span
                                                    :class="
                                                        statusTextClass(status)
                                                    "
                                                    >{{ status }}</span
                                                >
                                            </li>
                                        </ul>
                                    </div>
                                </transition>
                            </div>
                        </div>

                        <!-- 🔹 Detail Order -->
                        <div>
                            <p class="mt-4 mb-2 font-semibold">Detail Order</p>
                            <table class="w-full mb-4">
                                <tbody>
                                    <tr>
                                        <td class="py-1">Layanan</td>
                                        <td class="py-1 text-right">
                                            {{ order.service }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">Barang</td>
                                        <td class="py-1 text-right">
                                            {{ order.stuff }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">Tanggal Masuk</td>
                                        <td class="py-1 text-right">
                                            {{ fmt (order.orderDate) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">
                                            Tanggal Pengambilan
                                        </td>
                                        <td class="py-1 text-right">
                                            {{ fmt (order.returnDate) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">Metode Pengantaran</td>
                                        <td class="py-1 text-right">
                                            {{ order.deliveryMethod }}
                                        </td>
                                    </tr>
                                    <tr>
                                    <td class="py-1">Total Harga</td>
                                    <td class="py-1 text-right">
                                        <input 
                                        v-model.number="order.total"
                                        type="number"
                                        min="0"
                                        placeholder="Isi total"
                                        class="w-32 px-2 py-1 text-sm text-right border rounded-lg focus:ring-2 focus:ring-pink-300 focus:outline-none"
                                        />
                                    </td>
                                    </tr>
                                    <tr>
                                        <td class="pt-3 font-semibold">
                                            Total
                                        </td>
                                        <td class="pt-3 font-bold text-right text-gray-900">
                                         Rp {{ order.total ? order.total.toLocaleString() : '—' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- 🔹 Tombol Aksi -->
                        <div class="flex justify-end gap-3 mt-4">
                            <button
                                class="text-blue-500 hover:underline"
                                @click="updateOrder(order)"
                            >
                                Simpan
                            </button>
                            <button
                                class="text-red-500 hover:underline"
                                @click="deleteOrder(order.id)"
                            >
                                Hapus
                            </button>
                        </div>
                    </div>
                </transition>
            </div>

            <!-- Jika tidak ada hasil -->
            <div
                v-if="filteredOrders.length === 0"
                class="py-8 text-center text-gray-500"
            >
                Tidak ada order yang cocok dengan pencarian.
            </div>
        </div>
    </AdminPanel>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import axios from 'axios';
import AdminPanel from '../AdminPanel.vue';

// ------------------------------------------------
// PROPS & STATE
// ------------------------------------------------
const props = defineProps<{
    orders: Array<AdminOrder>;
}>();

// orders utama yang akan berubah ketika refresh
const orders = ref(props.orders || []);

// UI states
const expandedOrder = ref<number | null>(null);
const openDropdown = ref<number | null>(null);
const searchQuery = ref('');
const isRefreshing = ref(false);

// ------------------------------------------------
// SEARCH FILTER
// ------------------------------------------------
const filteredOrders = computed(() => {
    const q = searchQuery.value.trim().toLowerCase();
    if (!q) return orders.value;

    return orders.value.filter((order: any) => {
        const fields = [
            String(order.id),
            order.customer,
            order.address,
            order.service,
            order.stuff,
            order.orderStatus,
            order.paymentStatus,
            order.deliveryMethod,
            order.paymentMethod,
            order.pickupDate,
            order.returnDate,
            order.orderDate,
            order.date,
            String(order.pricePerKg),
            String(order.weight),
            String(order.total),
        ].map((f) => (f || '').toLowerCase());

        return fields.some((f) => f.includes(q));
    });
});

// ------------------------------------------------
// UI HANDLERS
// ------------------------------------------------
const toggleDetail = (id: number) => {
    expandedOrder.value = expandedOrder.value === id ? null : id;
};

const toggleDropdown = (id: number) => {
    openDropdown.value = openDropdown.value === id ? null : id;
};

const selectStatus = (order: any, status: string) => {
    order.orderStatus = status;
    openDropdown.value = null;
};

// ------------------------------------------------
// UPDATE & DELETE
// ------------------------------------------------
const updateOrder = (order: any) => {
    if (order.total == null || order.total < 0) {
    alert("Masukkan total harga!");
    return;
}

router.put(`/admin/orders/${order.id}`, {
    status: order.orderStatus,
    status_pembayaran: order.paymentStatus,
    total: order.total,
});


};

const deleteOrder = (id: number) => {
    if (confirm('Yakin ingin menghapus order ini?')) {
        router.delete(`/admin/orders/${id}`);
    }
};

// ------------------------------------------------
// REFRESH FROM DATABASE (PENTING)
// ------------------------------------------------
const refreshOrders = async () => {
    try {
        isRefreshing.value = true;

        const response = await axios.get('/admin/orders/data');

        if (response.data && response.data.orders) {
            orders.value = response.data.orders;
        }

    } catch (error) {
        console.error('❌ Gagal mengambil data terbaru:', error);
        alert('Gagal memuat data terbaru dari server.');
    } finally {
        isRefreshing.value = false;
    }
};

// ------------------------------------------------
// STATUS STYLE HELPERS
// ------------------------------------------------
const statusClass = (status: string) => {
    switch (status) {
        case 'Menunggu Penjemputan':
            return 'bg-yellow-100 text-yellow-700 border border-yellow-300';
        case 'Sedang Diproses':
            return 'bg-blue-100 text-blue-700 border border-blue-300';
        case 'Selesai Diproses':
            return 'bg-pink-100 text-pink-700 border border-pink-300';
        case 'Siap Diambil':
        case 'Siap Diantar':
            return 'bg-green-100 text-green-700 border border-green-300';
        case 'Batal':
            return 'bg-red-100 text-red-700 border border-red-300';
        default:
            return 'bg-gray-100 text-gray-700 border border-gray-300';
    }
};

const statusDotClass = (status: string) => {
    switch (status) {
        case 'Menunggu Penjemputan': return 'bg-yellow-400';
        case 'Sedang Diproses': return 'bg-blue-400';
        case 'Selesai Diproses': return 'bg-pink-400';
        case 'Siap Diambil':
        case 'Siap Diantar': return 'bg-green-400';
        case 'Batal': return 'bg-red-400';
        default: return 'bg-gray-400';
    }
};

const statusTextClass = (status: string) => {
    switch (status) {
        case 'Menunggu Penjemputan': return 'text-yellow-700';
        case 'Sedang Diproses': return 'text-blue-700';
        case 'Selesai Diproses': return 'text-pink-700';
        case 'Siap Diambil':
        case 'Siap Diantar': return 'text-green-700';
        case 'Batal': return 'text-red-700';
        default: return 'text-gray-700';
    }
};

// ------------------------------------------------
// FORMAT TANGGAL
// ------------------------------------------------
const fmt = (isoOrString: unknown) => {
    if (typeof isoOrString !== 'string' && !(isoOrString instanceof Date)) {
        return isoOrString;
    }

    try {
        let d = new Date(isoOrString);

        if (isNaN(d.getTime()) && typeof isoOrString === 'string') {
            d = new Date(`${isoOrString}T00:00:00`);
        }

        return isNaN(d.getTime())
            ? isoOrString
            : d.toLocaleDateString('id-ID', {
                  day: '2-digit',
                  month: 'long',
                  year: 'numeric',
              });
    } catch {
        return isoOrString;
    }
};

const statusOptions = [
    'Menunggu Penjemputan',
    'Sedang Diproses',
    'Selesai Diproses',
    'Siap Diambil',
    'Siap Diantar',
    'Batal',
];

const clearSearch = () => {
    searchQuery.value = '';
};
</script>


<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

.material-icons {
    font-size: 16px;
    vertical-align: middle;
}

/* Animasi dropdown muncul */
.fade-enter-active,
.fade-leave-active {
    transition: all 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-6px);
}

/* Tampilan dropdown mirip Google Sheets */
button.status-badge {
    font-family: 'Poppins', sans-serif;
    transition: all 0.2s ease;
}

button.status-badge:hover {
    transform: scale(1.03);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

ul li:hover {
    background-color: #f9f9f9;
}
</style>
