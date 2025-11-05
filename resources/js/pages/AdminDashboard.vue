<template>
    <Head title="Dashboard Admin - RonaLaundry" />
    <div class="font-poppins flex min-h-screen bg-pink-50">
        <!-- 🔹 Sidebar -->
        <Sidebar />
        <!-- 🔹 Main Content -->
        <main class="ml-64 flex-1 overflow-y-auto p-8">
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
                <div class="flex items-center space-x-3">
                    <span class="text-xl font-semibold text-slate-900"
                        >Admin</span
                    >
                    <span
                        class="material-icons-round rounded-full border-[1.5px] border-pink-400 bg-pink-50 p-1 text-4xl text-slate-800 shadow-sm"
                    >
                        person
                    </span>
                </div>
            </div>
            <!-- Stats Cards -->
            <div class="mb-8 grid grid-cols-4 gap-6">
                <div
                    v-for="card in stats"
                    :key="card.title"
                    class="rounded-xl border border-pink-300 bg-pink-100 p-4 shadow transition hover:shadow-lg"
                >
                    <div class="mb-2 flex items-center justify-between">
                        <p class="font-medium text-gray-600">
                            {{ card.title }}
                        </p>
                        <span class="material-icons text-pink-500">{{
                            card.icon
                        }}</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">
                        {{ card.value }}
                    </h3>
                </div>
            </div>

            <!-- Chart Pemasukan Bulanan -->
            <div class="mb-8 rounded-xl bg-white p-6 shadow-md">
                <h3 class="mb-4 font-semibold text-gray-700">
                    Pemasukan Bulanan
                </h3>
                <div class="h-96">
                    <apexchart
                        type="bar"
                        height="100%"
                        :options="chartOptions"
                        :series="chartSeries"
                    />
                </div>
            </div>

            <!-- Recent Tables -->
            <div class="grid grid-cols-2 gap-6">
                <!-- Recent Orders -->
                <div class="rounded-xl bg-white p-4 shadow-md">
                    <div class="mb-4 flex justify-between">
                        <h3 class="font-semibold text-gray-700">
                            Recent Orders
                        </h3>
                        <button class="text-pink-500 hover:underline">
                            View All
                        </button>
                    </div>
                    <table class="w-full text-left text-gray-700">
                        <thead class="bg-pink-100">
                            <tr>
                                <th class="px-3 py-2">Customer</th>
                                <th class="px-3 py-2">Service</th>
                                <th class="px-3 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="order in recentOrders"
                                :key="order.id"
                                class="border-b hover:bg-pink-50"
                            >
                                <td class="px-3 py-2">{{ order.name }}</td>
                                <td class="px-3 py-2">{{ order.service }}</td>
                                <td class="px-3 py-2">
                                    <span
                                        :class="{
                                            'font-semibold text-green-600':
                                                order.status === 'Selesai',
                                            'font-semibold text-yellow-600':
                                                order.status === 'Proses',
                                        }"
                                    >
                                        {{ order.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Recent Users -->
                <div class="rounded-xl bg-white p-4 shadow-md">
                    <div class="mb-4 flex justify-between">
                        <h3 class="font-semibold text-gray-700">
                            Recent Users
                        </h3>
                        <button class="text-pink-500 hover:underline">
                            View All
                        </button>
                    </div>
                    <table class="w-full text-left text-gray-700">
                        <thead class="bg-pink-100">
                            <tr>
                                <th class="px-3 py-2">Name</th>
                                <th class="px-3 py-2">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="user in recentUsers"
                                :key="user.id"
                                class="border-b hover:bg-pink-50"
                            >
                                <td class="px-3 py-2">{{ user.name }}</td>
                                <td class="px-3 py-2">{{ user.email }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup lang="ts">
import Sidebar from '@/components/Sidebar.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import ApexCharts from 'vue3-apexcharts';

// 🔹 Registrasi komponen ApexCharts agar bisa digunakan
const apexchart = ApexCharts;

// 🔹 Sidebar Menu
const menus = ref([
    { text: 'Dashboard', icon: 'dashboard', link: '/admin/dashboard' },
    { text: 'Products', icon: 'inventory_2', link: '/admin/products' },
    { text: 'Users', icon: 'group', link: '/admin/users' },
    { text: 'Orders', icon: 'shopping_cart', link: '/admin/orders' },
    { text: 'Payment Verification', icon: 'payments', link: '/admin/payments' },
]);

// 🔹 Statistik
const stats = ref([
    { title: 'Total Products', value: 12, icon: 'shopping_bag' },
    { title: 'Total Users', value: 32, icon: 'group' },
    { title: 'Total Orders', value: 18, icon: 'assignment' },
    { title: 'Pending Verification', value: 2, icon: 'hourglass_empty' },
]);

// 🔹 Data Chart (Pemasukan Bulanan)
const chartSeries = ref([
    {
        name: 'Pemasukan (Rp)',
        data: [
            3200000, 2800000, 3500000, 4000000, 4500000, 5000000, 4800000,
            5200000, 4700000, 4900000, 5300000, 6000000,
        ],
    },
]);

const chartOptions = ref({
    chart: {
        type: 'bar',
        toolbar: { show: false },
        animations: { enabled: true, easing: 'easeinout', speed: 800 },
    },
    colors: ['#ec4899'],
    plotOptions: {
        bar: {
            borderRadius: 6,
            columnWidth: '45%',
            distributed: false,
        },
    },
    dataLabels: { enabled: false },
    xaxis: {
        categories: [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec',
        ],
        labels: { style: { colors: '#6b7280' } },
    },
    yaxis: {
        labels: {
            style: { colors: '#6b7280' },
            formatter: (val) => `Rp ${val.toLocaleString()}`,
        },
    },
    tooltip: {
        y: { formatter: (val) => `Rp ${val.toLocaleString()}` },
    },
    grid: {
        borderColor: '#f3f4f6',
    },
    title: {
        text: 'Grafik Pemasukan Bulanan',
        align: 'center',
        style: { fontSize: '16px', color: '#1f2937', fontWeight: 'bold' },
    },
});

// 🔹 Data Recent Orders
const recentOrders = ref([
    { id: 1, name: 'Akmal', service: 'Cuci Kering', status: 'Selesai' },
    { id: 2, name: 'Siti', service: 'Setrika', status: 'Proses' },
    { id: 3, name: 'Roni', service: 'Cuci Karpet', status: 'Selesai' },
]);

// 🔹 Data Recent Users
const recentUsers = ref([
    { id: 1, name: 'Admin', email: 'admin@rona.com' },
    { id: 2, name: 'Test User', email: 'user@rona.com' },
]);
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

.material-icons {
    font-size: 20px;
}
</style>
