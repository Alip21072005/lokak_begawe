<!-- eslint-disable @typescript-eslint/no-unused-vars -->
<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { LogOut, Settings, UserCircle, Building2 } from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { computed } from 'vue';
import {
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import { logout } from '@/routes';
import { edit } from '@/routes/profile';
import type { User } from '@/types';

type Props = {
    user: User;
};

const props = defineProps<Props>();
const page = usePage();

// --- INTEGRASI DATA LOGO ---
// Mengambil logo dari mitra jika user memiliki relasi mitra
const userLogo = computed(() => {
    const mitra = (page.props.auth as any).user?.mitra;

    if (mitra && mitra.logo_mitra) {
        return `/storage/${mitra.logo_mitra}`;
    }

    return null;
});

const handleLogout = (e: Event) => {
    // Mencegah link langsung jalan
    e.preventDefault();

    Swal.fire({
        title: 'Yakin mau keluar?',
        text: 'Sesi Anda akan berakhir dan harus login kembali.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48', // Warna Rose-600 (Warning)
        cancelButtonColor: '#cbd5e1',
        confirmButtonText: 'Ya, Keluar!',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-[2rem] border-none',
            title: 'font-black uppercase italic tracking-tighter',
            confirmButton: 'rounded-xl font-bold uppercase italic px-6',
            cancelButton: 'rounded-xl font-bold uppercase italic px-6',
        },
    }).then((result) => {
        if (result.isConfirmed) {
            // Jalankan logout via Inertia
            router.post(
                (window as any).route('logout'),
                {},
                {
                    onFinish: () => {
                        router.flushAll(); // Bersihkan cache jika perlu
                    },
                },
            );
        }
    });
};
</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-3 px-3 py-3 text-left">
            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-slate-100 bg-slate-50 shadow-sm"
            >
                <img
                    v-if="userLogo"
                    :src="userLogo"
                    class="h-full w-full object-cover"
                />
                <div
                    v-else
                    class="flex h-full w-full items-center justify-center bg-white font-black text-sky-700 italic"
                >
                    {{ user.name.charAt(0).toUpperCase() }}
                </div>
            </div>

            <div class="grid flex-1 text-left text-sm leading-tight">
                <span
                    class="truncate font-black tracking-tight text-slate-900 uppercase italic"
                >
                    {{ user.name }}
                </span>
                <span
                    class="truncate text-[10px] font-bold tracking-widest text-slate-400 uppercase italic"
                >
                    {{ user.email }}
                </span>
            </div>
        </div>
    </DropdownMenuLabel>

    <DropdownMenuSeparator class="bg-slate-50" />

    <DropdownMenuGroup>
        <DropdownMenuItem :as-child="true">
            <Link
                class="flex w-full cursor-pointer items-center gap-2 px-3 py-2.5 text-xs font-black text-slate-600 uppercase italic transition-colors hover:text-sky-700"
                :href="edit()"
                prefetch
            >
                <Settings class="h-4 w-4" />
                <span>Pengaturan Profil</span>
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>

    <DropdownMenuSeparator class="bg-slate-50" />

    <DropdownMenuItem :as-child="true">
        <button
            class="flex w-full cursor-pointer items-center gap-2 px-3 py-2.5 text-xs font-black text-rose-500 uppercase italic transition-colors hover:bg-rose-50 hover:text-rose-600"
            @click="handleLogout"
            data-test="logout-button"
        >
            <LogOut class="h-4 w-4" />
            <span>Keluar Akun</span>
        </button>
    </DropdownMenuItem>
</template>

<style scoped>
/* Transisi halus untuk hover menu */
:deep(.relative.flex.cursor-default.select-none.items-center) {
    transition: all 0.2s ease;
}
</style>
