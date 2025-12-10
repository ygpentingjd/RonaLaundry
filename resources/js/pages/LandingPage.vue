<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import Footer from '../components/Footer.vue';
import Hero from '../components/Hero.vue';
import MapSection from '../components/MapSection.vue';
import Navbar from '../components/Navbar.vue';

const navbarHeight = ref('0px');

onMounted(() => {
    const navbar = document.querySelector('nav');
    if (navbar) {
        navbarHeight.value = navbar.offsetHeight + 'px';
    }
});

const page = usePage() as unknown as { props: { auth: any } };
const isAuthenticated = !!page.props.auth.user;

const handlePesanSekarang = () => {
    if (isAuthenticated) {
        // Sudah login langsung ke reservasi
        router.visit('/reservasi');
    } else {
        // Simpan niat user ke localStorage
        localStorage.setItem('afterLoginRedirect', '/reservasi');
        router.visit('/login');
    }
};

defineProps<{
    products?: any[];
}>();
</script>

<template>
    <Head title="Landing Page - RonaLaundry" />
    <div
        class="flex min-h-screen flex-col scroll-smooth bg-gradient-to-b from-white to-pink-50"
    >
        <Navbar :is-authenticated="isAuthenticated" />

        <main :style="{ marginTop: navbarHeight }" class="flex-grow">
            <!-- Section 1: Hero -->
            <Hero :products="products" />

            <!-- Section 2: About -->
            <section
                id="about"
                class="relative flex min-h-screen w-full flex-col items-center justify-center bg-cover bg-center bg-no-repeat px-6 py-20 text-center md:px-8 md:py-24"
                style="background-image: url('/images/pink.jpg')"
            >
                <div
                    class="mx-auto flex max-w-6xl flex-col items-center gap-10 px-4 md:flex-row md:px-6"
                >
                    <!-- Gambar kiri -->
                    <div class="flex flex-1 justify-center">
                        <img
                            src="/images/street.jpg"
                            alt="Rona Laundry"
                            class="w-80 rounded-xl object-cover shadow-xl md:w-96"
                            loading="lazy"
                        />
                    </div>

                    <!-- Teks kanan -->
                    <div class="flex-1 text-gray-800 md:pl-10 md:text-left">
                        <h2
                            class="mb-4 text-center text-3xl font-semibold tracking-tight md:text-left"
                        >
                            Tentang
                            <span class="text-pink-600">RonaLaundry</span>
                        </h2>
                        <p class="mb-3 leading-relaxed text-gray-700">
                            Di <span class="font-semibold">Rona Laundry</span>,
                            kami percaya bahwa pakaian bersih bukan sekadar
                            kebutuhan, tapi juga cerminan gaya hidup sehat dan
                            nyaman. Karena itu, kami menghadirkan layanan
                            laundry kiloan dan satuan yang cepat, higienis, dan
                            wangi tahan lama.
                        </p>
                        <p class="mb-5 leading-relaxed text-gray-700">
                            <span class="font-semibold">Rona Laundry</span> juga
                            melayani pelanggan dari berbagai kalangan, mulai
                            dari keluarga, mahasiswa, hingga komunitas. Kami
                            siap membantu siapa pun yang ingin menghemat waktu
                            tanpa khawatir soal cucian.
                        </p>
                        <div class="mt-4 flex items-center gap-3 text-pink-700">
                            <span class="text-2xl">⭐</span>
                            <span class="font-medium text-gray-700 italic"
                                >Jangan Ragu Jangan Bimbang</span
                            >
                        </div>
                    </div>
                </div>

                <!-- Section Metode Pembayaran -->
                <div
                    class="mt-16 rounded-2xl bg-white/80 px-4 py-12 text-center shadow-lg backdrop-blur-sm md:mt-20"
                >
                    <h3 class="mb-8 text-xl font-semibold text-gray-800">
                        Mau QRIS, transfer, atau cash? <br />
                        Semua gampang tinggal gas!
                    </h3>

                    <!-- 🔹 Grid responsif -->
                    <div
                        class="mt-6 grid grid-cols-2 justify-items-center gap-8 sm:grid-cols-2 md:grid-cols-4"
                    >
                        <div class="flex flex-col items-center">
                            <img
                                src="/images/qris.webp"
                                alt="QRIS"
                                class="h-28 w-28 rounded-xl bg-gray-50 object-contain p-3 shadow-lg transition-transform duration-300 hover:scale-105 md:h-36 md:w-36"
                                loading="lazy"
                            />
                            <p class="mt-3 font-medium text-gray-700">QRIS</p>
                        </div>

                        <div class="flex flex-col items-center">
                            <img
                                src="/images/bsi.png"
                                alt="BSI"
                                class="h-28 w-28 rounded-xl bg-gray-50 object-contain p-3 shadow-lg transition-transform duration-300 hover:scale-105 md:h-36 md:w-36"
                                loading="lazy"
                            />
                            <p class="mt-3 font-medium text-gray-700">BSI</p>
                        </div>

                        <div class="flex flex-col items-center">
                            <img
                                src="/images/bni.jpg"
                                alt="BNI"
                                class="h-28 w-28 rounded-xl bg-gray-50 object-contain p-3 shadow-lg transition-transform duration-300 hover:scale-105 md:h-36 md:w-36"
                                loading="lazy"
                            />
                            <p class="mt-3 font-medium text-gray-700">BNI</p>
                        </div>

                        <div class="flex flex-col items-center">
                            <img
                                src="/images/cash.png"
                                alt="Cash"
                                class="h-28 w-28 rounded-xl bg-gray-50 object-contain p-3 shadow-lg transition-transform duration-300 hover:scale-105 md:h-36 md:w-36"
                                loading="lazy"
                            />
                            <p class="mt-3 font-medium text-gray-700">Cash</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 3: CTA + Testimoni -->
            <section
                class="bg-gradient-to-b from-pink-100 to-pink-200 py-20 text-center"
            >
                <button
                    class="mb-16 rounded-lg bg-pink-500 px-12 py-4 text-lg font-semibold text-white shadow-md transition hover:bg-pink-600 focus:ring-2 focus:ring-pink-300 focus:ring-offset-2 focus:outline-none"
                    @click="handlePesanSekarang"
                >
                    PESAN SEKARANG
                </button>

                <h3 class="mb-10 text-2xl font-semibold text-gray-800">
                    APA KATA MEREKA?
                </h3>

                <div
                    class="mx-auto grid max-w-6xl grid-cols-1 gap-8 px-6 md:grid-cols-3"
                >
                    <div
                        class="rounded-xl bg-white p-6 text-gray-700 shadow-lg"
                    >
                        <img
                            src="/images/cat.jpg"
                            alt="User"
                            class="mx-auto mb-4 h-16 w-16 rounded-full object-cover"
                            loading="lazy"
                        />
                        <div class="mb-3 flex justify-center text-yellow-400">
                            <span v-for="i in 5" :key="i">★</span>
                        </div>
                        <p>
                            Cepat, tepat, wangi, rapi. Jadi ga perlu mikirin
                            cucian lagi deh.
                        </p>
                    </div>

                    <div
                        class="rounded-xl bg-white p-6 text-gray-700 shadow-lg"
                    >
                        <img
                            src="/images/chris.png"
                            alt="User"
                            class="mx-auto mb-4 h-16 w-16 rounded-full object-cover"
                            loading="lazy"
                        />
                        <div class="mb-3 flex justify-center text-yellow-400">
                            <span v-for="i in 5" :key="i">★</span>
                        </div>
                        <p>
                            Pelayanan sangat mantap dan tepat, karena waktu yang
                            saya tentukan.
                        </p>
                    </div>

                    <div
                        class="rounded-xl bg-white p-6 text-gray-700 shadow-lg"
                    >
                        <img
                            src="/images/asuna.jpg"
                            alt="User"
                            class="mx-auto mb-4 h-16 w-16 rounded-full object-cover"
                            loading="lazy"
                        />
                        <div class="mb-3 flex justify-center text-yellow-400">
                            <span v-for="i in 5" :key="i">★</span>
                        </div>
                        <p>Pelayanan sangat baik!</p>
                    </div>
                </div>
            </section>
        </main>

        <MapSection />
        <Footer />
    </div>
</template>
