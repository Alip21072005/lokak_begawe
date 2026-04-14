<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    Search,
    CheckCircle2,
    Eye,
    Trash2,
    ShieldCheck,
    AlertCircle,
    UserPlus,
} from 'lucide-vue-next';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const activeTab = ref('pending'); // 'pending' atau 'active'

// Data Dummy Mitra
const mitraList = [
    {
        id: 1,
        name: 'PT. Digital Bengkulu Baru',
        email: 'admin@dbb.id',
        sector: 'Teknologi',
        status: 'Pending',
        date: '14 April 2026',
        avatar: 'DB',
    },
    {
        id: 2,
        name: 'Universitas Dehasen',
        email: 'hrd@unived.ac.id',
        sector: 'Pendidikan',
        status: 'Terverifikasi',
        date: '10 Jan 2026',
        avatar: 'UD',
    },
];
</script>

<template>
    <Head title="Kelola Mitra - Admin" />

    <div class="space-y-8 p-6 lg:p-10">
        <div
            class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <h1
                    class="text-3xl font-black tracking-tighter text-lokak-text uppercase italic md:text-4xl"
                >
                    KONTROL <span class="text-lokak-brand">MITRA</span>
                </h1>
                <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
                <p
                    class="mt-4 text-[11px] font-bold tracking-widest text-lokak-text-muted uppercase italic"
                >
                    Manajemen pendaftaran perusahaan dan verifikasi legalitas
                    mitra.
                </p>
            </div>

            <button
                class="flex items-center justify-center gap-2 rounded-2xl bg-lokak-brand px-8 py-4 text-xs font-black text-white uppercase italic shadow-lg shadow-sky-900/20 transition-all hover:bg-lokak-brand-dark active:scale-95"
            >
                <UserPlus class="h-4 w-4" /> TAMBAH MITRA MANUAL
            </button>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
            <div
                class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm"
            >
                <p
                    class="text-[9px] font-black tracking-widest text-slate-400 uppercase italic"
                >
                    Total Mitra
                </p>
                <p class="text-2xl font-black text-lokak-text italic">
                    128
                    <span class="text-[10px] text-slate-300">Perusahaan</span>
                </p>
            </div>
            <div
                class="rounded-3xl border border-amber-100 bg-amber-50/50 p-6 shadow-sm"
            >
                <p
                    class="text-[9px] font-black tracking-widest text-amber-600 uppercase italic"
                >
                    Butuh Verifikasi
                </p>
                <p class="text-2xl font-black text-amber-700 italic">
                    05 <span class="text-[10px]">Menunggu</span>
                </p>
            </div>
            <div
                class="rounded-3xl border border-emerald-100 bg-emerald-50/50 p-6 shadow-sm"
            >
                <p
                    class="text-[9px] font-black tracking-widest text-emerald-600 uppercase italic"
                >
                    Aktif Bulan Ini
                </p>
                <p class="text-2xl font-black text-emerald-700 italic">
                    +12 <span class="text-[10px]">Baru</span>
                </p>
            </div>
        </div>

        <div
            class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between"
        >
            <div class="flex w-fit gap-2 rounded-2xl bg-slate-100 p-1.5">
                <button
                    @click="activeTab = 'pending'"
                    :class="[
                        activeTab === 'pending'
                            ? 'bg-white text-lokak-brand shadow-sm'
                            : 'text-slate-500 hover:text-lokak-text',
                    ]"
                    class="rounded-xl px-6 py-2.5 text-[10px] font-black uppercase italic transition-all"
                >
                    Menunggu Verifikasi
                </button>
                <button
                    @click="activeTab = 'active'"
                    :class="[
                        activeTab === 'active'
                            ? 'bg-white text-lokak-brand shadow-sm'
                            : 'text-slate-500 hover:text-lokak-text',
                    ]"
                    class="rounded-xl px-6 py-2.5 text-[10px] font-black uppercase italic transition-all"
                >
                    Daftar Mitra Aktif
                </button>
            </div>

            <div class="relative w-full lg:w-80">
                <Search
                    class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400"
                />
                <input
                    type="text"
                    placeholder="Cari nama perusahaan..."
                    class="h-12 w-full rounded-2xl border border-slate-200 bg-white pr-4 pl-10 text-xs font-bold outline-none focus:ring-2 focus:ring-lokak-brand/20"
                />
            </div>
        </div>

        <div
            class="rounded-[2.5rem] border border-slate-200 bg-white p-2 shadow-sm lg:p-6"
        >
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr
                            class="text-left text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase italic"
                        >
                            <th class="px-6 py-4">Informasi Mitra</th>
                            <th class="px-6 py-4">Bidang</th>
                            <th class="px-6 py-4">Tgl Daftar</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Kendali</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr
                            v-for="mitra in mitraList"
                            :key="mitra.id"
                            class="group transition-colors hover:bg-slate-50/50"
                        >
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-100 bg-white font-black text-lokak-brand shadow-sm transition-transform group-hover:scale-110"
                                    >
                                        {{ mitra.avatar }}
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm leading-tight font-black text-lokak-text uppercase"
                                        >
                                            {{ mitra.name }}
                                        </p>
                                        <p
                                            class="text-[10px] font-bold text-slate-400 lowercase italic"
                                        >
                                            {{ mitra.email }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span
                                    class="rounded-lg bg-slate-100 px-3 py-1 text-[10px] font-black text-slate-500 uppercase italic"
                                >
                                    {{ mitra.sector }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-5 text-[11px] font-bold text-slate-400 italic"
                            >
                                {{ mitra.date }}
                            </td>
                            <td class="px-6 py-5">
                                <div
                                    v-if="mitra.status === 'Pending'"
                                    class="flex items-center gap-2 text-amber-500"
                                >
                                    <AlertCircle class="h-4 w-4" />
                                    <span
                                        class="text-[10px] font-black uppercase italic"
                                        >Pending</span
                                    >
                                </div>
                                <div
                                    v-else
                                    class="flex items-center gap-2 text-emerald-500"
                                >
                                    <ShieldCheck class="h-4 w-4" />
                                    <span
                                        class="text-[10px] font-black uppercase italic"
                                        >Verified</span
                                    >
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div
                                    class="flex justify-end gap-2 opacity-0 transition-opacity group-hover:opacity-100"
                                >
                                    <button
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-400 shadow-sm transition-all hover:border-lokak-brand hover:text-lokak-brand"
                                        title="Lihat Profil"
                                    >
                                        <Eye class="h-4 w-4" />
                                    </button>

                                    <button
                                        v-if="mitra.status === 'Pending'"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-lg shadow-emerald-900/20 transition-all hover:bg-emerald-600"
                                        title="Verifikasi Sekarang"
                                    >
                                        <CheckCircle2 class="h-4 w-4" />
                                    </button>

                                    <button
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-rose-100 bg-white text-rose-400 shadow-sm transition-all hover:bg-rose-500 hover:text-white"
                                        title="Hapus Mitra"
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
    </div>
</template>

<style scoped>
.group {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
