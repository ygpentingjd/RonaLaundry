import "../css/app.css";
import "material-icons/iconfont/material-icons.css";

import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import type { DefineComponent } from "vue";
import { createApp, h } from "vue";

import axios from "axios";

axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");

if (token) {
    axios.defaults.headers.common["X-CSRF-TOKEN"] = token;
} else {
    console.error("CSRF token not found!");
}

import { initializeTheme } from "./composables/useAppearance";

// Toast notification
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

// @ts-ignore
import AOS from "aos";
import "aos/dist/aos.css";

import "tailwindcss-animate";

// Nama aplikasi dari .env
const appName = import.meta.env.VITE_APP_NAME || "Laravel";

// Inertia setup
createInertiaApp({
  title: (title) => (title ? `${title} - ${appName}` : appName),
  resolve: (name) =>
    resolvePageComponent(
      `./pages/${name}.vue`,
      import.meta.glob<DefineComponent>("./pages/**/*.vue")
    ),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(Toast, {
        position: "top-right",
        timeout: 3000,
        closeOnClick: true,
        pauseOnHover: true,
        draggable: true,
      });

    // Inisialisasi theme (setelah app dibuat)
    initializeTheme();

    // Inisialisasi animasi scroll (opsional)
    AOS.init({
      duration: 800,
      once: true,
      easing: "ease-in-out",
    });

    app.mount(el);
  },
  progress: {
    color: "#4B5563",
  },
});

declare module '@inertiajs/core' {
    interface PageProps {
        user?: {
            username: string;
            email: string;
            nama_lengkap?: string;
            nomor_telepon?: string;
            alamat_lengkap?: string;
            catatan_lokasi?: string;
        };
    }
}