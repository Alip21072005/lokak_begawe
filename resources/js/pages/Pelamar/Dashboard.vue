<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Send,
    MessageSquare,
    Calendar,
    Bookmark,
    Sparkles,
    ChevronRight,
} from 'lucide-vue-next';
import { computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    auth: {
        user: {
            name: string;
            pelamar: any;
        };
    };
    statistik?: {
        lamaranTerkirim: number;
        panggilanInterview: number;
        pesanBaru: number;
        lamaranDisimpan: number;
    };
    lamaranTerbaru?: any[];
    persentaseProfil?: number;
}>();

defineOptions({ layout: AppLayout });

const stats = computed(() => [
    { name: 'Lamaran Terkirim', value: props.statistik?.lamaranTerkirim ?? 0, icon: Send },
    { name: 'Interview', value: props.statistik?.panggilanInterview ?? 0, icon: Calendar },
    { name: 'Pesan Baru', value: props.statistik?.pesanBaru ?? 0, icon: MessageSquare },
    { name: 'Disimpan', value: props.statistik?.lamaranDisimpan ?? 0, icon: Bookmark },
]);

const getStatusBadge = (status: string) => {
    const badges: Record<string, string> = {
        pending: 'bg-amber-100 text-amber-700',
        reviewed: 'bg-blue-100 text-blue-700',
        interview: 'bg-purple-100 text-purple-700',
        accepted: 'bg-emerald-100 text-emerald-700',
        rejected: 'bg-rose-100 text-rose-700',
    };
    return badges[(status || '').toLowerCase()] || 'bg-slate-100 text-slate-700';
};
</script>

