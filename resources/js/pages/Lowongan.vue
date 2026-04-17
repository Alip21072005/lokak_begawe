<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import {
    Search,
    MapPin,
    Layers,
    Clock,
    ChevronRight,
    SlidersHorizontal,
} from 'lucide-vue-next';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

// Solusi supaya TypeScript tidak protes soal variabel global 'route'
declare const route: any;

const searchForm = useForm({
    keyword: '',
    category: '',
    location: '',
});

const handleSearch = () => {
    console.log('Searching for:', searchForm.data());
};

const lowongan = [
    {
        id: 1,
        title: 'FRONTEND DEVELOPER',
        company: 'UNIVERSITAS DEHASEN',
        salary: 'Rp 8-12 Juta',
        location: 'KOTA BENGKULU',
        type: 'Full Time',
        icon: '🎓',
    },
    {
        id: 2,
        title: 'UI/UX DESIGNER',
        company: 'CODE 21 BENGKULU',
        salary: 'Rp 7-10 Juta',
        location: 'MUKOMUKO',
        type: 'Remote',
        icon: '💎',
    },
    {
        id: 3,
        title: 'CYBER SECURITY',
        company: 'DECODE BENGKULU',
        salary: 'Rp 9-15 Juta',
        location: 'KOTA BENGKULU',
        type: 'Full Time',
        icon: '💻',
    },
    {
        id: 4,
        title: 'AI SPECIALIST',
        company: 'PHINCON ACADEMY',
        salary: 'Rp 15-25 Juta',
        location: 'MANNA',
        type: 'Contract',
        icon: '🤖',
    },
];
</script>

