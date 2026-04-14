<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    Download,
    Calendar,
    BadgeCheck,
    DollarSign,
    Briefcase,
    AlertCircle,
    Check,
    X,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

// Data Dummy Tawaran Kerja
const offerings = [
    {
        id: 1,
        position: 'Frontend Developer',
        company: 'Universitas Dehasen',
        salary: 'Rp 8.500.000',
        dateSent: '14 April 2026',
        deadline: '20 April 2026',
        status: 'Pending',
        description:
            'Kami sangat terkesan dengan portofolio dan hasil tes teknis Anda. Kami ingin menawarkan posisi Frontend Developer dengan skema kerja full-time.',
    },
];
</script>

<template>
    <Head title="Tawaran Kerja - Lokak Begawe" />

    <div class="space-y-8 p-6 lg:p-10">
        <div
            class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <h1
                    class="text-3xl font-black tracking-tighter text-lokak-text uppercase italic md:text-4xl"
                >
                    TAWARAN <span class="text-lokak-brand">KERJA</span>
                </h1>
                <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
                <p
                    class="mt-4 text-[11px] font-bold tracking-widest text-lokak-text-muted uppercase italic"
                >
                    Selamat! Perusahaan telah memilihmu. Tinjau penawaran mereka
                    di bawah ini.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div class="lg:col-span-8">
                <div
                    v-for="offer in offerings"
                    :key="offer.id"
                    class="overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white shadow-sm transition-all hover:border-sky-200 hover:shadow-xl"
                >
                    <div class="border-b border-slate-50 bg-slate-50/50 p-8">
                        <div
                            class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
                        >
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-2xl shadow-sm"
                                >
                                    🎓
                                </div>
                                <div>
                                    <h2
                                        class="text-lg leading-tight font-black text-lokak-text uppercase"
                                    >
                                        {{ offer.position }}
                                    </h2>
                                    <p
                                        class="text-xs font-bold tracking-wide text-lokak-brand uppercase italic"
                                    >
                                        {{ offer.company }}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex items-center gap-2 rounded-xl bg-amber-100 px-4 py-2 text-[10px] font-black text-amber-700 uppercase italic"
                            >
                                <AlertCircle class="h-3 w-3" /> Batas Waktu:
                                {{ offer.deadline }}
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div
                                class="flex items-center gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-4"
                            >
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-emerald-500 shadow-sm"
                                >
                                    <DollarSign class="h-5 w-5" />
                                </div>
                                <div>
                                    <p
                                        class="text-[9px] font-black text-slate-400 uppercase italic"
                                    >
                                        Gaji Ditawarkan
                                    </p>
                                    <p
                                        class="text-sm font-black text-lokak-text"
                                    >
                                        {{ offer.salary }}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex items-center gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-4"
                            >
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-sky-500 shadow-sm"
                                >
                                    <Calendar class="h-5 w-5" />
                                </div>
                                <div>
                                    <p
                                        class="text-[9px] font-black text-slate-400 uppercase italic"
                                    >
                                        Tanggal Penawaran
                                    </p>
                                    <p
                                        class="text-sm font-black text-lokak-text"
                                    >
                                        {{ offer.dateSent }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3
                                class="text-xs font-black tracking-widest text-lokak-text uppercase italic"
                            >
                                Surat Penawaran:
                            </h3>
                            <p
                                class="text-xs leading-loose font-medium text-slate-600 italic"
                            >
                                {{ offer.description }}
                            </p>
                        </div>

                        <div class="mt-10 flex flex-col gap-4 sm:flex-row">
                            <button
                                class="flex flex-1 items-center justify-center gap-2 rounded-2xl bg-lokak-brand py-4 text-xs font-black text-white uppercase italic shadow-lg shadow-sky-900/20 transition-all hover:bg-lokak-brand-dark active:scale-95"
                            >
                                <Check class="h-4 w-4" /> TERIMA PEKERJAAN
                            </button>
                            <button
                                class="flex flex-1 items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white py-4 text-xs font-black text-rose-500 uppercase italic transition-all hover:border-rose-100 hover:bg-rose-50"
                            >
                                <X class="h-4 w-4" /> TOLAK PENAWARAN
                            </button>
                            <button
                                class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-500 transition-all hover:bg-slate-200 hover:text-lokak-text"
                            >
                                <Download class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6 lg:col-span-4">
                <div
                    class="rounded-[2.5rem] bg-slate-900 p-8 text-white shadow-xl shadow-slate-900/20"
                >
                    <div
                        class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10"
                    >
                        <BadgeCheck class="h-6 w-6 text-emerald-400" />
                    </div>
                    <h3
                        class="text-lg leading-tight font-black tracking-tighter uppercase italic"
                    >
                        Verifikasi Tawaran
                    </h3>
                    <p
                        class="mt-3 text-[10px] leading-relaxed font-medium uppercase italic opacity-60"
                    >
                        Semua tawaran kerja di Lokak Begawe telah melewati tahap
                        verifikasi sistem untuk memastikan keamanan dan keaslian
                        mitra perusahaan.
                    </p>
                </div>

                <div
                    class="rounded-[2.5rem] border border-dashed border-slate-200 p-8 text-center"
                >
                    <div class="mb-4 flex justify-center">
                        <Briefcase class="h-10 w-10 text-slate-200" />
                    </div>
                    <h4
                        class="text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                    >
                        Butuh Bantuan?
                    </h4>
                    <p
                        class="mt-2 text-[9px] leading-relaxed font-bold text-slate-300 uppercase italic"
                    >
                        Jika ada ketidaksesuaian data, silakan hubungi tim
                        bantuan Lokak Begawe.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Transisi halus */
.rounded-3xl {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
