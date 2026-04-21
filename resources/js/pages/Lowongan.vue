<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
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

// Definisikan Props untuk menangkap data dari Controller
const props = defineProps<{
    lowongans: {
        data: Array<any>;
        links: Array<any>;
    };
}>();

const searchForm = useForm({
    keyword: '',
    category: '',
    location: '',
});

const handleSearch = () => {
    searchForm.get(route('lowongan'), {
        preserveState: true,
        replace: true,
    });
};

// Helper format Rupiah biar gak pusing liat nol banyak
const formatRupiah = (value: any) => {
    if (!value) return 'Bersaing';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
};
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
                    <form
                        @submit.prevent="handleSearch"
                        class="grid grid-cols-1 gap-4 lg:grid-cols-12"
                    >
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
                            type="submit"
                            class="flex h-14 items-center justify-center gap-3 rounded-2xl bg-lokak-brand text-xs font-black text-white uppercase italic shadow-xl shadow-sky-900/20 transition-all hover:-translate-y-1 hover:bg-lokak-brand-dark lg:col-span-2"
                        >
                            CARI LOKER
                        </button>
                    </form>
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
                        v-for="job in props.lowongans.data"
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
                            💼
                        </div>

                        <h3
                            class="mb-1 text-base font-black text-lokak-text uppercase italic transition-colors group-hover:text-lokak-brand"
                        >
                            {{ job.judul_lowongan }}
                        </h3>
                        <p
                            class="mb-6 text-[10px] font-bold tracking-wide text-slate-400 uppercase italic"
                        >
                            {{
                                job.mitra?.nama_mitra ||
                                'Perusahaan di Bengkulu'
                            }}
                        </p>

                        <div class="mb-10 flex flex-wrap gap-2">
                            <span
                                class="rounded-lg bg-slate-100 px-3 py-1 text-[9px] font-black text-slate-500 uppercase italic"
                            >
                                <Clock class="mr-1 inline h-2.5 w-2.5" />
                                {{ job.tipe_pekerjaan }}
                            </span>
                        </div>

                        <div
                            class="mt-auto flex items-center justify-between border-t border-slate-50 pt-6"
                        >
                            <div class="flex flex-col">
                                <span
                                    class="mb-0.5 text-[9px] font-bold text-slate-400 uppercase italic"
                                    >Estimasi Gaji</span
                                >
                                <span
                                    class="text-sm font-black text-lokak-brand italic"
                                >
                                    {{ formatRupiah(job.gaji_min) }}
                                </span>
                            </div>
                            <span
                                class="text-[9px] font-bold text-slate-400 uppercase italic"
                                >📍
                                {{
                                    job.lokasi?.nama_lokasi || 'Bengkulu'
                                }}</span
                            >
                        </div>

                        <Link
                            :href="route('detail.detaillowongan')"
                            class="mt-8 flex w-full items-center justify-center gap-2 rounded-2xl bg-[#0f172a] py-4 text-[10px] font-black text-white uppercase italic transition-all hover:bg-lokak-brand active:scale-95"
                        >
                            LIHAT DETAIL <ChevronRight class="h-3 w-3" />
                        </Link>
                    </div>
                </div>

                <div
                    v-if="props.lowongans.links.length > 3"
                    class="mt-12 flex items-center justify-center gap-3"
                >
                    <Link
                        v-for="(link, k) in props.lowongans.links"
                        :key="k"
                        :href="link.url"
                        v-html="link.label"
                        :class="[
                            'flex h-12 min-w-[3rem] items-center justify-center rounded-2xl px-4 text-xs font-black transition-all',
                            link.active
                                ? 'bg-lokak-brand text-white shadow-lg shadow-sky-900/20'
                                : 'border border-slate-100 bg-white text-slate-400 hover:text-lokak-brand',
                            !link.url ? 'pointer-events-none opacity-50' : '',
                        ]"
                    />
                </div>
            </section>
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
</style>
