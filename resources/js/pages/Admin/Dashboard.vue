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
let timer: any;
onMounted(() => {
    timer = setInterval(() => {
        currentTime.value = new Date().toLocaleTimeString('id-ID');
    }, 1000);
});
onUnmounted(() => clearInterval(timer));

const chartColors = [
    '#3b82f6', '#f97316', '#10b981', '#ef4444', '#8b5cf6', '#ec4899', 
    '#06b6d4', '#f59e0b', '#6366f1', '#84cc16', '#14b8a6', '#d946ef', 
    '#fb7185', '#059669', '#7c3aed', '#475569',
];

const commonOptions: ChartOptions<'line'> = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: true, position: 'bottom', labels: { usePointStyle: true, font: { size: 10, weight: 'bold' as const } } },
    },
};

const doughnutOptions: ChartOptions<'doughnut'> = {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '75%',
    plugins: {
        legend: { display: false },
        tooltip: { backgroundColor: '#1e293b', padding: 12, titleFont: { size: 12, weight: 'bold' as const }, bodyFont: { size: 12 }, cornerRadius: 12 },
    },
};

const lineData = computed(() => ({
    labels: props.registrationTrend.labels,
    datasets: [
        { label: 'Pelamar', borderColor: '#3b82f6', backgroundColor: 'rgba(59, 130, 246, 0.1)', data: props.registrationTrend.pelamar, tension: 0.4, borderWidth: 4, fill: true },
        { label: 'Mitra', borderColor: '#f43f5e', backgroundColor: 'rgba(244, 63, 94, 0.1)', data: props.registrationTrend.mitra, tension: 0.4, borderWidth: 4, fill: true },
    ],
}));

const pieData = computed(() => ({
    labels: props.industryStats.map((i) => i.name),
    datasets: [{ backgroundColor: chartColors, data: props.industryStats.map((i) => i.count), borderWidth: 2, borderColor: '#ffffff', hoverOffset: 15 }],
}));

