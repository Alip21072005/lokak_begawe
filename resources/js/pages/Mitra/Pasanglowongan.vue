<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    Send,
    Briefcase,
    DollarSign,
    FileText,
    AlertCircle,
    Eye,
} from 'lucide-vue-next';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

// State untuk Form (Contoh Reactive)
const form = ref({
    title: '',
    category: '',
    type: 'Full-time',
    salary_min: '',
    salary_max: '',
    location: '',
    description: '',
});
</script>

<template>
    <Head title="Pasang Lowongan - Lokak Begawe" />

    <div class="space-y-8 p-6 lg:p-10">
        <div class="flex flex-col items-start">
            <h1
                class="text-3xl font-black tracking-tighter text-lokak-text uppercase italic md:text-4xl"
            >
                PASANG <span class="text-lokak-brand">LOWONGAN</span>
            </h1>
            <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
            <p
                class="mt-4 text-[11px] font-bold tracking-widest text-lokak-text-muted uppercase italic"
            >
                Temukan talenta terbaik Bengkulu dengan informasi pekerjaan yang
                jelas.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
            <div class="lg:col-span-8">
                <div
                    class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm lg:p-10"
                >
                    <form @submit.prevent class="space-y-8">
                        <div class="space-y-6">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-50 text-lokak-brand"
                                >
                                    <Briefcase class="h-4 w-4" />
                                </div>
                                <h2
                                    class="text-xs font-black tracking-widest text-lokak-text uppercase italic"
                                >
                                    Informasi Dasar
                                </h2>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                                        >Judul Posisi</label
                                    >
                                    <input
                                        v-model="form.title"
                                        type="text"
                                        placeholder="Contoh: Frontend Developer"
                                        class="w-full rounded-xl border-slate-200 bg-slate-50 p-4 text-xs font-bold transition-all outline-none focus:border-lokak-brand focus:bg-white"
                                    />
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                                        >Tipe Pekerjaan</label
                                    >
                                    <select
                                        v-model="form.type"
                                        class="w-full rounded-xl border-slate-200 bg-slate-50 p-4 text-xs font-bold transition-all outline-none focus:border-lokak-brand focus:bg-white"
                                    >
                                        <option>Full-time</option>
                                        <option>Part-time</option>
                                        <option>Contract</option>
                                        <option>Internship</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-500"
                                >
                                    <DollarSign class="h-4 w-4" />
                                </div>
                                <h2
                                    class="text-xs font-black tracking-widest text-lokak-text uppercase italic"
                                >
                                    Gaji & Lokasi
                                </h2>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                                        >Rentang Gaji (Min)</label
                                    >
                                    <input
                                        v-model="form.salary_min"
                                        type="number"
                                        placeholder="Rp 3.000.000"
                                        class="w-full rounded-xl border-slate-200 bg-slate-50 p-4 text-xs font-bold transition-all outline-none focus:border-lokak-brand focus:bg-white"
                                    />
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                                        >Lokasi Penempatan</label
                                    >
                                    <input
                                        v-model="form.location"
                                        type="text"
                                        placeholder="Contoh: Kota Bengkulu"
                                        class="w-full rounded-xl border-slate-200 bg-slate-50 p-4 text-xs font-bold transition-all outline-none focus:border-lokak-brand focus:bg-white"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-50 text-purple-500"
                                >
                                    <FileText class="h-4 w-4" />
                                </div>
                                <h2
                                    class="text-xs font-black tracking-widest text-lokak-text uppercase italic"
                                >
                                    Deskripsi Pekerjaan
                                </h2>
                            </div>
                            <div class="space-y-2">
                                <textarea
                                    v-model="form.description"
                                    rows="6"
                                    placeholder="Sebutkan syarat, tanggung jawab, dan kualifikasi..."
                                    class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-xs font-bold transition-all outline-none focus:border-lokak-brand focus:bg-white"
                                ></textarea>
                            </div>
                        </div>

                        <div class="pt-6">
                            <button
                                class="flex w-full items-center justify-center gap-3 rounded-2xl bg-lokak-brand py-5 text-sm font-black text-white uppercase italic shadow-xl shadow-sky-900/20 transition-all hover:-translate-y-1 hover:bg-lokak-brand-dark active:scale-95"
                            >
                                <Send class="h-5 w-5" /> TAYANGKAN LOWONGAN
                                SEKARANG
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="space-y-6 lg:col-span-4">
                <div
                    class="rounded-[2.5rem] bg-slate-900 p-8 text-white shadow-xl"
                >
                    <div
                        class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-white/10"
                    >
                        <AlertCircle class="h-5 w-5 text-amber-400" />
                    </div>
                    <h3
                        class="text-sm font-black tracking-tighter uppercase italic"
                    >
                        Tips Loker Populer
                    </h3>
                    <ul
                        class="mt-4 space-y-3 text-[10px] leading-relaxed font-medium uppercase italic opacity-60"
                    >
                        <li>• Cantumkan rentang gaji yang kompetitif.</li>
                        <li>• Gunakan judul posisi yang spesifik.</li>
                        <li>• Jelaskan budaya kerja di perusahaan Anda.</li>
                    </ul>
                </div>

                <div
                    class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm"
                >
                    <div class="mb-4 flex items-center justify-between">
                        <h3
                            class="text-[10px] font-black tracking-widest text-lokak-text uppercase italic"
                        >
                            Live Preview
                        </h3>
                        <Eye class="h-4 w-4 text-slate-300" />
                    </div>
                    <div
                        class="rounded-2xl border border-slate-50 bg-slate-50 p-6"
                    >
                        <h4
                            class="text-sm font-black text-lokak-text uppercase"
                        >
                            {{ form.title || 'Judul Lowongan' }}
                        </h4>
                        <p
                            class="mt-1 text-[9px] font-bold text-lokak-brand uppercase italic"
                        >
                            {{ form.type }} • {{ form.location || 'Lokasi' }}
                        </p>
                        <div
                            class="mt-4 h-1.5 w-full rounded-full bg-slate-200"
                        ></div>
                        <div
                            class="mt-2 h-1.5 w-2/3 rounded-full bg-slate-200"
                        ></div>
                    </div>
                    <p
                        class="mt-4 text-center text-[9px] font-bold text-slate-300 uppercase italic"
                    >
                        Tampilan kartu loker Anda
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>
