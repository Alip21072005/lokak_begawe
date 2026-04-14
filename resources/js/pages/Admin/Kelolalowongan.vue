<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    Search,
    Clock,
    Building2,
    Eye,
    ThumbsUp,
    ThumbsDown,
    AlertCircle,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

// Data Dummy Lowongan yang diajukan Mitra
const pendingJobs = [
    {
        id: 1,
        title: 'Fullstack Developer (Laravel & Vue)',
        mitra: 'PT. Bengkulu Teknologi',
        dateSubmitted: '14 April 2026',
        salary: 'Rp 7.000.000 - 10.000.000',
        status: 'Pending',
        statusStyle: 'bg-amber-100 text-amber-700 border-amber-200',
    },
    {
        id: 2,
        title: 'Social Media Specialist',
        mitra: 'Cafe Digital Rejang Lebong',
        dateSubmitted: '13 April 2026',
        salary: 'Rp 3.000.000',
        status: 'Pending',
        statusStyle: 'bg-amber-100 text-amber-700 border-amber-200',
    },
    {
        id: 3,
        title: 'Admin Gudang',
        mitra: 'CV. Mukomuko Makmur',
        dateSubmitted: '12 April 2026',
        salary: 'Rp 3.500.000',
        status: 'Ditolak',
        statusStyle: 'bg-rose-100 text-rose-700 border-rose-200',
    },
];
</script>

<template>
    <Head title="Verifikasi Lowongan - Admin" />

    <div class="space-y-8 p-6 lg:p-10">
        <div
            class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <h1
                    class="text-3xl font-black tracking-tighter text-lokak-text uppercase italic md:text-4xl"
                >
                    VERIFIKASI <span class="text-lokak-brand">LOWONGAN</span>
                </h1>
                <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
                <p
                    class="mt-4 text-[11px] font-bold tracking-widest text-lokak-text-muted uppercase italic"
                >
                    Tinjau dan setujui lowongan kerja yang diajukan oleh mitra
                    perusahaan.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative w-full sm:w-64">
                    <Search
                        class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400"
                    />
                    <input
                        type="text"
                        placeholder="Cari loker atau mitra..."
                        class="h-11 w-full rounded-xl border border-slate-200 bg-white pr-4 pl-10 text-xs font-bold outline-none focus:ring-2 focus:ring-lokak-brand/20"
                    />
                </div>
            </div>
        </div>

        <div
            class="flex items-center gap-4 rounded-4xl border border-amber-100 bg-amber-50/50 p-6"
        >
            <div
                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-lg shadow-amber-900/20"
            >
                <AlertCircle class="h-6 w-6" />
            </div>
            <div>
                <h4
                    class="text-xs font-black tracking-tight text-amber-800 uppercase italic"
                >
                    Perhatian Admin
                </h4>
                <p
                    class="mt-1 text-[10px] leading-tight font-bold text-amber-700/80 uppercase italic"
                >
                    Harap periksa kelengkapan deskripsi dan rentang gaji sebelum
                    memberikan persetujuan tayang.
                </p>
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
                            <th class="px-4 pb-6">Lowongan & Mitra</th>
                            <th class="px-4 pb-6">Gaji Ditawarkan</th>
                            <th class="px-4 pb-6">Tgl Pengajuan</th>
                            <th class="px-4 pb-6">Status</th>
                            <th class="px-4 pb-6 text-right">Aksi Moderasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr
                            v-for="job in pendingJobs"
                            :key="job.id"
                            class="group transition-colors hover:bg-slate-50/50"
                        >
                            <td class="px-4 py-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-lokak-brand shadow-inner transition-all group-hover:bg-white"
                                    >
                                        <Building2 class="h-5 w-5" />
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-black text-lokak-text uppercase transition-colors group-hover:text-lokak-brand"
                                            >{{ job.title }}</span
                                        >
                                        <span
                                            class="text-[10px] font-bold text-slate-400 uppercase italic"
                                            >{{ job.mitra }}</span
                                        >
                                    </div>
                                </div>
                            </td>

                            <td
                                class="px-4 py-6 text-[11px] font-black text-lokak-text uppercase"
                            >
                                {{ job.salary }}
                            </td>

                            <td class="px-4 py-6">
                                <div
                                    class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic"
                                >
                                    <Clock class="h-4 w-4" />
                                    {{ job.dateSubmitted }}
                                </div>
                            </td>

                            <td class="px-4 py-6">
                                <span
                                    :class="[
                                        job.statusStyle,
                                        'inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-[9px] font-black tracking-widest uppercase italic',
                                    ]"
                                >
                                    {{ job.status }}
                                </span>
                            </td>

                            <td class="px-4 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <button
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-100 bg-white text-slate-400 shadow-sm transition-all hover:border-lokak-brand hover:text-lokak-brand"
                                        title="Lihat Detail"
                                    >
                                        <Eye class="h-4 w-4" />
                                    </button>
                                    <button
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-lg shadow-emerald-900/20 transition-all hover:bg-emerald-600"
                                        title="Setujui"
                                    >
                                        <ThumbsUp class="h-4 w-4" />
                                    </button>
                                    <button
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-rose-100 bg-white text-rose-500 shadow-sm transition-all hover:bg-rose-50"
                                        title="Tolak"
                                    >
                                        <ThumbsDown class="h-4 w-4" />
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
tr {
    transition: all 0.2s ease;
}
</style>