// --- AKSI ---
const confirmVerifyMitra = (id: string, name: string) => {
    Swal.fire({
        title: 'Verifikasi Mitra?',
        text: `Mitra ${name} akan diizinkan untuk memasang lowongan kerja.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Verifikasi!',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-[2.5rem]' },
    }).then((result) => {
        if (result.isConfirmed) {
            router.patch(`/dashboard/admin/mitra/${id}/status`, { status: 'verified' }, { preserveScroll: true });
        }
    });
};

const confirmDeletePelamar = (id: string, name: string) => {
    Swal.fire({
        title: 'Hapus Permanen?',
        text: `Data akun pelamar ${name} akan dihapus selamanya!`,
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Hapus Data',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-[2.5rem]' },
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('admin.user.delete', id), { preserveScroll: true });
        }
    });
};
</script>

<template>
    <Head title="Dashboard Admin" />

    <div class="min-h-screen space-y-8 bg-slate-50/50 p-6 lg:p-10">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tighter text-slate-900 uppercase italic">
                    ADMIN <span class="text-sky-700">DASHBOARD</span>
                </h1>
                <p class="mt-1 flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic">
                    <Clock class="h-3 w-3" /> {{ currentTime }} WIB • SECURE SESSION
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div
                v-for="s in [
                    { n: 'Pelamar', v: counts.pelamar, i: Users, c: 'text-sky-600', b: 'bg-sky-50' },
                    { n: 'Mitra Aktif', v: counts.mitra, i: ShieldCheck, c: 'text-emerald-600', b: 'bg-emerald-50' },
                    { n: 'Lowongan', v: counts.lowongan, i: Briefcase, c: 'text-indigo-600', b: 'bg-indigo-50' },
                    { n: 'Antrean', v: mitraPending.length, i: AlertCircle, c: 'text-amber-600', b: 'bg-amber-50' },
                ]"
                :key="s.n"
                class="rounded-4xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md"
            >
                <div :class="[s.b, 'mb-4 w-fit rounded-2xl p-3']">
                    <component :is="s.i" :class="[s.c, 'h-5 w-5']" />
                </div>
                <p class="text-[9px] font-black tracking-widest text-slate-400 uppercase">{{ s.n }}</p>
                <h2 class="mt-1 text-3xl font-black text-slate-900">{{ s.v }}</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm lg:col-span-7">
                <div class="mb-8 flex items-center gap-3">
                    <TrendingUp class="h-5 w-5 text-sky-700" />
                    <h2 class="text-sm font-black text-slate-900 uppercase italic">Tren Pendaftaran</h2>
                </div>
                <div class="h-80"><Line :data="lineData" :options="commonOptions" /></div>
            </div>

            <div class="flex flex-col rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm lg:col-span-5">
                <div class="mb-8 flex items-center gap-3">
                    <PieChart class="h-5 w-5 text-emerald-600" />
                    <h2 class="text-sm font-black text-slate-900 uppercase italic">Sebaran Industri</h2>
                </div>
                <div class="flex flex-1 flex-col justify-center gap-6">
                    <div class="relative h-56"><Doughnut :data="pieData" :options="doughnutOptions" /></div>
                    <div class="mt-4 grid max-h-32 grid-cols-2 gap-x-4 gap-y-2 overflow-y-auto px-2">
                        <div v-for="(item, index) in industryStats" :key="index" class="flex items-center gap-2">
                            <div class="h-2.5 w-2.5 shrink-0 rounded-full" :style="{ backgroundColor: chartColors[index % chartColors.length] }"></div>
                            <span class="truncate text-[10px] font-bold text-slate-600 uppercase" :title="item.name">{{ item.name }}</span>
                            <span class="ml-auto text-[10px] font-black text-slate-400">{{ item.count }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                <h2 class="mb-8 text-sm font-black text-slate-900 uppercase italic">Lowongan Terbaru</h2>
                <div class="space-y-4">
                    <div v-for="j in latestJobs" :key="j.id" class="flex items-center justify-between rounded-3xl border border-transparent bg-slate-50 p-4 transition-all hover:border-slate-100">
                        <div class="flex items-center gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white font-black text-indigo-600 uppercase italic shadow-sm">
                                {{ j.mitra?.nama_mitra?.charAt(0) }}
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-slate-900 uppercase line-clamp-1" :title="j.judul_lowongan">
                                    {{ j.judul_lowongan }}
                                </h4>
                                <p class="mt-0.5 text-[9px] font-bold text-slate-400 italic truncate">{{ j.mitra?.nama_mitra }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="hidden sm:block text-[9px] font-black text-slate-300">{{ new Date(j.created_at).toLocaleDateString() }}</span>
                            <Link :href="route('admin.detaillowongan', j.id)" title="Lihat Detail Lowongan" class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 shadow-sm transition-all hover:bg-indigo-600 hover:text-white active:scale-95">
                                <Eye class="h-4 w-4" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                <h2 class="mb-8 text-sm font-black text-slate-900 uppercase italic">Log Aktivitas</h2>
                <div class="relative space-y-6 before:absolute before:left-3 before:h-full before:w-0.5 before:bg-slate-100">
                    <div v-for="(log, idx) in activityLogs" :key="idx" class="relative pl-8">
                        <div class="absolute top-1 left-1.5 h-3 w-3 rounded-full border-2 border-white bg-sky-700"></div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase italic">{{ log.time }}</p>
                        <p class="mt-1 text-[10px] leading-tight font-black text-slate-700 uppercase">{{ log.desc }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                <h2 class="mb-8 text-sm font-black text-slate-900 uppercase italic">Verifikasi Mitra</h2>
                <div class="space-y-4">
                    <div v-if="mitraPending.length === 0" class="py-10 text-center text-[10px] font-black text-slate-300 uppercase italic">
                        Tidak ada antrean mitra
                    </div>
                    <div v-for="m in mitraPending" :key="m.id" class="flex items-center justify-between rounded-3xl border border-amber-100 bg-amber-50/50 p-4 transition-all hover:bg-amber-50">
                        <span class="text-xs font-black text-slate-700 uppercase italic truncate">{{ m.nama_mitra }}</span>
                        <div class="flex gap-2">
                            <Link :href="route('admin.detailmitra', m.id)" title="Lihat Profil Mitra" class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-sky-600 shadow-sm transition-all hover:bg-sky-600 hover:text-white active:scale-95">
                                <Eye class="h-4 w-4" />
                            </Link>
                            <button @click="confirmVerifyMitra(m.id, m.nama_mitra)" title="Verifikasi Mitra" class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-emerald-600 shadow-sm transition-all hover:bg-emerald-600 hover:text-white active:scale-95">
                                <Check class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                <h2 class="mb-8 text-sm font-black text-slate-900 uppercase italic">Pelamar Terbaru</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div v-for="p in pelamarTerbaru" :key="p.id" class="flex items-center justify-between rounded-3xl border border-transparent bg-slate-50 p-4 transition-all hover:border-slate-200 hover:bg-white shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white font-black text-sky-700 uppercase italic shadow-sm">
                                {{ p.name.charAt(0) }}
                            </div>
                            <span class="text-[10px] font-black text-slate-800 uppercase truncate" :title="p.name">{{ p.name }}</span>
                        </div>
                        <div class="flex gap-2">
                            <Link :href="route('admin.detailpelamar', p.id)" title="Lihat Detail Pelamar" class="flex h-8 w-8 items-center justify-center rounded-xl bg-sky-100 text-sky-600 transition-all hover:bg-sky-600 hover:text-white active:scale-95">
                                <Eye class="h-4 w-4" />
                            </Link>
                            <button @click="confirmDeletePelamar(p.id, p.name)" title="Hapus Pelamar" class="flex h-8 w-8 items-center justify-center rounded-xl bg-rose-100 text-rose-600 transition-all hover:bg-rose-600 hover:text-white active:scale-95">
                                <Trash2 class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>