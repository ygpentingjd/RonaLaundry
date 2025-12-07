<template>
    <AdminPanel>
        <Head title="Products Management - RonaLaundry" />
        <!-- 🔹 Header -->
        <div
            class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
        >
            <h1 class="text-3xl font-semibold text-black">
                Daftar Produk Laundry
            </h1>
            <button
                @click="openForm"
                class="rounded-lg bg-pink-600 px-5 py-2 text-white shadow-md transition hover:bg-pink-700"
            >
                + Tambah Layanan Laundry
            </button>
        </div>

        <!-- 🔹 Daftar Produk -->
        <div v-if="loading" class="py-12 text-center text-gray-500">
            Memuat produk...
        </div>

        <div v-else>
            <div
                v-for="(product, index) in productList"
                :key="product.id"
                class="mb-4 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-md"
            >
                <div
                    class="flex cursor-pointer items-center justify-between p-4 transition hover:bg-pink-50"
                    @click="toggleDetail(index)"
                >
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">
                            {{ product.name }}
                        </h2>
                        <p class="text-sm text-gray-500">
                            Klik untuk lihat detail
                        </p>
                    </div>
                    <button
                        class="font-semibold text-pink-600 focus:outline-none"
                    >
                        {{ activeIndex === index ? '▲ Tutup' : '▼ Detail' }}
                    </button>
                </div>

                <transition name="fade">
                    <div
                        v-if="activeIndex === index"
                        class="border-t border-gray-200 bg-pink-50 p-6"
                    >
                        <div
                            class="grid grid-cols-1 items-start gap-6 md:grid-cols-2"
                        >
                            <!-- Gambar -->
                            <div class="flex justify-center md:justify-start">
                                <img
                                    :src="`/storage/${product.image}`"
                                    class="mx-auto mb-6 h-64 w-64 object-contain"
                                    @error="handleImageError"
                                />
                            </div>

                            <!-- Detail Produk -->
                            <div class="space-y-3">
                                <div>
                                    <p class="font-medium text-gray-600">
                                        Nama Barang
                                    </p>
                                    <p class="text-gray-800">
                                        {{ product.name }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="font-medium text-gray-600">
                                            Estimasi Waktu
                                        </p>
                                        <p class="text-gray-800">
                                            {{ product.estimate }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-600">
                                            Satuan
                                        </p>
                                        <p class="text-gray-800">
                                            {{ product.unit }}
                                        </p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="font-medium text-gray-600">
                                            Harga Reguler
                                        </p>
                                        <input
                                            type="number"
                                            v-model.number="
                                                product.price_regular
                                            "
                                            class="w-full rounded-lg border px-3 py-2 text-black focus:ring-2 focus:ring-pink-400"
                                        />
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-600">
                                            Harga Kilat (24 jam)
                                        </p>
                                        <input
                                            type="number"
                                            v-model.number="
                                                product.price_express
                                            "
                                            class="w-full rounded-lg border px-3 py-2 text-black focus:ring-2 focus:ring-pink-400"
                                        />
                                    </div>
                                </div>

                                <div class="mt-3 flex gap-2">
                                    <button
                                        @click="openEdit(product)"
                                        class="rounded-lg bg-yellow-400 px-4 py-2 text-white hover:bg-yellow-500"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        @click="confirmDelete(product)"
                                        class="rounded-lg bg-red-500 px-4 py-2 text-white hover:bg-red-600"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mt-6">
                            <p class="mb-2 font-medium text-gray-600">
                                Deskripsi / Syarat & Ketentuan
                            </p>
                            <textarea
                                v-model="product.description"
                                rows="3"
                                class="w-full rounded-lg border px-3 py-2 text-black focus:ring-2 focus:ring-pink-400"
                            ></textarea>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <button
                                @click="saveChanges(product)"
                                class="rounded-lg bg-pink-600 px-5 py-2 text-white transition hover:bg-pink-700"
                            >
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </transition>
            </div>
        </div>

        <!-- 🩷 Modal Tambah / Edit Layanan -->
        <div
            v-if="showForm"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
        >
            <div
                class="animate-fadeIn relative w-[520px] rounded-2xl bg-[#ffe4ec] p-6 shadow-2xl"
            >
                <button
                    @click="closeForm"
                    class="absolute top-3 right-3 text-lg font-bold text-pink-700 transition hover:text-pink-900"
                >
                    ✕
                </button>

                <h2 class="mb-5 text-center text-xl font-bold text-pink-700">
                    {{ editMode ? 'Edit' : 'Tambah' }} Layanan Laundry
                </h2>

                <form
                    @submit.prevent="submitForm"
                    class="space-y-3 text-gray-700"
                >
                    <div>
                        <label class="font-semibold text-pink-600"
                            >Nama Barang</label
                        >
                        <input
                            v-model="form.nama_barang"
                            type="text"
                            required
                            class="w-full rounded-lg border bg-white px-3 py-2 text-black outline-none focus:ring-2 focus:ring-pink-400"
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="font-semibold text-pink-600"
                                >Satuan</label
                            >
                            <select
                                v-model="form.satuan"
                                required
                                class="w-full rounded-lg border bg-white px-3 py-2 text-black outline-none focus:ring-2 focus:ring-pink-400"
                            >
                                <option value="" disabled>Pilih Satuan</option>
                                <option value="kg">kg</option>
                                <option value="pcs">pcs</option>
                                <option value="meter">meter</option>
                            </select>
                        </div>

                        <div>
                            <label class="font-semibold text-pink-600"
                                >Estimasi Waktu</label
                            >
                            <input
                                v-model="form.estimasi_waktu"
                                type="text"
                                required
                                class="w-full rounded-lg border bg-white px-3 py-2 text-black outline-none focus:ring-2 focus:ring-pink-400"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="font-semibold text-pink-600"
                                >Harga Reguler</label
                            >
                            <input
                                v-model.number="form.harga_reguler"
                                type="number"
                                required
                                min="0"
                                class="w-full rounded-lg border bg-white px-3 py-2 text-black outline-none focus:ring-2 focus:ring-pink-400"
                            />
                        </div>
                        <div>
                            <label class="font-semibold text-pink-600"
                                >Harga Kilat (24 Jam)</label
                            >
                            <input
                                v-model.number="form.harga_kilat"
                                type="number"
                                required
                                min="0"
                                class="w-full rounded-lg border bg-white px-3 py-2 text-black outline-none focus:ring-2 focus:ring-pink-400"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="font-semibold text-pink-600"
                            >Deskripsi / Syarat & Ketentuan</label
                        >
                        <textarea
                            v-model="form.deskripsi"
                            rows="3"
                            class="w-full rounded-lg border bg-white px-3 py-2 text-black outline-none focus:ring-2 focus:ring-pink-400"
                        ></textarea>
                    </div>

                    <div>
                        <label class="mb-2 block font-semibold text-pink-600"
                            >Image</label
                        >

                        <div
                            class="upload-dropzone relative cursor-pointer rounded-lg border-2 border-dashed bg-white p-6 text-center"
                            @dragover.prevent
                            @dragenter.prevent="dragOver = true"
                            @dragleave.prevent="dragOver = false"
                            @drop.prevent="handleFileDrop"
                            @click="triggerFileInput"
                            :class="{ 'upload-dropzone--over': dragOver }"
                        >
                            <input
                                ref="fileInput"
                                type="file"
                                accept="image/*"
                                class="hidden"
                                @change="onFileChange"
                            />

                            <div v-if="!form.imageFile && !form.image">
                                <p class="text-sm">
                                    <span class="text-pink-500"
                                        >Drag & Drop your files or&nbsp;</span
                                    >
                                    <span
                                        class="cursor-pointer font-semibold text-[#8EC9FF] underline"
                                        @click="triggerFileInput"
                                    >
                                        Browse
                                    </span>
                                </p>
                            </div>

                            <div
                                v-else
                                class="flex items-center justify-center gap-3"
                            >
                                <img
                                    v-if="previewUrl"
                                    :src="previewUrl"
                                    alt="preview"
                                    class="h-20 w-20 rounded-md border object-cover"
                                />
                                <div class="text-left">
                                    <div
                                        class="text-sm font-medium text-gray-700"
                                    >
                                        {{
                                            form.imageFile
                                                ? form.imageFile.name
                                                : form.image
                                        }}
                                    </div>
                                    <button
                                        type="button"
                                        class="mt-1 text-xs text-red-500 underline"
                                        @click.stop="removeSelectedImage"
                                    >
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>

                        <p class="mt-2 text-xs text-gray-400">
                            Supported: JPG, PNG, WEBP. Max 2MB.
                        </p>
                    </div>

                    <div class="mt-6 flex justify-center gap-4">
                        <button
                            type="submit"
                            class="rounded-lg bg-green-500 px-6 py-2 text-white shadow-md transition hover:bg-green-600"
                        >
                            {{ editMode ? 'Perbarui' : 'Simpan' }}
                        </button>
                        <button
                            type="button"
                            @click="closeForm"
                            class="rounded-lg bg-red-500 px-6 py-2 text-white shadow-md transition hover:bg-red-600"
                        >
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminPanel>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, ref } from 'vue';
import AdminPanel from '../AdminPanel.vue';

interface ProductRaw {
    id_product: number; // Adjusted to match Model primary key
    nama_barang: string;
    satuan: string;
    estimasi_waktu: string;
    harga_reguler: number;
    harga_kilat: number;
    deskripsi: string;
    image: string;
}

const props = defineProps<{
    products: ProductRaw[];
}>();

// state
const loading = ref(false);
const activeIndex = ref<number | null>(null);
const showForm = ref(false);
const editMode = ref(false);

const fileInput = ref<HTMLInputElement | null>(null);
const dragOver = ref(false);
const previewUrl = ref<string | null>(null);

const form = ref<any>({
    id: null,
    nama_barang: '',
    satuan: '',
    estimasi_waktu: '',
    harga_reguler: null,
    harga_kilat: null,
    deskripsi: '',
    image: '',
    imageFile: null,
});

// mapping helper
function mapProductFromApi(item: ProductRaw) {
    return {
        id: item.id_product, // Map from id_product
        name: item.nama_barang,
        unit: item.satuan,
        estimate: item.estimasi_waktu,
        price_regular: item.harga_reguler,
        price_express: item.harga_kilat,
        description: item.deskripsi,
        image: item.image,
        image_url: item.image
            ? `/storage/${item.image}`
            : '/images/no-image.png',
    };
}

// Use computed or watch to map props.products if needed, or just use it directly if structure matches
// For now, let's map it on mount or watch
// Use computed to map props.products so it stays reactive to Inertia updates
const productList = computed(() => {
    if (!props.products) return [];
    return props.products.map((p: ProductRaw) => mapProductFromApi(p));
});

function toggleDetail(index: number) {
    activeIndex.value = activeIndex.value === index ? null : index;
}

function handleImageError(e: Event) {
    (e.target as HTMLImageElement).src = '/images/no-image.png';
}

function openForm() {
    resetForm();
    showForm.value = true;
}

function closeForm() {
    showForm.value = false;
    editMode.value = false;
    resetForm();
}

function openEdit(product: any) {
    editMode.value = true;
    showForm.value = true;
    form.value.id = product.id;
    form.value.nama_barang = product.name;
    form.value.satuan = product.unit;
    form.value.estimasi_waktu = product.estimate;
    form.value.harga_reguler = product.price_regular;
    form.value.harga_kilat = product.price_express;
    form.value.deskripsi = product.description;
    form.value.image = product.image;
    previewUrl.value = product.image
        ? `/storage/${product.image}`
        : null;
}

function triggerFileInput() {
    if (fileInput.value) fileInput.value.click();
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
    const allowed = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];
    if (!allowed.includes(file.type)) {
        alert('Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau WebP.');
        return;
    }

    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran file terlalu besar (max 2MB).');
        return;
    }

    form.value.imageFile = file;
    previewUrl.value = URL.createObjectURL(file);
}

