<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    Users,
    Briefcase,
    ShieldCheck,
    AlertCircle,
    Check,
    X,
    Eye,
    BellRing,
    Settings,
} from 'lucide-vue-next';
import { computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    counts?: {
        pelamar: number;
        mitra: number;
        lowongan: number;
    };
}>();

defineOptions({
    layout: AppLayout,
});

// Statistik yang diperluas untuk Admin
const stats = computed(() => [
    {
        name: 'Total Pelamar',
        value: props.counts?.pelamar?.toLocaleString('id-ID') ?? 0,
        description: 'User terdaftar',
        icon: Users,
        color: 'text-lokak-brand',
    },
    {
        name: 'Mitra Terverifikasi',
        value: props.counts?.mitra?.toLocaleString('id-ID') ?? 0,
        description: 'Perusahaan aktif',
        icon: ShieldCheck,
        color: 'text-emerald-500',
    },
    {
        name: 'Total Lowongan',
        value: props.counts?.lowongan?.toLocaleString('id-ID') ?? 0,
        description: 'Loker tayang',
        icon: Briefcase,
        color: 'text-indigo-500',
    },
    {
        name: 'Menunggu Verifikasi',
        value: '14', // Dummy data untuk admin
        description: 'Mitra baru',
        icon: AlertCircle,
        color: 'text-amber-500',
    },
]);

// Data Dummy Antrean Verifikasi Mitra
const pendingMitra = [
    {
        id: 1,
        name: 'PT. Bengkulu Sejahtera',
        sector: 'IT & Telco',
        date: '1 jam yang lalu',
    },
    {
        id: 2,
        name: 'CV. Maju Jaya Mukomuko',
        sector: 'Konstruksi',
        date: '3 jam yang lalu',
    },
    {
        id: 3,
        name: 'Koperasi Manna Makmur',
        sector: 'Perdagangan',
        date: 'Kemarin',
    },
];

// Data Dummy Aktivitas Sistem
const systemActivities = [
    { id: 1, msg: 'User baru "Rian" mendaftar', time: '10m ago' },
    { id: 2, msg: 'Mitra "Code 21" memasang loker', time: '25m ago' },
    { id: 3, msg: 'Admin "Alip" mengubah pengaturan', time: '1h ago' },
];
</script>

<template>
    <Head title="Dashboard Admin" />

    <div class="space-y-8 p-6 lg:p-10">
        <div
            class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <h1
                    class="text-3xl font-black tracking-tighter text-lokak-text uppercase italic md:text-4xl"
                >
                    ADMIN <span class="text-lokak-brand">CONTROL</span>
                </h1>
                <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
                <p
                    class="mt-4 text-[11px] font-bold tracking-widest text-lokak-text-muted uppercase italic"
                >
                    Pusat kendali operasional dan verifikasi platform Lokak
                    Begawe.
                </p>
            </div>

            <div class="flex gap-3">
                <button
                    class="flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 transition-all hover:text-lokak-brand hover:shadow-md"
                >
                    <BellRing class="h-5 w-5" />
                </button>
                <button
                    class="flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 transition-all hover:text-lokak-brand hover:shadow-md"
                >
                    <Settings class="h-5 w-5" />
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div
                v-for="stat in stats"
                :key="stat.name"
                class="group relative overflow-hidden rounded-4xl border border-slate-200 bg-white p-7 shadow-sm transition-all hover:border-sky-200 hover:shadow-xl hover:shadow-sky-900/5"
            >
                <div class="relative z-10 flex flex-col">
                    <p
                        class="text-[9px] font-black tracking-widest text-lokak-text-muted uppercase italic"
                    >
                        {{ stat.name }}
                    </p>
                    <p class="mt-1 text-3xl font-black text-lokak-text">
                        {{ stat.value }}
                    </p>
                    <div class="mt-3 flex items-center gap-2">
                        <div
                            :class="[
                                stat.color,
                                'flex h-8 w-8 items-center justify-center rounded-lg border border-slate-100 bg-slate-50 transition-all group-hover:bg-lokak-brand group-hover:text-white',
                            ]"
                        >
                            <component :is="stat.icon" class="h-4 w-4" />
                        </div>
                        <span
                            class="text-[9px] font-bold text-slate-400 uppercase italic"
                            >{{ stat.description }}</span
                        >
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
                            Verifikasi
                            <span class="text-lokak-brand">Mitra Baru</span>
                        </h2>
                        <span
                            class="rounded-full bg-amber-100 px-3 py-1 text-[9px] font-black text-amber-700 uppercase"
                            >3 Urgent</span
                        >
                    </div>

                    <div class="space-y-4">
                        <div
                            v-for="mitra in pendingMitra"
                            :key="mitra.id"
                            class="flex items-center justify-between rounded-2xl border border-slate-50 bg-slate-50 p-5 transition-all hover:border-slate-200 hover:bg-white hover:shadow-md"
                        >
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-white font-black text-lokak-brand shadow-sm"
                                >
                                    {{ mitra.name.charAt(0) }}
                                </div>
                                <div>
                                    <h3
                                        class="text-xs font-black text-lokak-text uppercase"
                                    >
                                        {{ mitra.name }}
                                    </h3>
                                    <p
                                        class="text-[9px] font-bold text-lokak-text-muted uppercase"
                                    >
                                        {{ mitra.sector }} • {{ mitra.date }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button
                                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-100 bg-white text-slate-400 shadow-sm transition-all hover:border-lokak-brand hover:text-lokak-brand"
                                >
                                    <Eye class="h-4 w-4" />
                                </button>
                                <button
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500 text-white shadow-lg shadow-emerald-900/20 transition-all hover:bg-emerald-600"
                                >
                                    <Check class="h-4 w-4" />
                                </button>
                                <button
                                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-rose-100 bg-white text-rose-500 transition-all hover:bg-rose-50"
                                >
                                    <X class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6 lg:col-span-4">
                <div
                    class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm"
                >
                    <h4
                        class="mb-6 text-[10px] font-black tracking-widest text-lokak-text uppercase italic"
                    >
                        Aktivitas Sistem
                    </h4>
                    <div class="space-y-6">
                        <div
                            v-for="act in systemActivities"
                            :key="act.id"
                            class="flex items-start gap-3 border-l-2 border-slate-100 pl-4 transition-all hover:border-lokak-brand"
                        >
                            <div class="flex flex-col">
                                <span
                                    class="text-[11px] leading-tight font-bold text-lokak-text"
                                    >{{ act.msg }}</span
                                >
                                <span
                                    class="mt-1 text-[9px] font-black text-slate-300 uppercase italic"
                                    >{{ act.time }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.group {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
