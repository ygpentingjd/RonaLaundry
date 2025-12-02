<template>
  <Head title="Profile User - RonaLaundry" />
  <UserLayout>
    <div class="flex">
      <div class="flex-1 bg-pink-50 min-h-screen font-poppins">
        <div class="w-full bg-white shadow-xl rounded-3xl overflow-hidden border border-pink-200 mx-auto">

          <!-- 🔹 Header Profil -->
          <div class="text-slate-600 w-full flex items-center p-8 gap-6">
            <div class="h-24 w-24 bg-white rounded-full flex items-center justify-center shadow-lg">
              <span class="material-icons text-pink-500 text-7xl">person</span>
            </div>
            <div class="flex flex-col justify-center text-left">
              <h2 class="mt-0 text-2xl font-bold">{{ form.username }}</h2>
              <p class="text-slate-600/80 text-sm">{{ form.email }}</p>
            </div>
          </div>

          <!-- 🔹 Statistik -->
          <div class="grid grid-cols-3 text-center py-6 bg-pink-100 border-t border-b border-pink-200">
            <div>
              <p class="text-sm text-gray-600">Total Transaksi</p>
              <p class="text-xl font-bold text-pink-600">{{ stats.total_transaksi }}x</p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Status</p>
              <p class="text-xl font-bold text-pink-600">{{ stats.status_pelanggan }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Favorit</p>
              <p class="text-xl font-bold text-pink-600">{{ stats.layanan_favorit }}</p>
            </div>
          </div>

          <!-- 🔹 Data Diri & Alamat -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8 bg-pink-50/30">
            <!-- Data Diri -->
            <div>
              <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Data Diri</h3>
              <div class="space-y-3 text-gray-700">

                <div class="bg-white border border-pink-100 rounded-xl p-3">
                  <p class="text-sm text-gray-500">Nama Lengkap</p>
                  <template v-if="editMode">
                    <input
                      v-model="form.nama_lengkap"
                      type="text"
                      class="w-full border rounded-lg px-3 py-1 focus:ring-2 focus:ring-pink-300 outline-none"
                      placeholder="Masukkan nama lengkap"
                    />
                  </template>
                  <template v-else>
                    <p class="font-semibold text-black-400">{{ form.nama_lengkap || 'Belum diisi' }}</p>
                  </template>
                </div>

                <div class="bg-white border border-pink-100 rounded-xl p-3">
                  <p class="text-sm text-gray-500">Nomor Telepon</p>
                  <template v-if="editMode">
                    <input
                      v-model="form.nomor_telepon"
                      type="text"
                      class="w-full border rounded-lg px-3 py-1 focus:ring-2 focus:ring-pink-300 outline-none"
                      placeholder="Masukkan nomor telepon"
                    />
                  </template>
                  <template v-else>
                    <p class="font-semibold text-black-400">{{ form.nomor_telepon || 'Belum diisi' }}</p>
                  </template>
                </div>

    
              </div>
            </div>

            <!-- Alamat -->
            <div>
              <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Alamat</h3>
              <div class="space-y-3 text-gray-700">

                <div class="bg-white border border-pink-100 rounded-xl p-3">
                  <p class="text-sm text-gray-500">Alamat Lengkap</p>
                  <template v-if="editMode">
                    <input
                      v-model="form.alamat_lengkap"
                      type="text"
                      class="w-full border rounded-lg px-3 py-1 focus:ring-2 focus:ring-pink-300 outline-none"
                      placeholder="Masukkan alamat lengkap"
                    />
                  </template>
                  <template v-else>
                    <p class="font-semibold text-black-400">{{ form.alamat_lengkap || 'Belum diisi' }}</p>
                  </template>
                </div>

                <div class="bg-white border border-pink-100 rounded-xl p-3">
                  <p class="text-sm text-gray-500">Catatan Lokasi</p>
                  <template v-if="editMode">
                    <input
                      v-model="form.catatan_lokasi"
                      type="text"
                      class="w-full border rounded-lg px-3 py-1 focus:ring-2 focus:ring-pink-300 outline-none"
                      placeholder="Masukkan catatan lokasi"
                    />
                  </template>
                  <template v-else>
                    <p class="font-semibold text-black-400">{{ form.catatan_lokasi || 'Belum diisi' }}</p>
                  </template>
                </div>

              </div>
            </div>
          </div>

          <!-- 🔹 Riwayat -->
          <div class="p-8 bg-white border-t border-pink-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Riwayat Terakhir</h3>
            <p v-if="lastOrder" class="text-gray-700">
              Laundry {{ lastOrder.berat ? lastOrder.berat + 'kg' : '-' }} -
              <span class="text-pink-600 font-semibold">{{ lastOrder.layanan }}</span>
              (dibuat <b>{{ lastOrder.time_ago }}</b>)
            </p>
            <p v-else class="text-gray-500 italic">Belum ada riwayat transaksi.</p>
          </div>

          <!-- 🔹 Tombol -->
          <div class="p-6 bg-pink-50 flex justify-center gap-4">
            <button
              @click="toggleEdit"
              class="bg-gradient-to-r from-pink-500 to-pink-600 text-white font-semibold px-6 py-2 rounded-full shadow hover:scale-105 transition duration-300"
            >
              {{ editMode ? 'Simpan' : 'Edit Profil' }}
            </button>

            <button
              v-if="!editMode"
              class="bg-white border border-pink-400 text-pink-600 font-semibold px-6 py-2 rounded-full shadow hover:scale-105 hover:shadow-lg hover:bg-pink-50 transition duration-300"
            >
              Reservasi Lagi!
            </button>
          </div>
        </div>
      </div>
    </div>
  </UserLayout>
</template>

<script setup lang="ts">
import UserLayout from "@/layouts/UserLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps<{
  user: any;
  stats: {
    total_transaksi: number;
    status_pelanggan: string;
    layanan_favorit: string;
  };
  lastOrder: {
    layanan: string;
    berat: number;
    time_ago: string;
  } | null;
}>();

const user = props.user || {
  username: "Pengguna",
  email: "email@contoh.com",
};

const editMode = ref(false);
const form = ref({
  username: user.username,
  email: user.email,
  nama_lengkap: user.nama_lengkap || "",
  nomor_telepon: user.nomor_telepon || "",
  alamat_lengkap: user.alamat_lengkap || "",
  catatan_lokasi: user.catatan_lokasi || "",
});

function toggleEdit() {
  if (editMode.value) {
    // Simpan data ke backend
    router.post("/profile/update", form.value, {
      onSuccess: () => {
        editMode.value = false;
      },
      onError: (errors) => {
        console.error(errors);
      },
    });
  } else {
    editMode.value = true;
  }
}
</script>

<style scoped>
.material-icons {
  font-size: 72px;
}
</style>