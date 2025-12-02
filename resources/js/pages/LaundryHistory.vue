<template>
    <Head title="Laundry History - RonaLaundry" />
    <UserLayout>
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Riwayat Laundry</h2>
        </div>

        <!-- History List -->
        <div class="space-y-6">
            <div
                v-for="order in completedOrders"
                :key="order.id"
                class="rounded-2xl border border-pink-100 bg-white p-6 shadow-md transition duration-200 hover:shadow-lg"
            >
                <!-- Header Card -->
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-pink-400 font-semibold text-white"
                            >
                                <span class="material-icons text-sm"
                                    >done_all</span
                                >
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                {{ order.service }}
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
                </div>

                <!-- Detail -->
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
                                    <tr>
                                        <td class="py-1">Jadwal Pengambilan</td>
                                        <td
                                            class="py-1 text-right text-gray-600"
                                        >
                                            {{ order.pickupDate }}
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
                                    'bg-green-100 text-green-700':
                                        order.paymentStatus === 'Lunas',
                                    'bg-yellow-100 text-yellow-700':
                                        order.paymentStatus === 'Pending',
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
                            <span
                                class="rounded-full border px-3 py-1 text-sm font-medium"
                                :class="statusClass(order.status)"
                            >
                                {{ order.status }}
                            </span>
                        </div>

                        <!-- 🔹 Detail Order -->
                        <div>
                            <p class="mt-4 mb-2 font-semibold">Detail Order</p>
                            <table class="mb-4 w-full">
                                <tbody>
                                    <tr>
                                        <td class="py-1">Jenis Layanan</td>
                                        <td class="py-1 text-right">
                                            {{ order.service }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">Harga per Kg</td>
                                        <td class="py-1 text-right">
                                            Rp
                                            {{
                                                order.pricePerKg.toLocaleString()
                                            }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">Berat Laundry</td>
                                        <td
                                            class="py-1 text-right text-gray-700"
                                        >
                                            {{ order.weight }} kg
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
                                                (
                                                    order.weight *
                                                    order.pricePerKg
                                                ).toLocaleString()
                                            }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- 🔹 Keterangan Tambahan -->
                        <div v-if="order.notes" class="mt-3">
                            <p class="mb-1 font-semibold">Catatan:</p>
                            <p class="text-gray-600 italic">
                                {{ order.notes }}
                            </p>
                        </div>

                        <!-- 🔹 Info Tanggal -->
                        <div
                            class="mt-4 flex items-center gap-1 text-xs text-gray-500"
                        >
                            <span class="material-icons text-sm"
                                >event_available</span
                            >
                            {{ order.date }}
                        </div>
                    </div>
                </transition>
            </div>

            <!-- Jika tidak ada riwayat -->
            <p
                v-if="completedOrders.length === 0"
                class="mt-10 text-center text-gray-500"
            >
                Belum ada riwayat laundry selesai 😌
            </p>
        </div>
    </UserLayout>
</template>

<script setup lang="ts">
import UserLayout from '@/layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps<{
    historyOrders: any[];
}>();

const expandedOrder = ref(null);

// 🔹 Gunakan data dari props
const completedOrders = computed(() => props.historyOrders || []);

// 🔹 Toggle detail
const toggleDetail = (id: any) => {
    expandedOrder.value = expandedOrder.value === id ? null : id;
};

// 🔹 Warna status
const statusClass = (status: string) => {
    switch (status) {
        case 'Selesai Diproses':
            return 'bg-green-100 text-green-700 border-green-300';
        case 'Siap Diambil':
        case 'Siap Diantar':
            return 'bg-blue-100 text-blue-700 border-blue-300';
        default:
            return 'bg-gray-100 text-gray-700 border-gray-300';
    }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

.material-icons {
    font-size: 16px;
    vertical-align: middle;
}

/* Animasi */
.fade-enter-active,
.fade-leave-active {
    transition: all 0.25s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-6px);
}
</style>
