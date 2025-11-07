<template>
  <AdminPanel>
    <!-- 🔹 Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
      <h1 class="text-3xl font-semibold text-pink-700">Daftar Produk Laundry</h1>
      <button @click="openForm"
        class="bg-pink-600 text-white px-5 py-2 rounded-lg hover:bg-pink-700 transition shadow-md">
        + Tambah Layanan Laundry
      </button>
    </div>

    <!-- 🔹 Daftar Produk -->
    <div v-for="(product, index) in products" :key="index"
      class="bg-white shadow-md rounded-2xl mb-4 overflow-hidden border border-gray-200">
      <div class="flex justify-between items-center p-4 cursor-pointer hover:bg-pink-50 transition"
        @click="toggleDetail(index)">
        <div>
          <h2 class="text-xl font-semibold text-gray-800">{{ product.name }}</h2>
          <p class="text-sm text-gray-500">Klik untuk lihat detail</p>
        </div>
        <button class="text-pink-600 font-semibold focus:outline-none">
          {{ activeIndex === index ? '▲ Tutup' : '▼ Detail' }}
        </button>
      </div>

      <transition name="fade">
        <div v-if="activeIndex === index" class="p-6 border-t border-gray-200 bg-pink-50">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
            <!-- Gambar -->
            <div class="flex justify-center md:justify-start">
              <img :src="`/images/${product.image}`" :alt="product.name" @error="handleImageError($event)"
                class="w-48 h-48 object-cover rounded-xl shadow-md border border-gray-200" />
            </div>

            <!-- Detail Produk -->
            <div class="space-y-3">
              <div>
                <p class="text-gray-600 font-medium">Nama Barang</p>
                <p class="text-gray-800">{{ product.name }}</p>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <p class="text-gray-600 font-medium">Estimasi Waktu</p>
                  <p class="text-gray-800">{{ product.estimate }}</p>
                </div>
                <div>
                  <p class="text-gray-600 font-medium">Satuan</p>
                  <p class="text-gray-800">{{ product.unit }}</p>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <p class="text-gray-600 font-medium">Harga Reguler</p>
                  <input type="number" v-model.number="product.price_regular"
                    class="border rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-pink-400 text-black" />
                </div>
                <div>
                  <p class="text-gray-600 font-medium">Harga Kilat (24 jam)</p>
                  <input type="number" v-model.number="product.price_express"
                    class="border rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-pink-400 text-black" />
                </div>
              </div>
            </div>
          </div>

          <!-- Deskripsi -->
          <div class="mt-6">
            <p class="text-gray-600 font-medium mb-2">Deskripsi / Syarat & Ketentuan</p>
            <textarea v-model="product.description" rows="3"
              class="border rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-pink-400 text-black"></textarea>
          </div>

          <div class="flex justify-end mt-4">
            <button @click="saveChanges(product)"
              class="bg-pink-600 text-white px-5 py-2 rounded-lg hover:bg-pink-700 transition">
              Simpan Perubahan
            </button>
          </div>
        </div>
      </transition>
    </div>

    <!-- 🩷 Modal Tambah Layanan -->
    <div v-if="showForm" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="relative w-[450px] bg-[#ffe4ec] rounded-2xl shadow-2xl p-6 animate-fadeIn">
        <!-- Tombol Tutup -->
        <button @click="closeForm"
          class="absolute top-3 right-3 text-pink-700 hover:text-pink-900 text-lg font-bold transition">
          ✕
        </button>

        <h2 class="text-xl font-bold text-center text-pink-700 mb-5">Tambah Layanan Laundry</h2>

        <div class="space-y-3 text-gray-700">
          <div>
            <label class="font-semibold text-pink-600">Nama Barang</label>
            <input v-model="newProduct.name" type="text"
              class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-400 outline-none bg-white text-black" />
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="font-semibold text-pink-600">Satuan</label>
              <input v-model="newProduct.unit" type="text"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-400 outline-none bg-white text-black" />
            </div>
            <div>
              <label class="font-semibold text-pink-600">Estimasi Waktu</label>
              <input v-model="newProduct.estimate" type="text"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-400 outline-none bg-white text-black" />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="font-semibold text-pink-600">Harga Reguler</label>
              <input v-model.number="newProduct.price_regular" type="number"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-400 outline-none bg-white text-black" />
            </div>
            <div>
              <label class="font-semibold text-pink-600">Harga Kilat (24 Jam)</label>
              <input v-model.number="newProduct.price_express" type="number"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-400 outline-none bg-white text-black" />
            </div>
          </div>

          <div>
            <label class="font-semibold text-pink-600">Deskripsi / Syarat & Ketentuan</label>
            <textarea v-model="newProduct.description" rows="3"
              class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-400 outline-none bg-white text-black"></textarea>
          </div>

          <div>
            <label class="font-semibold text-pink-600 block mb-2">Image</label>

            <div
              class="upload-dropzone relative rounded-lg border-2 border-dashed p-6 text-center cursor-pointer bg-white"
              @dragover.prevent @dragenter.prevent="dragOver = true" @dragleave.prevent="dragOver = false"
              @drop.prevent="handleFileDrop" @click="triggerFileInput" :class="{ 'upload-dropzone--over': dragOver }">
              <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="onFileChange" />

              <div v-if="!newProduct.imageFile && !newProduct.image">
                <p class="text-sm">
                  <span class="text-pink-500">Drag & Drop your files or&nbsp;</span>
                  <span class="font-semibold underline cursor-pointer text-[#8EC9FF]" @click="triggerFileInput">
                    Browse
                  </span>
                </p>
              </div>

              <div v-else class="flex items-center justify-center gap-3">
                <img v-if="previewUrl" :src="previewUrl" alt="preview"
                  class="w-20 h-20 object-cover rounded-md border" />
                <div class="text-left">
                  <div class="text-sm text-gray-700 font-medium">
                    {{ newProduct.imageFile ? newProduct.imageFile.name : newProduct.image }}
                  </div>
                  <button type="button" class="text-xs text-red-500 mt-1 underline" @click.stop="removeSelectedImage">
                    Remove
                  </button>
                </div>
              </div>
            </div>

            <p class="mt-2 text-xs text-gray-400">Supported: JPG, PNG. Max 2MB.</p>
          </div>
        </div>

        <div class="flex justify-center gap-4 mt-6">
          <button @click="saveNewProduct"
            class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg shadow-md transition">
            Simpan
          </button>
          <button @click="closeForm"
            class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg shadow-md transition">
            Batal
          </button>
        </div>
      </div>
    </div>
  </AdminPanel>
