<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    CategoryScale,
    LinearScale,
    PointElement,
    ArcElement,
    Filler,
} from 'chart.js';
import type { ChartOptions } from 'chart.js';
import {
    Users,
    Briefcase,
    ShieldCheck,
    AlertCircle,
    Check,
    Clock,
    Trash2,
    TrendingUp,
    PieChart,
    Eye,
    Building2,
    Activity,
    Sparkles,
    RefreshCw,
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Line, Doughnut } from 'vue-chartjs';
import AppLayout from '@/layouts/AppLayout.vue';

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    LineElement,
    CategoryScale,
    LinearScale,
    PointElement,
    ArcElement,
    Filler,
);

interface Props {
    auth: any;
    counts: { pelamar: number; mitra: number; lowongan: number };
    mitraPending: any[];
    pelamarTerbaru: any[];
    registrationTrend: { labels: string[]; pelamar: number[]; mitra: number[] };
    industryStats: { name: string; count: number }[];
    activityLogs: { desc: string; time: string }[];
    latestJobs: any[];
}

const props = defineProps<Props>();
defineOptions({ layout: AppLayout });

const currentTime = ref(new Date().toLocaleTimeString('id-ID'));
let timer: ReturnType<typeof setInterval> | null = null;

onMounted(() => {
    timer = setInterval(() => {
        currentTime.value = new Date().toLocaleTimeString('id-ID');
    }, 1000);
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
});

const chartColors = [
    '#3b82f6', '#f97316', '#10b981', '#ef4444', '#8b5cf6', '#ec4899',
    '#06b6d4', '#f59e0b', '#6366f1', '#84cc16', '#14b8a6', '#d946ef',
];

const statsCards = computed(() => [
    { key: 'pelamar', label: 'Pelamar', value: props.counts?.pelamar ?? 0, icon: Users, color: 'text-sky-600', bg: 'bg-sky-50' },
    { key: 'mitra', label: 'Mitra Aktif', value: props.counts?.mitra ?? 0, icon: ShieldCheck, color: 'text-emerald-600', bg: 'bg-emerald-50' },
    { key: 'lowongan', label: 'Lowongan', value: props.counts?.lowongan ?? 0, icon: Briefcase, color: 'text-indigo-600', bg: 'bg-indigo-50' },
    { key: 'pending', label: 'Antrean Verifikasi', value: props.mitraPending?.length ?? 0, icon: AlertCircle, color: 'text-amber-600', bg: 'bg-amber-50' },
]);

const commonOptions: ChartOptions<'line'> = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: { mode: 'index', intersect: false },
    plugins: {
        legend: {
            display: true,
            position: 'bottom',
            labels: { usePointStyle: true, font: { size: 11, weight: 'bold' as const } },
        },
        tooltip: {
            backgroundColor: '#0f172a',
            titleFont: { size: 12, weight: 'bold' as const },
            bodyFont: { size: 12 },
            cornerRadius: 10,
            padding: 10,
        },
    },
    scales: {
        x: {
            grid: { display: false },
            ticks: { color: '#64748b', font: { size: 10 } },
        },
        y: {
            beginAtZero: true,
            grid: { color: '#e2e8f0' },
            ticks: { color: '#64748b', font: { size: 10 }, precision: 0 },
        },
    },
};

const doughnutOptions: ChartOptions<'doughnut'> = {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '72%',
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: '#0f172a',
            cornerRadius: 10,
            padding: 10,
            titleFont: { size: 12, weight: 'bold' as const },
            bodyFont: { size: 12 },
        },
    },
};

const lineData = computed(() => ({
    labels: props.registrationTrend?.labels ?? [],
    datasets: [
        {
            label: 'Pelamar',
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.12)',
            data: props.registrationTrend?.pelamar ?? [],
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 2,
            fill: true,
        },
        {
            label: 'Mitra',
            borderColor: '#f43f5e',
            backgroundColor: 'rgba(244, 63, 94, 0.12)',
            data: props.registrationTrend?.mitra ?? [],
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 2,
            fill: true,
        },
    ],
}));

const pieData = computed(() => ({
    labels: (props.industryStats ?? []).map((i) => i.name),
    datasets: [
        {
            backgroundColor: chartColors,
            data: (props.industryStats ?? []).map((i) => i.count),
            borderWidth: 2,
            borderColor: '#ffffff',
            hoverOffset: 10,
        },
    ],
}));

const totalIndustryCount = computed(() => {
    return (props.industryStats ?? []).reduce((sum, item) => sum + (item.count || 0), 0);
});

const refreshPage = () => router.reload({ preserveScroll: true });

