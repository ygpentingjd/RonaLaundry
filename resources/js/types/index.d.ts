import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

declare global {
    interface User {
        id: number;
        name: string;
        email: string;
        avatar?: string;
        email_verified_at: string | null;
        created_at: string;
        updated_at: string;
    }

    interface Auth {
        user: User;
    }

    interface BreadcrumbItem {
        title: string;
        href: string;
    }

    interface NavItem {
        title: string;
        href: NonNullable<InertiaLinkProps['href']>;
        icon?: LucideIcon;
        isActive?: boolean;
    }

    interface Order {
        id: number;
        layanan: string;
        tanggal: string;
        nama: string;
        alamat: string;
        payment_status: string;
        status: string;
        barang: string[];
        harga_per_kg?: number;
        berat?: number;
        total?: number;
        pembayaran: string;
        updated_at: string;
    }

    type AppPageProps<
        T extends Record<string, unknown> = Record<string, unknown>,
    > = T & {
        name: string;
        quote: { message: string; author: string };
        auth: {user, any};
        sidebarOpen: boolean;
    };
}

export {};
