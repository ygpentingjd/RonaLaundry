<template>
    <Head title="My Laundry - RonaLaundry" />
    <UserLayout>
        <div class="mb-8 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">My Laundry</h2>
        </div>

        <div class="space-y-6">
            <div
                v-for="order in orders"
                :key="order.id"
                class="rounded-2xl border border-pink-100 bg-pink-50 p-6 shadow-md transition duration-200 hover:shadow-lg"
            >
                <!-- Header Card -->
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-pink-400 font-semibold text-white"
                            >
                                <span class="material-icons text-sm"
                                    >local_laundry_service</span
                                >
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                {{ order.layanan }}
                            </h3>
                        </div>
                        <div
                            class="mt-1 flex items-center gap-1 text-sm text-gray-500"
                        >
                            <span class="material-icons text-sm">schedule</span>
                            {{ formatFullDate(order.tanggal_kembali) }}
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
                        <!-- Identitas Pemesan -->
                        <div>
                            <p class="mb-2 font-semibold">Identitas Pemesan</p>
                            <table class="mb-2 w-full">
                                <tbody>
                                    <tr>
                                        <td class="py-1">Nama Pemesan</td>
                                        <td
                                            class="py-1 text-right text-gray-600"
                                        >
                                            {{ order.nama }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">Alamat</td>
                                        <td
                                            class="py-1 text-right text-gray-600"
                                        >
                                            {{ order.alamat }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">Jadwal Pengambilan</td>
                                        <td
                                            class="py-1 text-right text-gray-600"
                                        >
                                            {{ formatFullDate(order.tanggal) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Status Pembayaran -->
                        <div>
                            <p class="mb-2 font-semibold">Status Pembayaran</p>
                            <span
                                class="rounded-full px-3 py-1 text-sm font-medium"
                                :class="{
                                    'bg-yellow-100 text-yellow-700':
                                        order.payment_status === 'Pending',
                                    'bg-green-100 text-green-700':
                                        order.payment_status === 'Lunas',
                                    'bg-red-100 text-red-700':
                                        order.payment_status === 'Rejected',
                                }"
                            >
                                {{ order.payment_status }}
                            </span>
                        </div>

                        <!-- ❗ UPLOAD BUKTI PEMBAYARAN -->
                        <div v-if="order.payment_status === 'Pending'">
                            <p class="mt-4 mb-2 font-semibold">
                                Upload Bukti Pembayaran
                            </p>

                            <!-- Jika sudah upload -->
                            <div v-if="order.bukti_pembayaran" class="mb-3">
                                <img
                                    :src="`/storage/${order.bukti_pembayaran}`"
                                    class="w-40 rounded-lg shadow"
                                />
                            </div>

                            <input
                                type="file"
                                class="mb-3 mr-4 w-70 rounded-lg border border-gray-300 p-2 text-sm text-gray-700 focus:border-pink-500 focus:outline-none"
                                accept="image/*"
                                @change="(e) => selectPaymentFile(e, order.id)"
                            />

                            <button
                                @click="uploadPayment(order.id)"
                                class="rounded-lg bg-blue-500 px-4 py-2 text-white shadow hover:bg-blue-600"
                            >
                                Upload
                            </button>
                        </div>
                        <!-- END UPLOAD -->

                        <!-- Status Pesanan -->
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

                        <!-- Detail Order -->
                        <div>
                            <p class="mt-4 mb-2 font-semibold">Detail Order</p>
                            <table class="mb-4 w-full">
                                <tbody>
                                    <tr>
                                        <td class="py-1">Jenis Barang</td>
                                        <td class="py-1 text-right">
                                            {{ order.barang.join(', ') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">Pesan Tambahan</td>
                                        <td
                                            class="py-1 text-right text-gray-600"
                                        >
                                            {{ order.pesan }}
                                        </td>
                                    </tr>
                                    <!-- 🔹 Dynamic Items -->
                                    <template v-if="order.items && order.items.length > 0">
                                        <tr v-for="(item, index) in order.items" :key="index">
                                            <td class="py-1">{{ item.label }}</td>
                                            <td class="py-1 text-right">
                                                {{ item.qty }} x Rp {{ item.price.toLocaleString() }}
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr>
                                            <td class="py-1">Harga per Kg</td>
                                            <td class="py-1 text-right">
                                                Rp
                                                {{
                                                    order.harga_per_kg
                                                        ? order.harga_per_kg.toLocaleString()
                                                        : '-'
                                                }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-1">Berat Laundry</td>
                                            <td
                                                class="py-1 text-right text-gray-600 italic"
                                            >
                                                {{
                                                    order.berat
                                                        ? order.berat + ' kg'
                                                        : 'Menunggu input admin'
                                                }}
                                            </td>
                                        </tr>
                                    </template>
                                    <tr>
                                        <td class="pt-3 font-semibold">
                                            Total
                                        </td>
                                        <td
                                            class="pt-3 text-right font-bold text-gray-900"
                                        >
                                            Rp
                                            {{
                                                (order.total || (order.berat && order.harga_per_kg ? order.berat * order.harga_per_kg : 0)).toLocaleString()
                                            }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">Metode Pembayaran</td>
                                        <td class="py-1 text-right">
                                            {{ order.pembayaran }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="mt-4 flex justify-end">
                            <button
                                class="hover:non text-red-500"
                                @click="cancelOrder(order.id)"
                            >
                                Batal Pesan
                            </button>
                        </div>

                        <!-- Waktu Update -->
                        <div
                            class="mt-4 flex items-center gap-1 text-xs text-gray-500"
                        >
                            <span class="material-icons text-sm">update</span>
                            {{ formatFullDate(order.created_at) }}
                        </div>
                    </div>
                </transition>
            </div>

            <p
                v-if="orders.length === 0"
                class="mt-10 text-center text-gray-500"
            >
                Tidak ada pesanan aktif saat ini!
            </p>
        </div>
    </UserLayout>
</template>

<script setup lang="ts">
import UserLayout from '@/layouts/UserLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

// 🔹 Tipe sesuai data yang dikirim (bukti_pembayaran sudah di level utama)
interface Order {
    id: number;
    layanan: string;
    tanggal: string;
    nama: string;
    alamat: string;
    pesan?: string;
    payment_status: 'Pending' | 'Verified' | 'Rejected' | 'Lunas';
    bukti_pembayaran: string | null; // ✅ sekarang tersedia!
    status: string;
    barang: string[];
    harga_per_kg: number | null;
    berat: number | null;
    total: number | null;
    pembayaran: string; // metode pembayaran (string)
    updated_at: string;
    created_at: string;
    tanggal_kembali: string;
    items?: { label: string; qty: number; price: number }[];
}

const { props } = usePage<{ reservasis: Order[] }>();
const orders = ref<Order[]>(props.reservasis || []);
const expandedOrder = ref<number | null>(null);
const paymentFiles = ref<Record<number, File | null>>({});

const toggleDetail = (id: number) => {
    expandedOrder.value = expandedOrder.value === id ? null : id;
};

const cancelOrder = (id: number) => {
    if (confirm('Yakin ingin membatalkan pesanan ini?')) {
        router.post(
            `/reservasi/${id}/cancel`,
            {},
            {
                onSuccess: () => {
                    orders.value = orders.value.filter(
                        (order) => order.id !== id,
                    );
                    alert('Pesanan berhasil dibatalkan!');
                },
                onError: () => {
                    alert('Gagal membatalkan pesanan.');
                },
            },
        );
    }
};

const selectPaymentFile = (e: Event, id: number) => {
    const input = e.target as HTMLInputElement;
    if (input.files?.length) {
        paymentFiles.value[id] = input.files[0];
    }
};

const uploadPayment = (id: number) => {
    if (!paymentFiles.value[id]) {
        alert('Silakan pilih gambar bukti terlebih dahulu.');
        return;
    }

    const formData = new FormData();
    formData.append('bukti_pembayaran', paymentFiles.value[id]!);

    // ✅ Sesuai route: /payment/upload/{id}
    router.post(`/payment/upload/${id}`, formData, {
        forceFormData: true,
        onSuccess: () => {
            alert('Bukti pembayaran berhasil diupload!');
            // Refresh agar data terbaru (termasuk bukti_pembayaran) muncul
            router.visit(route('mylaundry'), {
                preserveState: false,
                preserveScroll: true,
            });
        },
        onError: (errors) => {
            console.error('Upload error:', errors);
            alert('Gagal upload. Cek file (max 2MB, JPG/PNG).');
        },
    });
};

const statusClass = (status?: string | null): string => {
    if (!status) return 'bg-gray-100 text-gray-700 border-gray-300';
    const s = status.toLowerCase();
    if (s.includes('menunggu'))
        return 'bg-yellow-100 text-yellow-700 border-yellow-300';
    if (s.includes('diproses'))
        return 'bg-blue-100 text-blue-700 border-blue-300';
    if (s.includes('selesai'))
        return 'bg-pink-100 text-pink-700 border-pink-300';
    if (s.includes('siap'))
        return 'bg-green-100 text-green-700 border-green-300';
    if (s.includes('batal')) return 'bg-red-100 text-red-700 border-red-300';
    return 'bg-gray-100 text-gray-700 border-gray-300';
};

const formatFullDate = (dateStr?: string | null): string => {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    const tanggal = date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
    const waktu = date
        .toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
        })
        .replace(':', '.');
    return `${tanggal} | ${waktu} WIB`;
};
</script>
