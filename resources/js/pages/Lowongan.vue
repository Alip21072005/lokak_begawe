<!-- eslint-disable @typescript-eslint/no-unused-vars -->
<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Search, MapPin, Layers, Clock, ChevronRight, SlidersHorizontal } from 'lucide-vue-next';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

const props = defineProps<{
    lowongans: { data: Array<any>; links: Array<any>; };
    lokasis: Array<any>;
    kategoris: Array<any>;
    filters: any;
}>();

const searchForm = useForm({
    keyword: props.filters?.keyword || '',
    category: props.filters?.category || '',
    location: props.filters?.location || '',
});

const handleSearch = () => {
    searchForm.get('/lowongan', { preserveState: true, replace: true });
};

const formatRupiah = (v: any) => {
    if (!v || v == 0) {
return 'Bersaing';
}

    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(v);
};
</script>

<template>
    <Head title="Cari Lowongan - Lokak Begawe" />
    <div class="mt-12 min-h-screen bg-lokak-bg font-sans text-lokak-text">
        <Navbar />
        <main class="w-full px-6 py-12 md:px-16 lg:px-24 xl:px-32">
            
            <section class="mb-20">
                <div class="mb-12 flex flex-col items-start space-y-4">
                    <h1 class="text-4xl font-black tracking-tighter text-lokak-text uppercase italic md:text-6xl">
                        CARI <span class="text-lokak-brand">LOWONGAN</span>
                    </h1>
                    <div class="h-2 w-32 rounded-full bg-lokak-brand"></div>
                    <p class="max-w-2xl text-xs font-bold tracking-widest text-slate-400 uppercase italic leading-relaxed">
                        Temukan masa depanmu di tanah Bengkulu. Ribuan peluang
                        karir menunggumu untuk dieksplorasi sekarang juga.
                    </p>
                </div>

                <div class="group relative rounded-[3rem] border border-slate-100 bg-white p-4 shadow-2xl shadow-slate-200/60 transition-all duration-500 hover:border-sky-200 md:p-6 lg:p-8">
                    <div class="absolute -top-10 -right-10 -z-10 h-40 w-40 rounded-full bg-sky-50 opacity-50 blur-3xl transition-all group-hover:bg-sky-100"></div>
                    
                    <form @submit.prevent="handleSearch" class="grid grid-cols-1 gap-6 lg:grid-cols-12">
                        <div class="relative lg:col-span-4">
                            <label class="mb-2 ml-4 block text-[10px] font-black tracking-widest text-slate-400 uppercase">Posisi Pekerjaan</label>
                            <div class="relative">
                                <Search class="absolute top-1/2 left-5 h-4 w-4 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-lokak-brand" />
                                <input
                                    v-model="searchForm.keyword"
                                    type="text"
                                    placeholder="Cari posisi atau keahlian..."
                                    class="h-16 w-full rounded-4xl border border-slate-100 bg-slate-50 pr-6 pl-14 text-xs font-bold transition-all outline-none focus:border-lokak-brand focus:bg-white focus:ring-4 focus:ring-lokak-brand/5"
                                />
                            </div>
                        </div>

                        <div class="relative lg:col-span-3">
                            <label class="mb-2 ml-4 block text-[10px] font-black tracking-widest text-slate-400 uppercase">Kategori</label>
                            <div class="relative">
                                <Layers class="absolute top-1/2 left-5 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                <select
                                    v-model="searchForm.category"
                                    class="h-16 w-full appearance-none rounded-4xl border border-slate-100 bg-slate-50 pr-10 pl-14 text-xs font-bold text-slate-700 transition-all outline-none focus:border-lokak-brand focus:bg-white"
                                >
                                    <option value="">Semua Kategori</option>
                                    <option v-for="kat in kategoris" :key="kat.id" :value="kat.id">{{ kat.nama_kategori }}</option>
                                </select>
                                <ChevronRight class="absolute top-1/2 right-5 h-4 w-4 -translate-y-1/2 rotate-90 text-slate-300 pointer-events-none" />
                            </div>
                        </div>

                        <div class="relative lg:col-span-3">
                            <label class="mb-2 ml-4 block text-[10px] font-black tracking-widest text-slate-400 uppercase">Wilayah Bengkulu</label>
                            <div class="relative">
                                <MapPin class="absolute top-1/2 left-5 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                <select
                                    v-model="searchForm.location"
                                    class="h-16 w-full appearance-none rounded-4xl border border-slate-100 bg-slate-50 pr-10 pl-14 text-xs font-bold text-slate-700 transition-all outline-none focus:border-lokak-brand focus:bg-white"
                                >
                                    <option value="">Semua Lokasi</option>
                                    <option v-for="lok in lokasis" :key="lok.id" :value="lok.id">{{ lok.nama_lokasi }}</option>
                                </select>
                                <ChevronRight class="absolute top-1/2 right-5 h-4 w-4 -translate-y-1/2 rotate-90 text-slate-300 pointer-events-none" />
                            </div>
                        </div>

                        <div class="flex items-end lg:col-span-2">
                            <button
                                type="submit"
                                class="flex h-16 w-full items-center justify-center gap-3 rounded-4xl bg-lokak-brand text-xs font-black text-white uppercase italic shadow-xl shadow-sky-900/20 transition-all duration-300 hover:-translate-y-1 hover:bg-sky-600 active:scale-95"
                            >
                                <span>CARI</span>
                                <Search class="h-4 w-4" />
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
                <div v-for="job in props.lowongans.data" :key="job.id" class="group relative flex flex-col rounded-[2.5rem] border border-slate-100 bg-white p-8 shadow-sm transition-all duration-500 hover:-translate-y-2 hover:border-sky-300 hover:shadow-2xl hover:shadow-sky-900/10">
                    <div class="mb-8 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-3xl shadow-inner transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6">💼</div>
                    <h3 class="mb-1 text-base font-black text-lokak-text uppercase italic transition-colors group-hover:text-lokak-brand">{{ job.judul_lowongan }}</h3>
                    <p class="mb-6 text-[10px] font-bold text-slate-400 uppercase italic">{{ job.mitra?.nama_mitra || 'Perusahaan Bengkulu' }}</p>
                    
                    <div class="mb-8 flex flex-wrap gap-2">
                        <span class="rounded-lg bg-slate-50 px-3 py-1 text-[9px] font-black text-slate-500 uppercase italic">
                            <Clock class="mr-1 inline h-2.5 w-2.5 text-lokak-brand" /> {{ job.tipe_pekerjaan }}
                        </span>
                    </div>

                    <div class="mt-auto flex items-center justify-between border-t border-slate-50 pt-6">
                        <div class="flex flex-col">
                            <span class="mb-0.5 text-[9px] font-bold text-slate-400 uppercase italic">Estimasi Gaji</span>
                            <span class="text-sm font-black text-lokak-brand italic">{{ formatRupiah(job.gaji_min) }}</span>
                        </div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase italic">📍 {{ job.lokasi?.nama_lokasi || 'Bengkulu' }}</span>
                    </div>
                    
                    <Link :href="'/detail/lowongan/' + job.id" class="group/btn mt-8 flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-900 py-4 text-[10px] font-black text-white uppercase italic transition-all hover:bg-lokak-brand active:scale-95">
                        LIHAT DETAIL <ChevronRight class="h-3 w-3 transition-transform group-hover/btn:translate-x-1" />
                    </Link>
                </div>
            </div>

            <div v-if="props.lowongans.links.length > 3" class="mt-16 flex items-center justify-center gap-3">
                <template v-for="(link, k) in props.lowongans.links" :key="k">
                    <div v-if="!link.url" class="flex h-12 min-w-12 items-center justify-center rounded-2xl border border-slate-100 bg-white px-4 text-xs font-black text-slate-300 opacity-50" v-html="link.label"></div>
                    <Link v-else :href="link.url" :class="['flex h-12 min-w-12 items-center justify-center rounded-2xl px-4 text-xs font-black transition-all duration-300', link.active ? 'bg-lokak-brand text-white shadow-lg shadow-sky-900/20' : 'border border-slate-100 bg-white text-slate-400 hover:border-lokak-brand hover:text-lokak-brand']">
                        <span v-html="link.label"></span>
                    </Link>
                </template>
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
</style>