</template>

<script setup lang="ts">
import { ref } from "vue";
import AdminPanel from "../AdminPanel.vue";

const activeIndex = ref(null);
const fileInput = ref<HTMLInputElement | null>(null);
const dragOver = ref(false);
const previewUrl = ref<string | null>(null);

const showForm = ref(false);


const products = ref<Product[]>([
  {
    name: "Pakaian Biasa",
    unit: "Kg",
    estimate: "1-2 Hari",
    price_regular: 3000,
    price_express: 6000,
    description: "Cuci bersih, setrika rapi, dan wangi tahan lama.",
    image: "kiloan.png",
    imageFile: null,
  },
  {
    name: "Selimut",
    unit: "Satuan",
    estimate: "2 Hari",
    price_regular: 5000,
    price_express: 10000,
    description: "Selimut dicuci dengan air hangat dan pewangi lembut.",
    image: "selimut.png",
    imageFile: null,
  },

  // ✅ Tambahan item dummy yang kamu minta:
  {
    name: "Boneka",
    unit: "Satuan",
    estimate: "2 Hari",
    price_regular: 5000,
    price_express: 10000,
    description: "Boneka dicuci dengan tangan dan dikeringkan alami.",
    image: "boneka.png",
    imageFile: null,
  },
  {
    name: "Karpet",
    unit: "Meteran",
    estimate: "3 Hari",
    price_regular: 5000,
    price_express: 10000,
    description: "Karpet dibersihkan dengan vacuum dan cairan khusus.",
    image: "karpet.png",
    imageFile: null,
  },
  {
    name: "Alat Ibadah",
    unit: "Meter",
    estimate: "1 Hari",
    price_regular: 3000,
    price_express: 6000,
    description: "Dijaga kebersihan dan kesucian sesuai standar.",
    image: "alat-ibadah.png",
    imageFile: null,
  },
  {
    name: "Handuk",
    unit: "Satuan",
    estimate: "1 Hari",
    price_regular: 3000,
    price_express: 6000,
    description: "Cuci higienis untuk menjaga kelembutan handuk.",
    image: "handuk.png",
    imageFile: null,
  },
  {
    name: "Sprei",
    unit: "Meter",
    estimate: "2 Hari",
    price_regular: 5000,
    price_express: 10000,
    description: "Dicuci lembut agar warna dan serat tetap awet.",
    image: "sprei.png",
    imageFile: null,
  },
]);

