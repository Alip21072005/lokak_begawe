<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Send,
    MessageSquare,
    Calendar,
    Bookmark,
    MapPin,
    Building2,
} from 'lucide-vue-next';
import { computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

// 1. Definisikan Props dengan tanda ? (Opsional) agar tidak blank putih saat loading
const props = defineProps<{
    auth: {
        user: {
            name: string;
            pelamar: any;
        };
    };
    mitraVerified?: any[];
    statistik?: {
        lamaranTerkirim: number;
        panggilanInterview: number;
        pesanBaru: number;
        lamaranDisimpan: number;
    };
    lamaranTerbaru?: any[];
    persentaseProfil?: number;
}>();

defineOptions({
    layout: AppLayout,
});

// 2. Statistik Dinamis yang Aman (Defensive Programming)
const stats = computed(() => [
    {
        name: 'Lamaran Terkirim',
        value: props.statistik?.lamaranTerkirim ?? 0,
        icon: Send,
        color: 'text-lokak-brand',
    },
    {
        name: 'Panggilan Interview',
        value: props.statistik?.panggilanInterview ?? 0,
        icon: Calendar,
        color: 'text-amber-500',
    },
    {
        name: 'Pesan Baru',
        value: props.statistik?.pesanBaru ?? 0,
        icon: MessageSquare,
        color: 'text-emerald-500',
    },
    { 
        name: 'Disimpan', 
        value: props.statistik?.lamaranDisimpan ?? 0, 
        icon: Bookmark, 
        color: 'text-rose-500' 
    },
]);

// 3. Helper Status Warna
const getStatusBadge = (status: string) => {
    const badges: Record<string, string> = {
        'pending': 'bg-amber-100 text-amber-700',
        'accepted': 'bg-emerald-100 text-emerald-700',
        'rejected': 'bg-rose-100 text-rose-700',
    };

    return badges[status?.toLowerCase()] || 'bg-slate-100 text-slate-700'; 
};
</script>

<template>
    <Head title="Dashboard Pelamar" />

    <div class="space-y-8 p-6 lg:p-10">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tighter text-lokak-text uppercase italic md:text-4xl">
                    HALO,
                    <span class="text-lokak-brand">{{ props.auth.user.name }}</span>
                </h1>
                <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
                <p class="mt-4 text-[11px] font-bold tracking-widest text-lokak-text-muted uppercase italic">
                    Semoga hari ini adalah langkah baru menuju karier impianmu!
                </p>
            </div>

            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-4 shadow-sm md:w-72">
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-[10px] font-black text-lokak-brand uppercase italic">Kelengkapan Profil</span>
                    <span class="text-[10px] font-black text-lokak-brand">{{ props.persentaseProfil ?? 0 }}%</span>
                </div>
                <div class="h-2 w-full rounded-full bg-white shadow-inner">
                    <div
                        class="h-2 rounded-full bg-lokak-brand transition-all"
                        :style="`width: ${props.persentaseProfil ?? 0}%`"
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
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-slate-100 bg-slate-50 shadow-inner transition-all group-hover:bg-lokak-brand group-hover:text-white">
                    <component :is="stat.icon" class="h-6 w-6" />
                </div>
                <div>
                    <p class="text-[10px] font-black tracking-widest text-lokak-text-muted uppercase italic">
                        {{ stat.name }}
                    </p>
                    <p class="text-2xl font-black text-lokak-text">
                        {{ stat.value }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div class="space-y-8 lg:col-span-8">
                
                <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                    <div class="mb-8 flex items-center justify-between">
                        <h2 class="text-lg font-black tracking-tight text-lokak-text uppercase italic">
                            Lamaran <span class="text-lokak-brand">Terakhir</span>
                        </h2>
                        <Link href="/dashboard/pelamar/lamaran" class="text-[10px] font-black text-lokak-brand uppercase italic hover:underline">Lihat Semua →</Link>
                    </div>

                    <div v-if="(props.lamaranTerbaru?.length ?? 0) > 0" class="space-y-4">
                        <div v-for="app in props.lamaranTerbaru" :key="app.id" class="group flex items-center justify-between rounded-2xl border border-slate-50 bg-slate-50 p-4 transition-all hover:border-slate-200 hover:bg-white hover:shadow-md">
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-lg shadow-sm">
                                    💼
                                </div>
                                <div>
                                    <h3 class="text-xs font-black text-lokak-text uppercase group-hover:text-lokak-brand">
                                        {{ app.lowongan?.judul_lowongan ?? 'Posisi Tidak Diketahui' }}
                                    </h3>
                                    <p class="text-[9px] font-bold text-lokak-text-muted uppercase">
                                        {{ app.lowongan?.mitra?.nama_mitra ?? 'Perusahaan Rahasia' }} • 
                                        {{ new Date(app.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                                    </p>
                                </div>
                            </div>
                            <span :class="[getStatusBadge(app.status), 'rounded-lg px-3 py-1 text-[8px] font-black tracking-widest uppercase italic']">
                                {{ app.status }}
                            </span>
                        </div>
                    </div>
                    <div v-else class="py-6 text-center">
                        <p class="text-xs font-bold text-slate-400 italic">Kamu belum mengirim lamaran apapun.</p>
                    </div>
                </div>

                <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                    <div class="mb-8 flex items-center justify-between">
                        <h2 class="text-lg font-black tracking-tight text-lokak-text uppercase italic">
                            Mitra <span class="text-emerald-500">Terverifikasi</span>
                        </h2>
                    </div>

                    <div v-if="(props.mitraVerified?.length ?? 0) > 0" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div v-for="mitra in props.mitraVerified" :key="mitra.id" class="flex flex-col rounded-3xl border border-slate-100 bg-white p-5 transition-all hover:shadow-lg">
                            <div class="flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 text-xl shadow-inner">
                                    <Building2 class="h-6 w-6 text-slate-400" />
                                </div>
                                <div>
                                    <h4 class="text-xs font-black text-lokak-text uppercase">
                                        {{ mitra.nama_mitra }}
                                    </h4>
                                    <div class="flex items-center gap-1 text-[9px] font-bold text-lokak-text-muted uppercase">
                                        <MapPin class="h-3 w-3" />
                                        {{ mitra.lokasi?.nama_lokasi ?? 'Bengkulu' }}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between border-t border-slate-50 pt-4">
                                <span class="text-[9px] font-black text-emerald-500 uppercase italic">Terverifikasi Admin</span>
                                <Link href="#" class="text-[9px] font-black text-lokak-brand uppercase hover:underline">
                                    Profil →
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div v-else class="py-10 text-center text-[11px] font-bold text-slate-400 uppercase italic">
                        Belum ada perusahaan terverifikasi.
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