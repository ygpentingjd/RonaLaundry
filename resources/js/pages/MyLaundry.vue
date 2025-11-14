<template>
  <Head title="My Laundry - RonaLaundry" />
  <UserLayout>
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-2xl font-bold text-gray-800">My Laundry</h2>
    </div>

    <div class="space-y-6">
      <div
        v-for="order in orders"
        :key="order.id"
        class="bg-pink-50 border border-pink-100 rounded-2xl p-6 shadow-md transition duration-200 hover:shadow-lg"
      >
        <!-- Header Card -->
        <div class="flex justify-between items-center">
          <div>
            <div class="flex items-center gap-3">
              <div
                class="bg-pink-400 text-white w-8 h-8 flex items-center justify-center rounded-full font-semibold"
              >
                <span class="material-icons text-sm">local_laundry_service</span>
              </div>
              <h3 class="text-lg font-semibold text-gray-800">
                {{ order.layanan }}
              </h3>
            </div>
            <div class="text-sm text-gray-500 mt-1 flex items-center gap-1">
              <span class="material-icons text-sm">schedule</span>
              {{ formatFullDate(order.tanggal) }}
            </div>
          </div>

          <button
            @click="toggleDetail(order.id)"
            class="bg-pink-400 hover:bg-pink-500 text-white px-4 py-1 rounded-lg text-sm shadow transition"
          >
            {{ expandedOrder === order.id ? 'Tutup ▲' : 'Lihat Detail ▼' }}
          </button>
        </div>

        <!-- Detail -->
        <transition name="fade">
          <div
            v-if="expandedOrder === order.id"
            class="mt-5 text-sm text-gray-700 space-y-4"
          >
            <!-- Identitas Pemesan -->
            <div>
              <p class="font-semibold mb-2">Identitas Pemesan</p>
              <table class="w-full mb-2">
                <tbody>
                  <tr>
                    <td class="py-1">Nama Pemesan</td>
                    <td class="py-1 text-right text-gray-600">{{ order.nama }}</td>
                  </tr>
                  <tr>
                    <td class="py-1">Alamat</td>
                    <td class="py-1 text-right text-gray-600">{{ order.alamat }}</td>
                  </tr>
                  <tr>
                    <td class="py-1">Jadwal Pengambilan</td>
                    <td class="py-1 text-right text-gray-600">{{ formatFullDate(order.tanggal) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Status Pembayaran -->
            <div>
              <p class="font-semibold mb-2">Status Pembayaran</p>
              <span
                class="px-3 py-1 rounded-full text-sm font-medium"
                :class="{
                  'bg-yellow-100 text-yellow-700': order.payment_status === 'Pending',
                  'bg-green-100 text-green-700': order.payment_status === 'Verified',
                  'bg-red-100 text-red-700': order.payment_status === 'Rejected'
                }"
              >
                {{ order.payment_status }}
              </span>
            </div>

            <!-- Status Pesanan -->
            <div>
              <p class="font-semibold mb-2 mt-4">Status Pesanan</p>
              <span
                class="px-3 py-1 rounded-full text-sm font-medium border"
                :class="statusClass(order.status)"
              >
                {{ order.status }}
              </span>
            </div>

            <!-- Detail Order -->
            <div>
              <p class="font-semibold mb-2 mt-4">Detail Order</p>
              <table class="w-full mb-4">
                <tbody>
                  <tr>
                    <td class="py-1">Jenis Barang</td>
                    <td class="py-1 text-right">{{ order.barang.join(', ') }}</td>
                  </tr>
                  <tr>
                    <td class="py-1">Harga per Kg</td>
                    <td class="py-1 text-right">
                      Rp {{ order.harga_per_kg ? order.harga_per_kg.toLocaleString() : '-' }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-1">Berat Laundry</td>
                    <td class="py-1 text-right text-gray-600 italic">
                      {{ order.berat ? order.berat + ' kg' : 'Menunggu input admin' }}
                    </td>
                  </tr>
                  <tr>
                    <td class="pt-3 font-semibold">Total</td>
                    <td class="pt-3 text-right font-bold text-gray-900">
                      Rp {{
                        order.total
                          ? order.total.toLocaleString()
                          : order.berat && order.harga_per_kg
                            ? (order.berat * order.harga_per_kg).toLocaleString()
                            : '—'
                      }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-1">Metode Pembayaran</td>
                    <td class="py-1 text-right">{{ order.pembayaran }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end mt-4">
              <button
                class="text-red-500 hover:non"
                @click="cancelOrder(order.id)"
              >
                Batal Pesan
              </button>
            </div>

            <!-- Waktu Update -->
            <div class="text-xs text-gray-500 mt-4 flex items-center gap-1">
              <span class="material-icons text-sm">update</span>
              {{ formatFullDate(order.updated_at) }}
            </div>
          </div>
        </transition>
      </div>

      <p v-if="orders.length === 0" class="text-gray-500 text-center mt-10">
        Tidak ada pesanan aktif saat ini!
      </p>
    </div>
  </UserLayout>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import UserLayout from '@/layouts/UserLayout.vue'
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const { props } = usePage<AppPageProps<{ reservasis: Order[] }>>()

/* --- deklarasi ref dengan tipe eksplisit --- */
const orders = ref<Order[]>(props.reservasis || [])
const expandedOrder = ref<number | null>(null)

/* --- fungsi dengan parameter bertipe --- */
const toggleDetail = (id: number) => {
  expandedOrder.value = expandedOrder.value === id ? null : id
}

const cancelOrder = (id: number) => {
  if (confirm('Yakin ingin membatalkan pesanan ini?')) {
    router.post(`/reservasi/${id}/cancel`, {}, {
      onSuccess: () => {
        // Hapus pesanan dari tampilan secara lokal
        orders.value = orders.value.filter(order => order.id !== id)
        
        // Opsional: tampilkan notifikasi
        alert('Pesanan berhasil dibatalkan!')
      },
      onError: () => {
        alert('Gagal membatalkan pesanan. Silakan coba lagi.')
      }
    })
  }
}

/* status bisa undefined/null => beri tipe union */
const statusClass = (status?: string | null): string => {
  if (!status) return 'bg-gray-100 text-gray-700 border-gray-300'
  const s = status.toLowerCase()
  if (s.includes('menunggu')) return 'bg-yellow-100 text-yellow-700 border-yellow-300'
  if (s.includes('diproses')) return 'bg-blue-100 text-blue-700 border-blue-300'
  if (s.includes('selesai')) return 'bg-pink-100 text-pink-700 border-pink-300'
  if (s.includes('siap')) return 'bg-green-100 text-green-700 border-green-300'
  if (s.includes('batal')) return 'bg-red-100 text-red-700 border-red-300'
  return 'bg-gray-100 text-gray-700 border-gray-300'
}

/* formatFullDate: parameter bertipe string | null | undefined */
const formatFullDate = (dateStr?: string | null): string => {
  if (!dateStr) return '-'
  const date = new Date(dateStr)

  const tanggal = date.toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  })

  const waktu = date.toLocaleTimeString('id-ID', {
    hour: '2-digit',
    minute: '2-digit',
    hour12: false
  }).replace(':', '.')

  return `${tanggal} | ${waktu} WIB`
}
</script>