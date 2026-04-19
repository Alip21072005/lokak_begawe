<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import {
    Search,
    User as UserIcon,
    CheckCircle2,
    Mail,
    Phone,
    Eye,
    ShieldAlert,
    Trash2,
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    auth: any;
    users: any[];
    filters: { search: string };
    stats: { total: number; active: number };
}>();

defineOptions({ layout: AppLayout });

// --- PENCARIAN REAL-TIME ---
const search = ref(props.filters.search);
watch(search, (value) => {
    router.get(
        '/dashboard/admin/kelolapelamar',
        { search: value },
        { preserveState: true, replace: true },
    );
});

// --- AKSI DENGAN KONFIRMASI ---

const confirmBlock = (id: string, name: string) => {
    Swal.fire({
        title: 'Blokir Akun?',
        text: `Pelamar ${name} tidak akan bisa masuk ke sistem.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        confirmButtonText: 'Ya, Blokir',
        customClass: { popup: 'rounded-[2.5rem]' },
    }).then((result) => {
        if (result.isConfirmed) {
            router.patch(
                `/dashboard/admin/blokir-akun-user/${id}`,
                {},
                {
                    onSuccess: () =>
                        Swal.fire({
                            title: 'Berhasil!',
                            icon: 'success',
                            customClass: { popup: 'rounded-[2.5rem]' },
                        }),
                },
            );
        }
    });
};

const confirmDelete = (id: string, name: string) => {
    Swal.fire({
        title: 'Hapus Permanen?',
        text: `Seluruh data ${name} akan hilang selamanya!`,
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        confirmButtonText: 'Ya, Hapus',
        customClass: { popup: 'rounded-[2.5rem]' },
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/dashboard/admin/hapus-akun-user/${id}`, {
                onSuccess: () =>
                    Swal.fire({
                        title: 'Terhapus!',
                        icon: 'success',
                        customClass: { popup: 'rounded-[2.5rem]' },
                    }),
            });
        }
    });
};
</script>

<template>
    <Head title="Kelola Pelamar - Admin" />

    <div class="space-y-8 p-6 lg:p-10">
        <div
            class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <h1
                    class="text-3xl font-black tracking-tighter text-lokak-text uppercase italic md:text-4xl"
                >
                    KELOLA <span class="text-lokak-brand">PELAMAR</span>
                </h1>
                <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative w-full sm:w-72">
                    <Search
                        class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400"
                    />
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari nama atau email..."
                        class="h-11 w-full rounded-xl border border-slate-200 bg-white pr-4 pl-10 text-xs font-bold outline-none focus:ring-2 focus:ring-lokak-brand/20"
                    />
                </div>
            </div>
        </div>

        <div
            class="rounded-[2.5rem] border border-slate-200 bg-white p-4 shadow-sm lg:p-8"
        >
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr
                            class="border-b border-slate-100 text-left text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase italic"
                        >
                            <th class="px-4 pb-6">Identitas Pelamar</th>
                            <th class="px-4 pb-6">Kontak</th>
                            <th class="px-4 pb-6 text-center">Total Lamaran</th>
                            <th class="px-4 pb-6">Status Akun</th>
                            <th class="px-4 pb-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr
                            v-for="user in users"
                            :key="user.id"
                            class="group transition-colors hover:bg-slate-50/50"
                        >
                            <td class="px-4 py-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="flex h-11 w-11 items-center justify-center rounded-full bg-sky-100 text-[10px] font-black text-lokak-brand"
                                    >
                                        {{ user.avatar }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-black text-lokak-text uppercase"
                                            >{{ user.name }}</span
                                        >
                                        <span
                                            class="text-[10px] font-bold text-slate-400 uppercase italic"
                                            >{{ user.university }}</span
                                        >
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-6">
                                <div class="flex flex-col gap-1">
                                    <span
                                        class="flex items-center gap-2 text-[10px] font-bold text-lokak-text uppercase italic"
                                    >
                                        <Mail
                                            class="h-3 w-3 text-lokak-brand"
                                        />
                                        {{ user.email }}
                                    </span>
                                    <span
                                        class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic"
                                    >
                                        <Phone class="h-3 w-3" />
                                        {{ user.phone }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-4 py-6 text-center">
                                <span
                                    class="rounded-lg bg-slate-100 px-3 py-1 text-xs font-black text-lokak-text"
                                >
                                    {{ user.applicationsCount }}
                                </span>
                            </td>

                            <td class="px-4 py-6">
                                <span
                                    :class="[
                                        user.status === 'blocked'
                                            ? 'border-rose-200 bg-rose-100 text-rose-700'
                                            : 'border-emerald-200 bg-emerald-100 text-emerald-700',
                                        'inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-[9px] font-black tracking-wider uppercase italic',
                                    ]"
                                >
                                    <div
                                        :class="[
                                            user.status === 'blocked'
                                                ? 'bg-rose-500'
                                                : 'bg-emerald-500',
                                            'h-1.5 w-1.5 rounded-full',
                                        ]"
                                    ></div>
                                    {{
                                        user.status === 'blocked'
                                            ? 'Terblokir'
                                            : 'Aktif'
                                    }}
                                </span>
                            </td>

                            <td class="px-4 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <button
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-100 bg-white text-slate-400 shadow-sm transition-all hover:border-lokak-brand hover:text-lokak-brand"
                                    >
                                        <Eye class="h-4 w-4" />
                                    </button>
                                    <button
                                        @click="
                                            confirmBlock(user.id, user.name)
                                        "
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-100 bg-white text-slate-400 shadow-sm transition-all hover:border-orange-100 hover:text-orange-500"
                                    >
                                        <ShieldAlert class="h-4 w-4" />
                                    </button>
                                    <button
                                        @click="
                                            confirmDelete(user.id, user.name)
                                        "
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-100 bg-white text-slate-400 shadow-sm transition-all hover:border-rose-100 hover:text-rose-500"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div
                class="flex items-center gap-6 rounded-4xl border border-slate-200 bg-white p-6"
            >
                <div
                    class="flex h-14 w-14 items-center justify-center rounded-2xl border border-sky-100 bg-sky-50 text-lokak-brand shadow-inner"
                >
                    <UserIcon class="h-7 w-7" />
                </div>
                <div>
                    <h4
                        class="text-xs font-black tracking-widest text-lokak-text uppercase italic"
                    >
                        Total Pelamar Terdaftar
                    </h4>
                    <p class="mt-1 text-2xl font-black text-lokak-text">
                        {{ stats.total }}
                        <span
                            class="ml-1 text-[10px] font-bold text-slate-400 uppercase"
                            >Orang</span
                        >
                    </p>
                </div>
            </div>

            <div
                class="flex items-center gap-6 rounded-4xl bg-slate-900 p-6 text-white shadow-xl shadow-slate-900/10"
            >
                <div
                    class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 text-emerald-400"
                >
                    <CheckCircle2 class="h-7 w-7" />
                </div>
                <div>
                    <h4
                        class="text-xs font-black tracking-widest uppercase italic opacity-60"
                    >
                        Pelamar Baru Bulan Ini
                    </h4>
                    <p class="mt-1 text-2xl font-black">
                        {{ stats.active }}
                        <span
                            class="ml-1 text-[10px] font-bold uppercase opacity-40"
                            >User</span
                        >
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