function removeSelectedImage() {
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
        previewUrl.value = null;
    }
    form.value.imageFile = null;
    form.value.image = '';
    if (fileInput.value) (fileInput.value.value as any) = '';
}

async function submitForm() {
    // basic validation
    if (
        !form.value.nama_barang ||
        !form.value.satuan ||
        !form.value.harga_reguler
    ) {
        alert('Nama barang, satuan, dan harga reguler wajib diisi.');
        return;
    }

    const fd = new FormData();
    fd.append('nama_barang', form.value.nama_barang);
    fd.append('satuan', form.value.satuan);
    fd.append('estimasi_waktu', form.value.estimasi_waktu);
    fd.append('harga_reguler', String(form.value.harga_reguler ?? ''));
    fd.append('harga_kilat', String(form.value.harga_kilat ?? ''));
    fd.append('deskripsi', form.value.deskripsi ?? '');

    if (form.value.imageFile) {
        fd.append('image', form.value.imageFile);
    }

    try {
        if (editMode.value && form.value.id) {
            // use method spoofing to ensure compatibility
            fd.append('_method', 'PUT');
            // Route resource 'products' -> PUT /products/{id}
            await axios.post(`/products/${form.value.id}`, fd, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });
            alert('Produk berhasil diperbarui');
        } else {
            await axios.post('/products', fd, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });
            alert('Produk berhasil ditambahkan');
        }

        closeForm();
        router.reload({ only: ['products'] }); // Reload props from Inertia
    } catch (e: any) {
        console.error(e);
        alert(
            e?.response?.data?.message ||
                'Terjadi kesalahan saat menyimpan produk.',
        );
    }
}

