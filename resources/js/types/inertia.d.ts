import '@inertiajs/core';

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
        [key: string]: any; // WAJIB
    }
}