<template>
    <Head title="Cari Lowongan - Lokak Begawe" />

    <div
        class="mt-12 min-h-screen overflow-x-hidden bg-lokak-bg font-sans text-lokak-text selection:bg-lokak-brand selection:text-white"
    >
        <Navbar />

        <main class="w-full px-6 py-12 md:px-16 lg:px-24 xl:px-32">
            <section class="mb-20">
                <div class="mb-12 flex flex-col items-start space-y-4">
                    <h1
                        class="text-4xl font-black tracking-tighter text-lokak-text uppercase italic md:text-6xl"
                    >
                        CARI <span class="text-lokak-brand">LOWONGAN</span>
                    </h1>
                    <div class="h-2 w-32 rounded-full bg-lokak-brand"></div>
                    <p
                        class="max-w-2xl text-xs font-bold tracking-widest text-slate-400 uppercase italic"
                    >
                        Temukan masa depanmu di tanah Bengkulu. Ribuan peluang
                        karir menunggumu untuk dieksplorasi sekarang juga.
                    </p>
                </div>

                <div
                    class="rounded-[2.5rem] border border-slate-100 bg-white p-6 shadow-2xl shadow-slate-200/50 md:p-10"
                >
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-12">
                        <div class="relative lg:col-span-5">
                            <Search
                                class="absolute top-1/2 left-4 h-4 w-4 -translate-y-1/2 text-slate-400"
                            />
                            <input
                                v-model="searchForm.keyword"
                                type="text"
                                placeholder="Cari posisi (Contoh: Web Developer)"
                                class="h-14 w-full rounded-2xl border border-slate-100 bg-slate-50 pr-4 pl-11 text-xs font-bold transition-all outline-none focus:border-lokak-brand focus:bg-white focus:ring-4 focus:ring-lokak-brand/5"
                            />
                        </div>
                        <div class="relative lg:col-span-3">
                            <Layers
                                class="absolute top-1/2 left-4 h-4 w-4 -translate-y-1/2 text-slate-400"
                            />
                            <select
                                v-model="searchForm.category"
                                class="h-14 w-full appearance-none rounded-2xl border border-slate-100 bg-slate-50 pr-4 pl-11 text-xs font-bold text-slate-500 transition-all outline-none focus:border-lokak-brand focus:bg-white"
                            >
                                <option value="">Semua Kategori</option>
                                <option value="it">IT & Software</option>
                                <option value="design">Design</option>
                            </select>
                        </div>
                        <div class="relative lg:col-span-2">
                            <MapPin
                                class="absolute top-1/2 left-4 h-4 w-4 -translate-y-1/2 text-slate-400"
                            />
                            <input
                                v-model="searchForm.location"
                                type="text"
                                placeholder="Lokasi"
                                class="h-14 w-full rounded-2xl border border-slate-100 bg-slate-50 pr-4 pl-11 text-xs font-bold transition-all outline-none focus:border-lokak-brand focus:bg-white focus:ring-4 focus:ring-lokak-brand/5"
                            />
                        </div>
                        <button
                            @click="handleSearch"
                            class="flex h-14 items-center justify-center gap-3 rounded-2xl bg-lokak-brand text-xs font-black text-white uppercase italic shadow-xl shadow-sky-900/20 transition-all hover:-translate-y-1 hover:bg-lokak-brand-dark lg:col-span-2"
                        >
                            CARI LOKER
                        </button>
                    </div>
                </div>
            </section>

            <section class="mb-24">
                <div class="mb-12 flex items-center justify-between">
                    <div class="flex flex-col">
                        <h2
                            class="text-xl font-black tracking-tight text-lokak-text uppercase italic"
                        >
                            LOWONGAN
                            <span class="text-lokak-brand">TERSEDIA</span>
                        </h2>
                        <div
                            class="mt-2 h-1.5 w-16 rounded-full bg-slate-200"
                        ></div>
                    </div>
                    <div class="flex items-center gap-4">
                        <button
                            class="flex items-center gap-2 rounded-xl border border-slate-100 bg-white px-4 py-2 text-[10px] font-black text-slate-400 uppercase italic transition-colors hover:text-lokak-brand"
                        >
                            <SlidersHorizontal class="h-3.5 w-3.5" /> Urutkan
                        </button>
                    </div>
                </div>

                <div
                    class="job-grid grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5"
                >
                    <div
                        v-for="job in lowongan"
                        :key="job.id"
                        class="group relative flex flex-col rounded-[2.5rem] border border-slate-100 bg-white p-8 shadow-sm transition-all duration-500 hover:-translate-y-2 hover:border-sky-300 hover:shadow-2xl hover:shadow-sky-900/10"
                    >
                        <span
                            class="absolute top-8 right-8 rounded-full bg-sky-50 px-3 py-1 text-[9px] font-black tracking-widest text-lokak-brand uppercase italic"
                        >
                            Terbaru
                        </span>

                        <div
                            class="mb-8 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-3xl shadow-inner transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6"
                        >
                            {{ job.icon }}
                        </div>

                        <h3
                            class="mb-1 text-base font-black text-lokak-text uppercase italic transition-colors group-hover:text-lokak-brand"
                        >
                            {{ job.title }}
                        </h3>
                        <p
                            class="mb-6 text-[10px] font-bold tracking-wide text-slate-400 uppercase italic"
                        >
                            {{ job.company }}
                        </p>

                        <div class="mb-10 flex flex-wrap gap-2">
                            <span
                                class="rounded-lg bg-slate-100 px-3 py-1 text-[9px] font-black text-slate-500 uppercase italic"
                            >
                                <Clock class="mr-1 inline h-2.5 w-2.5" />
                                {{ job.type }}
                            </span>
                        </div>

                        <div
                            class="mt-auto flex items-center justify-between border-t border-slate-50 pt-6"
                        >
                            <div class="flex flex-col">
                                <span
                                    class="mb-0.5 text-[9px] font-bold text-slate-400 uppercase italic"
                                    >Penawaran Gaji</span
                                >
                                <span
                                    class="text-sm font-black text-lokak-brand italic"
                                    >{{ job.salary }}</span
                                >
                            </div>
                            <span
                                class="text-[9px] font-bold text-slate-400 uppercase italic"
                                >📍 {{ job.location }}</span
                            >
                        </div>

                        <Link
                            href= "/detail/detaillowongan"
                            class="mt-8 flex w-full items-center justify-center gap-2 rounded-2xl bg-[#0f172a] py-4 text-[10px] font-black text-white uppercase italic transition-all hover:bg-lokak-brand active:scale-95"
                        >
                            LIHAT DETAIL <ChevronRight class="h-3 w-3" />
                        </Link>

                    </div>
                </div>
            </section>
            <div class="mt-12 flex items-center justify-center gap-3">
                <button
                    class="flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-100 bg-white text-slate-400 shadow-sm transition-all hover:border-lokak-brand hover:text-lokak-brand"
                >
                    ←
                </button>
                <div class="flex gap-2">
                    <button
                        v-for="n in 3"
                        :key="n"
                        :class="
                            n === 3
                                ? 'bg-lokak-brand text-white shadow-lg shadow-sky-900/20'
                                : 'border border-slate-100 bg-white text-slate-400'
                        "
                        class="flex h-12 w-12 items-center justify-center rounded-2xl text-xs font-black transition-all hover:border-lokak-brand hover:text-lokak-brand"
                    >
                        {{ n }}
                    </button>
                    <span
                        class="flex h-12 w-12 items-center justify-center text-slate-300"
                        >...</span
                    >
                </div>
                <button
                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-lokak-brand text-white shadow-lg shadow-sky-900/20 transition-all hover:bg-lokak-brand-dark"
                >
                    →
                </button>
            </div>
        </main>

        <Footer />
    </div>
</template>

<style scoped>
*:focus {
    outline: none !important;
}

.transition-all {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.job-grid > div {
    animation: fadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1) backwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.job-grid > div:nth-child(1) {
    animation-delay: 0.1s;
}
.job-grid > div:nth-child(2) {
    animation-delay: 0.2s;
}
.job-grid > div:nth-child(3) {
    animation-delay: 0.3s;
}
.job-grid > div:nth-child(4) {
    animation-delay: 0.4s;
}
</style>