async function saveChanges(product: any) {
    // NOTE: We use FormData with _method: 'PUT' to support potential image updates if we expanded this form,
    // but even for text-only, it's consistent.
    // Important: field names must match Controller validation (nama_barang, etc.)
    const fd = new FormData();
    fd.append('_method', 'PUT'); // Method spoofing
    fd.append('nama_barang', product.name);
    fd.append('satuan', product.unit);
    fd.append('estimasi_waktu', product.estimate);
    fd.append('harga_reguler', String(product.price_regular ?? ''));
    fd.append('harga_kilat', String(product.price_express ?? ''));
    fd.append('deskripsi', product.description ?? '');

    // Note: Inline edit here doesn't support image change by default based on UI, 
    // but if you wanted to add it, you'd append 'image' here.

    try {
        await axios.post(`/products/${product.id}`, fd, { 
            headers: { 'Content-Type': 'multipart/form-data' },
        });
        alert('Perubahan disimpan');
    } catch (e: any) {
        console.error(e);
        alert('Gagal menyimpan perubahan: ' + (e.response?.data?.message || 'Error'));
    }
}

function confirmDelete(product: any) {
    if (!confirm(`Hapus produk "${product.name}"?`)) return;
    deleteProduct(product.id);
}

async function deleteProduct(id: number) {
    try {
        await axios.delete(`/products/${id}`);
        alert('Produk dihapus');
        window.location.reload();
    } catch (e) {
        console.error(e);
        alert('Gagal menghapus produk');
    }
}

function resetForm() {
    form.value = {
        id: null,
        nama_barang: '',
        satuan: '',
        estimasi_waktu: '',
        harga_reguler: null,
        harga_kilat: null,
        deskripsi: '',
        image: '',
        imageFile: null,
    };
    previewUrl.value = null;
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
    color: #8ec9ff;
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
