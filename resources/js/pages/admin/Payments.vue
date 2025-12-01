kok tampilannya jadi bedaaa sama yang sebelumnya <template>
  <AdminPanel>
    <!-- 🔹 Header -->
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-semibold text-pink-700">Payment Verification</h1>

      <!-- 🔹 Filter dan Refresh -->
      <div class="flex items-center gap-3">
        <!-- Filter Status -->
        <select
          v-model="filterStatus"
          class="border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring-2 focus:ring-pink-400 focus:outline-none"
        >
          <option value="All">All</option>
          <option value="Pending">Pending</option>
          <option value="Verified">Verified</option>
          <option value="Rejected">Rejected</option>
        </select>

        <!-- Tombol Refresh dengan animasi -->
        <button
          @click="refreshData"
          :disabled="isRefreshing"
          class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg shadow transition disabled:opacity-50"
        >
          <span
            class="material-icons align-middle mr-1 text-sm"
            :class="{ 'animate-spin': isRefreshing }"
          >
            refresh
          </span>
          {{ isRefreshing ? 'Refreshing...' : 'Refresh' }}
        </button>
      </div>
    </div>

    <!-- 🔹 Tabel Payment -->
    <div class="bg-white shadow-md rounded-xl p-6">
      <table class="w-full text-left text-gray-700 border-collapse">
        <thead class="bg-pink-100">
          <tr>
            <th class="py-3 px-4 rounded-tl-lg">Order ID</th>
            <th class="py-3 px-4">User Name</th>
            <th class="py-3 px-4">Payment Method</th>
            <th class="py-3 px-4">Total Amount</th>
            <th class="py-3 px-4">Status</th>
            <th class="py-3 px-4 text-center rounded-tr-lg">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="payment in filteredPayments"
            :key="payment.id"
            class="border-b hover:bg-pink-50 transition"
          >
            <td class="py-3 px-4 font-semibold">#{{ payment.orderId }}</td>
            <td class="py-3 px-4">{{ payment.userName }}</td>
            <td class="py-3 px-4">{{ payment.method }}</td>
            <td class="py-3 px-4">Rp {{ payment.total.toLocaleString() }}</td>
            <td class="py-3 px-4">
              <span
                :class="{
                  'text-yellow-600 font-semibold': payment.status === 'Pending',
                  'text-green-600 font-semibold': payment.status === 'Verified',
                  'text-red-500 font-semibold': payment.status === 'Rejected',
                }"
              >
                {{ payment.status }}
              </span>
            </td>
            <td class="py-3 px-4 text-center">
              <button
                class="text-blue-500 hover:underline mr-2"
                @click="viewDetails(payment)"
              >
                View
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Jika tidak ada data -->
      <p v-if="filteredPayments.length === 0" class="text-center text-gray-500 py-4">
        No payment records found.
      </p>
    </div>

    <!-- 🔹 Modal Detail Pembayaran -->
    <div
      v-if="selectedPayment"
      class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50"
    >
      <div class="relative w-[420px] bg-[#ffe4ec] rounded-2xl shadow-2xl p-6 animate-fadeIn">
        <!-- Tombol Tutup -->
        <button
          @click="closeModal"
          class="absolute top-3 right-3 text-pink-600 hover:text-pink-800 text-lg font-bold transition"
        >
          ✕
        </button>

        <h3 class="text-xl font-bold text-center text-pink-700 mb-5">
          Payment Details
        </h3>

        <!-- Detail Konten -->
        <div class="space-y-2 text-gray-700">
          <p><span class="font-semibold text-pink-600">Order ID:</span> {{ selectedPayment.orderId }}</p>
          <p><span class="font-semibold text-pink-600">User:</span> {{ selectedPayment.userName }}</p>
          <p><span class="font-semibold text-pink-600">Payment Method:</span> {{ selectedPayment.method }}</p>
          <p><span class="font-semibold text-pink-600">Total:</span> Rp {{ selectedPayment.total.toLocaleString() }}</p>
          <p><span class="font-semibold text-pink-600">Status:</span> {{ selectedPayment.status }}</p>
          <p><span class="font-semibold text-pink-600">Date:</span> {{ selectedPayment.date }}</p>
        </div>

        <!-- Bukti Pembayaran -->
        <div class="mt-4">
          <span class="font-semibold text-pink-600 block mb-2">Payment Proof:</span>
          <div
            class="border border-pink-300 bg-white rounded-xl p-2 shadow-sm hover:shadow-md transition"
          >
            <img
              :src="selectedPayment.proof"
              alt="Payment Proof"
              class="w-full h-48 object-cover rounded-md"
            />
          </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-center gap-4 mt-6">
          <button
            @click="verifyPayment(selectedPayment.id)"
            class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg shadow-md transition"
          >
            Verify
          </button>
          <button
            @click="rejectPayment(selectedPayment.id)"
            class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg shadow-md transition"
          >
            Reject
          </button>
        </div>
      </div>
    </div>
  </AdminPanel>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import AdminPanel from "../AdminPanel.vue";
import axios from "axios";

const payments = ref([]);
const filterStatus = ref("All");
const selectedPayment = ref(null);
const isRefreshing = ref(false);

// 🔹 Ambil data dari Laravel
const loadPayments = async () => {
  const res = await axios.get("/admin/payments/data");
  payments.value = res.data;
};

// 🔹 Load saat halaman dibuka
onMounted(() => {
  loadPayments();
});

// 🔹 Filter
const filteredPayments = computed(() => {
  if (filterStatus.value === "All") return payments.value;
  return payments.value.filter((p) => p.status === filterStatus.value);
});

// 🔹 Actions
const viewDetails = (payment) => {
  selectedPayment.value = payment;
};

const verifyPayment = async (id) => {
  await axios.post(`/admin/payment/${id}/verify`);
  await loadPayments();
  selectedPayment.value = null;
};

const rejectPayment = async (id) => {
  await axios.post(`/admin/payment/${id}/reject`);
  await loadPayments();
  selectedPayment.value = null;
};

const closeModal = () => {
  selectedPayment.value = null;
};

// 🔹 Refresh
const refreshData = async () => {
  isRefreshing.value = true;
  await loadPayments();
  setTimeout(() => (isRefreshing.value = false), 600);
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

@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap");
</style> 