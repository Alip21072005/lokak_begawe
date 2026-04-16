<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    Search,
    MapPin,
    DollarSign,
    Briefcase,
    Clock,
    Bookmark,
    ChevronRight,
    SlidersHorizontal,
    Building2,
    Zap,
} from 'lucide-vue-next';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const searchQuery = ref('');

// Data Dummy Rekomendasi Lowongan
const recommendedJobs = [
    {
        id: 1,
        title: 'Frontend Vue Developer',
        company: 'PT. Teknologi Bengkulu',
        location: 'Kota Bengkulu',
        salary: 'Rp 4.000.000 - 6.000.000',
        type: 'Full-time',
        postedAt: '2 jam lalu',
        isHot: true,
        logo: 'TB',
    },
    {
        id: 2,
        title: 'UI/UX Designer',
        company: 'Kreatif Studio Digital',
        location: 'Rejang Lebong (Remote)',
        salary: 'Rp 3.500.000 - 5.000.000',
        type: 'Contract',
        postedAt: '1 hari lalu',
        isHot: false,
        logo: 'KS',
    },
    {
        id: 3,
        title: 'Cyber Security Staff',
        company: 'Universitas Dehasen',
        location: 'Kota Bengkulu',
        salary: 'Dirahasiakan',
        type: 'Full-time',
        postedAt: '2 hari lalu',
        isHot: false,
        logo: 'UD',
    },
];
</script>

