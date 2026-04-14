<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    Search,
    Filter,
    FileText,
    CheckCircle2,
    XCircle,
    UserCheck,
    Star,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

// Data Dummy Pelamar yang masuk ke Mitra
const applicants = [
    {
        id: 1,
        name: 'Alip Maulana',
        position: 'Frontend Developer',
        matchScore: 95,
        appliedDate: '14 April 2026',
        status: 'Interview',
        statusStyle: 'bg-emerald-100 text-emerald-700 border-emerald-200',
        avatar: 'AM',
    },
    {
        id: 2,
        name: 'Budi Setiawan',
        position: 'UI/UX Designer',
        matchScore: 82,
        appliedDate: '13 April 2026',
        status: 'Review',
        statusStyle: 'bg-amber-100 text-amber-700 border-amber-200',
        avatar: 'BS',
    },
    {
        id: 3,
        name: 'Siti Aminah',
        position: 'Frontend Developer',
        matchScore: 65,
        appliedDate: '12 April 2026',
        status: 'Ditolak',
        statusStyle: 'bg-rose-100 text-rose-700 border-rose-200',
        avatar: 'SA',
    },
];
</script>

<template>
    <Head title="Kelola Pelamar - Mitra" />

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
                    Seleksi kandidat terbaik untuk tim Anda di Bengkulu.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative w-full sm:w-64">
                    <Search
                        class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400"
                    />
                    <input
                        type="text"
                        placeholder="Cari nama pelamar..."
                        class="h-11 w-full rounded-xl border border-slate-200 bg-white pr-4 pl-10 text-xs font-bold outline-none focus:ring-2 focus:ring-lokak-brand/20"
                    />
                </div>
                <button
                    class="flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition-all hover:text-lokak-brand"
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
                            <th class="px-4 pb-6">Kandidat</th>
                            <th class="px-4 pb-6">Posisi Dilamar</th>
                            <th class="px-4 pb-6 text-center">
                                Skor Kecocokan
                            </th>
                            <th class="px-4 pb-6">Status</th>
                            <th class="px-4 pb-6 text-right">Aksi Seleksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr
                            v-for="applicant in applicants"
                            :key="applicant.id"
                            class="group transition-colors hover:bg-slate-50/50"
                        >
                            <td class="px-4 py-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-2xl border border-sky-100 bg-sky-50 font-black text-lokak-brand shadow-inner transition-all group-hover:bg-white"
                                    >
                                        {{ applicant.avatar }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm leading-tight font-black text-lokak-text uppercase"
                                            >{{ applicant.name }}</span
                                        >
                                        <span
                                            class="text-[10px] font-bold text-slate-400 uppercase italic"
                                            >Tgl Lamar:
                                            {{ applicant.appliedDate }}</span
                                        >
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-6">
                                <span
                                    class="text-[11px] font-black tracking-tight text-lokak-text uppercase italic"
                                    >{{ applicant.position }}</span
                                >
                            </td>

                            <td class="px-4 py-6 text-center">
                                <div
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-3 py-1 text-xs font-black text-lokak-text"
                                >
                                    <Star
                                        class="h-3 w-3 fill-amber-500 text-amber-500"
                                    />
                                    {{ applicant.matchScore }}%
                                </div>
                            </td>

                            <td class="px-4 py-6">
                                <span
                                    :class="[
                                        applicant.statusStyle,
                                        'inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-[9px] font-black tracking-widest uppercase italic',
                                    ]"
                                >
                                    {{ applicant.status }}
                                </span>
                            </td>

                            <td class="px-4 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <button
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-100 bg-white text-slate-400 shadow-sm transition-all hover:border-lokak-brand hover:text-lokak-brand"
                                        title="Lihat CV"
                                    >
                                        <FileText class="h-4 w-4" />
                                    </button>
                                    <button
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-lokak-brand text-white shadow-lg shadow-sky-900/20 transition-all hover:bg-lokak-brand-dark"
                                        title="Panggil Interview"
                                    >
                                        <UserCheck class="h-4 w-4" />
                                    </button>
                                    <button
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-100 bg-white text-slate-400 shadow-sm transition-all hover:text-rose-500"
                                        title="Tolak"
                                    >
                                        <XCircle class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div
            class="rounded-[2.5rem] bg-slate-900 p-8 text-white shadow-xl shadow-slate-900/20"
        >
            <div class="flex flex-col gap-6 md:flex-row md:items-center">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 text-sky-400"
                >
                    <CheckCircle2 class="h-7 w-7" />
                </div>
                <div>
                    <h3
                        class="text-sm font-black tracking-tighter uppercase italic"
                    >
                        Sistem Seleksi Cerdas
                    </h3>
                    <p
                        class="mt-1 text-[10px] leading-relaxed font-medium uppercase italic opacity-60"
                    >
                        Skor kecocokan dihitung berdasarkan kesesuaian antara
                        deskripsi lowongan Anda dengan profil dan portofolio
                        kandidat. Gunakan ini sebagai referensi utama tahap
                        awal.
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
