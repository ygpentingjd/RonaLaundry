<template>
  <AdminPanel>
    <!-- Header -->
    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-semibold text-pink-700">Orders Management</h1>

      <div class="flex items-center gap-3">
        <!-- Search Bar -->
        <input v-model="searchQuery" type="text" placeholder="Cari order..." class="w-64 bg-white border border-pink-300 rounded-lg px-4 py-2 text-gray-700
             focus:outline-none focus:ring-2 focus:ring-pink-400 shadow-sm transition" />

        <!-- Tombol Clear -->
        <button @click="clearSearch" v-if="searchQuery" class="text-sm text-gray-500 hover:text-gray-700">
          Clear
        </button>

        <!-- Tombol Refresh -->
        <button @click="refreshOrders" :disabled="isRefreshing"
          class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg shadow transition disabled:opacity-50">
          <span class="material-icons align-middle mr-1 text-sm" :class="{ 'animate-spin': isRefreshing }">
            refresh
          </span>
          {{ isRefreshing ? 'Refreshing...' : 'Refresh' }}
        </button>
      </div>
    </div>

    <!-- Orders List -->
    <div class="space-y-6">
      <!-- iterate filteredOrders instead of orders -->
      <div v-for="order in filteredOrders" :key="order.id"
        class="bg-pink-50 rounded-2xl p-6 shadow-md transition duration-200">
        <!-- Header Card -->
        <div class="flex justify-between items-center">
          <div>
            <div class="flex items-center gap-3">
              <div class="bg-pink-400 text-white w-8 h-8 flex items-center justify-center rounded-full font-semibold">
                {{ String(order.id).padStart(2, '0') }}
              </div>
              <!-- Hanya tampilkan Order ID -->
              <h3 class="text-lg font-semibold text-gray-800">
                Order #{{ order.id }}
              </h3>
            </div>
            <div class="text-sm text-gray-500 mt-1 flex items-center gap-1">
              <span class="material-icons text-sm">schedule</span>
              {{ order.date }}
            </div>
          </div>

          <button @click="toggleDetail(order.id)"
            class="bg-pink-400 hover:bg-pink-500 text-white px-4 py-1 rounded-lg text-sm shadow transition">
            {{ expandedOrder === order.id ? 'Tutup ▲' : 'Lihat Detail ▼' }}
          </button>
        </div>

        <!-- Detail (Dropdown) -->
        <transition name="fade">
          <div v-if="expandedOrder === order.id" class="mt-5 text-sm text-gray-700 space-y-4">
            <!-- 🔹 Identitas Pemesan -->
            <div>
              <p class="font-semibold mb-2">Identitas Pemesan</p>
              <table class="w-full mb-2">
                <tbody>
                  <tr>
                    <td class="py-1">Order ID</td>
                    <td class="py-1 text-right text-gray-600">
                      #{{ order.id }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-1">Nama Pemesan</td>
                    <td class="py-1 text-right text-gray-600">
                      {{ order.customer }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-1">Alamat</td>
                    <td class="py-1 text-right text-gray-600">
                      {{ order.address }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- 🔹 Status Pembayaran -->
            <div>
              <p class="font-semibold mb-2">Status Pembayaran</p>
              <span class="px-3 py-1 rounded-full text-sm font-medium" :class="{
                'bg-yellow-100 text-yellow-700': order.paymentStatus === 'Pending',
                'bg-green-100 text-green-700': order.paymentStatus === 'Verified',
                'bg-red-100 text-red-700': order.paymentStatus === 'Rejected'
              }">
                {{ order.paymentStatus }}
              </span>
            </div>

            <!-- 🔹 Status Pesanan -->
            <div>
              <p class="font-semibold mb-2 mt-4">Status Pesanan</p>
              <div class="relative inline-block text-left">
                <button @click="toggleDropdown(order.id)"
                  class="px-3 py-1 rounded-full text-sm font-medium flex items-center justify-between w-52"
                  :class="statusClass(order.orderStatus)">
                  {{ order.orderStatus }}
                  <span class="material-icons text-xs ml-1">arrow_drop_down</span>
                </button>

                <transition name="fade">
                  <div v-if="openDropdown === order.id"
                    class="absolute mt-1 w-52 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                    <ul class="py-1 text-sm text-gray-700">
                      <li v-for="status in statusOptions" :key="status" @click="selectStatus(order, status)"
                        class="cursor-pointer px-3 py-2 flex items-center rounded-lg hover:bg-gray-100">
                        <span class="w-3 h-3 rounded-full mr-2" :class="statusDotClass(status)"></span>
                        <span :class="statusTextClass(status)">{{ status }}</span>
                      </li>
                    </ul>
                  </div>
                </transition>
              </div>
            </div>

            <!-- 🔹 Detail Order -->
            <div>
              <p class="font-semibold mb-2 mt-4">Detail Order</p>
              <table class="w-full mb-4">
                <tbody>
                  <tr>
                    <td class="py-1">Layanan</td>
                    <td class="py-1 text-right">{{ order.service }}</td>
                  </tr>
                  <tr>
                    <td class="py-1">Tanggal Masuk</td>
                    <td class="py-1 text-right">{{ order.orderDate }}</td>
                  </tr>
                  <tr>
                    <td class="py-1">Tanggal Pengembalian</td>
                    <td class="py-1 text-right">
                      {{ order.returnDate ? order.returnDate : '—' }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-1">Metode Pengantaran</td>
                    <td class="py-1 text-right">{{ order.deliveryMethod }}</td>
                  </tr>
                  <tr>
                    <td class="py-1">Harga per Kg</td>
                    <td class="py-1 text-right">
                      Rp {{ order.pricePerKg.toLocaleString() }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-1">Berat Laundry</td>
                    <td class="py-1 text-right">
                      <input v-model.number="order.weight" type="number" min="0" step="0.1" placeholder="(kg)"
                        class="w-24 border rounded-lg px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300 text-right" />
                    </td>
                  </tr>
                  <tr>
                    <td class="pt-3 font-semibold">Total</td>
                    <td class="pt-3 text-right font-bold text-gray-900">
                      Rp
                      {{

                        order.weight
                          ? (order.weight * order.pricePerKg).toLocaleString()
                          : '—'
                      }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- 🔹 Tombol Aksi -->
            <div class="flex justify-end gap-3 mt-4">
              <button class="text-blue-500 hover:underline" @click="updateOrder(order)">
                Simpan
              </button>
              <button class="text-red-500 hover:underline" @click="deleteOrder(order.id)">
                Hapus
              </button>
            </div>

            <div class="text-xs text-gray-500 mt-4 flex items-center gap-1">
              <span class="material-icons text-sm">schedule</span>
              {{ order.date }}
            </div>
          </div>
        </transition>
      </div>

      <!-- Jika tidak ada hasil -->
      <div v-if="filteredOrders.length === 0" class="text-center py-8 text-gray-500">
        Tidak ada order yang cocok dengan pencarian.
      </div>
    </div>
  </AdminPanel>
</template>

<script setup lang="ts">
import AdminPanel from '../AdminPanel.vue'
import { ref, computed } from 'vue'

const expandedOrder = ref(null)
const openDropdown = ref(null)
const searchQuery = ref('') 

const orders = ref([
  {
    id: 1,
    customer: 'Siti Aisyah',
    service: 'Cuci Kiloan Reguler',
    address: 'Jl. Merpati No. 21, Bandung',
    pickupDate: 'Selasa, 23 Desember 2025',
    pricePerKg: 7000,
    weight: 0,
    paymentStatus: 'Pending',
    orderStatus: 'Menunggu Penjemputan',
    date: '19 Desember 2025 | 11:00 WIB',
    orderDate: '18 Desember 2025',
    returnDate: '24 Desember 2025',
    deliveryMethod: 'Diantar'
  },
  {
    id: 2,
    customer: 'Rizky Putra',
    service: 'Cuci Express',
    address: 'Jl. Cendrawasih No. 10, Cimahi',
    pickupDate: 'Rabu, 24 Desember 2025',
    pricePerKg: 9000,
    weight: 0,
    paymentStatus: 'Verified',
    orderStatus: 'Sedang Diproses',
    date: '20 Desember 2025 | 09:30 WIB',
    orderDate: '19 Desember 2025',
    returnDate: '25 Desember 2025',
    deliveryMethod: 'Ambil Sendiri'
  }
])

const statusOptions = [
  'Menunggu Penjemputan',
  'Sedang Diproses',
  'Selesai Diproses',
  'Siap Diambil',
  'Siap Diantar',
  'Batal'
]

// ----------------- SEARCH LOGIC -----------------
// computed filteredOrders: mencari di banyak field (case-insensitive)
const filteredOrders = computed(() => {
  const q = searchQuery.value.trim().toLowerCase()
  if (!q) return orders.value

  return orders.value.filter(order => {
    // gabungkan beberapa field sebagai sumber pencarian
    const fields = [
      String(order.id),
      order.customer || '',
      order.address || '',
      order.service || '',
      order.orderStatus || '',
      order.paymentStatus || '',
      order.deliveryMethod || '',
      order.orderDate || '',
      order.returnDate || '',
      order.date || '',
      String(order.pricePerKg || ''),
      String(order.weight || '')
    ].map(f => String(f).toLowerCase())

    // jika salah satu field mengandung query -> cocok
    return fields.some(f => f.includes(q))
  })
})
// ------------------------------------------------

const toggleDetail = (id: any) => {
  expandedOrder.value = expandedOrder.value === id ? null : id
}

const toggleDropdown = (id: any) => {
  openDropdown.value = openDropdown.value === id ? null : id
}

const selectStatus = (order: any, status: string) => {
  order.orderStatus = status
  openDropdown.value = null
}

const updateOrder = (order: any) => {
  if (!order.weight || order.weight <= 0) {
    alert('Masukkan berat laundry terlebih dahulu!')
    return
  }
  alert(
    `✅ Order ${order.customer} diperbarui!\nBerat: ${order.weight} kg\nStatus Pesanan: ${order.orderStatus}\nStatus Pembayaran: ${order.paymentStatus}\nTotal: Rp ${(order.weight * order.pricePerKg).toLocaleString()}`
  )
}

const deleteOrder = (id: any) => {
  if (confirm('Yakin ingin menghapus order ini?')) {
    orders.value = orders.value.filter((o) => o.id !== id)
  }
}

const isRefreshing = ref(false)

const refreshOrders = async () => {
  isRefreshing.value = true
  await new Promise((r) => setTimeout(r, 1000)) // simulasi loading 1 detik

  // Reset atau ambil ulang data orders
  orders.value = [
    {
      id: 1,
      customer: 'Siti Aisyah',
      service: 'Cuci Kiloan Reguler',
      address: 'Jl. Merpati No. 21, Bandung',
      pickupDate: 'Selasa, 23 Desember 2025',
      pricePerKg: 7000,
      weight: 0,
      paymentStatus: 'Pending',
      orderStatus: 'Menunggu Penjemputan',
      date: '19 Desember 2025 | 11:00 WIB',
      orderDate: '18 Desember 2025',
      returnDate: '24 Desember 2025',
      deliveryMethod: 'Diantar'
    },
    {
      id: 2,
      customer: 'Rizky Putra',
      service: 'Cuci Express',
      address: 'Jl. Cendrawasih No. 10, Cimahi',
      pickupDate: 'Rabu, 24 Desember 2025',
      pricePerKg: 9000,
      weight: 0,
      paymentStatus: 'Verified',
      orderStatus: 'Sedang Diproses',
      date: '20 Desember 2025 | 09:30 WIB',
      orderDate: '19 Desember 2025',
      returnDate: '25 Desember 2025',
      deliveryMethod: 'Ambil Sendiri'
    }
  ]

  alert('✅ Data order berhasil diperbarui!')
  isRefreshing.value = false
}

const statusClass = (status: string) => {
  switch (status) {
    case 'Menunggu Penjemputan':
      return 'bg-yellow-100 text-yellow-700 border border-yellow-300'
    case 'Sedang Diproses':
      return 'bg-blue-100 text-blue-700 border border-blue-300'
    case 'Selesai Diproses':
      return 'bg-pink-100 text-pink-700 border border-pink-300'
    case 'Siap Diambil':
    case 'Siap Diantar':
      return 'bg-green-100 text-green-700 border border-green-300'
    case 'Batal':
      return 'bg-red-100 text-red-700 border border-red-300'
    default:
      return 'bg-gray-100 text-gray-700 border border-gray-300'
  }
}

const statusDotClass = (status: string) => {
  switch (status) {
    case 'Menunggu Penjemputan':
      return 'bg-yellow-400'
    case 'Sedang Diproses':
      return 'bg-blue-400'
    case 'Selesai Diproses':
      return 'bg-pink-400'
    case 'Siap Diambil':
    case 'Siap Diantar':
      return 'bg-green-400'
    case 'Batal':
      return 'bg-red-400'
    default:
      return 'bg-gray-400'
  }
}

const statusTextClass = (status: string) => {
  switch (status) {
    case 'Menunggu Penjemputan':
      return 'text-yellow-700'
    case 'Sedang Diproses':
      return 'text-blue-700'
    case 'Selesai Diproses':
      return 'text-pink-700'
    case 'Siap Diambil':
    case 'Siap Diantar':
      return 'text-green-700'
    case 'Batal':
      return 'text-red-700'
    default:
      return 'text-gray-700'
  }
}

const fmt = (isoOrString: unknown) => {
  if (typeof isoOrString !== 'string' && !(isoOrString instanceof Date)) {
    return isoOrString
  }

  try {
    const d = new Date(isoOrString)
    return isNaN(d.getTime())
      ? isoOrString
      : d.toLocaleDateString('id-ID', {
          day: '2-digit',
          month: 'long',
          year: 'numeric'
        })
  } catch {
    return isoOrString
  }
}

const clearSearch = () => {
  searchQuery.value = ''
}
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