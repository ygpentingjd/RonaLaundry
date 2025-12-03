<template>
    <AdminPanel>
        <Head title="Orders Management - RonaLaundry" />
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-3xl font-semibold text-pink-700">
                Orders Management
            </h1>

            <div class="flex items-center gap-3">
                <!-- Search Bar -->
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Cari order..."
                    class="w-64 rounded-lg border border-pink-300 bg-white px-4 py-2 text-gray-700 shadow-sm transition focus:ring-2 focus:ring-pink-400 focus:outline-none"
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
                    class="rounded-lg bg-pink-500 px-4 py-2 text-white shadow transition hover:bg-pink-600 disabled:opacity-50"
                >
                    <span
                        class="material-icons mr-1 align-middle text-sm"
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
                class="rounded-2xl bg-pink-50 p-6 shadow-md transition duration-200"
            >
                <!-- Header Card -->
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-pink-400 font-semibold text-white"
                            >
                                {{ String(order.id).padStart(2, '0') }}
                            </div>
                            <!-- Hanya tampilkan Order ID -->
                            <h3 class="text-lg font-semibold text-gray-800">
                                Order #{{ order.id }}
                            </h3>
                        </div>
                        <div
                            class="mt-1 flex items-center gap-1 text-sm text-gray-500"
                        >
                            <span class="material-icons text-sm">schedule</span>
                            {{ order.date }}
                        </div>
                    </div>

                    <button
                        @click="toggleDetail(order.id)"
                        class="rounded-lg bg-pink-400 px-4 py-1 text-sm text-white shadow transition hover:bg-pink-500"
                    >
                        {{
                            expandedOrder === order.id
                                ? 'Tutup ▲'
                                : 'Lihat Detail ▼'
                        }}
                    </button>
                    <button
                        v-if="order.orderStatus !== 'Selesai'"
                        @click="markAsDone(order)"
                        class="rounded-lg bg-pink-400 px-4 py-1 text-sm text-white shadow transition hover:bg-pink-500"
                    >
                        Selesai
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
                            <table class="mb-2 w-full">
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
                                class="rounded-full px-3 py-1 text-sm font-medium"
                                :class="{
                                    'bg-yellow-100 text-yellow-700':
                                        order.paymentStatus === 'Pending',
                                    'bg-green-100 text-green-700':
                                        order.paymentStatus === 'Lunas',
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
                                    class="flex w-52 items-center justify-between rounded-full px-3 py-1 text-sm font-medium"
                                    :class="statusClass(order.orderStatus)"
                                >
                                    {{ order.orderStatus }}
                                    <span class="material-icons ml-1 text-xs"
                                        >arrow_drop_down</span
                                    >
                                </button>

                                <transition name="fade">
                                    <div
                                        v-if="openDropdown === order.id"
                                        class="absolute z-50 mt-1 w-52 rounded-lg border border-gray-200 bg-white shadow-lg"
                                    >
                                        <ul class="py-1 text-sm text-gray-700">
                                            <li
                                                v-for="status in statusOptions"
                                                :key="status"
                                                @click="
                                                    selectStatus(order, status)
                                                "
                                                class="flex cursor-pointer items-center rounded-lg px-3 py-2 hover:bg-gray-100"
                                            >
                                                <span
                                                    class="mr-2 h-3 w-3 rounded-full"
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
                            <table class="mb-4 w-full">
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
                                        <td class="py-1">Pesan Tambahan</td>
                                        <td
                                            class="py-1 text-right text-gray-600"
                                        >
                                            {{ order.message }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">Tanggal Masuk</td>
                                        <td class="py-1 text-right">
                                            {{ fmt(order.orderDate) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">
                                            Tanggal Pengambilan
                                        </td>
                                        <td class="py-1 text-right">
                                            {{ fmt(order.returnDate) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">Metode Pengantaran</td>
                                        <td class="py-1 text-right">
                                            {{ order.deliveryMethod }}
                                        </td>
                                    </tr>
                                    <!-- 🔹 Dynamic Input based on Unit -->
                                    <tr v-for="(item, index) in getOrderItems(order)" :key="index">
                                        <td class="py-1">
                                            {{ item.label }}
                                        </td>
                                        <td class="py-1 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <span class="text-xs text-gray-500">
                                                    (@ {{ formatPrice(item.price) }})
                                                </span>
                                                <input
                                                    v-model.number="item.qty"
                                                    type="number"
                                                    min="0"
                                                    class="w-24 rounded-lg border px-2 py-1 text-right text-sm focus:ring-2 focus:ring-pink-300 focus:outline-none"
                                                    @input="calculateTotal(order)"
                                                />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">Total Harga</td>
                                        <td class="py-1 text-right">
                                            <input
                                                v-model.number="order.total"
                                                type="number"
                                                min="0"
                                                readonly
                                                class="w-32 rounded-lg border bg-gray-100 px-2 py-1 text-right text-sm focus:outline-none"
                                            />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pt-3 font-semibold">
                                            Total
                                        </td>
                                        <td
                                            class="pt-3 text-right font-bold text-gray-900"
                                        >
                                            Rp
                                            {{
                                                order.total
                                                    ? order.total.toLocaleString()
                                                    : '—'
                                            }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- 🔹 Tombol Aksi -->
                        <div class="mt-4 flex justify-end gap-3">
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
import { Head, router } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import AdminPanel from '../AdminPanel.vue';

// ------------------------------------------------
// TYPES
// ------------------------------------------------
interface Order {
    id: number;
    customer: string;
    address: string;
    service: string;
    stuff: string;
    message: string;
    orderStatus: string;
    paymentStatus: string;
    status_pembayaran: string;
    pricePerKg: number;
    weight: number;
    orderDate: string;
    returnDate: string;
    deliveryMethod: string;
    date: string;
    paymentMethod?: string;
    pickupDate?: string;
    massage?: string;
    total?: number;
    // Local state for dynamic items
    parsedItems?: {
        name: string;
        label: string;
        unit: string;
        price: number;
        qty: number;
    }[];
}


const props = defineProps<{
    orders: Order[];
    products: any[];
}>();


const orderList = ref<Order[]>([]);

// Helper to find product
const findProductByName = (name: string) => {
    if (!props.products) return null;
    return props.products.find(p => name.toLowerCase().includes(p.nama_barang.toLowerCase()));
};

const getOrderItems = (order: Order) => {
    if (order.parsedItems) return order.parsedItems;

    // Parse stuff string: "Selimut, Boneka" -> ["Selimut", "Boneka"]
    const items = order.stuff.split(',').map(s => s.trim());
    
    order.parsedItems = items.map(itemName => {
        const product = findProductByName(itemName);
        const unit = product?.satuan || 'kg';
        
        // Determine price based on service (Biasa/Kilat)
        let price = 0;
        if (product) {
            price = order.service.toLowerCase() === 'kilat' 
                ? Number(product.harga_kilat) 
                : Number(product.harga_reguler);
        }

        let label = 'Berat (kg)';
        if (unit === 'pcs') label = `Jumlah ${itemName} (pcs)`;
        else if (unit === 'meter') label = `Panjang ${itemName} (m)`;
        else label = `Berat ${itemName} (kg)`;

        return {
            name: itemName,
            label,
            unit,
            price,
            qty: 0 // Default qty
        };
    });

    return order.parsedItems;
};

const calculateTotal = (order: Order) => {
    if (!order.parsedItems) return;
    
    const total = order.parsedItems.reduce((sum, item) => {
        return sum + (item.qty * item.price);
    }, 0);

    order.total = total;
    
    order.weight = order.parsedItems.reduce((sum, item) => sum + item.qty, 0);
};

const formatPrice = (price: number) => {
    return 'Rp ' + price.toLocaleString();
};

onMounted(() => {
    if (props.orders) {
        orderList.value = props.orders;
    }
});

const captureState = () => {
    const map: Record<number, any> = {};
    orderList.value.forEach(order => {
        if (order.parsedItems) {
            map[order.id] = order.parsedItems;
        }
    });
    return map;
};

const restoreState = (savedMap: Record<number, any>) => {
    if (!props.orders) return;
    
    orderList.value = props.orders.map(newOrder => {
        if (savedMap[newOrder.id]) {
            newOrder.parsedItems = savedMap[newOrder.id];
            calculateTotal(newOrder);
        }
        return newOrder;
    });
};

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
    if (!q) return orderList.value;

    return orderList.value.filter((order: Order) => {
        const fields = [
            String(order.id),
            order.customer,
            order.address,
            order.service,
            order.stuff,
            order.orderStatus,
            order.paymentStatus,
            order.status_pembayaran,
            order.deliveryMethod,
            order.paymentMethod,
            order.pickupDate,
            order.returnDate,
            order.orderDate,
            order.date,
            order.massage,
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

const selectStatus = (order: Order, status: string) => {
    order.orderStatus = status;
    openDropdown.value = null;
};

// ------------------------------------------------
// UPDATE & DELETE
// ------------------------------------------------
const markAsDone = (order: Order) => {
    if (confirm('Tandai pesanan ini sebagai Selesai?')) {
        order.orderStatus = 'Selesai';
        updateOrder(order);
    }
};

const updateOrder = (order: Order) => {
    if (!order.weight || order.weight <= 0) {
        alert('Masukkan jumlah/berat laundry terlebih dahulu!');
        return;
    }

    const savedState = captureState();

    router.put(
        `/admin/orders/${order.id}`,
        {
            status_pesanan: order.orderStatus,
            status_pembayaran: order.paymentStatus,
            berat: order.weight, // Sum of quantities
            harga_per_kg: 0, // Not used anymore
            total: order.total,
            items: order.parsedItems, // Send detailed items
        },
        {
            onSuccess: () => {
                alert('Order berhasil diperbarui');
                // Restore state after props update
                restoreState(savedState);
            },
            onError: (errors) => {
                console.error(errors);
                alert('Gagal memperbarui order');
            },
        },
    );
};

const deleteOrder = (id: number) => {
    if (confirm('Yakin ingin menghapus order ini?')) {
        router.delete(`/admin/orders/${id}`, {
            onSuccess: () => {
                alert('Order berhasil dihapus');
                router.reload({ only: ['orders'] });
            },
        });
    }
};

// ------------------------------------------------
// REFRESH FROM DATABASE (PENTING)
// ------------------------------------------------
const refreshOrders = () => {
    const savedState = captureState();
    isRefreshing.value = true;
    router.reload({
        only: ['orders'],
        onFinish: () => {
            isRefreshing.value = false;
            restoreState(savedState);
        },
    });
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
        case 'Selesai':
            return 'bg-green-100 text-green-700 border border-green-300';
        case 'Batal':
            return 'bg-red-100 text-red-700 border border-red-300';
        default:
            return 'bg-gray-100 text-gray-700 border border-gray-300';
    }
};

const statusDotClass = (status: string) => {
    switch (status) {
        case 'Menunggu Penjemputan':
            return 'bg-yellow-400';
        case 'Sedang Diproses':
            return 'bg-blue-400';
        case 'Selesai Diproses':
            return 'bg-pink-400';
        case 'Siap Diambil':
        case 'Siap Diantar':
        case 'Selesai':
            return 'bg-green-400';
        case 'Batal':
            return 'bg-red-400';
        default:
            return 'bg-gray-400';
    }
};

const statusTextClass = (status: string) => {
    switch (status) {
        case 'Menunggu Penjemputan':
            return 'text-yellow-700';
        case 'Sedang Diproses':
            return 'text-blue-700';
        case 'Selesai Diproses':
            return 'text-pink-700';
        case 'Siap Diambil':
        case 'Siap Diantar':
        case 'Selesai':
            return 'text-green-700';
        case 'Batal':
            return 'text-red-700';
        default:
            return 'text-gray-700';
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
    'Selesai',
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
