<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    Search,
    Filter,
    User,
    CheckCircle2,
    Mail,
    Phone,
    Eye,
    ShieldAlert,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

// Data Dummy Daftar Pelamar
const pelamarList = [
    {
        id: 1,
        name: 'Alip Maulana',
        email: 'alip@example.com',
        phone: '0812-3456-7890',
        university: 'Universitas Dehasen',
        status: 'Aktif',
        statusStyle: 'bg-emerald-100 text-emerald-700 border-emerald-200',
        applicationsCount: 12,
        avatar: 'AM',
    },
    {
        id: 2,
        name: 'Budi Setiawan',
        email: 'budi@example.com',
        phone: '0821-9988-7766',
        university: 'Universitas Bengkulu',
        status: 'Terblokir',
        statusStyle: 'bg-rose-100 text-rose-700 border-rose-200',
        applicationsCount: 2,
        avatar: 'BS',
    },
    {
        id: 3,
        name: 'Siti Aminah',
        email: 'siti@example.com',
        phone: '0853-1122-3344',
        university: 'IAIN Bengkulu',
        status: 'Aktif',
        statusStyle: 'bg-emerald-100 text-emerald-700 border-emerald-200',
        applicationsCount: 8,
        avatar: 'SA',
    },
];
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
                <p
                    class="mt-4 text-[11px] font-bold tracking-widest text-lokak-text-muted uppercase italic"
                >
                    Manajemen database pencari kerja dan pemantauan aktivitas
                    user.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative w-full sm:w-72">
                    <Search
                        class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400"
                    />
                    <input
                        type="text"
                        placeholder="Cari nama atau email..."
                        class="h-11 w-full rounded-xl border border-slate-200 bg-white pr-4 pl-10 text-xs font-bold outline-none focus:ring-2 focus:ring-lokak-brand/20"
                    />
                </div>
                <button
                    class="flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-lokak-brand"
                >
                    <Filter class="h-5 w-5" />
                </button>
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
                            v-for="user in pelamarList"
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
                                        user.statusStyle,
                                        'inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-[9px] font-black tracking-wider uppercase italic',
                                    ]"
                                >
                                    <div
                                        v-if="user.status === 'Aktif'"
                                        class="h-1.5 w-1.5 rounded-full bg-emerald-500"
                                    ></div>
                                    <div
                                        v-else
                                        class="h-1.5 w-1.5 rounded-full bg-rose-500"
                                    ></div>
                                    {{ user.status }}
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
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-100 bg-white text-slate-400 shadow-sm transition-all hover:border-rose-100 hover:text-rose-500"
                                    >
                                        <ShieldAlert class="h-4 w-4" />
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
                    <User class="h-7 w-7" />
                </div>
                <div>
                    <h4
                        class="text-xs font-black tracking-widest text-lokak-text uppercase italic"
                    >
                        Total Pelamar Terdaftar
                    </h4>
                    <p class="mt-1 text-2xl font-black text-lokak-text">
                        1.240
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
                        User Aktif Bulan Ini
                    </h4>
                    <p class="mt-1 text-2xl font-black">
                        856
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

<style scoped>
tr {
    transition: all 0.2s ease;
}
</style>
