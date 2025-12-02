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

    function route(
        name?: string,
        params?: Record<string, any>,
        absolute?: boolean,
    ): any;

    interface NavItem {
        title: string;
        href: NonNullable<InertiaLinkProps['href']>;
        icon?: LucideIcon;
        isActive?: boolean;
    }

    interface AdminOrder {
        id: number;
        customer: string;
        address: string;
        service: string;
        stuff: string;
        orderStatus: string;
        paymentStatus: string;
        deliveryMethod: string;
        paymentMethod: string;
        orderDate: string;
        pickupDate?: string;
        returnDate?: string;
        pricePerKg: number;
        weight: number;
        date: string;
        total: number;
        message: string;
        created_at?: string;
        updated_at?: string;
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
        pesan: string;
        total?: number;
        pembayaran: string;
        updated_at: string;
    }

    interface Payment {
        id: number;
        orderId: string;
        userName: string;
        method: string;
        total: number;
        status: string;
        date: string;
        proof: string;
    }

    interface Product {
        name: string;
        unit: string;
        estimate: string;
        price_regular: number | null;
        price_express: number | null;
        description: string;
        image: string | null;
        imageFile: File | null;
    }

    interface Item {
        name: string;
        img: string;
        price: string;
        bg: string;
        desc: string;
    }

    type AppPageProps<
        T extends Record<string, unknown> = Record<string, unknown>,
    > = T & {
        name: string;
        quote: { message: string; author: string };
        auth: { user; any };
        sidebarOpen: boolean;
    };
}

export {};

export interface BreadcrumbItem {
    title: string;
    href: string;
}
