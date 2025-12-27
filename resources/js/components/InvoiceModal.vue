<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 transition-opacity">
        <!-- Modal Container -->
        <div class="relative w-full max-w-2xl transform overflow-hidden rounded-lg bg-white shadow-xl transition-all">
            <!-- Close Button (Screen Only) -->
            <button
                @click="$emit('close')"
                class="absolute right-4 top-4 text-gray-400 hover:text-gray-600 print:hidden"
            >
                <span class="material-icons">close</span>
            </button>

            <!-- Invoice Content (Paper Style) -->
            <div id="invoice-content" class="bg-white p-8 md:p-12 print:w-full print:p-0">
                <!-- Header -->
                <div class="mb-8 flex items-start justify-between border-b pb-6">
                    <div class="flex items-center gap-4">
                        <img src="/images/logo.png" alt="Rona Laundry" class="h-16 w-16 object-contain" />
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Rona Laundry</h1>
                            <p class="text-sm text-gray-500">Professional Laundry Service</p>
                            <p class="text-xs text-gray-400">Jl. Contoh No. 123, Kota Laundry</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <h2 class="text-xl font-bold text-gray-700">INVOICE</h2>
                        <p class="text-sm font-semibold text-pink-500">#{{ order.id }}</p>
                        <p class="mt-1 text-xs text-gray-500">Tanggal: {{ formatDate(new Date()) }}</p>
                    </div>
                </div>

                <!-- Customer Info & Dates -->
                <div class="mb-8 grid grid-cols-2 gap-8">
                    <div>
                        <h3 class="mb-2 text-sm font-bold uppercase text-gray-500">Customer</h3>
                        <p class="font-semibold text-gray-800">{{ order.nama || order.customer }}</p>
                        <p class="text-sm text-gray-600">{{ order.alamat || order.address }}</p>
                    </div>
                    <div class="text-right">
                        <h3 class="mb-2 text-sm font-bold uppercase text-gray-500">Detail Pesanan</h3>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Masuk:</span> {{ order.date || formatDate(order.created_at) }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Jadwal:</span> {{ order.pickupDate || formatDate(order.tanggal_kembali) }}
                        </p>
                        <p class="mt-2 text-sm font-semibold text-gray-800">
                            Status: <span class="uppercase text-pink-500">{{ order.paymentStatus || order.payment_status }}</span>
                        </p>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="mb-8">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold uppercase text-gray-600">
                                <th class="p-3">Item / Layanan</th>
                                <th class="p-3 text-right">Qty / Berat</th>
                                <th class="p-3 text-right">Harga</th>
                                <th class="p-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700">
                            <!-- Dynamic Items -->
                            <template v-if="order.items && order.items.length > 0">
                                <tr v-for="(item, index) in order.items" :key="index" class="border-b border-gray-100">
                                    <td class="p-3">{{ item.label }}</td>
                                    <td class="p-3 text-right">{{ item.qty }}</td>
                                    <td class="p-3 text-right">{{ formatCurrency(item.price) }}</td>
                                    <td class="p-3 text-right">{{ formatCurrency(item.qty * item.price) }}</td>
                                </tr>
                            </template>
                            
                            <!-- Fallback for simple kiloan -->
                            <tr v-else class="border-b border-gray-100">
                                <td class="p-3">{{ order.service || order.layanan }} ({{ (order.barang || []).join(', ') }})</td>
                                <td class="p-3 text-right">{{ order.weight || order.berat || '-' }} kg</td>
                                <td class="p-3 text-right">{{ formatCurrency(order.pricePerKg || order.harga_per_kg) }}</td>
                                <td class="p-3 text-right">{{ formatCurrency(order.total || ((order.weight || 0) * (order.pricePerKg || 0))) }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="font-bold text-gray-800">
                                <td colspan="3" class="border-t p-3 text-right">TOTAL</td>
                                <td class="border-t p-3 text-right text-lg text-pink-600">
                                    {{ formatCurrency(order.total || ((order.weight || 0) * (order.pricePerKg || 0))) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Footer / Notes -->
                <div class="border-t pt-6 text-center">
                    <p class="mb-2 text-sm font-semibold text-gray-800">Terima kasih atas kepercayaan Anda!</p>
                    <p class="text-xs text-gray-500">
                        Harap simpan invoice ini sebagai bukti pembayaran.
                    </p>
                    <div v-if="order.notes || order.pesan" class="mt-4 rounded bg-yellow-50 p-2 text-xs text-yellow-800">
                        <strong>Catatan:</strong> {{ order.notes || order.pesan }}
                    </div>
                </div>
            </div>

            <!-- Footer Actions (Screen Only) -->
            <div class="flex items-center justify-end gap-4 bg-gray-50 px-8 py-4 print:hidden">
                <button
                    @click="$emit('close')"
                    class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-200"
                >
                    Tutup
                </button>
                <button
                    @click="printInvoice"
                    class="flex items-center gap-2 rounded-lg bg-pink-500 px-6 py-2 text-sm font-medium text-white shadow transition hover:bg-pink-600"
                >
                    <span class="material-icons text-sm">print</span>
                    Download / Cetak PDF
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

defineProps<{
    show: boolean;
    order: any;
}>();

defineEmits(['close']);


const formatCurrency = (value: number | undefined | null) => {
    if (value === undefined || value === null) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(value);
};

const formatDate = (dateString: string | Date | undefined) => {
    if (!dateString) return '-';
    // Handle specific date string formats if necessary, or just rely on Date ctor
    const d = new Date(dateString);
    if (isNaN(d.getTime())) return dateString; // fallback if invalid date
    return d.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

const printInvoice = () => {
    window.print();
};
</script>

<style scoped>
/* Print Styles */
@media print {
    /* Hide everything that is NOT the invoice content */
    :deep(body > *) {
        display: none !important;
    }
    
    /* Ensure only the invoice modal content is visible and takes up full page */
    .fixed, .absolute, .relative {
        position: static !important;
        transform: none !important;
        width: 100% !important;
        height: auto !important;
        overflow: visible !important;
        background: white !important;
        box-shadow: none !important;
    }

    #invoice-content {
        display: block !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
    }
    
    /* Hide the screen-only close buttons and footer */
    .print\:hidden {
        display: none !important;
    }
}
</style>
