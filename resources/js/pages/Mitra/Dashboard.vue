<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Plus,
    Users,
    FileText,
    UserCheck,
    ArrowUpRight,
    MoreHorizontal,
    Search,
    BarChart3,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const stats = [
    {
        name: 'Lowongan Aktif',
        value: '08',
        icon: FileText,
        color: 'text-lokak-brand',
    },
    {
        name: 'Total Pelamar',
        value: '142',
        icon: Users,
        color: 'text-indigo-500',
    },
    {
        name: 'Perlu Ditinjau',
        value: '24',
        icon: Search,
        color: 'text-amber-500',
    },
    {
        name: 'Kandidat Cocok',
        value: '12',
        icon: UserCheck,
        color: 'text-emerald-500',
    },
];

// Data Dummy Pelamar Terbaru
const recentApplicants = [
    {
        id: 1,
        name: 'Budi Setiawan',
        position: 'Frontend Developer',
        date: 'Hari ini',
        avatar: 'BS',
    },
    {
        id: 2,
        name: 'Siti Aminah',
        position: 'UI/UX Designer',
        date: 'Kemarin',
        avatar: 'SA',
    },
    {
        id: 3,
        name: 'Rian Pratama',
        position: 'Cyber Security',
        date: '2 hari lalu',
        avatar: 'RP',
    },
];
</script>

<template>
    <Head title="Dashboard Mitra" />

    <div class="space-y-8 p-6 lg:p-10">
        <div
            class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <h1
                    class="text-3xl font-black tracking-tighter text-lokak-text uppercase italic md:text-4xl"
                >
                    MITRA <span class="text-lokak-brand">DASHBOARD</span>
                </h1>
                <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
                <p
                    class="mt-4 text-[11px] font-bold tracking-widest text-lokak-text-muted uppercase italic"
                >
                    Selamat datang, Universitas Dehasen! Mari temukan talenta
                    terbaik hari ini.
                </p>
            </div>

            <Link
                href="#"
                class="flex items-center justify-center gap-2 rounded-2xl bg-lokak-brand px-8 py-4 text-xs font-black text-white uppercase italic shadow-lg shadow-sky-900/20 transition-all hover:-translate-y-1 hover:bg-lokak-brand-dark active:scale-95"
            >
                <Plus class="h-4 w-4" /> PASANG LOWONGAN BARU
            </Link>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div
                v-for="stat in stats"
                :key="stat.name"
                class="group relative overflow-hidden rounded-4xl border border-slate-200 bg-white p-7 shadow-sm transition-all hover:border-sky-200 hover:shadow-xl hover:shadow-sky-900/5"
            >
                <div class="relative z-10 flex items-center gap-5">
                    <div
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-slate-100 bg-slate-50 shadow-inner transition-all group-hover:bg-lokak-brand group-hover:text-white"
                    >
                        <component :is="stat.icon" class="h-7 w-7" />
                    </div>
                    <div>
                        <p
                            class="text-[10px] font-black tracking-widest text-lokak-text-muted uppercase italic"
                        >
                            {{ stat.name }}
                        </p>
                        <p class="text-3xl font-black text-lokak-text">
                            {{ stat.value }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div class="lg:col-span-8">
                <div
                    class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm"
                >
                    <div class="mb-8 flex items-center justify-between">
                        <h2
                            class="text-lg font-black tracking-tight text-lokak-text uppercase italic"
                        >
                            Pelamar
                            <span class="text-lokak-brand">Terbaru</span>
                        </h2>
                        <Link
                            href="#"
                            class="text-[10px] font-black text-lokak-brand uppercase italic hover:underline"
                        >
                            Kelola Semua Pelamar →
                        </Link>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr
                                    class="border-b border-slate-100 text-left text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                                >
                                    <th class="pb-4">Nama Pelamar</th>
                                    <th class="pb-4">Posisi</th>
                                    <th class="pb-4">Tanggal</th>
                                    <th class="pb-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr
                                    v-for="applicant in recentApplicants"
                                    :key="applicant.id"
                                    class="group transition-colors hover:bg-slate-50"
                                >
                                    <td class="py-5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-9 w-9 items-center justify-center rounded-full bg-sky-100 text-[10px] font-black text-lokak-brand"
                                            >
                                                {{ applicant.avatar }}
                                            </div>
                                            <span
                                                class="text-xs font-black text-lokak-text"
                                                >{{ applicant.name }}</span
                                            >
                                        </div>
                                    </td>
                                    <td
                                        class="py-5 text-[11px] font-bold text-lokak-text-muted uppercase"
                                    >
                                        {{ applicant.position }}
                                    </td>
                                    <td
                                        class="py-5 text-[11px] font-bold text-slate-400 italic"
                                    >
                                        {{ applicant.date }}
                                    </td>
                                    <td class="py-5 text-right">
                                        <button
                                            class="rounded-lg p-2 text-slate-400 transition-all hover:bg-white hover:text-lokak-brand hover:shadow-sm"
                                        >
                                            <MoreHorizontal class="h-5 w-5" />
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-6 lg:col-span-4">
                <div
                    class="rounded-[2.5rem] border border-slate-200 bg-white p-8"
                >
                    <div class="mb-6 flex items-center gap-3">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600"
                        >
                            <BarChart3 class="h-4 w-4" />
                        </div>
                        <h4
                            class="text-[11px] font-black tracking-widest text-lokak-text uppercase italic"
                        >
                            Performa Lowongan
                        </h4>
                    </div>

                    <div class="space-y-6">
                        <div v-for="n in 2" :key="n" class="space-y-2">
                            <div
                                class="flex justify-between text-[10px] font-bold uppercase italic"
                            >
                                <span class="text-lokak-text"
                                    >Frontend Dev</span
                                >
                                <span class="text-lokak-brand">80% Reach</span>
                            </div>
                            <div class="h-1.5 w-full rounded-full bg-slate-100">
                                <div
                                    class="h-1.5 rounded-full bg-lokak-brand"
                                    :style="{ width: n === 1 ? '80%' : '45%' }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-[2.5rem] bg-slate-900 p-8 text-white shadow-xl"
                >
                    <div
                        class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-white/10"
                    >
                        <ArrowUpRight class="h-5 w-5 text-sky-400" />
                    </div>
                    <h3
                        class="text-sm font-black tracking-tighter uppercase italic"
                    >
                        Temukan Kandidat<br />Lebih Cepat
                    </h3>
                    <p
                        class="mt-2 text-[10px] leading-relaxed font-medium uppercase italic opacity-60"
                    >
                        Gunakan fitur "Shortlist" untuk menandai kandidat yang
                        paling sesuai dengan kriteria teknis perusahaan Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.group {
    transition: all 0.2s ease;
}
</style>