const newProduct = ref<Product>({
  name: "",
  unit: "",
  estimate: "",
  price_regular: null,
  price_express: null,
  description: "",
  image: "",         // existing: file name or url
  imageFile: null,   // NEW: File object
});

function toggleDetail(index: any) {
  activeIndex.value = activeIndex.value === index ? null : index;
}

function saveChanges(product: Product) {
  alert(`Perubahan untuk ${product.name} telah disimpan!`);
}

function handleImageError(event: any) {
  event.target.src = "/images/no-image.png";
}

function openForm() {
  showForm.value = true;
}

function closeForm() {
  showForm.value = false;
}

function saveNewProduct() {
  if (!newProduct.value.name || !newProduct.value.unit) {
    alert("Harap isi semua data penting terlebih dahulu!");
    return;
  }
  products.value.push({ ...newProduct.value });
  alert("✅ Layanan baru berhasil ditambahkan!");
  showForm.value = false;

  newProduct.value = {
    name: "",
    unit: "",
    estimate: "",
    price_regular: null,
    price_express: null,
    description: "",
    image: "",
    imageFile: null
  };
  if (!newProduct.value.name || !newProduct.value.unit) {
    alert("Harap isi semua data penting terlebih dahulu!");
    return;
  }
}

function triggerFileInput() {
  if (fileInput.value) {
    fileInput.value.click();
  }
}

function onFileChange(e: Event) {
  const target = e.target as HTMLInputElement;
  const file = target.files && target.files[0];
  if (!file) return;
  handleSelectedFile(file);
}

function handleFileDrop(e: DragEvent) {
  e.preventDefault();
  dragOver.value = false;
  const file = e.dataTransfer?.files?.[0];
  if (!file) return;
  handleSelectedFile(file);
}

function handleSelectedFile(file: File) {
  // optional: validate type & size
  const allowed = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp']
  if (!allowed.includes(file.type)) {
    alert('Format file tidak didukung. Gunakan JPG/PNG/WebP.')
    return
  }
  if (file.size > 2 * 1024 * 1024) { // 2MB
    alert('Ukuran file terlalu besar (max 2MB).')
    return
  }

  newProduct.value.imageFile = file
  // set preview
  previewUrl.value = URL.createObjectURL(file)
  // set image name (optional)
  newProduct.value.image = file.name
}

function removeSelectedImage() {
  if (previewUrl.value) {
    URL.revokeObjectURL(previewUrl.value)
    previewUrl.value = null
  }
  newProduct.value.imageFile = null
  newProduct.value.image = ''
  // also clear file input value
  if (fileInput.value) fileInput.value.value = ''
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-5px);
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

.upload-dropzone {
  background: white !important;
  color: #4b5563;
  /* text-gray-600 */
  border-color: #e5e7eb;
  /* gray-200 */
  transition: 0.25s ease;
}

.upload-dropzone--over {
  border-color: #f472b6;
  /* pink-400 */
  box-shadow: 0 6px 18px rgba(244, 114, 182, 0.3);
}

.text-pink-400 {
  color: #f9a8d4;
}

.text-\[\#8EC9FF\] {
  color: #8EC9FF;
}


.upload-dropzone p {
  color: #cbd5e1;
}


.upload-dropzone img {
  border: 1px solid rgba(0, 0, 0, 0.06);
}


/* responsive kecil */
@media (max-width: 640px) {
  .upload-dropzone {
    padding: 18px;
  }
}
</style>