<template>
    <Head title="Cari Lowongan - Lokak Begawe" />

    <div class="space-y-8 p-6 lg:p-10">
        <div
            class="flex flex-col gap-8 md:flex-row md:items-end md:justify-between"
        >
            <div>
                <h1
                    class="text-3xl font-black tracking-tighter text-lokak-text uppercase italic md:text-4xl"
                >
                    REKOMENDASI <span class="text-lokak-brand">LOWONGAN</span>
                </h1>
                <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
                <p
                    class="mt-4 text-[11px] font-bold tracking-widest text-lokak-text-muted uppercase italic"
                >
                    Temukan karir impianmu dari ratusan mitra kami di Bengkulu.
                </p>
            </div>

            <div class="flex w-full items-center gap-3 md:w-96">
                <div class="relative w-full">
                    <Search
                        class="absolute top-1/2 left-4 h-4 w-4 -translate-y-1/2 text-slate-400"
                    />
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari posisi atau perusahaan..."
                        class="h-12 w-full rounded-2xl border border-slate-200 bg-white pr-4 pl-11 text-xs font-bold transition-all outline-none focus:border-lokak-brand focus:ring-2 focus:ring-lokak-brand/20"
                    />
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div class="hidden space-y-6 lg:col-span-3 lg:block">
                <div
                    class="rounded-[2.5rem] border border-slate-200 bg-white p-6 shadow-sm"
                >
                    <div
                        class="mb-4 flex items-center gap-2 border-b border-slate-100 pb-4"
                    >
                        <SlidersHorizontal class="h-4 w-4 text-lokak-brand" />
                        <h3
                            class="text-xs font-black tracking-widest text-lokak-text uppercase italic"
                        >
                            Filter Pencarian
                        </h3>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                                >Tipe Pekerjaan</label
                            >
                            <div
                                class="flex flex-col gap-2 text-[11px] font-bold text-slate-500 uppercase"
                            >
                                <label
                                    class="flex cursor-pointer items-center gap-2"
                                    ><input
                                        type="checkbox"
                                        class="rounded text-lokak-brand focus:ring-lokak-brand"
                                        checked
                                    />
                                    Full-time</label
                                >
                                <label
                                    class="flex cursor-pointer items-center gap-2"
                                    ><input
                                        type="checkbox"
                                        class="rounded text-lokak-brand focus:ring-lokak-brand"
                                    />
                                    Part-time</label
                                >
                                <label
                                    class="flex cursor-pointer items-center gap-2"
                                    ><input
                                        type="checkbox"
                                        class="rounded text-lokak-brand focus:ring-lokak-brand"
                                    />
                                    Internship</label
                                >
                            </div>
                        </div>

                        <div class="pt-2">
                            <button
                                class="w-full rounded-xl bg-slate-100 py-3 text-[10px] font-black text-slate-500 uppercase italic transition-all hover:bg-slate-200"
                            >
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-[2.5rem] bg-lokak-brand p-6 text-white shadow-xl shadow-sky-900/20"
                >
                    <div
                        class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-white/20"
                    >
                        <Zap class="h-5 w-5 text-yellow-300" />
                    </div>
                    <h3
                        class="text-xs font-black tracking-tighter uppercase italic"
                    >
                        Match Profile!
                    </h3>
                    <p
                        class="mt-2 text-[10px] leading-relaxed font-medium uppercase italic opacity-80"
                    >
                        Lowongan di halaman ini telah disesuaikan dengan skill
                        dan minat pada profil Anda.
                    </p>
                </div>
            </div>

            <div class="space-y-4 lg:col-span-9">
                <div
                    v-for="job in recommendedJobs"
                    :key="job.id"
                    class="group relative flex flex-col justify-between gap-6 overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white p-6 shadow-sm transition-all hover:border-sky-200 hover:shadow-xl sm:flex-row sm:items-center"
                >
                    <div class="flex items-start gap-5">
                        <div
                            class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl border border-slate-100 bg-slate-50 text-xl font-black text-lokak-brand shadow-inner transition-colors group-hover:bg-white"
                        >
                            {{ job.logo }}
                        </div>
                        <div class="flex flex-col">
                            <div class="mb-1 flex items-center gap-2">
                                <h2
                                    class="text-base font-black text-lokak-text uppercase transition-colors group-hover:text-lokak-brand"
                                >
                                    {{ job.title }}
                                </h2>
                                <span
                                    v-if="job.isHot"
                                    class="rounded-lg bg-rose-100 px-2 py-0.5 text-[8px] font-black text-rose-600 uppercase italic"
                                    >HOT</span
                                >
                            </div>

                            <p
                                class="mb-3 flex items-center gap-1.5 text-[10px] font-bold text-slate-500 uppercase italic"
                            >
                                <Building2 class="h-3 w-3" /> {{ job.company }}
                            </p>

                            <div
                                class="flex flex-wrap items-center gap-3 text-[10px] font-bold text-slate-400 uppercase italic"
                            >
                                <span class="flex items-center gap-1"
                                    ><MapPin class="h-3 w-3 text-lokak-brand" />
                                    {{ job.location }}</span
                                >
                                <span class="flex items-center gap-1"
                                    ><Briefcase
                                        class="h-3 w-3 text-emerald-500"
                                    />
                                    {{ job.type }}</span
                                >
                                <span class="flex items-center gap-1"
                                    ><DollarSign
                                        class="h-3 w-3 text-amber-500"
                                    />
                                    {{ job.salary }}</span
                                >
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex items-center gap-3 sm:flex-col sm:items-end"
                    >
                        <button
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-400 transition-all hover:border-lokak-brand hover:text-lokak-brand"
                        >
                            <Bookmark class="h-4 w-4" />
                        </button>
                        <button
                            class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-slate-900 px-6 py-3 text-[10px] font-black text-white uppercase italic shadow-lg transition-all hover:bg-lokak-brand active:scale-95 sm:flex-none"
                        >
                            LIHAT DETAIL <ChevronRight class="h-3 w-3" />
                        </button>
                        <span
                            class="absolute top-6 right-6 text-[9px] font-bold text-slate-300 uppercase italic sm:static"
                        >
                            <Clock class="mr-1 mb-0.5 inline h-3 w-3" />{{
                                job.postedAt
                            }}
                        </span>
                    </div>
                </div>

                <div class="mt-8 flex justify-center">
                    <button
                        class="rounded-2xl border border-slate-200 bg-white px-8 py-4 text-xs font-black text-lokak-text uppercase italic shadow-sm transition-all hover:border-lokak-brand hover:text-lokak-brand"
                    >
                        MUAT LEBIH BANYAK
                    </button>
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