// --- AKSI ---
const confirmVerifyMitra = (id: string, name: string) => {
    Swal.fire({
        title: 'Verifikasi mitra?',
        text: `${name} akan diizinkan memasang lowongan.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, verifikasi',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-2xl' },
    }).then((result) => {
        if (!result.isConfirmed) return;

        router.patch(
            route('admin.mitra.update-status', id),
            { status: 'verified' },
            {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire({
                        title: 'Berhasil',
                        text: 'Mitra sudah diverifikasi.',
                        icon: 'success',
                        confirmButtonColor: '#059669',
                        customClass: { popup: 'rounded-2xl' },
                    });
                },
            },
        );
    });
};

const confirmDeletePelamar = (id: string, name: string) => {
    Swal.fire({
        title: 'Hapus akun pelamar?',
        text: `Akun ${name} akan dihapus permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-2xl' },
    }).then((result) => {
        if (!result.isConfirmed) return;

        router.delete(route('admin.user.delete', id), {
            preserveScroll: true,
            onSuccess: () => {
                Swal.fire({
                    title: 'Terhapus',
                    text: 'Data pelamar berhasil dihapus.',
                    icon: 'success',
                    confirmButtonColor: '#0f172a',
                    customClass: { popup: 'rounded-2xl' },
                });
            },
        });
    });
};
</script>

