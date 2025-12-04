kok tampilannya jadi bedaaa sama yang sebelumnya
<template>
    <AdminPanel>
        <Head title="Payments Verification - RonaLaundry" />
        <!-- 🔹 Header -->
        <div class="mb-8">
            <h1 class="mb-6 text-3xl font-semibold text-pink-700">
                Payment Verification
            </h1>

            <!-- 🔹 Stats Cards -->
            <div class="mb-6 grid grid-cols-2 gap-6">
                <div class="rounded-xl border border-pink-200 bg-pink-50 p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-600">Total Semua Pendapatan (Lunas)</p>
                    <h3 class="text-2xl font-bold text-pink-700">Rp {{ totalAllRevenue.toLocaleString() }}</h3>
                </div>
                <div class="rounded-xl border border-blue-200 bg-blue-50 p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-600">Pendapatan Periode Ini (Lunas)</p>
                    <h3 class="text-2xl font-bold text-blue-700">Rp {{ filteredRevenue.toLocaleString() }}</h3>
                </div>
            </div>

            <!-- 🔹 Filter dan Refresh -->
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <!-- Filter Bulan -->
                    <select
                        v-model="filters.month"
                        @change="applyFilters"
                        class="rounded-lg border border-gray-300 px-3 py-2 text-gray-700 focus:ring-2 focus:ring-pink-400 focus:outline-none"
                    >
                        <option value="">Semua Bulan</option>
                        <option v-for="m in 12" :key="m" :value="m">{{ getMonthName(m) }}</option>
                    </select>

                    <!-- Filter Tahun -->
                    <select
                        v-model="filters.year"
                        @change="applyFilters"
                        class="rounded-lg border border-gray-300 px-3 py-2 text-gray-700 focus:ring-2 focus:ring-pink-400 focus:outline-none"
                    >
                        <option value="">Semua Tahun</option>
                        <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
                    </select>

                    <!-- Filter Status (Client-side for now, or could be server-side) -->
                    <select
                        v-model="filterStatus"
                        class="rounded-lg border border-gray-300 px-3 py-2 text-gray-700 focus:ring-2 focus:ring-pink-400 focus:outline-none"
                    >
                        <option value="All">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Lunas">Lunas</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>

                <!-- Tombol Refresh -->
                <button
                    @click="refreshData"
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

        <!-- 🔹 Tabel Payment -->
        <div class="rounded-xl bg-white p-6 shadow-md">
            <table class="w-full border-collapse text-left text-gray-700">
                <thead class="bg-pink-100">
                    <tr>
                        <th class="rounded-tl-lg px-4 py-3">Order ID</th>
                        <th class="px-4 py-3">User Name</th>
                        <th class="px-4 py-3">Payment Method</th>
                        <th class="px-4 py-3">Total Amount</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="rounded-tr-lg px-4 py-3 text-center">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="payment in filteredPayments"
                        :key="payment.id"
                        class="border-b transition hover:bg-pink-50"
                    >
                        <td class="px-4 py-3 font-semibold">
                            #{{ payment.orderId }}
                        </td>
                        <td class="px-4 py-3">{{ payment.userName }}</td>
                        <td class="px-4 py-3">{{ payment.method }}</td>
                        <td class="px-4 py-3">
                            Rp {{ payment.total.toLocaleString() }}
                        </td>
                        <td class="px-4 py-3">
                            <span
                                :class="{
                                    'font-semibold text-yellow-600':
                                        payment.status === 'Pending',
                                    'font-semibold text-green-600':
                                        payment.status === 'Lunas',
                                    'font-semibold text-red-500':
                                        payment.status === 'Rejected',
                                }"
                            >
                                {{ payment.status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button
                                class="mr-2 text-blue-500 hover:underline"
                                @click="viewDetails(payment)"
                            >
                                View
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Jika tidak ada data -->
            <p
                v-if="filteredPayments.length === 0"
                class="py-4 text-center text-gray-500"
            >
                No payment records found.
            </p>

            <!-- Pagination -->
            <div v-if="payments.links.length > 3" class="mt-6 flex justify-center">
                <div class="flex gap-1">
                    <Link
                        v-for="(link, key) in payments.links"
                        :key="key"
                        :href="link.url"
                        class="px-3 py-1 rounded border"
                        :class="{
                            'bg-pink-500 text-white border-pink-500': link.active,
                            'bg-white text-gray-700 border-gray-300 hover:bg-gray-50': !link.active,
                            'opacity-50 cursor-not-allowed': !link.url
                        }"
                    >
                        <span v-html="link.label"></span>
                    </Link>
                </div>
            </div>
        </div>

        <!-- 🔹 Modal Detail Pembayaran -->
        <div
            v-if="selectedPayment"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm"
        >
            <div
                class="animate-fadeIn relative w-[420px] rounded-2xl bg-[#ffe4ec] p-6 shadow-2xl"
            >
                <!-- Tombol Tutup -->
                <button
                    @click="closeModal"
                    class="absolute top-3 right-3 text-lg font-bold text-pink-600 transition hover:text-pink-800"
                >
                    ✕
                </button>

                <h3 class="mb-5 text-center text-xl font-bold text-pink-700">
                    Payment Details
                </h3>

                <!-- Detail Konten -->
                <div class="space-y-2 text-gray-700">
                    <p>
                        <span class="font-semibold text-pink-600"
                            >Order ID:</span
                        >
                        {{ selectedPayment.orderId }}
                    </p>
                    <p>
                        <span class="font-semibold text-pink-600">User:</span>
                        {{ selectedPayment.userName }}
                    </p>
                    <p>
                        <span class="font-semibold text-pink-600"
                            >Payment Method:</span
                        >
                        {{ selectedPayment.method }}
                    </p>
                    <p>
                        <span class="font-semibold text-pink-600">Total:</span>
                        Rp {{ selectedPayment.total.toLocaleString() }}
                    </p>
                    <p>
                        <span class="font-semibold text-pink-600">Status:</span>
                        {{ selectedPayment.status }}
                    </p>
                    <p>
                        <span class="font-semibold text-pink-600">Date:</span>
                        {{ selectedPayment.date }}
                    </p>
                </div>

                <!-- Bukti Pembayaran -->
                <div class="mt-4">
                    <span class="mb-2 block font-semibold text-pink-600"
                        >Payment Proof:</span
                    >
                    <div
                        class="rounded-xl border border-pink-300 bg-white p-2 shadow-sm transition hover:shadow-md"
                    >
                        <img
                            :src="selectedPayment.proof"
                            alt="Payment Proof"
                            class="h-48 w-full rounded-md object-cover"
                        />
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-6 flex justify-center gap-4">
                    <button
                        @click="verifyPayment(selectedPayment.id)"
                        class="rounded-lg bg-green-500 px-6 py-2 text-white shadow-md transition hover:bg-green-600"
                    >
                        Verify
                    </button>
                    <button
                        @click="rejectPayment(selectedPayment.id)"
                        class="rounded-lg bg-red-500 px-6 py-2 text-white shadow-md transition hover:bg-red-600"
                    >
                        Reject
                    </button>
                </div>
            </div>
        </div>
    </AdminPanel>
</template>

<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminPanel from '../AdminPanel.vue';

interface Payment {
    id: number;
    orderId: string;
    userName: string;
    method: string;
    total: number;
    status: string;
    date: string;
    proof?: string;
}

const props = defineProps<{
    payments: {
        data: Payment[];
        links: any[];
    };
    totalAllRevenue: number;
    filteredRevenue: number;
    filters: {
        month?: string;
        year?: string;
    };
    minYear: number;
}>();

// 🔹 State
const filterStatus = ref('All');
const selectedPayment = ref<Payment | null>(null);
const isRefreshing = ref(false);

const filters = ref({
    month: props.filters.month || '',
    year: props.filters.year || '',
});

const years = computed(() => {
    const currentYear = new Date().getFullYear();
    const startYear = props.minYear || currentYear;
    const y = [];
    // Generate years from minYear to currentYear
    for (let year = startYear; year <= currentYear; year++) {
        y.push(year);
    }
    // Sort descending (newest first)
    return y.sort((a, b) => b - a);
});

const getMonthName = (m: number) => {
    const date = new Date();
    date.setMonth(m - 1);
    return date.toLocaleString('default', { month: 'long' });
};

const applyFilters = () => {
    router.get('/admin/payments', filters.value, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

// 🔹 Filter Client Side (Status)
const filteredPayments = computed(() => {
    if (filterStatus.value === 'All') return props.payments.data;
    return props.payments.data.filter(
        (p: Payment) => p.status === filterStatus.value,
    );
});

// 🔹 Actions
const viewDetails = (payment: Payment) => {
    selectedPayment.value = payment;
};

const verifyPayment = (id: number) => {
    if (confirm('Are you sure you want to verify this payment?')) {
        router.post(
            `/admin/payments/${id}/verify`,
            {},
            {
                onSuccess: () => {
                    selectedPayment.value = null;
                    alert("✅ Payment verified successfully!");
                },
            },
        );
    }
};

const rejectPayment = (id: number) => {
    if (confirm('Are you sure you want to reject this payment?')) {
        router.post(
            `/admin/payments/${id}/reject`,
            {},
            {
                onSuccess: () => {
                    selectedPayment.value = null;
                    alert("❌ Payment rejected.");
                },
            },
        );
    }
};

const closeModal = () => {
    selectedPayment.value = null;
};

// 🔹 Refresh Button Animation
const refreshData = () => {
    isRefreshing.value = true;
    router.reload({
        only: ['payments', 'totalAllRevenue', 'filteredRevenue'],
        onFinish: () => (isRefreshing.value = false),
    });
};
</script>

<style scoped>
.material-icons {
    font-size: 18px;
    vertical-align: middle;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.25s ease-out;
}

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
</style>
