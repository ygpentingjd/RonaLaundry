<template>
    <AdminPanel>
        <Head title="Dasboard Admin - RonaLaundry" />
        <!-- 🔹 Header -->
        <div class="mb-8 flex items-center justify-between">
            <h1 class="mb-6 text-3xl font-semibold text-black">
                Dashoard Admin
            </h1>
            <div class="flex items-center space-x-3">
                <span class="text-xl font-semibold text-slate-900">Admin</span>
                <span
                    class="material-icons-round rounded-full border-[1.5px] border-pink-400 bg-pink-50 p-1 text-4xl text-slate-800 shadow-sm"
                >
                    person
                </span>
            </div>
        </div>

        <div class="mb-8 grid grid-cols-4 gap-6">
            <div
                v-for="card in stats"
                :key="card.title"
                class="rounded-xl border border-pink-300 bg-pink-100 p-4 shadow transition hover:shadow-lg"
            >
                <div class="mb-2 flex items-center justify-between">
                    <p class="font-medium text-gray-600">{{ card.title }}</p>
                    <span class="material-icons text-pink-500">{{
                        card.icon
                    }}</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">
                    {{ card.value }}
                </h3>
            </div>
        </div>


        <div class="mb-8 rounded-xl bg-white p-6 shadow-md">
            <h3 class="mb-4 font-semibold text-gray-700">Pemasukan 12 Bulan Terakhir</h3>
            <div class="h-96">
                <apexchart
                    type="bar"
                    height="100%"
                    :options="chartOptions"
                    :series="chartSeries"
                />
            </div>
        </div>

        <!-- 🔹 Recent Tables -->
        <div class="grid grid-cols-2 gap-6">
            <!-- Recent Orders -->
            <div class="rounded-xl bg-white p-4 shadow-md">
                <div class="mb-4 flex justify-between">
                    <h3 class="font-semibold text-gray-700">Recent Orders</h3>
                    <Link href="/admin/orders" class="text-pink-500 hover:underline">
                        View All
                    </Link>
                </div>
                <table class="w-full text-left text-gray-700">
                    <thead class="bg-pink-100">
                        <tr>
                            <th class="px-3 py-2">Customer</th>
                            <th class="px-3 py-2">Service</th>
                            <th class="px-3 py-2">Date</th>
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
                            <td class="px-3 py-2 text-sm text-gray-500">{{ order.date }}</td>
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
                    <h3 class="font-semibold text-gray-700">Recent Users</h3>
                    <Link href="/admin/users" class="text-pink-500 hover:underline">
                        View All
                    </Link>
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
    </AdminPanel>
</template>

<script setup lang="ts">
import AdminPanel from '../AdminPanel.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import type { ApexOptions } from 'apexcharts'
import ApexCharts from 'vue3-apexcharts'
const apexchart = ApexCharts

interface StatCard {
  title: string;
  value: number;
  icon: string;
}

interface ChartSeries {
  name: string;
  data: number[];
}

interface Order {
  id: number;
  name: string;
  service: string;
  status: string;
  date?: string;
}

interface User {
  id: number;
  name: string;
  email: string;
}

const props = defineProps<{
  stats: StatCard[];
  chartSeries: ChartSeries[];
  chartCategories: string[];
  recentOrders: Order[];
  recentUsers: User[];
}>();

const chartOptions = ref<ApexOptions>({
    chart: { type: 'bar', toolbar: { show: false } },
    colors: ['#ec4899'], //c4899
    plotOptions: { bar: { borderRadius: 6, columnWidth: '45%' } },
    dataLabels: { enabled: false },
    xaxis: {
        categories: props.chartCategories,
    },
    yaxis: { labels: { formatter: (val) => `Rp ${val.toLocaleString()}` } },
    grid: { borderColor: '#f3f4f6' }, 
    tooltip: {
        enabled: true,
        theme: 'light',
        style: {
            fontSize: '12px',
        },
        y: {
            formatter: (val) => `Rp ${val.toLocaleString()}`,
        },
    },
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
.material-icons {
    font-size: 20px;
}

/* 🔹 Force Tooltip Text Black */
:deep(.apexcharts-tooltip-title),
:deep(.apexcharts-tooltip-text) {
    color: #000000 !important;
}
</style>
