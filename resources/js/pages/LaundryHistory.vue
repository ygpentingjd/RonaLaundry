<template>
  <Head title="Laundry History - RonaLaundry" />
    <UserLayout>
      <!-- Header -->
      <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Riwayat Laundry</h2>
      </div>
  
      <!-- History List -->
      <div class="space-y-6">
        <div
          v-for="order in completedOrders"
          :key="order.id"
          class="bg-white border border-pink-100 rounded-2xl p-6 shadow-md transition duration-200 hover:shadow-lg"
        >
          <!-- Header Card -->
          <div class="flex justify-between items-center">
            <div>
              <div class="flex items-center gap-3">
                <div
                  class="bg-pink-400 text-white w-8 h-8 flex items-center justify-center rounded-full font-semibold"
                >
                  <span class="material-icons text-sm">done_all</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">
                  {{ order.service }}
                </h3>
              </div>
              <div class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                <span class="material-icons text-sm">schedule</span>
                {{ order.date }}
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
              <!-- 🔹 Identitas Pemesan -->
              <div>
                <p class="font-semibold mb-2">Identitas Pemesan</p>
                <table class="w-full mb-2">
                  <tbody>
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
                    <tr>
                      <td class="py-1">Jadwal Pengambilan</td>
                      <td class="py-1 text-right text-gray-600">
                        {{ order.pickupDate }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
  
              <!-- 🔹 Status Pembayaran -->
              <div>
                <p class="font-semibold mb-2">Status Pembayaran</p>
                <span
                  class="px-3 py-1 rounded-full text-sm font-medium"
                  :class="{
                    'bg-green-100 text-green-700': order.paymentStatus === 'Lunas',
                    'bg-yellow-100 text-yellow-700': order.paymentStatus === 'Pending',
                  }"
                >
                  {{ order.paymentStatus }}
                </span>
              </div>
  
              <!-- 🔹 Status Pesanan -->
              <div>
                <p class="font-semibold mb-2 mt-4">Status Pesanan</p>
                <span
                  class="px-3 py-1 rounded-full text-sm font-medium border"
                  :class="statusClass(order.status)"
                >
                  {{ order.status }}
                </span>
              </div>
  
              <!-- 🔹 Detail Order -->
              <div>
                <p class="font-semibold mb-2 mt-4">Detail Order</p>
                <table class="w-full mb-4">
                  <tbody>
                    <tr>
                      <td class="py-1">Jenis Layanan</td>
                      <td class="py-1 text-right">{{ order.service }}</td>
                    </tr>
                    <tr>
                      <td class="py-1">Harga per Kg</td>
                      <td class="py-1 text-right">
                        Rp {{ order.pricePerKg.toLocaleString() }}
                      </td>
                    </tr>
                    <tr>
                      <td class="py-1">Berat Laundry</td>
                      <td class="py-1 text-right text-gray-700">
                        {{ order.weight }} kg
                      </td>
                    </tr>
                    <tr>
                      <td class="pt-3 font-semibold">Total</td>
                      <td class="pt-3 text-right font-bold text-gray-900">
                        Rp {{ (order.weight * order.pricePerKg).toLocaleString() }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
  
              <!-- 🔹 Keterangan Tambahan -->
              <div v-if="order.notes" class="mt-3">
                <p class="font-semibold mb-1">Catatan:</p>
                <p class="text-gray-600 italic">{{ order.notes }}</p>
              </div>
  
              <!-- 🔹 Info Tanggal -->
              <div class="text-xs text-gray-500 mt-4 flex items-center gap-1">
                <span class="material-icons text-sm">event_available</span>
                {{ order.date }}
              </div>
            </div>
          </transition>
        </div>
  
        <!-- Jika tidak ada riwayat -->
        <p
          v-if="completedOrders.length === 0"
          class="text-gray-500 text-center mt-10"
        >
          Belum ada riwayat laundry selesai 😌
        </p>
      </div>
    </UserLayout>
  </template>
  
  <script setup lang="ts">
  import { Head } from '@inertiajs/vue3'
  import UserLayout from '@/layouts/UserLayout.vue'
  import { ref, computed } from 'vue'
  
  const expandedOrder = ref(null)
  
  // 🔹 Dummy data hanya untuk pesanan selesai
  const orders = ref([
    {
      id: 1,
      customer: 'Siti Aisyah',
      service: 'Cuci Kiloan Reguler',
      address: 'Jl. Merpati No. 21, Bandung',
      pickupDate: 'Selasa, 23 Desember 2025',
      pricePerKg: 7000,
      weight: 5,
      paymentStatus: 'Lunas',
      status: 'Selesai Diproses',
      notes: 'Sudah disetrika dan dikemas rapi.',
      date: '19 Desember 2025 | 11:00 WIB'
    },
    {
      id: 2,
      customer: 'Rizky Putra',
      service: 'Cuci Express',
      address: 'Jl. Cendrawasih No. 10, Cimahi',
      pickupDate: 'Rabu, 24 Desember 2025',
      pricePerKg: 9000,
      weight: 3,
      paymentStatus: 'Lunas',
      status: 'Siap Diambil',
      notes: 'Dijemput oleh kurir pada pukul 10:00 WIB.',
      date: '21 Desember 2025 | 10:30 WIB'
    }
  ])
  
  // 🔹 Filter hanya yang selesai
  const completedOrders = computed(() =>
    orders.value.filter(o =>
      ['Selesai Diproses', 'Siap Diambil', 'Siap Diantar'].includes(o.status)
    )
  )
  
  // 🔹 Toggle detail
  const toggleDetail = (id) => {
    expandedOrder.value = expandedOrder.value === id ? null : id
  }
  
  // 🔹 Warna status
  const statusClass = (status) => {
    switch (status) {
      case 'Selesai Diproses':
        return 'bg-green-100 text-green-700 border-green-300'
      case 'Siap Diambil':
      case 'Siap Diantar':
        return 'bg-blue-100 text-blue-700 border-blue-300'
      default:
        return 'bg-gray-100 text-gray-700 border-gray-300'
    }
  }
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