<template>
    <Head title="Dashboard Pelamar" />

    <div class="min-h-screen bg-slate-50/50 p-4 md:p-6 lg:p-8">
        <!-- Welcome Section -->
        <section class="relative overflow-hidden rounded-4xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-sky-100/50 blur-3xl"></div>
            <div class="absolute -bottom-10 -left-10 h-32 w-32 rounded-full bg-sky-50/50 blur-2xl"></div>

            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-xl">
                    <div class="mb-2 inline-flex items-center gap-2 rounded-full border border-sky-100 bg-sky-50 px-3 py-1.5">
                        <Sparkles class="h-3 w-3 text-sky-600" />
                        <span class="text-[10px] font-bold uppercase tracking-wider text-sky-700">Selamat Datang Kembali</span>
                    </div>
                    <h1 class="text-3xl font-black tracking-tighter text-slate-900 uppercase italic md:text-4xl lg:text-5xl">
                        Halo, <span class="text-lokak-brand">{{ props.auth.user.name }}</span>
                    </h1>
                    <p class="mt-3 max-w-md text-xs font-medium leading-relaxed text-slate-500">
                        Semoga hari ini membawa langkah baru menuju karier impianmu. Pantau perkembangan lamaran dan temukan peluang terbaik.
                    </p>
                </div>

                <!-- Profile Progress -->
                <div class="w-full rounded-2xl border border-slate-100 bg-slate-50/50 p-5 backdrop-blur-sm sm:w-80">
                    <div class="mb-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-sm">
                                <span class="text-xs font-black text-lokak-brand">{{ props.persentaseProfil ?? 0 }}%</span>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-wide text-slate-600">Profil Lengkap</span>
                        </div>
                        <Link
                            v-if="(props.persentaseProfil ?? 0) < 100"
                            :href="route('settings.profile')"
                            class="text-[10px] font-bold uppercase text-lokak-brand hover:underline"
                        >
                            Lengkapi →
                        </Link>
                    </div>
                    <div class="h-2.5 w-full overflow-hidden rounded-full bg-white shadow-inner">
                        <div
                            class="h-full rounded-full bg-linear-to-r from-sky-500 to-sky-400 transition-all duration-700 ease-out"
                            :style="`width: ${props.persentaseProfil ?? 0}%`"
                        />
                    </div>
                    <p v-if="(props.persentaseProfil ?? 0) < 100" class="mt-2 text-[10px] text-slate-400">
                        Lengkapi profil untuk meningkatkan peluang dilirik recruiter.
                    </p>
                </div>
            </div>
        </section>

        <!-- Stats Grid -->
        <section class="mt-6 grid grid-cols-2 gap-3 md:grid-cols-4 md:gap-4">
            <article
                v-for="(stat, index) in stats"
                :key="stat.name"
                class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-md md:p-5"
                :style="`animation-delay: ${index * 100}ms`"
            >
                <div class="absolute -right-4 -top-4 h-20 w-20 rounded-full bg-sky-50/50 transition-all group-hover:scale-150 group-hover:bg-sky-100/30"></div>
                <div class="relative flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ stat.name }}</p>
                        <p class="mt-1 text-2xl font-black text-slate-800 md:text-3xl">{{ stat.value }}</p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-400 shadow-sm transition-all group-hover:bg-lokak-brand group-hover:text-white group-hover:shadow-lg group-hover:shadow-sky-500/25">
                        <component :is="stat.icon" class="h-5 w-5" />
                    </div>
                </div>
            </article>
        </section>

        <!-- Recent Applications -->
        <section class="mt-6 rounded-4xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                        <Send class="h-5 w-5" />
                    </div>
                    <div>
                        <h2 class="text-base font-black uppercase italic tracking-tight text-slate-900 md:text-lg">
                            Lamaran <span class="text-lokak-brand">Terakhir</span>
                        </h2>
                        <p class="text-[10px] font-bold text-slate-400">Pantau status lamaran terbarumu</p>
                    </div>
                </div>
                <Link
                    :href="route('pelamar.lamaran')"
                    class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-[10px] font-bold uppercase tracking-wide text-slate-600 transition hover:border-lokak-brand hover:text-lokak-brand"
                >
                    Lihat Semua
                    <ChevronRight class="h-3.5 w-3.5" />
                </Link>
            </div>

            <!-- Applications List -->
            <div v-if="(props.lamaranTerbaru?.length ?? 0) > 0" class="space-y-2">
                <article
                    v-for="app in props.lamaranTerbaru"
                    :key="app.id"
                    class="group flex flex-col gap-3 rounded-xl border border-slate-100 bg-slate-50/50 p-4 transition-all hover:border-sky-200 hover:bg-white hover:shadow-sm sm:flex-row sm:items-center sm:justify-between"
                >
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white text-sm font-black text-sky-600 shadow-sm">
                            {{ app.lowongan?.mitra?.nama_mitra?.substring(0, 2).toUpperCase() ?? 'CP' }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-black uppercase text-slate-800 group-hover:text-lokak-brand transition-colors">
                                {{ app.lowongan?.judul_lowongan ?? 'Posisi Tidak Diketahui' }}
                            </p>
                            <p class="truncate text-[10px] font-bold uppercase text-slate-400">
                                {{ app.lowongan?.mitra?.nama_mitra ?? 'Perusahaan' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between gap-3 sm:justify-end">
                        <span class="text-[10px] font-bold uppercase text-slate-400">
                            {{ new Date(app.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }) }}
                        </span>
                        <span
                            :class="[getStatusBadge(app.status), 'rounded-lg px-3 py-1.5 text-[10px] font-black uppercase tracking-wide']"
                        >
                            {{ app.status }}
                        </span>
                    </div>
                </article>
            </div>

            <!-- Empty State -->
            <div v-else class="flex flex-col items-center justify-center py-12 text-center">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100">
                    <Send class="h-8 w-8 text-slate-300" />
                </div>
                <h3 class="text-sm font-black uppercase italic text-slate-500">Belum Ada Lamaran</h3>
                <p class="mt-1 max-w-xs text-[10px] font-bold text-slate-400">
                    Kamu belum mengirim lamaran apapun. Temukan lowongan yang sesuai dengan keahlianmu.
                </p>
                <Link
                    :href="route('pelamar.lowongankerja')"
                    class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-lokak-brand px-5 py-2.5 text-[10px] font-black uppercase italic text-white shadow-lg shadow-sky-500/25 transition hover:bg-sky-600"
                >
                    Cari Lowongan
                    <ChevronRight class="h-3.5 w-3.5" />
                </Link>
            </div>
        </section>
    </div>
</template>