<template>
    <Head title="Dashboard Admin" />

    <div class="min-h-screen space-y-6 bg-slate-50 p-4 md:p-6 lg:p-8">
        <!-- Header -->
        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-black uppercase italic tracking-tight text-slate-900 md:text-3xl">
                        ADMIN <span class="text-sky-700">DASHBOARD</span>
                    </h1>
                    <p class="mt-1 inline-flex items-center gap-2 text-[11px] font-bold uppercase italic tracking-wide text-slate-500">
                        <Clock class="h-3.5 w-3.5" />
                        {{ currentTime }} WIB
                        <span class="text-slate-300">•</span>
                        Secure Session
                    </p>
                </div>

                <button
                    type="button"
                    @click="refreshPage"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold uppercase tracking-wide text-slate-600 transition hover:bg-slate-100"
                >
                    <RefreshCw class="h-4 w-4" />
                    Refresh Data
                </button>
            </div>
        </section>

        <!-- Stats -->
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article
                v-for="card in statsCards"
                :key="card.key"
                class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md"
            >
                <div :class="[card.bg, 'mb-4 inline-flex rounded-2xl p-3']">
                    <component :is="card.icon" :class="[card.color, 'h-5 w-5']" />
                </div>
                <p class="text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">
                    {{ card.label }}
                </p>
                <h2 class="mt-1 text-3xl font-black text-slate-900">
                    {{ card.value }}
                </h2>
            </article>
        </section>

        <!-- Charts -->
        <section class="grid grid-cols-1 gap-6 xl:grid-cols-12">
            <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm md:p-6 xl:col-span-7">
                <div class="mb-5 flex items-center gap-2">
                    <TrendingUp class="h-5 w-5 text-sky-700" />
                    <h3 class="text-sm font-black uppercase italic text-slate-900">Tren Pendaftaran</h3>
                </div>
                <div class="h-72 md:h-80">
                    <Line :data="lineData" :options="commonOptions" />
                </div>
            </article>

            <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm md:p-6 xl:col-span-5">
                <div class="mb-5 flex items-center gap-2">
                    <PieChart class="h-5 w-5 text-emerald-600" />
                    <h3 class="text-sm font-black uppercase italic text-slate-900">Sebaran Industri</h3>
                </div>

                <div v-if="industryStats?.length" class="grid gap-4 md:grid-cols-2">
                    <div class="relative h-56 md:h-64">
                        <Doughnut :data="pieData" :options="doughnutOptions" />
                        <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
                            <div class="rounded-full bg-white/90 px-3 py-1 text-center shadow-sm">
                                <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Total</p>
                                <p class="text-sm font-black text-slate-700">{{ totalIndustryCount }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="max-h-64 space-y-2 overflow-y-auto pr-1">
                        <div
                            v-for="(item, index) in industryStats"
                            :key="`${item.name}-${index}`"
                            class="flex items-center gap-2 rounded-lg border border-slate-100 bg-slate-50 px-2.5 py-2"
                        >
                            <span
                                class="h-2.5 w-2.5 shrink-0 rounded-full"
                                :style="{ backgroundColor: chartColors[index % chartColors.length] }"
                            />
                            <span class="truncate text-[11px] font-semibold text-slate-700">{{ item.name }}</span>
                            <span class="ml-auto text-[11px] font-black text-slate-500">{{ item.count }}</span>
                        </div>
                    </div>
                </div>

                <div v-else class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-10 text-center">
                    <p class="text-sm font-semibold text-slate-500">Belum ada data industri.</p>
                </div>
            </article>
        </section>

        <!-- Mid Content -->
        <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
                <div class="mb-5 flex items-center gap-2">
                    <Briefcase class="h-5 w-5 text-indigo-600" />
                    <h3 class="text-sm font-black uppercase italic text-slate-900">Lowongan Terbaru</h3>
                </div>

                <div v-if="latestJobs?.length" class="space-y-3">
                    <div
                        v-for="job in latestJobs"
                        :key="job.id"
                        class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-3 transition hover:border-slate-200 hover:bg-white"
                    >
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white font-black uppercase text-indigo-600 shadow-sm">
                                {{ job?.mitra?.nama_mitra?.charAt(0) || 'M' }}
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-xs font-black uppercase text-slate-900">
                                    {{ job?.judul_lowongan || '-' }}
                                </p>
                                <p class="truncate text-[10px] font-semibold text-slate-500">
                                    {{ job?.mitra?.nama_mitra || 'Mitra tidak diketahui' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="hidden text-[10px] font-bold text-slate-400 sm:inline">
                                {{ new Date(job.created_at).toLocaleDateString('id-ID') }}
                            </span>
                            <Link
                                :href="route('admin.detaillowongan', job.id)"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 transition hover:bg-indigo-600 hover:text-white"
                                title="Lihat detail lowongan"
                            >
                                <Eye class="h-4 w-4" />
                            </Link>
                        </div>
                    </div>
                </div>

                <div v-else class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-10 text-center">
                    <p class="text-sm font-semibold text-slate-500">Belum ada lowongan terbaru.</p>
                </div>
            </article>

            <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
                <div class="mb-5 flex items-center gap-2">
                    <Activity class="h-5 w-5 text-sky-700" />
                    <h3 class="text-sm font-black uppercase italic text-slate-900">Log Aktivitas</h3>
                </div>

                <div v-if="activityLogs?.length" class="relative space-y-4 before:absolute before:left-2.5 before:top-1 before:h-[calc(100%-8px)] before:w-px before:bg-slate-200">
                    <div v-for="(log, idx) in activityLogs" :key="idx" class="relative pl-7">
                        <span class="absolute left-0 top-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-sky-700 text-white">
                            <Sparkles class="h-3 w-3" />
                        </span>
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">{{ log.time }}</p>
                        <p class="mt-0.5 text-xs font-semibold text-slate-700">{{ log.desc }}</p>
                    </div>
                </div>

                <div v-else class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-10 text-center">
                    <p class="text-sm font-semibold text-slate-500">Belum ada aktivitas terbaru.</p>
                </div>
            </article>
        </section>

        <!-- Bottom Content -->
        <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
                <div class="mb-5 flex items-center gap-2">
                    <Building2 class="h-5 w-5 text-amber-600" />
                    <h3 class="text-sm font-black uppercase italic text-slate-900">Verifikasi Mitra</h3>
                </div>

                <div v-if="mitraPending?.length" class="space-y-3">
                    <div
                        v-for="mitra in mitraPending"
                        :key="mitra.id"
                        class="flex items-center justify-between gap-3 rounded-2xl border border-amber-100 bg-amber-50 p-3"
                    >
                        <div class="min-w-0">
                            <p class="truncate text-xs font-black uppercase text-slate-800">
                                {{ mitra.nama_mitra || '-' }}
                            </p>
                            <p class="text-[10px] font-semibold text-slate-500">
                                Menunggu verifikasi
                            </p>
                        </div>

                        <div class="flex shrink-0 items-center gap-2">
                            <Link
                                :href="route('admin.detailmitra', mitra.id)"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-white text-sky-600 shadow-sm transition hover:bg-sky-600 hover:text-white"
                                title="Lihat detail mitra"
                            >
                                <Eye class="h-4 w-4" />
                            </Link>
                            <button
                                type="button"
                                @click="confirmVerifyMitra(mitra.id, mitra.nama_mitra)"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-white text-emerald-600 shadow-sm transition hover:bg-emerald-600 hover:text-white"
                                title="Verifikasi mitra"
                            >
                                <Check class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>

                <div v-else class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-10 text-center">
                    <p class="text-sm font-semibold text-slate-500">Tidak ada antrean verifikasi mitra.</p>
                </div>
            </article>

            <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
                <div class="mb-5 flex items-center gap-2">
                    <Users class="h-5 w-5 text-sky-700" />
                    <h3 class="text-sm font-black uppercase italic text-slate-900">Pelamar Terbaru</h3>
                </div>

                <div v-if="pelamarTerbaru?.length" class="space-y-3">
                    <div
                        v-for="pelamar in pelamarTerbaru"
                        :key="pelamar.id"
                        class="flex items-center justify-between gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-3 transition hover:border-slate-200 hover:bg-white"
                    >
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white font-black uppercase text-sky-700 shadow-sm">
                                {{ pelamar?.name?.charAt(0) || 'P' }}
                            </div>
                            <p class="truncate text-xs font-black uppercase text-slate-800" :title="pelamar.name">
                                {{ pelamar.name || '-' }}
                            </p>
                        </div>

                        <div class="flex shrink-0 items-center gap-2">
                            <Link
                                :href="route('detail.pelamar', pelamar.id)"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-sky-100 text-sky-600 transition hover:bg-sky-600 hover:text-white"
                                title="Lihat detail pelamar"
                            >
                                <Eye class="h-4 w-4" />
                            </Link>
                            <button
                                type="button"
                                @click="confirmDeletePelamar(pelamar.id, pelamar.name)"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-rose-100 text-rose-600 transition hover:bg-rose-600 hover:text-white"
                                title="Hapus pelamar"
                            >
                                <Trash2 class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>

                <div v-else class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-10 text-center">
                    <p class="text-sm font-semibold text-slate-500">Belum ada pelamar terbaru.</p>
                </div>
            </article>
        </section>
    </div>
</template>