<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Send,
    MessageSquare,
    Calendar,
    Bookmark,
    Search,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const stats = [
    {
        name: 'Lamaran Terkirim',
        value: '12',
        icon: Send,
        color: 'text-lokak-brand',
    },
    {
        name: 'Panggilan Interview',
        value: '3',
        icon: Calendar,
        color: 'text-amber-500',
    },
    {
        name: 'Pesan Baru',
        value: '5',
        icon: MessageSquare,
        color: 'text-emerald-500',
    },
    { name: 'Disimpan', value: '8', icon: Bookmark, color: 'text-rose-500' },
];

const recentApplications = [
    {
        id: 1,
        position: 'Frontend Developer',
        company: 'Universitas Dehasen',
        date: '12 April 2026',
        status: 'Review',
        statusColor: 'bg-amber-100 text-amber-700',
    },
    {
        id: 2,
        position: 'UI/UX Designer',
        company: 'Code 21 Bengkulu',
        date: '10 April 2026',
        status: 'Interview',
        statusColor: 'bg-emerald-100 text-emerald-700',
    },
    {
        id: 3,
        position: 'Cyber Security',
        company: 'De Code BKL',
        date: '08 April 2026',
        status: 'Ditolak',
        statusColor: 'bg-rose-100 text-rose-700',
    },
];
</script>

<template>
    <Head title="Dashboard Pelamar" />

    <div class="space-y-8 p-6 lg:p-10">
        <div
            class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <h1
                    class="text-3xl font-black tracking-tighter text-lokak-text uppercase italic md:text-4xl"
                >
                    HALO, <span class="text-lokak-brand">ALIP MAULANA</span>
                </h1>
                <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
                <p
                    class="mt-4 text-[11px] font-bold tracking-widest text-lokak-text-muted uppercase italic"
                >
                    Semoga hari ini adalah langkah baru menuju karier impianmu!
                </p>
            </div>

            <div
                class="rounded-2xl border border-sky-100 bg-sky-50 p-4 shadow-sm md:w-72"
            >
                <div class="mb-2 flex items-center justify-between">
                    <span
                        class="text-[10px] font-black text-lokak-brand uppercase italic"
                        >Kelengkapan Profil</span
                    >
                    <span class="text-[10px] font-black text-lokak-brand"
                        >85%</span
                    >
                </div>
                <div class="h-2 w-full rounded-full bg-white shadow-inner">
                    <div
                        class="h-2 rounded-full bg-lokak-brand transition-all"
                        style="width: 85%"
                    ></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div
                v-for="stat in stats"
                :key="stat.name"
                class="group flex items-center gap-5 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:border-sky-200 hover:shadow-lg"
            >
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-slate-100 bg-slate-50 shadow-inner transition-all group-hover:bg-lokak-brand group-hover:text-white"
                >
                    <component :is="stat.icon" class="h-6 w-6" />
                </div>
                <div>
                    <p
                        class="text-[10px] font-black tracking-widest text-lokak-text-muted uppercase italic"
                    >
                        {{ stat.name }}
                    </p>
                    <p class="text-2xl font-black text-lokak-text">
                        {{ stat.value }}
                    </p>
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
                            Lamaran
                            <span class="text-lokak-brand">Terakhir</span>
                        </h2>
                        <Link
                            href="#"
                            class="text-[10px] font-black text-lokak-brand uppercase italic hover:underline"
                        >
                            Lihat Semua →
                        </Link>
                    </div>

                    <div class="space-y-4">
                        <div
                            v-for="app in recentApplications"
                            :key="app.id"
                            class="group flex items-center justify-between rounded-2xl border border-slate-50 bg-slate-50 p-4 transition-all hover:border-slate-200 hover:bg-white hover:shadow-md"
                        >
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-lg shadow-sm"
                                >
                                    💼
                                </div>
                                <div>
                                    <h3
                                        class="text-xs font-black text-lokak-text uppercase group-hover:text-lokak-brand"
                                    >
                                        {{ app.position }}
                                    </h3>
                                    <p
                                        class="text-[9px] font-bold text-lokak-text-muted uppercase"
                                    >
                                        {{ app.company }} • {{ app.date }}
                                    </p>
                                </div>
                            </div>
                            <span
                                :class="[
                                    app.statusColor,
                                    'rounded-lg px-3 py-1 text-[8px] font-black tracking-widest uppercase italic',
                                ]"
                            >
                                {{ app.status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6 lg:col-span-4">
                <div
                    class="rounded-[2.5rem] bg-lokak-brand p-8 text-white shadow-xl shadow-sky-900/10"
                >
                    <h3
                        class="text-xl leading-tight font-black tracking-tighter uppercase italic"
                    >
                        Cari Loker<br />Lagi Yuk!
                    </h3>
                    <p
                        class="mt-2 text-[10px] font-medium tracking-wide uppercase opacity-80"
                    >
                        Ada 150+ lowongan baru di Bengkulu hari ini.
                    </p>
                    <Link
                        href="/lowongan"
                        class="mt-6 flex items-center justify-center gap-2 rounded-xl bg-white py-3 text-[10px] font-black text-lokak-brand uppercase italic shadow-lg transition hover:bg-slate-50 active:scale-95"
                    >
                        <Search class="h-3 w-3" /> JELAJAHI SEKARANG
                    </Link>
                </div>

                <div
                    class="rounded-[2.5rem] border border-slate-200 bg-white p-8"
                >
                    <div class="mb-4 flex items-center gap-3">
                        <div class="h-2 w-2 rounded-full bg-lokak-brand"></div>
                        <h4
                            class="text-[10px] font-black tracking-widest text-lokak-text uppercase italic"
                        >
                            Tips Hari Ini
                        </h4>
                    </div>
                    <p
                        class="text-[11px] leading-relaxed font-bold text-lokak-text-muted italic"
                    >
                        "Pastikan portofolio kamu mencantumkan proyek terbaru di
                        Bengkulu untuk menarik minat mitra lokal."
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Animasi halus */
.group {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
